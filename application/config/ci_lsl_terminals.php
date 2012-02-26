<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config = array();
// inworld
$config['ci_lsl_terminal_inworld_password']	= '0000';
// terminals list page
$config['ci_lsl_terminal_page_list_limit']	= 3;
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
