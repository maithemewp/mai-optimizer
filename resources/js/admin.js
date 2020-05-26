( function( $ ) {
	$( '.mai-optimizer-toggle' ).on( 'click', function() {
		var checkBoxes = $( '.mai-optimizer-form input[type="checkbox"]' );
		checkBoxes.prop( 'checked', ! checkBoxes.prop( 'checked' ) );
	} );
} )( jQuery );
