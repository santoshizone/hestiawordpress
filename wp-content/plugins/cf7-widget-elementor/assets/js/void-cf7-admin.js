(function($) {
    // Hook into the "void-query-promotion-notice" class we added to the notice, so
    // Only listen to YOUR notices being dismissed
    var elNotice = $('.void-query-promotion-notice');

    $( document ).on( 'click', '.void-query-promotion-notice .notice-dismiss', function () {
        // Read the "data-notice" information to track which notice
        // is being dismissed and send it via AJAX
        var type = $( this ).closest( '.void-query-promotion-notice' ).data( 'notice' );
        var nonce = $( this ).closest( '.void-query-promotion-notice' ).data( 'nonce' );

        //elNotice.hide();
        console.log('cross button click');

        // Make an AJAX call
        // Since WP 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        $.ajax({
            // url of ajax request, value of voidCf7Admin.ajaxUrl is localized during enqueue script
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'dismissed_promotional_notice_handler',
                type: type,
                status: 'remind-me-later',
            },
            // wp verify nonce automatically after sending nonce like this
            headers: {
                'X-WP-Nonce': nonce
            },
            dataType: 'json',
        });
    });

    elNotice.find( '.void-query-never-show' ).on( 'click', function (e) {
        e.preventDefault();
        // Read the "data-notice" information to track which notice
        // is being dismissed and send it via AJAX
        var type = $( this ).closest( '.void-query-promotion-notice' ).data( 'notice' );
        var nonce = $( this ).closest( '.void-query-promotion-notice' ).data( 'nonce' );

        elNotice.hide();

        // console.log('never click');

        // Make an AJAX call
        // Since WP 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        $.ajax({
            // url of ajax request, value of voidCf7Admin.ajaxUrl is localized during enqueue script
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'dismissed_promotional_notice_handler',
                type: type,
                status: 'never-show',
            },
            // wp verify nonce automatically after sending nonce like this
            headers: {
                'X-WP-Nonce': nonce
            },
            dataType: 'json',
        });
    });

})(jQuery);