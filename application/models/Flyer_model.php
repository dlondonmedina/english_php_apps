<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Flyer_Model extends CI_Model {

  public function __construct() {
    parent::__construct();
    $this->load->database('engl');
  }

  public function get_content($id, $table = NULL) {
    $table = $table ? $table : 'announcements';
    $query = $this->db->get_where($table, array('id' => $id ));

    return $query->row_array();

  }

}
