<?php

class xp_setup
{
    static function plugins_loaded ()
    {
        // https://developer.wordpress.org/reference/functions/add_menu_page/
        add_action("admin_init", "xp_setup::admin_init");

    }

    static function admin_init ()
    {
        // https://developer.wordpress.org/reference/functions/add_menu_page/
        // https://developer.wordpress.org/reference/functions/add_submenu_page/
        add_submenu_page(
            "plugins.php",
            "Xpress",
            "Xpress",
            "manage_options",
            "xpress",
            "xp_setup::xpress_page",
            "dashicons-admin-generic",
            100
        );
    }

    static function xpress_page ()
    {
        require __DIR__ . "/../templates/plugin-admin.php";
    }
}
