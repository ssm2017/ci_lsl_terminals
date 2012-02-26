<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class pages_model extends CI_Model {

  function get_terminals($limit = NULL, $offset = NULL) {
    $terminals = array();
    $this->db->select('*')->from('ci_lsl_terminals');
    $this->db->limit($limit, $offset);
    $query = $this->db->get();
    if ($query->num_rows() > 0) {
      foreach ($query->result() as $row) {
        $terminals[] = $row;
      }
      return $terminals;
    }
    else {
      return NULL;
    }
  }

  function count_terminals() {
    return $this->db->count_all_results('ci_lsl_terminals');
  }

}