<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Events extends CI_Controller {
   public $user;
   public $english;

  public function __construct() {
    parent::__construct();

    // For Testing
    $this->user = $this->config->item('uw_user');
    // Done Testing

    $this->load->model('engl_model');
    $this->load->model('events_model');
    $this->load->helper('url_helper');

    $this->english = $this->engl_model->is_english($this->user);
  }

  public function index() {
    $data['title'] = "Events";
    $data['events'] = $this->events_model->get_events();

    if (!$data['events']) {
      $data['events'] = [
        [
          'id' => 1,
          'title' => "placeholder",
          'speaker' => "placeholder",
          'dt' => "2017-2-3 23:23:00",
          'place' => "Padelford 204"
        ],
      ];
    }
    $is_english = $this->engl_model->is_english($this->user);
    if ($is_english) {
      $data['create_button'] = '<button type="button" name="button" class="btn btn-success"
        onclick="location.href=\'/events/create\'">Create New Event!</button>';
    }
    $this->load->view('templates/header', $data);
    $this->load->view('events/index', $data);
    $this->load->view('templates/footer');
  }

  public function view($id = FALSE) {
    $data['title'] = "Events";
    if ($id === FALSE) {
        show_404();
    } else {
      $data['event'] = $this->events_model->get_events($id);
    }
    $can_edit = $this->events_model->can_edit_event($this->user);
    if ($can_edit || $data['event']['creator'] == $this->user) {
      $data['edit_button'] = '<button type="button" name="button" class="btn btn-danger"
            onclick="location.href=\'/events/create/' . $data['event']['id'] . '\'">
            Edit Event!</button>';
      $data['flyer_button'] = '<button type="button" name="button" class="btn btn-success"
            onclick="location.href=\'/flyer/create/' . $data['event']['id'] . '\'">
            Create Flyer!</button>';
    }
    $this->load->view('templates/header', $data);
    $this->load->view('events/view', $data);
    $this->load->view('templates/footer');
  }

  public function create($edit = FALSE) {
    // Check if user is allowed to be here at all
    if (!$this->english) {
      $data['title'] = "Something Went Wrong.";
      $this->load->view('templates/header', $data);
      $this->load->view('pages/refused');
      $this->load->view('templates/footer');
   } else {
     if ($edit) {
       $data['event'] = $this->events_model->get_events($edit);
       $data['title'] = "Edit your event";
     } else {
       $data['event'] = NULL;
       $data['title'] = "Create a new event";
     }

     $this->load->helper('form');
     $this->load->library('form_validation');
     $data['events'] = $this->events_model->get_events();

     // Create validation rules
     $this->form_validation->set_rules('netid', 'NetID', 'required');
     $this->form_validation->set_rules('title', 'Title', 'required');
     $this->form_validation->set_rules('speaker', 'Speaker', 'required');
     $this->form_validation->set_rules('place', 'Place', 'required');
     $this->form_validation->set_rules('date', 'Date', 'required');
     $this->form_validation->set_rules('hour', 'Hour', 'required');
     $this->form_validation->set_rules('minute', 'Minute', 'required');
     $this->form_validation->set_rules('daypart', 'AM/PM', 'required');
     $this->form_validation->set_rules('description', 'Description', 'required');
     // Check if validation worked
     if ($this->form_validation->run() === FALSE) {
       // reroute to create view
       $this->load->view('templates/header', $data);
       $this->load->view('events/create', $data);
       $this->load->view('templates/footer');
     } else {
       $this->events_model->set_events($edit);
       $this->load->view('events/success');
     }

   }

 }
}
