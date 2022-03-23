<?php

// register distributors cpt
function sbwc_register_distributors_cpt()
{

    /**
     * Post Type: Local Distributors.
     */

    $labels = [
        "name"                     => __("Local Distributors", "sbwc-dists"),
        "singular_name"            => __("Local Distributor", "sbwc-dists"),
        "menu_name"                => __("Local Distributors", "sbwc-dists"),
        "all_items"                => __("All Local Distributors", "sbwc-dists"),
        "add_new"                  => __("Add new", "sbwc-dists"),
        "add_new_item"             => __("Add new Local Distributor", "sbwc-dists"),
        "edit_item"                => __("Edit Local Distributor", "sbwc-dists"),
        "new_item"                 => __("New Local Distributor", "sbwc-dists"),
        "view_item"                => __("View Local Distributor", "sbwc-dists"),
        "view_items"               => __("View Local Distributors", "sbwc-dists"),
        "search_items"             => __("Search Local Distributors", "sbwc-dists"),
        "not_found"                => __("No Local Distributors found", "sbwc-dists"),
        "not_found_in_trash"       => __("No Local Distributors found in trash", "sbwc-dists"),
        "parent"                   => __("Parent Local Distributor: ", "sbwc-dists"),
        "featured_image"           => __("Featured image for this Local Distributor", "sbwc-dists"),
        "set_featured_image"       => __("Set featured image for this Local Distributor", "sbwc-dists"),
        "remove_featured_image"    => __("Remove featured image for this Local Distributor", "sbwc-dists"),
        "use_featured_image"       => __("Use as featured image for this Local Distributor", "sbwc-dists"),
        "archives"                 => __("Local Distributor archives", "sbwc-dists"),
        "insert_into_item"         => __("Insert into Local Distributor", "sbwc-dists"),
        "uploaded_to_this_item"    => __("Upload to this Local Distributor", "sbwc-dists"),
        "filter_items_list"        => __("Filter Local Distributors list", "sbwc-dists"),
        "items_list_navigation"    => __("Local Distributors list navigation", "sbwc-dists"),
        "items_list"               => __("Local Distributors list", "sbwc-dists"),
        "attributes"               => __("Local Distributors attributes", "sbwc-dists"),
        "name_admin_bar"           => __("Local Distributor", "sbwc-dists"),
        "item_published"           => __("Local Distributor published", "sbwc-dists"),
        "item_published_privately" => __("Local Distributor published privately.", "sbwc-dists"),
        "item_reverted_to_draft"   => __("Local Distributor reverted to draft.", "sbwc-dists"),
        "item_scheduled"           => __("Local Distributor scheduled", "sbwc-dists"),
        "item_updated"             => __("Local Distributor updated.", "sbwc-dists"),
        "parent_item_colon"        => __("Parent Local Distributor: ", "sbwc-dists"),
    ];

    $args = [
        "label"                 => __("Local Distributors", "sbwc-dists"),
        "labels"                => $labels,
        "description"           => "",
        "public"                => true,
        "publicly_queryable"    => true,
        "show_ui"               => true,
        "show_in_rest"          => false,
        "rest_base"             => "",
        "rest_controller_class" => "WP_REST_Posts_Controller",
        "has_archive"           => false,
        "show_in_menu"          => true,
        "show_in_nav_menus"     => false,
        "delete_with_user"      => false,
        "exclude_from_search"   => true,
        "capability_type"       => "post",
        "map_meta_cap"          => true,
        "hierarchical"          => false,
        "can_export"            => false,
        "rewrite"               => ["slug" => "distributor", "with_front" => true],
        "query_var"             => true,
        "menu_position"         => 20,
        "menu_icon"             => "dashicons-randomize",
        "supports"              => ["title"],
        "show_in_graphql"       => false,
    ];

    register_post_type("distributor", $args);
}

add_action('init', 'sbwc_register_distributors_cpt');
