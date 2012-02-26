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

/**
* Check allowed access
*/
if (!function_exists('check_access')) {
  function check_access($allowed_networks) {
    $allowed = FALSE;
    foreach($allowed_networks as $network) {
      if(check_net_match($network,$_SERVER['REMOTE_ADDR'])) {
        $allowed = TRUE;
        break;
      }
    }
    return $allowed;
  }
}

/**
* Check the ip.
* Authors: Falados Kapuskas, JoeTheCatboy Freelunch
*/
if (!function_exists('check_net_match')) {
  function check_net_match($network, $ip) {
    // determines if a network in the form of 192.168.17.1/16 or
    // 127.0.0.1/255.255.255.255 or 10.0.0.1 matches a given ip
    $ip_arr = explode('/', $network);
    $network_long = ip2long($ip_arr[0]);

    $x = ip2long($ip_arr[1]);
    $mask =  long2ip($x) == $ip_arr[1] ? $x : 0xffffffff << (32 - $ip_arr[1]);
    $ip_long = ip2long($ip);

    return ($ip_long & $mask) == ($network_long & $mask);
  }
}

/**
* Get values
*/
if (!function_exists('get_values')) {
  function get_values() {
    $values = new stdClass();
    $values->uuid         = $_SERVER['HTTP_X_SECONDLIFE_OBJECT_KEY'];
    $values->name         = $_SERVER['HTTP_X_SECONDLIFE_OBJECT_NAME'];
    $values->owner_key 		= $_SERVER['HTTP_X_SECONDLIFE_OWNER_KEY'];
    $values->owner_name 	= $_SERVER['HTTP_X_SECONDLIFE_OWNER_NAME'];
    $values->region 			= $_SERVER['HTTP_X_SECONDLIFE_REGION'];
    $values->position 		= $_SERVER['HTTP_X_SECONDLIFE_LOCAL_POSITION'];
    return $values;
  }
}

if (!function_exists('parse_region')) {
  function parse_region(&$terminal) {
    preg_match_all('/(.*) \((\d+), (\d+)\)/', $terminal->region, $temp);
    $terminal->region_name = $temp[1][0];
    $terminal->region_x    = $temp[2][0];
    $terminal->region_y    = $temp[3][0];
    $terminal->region_xx   = floor($terminal->region_x);
    $terminal->region_yy   = floor($terminal->region_y);
  }
}

if (!function_exists('parse_position')) {
  function parse_position(&$terminal) {
    preg_match_all('/\((.*), (.*), (.*)\)/', $terminal->position, $temp);
    $terminal->position_x  = $temp[1][0];
    $terminal->position_y  = $temp[2][0];
    $terminal->position_z  = $temp[3][0];
    $terminal->position_xx = floor($terminal->position_x);
    $terminal->position_yy = floor($terminal->position_y);
    $terminal->position_zz = floor($terminal->position_z);
  }
}

/**
* Check password
*/
if (!function_exists('check_password')) {
  function check_password($password, $key, $stored_pass) {
    if ($password != md5($stored_pass.":".$key)) {
      return FALSE;
    }
    return TRUE;
  }
}