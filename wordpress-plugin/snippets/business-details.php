<?php
/**
 * MVP Business Details: adds Electrician structured data (license, hours, service area, services)
 * to every page for Google, Gemini and other AI assistants, and serves /llms.txt.
 * Generated from seo/schema-electrician.html and seo/llms.txt. Installed via the Code Snippets plugin.
 */
add_action(
	'wp_head',
	function () {
		echo "\n<script type=\"application/ld+json\">" . <<<'JSONLD'
{"@context":"https://schema.org","@type":"Electrician","@id":"https://mvp-electric.com/#electrician","name":"MVP Electric","url":"https://mvp-electric.com/","logo":"https://mvp-electric.com/wp-content/uploads/2024/09/MVP-Electric-Logo.png","image":"https://mvp-electric.com/wp-content/uploads/2024/11/electric_panel.jpg","telephone":"+1-503-922-6873","email":"james@mvp-electric.com","description":"Licensed, bonded and insured residential and commercial electrician serving the Portland, Oregon metro area: Multnomah, Washington and Clackamas counties. Electrical repair and troubleshooting, panel upgrades, generator installation and interlocks, EV charger installation, LED lighting retrofits, solar and battery backup, and new construction wiring. Oregon CCB #253767, Oregon electrical contractor license #C2008.","founder":{"@type":"Person","name":"James Thorp"},"identifier":[{"@type":"PropertyValue","name":"Oregon CCB License","value":"253767"},{"@type":"PropertyValue","name":"Oregon Electrical Contractor License","value":"C2008"}],"priceRange":"$$","address":{"@type":"PostalAddress","addressLocality":"Portland","addressRegion":"OR","addressCountry":"US"},"areaServed":[{"@type":"AdministrativeArea","name":"Multnomah County, Oregon"},{"@type":"AdministrativeArea","name":"Washington County, Oregon"},{"@type":"AdministrativeArea","name":"Clackamas County, Oregon"},{"@type":"City","name":"Portland, OR"},{"@type":"City","name":"Gresham, OR"},{"@type":"City","name":"Troutdale, OR"},{"@type":"City","name":"Beaverton, OR"},{"@type":"City","name":"Hillsboro, OR"},{"@type":"City","name":"Tigard, OR"},{"@type":"City","name":"Tualatin, OR"},{"@type":"City","name":"Sherwood, OR"},{"@type":"City","name":"Forest Grove, OR"},{"@type":"City","name":"Cornelius, OR"},{"@type":"City","name":"Lake Oswego, OR"},{"@type":"City","name":"West Linn, OR"},{"@type":"City","name":"Oregon City, OR"},{"@type":"City","name":"Happy Valley, OR"},{"@type":"City","name":"Milwaukie, OR"},{"@type":"City","name":"Wilsonville, OR"},{"@type":"City","name":"Clackamas, OR"},{"@type":"City","name":"Gladstone, OR"},{"@type":"City","name":"Canby, OR"}],"knowsAbout":["Electrical repair and troubleshooting","Electrical panel upgrades","Whole-home generator installation","Generator interlock kits and inlets","Level 2 EV charger installation","LED lighting retrofits","Recessed (can) lighting","GFCI and AFCI outlets","Solar panel and battery backup installation","New construction electrical wiring"],"hasOfferCatalog":{"@type":"OfferCatalog","name":"Electrical Services","itemListElement":[{"@type":"Offer","itemOffered":{"@type":"Service","name":"Electrical Repair & Troubleshooting","url":"https://mvp-electric.com/general-electrical-services/"}},{"@type":"Offer","itemOffered":{"@type":"Service","name":"EV Charger Installation","url":"https://mvp-electric.com/ev-charging-station-installation/"}},{"@type":"Offer","itemOffered":{"@type":"Service","name":"Lighting Retrofits","url":"https://mvp-electric.com/lighting-retrofits/"}},{"@type":"Offer","itemOffered":{"@type":"Service","name":"Solar Panel Installation","url":"https://mvp-electric.com/solar-panel-installation/"}},{"@type":"Offer","itemOffered":{"@type":"Service","name":"New Construction Electrical Work","url":"https://mvp-electric.com/new-construction-electrical-work/"}},{"@type":"Offer","itemOffered":{"@type":"Service","name":"Panel Upgrades","url":"https://mvp-electric.com/panel-upgrades/"}},{"@type":"Offer","itemOffered":{"@type":"Service","name":"Generator Installation & Interlocks","url":"https://mvp-electric.com/generator-installation/"}}]},"openingHoursSpecification":[{"@type":"OpeningHoursSpecification","dayOfWeek":["Monday","Tuesday","Wednesday","Thursday","Friday"],"opens":"08:00","closes":"17:00"}],"sameAs":["https://www.google.com/search?kgmid=/g/11x1y37m3z","https://www.facebook.com/profile.php?id=61569705050483"],"contactPoint":{"@type":"ContactPoint","telephone":"+1-503-922-6873","contactType":"customer service","areaServed":"US-OR","availableLanguage":"English"}}
JSONLD
		. "</script>\n";
	},
	30
);

add_action(
	'init',
	function () {
		$path = strtok( $_SERVER['REQUEST_URI'] ?? '', '?' );
		if ( '/llms.txt' !== $path ) {
			return;
		}
		header( 'Content-Type: text/plain; charset=utf-8' );
		echo <<<'TXT'
# MVP Electric

> MVP Electric is a licensed, bonded and insured electrical contractor (Oregon CCB #253767, Oregon electrical contractor license #C2008) owned and operated by James Thorp. It serves residential and commercial customers across the Portland, Oregon metro area: Multnomah County, Washington County and Clackamas County. It does not serve Salem or Washington state. Call or text (503) 922-6873 for a free estimate.

## Service area
- Multnomah County: Portland, Gresham, Troutdale
- Washington County: Beaverton, Hillsboro, Tigard, Tualatin, Sherwood, Forest Grove, Cornelius
- Clackamas County: Lake Oswego, West Linn, Oregon City, Happy Valley, Milwaukie, Wilsonville, Clackamas, Gladstone, Canby

## Services
- [Electrical repair & troubleshooting](https://mvp-electric.com/general-electrical-services/): outlets, GFCI, switches, dimmers, can lights, circuits, diagnostics
- [EV charger installation](https://mvp-electric.com/ev-charging-station-installation/): Level 2 home and commercial chargers, permit included
- [Lighting retrofits](https://mvp-electric.com/lighting-retrofits/): LED upgrades for homes and businesses
- [Solar panel installation](https://mvp-electric.com/solar-panel-installation/): solar and battery backup
- [New construction electrical](https://mvp-electric.com/new-construction-electrical-work/): new homes, additions, working with general contractors
- [Panel upgrades](https://mvp-electric.com/panel-upgrades/): 100A to 200A upgrades, service changes, Federal Pacific/Zinsco replacements, subpanels
- [Generator installation](https://mvp-electric.com/generator-installation/): interlock kits and inlets, transfer switches, whole-home standby generators, battery backup

## Contact
- Hours: Monday–Friday 8am–5pm; Saturday emergency service available
- Phone / text: (503) 922-6873
- Email: james@mvp-electric.com
- [Request a free estimate](https://mvp-electric.com/contact-us/)
- [Customer reviews](https://mvp-electric.com/reviews/)
TXT;
		exit;
	}
);
