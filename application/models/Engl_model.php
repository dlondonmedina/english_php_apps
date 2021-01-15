<?php
class Engl_model extends CI_model {

	public function __construct()
	{
		$this->load->database('engl');
	}

	public function is_english($uwnetid)
	{
		$sql = 'SELECT first, last, uwnetid FROM people WHERE uwnetid = ?';
		$query = $this->db->query($sql, array($uwnetid));
		return ($query->num_rows()) ? $query->row() : NULL;
	}

	public function get_employees( $employee_type = NULL, $hire_year = NULL )
	{
		$fields = 'employID, Last, First, Room, Hours, uwnetid, hideroom, hireyear';

		if ( ! $employee_type )
		{
			// below: ->where() could be dropped and ->get() could become ->get_where('people', 'status=3')
			$query = $this->db
				->select($fields)
				->where('status = 3')
				->order_by('Last, First')
				->get('people');
			return $query->result_array();
		}

		switch ($employee_type) {
			case 'fac': $type = 'TypeID < 10'; break;
			case 'profs': $type = 'TypeID < 4'; break;
			case 'prof1': $type = 'TypeID = 1'; break;
			case 'prof2': $type = 'TypeID = 2'; break;
			case 'prof3': $type = 'TypeID = 3'; break;
			case 'lecturers': $type = 'TypeID IN (10,11,12,13)'; break;
			case 'lectprin': $type = 'TypeID = 10'; break;
			case 'lectsen': $type = 'TypeID = 11'; break;
			case 'lect': $type = 'TypeID = 12'; break;
			case 'lectpart': $type = 'TypeID = 13'; break;
			case 'ai': $type = 'TypeID = 20'; break;
			case 'adjunct': $type = 'TypeID = 30'; break;
			case 'visiting': $type = 'TypeID = 31'; break;
			case 'tas': $type = 'TypeID >= 40 AND TypeID < 50'; break;
			case 'ta': $type = 'TypeID = 40'; break;
			case 'taiwp': $type = 'TypeID = 41'; break;
			case 'tacw': $type = 'TypeID = 42'; break;
			case 'staff': $type = 'TypeID = 50'; break;
			default: $type = 'TypeID = 1';
		}

		// $hire_year is passed in URL as 2nd folder name after $employee_type
		// EX: ... employees/filtered/staff/2005
		// OR, with route defined:
		// 	... engl/staff/2005
		$hire_year = ($hire_year) ? ' AND hireyear >= ' . $hire_year : '';

		// $query = $this->db->get_where('people', array('TypeID' => $typeID, 'status' => '3'));
		// $query = $this->db->get_where('people', "(TypeID = $typeID and status in (0,3))");
		// $where = "(TypeID = $typeID and status in (0,3))";
		// $query = $this->db->get_where('people', $where);
		// $query = $this->db->get_where('people', array('TypeID' => $typeID, 'hireyear >=' => '2015'));

		// $this->db->select('Last, First, Room, Hours');
		// $this->db->where('TypeID', $typeID);
		// $this->db->where('hireyear >=', '2014');
		// $query = $this->db->get('people');
		// $query = $this->db->get_where('people', "TypeID = $typeID $hire_year and status=3");

		$query = $this->db
			->select($fields)
			->where("$type $hire_year AND status=3")
			->order_by('Last, First')
			->get('people');
		return $query->result_array();

		// GET_WHERE() allows WHERE condition to follow tablename.
		//	1. string
		// 		get_where('people', "(TypeID = $typeID and status in (1,3))");
		//	or
		//		$where = "(TypeID = $typeID and status in (1,3))";
		//		get_where('people', $where);
		//	2. array
		//		get_where('people', array('TypeID' => $typeID, 'hireyear >=' => $year)); // AND
		// Various methods provided by query builder:
		// 	where()
		// 	or_where()
		// 	or_where_in()
		// 	where_not_in()
		// 	or_where_not_in()
		// 	like()
		// 	or_like()
		// 	not_like()
		// METHOD CHAINING
		// 	$query =
		//		$this->db
		//			->select('Last, First, Room, Hours')
		//			->where('id', $id)
		//			->order_by('last, first')
		//			->limit(10,20)
		//			->get('tablename');
	}

	public function get_profile($id)
	{
		$fields = 'employID, first, middle, last, drupal_name, typeID, hireyear, appointment, proftitle, status, usercode, hours, uwnetid, alt_email, alt_meet';
		$query = $this->db
			->select($fields)
			->where('employID', $id)
			->get('people');
		$a = $query->result_array();  // $a is a multi-dimensional array.
		return $a[0];  // Return 1st employee found.
	}

	public function find_code($v)
	// Normally, $v will be a string, so we can find corresponding groupID.
	// If an integer, we can find corresponding string after flipping array.
	{
		$codes = [
			'map'=> 0,
			'typeID' => 1,
			'qtr' => 2,
			'unit' => 3,
			'status' => 4,
			'ewp_day_options' => 5,
			'ewp_time_options' => 6
			];
		if (ctype_digit($v)) $codes = array_flip($codes);
		$groupID = (isset($codes[$v])) ? $codes[$v] : NULL;
		return $groupID;
	}

	public function get_codes($v = NULL)  // $v : string used to look up groupID in engl.codes
	{
		if ($v)
		{  // Convert string to groupID.
			$groupID = $this->find_code($v);
			// TODO: Check for legal $groupID.  if (! $groupID) throw error or return NULL.
			$this->db->where('groupID', $groupID);
			if (! $this->db->count_all_results('codes')) return NULL;
			$query = $this->db
				->select('id, description')
				->where('groupID', $groupID)
				->get('codes');
			$r = $query->result_array();

			// Convert to simple associative array: key = [id], value = [description]
			foreach ($r as $arr) $a[ $arr['id'] ] = $arr['description'];
		}
		else  // Get all codes.
		{
			$query = $this->db
				->select('groupID, id, description')
				->where('groupID > 0')
				->order_by('groupID, id')
				->get('codes');
			$r = $query->result_array();
			// Create 2-dim $codes array:  [groupstring][id] = value (ex: ['status'][0] = 'suspended')
			$a='';
			foreach ($r as $arr)
			{
				if ($v===NULL || $v !== $arr['groupID'])
				{
					$v = $arr['groupID'];
					$grpstring = $this->find_code($v);  // For readability, convert groupID to string.
				}
				if ($v) $a[$grpstring][ $arr['id'] ] = $arr['description'];
			}
		}
		return $a;
	}

	public function put_profile()
	{
		$this->load->helper('url');

		$s = $this->input->post('first');
		$s .= ' ' . $this->input->post('middle');
		$s .= ' ' . $this->input->post('last');
		$drupal_name = trim(url_title($s, 'dash', TRUE));

		$data = array(
			'first' => $this->input->post('first'),
			'middle' => $this->input->post('middle'),
			'last' => $this->input->post('last'),
			'drupal_name' => $drupal_name,
			'typeID' => $this->input->post('typeID'),
			'hireyear' => $this->input->post('hireyear'),
			'appointment' => $this->input->post('appointment'),
			'proftitle' => $this->input->post('proftitle'),
			'status' => $this->input->post('status'),
			'usercode' => $this->input->post('usercode'),
			'hours' => $this->input->post('hours'),
			'uwnetid' => $this->input->post('uwnetid'),
			'alt_email' => $this->input->post('alt_email'),
			'alt_meet' => $this->input->post('alt_meet')
		);

		return $query =
			$this->db
			->set($data)
			->where('employID', $this->input->post('employID'))
			->update('people');
	}

}
