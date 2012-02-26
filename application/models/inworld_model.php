<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Inworld_model extends CI_Model {

  function update_terminal($uuid, $url) {
    // check if rentbox exists
    $data = array(
      'uuid' => $uuid,
      'url' => $url
    );
    $exists = $this->db->get_where('ci_lsl_terminals', array('uuid' => $uuid))->num_rows() > 0;
    if ($exists) {
      $this->db->where('uuid', $uuid);
      unset($data['uuid']);
      $this->db->update('ci_lsl_terminals', $data);
      return 'success|true;message|update';
    }
    else {
      $this->db->insert('ci_lsl_terminals', $data);
      return 'success|true;message|insert';
    }
  }

}