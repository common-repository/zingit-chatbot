<?php
/*
  Plugin Name: Zingit Chatbot
  Plugin URI: https://zingitsolutions.com
  Description: Zingit chatbot helps to convert visitors into customers.
  Version: 1.0.2
  Author: Zingit Solutions
  Author URI: https://profiles.wordpress.org/zingitadmin/
  License:     GPLv2 or later
  License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

register_activation_hook(__FILE__, 'zcb_plugin_activate');
add_action('admin_init', 'zcb_plugin_redirect');

function zcb_plugin_activate() {
    add_option('zcb_plugin_do_activation_redirect', true);
}



function zcb_plugin_redirect() {
    if (get_option('zcb_plugin_do_activation_redirect', false)) {
        delete_option('zcb_plugin_do_activation_redirect');
        if(!isset($_GET['activate-multi']))
        {
            wp_redirect("admin.php?page=zcb-plugin-settings");
            exit;
        }
    }
}



//Plugin Text Domain
define("ZCB_TXTDM","zingit-chatbot");

// All Query Page Code
add_action( 'admin_menu', 'zcb_add_menus' );
function zcb_add_menus() {
    add_menu_page( 'Zingit Chat Bot id', __( 'Zingit Chat Bot', ZCB_TXTDM ),  'administrator', 'zcb-plugin-settings', 'zcb_plugin_settings', 'dashicons-welcome-widgets-menus', 65);
}

// setting page body
function zcb_plugin_settings() {
    add_option('z2_chat_bot_key','');

    if(isset($_POST['zcb_update'])) {

        if (!isset($_POST['zcb_update_setting']) || !wp_verify_nonce($_POST['zcb_update_setting'], 'zcb-update-setting')) {
            die("<br><br>Hmm .. looks like you didn't send any credentials.. No CSRF for you! ");

        } else {
            if(isset($_POST['z2_chat_bot_key'])) {
                $z2_chat_bot_guid = sanitize_text_field($_POST['z2_chat_bot_key']);
                update_option('z2_chat_bot_key', $z2_chat_bot_guid);
            }

        }
    }

    ?>

    <form method="post" enctype="multipart/form-data"  class="wrap" name="zcb_form">
        <h1>Plugin Chat bot Id</h1>
        <style>
            .ZingFormButton{
                width:auto;
                padding: 0 40px;
                background: #0073aa;
                color:#ffffff;
            }
        </style>
        <table class="form-table table">
            <tbody>
            <tr>
                <th width="45%" scope="row">Enter your chat bot ID :</th>
                <td>
                    <input type="text" class="regular-text" name="z2_chat_bot_key" value="<?php if(get_option('z2_chat_bot_key')){ echo get_option('z2_chat_bot_key'); } ?>" >
                    <p class="description">Please enter your chat bot ID sent via email by Zingit team to activate this Plugin.</p>
                    <input name="zcb_update_setting" type="hidden" value="<?php echo wp_create_nonce('zcb-update-setting'); ?>" />
                </td>
            </tr>
            <tr>
                <td> &nbsp;</td><td><input class="button button-primary ZingFormButton" type="submit" name="zcb_update" value="<?php _e('Update') ?>"></td>
            </tr>

            </tbody>
        </table>
    </form>

    <?php
}

add_action( 'wp_head', 'zcb_chat_bot_scripts' );

function zcb_chat_bot_scripts(){
    $zcb_value = get_option('z2_chat_bot_key');
    ?>
    <script type="text/javascript">
        window.cb__config = {
            appId: '<?php echo $zcb_value; ?>'
        }
    </script>

    <?php

    wp_enqueue_style( 'zcb-custom-style', 'https://dngl1vyyqycu5.cloudfront.net/embed.css' );
    wp_enqueue_script ( 'zcb-custom-script', 'https://dngl1vyyqycu5.cloudfront.net/embed.js' );

}

?>
