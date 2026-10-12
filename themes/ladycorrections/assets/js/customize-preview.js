/**
 * Customizer live preview: brand colors update instantly via CSS variables.
 * Vanilla JS, no jQuery.
 */
(function () {
	if ( ! window.wp || ! wp.customize ) {
		return;
	}
	wp.customize( 'lc_primary_color', function ( value ) {
		value.bind( function ( to ) {
			document.documentElement.style.setProperty( '--lc-primary', to );
		} );
	} );
	wp.customize( 'lc_berry_color', function ( value ) {
		value.bind( function ( to ) {
			document.documentElement.style.setProperty( '--lc-berry', to );
		} );
	} );
})();
