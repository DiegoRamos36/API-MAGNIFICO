<?php

function api_item_delete($request) {
  $post_id = $request['id'];
  $user = wp_get_current_user();
  $post = get_post($post_id);


  wp_delete_post($post_id, true);

  return rest_ensure_response('Post deletado.');
}

function register_api_item_delete() {
  register_rest_route('api', '/item/(?P<id>[0-9]+)', [
    'methods' => WP_REST_Server::DELETABLE,
    'callback' => 'api_item_delete',
  ]);
}
add_action('rest_api_init', 'register_api_item_delete');

?>