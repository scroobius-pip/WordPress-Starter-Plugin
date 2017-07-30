<?php
/**
 * Loads the plugin files
 *
 * @since 1.0
 */


// Exit if accessed directly
if ( !defined('ABSPATH') ) exit;


// Load basic setup. Plugin list links, text domain, footer links etc. 
require_once( PREFIX_STARTER_PLUGIN_DIR . '/admin/prefix_starter-plugin-basic-setup.php');

// Load admin setup. Register menus and settings
require_once( PREFIX_STARTER_PLUGIN_DIR . '/admin/prefix_starter-plugin-admin-setup.php');

// Render Admin UI
require_once( PREFIX_STARTER_PLUGIN_DIR . '/admin/prefix_starter-plugin-admin-ui-render.php');

// Do plugin operations
require_once( PREFIX_STARTER_PLUGIN_DIR . '/admin/prefix_starter-plugin-do.php');

 ?>