<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Ajax extends CI_Controller {

  function index() {
    echo "ok";
  }

  function check_terminal() {
    $url = $this->input->get('url');
    if (!$url) {
      echo "Missing arguments";
      return;
    }
    $ctx = stream_context_create(
        array(
          'http' => array(
            'timeout' => 3
          )
        )
    );
    $answer = @file_get_contents($url, 0, $ctx);
    if ($answer) {
      echo '<span style="color:green">' . $answer . '</span>';
    }
    else {
      echo '<span style="color:red">Offline</span>';
    }
  }

}