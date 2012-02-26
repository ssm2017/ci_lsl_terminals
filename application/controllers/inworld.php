<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Inworld extends CI_Controller {

  function index() {
    echo "success|true;message|ok";
  }

  function update_terminal() {
    $uuid = $this->input->get_post('uuid');
    $url = $this->input->get_post('url');
    if (!$uuid || !$url) {
      echo 'success|false;message|missing values';
      return;
    }
    $this->load->model('Inworld_model');
    echo $this->Inworld_model->update_terminal($uuid, urldecode($url));
  }

}