<?php

/*
Plugin Name: Bonsai
Description: Run WP-CLI commands from within WordPress
Version: 0.0.0
*/

add_action('admin_menu', 'wpcli_plugin_menu');
function wpcli_plugin_menu() {
    add_menu_page('Bonsai', 'Bonsai', 'manage_options', 'wpcli-plugin', 'wpcli_plugin_page');
}

function wpcli_plugin_page() {
    ?>
    <div class="wrap">
        <h1>Bonsai</h1>
        <form method="post">
            <?php wp_nonce_field('wpcli_plugin_execute_command', 'wpcli_plugin_nonce'); ?>
            <label for="wpcli_command">Enter WP-CLI Command:</label>
            <input type="text" id="wpcli_command" name="wpcli_command" size="50" />
            <input type="submit" value="Execute Command" class="button button-primary" />
        </form>
    </div>
    <?php
}

add_action('admin_post_wpcli_plugin_execute_command', 'wpcli_plugin_execute_command');
function wpcli_plugin_execute_command() {
    // Check security token
    check_admin_referer('wpcli_plugin_execute_command', 'wpcli_plugin_nonce');

    // Get user input and sanitize it
    $command = sanitize_text_field($_POST['wpcli_command']);

    // Validate command parameter to prevent command injection
    if (strpos($command, 'wp ') === 0) {
        // Execute the command using shell_exec
        $output = shell_exec($command);

        // Display the output to the user
        echo '<div class="wrap">';
        echo '<h1>Bonsai</h1>';
        echo '<p>Command: <code>' . $command . '</code></p>';
        echo '<pre>' . $output . '</pre>';
        echo '</div>';
    } else {
        // Invalid command parameter
        wp_die('Invalid WP-CLI command');
    }
}
