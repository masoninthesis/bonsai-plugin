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

    $wp_core_version_checked = isset($_POST['wp_core_version']) && $_POST['wp_core_version'] === 'on';
    $wp_cli_version_checked = isset($_POST['wp_cli_version']) && $_POST['wp_cli_version'] === 'on';

    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

        <form method="post">
            <?php wp_nonce_field('wpcli-plugin-nonce', 'wpcli-plugin-nonce-field'); ?>

            <label>
                <input type="checkbox" name="wp_core_version" <?php if ($wp_core_version_checked) echo 'checked'; ?>>
                WP Core Version
            </label>

            <br>

            <label>
                <input type="checkbox" name="wp_cli_version" <?php if ($wp_cli_version_checked) echo 'checked'; ?>>
                WP CLI Version
            </label>

            <br><br>

            <input type="submit" name="submit" class="button button-primary" value="Run WP CLI Command">
        </form>

        <div id="wpcli-plugin-output">
            <?php
            if (isset($_POST['submit'])) {
                $commands = [];

                if ($wp_core_version_checked) {
                    $commands[] = 'wp core version';
                }

                if ($wp_cli_version_checked) {
                    $commands[] = 'wp cli version';
                }

                if (count($commands) > 0) {
                    $output = '';
                    foreach ($commands as $command) {
                        $output .= "<h3>$command</h3>";
                        $output .= "<pre>" . shell_exec($command) . "</pre>";
                    }

                    echo $output;
                }
            }
            ?>
        </div>
    </div>
    <?php
}
