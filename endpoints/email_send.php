<?php

function api_email_send($request) {
  $email = $request['email'];
  $vaga = $request['vaga'];
  $message = $request['message'];
  $interesse = $request['interesse'];
  $publi = $request['publi'];
  $nomeCompleto = $request['name'];


  if (empty($nomeCompleto) || empty($email) || empty($vaga) || empty($message) || empty($interesse) || empty($publi)) {
   $response = new WP_Error('error', 'Dados incompletos.', ['status' => 422]);
   return rest_ensure_response($response);
 }

  $message_content = "Vaga: $vaga\nInteresse: $interesse\nPubli: $publi \nConcorrente: $nomeCompleto\n\n$message";

  $response = wp_mail('diegodarkz36@gmail.com', 'Vaga Dom El Magnifico', $message_content);

  if ($result) {
    return rest_ensure_response($response);
  } else {
    return new WP_Error('email_send_error', 'Erro ao enviar o email', ['status' => 500]);
  }
}

function register_api_email_send() {
  register_rest_route('api', '/email/send', [
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'api_email_send',
  ]);
}
add_action('rest_api_init', 'register_api_email_send');

?>