<?php


function photo_data($post) {
$post_meta = get_post_meta( $post->ID);
$src = wp_get_attachment_image_src( $post_meta['img'][0], 'large' )[0];

    return [
        'id' => $post->ID,
        'title' => $post->post_title,
        'src' => $src,
        'desc' => $post->post_content,
        'preco' => $post_meta['preco'][0],
        'tipo' => $post_meta['tipo'][0],
    ];
}


function api_item_get($request) {
  $post_id = $request['id'];
  $post = get_post($post_id);


 /*
  RESOLVER O PROBLEMA DA VERIFICAÇÃO DE ERRO E ZERAR OS ID'S
  if(!isset($post) || empty($post_id) || !isset($post_meta['img'][0]) || empty($post_meta['img'][0])) {
    $response = new WP_Error('error', 'Post não encontrado', ['status' => 404]);
    return rest_ensure_response($response);
  }

  */

  $photo = photo_data($post);

  $response = [
    'photo' => $photo,
  ];

  return rest_ensure_response($response);
}

function register_api_item_get() {
  register_rest_route('api', '/item/(?P<id>[0-9]+)', [
    'methods' => WP_REST_Server::READABLE,
    'callback' => 'api_item_get',
  ]);
}
add_action('rest_api_init', 'register_api_item_get');


//GET ALL PHOTOS


function api_photos_get($request) {
  $_total = sanitize_text_field($request['_total']) ?: 6;
  $_page = sanitize_text_field($request['_page']) ?: 1;

  $args = [
  'post_type' => 'post',
  'posts_per_page' => $_total,
  'paged' => $_page,
  'category_name' => $category_slug,
];

  $query = new WP_Query($args);
  $posts = $query->posts;

  $photos = [];
  if($posts) {
    foreach ($posts as $post) {
      $photos[] = photo_data($post);
    }
  }
    return rest_ensure_response($photos);
}


function register_api_photos_get() {
  register_rest_route('api', '/category', [
    'methods' => WP_REST_Server::READABLE,
    'callback' => 'api_photos_get',
  ]);
}
add_action('rest_api_init', 'register_api_photos_get');




//GET CATEGORY

function api_item_get_category($request) {
  $_total = sanitize_text_field($request['_total']) ?: 6;
  $_page = sanitize_text_field($request['_page']) ?: 1;

  $category_slug = $request['category'];
  $category = get_category_by_slug($category_slug);
  
    if (empty($category)) {
      $response = new WP_Error('error', 'Categoria não encontrada', ['status' => 404]);
      return rest_ensure_response($response);
    }

    $args = [
      'post_type' => 'post',
      'posts_per_page' => $_total,
      'paged' => $_page,
      'category_name' => $category_slug,
    ];

    $query = new WP_Query($args);
    $posts = $query->posts;
    $item = []; 
    if($posts) {
      foreach ($posts as $post) {
        $item[] = photo_data($post);
      
      }
      return rest_ensure_response($item);
    } else {
  $response = new WP_Error('error', 'Nenhum item encontrado para a categoria especificada', ['status' => 404]);
  return rest_ensure_response($response);
}
}

function register_api_item_get_category() {
  register_rest_route('api', '/category/(?P<category>[a-zA-Z0-9-]+)', [
    'methods' => WP_REST_Server::READABLE,
    'callback' => 'api_item_get_category',
  ]);
}
add_action('rest_api_init', 'register_api_item_get_category');



?>