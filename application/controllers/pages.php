<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class pages extends CI_Controller {

  function index() {

    $this->load->library('pagination');
    $this->load->helper('url');

    $this->load->model('pages_model');
    $per_page = 10;
    $total = $this->pages_model->count_terminals();
    $data = array();
    $data['terminals'] = $this->pages_model->get_terminals($per_page, $this->uri->segment(3));

    $config = array();
    $config['base_url'] = base_url(). 'pages/index/';
    $config['total_rows'] = $total;
    $config['per_page'] = $per_page;
    $config['uri_segment'] = '3';
    $config['next_link'] = 'Next;';
    $config['prev_link'] = 'Prev';

    $this->pagination->initialize($config);

    $this->load->view('includes/header');
    $this->load->view('list', $data);
    $this->load->view('includes/footer');
  }

}