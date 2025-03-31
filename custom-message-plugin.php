<?php
/*
Plugin Name: Custom Message Plugin
Description: Wtyczka do WordPressa.
Version: 1.0
Author: Mateusz
*/

// 1. Rejestracja shortcode
function cmp_register_shortcode() {
    $message = get_option('cmp_custom_message', 'Hello, WordPress!');
    return $message;
}
add_shortcode('custom_message', 'cmp_register_shortcode');

// 2. Dodanie strony do menu administracyjnego
function cmp_add_admin_menu() {
    add_menu_page(
        'Custom Plugin',
        'Custom Plugin',
        'manage_options',
        'custom-message-plugin',
        'cmp_admin_page_content',
        'dashicons-admin-generic',
        80
    );
}
add_action('admin_menu', 'cmp_add_admin_menu');

// 3. Zawartość strony administracyjnej
function cmp_admin_page_content() {
    ?>
    <div class="wrap">
        <h1>Custom Message Plugin</h1>
        <form id="cmp-form">
            <label for="cmp-message">Wiadomość:</label>
            <input type="text" id="cmp-message" name="cmp-message" value="<?php echo esc_attr(get_option('cmp_custom_message', 'Hello, WordPress!')); ?>">
            <button type="submit" class="button button-primary">Zapisz</button>
            <p id="cmp-response"></p>
        </form>
    </div>
    <?php
}

// 4. Rejestracja skryptu JS z AJAX
function cmp_enqueue_scripts() {
    wp_enqueue_script(
        'cmp-script',
        plugins_url('/js/script.js', __FILE__),
        array('jquery'),
        true
    );

    wp_localize_script(
        'cmp-script',
        'cmp_ajax',
        array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('cmp_nonce')
        )
    );
}
add_action('admin_enqueue_scripts', 'cmp_enqueue_scripts');

// 5. Obsługa AJAX
function cmp_save_message() {
    check_ajax_referer('cmp_nonce', 'nonce');

    if (isset($_POST['message'])) {
        $message = sanitize_text_field($_POST['message']);
        update_option('cmp_custom_message', $message);
        wp_send_json_success('Wiadomość zapisana!');
    } else {
        wp_send_json_error('Błąd: brak wiadomości.');
    }
}
add_action('wp_ajax_cmp_save_message', 'cmp_save_message');

