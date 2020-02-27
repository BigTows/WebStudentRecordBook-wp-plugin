<?php

namespace WebStudentRecordBook\Menu;

class AdminMenu
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'init']);
        add_action('admin_init', [$this, 'plugin_settings']);
    }


    public function init()
    {
        add_menu_page('Элетронная зачетка', 'Элетронная зачетка', 'manage_options', 'student-record-book.php', [$this, 'render'], 'dashicons-book', 100);
    }

    public function plugin_settings()
    {
        // параметры: $option_group, $option_name, $sanitize_callback
        register_setting('option_group', 'option_name', 'sanitize_callback');

        // параметры: $id, $title, $callback, $page
        add_settings_section('section_id', 'Основные настройки', '', 'primer_page');

        // параметры: $id, $title, $callback, $page, $section, $args
        add_settings_field('primer_field1', 'Название опции', [$this, 'fill_primer_field1'], 'primer_page', 'section_id');
        add_settings_field('primer_field2', 'Другая опция', [$this, 'fill_primer_field2'], 'primer_page', 'section_id');
    }


    function fill_primer_field1()
    {
        $val = get_option('option_name');
        $val = $val ? $val['input'] : null;
        ?>
        <input type="text" name="option_name[input]" value="<?php echo esc_attr($val) ?>"/>
        <?php
    }

## Заполняем опцию 2
    function fill_primer_field2()
    {
        $val = get_option('option_name');
        $val = $val ? $val['checkbox'] : null;
        ?>
        <label><input type="checkbox" name="option_name[checkbox]" value="1" <?php checked(1, $val) ?> />
            отметить</label>
        <?php
    }

    public function render()
    {
        ?>
        <div class="wrap">
            <h2><?php echo get_admin_page_title(); ?></h2>

            <script>
                (function ($) {

                    $(document).ready(function () {
                        var _nonce = "<?php echo wp_create_nonce('wp_rest'); ?>";
                        $.ajax({
                            type: 'GET',
                            url: '/wp-json/my-plugin/v1/uid/',
                            data: {
                                // bid : next
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

            <form action="options.php" method="POST">
                <?php
                settings_fields('option_group');     // скрытые защитные поля
                do_settings_sections('primer_page'); // секции с настройками (опциями). У нас она всего одна 'section_id'
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}