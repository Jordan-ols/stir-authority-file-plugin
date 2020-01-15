<?php

/*
 * Add action people_post_init for registration of post type
 */
add_action('init', 'people_post_init');

/*
 * Set up new post type
 */
function people_post_init()
{
     $labels = array(
        "name" => "Authority Files",
        "singular_name" => "Authority File",
        "menu_name" => "Authority Files",
        "all_items" => "All People",
        "add_new" => "Add New",
        "add_new_item" => "Add New Person",
        "edit" => "Edit",
        "edit_item" => "Edit Person",
        "new_item" => "New Person",
        "view" => "View",
        "view_item" => "View Person",
        "search_items" => "Search People",
        "not_found" => "No People Records Found",
        "not_found_in_trash" => "No People Records Found in Trash",
        "parent" => "Parent Person Record",
    );
    $args = array(
        "labels" => $labels,
        "description" => "Stirling People Record Collection",
        "public" => true,
        "show_ui" => true,
        "has_archive" => false,
        "show_in_menu" => true,
        "exclude_from_search" => false,
        "capability_type" => "post",
        'capabilities' => array(
            'create_posts' => 'do_not_allow', // false < WP 4.5, credit @Ewout
        ),
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => array( "slug" => "people", "with_front" => true ),
        "query_var" => true,
        "menu_icon" => "dashicons-welcome-widgets-menus",
        "supports" => array( "title" ),
    );
    register_post_type( "people", $args );
}