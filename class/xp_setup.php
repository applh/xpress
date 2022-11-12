<?php

class xp_setup
{
    static function plugins_loaded ()
    {
        // https://developer.wordpress.org/reference/functions/add_menu_page/
        if (is_admin()) {
            add_action("admin_menu", "xp_setup::admin_init");
        }

    }

    static function admin_init ()
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

    static function admin_page ()
    {
        require __DIR__ . "/../templates/plugin-admin.php";
    }
}
