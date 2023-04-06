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
            <input type="hidden" name="wpcli_plugin_action" value="run_wpcli_command">
            <input type="submit" name="run_wpcli_command" class="button button-primary" value="Run WP CLI Command">
        </form>

        <div id="wpcli-plugin-output">
            <?php
            if (isset($_POST['wpcli_plugin_action']) && $_POST['wpcli_plugin_action'] === 'run_wpcli_command') {
                $output = shell_exec('wp core version');
                echo "<pre>$output</pre>";
            }
            ?>
        </div>
    </div>
    <?php
}
