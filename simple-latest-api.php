<?php

/*
Plugin Name: Simple Latest API
Plugin URI: https://github.com/reneses/simple-latest-api/
Description: Simple endpoint to retrieve the latest posts in JSON format, accessible at: <code>/api-latest/{per_page}/{page}</code>. IMPORTANT: Once installed, you have to flush and regenerate the rewrite rules database, by going to 'Settings -> Permalinks' and just clicking 'Save Changes'
Version: 1.0
Author: Alvaro Reneses
Author URI: http://www.reneses.io
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Simple Latest API is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Simple Latest API is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Simple Latest API. If not, see {License URI}.
*/


/**
 * Add the endpoint /api-latest/{per_page}/{page}
 */
function api_last_endpoint()
{
    add_rewrite_tag('%api_latest_per_page%', '([^&]+)');
    add_rewrite_tag('%api_latest_page%', '([^&]+)');
    add_rewrite_rule('api-latest/(\d+)/(\d+)/?', 'index.php?api_latest_per_page=$matches[1]&api_latest_page=$matches[2]', 'top');
}

add_action('init', 'api_last_endpoint');


/**
 * Bind the logic to the endpoint
 */
function api_last_process()
{

    // Load the variables and check that we have been called by the API
    global $wp_query;
    $per_page = $wp_query->get('api_latest_per_page');
    $page = $wp_query->get('api_latest_page');
    if (!$per_page || !$page) {
        return;
    }

    // Query and process the data
    $output = [];
    $args = [
        'orderby' => 'post_date',
        'order' => 'DESC',
        'paged' => $page,
        'showposts' => $per_page,
    ];
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            global $post;
            $output[] = [
                'link' => get_the_permalink(),
                'categories' => array_map(function ($category) {
                    return [
                        'name' => $category->name,
                        'slug' => $category->slug,
                        'link' => get_category_link($category->cat_ID)
                    ];

                }, get_the_category()),
                'image' => wp_get_attachment_url(get_post_thumbnail_id($post->ID)),
                'title' => get_the_title(),
                'timestamp' => get_post_time('U', true) * 1000,
                'author' => [
                    'name' => get_the_author(),
                    'link' => get_author_posts_url(get_the_author_meta('ID'), get_the_author_meta('user_nicename')),
                    'html_link' => get_the_author_posts_link(),
                ]
            ];
        }
        wp_reset_postdata();
    }
    wp_send_json($output);
}

add_action('template_redirect', 'api_last_process');