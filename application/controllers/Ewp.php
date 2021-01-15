<?php
class Ewp extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('ewp_teach_prefs_model');
		$this->load->model('engl_model');
		$this->load->helper('url_helper');
		$this->load->helper('MY_course_helper');
		// Admins can change the form's open/closed dates.
		$this->form_admins = ['jhuebsch', 'crai', 'weller', 'jscon', 'medinad'];
	}

	public function get_prefs($uwnetid = NULL, $year_q = NULL)
	{
		// TEST CODE
		if (! $uwnetid) $uwnetid = 'weller';
		// if (! preg_match("/^20\d\d[1-4]$/", $year_q) ) $year_q = '20173';

		$data['manage_form'] = (in_array($uwnetid, $this->form_admins)) ? '<p><a href="/ci/ewp/get_prefs_manage">(Manage this form\'s open/closed dates.)</a></p>' : '';

		// Get date range from codes table.  
		$date_ranges = $this->ewp_teach_prefs_model->get_date_ranges();
		// Set window/tab title.
		$data['headtitle'] = 'teaching preferences';
		$data['bodyid'] = 'get_prefs';

		$user = $this->engl_model->is_english($uwnetid);
		if (! $user ) 
		{
			$data['uwnetid'] = $uwnetid;
			$data['title'] = 'Oops.';
			$this->load->view('templates/header', $data);
			$this->load->view('ewp/id_not_found', $data);
			$this->load->view('templates/footer', $data);
		}
		else if ( is_array( ($date_range = teach_prefs_closed($date_ranges)) ) )
		{
			$year_q = get_next_quarter();
			$acad_term = format_term( $year_q );  // ['year'],['qtr_name']
			$data['title'] = "Teaching Preferences for {$acad_term['qtr_name']} {$acad_term['year']}.";
			$data['uwnetid'] = $uwnetid;
			$data['next_qtr'] = $acad_term['qtr_name'] . ' ' . $acad_term['year'];
			$data['date_range'] = $date_range['open'] . ' and ' . $date_range['closed'];
			$data['manage_form'] = ($user) ? '<p><a href="/ci/ewp/get_prefs_manage">(Manage this form\'s open/closed dates.)</a></p>' : '';
			$this->load->view('templates/header', $data);
			$this->load->view('ewp/teach_prefs_closed', $data);
			$this->load->view('templates/footer', $data);
		}
		else 
		{
			$this->load->helper('form');
			$this->load->library('form_validation');

			// DUCK!! probably don't want to allow user to enter URL args to change year/quarter.
			// So, should not check for $year_q here.
			if (! $year_q) $year_q = get_next_quarter();

			for ($i = 1; $i < 5; $i++)
			{
				$this->form_validation->set_rules("days$i", "Preferred Days $i", 'trim|required|greater_than[0]',['greater_than' => '{field} can\'t be blank.']);
				$this->form_validation->set_rules("times$i", "Preferred Times $i", 'trim|required|greater_than[0]',['greater_than' => '{field} can\'t be blank.']);
			}

			$data['fname'] = $user->first;
			$data['lname'] = $user->last;
			$data['uwnetid'] = $user->uwnetid;
			$data['year_q'] = $year_q;

			if ($this->form_validation->run() === FALSE)
			{
				$acad_term = format_term( $year_q );  // ['year'],['qtr_name']
				$prefs = $this->ewp_teach_prefs_model->get_prefs($uwnetid, $year_q);
				$data['dayoptions'] = $this->engl_model->get_codes('ewp_day_options');
				$data['timeoptions'] = $this->engl_model->get_codes('ewp_time_options');

				$data['title'] = "{$data['fname']}'s teaching preferences for {$acad_term['qtr_name']} {$acad_term['year']}";
				$data['prefs'] = $prefs;

				$this->load->view('templates/header', $data);
				$this->load->view('ewp/teach_prefs', $data);
				$this->load->view('templates/footer', $data);
			} 
			else 
			{
				$data['result'] = $this->ewp_teach_prefs_model->put_prefs();
				$data['title'] = 'Teaching Preferences Updated';
				$this->load->view('templates/header', $data);
				$this->load->view('ewp/teach_prefs_updated', $data);
				$this->load->view('templates/footer', $data);
			}
		}
	}

	public function get_prefs_manage()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');

		$uwnetid = (isset($_SERVER['REMOTE_USER'])) ? $_SERVER['REMOTE_USER'] : 'jhuebsch';
		$user = (in_array($uwnetid, $this->form_admins)) ? 'OK' : NULL;

		if (! $user ) 
		{
			$data['uwnetid'] = $uwnetid;
			$data['title'] = 'Oops.';
			$data['bodyid'] = 'oops';
			$this->load->view('templates/header', $data);
			$this->load->view('ewp/id_not_found', $data);
			$this->load->view('templates/footer', $data);
		}
		else
		{
			// Set up form validation for the 8 open/closed inputs.
			$form_fields = [
				'summer' => ['open','closed'],
				'autumn' => ['open','closed'],
				'winter' => ['open','closed'],
				'spring' => ['open','closed']
			];
			// Produce rules for summer_open, summer_closed, etc.
			foreach ($form_fields as $k => $a) {
				foreach ($a as $v) $this->form_validation->set_rules(
					$k . '_' . $v,
					'[' . ucfirst($k) . ' ' . $v . ']',
					'trim|required|regex_match[/\d{2}\-\d{2}/]', 
					['regex_match' => 'The {field} field needs a better date.']
				);
			}

			if ($this->form_validation->run() === FALSE)
			{
				$data['headtitle'] = 'manage get prefs';
				$data['title'] = 'Manage Teaching Preferences Form';
				$data['bodyid'] = 'get_prefs_manage';
				$data['form_dates'] = $this->ewp_teach_prefs_model->get_date_ranges();
				$this->load->view('templates/header', $data);
				$this->load->view('ewp/teach_prefs_manage', $data);
				$this->load->view('templates/footer', $data);
			} else {
				$data['result'] = $this->ewp_teach_prefs_model->update_date_ranges();
				$data['headtitle'] = 'Manage Teaching Prefs';
				$data['title'] = 'Teaching Preferences Updated';
				$data['bodyid'] = 'get_prefs_form_updated';
				// Non-admins never get here, so pass link w/out further check.
				$data['manage_form'] = '<p><a href="get_prefs_manage">(Manage this form\'s open/closed dates.)</a></p>';
				$this->load->view('templates/header', $data);
				$this->load->view('ewp/teach_prefs_manage_success', $data);
				$this->load->view('templates/footer', $data);
			}

		}

	}
}