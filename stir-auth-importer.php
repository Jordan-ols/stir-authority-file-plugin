<?php
/*
 * Plugin Name: Stirling Authorised Files Display
 * Plugin URI: www.orangeleaf.com
 * Description: Import people data in XML format, and display into a people custom post type format
 * Version: 1.1.0
 * Author: Jordan Quinn
 * Copyright Orangeleaf Systems Ltd
 * License: All Rights Reserved
 */

require_once plugin_dir_path( __FILE__ ) . 'PeopleImporter.php';
require_once plugin_dir_path( __FILE__ ) . 'PeopleInsert.php';
require_once plugin_dir_path( __FILE__ ) . 'PeopleListing.php';
require_once plugin_dir_path( __FILE__ ) . 'PeoplePost.php';
require_once plugin_dir_path( __FILE__ ) . 'libraries/authAdmin.php';

/*
 * add and register admin page under tools tab
 */
add_action('admin_menu', function() {
    add_management_page('Authority File Importer', 'Authority File Import' , 'edit_pages', 'artists', 'OL_Auth_Display');
});

add_filter('upload_mimes', 'auth_upload_xml');
function auth_upload_xml($mimes) {
    $mimes = array_merge($mimes, array('xml' => 'application/xml'));
    return $mimes;
}

/**
 * Return display
 */
function OL_Auth_Display()
{
    $form = new authAdmin();
    $display = $form->get_tabbed_view();
    echo $display;
}
