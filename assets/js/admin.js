( function ( $ ) {
    'use strict';

    $( document ).on( 'click', '.gent-media-btn', function ( e ) {
        e.preventDefault();
        var $btn     = $( this );
        var $wrap    = $btn.closest( '.gent-media-wrap' );
        var $input   = $wrap.find( '.gent-media-id' );
        var $preview = $wrap.find( '.gent-media-preview' );
        var $remove  = $wrap.find( '.gent-media-remove' );

        var frame = wp.media( {
            title: 'Select Image',
            button: { text: 'Use this image' },
            multiple: false,
            library: { type: 'image' },
        } );

        frame.on( 'select', function () {
            var attachment = frame.state().get( 'selection' ).first().toJSON();
            $input.val( attachment.id );
            $preview.attr( 'src', attachment.url ).show();
            $remove.show();
            $btn.text( 'Change Image' );
        } );

        frame.open();
    } );

    $( document ).on( 'click', '.gent-media-remove', function ( e ) {
        e.preventDefault();
        var $wrap = $( this ).closest( '.gent-media-wrap' );
        $wrap.find( '.gent-media-id' ).val( '' );
        $wrap.find( '.gent-media-preview' ).hide().attr( 'src', '' );
        $wrap.find( '.gent-media-btn' ).text( 'Select Image' );
        $( this ).hide();
    } );

} )( jQuery );
