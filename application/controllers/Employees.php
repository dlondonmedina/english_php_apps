<?php
class Employees extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('engl_model');
		$this->load->helper('url_helper');
	}

	// rw: This controller knows about the model and the view.
	private function _lastname_firstname($a)
	{
		foreach ($a as $v) {
			$s = 
				( (trim($v['Room'])) ? ', ' . $v['Room'] : '') . 
				( (trim($v['Hours'])) ? ', ' . $v['Hours'] : '') .
				( (trim($v['hireyear'])) ? ', ' . 'hired ' . $v['hireyear'] : '');

			$lf[] = 
				'<a href="/ci/edit_profile/' . $v['employID'] . '">' .
				$v['Last'] . ', ' . 
				$v['First'] . 
				' (' . $v['uwnetid'] . ')' .
				'</a>' .
				'<br>' . 
				substr($s, 1)  // remove initial ','
				;
		}
		return $lf;
	}

	private function _get_profiler_settings() 
	{
		return $sections = [
			'benchmarks' => TRUE,
			'config' => FALSE,
			'controller_info' => FALSE,
			'get' => FALSE,
			'http_headers' => FALSE,
			'memory_usage' => FALSE,
			'post' => FALSE,
			'queries' => TRUE,
			'uri_string' => FALSE,
			'session_data' => FALSE,
			'query_toggle_count' => FALSE
		];
	}

	public function index($employee_type = NULL, $hire_year = NULL)
	{
		$employees = $this->engl_model->get_employees($employee_type, $hire_year);

		if (empty($employees)) 
		{
			$data['title'] = 'Sorry.  Nothing found.';
		}
		else 
		{
			$data['title'] = '(' . count($employees) . ') ' . $employee_type;
			if ($hire_year) $data['title'] .= " since $hire_year";
		}

		$data['list'] = $this->_lastname_firstname($employees);

		$this->output->set_profiler_sections( $this->_get_profiler_settings() );
		$this->output->enable_profiler(TRUE);

		$this->load->view('templates/header', $data);
		$this->load->view('engl/index', $data);
		$this->load->view('templates/footer');
	}


	public function edit_profile($id = NULL)
	{
		// If CANCEL is a submit input on the form, this is one way to handle canceling form input.
		// if (null !== $this->input->post('cancel')) 
		// {
		// 	redirect('employees');
		// }

		$this->load->helper('form');
		$this->load->library('form_validation');

		// set_rules(): name of input field, text to use in error, the rule
		$this->form_validation->set_rules('employID', 'EmployID', 'required');
		$this->form_validation->set_rules('last', 'Last', 'required');
		$this->form_validation->set_rules('uwnetid', 'UWNetID', 'required');

		if ($this->form_validation->run() === FALSE)
		{
			// If nothing passed to this method, must be coming from submit.
			$data['id'] = ($id) ? $id : $this->input->post('employID');
			$employee = $this->engl_model->get_profile($id);
			if (empty($employee)) 
			{
				$data['title'] = 'Sorry.  Nothing found.';
			}
			else 
			{
				// The Model limits the result to the first employee found.
				$data['title'] = $employee['last'] . "'s Profile";
				$data['employee'] = $employee;

				// GET_CODES(string) returns simple associative array, ready for CI's form_dropdown() method.
				// Current strings are:  'typeID', 'qtr', 'unit', 'status' (see engl.codes & engl.people tables).
				// If bad string is passed, get_codes() returns NULL.  
				$data['dept_status'] = $this->engl_model->get_codes('status');
				$data['types'] = $this->engl_model->get_codes('typeID');

				// GET_CODES() returns 2-dim array, w/groupIDs converted to strings.  This line:
				// 	$data['allcodes'] = $this->engl_model->get_codes();
				// returns an array with rows like:
				// 		['typeID'][1]='Professor'
				// 		['typeID'][2]='Associate Professor' 
				// 		['typeID'][3]='Assistant Professor'
				// To use in a View, break into separate associative arrays, like so:
				// 	$codesarr = $this->engl_model->get_codes();
				// 	foreach($codesarr as $k => $v) $data["$k"] = $v;
			}

			$this->output->set_profiler_sections( $this->_get_profiler_settings() );
			$this->output->enable_profiler(TRUE);

			$this->load->view('templates/header', $data);
			$this->load->view('people/edit_profile');
			$this->load->view('templates/footer', $data);
		}
		else
		{
			$data['title'] = $this->input->post('first') . ' ' . $this->input->post('last') . ' Updated';
			$this->engl_model->put_profile();
			$this->load->view('templates/header', $data);
			$this->load->view('people/profile_success');
			$this->load->view('templates/footer', $data);
		}

	}

}