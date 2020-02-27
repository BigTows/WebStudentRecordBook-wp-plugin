<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<div class="wrap">
    <h2><?php echo get_admin_page_title(); ?></h2>

    <form action="" id="findStudent">
        <label>
            <input type="text" placeholder="Введите номер студенческого билета">
        </label>
        <input type="submit" value="Найти" class="button button-primary">
    </form>

    <script>
        (function ($) {
            $("#findStudent").submit(function (event) {
                event.preventDefault();
                let _nonce = "<?php echo wp_create_nonce('wp_rest'); ?>";
                let studentId = $( "input" ).first().val();
                $.ajax({
                    type: 'GET',
                    url: '/wp-json/my-plugin/v1/uid/',
                    data: {
                        'studentId': studentId
                    },
                    dataType: 'json',
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-WP-Nonce', _nonce);
                    }
                }).done(function (response) {
                    console.log(response);
                });
            });

        })(jQuery);
    </script>
    <?php submit_button(); ?>

</div>
</body>
</html>