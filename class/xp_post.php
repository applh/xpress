<?php

class xp_post 
{
    static function read_list ($post_type='post', $post_status='publish')
    {
        $select = [
            'post_type' => $post_type,
            'posts_per_page' => -1,
            'orderby' => 'ID',
            'order' => 'DESC',
        ];
        // add post_status if not empty
        if ($post_status) {
            $select['post_status'] = $post_status;
        }
        else {
            // https://developer.wordpress.org/reference/functions/get_posts/
            $select['post_status'] = 'any';
        }

        // read posts
        $posts = get_posts($select);

        // if post_status is not publish then filter


        // $list = [];
        // foreach ($posts as $post) {
        //     $list[] = [
        //         'id' => $post->ID,
        //         'title' => $post->post_title,
        //         'url' => get_permalink($post->ID)
        //     ];
        // }

        return $list ?? $posts ?? [];

    }
}