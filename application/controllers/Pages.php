<?php
class Pages extends CI_controller {

	public function view($page = 'home')
	{

		if ( ! file_exists(APPPATH.'views/pages/'.$page.'.php'))
		{
			// Whoops, we don't have a page for that!
			show_404();
		}

		$data['title'] = ucfirst($page);  // Cap 1st letter
		$data['list'] = ['first', 'second', 'third', 'fourth', 'fifth'];

		$this->load->view('templates/header', $data);
		$this->load->view('pages/'.$page, $data);
		$this->load->view('templates/footer', $data);
	}

}

