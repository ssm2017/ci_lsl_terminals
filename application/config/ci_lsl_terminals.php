<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config = array();
$config['ci_lsl_terminal_timeout']	= 3;
// inworld
$config['ci_lsl_terminal_inworld_password']	= '0000';
$config['ci_lsl_terminal_inworld_allowed_networks']	= array(
  '127.0.0.1/22'
);
// terminals list page
$config['ci_lsl_terminal_page_list_limit']	= 10;
$config['ci_lsl_terminal_page_list_sort_by']	= 'region';
$config['ci_lsl_terminal_page_list_sort_order']	= 'asc';
$config['ci_lsl_terminal_page_list_fields']	= array(
  //'uuid' => 'Uuid',
  //'url' => 'Url',
  'name' => 'Name',
  'region' => 'Region',
  'parcel' => 'Parcel',
  'position' => 'Position',
);
// deletion
$config['ci_lsl_terminal_autodelete']	= TRUE;