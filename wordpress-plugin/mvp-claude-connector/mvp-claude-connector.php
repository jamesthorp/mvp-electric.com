<?php
/**
 * Plugin Name: MVP Claude Connector
 * Description: Lets a logged-in editor (using an Application Password) read and update Elementor layouts and Yoast SEO fields through the WordPress REST API, and clears the Elementor and LiteSpeed caches after each change.
 * Version:     1.0.0
 * Author:      MVP Electric
 * License:     GPL-2.0-or-later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const MVP_CC_POST_TYPES = array( 'page', 'post' );
const MVP_CC_META_KEYS  = array(
	'_elementor_data',
	'_elementor_edit_mode',
	'_elementor_template_type',
	'_yoast_wpseo_title',
	'_yoast_wpseo_metadesc',
	'_yoast_wpseo_focuskw',
);

/**
 * Expose the Elementor and Yoast fields to the REST API. Only users who can edit the post may change them.
 */
add_action(
	'init',
	function () {
		foreach ( MVP_CC_POST_TYPES as $post_type ) {
			foreach ( MVP_CC_META_KEYS as $meta_key ) {
				register_post_meta(
					$post_type,
					$meta_key,
					array(
						'type'          => 'string',
						'single'        => true,
						'show_in_rest'  => true,
						'auth_callback' => function ( $allowed, $meta_key, $post_id ) {
							return current_user_can( 'edit_post', $post_id );
						},
					)
				);
			}
		}
	}
);

/**
 * Hide these fields from anonymous visitors so the public API output is unchanged.
 */
foreach ( MVP_CC_POST_TYPES as $mvp_cc_type ) {
	add_filter(
		"rest_prepare_{$mvp_cc_type}",
		function ( $response, $post ) {
			if ( current_user_can( 'edit_post', $post->ID ) ) {
				return $response;
			}
			$data = $response->get_data();
			if ( isset( $data['meta'] ) && is_array( $data['meta'] ) ) {
				foreach ( MVP_CC_META_KEYS as $meta_key ) {
					unset( $data['meta'][ $meta_key ] );
				}
				$response->set_data( $data );
			}
			return $response;
		},
		10,
		2
	);

	/**
	 * After a REST save, clear the page's caches so the change shows up on the live site.
	 */
	add_action(
		"rest_after_insert_{$mvp_cc_type}",
		function ( $post ) {
			mvp_cc_clear_post_caches( $post->ID );
		}
	);
}

function mvp_cc_clear_post_caches( $post_id ) {
	// Elementor regenerates these the next time the page is viewed.
	delete_post_meta( $post_id, '_elementor_css' );
	delete_post_meta( $post_id, '_elementor_element_cache' );
	delete_post_meta( $post_id, '_elementor_page_assets' );
	if ( class_exists( '\Elementor\Plugin' ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}

	// Yoast caches SEO titles in its "indexables" table. Deleting the row makes Yoast rebuild it from the new values.
	try {
		if ( function_exists( 'YoastSEO' ) ) {
			$repository = YoastSEO()->classes->get( \Yoast\WP\SEO\Repositories\Indexable_Repository::class );
			$indexable  = $repository->find_by_id_and_type( $post_id, 'post', false );
			if ( $indexable ) {
				$indexable->delete();
			}
		}
	} catch ( \Throwable $e ) {
		error_log( 'MVP Claude Connector: could not reset Yoast indexable: ' . $e->getMessage() );
	}

	do_action( 'litespeed_purge_post', $post_id );
}

/**
 * POST /wp-json/mvp-cc/v1/purge clears every Elementor and LiteSpeed cache, e.g. after a site-wide change.
 */
add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'mvp-cc/v1',
			'/purge',
			array(
				'methods'             => 'POST',
				'permission_callback' => function () {
					return current_user_can( 'edit_pages' );
				},
				'callback'            => function () {
					if ( class_exists( '\Elementor\Plugin' ) ) {
						\Elementor\Plugin::$instance->files_manager->clear_cache();
					}
					do_action( 'litespeed_purge_all' );
					return array( 'ok' => true );
				},
			)
		);
	}
);
