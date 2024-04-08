<?php
//remove_action('rest_api_init', 'create_initial_rest_routes', 99);

add_filter( 'rest_endpoints', function($endpoints){
    unset($endpoints['/wp/v2/users']);
    unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
    return $endpoints;
});

$dirbase = get_template_directory();

require_once $dirbase . '/endpoints/item_post.php';
require_once $dirbase . '/endpoints/item_delete.php';
require_once $dirbase . '/endpoints/item_get.php';
require_once $dirbase . '/endpoints/item_put.php';


require_once $dirbase . '/endpoints/email_send.php';

update_option('large_size_w', 1000);
update_option('large_size_h', 1000);
update_option('large_crop', 1);


function change_api() {
    return 'json';
}

add_filter('rest_url_prefix','change_api');

?>