<?php

/**
 * @package    ci_lsl_terminals
 * @copyright Copyright (C) 2012 Wene - ssm2017 Binder ( S.Massiaux ). All rights reserved.
 * @license   GNU/GPL, http://www.gnu.org/licenses/gpl-2.0.html
 * ci_lsl_terminals is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

if (!defined('FIELD_SEPARATOR'))
  define('FIELD_SEPARATOR', 'Ⅱ');
if (!defined('VALUE_SEPARATOR'))
  define('VALUE_SEPARATOR', 'Ⅰ');

class Inworld extends CI_Controller {

  function index() {
    echo "ok";
  }

  function update_terminal() {

    // load some usefull files
    $this->load->helper('ci_lsl_terminals_helper');
    $this->config->load('ci_lsl_terminals');

    // chek for the network
    if (!check_access($this->config->item('ci_lsl_terminal_inworld_allowed_networks'))) {
      echo '70053'. VALUE_SEPARATOR. 'Not authorized';
      return;
    }

    // check for the password
    if (!check_password($this->input->get_post('password'), $this->input->get_post('key'), $this->config->item('ci_lsl_terminal_inworld_password'))) {
      echo '70053'. VALUE_SEPARATOR. 'Wrong password';
      return;
    }

    // get values
    $values = get_values();

    // fill the values
    $data = array(
      'uuid' => $values->uuid,
      'url' => $this->input->get_post('url'),
      'name' => $this->input->get_post('name'),
      'parcel' => $this->input->get_post('parcel'),
      'region' => $values->region,
      'position' => $values->position
    );

    // check values
    if (!$data['url']) {
      echo '70053'. VALUE_SEPARATOR. 'Missing values';
      return;
    }

    // update the terminal
    $this->load->model('Terminals_model');
    echo $this->Terminals_model->update_terminal($data);
  }
  function test() {
    $this->config->load('ci_lsl_terminals');
    $url = 'http://inux:9000/lslhttp/685ca85a-cc5a-483b-ad44-8374115f8240/speaker?sentence=say%20hello';
    $ctx = stream_context_create(
      array(
        'http' => array(
          'timeout' => $this->config->item('ci_lsl_terminal_timeout')
        )
      )
    );
    $answer = @file_get_contents($url, 0, $ctx);
    if ($answer) {
      echo $answer;
    }
    else {
      echo "no answer";
    }
  }

}