<?php
/**
 * Plugin Name: PageBot
 * Plugin URI: https://thepagebot.com
 * Description: Handle customer support with a GPT powered AI ChatBot.
 * Author: PageBot
 * Author URI: https://twitter.com/pagebotai
 * Version: 1.0
 * Text Domain: starter-plugin
 * Domain Path: /languages
 * License: GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * This plugin was developed using the WordPress starter plugin template by Arun Basil Lal <arunbasillal@gmail.com>
 * Please leave this credit and the directory structure intact for future developers who might read the code.
 * @GitHub https://github.com/arunbasillal/WordPress-Starter-Plugin
 */
 
/**
 * ~ Directory Structure ~
 *
 * /admin/ 					- Plugin backend stuff.
 * /functions/					- Functions and plugin operations.
 * /includes/					- External third party classes and libraries.
 * /languages/					- Translation files go here. 
 * /public/					- Front end files and functions that matter on the front end go here.
 * index.php					- Dummy file.
 * license.txt					- GPL v2
 * starter-plugin.php				- Main plugin file containing plugin name and other version info for WordPress.
 * readme.txt					- Readme for WordPress plugin repository. https://wordpress.org/plugins/files/2018/01/readme.txt
 * uninstall.php				- Fired when the plugin is uninstalled. 
 */
 
/**
 * ~ TODO ~
 *
 * - Note: (S&R) = Search and Replace by matching case.
 *
 * - Plugin name: Starter Plugin (S&R)
 * - Plugin folder slug: starter-plugin (S&R)
 * - Decide on a prefix for the plugin (S&R)
 * - Plugin description
 * - Text domain. Text domain for plugins has to be the folder name of the plugin. For eg. if your plugin is in /wp-content/plugins/abc-def/ folder text domain should be abc-def (S&R)
 * - Update prefix_settings_link() 		in \admin\basic-setup.php
 * - Update prefix_footer_text()		in \admin\basic-setup.php
 * - Update prefix_add_menu_links() 		in \admin\admin-ui-setup.php
 * - Update prefix_register_settings() 		in \admin\admin-ui-setup.php
 * - Update UI format and settings		in \admin\admin-ui-render.php
 * - Update uninstall.php
 * - Update readme.txt
 * - Update PREFIX_VERSION_NUM 			in starter-plugin.php (keep this line for future updates)
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Define constants
 *
 * @since 1.0
 */
if ( ! defined( 'PREFIX_VERSION_NUM' ) ) 		define( 'PREFIX_VERSION_NUM'		, '1.0' ); // Plugin version constant
if ( ! defined( 'PREFIX_STARTER_PLUGIN' ) )		define( 'PREFIX_STARTER_PLUGIN'		, trim( dirname( plugin_basename( __FILE__ ) ), '/' ) ); // Name of the plugin folder eg - 'starter-plugin'
if ( ! defined( 'PREFIX_STARTER_PLUGIN_DIR' ) )	define( 'PREFIX_STARTER_PLUGIN_DIR'	, plugin_dir_path( __FILE__ ) ); // Plugin directory absolute path with the trailing slash. Useful for using with includes eg - /var/www/html/wp-content/plugins/starter-plugin/
if ( ! defined( 'PREFIX_STARTER_PLUGIN_URL' ) )	define( 'PREFIX_STARTER_PLUGIN_URL'	, plugin_dir_url( __FILE__ ) ); // URL to the plugin folder with the trailing slash. Useful for referencing src eg - http://localhost/wp/wp-content/plugins/starter-plugin/

/**
 * Database upgrade todo
 *
 * @since 1.0
 */
function prefix_upgrader() {
	
	// Get the current version of the plugin stored in the database.
	$current_ver = get_option( 'abl_prefix_version', '0.0' );
	
	// Return if we are already on updated version. 
	if ( version_compare( $current_ver, PREFIX_VERSION_NUM, '==' ) ) {
		return;
	}
	
	// This part will only be excuted once when a user upgrades from an older version to a newer version.
	
	// Finally add the current version to the database. Upgrade todo complete. 
	update_option( 'abl_prefix_version', PREFIX_VERSION_NUM );
}



add_action( 'admin_init', 'prefix_upgrader' );

// Load everything
// require_once( PREFIX_STARTER_PLUGIN_DIR . 'loader.php' );

// // Register activation hook (this has to be in the main plugin file or refer bit.ly/2qMbn2O)
// register_activation_hook( __FILE__, 'prefix_activate_plugin' );

function add_my_script() {
	$your_id = esc_attr(get_option('your_id'));
    if ($your_id) {
        echo "<script data-pgbt_id='$your_id' src='https://x.thepagebot.com'></script>";
    }
}

function my_script_plugin_menu() {
    add_options_page(
        'PageBot Settings',
        'PageBot Settings',
        'manage_options',
        'pagebot-plugin-settings',
        'pagebot_plugin_settings_page'
    );
}



function pagebot_plugin_settings_page() {
	$sources = get_option('pgbt_sources', []);
	if (!is_array($sources)) {
		$sources = [];
	}
    ?> 
	 <div class="wrap">
        <h1>PageBot Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('pagebot-plugin-settings-group'); ?>
            <?php do_settings_sections('pagebot-plugin-settings-group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">PGBT ID</th>
                    <td>
						<p>Enter your PageBot ID. You can find it at <a href="https://thepagebot.com/dashboard" target="_blank">thepagebot.com/dashboard</a>.</p>
                        <input type="text" name="your_id" value="<?php echo esc_attr(get_option('your_id')); ?>" />
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <div class="wrap">
        <h1>PageBot Sources</h1>
		<p>PageBot will retrieve content from these sources.</p>
        <form method="post" action="options.php">
            <?php settings_fields('pagebot-plugin-settings-group'); ?>
            <?php do_settings_sections('pagebot-plugin-settings-group'); ?>
            <table class="form-table" id="pgbt-sources-table">
                <thead>
                    <tr>
                        <th>URL or Text</th>
                        <th>Expires Seconds (optional)</th>
                        <th></th>
                    </tr>
                </thead>
				<tbody>
    <?php foreach ($sources as $index => $source) : ?>
        <tr>
            <td><input type="text" name="pgbt_sources[<?php echo $index; ?>][url]" value="<?php echo isset($source['url']) ? esc_attr($source['url']) : ''; ?>" /></td>
            <td><input type="text" name="pgbt_sources[<?php echo $index; ?>][expires]" value="<?php echo isset($source['expires']) ? esc_attr($source['expires']) : ''; ?>" /></td>
            <td><button type="button" onclick="removeRow(this);">Remove</button></td>
        </tr>
    <?php endforeach; ?>
</tbody>

            </table>
            <button type="button" onclick="addRow();">Add Source</button>
            <?php submit_button(); ?>
        </form>
        <script>
            function addRow() {
                let table = document.getElementById("pgbt-sources-table").getElementsByTagName('tbody')[0];
                let newRow = table.insertRow();
                newRow.innerHTML = `
                    <td><input type="text" name="pgbt_sources[][url]" value="" /></td>
                    <td><input type="text" name="pgbt_sources[][expires]" value="" /></td>
                    <td><button type="button" onclick="removeRow(this);">Remove</button></td>
                `;
            }

            function removeRow(button) {
                let row = button.parentNode.parentNode;
                row.parentNode.removeChild(row);
            }
        </script>
    </div>
    <?php
}
function my_script_plugin_settings() {
    register_setting('pagebot-plugin-settings-group', 'your_id');
	register_setting('pagebot-plugin-settings-group', 'pgbt_sources', 'sanitize_pgbt_sources');
}

function sanitize_pgbt_sources($sources) {
	if (!is_array($sources)) {
		$sources = [];
	}
    foreach ($sources as $index => $source) {
        if (isset($source['url'])) {
            $sources[$index]['url'] = sanitize_text_field($source['url']);
        }

        if (isset($source['expires'])) {
            $sources[$index]['expires'] = sanitize_text_field($source['expires']);
        }
    }
    return $sources;
}


// function add_my_meta_tags() {
//     $sources = get_option('pgbt_sources', []);
// 	if (!is_array($sources)) {
// 		$sources = [];
// 	}
//     foreach ($sources as $source) {
//         $url = esc_attr($source['url']);
//         $expires = isset($source['expires']) ? ' data-expires="' . esc_attr($source['expires']) . '"' : '';
//         echo "<meta name='pgbt:source' content='$url'$expires />";
//     }
// }
function add_my_meta_tags() {
    $sources = get_option('pgbt_sources', []);
    if (!is_array($sources)) {
        $sources = [];
    }
    foreach ($sources as $source) {
		if (!isset($source['url']) && !isset($source['expires'])) {
			continue;
		}
        if (isset($source['url'])) {
            $url = esc_attr($source['url']);
        } else {
            continue; // Skip to the next iteration if 'url' is not set
        }

        $expires = isset($source['expires']) ? ' data-expires="' . esc_attr($source['expires']) . '"' : '';
        echo "<meta name='pgbt:source' content='$url'$expires />";
    }
}


function pagebot_plugin_activation() {
    // Check if it's a network-wide activation
    if (isset($_GET['networkwide']) && ($_GET['networkwide'] == 1)) {
        return;
    }

    // Redirect to the dashboard
    wp_redirect('https://thepagebot.com?wordpress=true');
    exit;  // Always call exit after wp_redirect
}

// register_activation_hook(__FILE__, 'pagebot_plugin_activation');
// The format is plugin_action_links_[plugin file name]
 

add_action('wp_head', 'add_my_script');
add_action('wp_head', 'add_my_meta_tags');
add_action('admin_menu', 'my_script_plugin_menu');
add_action('admin_init', 'my_script_plugin_settings');
