<?php

class xpi_admin
{
    static function posts_load ()
    {
        $feedback = "";        
        $logs = [];
        // get all the $_FILES
        if (!empty($_FILES)) {
            // get all files with extension .html
            $files = array();
            foreach ($_FILES as $input_name => $file) {
                if (preg_match('/\.html$/', $file['name'])) {
                    $files[$input_name] = $file;
                }
            }
            // for each $files
            // find the post with the same name
            // and update the post with the content of the file
            foreach ($files as $input_name => $file) {
                // $input_name can be in format file or file/post_type
                $input_parts = explode('/', $input_name);
                $post_type = $input_parts[1] ?? 'page';

                // get the filename without extension .html
                $filename = substr($file['name'], 0, -5);

                // update the post with the content of the file
                $file_html = file_get_contents($file['tmp_name']);
                // wrap inside a block element wp:html
                
                // get the post with the same name
                $post = get_page_by_path($filename, ARRAY_A, $post_type);
                if (!empty($post)) {
                    // get the current content
                    $content = $post['post_content'];
                    // if there is a <!-- xp-html --> tag
                    // replace the content between <!-- xp-html --> and <!-- /xp-html -->
                    // with the content of the file
                    if (preg_match('/<!-- xp-html -->.*<!-- \/xp-html -->/s', $content, $matches)) {
                        $html = 
                        <<<html
                        <!-- xp-html -->
                        $file_html
                        <!-- /xp-html -->
                        html;
                        $content = str_replace($matches[0], $html, $content);
                    } else {
                        $html = 
                        <<<html
                        <!-- wp:html -->
                        <!-- xp-html -->
                        $file_html
                        <!-- /xp-html -->
                        <!-- /wp:html -->
                        html;
                        // if there is no <!-- xp-html --> tag
                        // append the content of the file to the post content
                        $content .= $html;
                    }
                    // update the post content with the content of the file
                    $post["post_content"] = $content;

                    wp_update_post($post);
                    $post_id = $post["ID"];
                    $post_title = $post["post_title"];
                    $logs[] = "updated post ($post_id) $post_title ($filename)($post_type)";
                    $feedback .= "updated post ($post_id) $post_title ($filename)($post_type)";
                }
                else {
                    $html = 
                    <<<html
                    <!-- wp:html -->
                    <!-- xp-html -->
                    $file_html
                    <!-- /xp-html -->
                    <!-- /wp:html -->
                    html;

                    // create a new post with the content of the file
                    $post = [
                        'post_title' => $filename,
                        'post_content' => $html,
                        'post_status' => 'publish',
                        'post_type' => $post_type,
                    ];
                    $post_id = wp_insert_post($post);
                    $logs[] = "created post ($post_type)($post_id) $filename ";
                    $feedback .= "Post ($post_type)($post_id) $filename created. ";
                }

            }
        }
        
        xp_os::api_data('logs', $logs);

        return $feedback;
    }

    static function posts_update()
    {
        $feedback = "";

        // get inputs post_type, post_id, post_title, post_content, post_status
        $post_type = $_POST['post_type'] ?? 'post';
        $post_id = $_POST['post_id'] ?? 0;
        $post_title = $_POST['post_title'] ?? '';
        $post_content = $_POST['post_content'] ?? '';
        $post_status = $_POST['post_status'] ?? 'publish';

        // sanitize inputs
        $post_type = preg_replace('/[^a-z0-9-_]/i', '', $post_type);
        $post_id = intval($post_id);
        $post_status = preg_replace('/[^a-z0-9-_]/i', '', $post_status);

        // check post_type
        if ($post_type) {
            // check post_id
            if ($post_id) {
                // update post
                $post = [
                    'ID' => $post_id,
                    'post_title' => $post_title,
                    'post_content' => $post_content,
                    'post_status' => $post_status,
                    'post_type' => $post_type,
                ];
                $post_id = wp_update_post($post);
                $feedback = "Post updated ($post_id)";
            }
            // refresh post list
            $posts = xp_post::read_list($post_type);
            // store posts in api_data
            xp_os::api_data('posts', $posts);
        }
        return $feedback;
    }

    static function posts_create()
    {
        $feedback = "";

        // get inputs
        $post_type = $_REQUEST['post_type'] ?? '';
        $post_title = $_REQUEST['post_title'] ?? '';
        $post_content = $_REQUEST['post_content'] ?? '';
        $post_status = $_REQUEST['post_status'] ?? 'publish';

        // sanitize inputs
        $post_type = preg_replace('/[^a-z0-9-_]/i', '', $post_type);
        $post_status = preg_replace('/[^a-z0-9-_]/i', '', $post_status);

        // create post if post_type is not empty
        if ($post_type) {
            $post_id = wp_insert_post([
                'post_type' => $post_type,
                'post_title' => $post_title,
                'post_content' => $post_content,
                'post_status' => $post_status,
            ]);
            $feedback = "created ($post_id)";

            // refresh post list
            $posts = xp_post::read_list($post_type);
            // store posts in api_data
            xp_os::api_data('posts', $posts);
        }

        return $feedback;
    }

    static function posts_delete()
    {
        $feedback = "";

        // get post id
        $post_id = $_REQUEST['post_id'] ?? 0;
        $post_id = intval($post_id);
        // if post id is valid (> 0)
        if ($post_id > 0) {
            // delete post
            $res = wp_delete_post($post_id, true);
            // if post deleted
            if ($res) {
                $feedback = "post deleted ($post_id)";
            } else {
                $feedback = "ERROR: post NOT deleted ($post_id)";
            }
        } else {
            $feedback = "post id not valid";
        }

        // refresh posts list
        $post_type = $_REQUEST['post_type'] ?? 'post';
        // sanitize post_type
        $post_type = preg_replace('/[^a-z0-9-_]/i', '', $post_type);
        // check if post_type is not empty
        if ($post_type) {
            // refresh post list
            $posts = xp_post::read_list($post_type);
            // store posts in api_data
            xp_os::api_data('posts', $posts);
        }

        return $feedback;
    }

    static function posts_read()
    {
        $feedback = "";
        // get post_type
        $post_type = $_REQUEST['post_type'] ?? '';
        // post_status
        $post_status = $_REQUEST['post_status'] ?? 'publish';

        // sanitize post_type, post_status
        $post_type = preg_replace('/[^a-z0-9-_]/i', '', $post_type);
        $post_status = preg_replace('/[^a-z0-9-_]/i', '', $post_status);

        // check if post_type is not empty
        if ($post_type) {
            // refresh post list
            $posts = xp_post::read_list($post_type, $post_status);

            // store posts in api_data
            xp_os::api_data('posts', $posts);

            $nb_found = count($posts);
            $feedback = "($post_type) (found: $nb_found)";
        }

        // return posts
        return $feedback;
    }

    static function script()
    {
        // isolate WP code for later separation
        $root_plugin_dir = WP_PLUGIN_DIR;

        $res = "";
        // date
        $date = date("ymd-His");
        // plugin xp-data dir
        $xp_data_dir = "$root_plugin_dir/xpress-data";

        // FIXME: can be dangerous
        // needs more security
        // block php files
        // and don't overwrite existing files
        $res = "admin script ($date)";

        // CODE PROCESSING
        $filename = $_REQUEST['filename'] ?? '';
        $filename = trim($filename);
        // accept letters, numbers, underscore, dash and dot
        // $filename = preg_replace('/[^a-z0-9_\-\.]/i', '', $filename);
        $filename = preg_replace('/[^a-z0-9\-_]/i', '', $filename);
        // lowercase
        $filename = strtolower($filename);

        $extension = $_REQUEST['extension'] ?? '';
        $extension = trim($extension);
        $extension = preg_replace('/[^a-z0-9-_]/i', '', $extension);
        // lowercase
        $extension = strtolower($extension);

        $code = $_REQUEST['code'] ?? '';
        $code = trim($code);
        // if extension is json then decode code
        if ($extension == "json") {
            // FIXME: WHY DO WE NEED stripslashes() ???
            $code = stripslashes($code);
            $json_infos = json_decode($code, true);
            // if not valid set empty array
            $json ??= [];

            $res = $json_infos;
        }

        // ZIP PROCESSING

        // store each file in different zip file 
        // depending on its file extension
        $ext_zips = [];

        // move all uploaded files to xp-data dir
        $files = $_FILES;
        foreach ($files as $file) {
            $tmp_name = $file['tmp_name'];
            $name = $file['name'];
            // sanitize name (filename and extension)
            $name = preg_replace('/[^a-z0-9-_\.]/i', '', $name);
            $name = strtolower($name);
            // get extension
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            // if extension is not allowed
            if (in_array($ext, ["zip"])) {
                // don't zip zip files
                // special processing
            } else {
                // check if zip file for this extension exists
                $zip = $ext_zips[$ext] ?? null;
                // if not create it
                if ($zip == null) {
                    // check if zip file exists with name media-%RANDOM-MD5%.zip
                    $files = glob($xp_data_dir . "/media-$ext-*.zip");
                    // if no files found create a new one with name media-%RANDOM-MD5%.zip
                    if (count($files) == 0) {
                        $random_tag = md5(password_hash(uniqid(), PASSWORD_DEFAULT));
                        $zip_file = $xp_data_dir . "/media-$ext-$random_tag.zip";
                    } else {
                        // get the last file found
                        $zip_file = $files[count($files) - 1];
                    }
                    // header debug
                    // header("X-Xp-Debug-script: $zip_file");
                    // create zip file
                    $zip = new ZipArchive();
                    $zip->open($zip_file, ZipArchive::CREATE);

                    $ext_zips[$ext] = $zip;
                }

                // $dest = "$xp_media_dir/$name";
                // move_uploaded_file($tmp_name, $dest);

                // if $zip_file exists add file to zip
                if ($zip) {
                    $zip->addFile($tmp_name, $name);
                }
            }
        }

        // close all zip files
        foreach ($ext_zips as $zip) {
            $zip->close();
        }

        return $res;
    }

    static function zip_list()
    {
        // isolate WP code for later separation
        $root_plugin_dir = WP_PLUGIN_DIR;

        $ext = $_REQUEST['ext'] ?? "webp";

        // read the list of files in media zip file
        $xp_data_dir = "$root_plugin_dir/xpress-data";
        $search = "$xp_data_dir/media-$ext-*.zip";
        // debug header
        // header("X-Xp-Debug-zip_list: $search");
        $files = glob($search);
        $list = [];
        if (!empty($files)) {
            $zip_file = $files[count($files) - 1];
            // debug header
            // header("X-Xp-Debug-zip_list: $zip_file");

            $zip = new ZipArchive();
            $zip->open($zip_file);
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $list[] = $zip->getNameIndex($i);
            }
            $zip->close();
        }
        return $list;
    }

    static function key_user_create()
    {
        $res = "";

        $user_c = $_REQUEST['user_c'] ?? "user";
        // sanitize user_c
        $user_c = preg_replace('/[^a-z0-9_]/i', '', $user_c);

        $user_tmax = $_REQUEST['user_tmax'] ?? 0;
        // can be useful to test short expiration time
        $user_tmax = floatval($user_tmax);
        // transform days to seconds
        $user_tmax = $user_tmax * 24 * 60 * 60;

        // check xpress_api_key
        $xpress_api_key = xp_setup::get_option("xpress_api_key");
        if ($xpress_api_key && $user_c && ($user_tmax > 0)) {
            $expiration_time = time() + $user_tmax;
            // compose the api_user_key with xpress_api_key / user_c / user_tmax
            $api_user_key = "$xpress_api_key/$user_c/$expiration_time";
            // encode api_user_key
            $res = md5($api_user_key);
            // header("X-Xp-Debug--key-user-create: $res/$api_user_key");
            $user_datemax = date("Y-m-d H:i:s", $expiration_time);
            $res = "$res/$expiration_time ($user_datemax)";
        }
        return $res;
    }

    static function task_001()
    {
        $res = "(...)";

        // blog name
        // $blog_name = get_bloginfo('name');
        $option_blogname = $_REQUEST['option_blogname'] ?? "";
        $option_blogname = trim($option_blogname);
        if ($option_blogname) {
            update_option('blogname', $option_blogname);
        }

        // blog description
        // $blog_description = get_bloginfo('description');
        $option_blogdescription = $_REQUEST['option_blogdescription'] ?? "";
        $option_blogdescription = trim($option_blogdescription);
        if ($option_blogdescription) {
            update_option('blogdescription', $option_blogdescription);
        }

        // date format
        $option_date_format = $_REQUEST["option_date_format"] ?? "d/m/Y";
        // TODO: isolate WP code for later separation
        update_option("date_format", $option_date_format);

        // time format
        $option_time_format = $_REQUEST["option_time_format"] ?? "H:i";
        update_option("time_format", $option_time_format);

        // 'default_pingback_flag',
        // 'default_ping_status',
        // 'default_comment_status',
        // option comments
        $option_comments = $_REQUEST["option_comments"] ?? "off";
        if ($option_comments == "off") {
            update_option("default_pingback_flag", "0");
            update_option("default_ping_status", "closed");
            update_option("default_comment_status", "closed");
        } else {
            update_option("default_pingback_flag", "1");
            update_option("default_ping_status", "open");
            update_option("default_comment_status", "open");
        }


        // 'show_avatars',
        $option_show_avatars = $_REQUEST["option_show_avatars"] ?? "off";
        if ($option_show_avatars == "off") {
            update_option("show_avatars", "0");
        } else {
            update_option("show_avatars", "1");
        }

        // 'blog_public',
        $option_blog_public = $_REQUEST["option_blog_public"] ?? "off";
        if ($option_blog_public == "off") {
            update_option("blog_public", "0");
        } else {
            update_option("blog_public", "1");
        }

        // build the pages
        $pages = $_REQUEST["pages"] ?? "";
        $pages = trim($pages);
        if ($pages) {
            $page_list = explode("\n", $pages);
            foreach ($page_list as $index => $page) {
                $page = trim($page);
                if ($page) {
                    // sanitize page
                    $page = preg_replace('/[^a-z0-9\-]/i', '', $page);
                    //  to lower
                    $page = strtolower($page);

                    // check if page exists
                    $page_found = get_page_by_path($page);
                    $page_id = 0;
                    if (empty($page_found)) {
                        // create page
                        $page_id = wp_insert_post([
                            'post_title' => $page,
                            'post_name' => $page,
                            'post_type' => 'page',
                            'post_status' => 'publish',
                            // 'comment_status' => 'closed',
                            // 'ping_status' => 'closed',
                        ]);
                    }

                    header("X-Xp-Debug-page-$index: $page_id/$page");
                }
            }
        }

        // build the primary menu
        $menu_primary = $_REQUEST["menu_primary"] ?? "";
        $menu_primary = trim($menu_primary);
        if ($menu_primary) {

            // create menu as post_type wp_navigation
            // check if menu exists
            $menu_found = get_page_by_path("menu-primary", OBJECT, "wp_navigation");
            $menu_id = 0;
            if (empty($menu_found)) {
                // create menu
                $menu_id = wp_insert_post([
                    'post_title' => "Menu Primary",
                    'post_name' => "menu-primary",
                    'post_type' => 'wp_navigation',
                    'post_status' => 'publish',
                    // 'comment_status' => 'closed',
                    // 'ping_status' => 'closed',
                ]);
            } else {
                $menu_id = $menu_found->ID;
            }

            $menu_list = explode("\n", $menu_primary);
            $menu_items = [];
            foreach ($menu_list as $index => $menu_item) {
                $menu_item = trim($menu_item);
                if ($menu_item) {
                    // sanitize menu_item
                    $menu_item = preg_replace('/[^a-z0-9\-]/i', '', $menu_item);
                    //  to lower
                    $menu_item = strtolower($menu_item);

                    // check if page exists
                    $page_found = get_page_by_path($menu_item);
                    if (!empty($page_found)) {
                        // <!-- wp:navigation-link {"label":"products","type":"page","id":117,"url":"https://wp.looomi.com/products","kind":"post-type","isTopLevelLink":true} /-->
                        // build html code
                        $menu_item_html = <<<html
                        <!-- wp:navigation-link {"label":"{$menu_item}","type":"page","id":{$page_found->ID},"url":"{$page_found->guid}","kind":"post-type","isTopLevelLink":true} /-->   
                        html;
                        $menu_items[] = $menu_item_html;
                        // header("X-Xp-Debug-menu-item-$index: $menu_item_html");
                    }
                }
            }
            // update menu content
            $menu_content = implode("\n", $menu_items);
            wp_update_post([
                'ID' => $menu_id,
                'post_content' => $menu_content,
            ]);
        }

        // build the secondary menu
        $menu_secondary = $_REQUEST["menu_secondary"] ?? "";
        $menu_secondary = trim($menu_secondary);
        if ($menu_secondary) {

            // create menu as post_type wp_navigation
            // check if menu exists
            $menu_found = get_page_by_path("menu-secondary", OBJECT, "wp_navigation");
            $menu_id = 0;
            if (empty($menu_found)) {
                // create menu
                $menu_id = wp_insert_post([
                    'post_title' => "Menu Secondary",
                    'post_name' => "menu-secondary",
                    'post_type' => 'wp_navigation',
                    'post_status' => 'publish',
                    // 'comment_status' => 'closed',
                    // 'ping_status' => 'closed',
                ]);
            } else {
                $menu_id = $menu_found->ID;
            }

            $menu_list = explode("\n", $menu_secondary);
            $menu_items = [];
            foreach ($menu_list as $index => $menu_item) {
                $menu_item = trim($menu_item);
                if ($menu_item) {
                    // sanitize menu_item
                    $menu_item = preg_replace('/[^a-z0-9\-]/i', '', $menu_item);
                    //  to lower
                    $menu_item = strtolower($menu_item);

                    // check if page exists
                    $page_found = get_page_by_path($menu_item);
                    if (!empty($page_found)) {
                        // <!-- wp:navigation-link {"label":"products","type":"page","id":117,"url":"https://wp.looomi.com/products","kind":"post-type","isTopLevelLink":true} /-->
                        // build html code
                        $menu_item_html = <<<html
                        <!-- wp:navigation-link {"label":"{$menu_item}","type":"page","id":{$page_found->ID},"url":"{$page_found->guid}","kind":"post-type","isTopLevelLink":true} /-->   
                        html;
                        $menu_items[] = $menu_item_html;
                        // header("X-Xp-Debug-menu-item-$index: $menu_item_html");
                    }
                }
            }
            // update menu content
            $menu_content = implode("\n", $menu_items);
            wp_update_post([
                'ID' => $menu_id,
                'post_content' => $menu_content,
            ]);
        }

        // build the posts
        $posts = $_REQUEST["posts"] ?? "";
        $posts = trim($posts);
        if ($posts) {
            $post_list = explode("\n", $posts);
            foreach ($post_list as $index => $post) {
                $post = trim($post);
                if ($post) {
                    // sanitize post
                    $post = preg_replace('/[^a-z0-9\-]/i', '', $post);
                    //  to lower
                    $post = strtolower($post);

                    // check if post exists
                    $post_found = get_page_by_path($post, post_type: 'post');
                    $post_id = 0;
                    if (empty($post_found)) {
                        // create post
                        $post_id = wp_insert_post([
                            'post_title' => $post,
                            'post_name' => $post,
                            'post_type' => 'post',
                            'post_status' => 'publish',
                            // 'comment_status' => 'closed',
                            // 'ping_status' => 'closed',
                        ]);
                    }

                    header("X-Xp-Debug-post-$index: $post_id/$post");
                }
            }
        }

        // option_page_on_front
        $option_page_on_front = $_REQUEST["option_page_on_front"] ?? "";
        $option_page_on_front = trim($option_page_on_front);
        if ($option_page_on_front) {
            $page_found = get_page_by_path($option_page_on_front);
            if (!empty($page_found)) {
                header("X-Xp-Debug-page_on_front: $page_found->ID/$option_page_on_front");
                update_option("page_on_front", $page_found->ID);
                update_option("show_on_front", "page");
            }
        }
        // option_page_for_posts
        $option_page_for_posts = $_REQUEST["option_page_for_posts"] ?? "";
        $option_page_for_posts = trim($option_page_for_posts);
        if ($option_page_for_posts) {
            $page_found = get_page_by_path($option_page_for_posts);
            if (!empty($page_found)) {
                header("X-Xp-Debug-page_for_posts: $page_found->ID/$option_page_for_posts");
                update_option("page_for_posts", $page_found->ID);
            }
        }

        // option_post_types
        $option_post_types = $_REQUEST["option_post_types"] ?? "";
        $option_post_types = trim($option_post_types);
        if ($option_post_types) {
            $option_post_types = explode(",", $option_post_types);
            $option_post_types = array_map(function ($item) {
                return strtolower(
                    preg_replace('/[^a-z0-9\-_]/i', '', $item)
                );
            }, $option_post_types);
            $option_post_types = array_unique($option_post_types);
            $option_post_types = implode(",", $option_post_types);
            header("X-Xp-Debug-option_post_types: $option_post_types");
            update_option("xp_option_post_types", $option_post_types);
        }
        $option_post_types = $_REQUEST["option_post_types_noui"] ?? "";
        $option_post_types = trim($option_post_types);
        if ($option_post_types) {
            $option_post_types = explode(",", $option_post_types);
            $option_post_types = array_map(function ($item) {
                return strtolower(
                    preg_replace('/[^a-z0-9\-_]/i', '', $item)
                );
            }, $option_post_types);
            $option_post_types = array_unique($option_post_types);
            $option_post_types = implode(",", $option_post_types);
            header("X-Xp-Debug-option_post_types: $option_post_types");
            update_option("xp_option_post_types_noui", $option_post_types);
        }

        return $res;
    }
}
