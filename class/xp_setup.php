<?php

class xp_setup
{
    static function plugins_loaded()
    {
        // https://developer.wordpress.org/reference/functions/add_menu_page/
        if (is_admin()) {
            add_action("admin_menu", "xp_setup::admin_init");
        }

        // add new ajax action (not logged in) action="xpress_api"
        // warning: POST request only
        // curl -v -X POST -d "action=xpress" https://YOUSITE.COM/wp-admin/admin-ajax.php -o ajax.json
        add_action("wp_ajax_nopriv_xpress", "xp_os::xpress_ajax");
        // also needed if user logged in
        add_action("wp_ajax_xpress", "xp_os::xpress_ajax");

        // https://developer.wordpress.org/reference/hooks/template_include/
        add_filter( 'template_include', 'xp_os::template_include');

        // add custom post types
        add_action('init', 'xp_setup::register_post_types');
    }

    static function register_post_types ()
    {
        // get option xp_option_post_types
        $post_types = get_option('xp_option_post_types_noui', "");
        // if not empty then explode , and loop
        if ($post_types) {
            $post_types = explode(",", $post_types);
            foreach ($post_types as $post_type) {
                // register post type
                $post_type = trim($post_type);
                $pt_config = [
                    'public' => true,
                    'show_in_rest' => true, // enable REST API fo BLock Editor
                    'show_ui' => false,
                    'label' => $post_type,
                    'taxonomies' => ['category', 'post_tag'],
                    'has_archive' => true,
                ];

                register_post_type($post_type, $pt_config);

            }
        }
        
        // get option xp_option_post_types
        $post_types = get_option('xp_option_post_types', "");
        // if not empty then explode , and loop
        if ($post_types) {
            $post_types = explode(",", $post_types);
            foreach ($post_types as $post_type) {
                // register post type
                $post_type = trim($post_type);
                $pt_config = [
                    'public' => true,
                    'show_in_rest' => true, // enable REST API fo BLock Editor
                    'show_ui' => true,
                    'label' => $post_type,
                    'taxonomies' => ['category', 'post_tag'],
                    'has_archive' => true,
                    'query_var' => true,
                    'publicly_queryable' => true,
                    'rewrite' => ['slug' => $post_type],
                    'capability_type' => 'post',
                    'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions', 'page-attributes', 'post-formats'],
                    'hierarchical' => true,
                    // 'show_in_menu' => true,
                ];

                register_post_type($post_type, $pt_config);

                // register_post_type($post_type, [
                //     'labels' => [
                //         'name' => $post_type,
                //         'singular_name' => $post_type,
                //         'add_new' => 'Add New',
                //         'add_new_item' => 'Add New ' . $post_type,
                //         'edit_item' => 'Edit ' . $post_type,
                //         'new_item' => 'New ' . $post_type,
                //         'view_item' => 'View ' . $post_type,
                //         'search_items' => 'Search ' . $post_type,
                //         'not_found' => 'No ' . $post_type . ' found',
                //         'not_found_in_trash' => 'No ' . $post_type . ' found in Trash',
                //         'parent_item_colon' => 'Parent ' . $post_type . ':',
                //         'menu_name' => $post_type,
                //     ],
                //     'hierarchical' => false,
                //     'description' => 'List of ' . $post_type,
                //     'supports' => [
                //         'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'
                //     ],
                //     'taxonomies' => ['category', 'post_tag'],
                //     'public' => true,
                //     'show_ui' => true,
                //     'show_in_menu' => true,
                //     'menu_position' => 5,
                //     'menu_icon' => 'dashicons-admin-post',
                //     'show_in_nav_menus' => true,
                //     'publicly_queryable' => true,
                //     'exclude_from_search' => false,
                //     'has_archive' => true,
                //     'query_var' => true,
                //     'can_export' => true,
                //     'rewrite' => true,
                //     'capability_type' => 'post'
                // ]);
            }
        }

    }

    static function admin_init()
    {
        // https://developer.wordpress.org/reference/functions/add_plugins_page/
        add_plugins_page(
            "XPress",
            "XPress",
            "edit_plugins",
            "xpress-admin",
            "xp_setup::admin_page",
        );
    }

    static function admin_page()
    {
        // check xpress_api_key 
        $xpress_api_key = get_option('xpress_api_key');
        // if not set create random md5
        if (!$xpress_api_key) {
            $xpress_api_key = md5(password_hash(uniqid(), PASSWORD_DEFAULT));
            update_option('xpress_api_key', $xpress_api_key);
        }

        // check if xpress-data folder exists
        $xpress_data_folder = __DIR__ . "/../../xpress-data";
        if (!file_exists($xpress_data_folder)) {
            mkdir($xpress_data_folder);

            $code = '$xpress_api_key = "' . $xpress_api_key . '";';

            // add index.php with plugin annotation
            $index_php =
                <<<php
            <?php
            /*
            Plugin Name: XPress Data
            */

            $code

            php;

            file_put_contents($xpress_data_folder . "/index.php", $index_php);
        }

        // should output: /wp-content/plugins/xpress-main 
        $xp_url = plugin_dir_url(__DIR__);

        require __DIR__ . "/../templates/plugin-admin.php";
    }

    static function get_option ($name, $default='')
    {
        if (function_exists("get_option")) {
            $res = get_option($name);
        }
        else {
            $res = $default;
        }
        return $res;
    }
}
