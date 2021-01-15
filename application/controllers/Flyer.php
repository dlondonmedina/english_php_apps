<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Flyer extends CI_Controller {
  public $user;

  public function __construct() {
    parent::__construct();

    $this->user = $this->config->item('uw_user');

    $this->load->model('flyer_model');
    $this->load->model('engl_model');
    $this->load->helper('url');

  }

  public function index() {
    $data['title'] = "Create a Flyer";
    $data['custom_header'] = '
    <script src="https://cloud.tinymce.com/stable/tinymce.min.js"></script>
    <script>
    tinymce.init({
      selector:"textarea",
      height: "400"
    });
    </script>
    <script src="/js/docxtemplater.js"></script>
    <script src="/js/jszip.js"></script>
    <script src="/js/jszip-utils.js"></script>
    <script src="/js/file-saver.min.js"></script>
    <script src="/js/makeFile.js"></script>';

    $data['custom_footer'] = '<script src="/js/makeDocx.js"></script>';

    $this->load->view('templates/header', $data);
    $this->load->view('flyer/view', $data);
    $this->load->view('templates/footer', $data);

  }


  public function create($id = NULL, $table = 'announcements') {
    if (!$id) {
      $data['title'] = "Something Went Wrong.";
      $this->load->view('templates/header', $data);
      $this->load->view('pages/refused');
      $this->load->view('templates/footer');
    } else {
      $data['title'] = "Create a Flyer!";
      $result = $this->flyer_model->get_content($id, $table);
      if (!$result) {
        show_404();
      }
      // convert $content to json and so it can be passed to my function.
      $dt = date_create($result['dt']);
      $result['date'] = date_format($dt, 'l') . ', ' . date_format($dt, 'F j') .
          ', ' . date_format($dt, 'Y');
      $result['time'] = date_format($dt, 'g:i A');
      $data['content'] = json_encode($result);
      $text = trim(preg_replace('/\s+/', ' ', $result['flyer_text']));
      $data['custom_header'] = '
      <script src="https://cloud.tinymce.com/stable/tinymce.min.js"></script>
      <script>
      tinymce.init({
        selector:"textarea",
        height:"400",
        init_instance_callback : function(editor) {
          tinymce.activeEditor.setContent(\'' . $text . '\');
        }
      });
      </script>
      <script src="/js/docxtemplater.js"></script>
      <script src="/js/jszip.js"></script>
      <script src="/js/jszip-utils.js"></script>
      <script src="/js/file-saver.min.js"></script>';
      $data['custom_footer'] = '<script src="/js/makeDocx.js"></script>';
      $this->load->view('templates/header', $data);
      $this->load->view('flyer/view', $data);
      $this->load->view('templates/footer', $data);
    }
  }
}
