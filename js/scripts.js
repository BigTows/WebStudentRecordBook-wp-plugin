(function ($) {

    $(document).ready(function () {
        var settings = MyPluginSettings;
        var data = get('data');
        $.ajax({
            url: '/',
            method: 'GET',
            data: {
                'rest_route': '/my-plugin/v1/uid',
                'data': data
            },
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', settings.nonce);
            }
        }).done(function (response) {

            // Will return your UID.
            console.log(response);
        });

    });

})(jQuery);


function get(name) {
    const parts = window.location.href.split('?');
    if (parts.length > 1) {
        name = encodeURIComponent(name);
        const params = parts[1].split('&');
        const found = params.filter(el => (el.split('=')[0] === name) && el);
        if (found.length) return decodeURIComponent(found[0].split('=')[1]);
    }
}