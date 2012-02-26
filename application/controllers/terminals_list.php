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

class Terminals_list extends CI_Controller {

  function index($sort_by = NULL, $sort_order = NULL, $offset = 0) {

    // load some usefull files
    $this->config->load('ci_lsl_terminals');

    // define start values
    $limit = $this->config->item('ci_lsl_terminal_page_list_limit');
    $data = array(
      'fields' => $this->config->item('ci_lsl_terminal_page_list_fields'),
      'sort_by' => (!is_null($sort_by)) ? $sort_by : $this->config->item('ci_lsl_terminal_page_list_sort_by'),
      'sort_order' => (!is_null($sort_order)) ? $sort_order : $this->config->item('ci_lsl_terminal_page_list_sort_order')
    );

    // get the terminals
		$this->load->model('Terminals_model');
		$results = $this->Terminals_model->get_terminbals_list($limit, $offset, $data['sort_by'], $data['sort_order'], array_keys($data['fields']));
		$data['terminals'] = $results['rows'];
		$data['num_results'] = $results['num_rows'];

		// pagination
		$this->load->library('pagination');
    $this->load->helper('url');
		$config = array();
		$config['base_url'] = site_url('terminals_list/index/'. $data['sort_by']. '/'. $data['sort_order']);
		$config['total_rows'] = $data['num_results'];
		$config['per_page'] = $limit;
		$config['uri_segment'] = 5;
    $config['next_link'] = 'Next';
    $config['prev_link'] = 'Prev';
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();

    // show the output
    $header_data = array(
      'title' => 'Terminals',
      'scripts' => '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript" charset="utf-8"></script>',
      'css' => '<link rel="stylesheet" type="text/css" href="'. site_url('assets/css/ci_lsl_terminals.css'). '" />'
    );
    $this->load->view('includes/header', $header_data);
		$this->load->view('table', $data);
    $this->load->view('includes/footer');
	}

}