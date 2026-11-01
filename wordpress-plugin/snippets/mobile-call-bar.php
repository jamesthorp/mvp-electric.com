<?php
/**
 * MVP Mobile Call Bar: a Call / Text / Free Estimate bar pinned to the bottom of the screen on phones.
 * Installed as a front-end snippet in the Code Snippets plugin (deactivate there to remove it).
 */
add_action(
	'wp_footer',
	function () {
		?>
<style>
.mvp-callbar{display:none}
@media (max-width:767px){
	body{padding-bottom:64px}
	.mvp-callbar{display:grid;grid-template-columns:1fr 1fr 1fr;position:fixed;left:0;right:0;bottom:0;z-index:9990;
		box-shadow:0 -4px 16px rgba(0,0,0,.18);font-family:Inter,sans-serif;padding-bottom:env(safe-area-inset-bottom);background:#0b1f4b}
	.mvp-callbar a{display:flex;align-items:center;justify-content:center;gap:6px;height:64px;font-size:16px;font-weight:700;
		text-decoration:none;color:#fff;background:#0b1f4b}
	.mvp-callbar a.mvp-call{background:#fcd30d;color:#111}
	.mvp-callbar a.mvp-est{background:#111}
	.mvp-callbar svg{width:20px;height:20px;fill:currentColor;flex:none}
}
</style>
<nav class="mvp-callbar" aria-label="Contact MVP Electric">
	<a class="mvp-call" href="tel:+15039226873"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2a1 1 0 0 1 1-.25 11.4 11.4 0 0 0 3.6.57 1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.25.2 2.45.57 3.6a1 1 0 0 1-.25 1z"/></svg>Call</a>
	<a class="mvp-text" href="sms:+15039226873"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 2H4a2 2 0 0 0-2 2v18l4-4h14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zM7 9h10v2H7zm6 5H7v-2h6zm4-6H7V6h10z"/></svg>Text</a>
	<a class="mvp-est" href="https://mvp-electric.com/contact-us/"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8zm-1 7V3.5L18.5 9zM8 13h8v2H8zm0 4h5v2H8z"/></svg>Estimate</a>
</nav>
		<?php
	}
);
