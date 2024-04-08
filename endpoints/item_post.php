<?php

function api_item_post($request) {
  $titulo = $request['titulo'];
  $desc = $request['desc'];
  $preco = $request['preco'];
  $files = $request->get_file_params();
  $tipo_slug = $request['tipo'];
  $tamanho = $request['tamanho'];
  $tipo_id = get_cat_id($tipo_slug);

  //TODO: Resolver a verificação de erro
  // if (empty($desc) || empty($titulo) || empty($preco) || empty($tipo) || !isset($files)) {
  //   $response = new WP_Error('error', 'Dados incompletos.', ['status' => 422]);
  //   return rest_ensure_response($response);
  // }
  if(!$tipo_id) {
    $response = new WP_Error('error', 'Categoria não encontrada.', ['status' => 422]);
    return rest_ensure_response($response);
  }

  $response = [
    'post_author' => 'master',
    'post_type' => 'post',
    'post_status' => 'publish',
    'post_title' => $titulo,
    'post_content' => $desc,
    'files' => $files,
    'meta_input' => [
      'preco' => $preco,
      'tipo' => $tipo_slug,
      'tamanho' => $tamanho
    ],
  ];
  $post_id = wp_insert_post($response);
  wp_set_post_categories($post_id, [$tipo_id]);

  require_once ABSPATH . 'wp-admin/includes/image.php';
  require_once ABSPATH . 'wp-admin/includes/file.php';
  require_once ABSPATH . 'wp-admin/includes/media.php';

  $photo_id = media_handle_upload('img', $post_id);
  update_post_meta($post_id, 'img', $photo_id);
  return rest_ensure_response($response);
}

function register_api_item_post() {
  register_rest_route('api', '/item', [
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'api_item_post',
  ]);
}
add_action('rest_api_init', 'register_api_item_post');

?>