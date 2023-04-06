<?php
/*
Plugin Name: WP CLI Plugin
*/

function wpcli_plugin_menu() {
    add_menu_page('WP CLI Plugin', 'WP CLI Plugin', 'manage_options', 'wpcli-plugin', 'wpcli_plugin_settings_page');
}

add_action('admin_menu', 'wpcli_plugin_menu');

function wpcli_plugin_settings_page() {
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

        <form method="post">
            <?php wp_nonce_field('wpcli-plugin-nonce', 'wpcli-plugin-nonce-field'); ?>
            <input type="checkbox" name="wpcli_plugin_disable" id="wpcli-plugin-disable" value="1">
            <label for="wpcli-plugin-disable"><?php _e( 'Disable WP CLI Command', 'wpcli-plugin' ); ?></label>
            <input type="text" name="wpcli_plugin_action" value="wp core version" readonly>
            <input type="submit" name="wp_core_version" class="button button-primary" value="Run WP CLI Command">
        </form>

        <div id="wpcli-plugin-output">
          <p>Output:</p>
            <?php
            if (isset($_POST['wpcli_plugin_action']) && $_POST['wpcli_plugin_action'] === 'wp core version' && empty($_POST['wpcli_plugin_disable'])) {
                $output = shell_exec('wp core version');
                echo "<pre>$output</pre>";
            }
            ?>
        </div>
    </div>
    <?php
}
