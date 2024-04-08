<?php

function api_item_put($request) {
  $post_id =  (int) $request['id'];
  $desc = $request['desc'];
  $tipo_slug =  $request['tipo'];
  $titulo = $request['titulo'];
  $preco = $request['preco'];

  /* 
  RESOLVER: VERIFICAÇÃO DE ERROS
  */

  $tipo_category = get_category_by_slug($tipo_slug);
  $tipo_id = $tipo_category->term_id;

  // $user = wp_get_current_user();
  // $post = get_post($post_id);

  $post_data = [
    'ID' => $post_id,
    'post_content' => $desc,
    'post_title' => $titulo,
    'meta_input' => [
      'preco' => $preco,
      'tipo' => $tipo_slug,
    ],
  ];
  $post_updated = wp_update_post($post_data);
  wp_set_post_categories($post_id, array($tipo_id));
  return rest_ensure_response(array('message' => 'Post atualizado com sucesso.', 'post_data' => $post_data));
}

function register_api_item_put() {
  register_rest_route('api', '/item/(?P<id>[0-9]+)', [
    'methods' => WP_REST_Server::EDITABLE,
    'callback' => 'api_item_put',
  ]);
}
add_action('rest_api_init', 'register_api_item_put');

?>