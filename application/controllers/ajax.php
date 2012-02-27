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

class Ajax extends CI_Controller {

  function index() {
    echo "ok";
  }

  function check_terminal() {
    $this->config->load('ci_lsl_terminals');
    $url = $this->input->get('url');

    if (!$url) {
      echo "Missing arguments";
      return;
    }
    $ctx = stream_context_create(
      array(
        'http' => array(
          'timeout' => $this->config->item('ci_lsl_terminal_timeout')
        )
      )
    );
    $answer = @file_get_contents($url, 0, $ctx);
    if ($answer) {
      echo '<span class="online">Online</span>';
    }
    else {
      $delete = $this->input->get('autodelete');
      if ($delete) {
        $this->config->load('ci_lsl_terminals');
        if ($this->config->item('ci_lsl_terminal_autodelete')) {
          $this->load->model('Terminals_model');
          $this->Terminals_model->delete_terminal_by_url($url);
          echo '<span class="offline">Deleted</span>';
        }
      }
      else {
        echo '<span class="offline">Offline</span>';
      }
    }
  }

}