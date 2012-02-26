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

class Terminals_model extends CI_Model {

  function update_terminal($values) {
    // check if rentbox exists
    $exists = $this->db->get_where('ci_lsl_terminals', array('uuid' => $values['uuid']))->num_rows() > 0;
    if ($exists) {
      $this->db->where('uuid', $values['uuid']);
      unset($values['uuid']);
      $this->db->update('ci_lsl_terminals', $values);
      return '70052';
    }
    else {
      $this->db->insert('ci_lsl_terminals', $values);
      return '70051';
    }
  }

  function get_terminbals_list($limit, $offset, $sort_by, $sort_order, $keys) {

		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_columns = $keys;
		$sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'region';

		// results query
		$q = $this->db->select(',', implode($keys))
			->from('ci_lsl_terminals')
			->limit($limit, $offset)
			->order_by($sort_by, $sort_order);

		$ret['rows'] = $q->get()->result();
    $ret['num_rows'] = $this->db->count_all_results('ci_lsl_terminals');

		return $ret;
	}

}