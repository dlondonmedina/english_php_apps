<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Events_Model extends CI_Model {

  public $user;

  public function __construct() {
    parent::__construct();
    $this->load->database('engl');
    $this->user = $this->config->item('uw_user');

  }

  public function get_events($id = FALSE) {
    if ($id === FALSE) {
      $query = $this->db->get('announcements');
      return $query->result_array();
    }

    $query = $this->db->get_where('announcements', array('id' => $id));
    return $query->row_array();
  }

  public function set_events($id = FALSE) {
     $this->load->helper('url');
     // process datetime
     $time = $this->input->post('hour');
     if ($this->input->post('daypart') == "PM") {
        $time += 12;
     }
     $time = $time . ':' . $this->input->post('minute');
     $datetime = $this->input->post('date') . ' ' . $time;
     $data = array(
        'creator' => $this->input->post('netid'),
        'title' => $this->input->post('title'),
        'speaker' => $this->input->post('speaker'),
        'dt' => $datetime,
        'place' => $this->input->post('place'),
        'description' => $this->input->post('description'),
     );
     $d = date_create($datetime);

     // prep flyer there is where we can change the format.
     $text = '
     <div style="text-align:center">
     <br />
     <br />
     <h3>The Department of English <br/> University of Washington</h3>
     <p>Invites you to a talk by:</p>
     <h1>' . $this->input->post('speaker') . '</h1>
     <h2>' . $this->input->post('title') . '</h2>
     <p>' . $this->input->post('description') . '</p>
     <p>' . date_format($d, 'l') . ', ' . date_format($d, 'F j') . ', ' . date_format($d, 'Y') . '<br/>
       ' . date_format($d, 'g:i A') . '<br />
       ' . $this->input->post('place') .'
     </p>
     </div>
     ';
     $data['flyer_text'] = $text;
     if ($id != FALSE) {
        $this->db->where('id', $id);
        return $this->db->update('announcements', $data);
     } else {
        return $this->db->insert('announcements', $data);
     }
  }

  public function can_edit_event($user_id = NULL) {

      $query = $this->db->get_where('people', array('uwnetid' => $user_id));
      $user = $query->row_array();
      $type = $user['TypeID'];
      // Check with Rob
      return in_array($type, [50, 51]) ? TRUE : FALSE;

  }
}
