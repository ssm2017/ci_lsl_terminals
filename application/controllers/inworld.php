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

class Inworld extends CI_Controller {

  function index() {
    echo "ok";
  }

  function update_terminal() {
    // get values
    $values = array(
      'uuid' => $this->input->get_post('uuid'),
      'url' => $this->input->get_post('url'),
      'name' => $this->input->get_post('name'),
      'region' => $this->input->get_post('region'),
      'parcel' => $this->input->get_post('parcel'),
      'position' => $this->input->get_post('position'),
      'password' => $this->input->get_post('password'),
      'key' => $this->input->get_post('key')
    );

    // check values
    if (!$values['uuid'] || !$values['url']) {
      echo '70053|Missing values';
      return;
    }

    // get config

    // check the password
    unset($values['password']);
    unset($values['key']);

    // update the terminal
    $this->load->model('Terminals_model');
    echo $this->Terminals_model->update_terminal($values);
  }

}