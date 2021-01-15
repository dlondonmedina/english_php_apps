<?php
class Ewp_teach_prefs_model extends CI_model {

	public function __construct()
	{
		$this->load->database('engl');
	}

	public function get_date_ranges()
	{
		$sql = "SELECT
				id,
				description
			FROM
				codes
			WHERE
				groupID = 7
			ORDER BY
				id
			";
		$query = $this->db->query($sql);
		$r = ($query->num_rows() > 0) ? $query->result_array() : NULL;
		if (! $r)  // Group 7 doesn't exist.  Manually create expected data structure.
		{
			$a[1]['open'] = '12-01'; $a[1]['closed'] = '12-15';
			$a[2]['open'] = '03-01'; $a[2]['closed'] = '03-15';
			$a[3]['open'] = '05-01'; $a[3]['closed'] = '05-15';
			$a[4]['open'] = '09-01'; $a[4]['closed'] = '09-15';
			return $a;
		}
		for ($i = 0, $max = count($r); $i < $max; $i++)
		{
			$key = $r[$i]['id'];
			$val = explode('|', $r[$i]['description']);
			$a[$key]['open'] = $val[0];
			$a[$key]['closed'] = $val[1];
		}
		return $a;
	}

	public function update_date_ranges()
	{
		// Switching between INSERT and UPDATE creates a primary key violation--if UPDATE attempts to set groupID or ID.
		// So give INSERT and UPDATE their own code blocks, rather than try to combine them in one statement.
		$tbl = 'codes';
		$where = 'WHERE groupID = 7';
		$sql = "SELECT ID FROM $tbl $where";
		$r = $this->db->query($sql);
		// Using static keys, to align with quarter numbers. 
		$field_vals[1] = $this->input->post('winter_open') . '|' . $this->input->post('winter_closed');
		$field_vals[2] = $this->input->post('spring_open') . '|' . $this->input->post('spring_closed');
		$field_vals[3] = $this->input->post('summer_open') . '|' . $this->input->post('summer_closed');
		$field_vals[4] = $this->input->post('autumn_open') . '|' . $this->input->post('autumn_closed');

		if (! $r->num_rows())  // Group 7 doesn't exist.
		{
			for ($i = 1; $i < 5; $i++)
			{
				$insert_vals[] = "(7, $i, '{$field_vals[$i]}')";
			}
			$fields = implode(',', $insert_vals);
			$sql = "INSERT INTO codes (groupID, ID, description) VALUES $fields";
			$this->db->query($sql);
		}
		else
		{
			$sql = "UPDATE $tbl SET description = ? WHERE groupID = 7 and ID = ?";
			for ($i = 1; $i < 5; $i++)
			{
				$this->db->query($sql, [$field_vals[$i], $i]);  // Array holds values for `description` and `id`.
			}
		}
	}

	public function get_prefs($uwnetid, $year_q)
	{
		$fieldnames = ['days1', 'times1', 'days2', 'times2', 'days3', 'times3', 'days4', 'times4'];
		$fieldnamestring = implode(',', $fieldnames);
		$sql = "SELECT 
				p.last,
				p.first,
				e.uwnetid,
				$fieldnamestring,
				e.notes
			FROM 
				ewp_teach_prefs AS e JOIN people AS p USING(uwnetid)
			WHERE
				e.uwnetid = ? AND year_q = ?
			";
		$query = $this->db->query($sql, array($uwnetid, $year_q));
		$a = ($query->num_rows()) ? $query->result_array() : NULL;  // $a will be multi-dimentional array or NULL
		// We're assuming uwnetid *does* exist in engl.people, so go ahead and setup blank values.
		if (! $a) 
		{
			foreach ($fieldnames as $k) $a[0][$k] = '';
			$a[0]['notes'] ='';
		}
		return ($a) ? $a[0] : $a;  // Returns first row  $a or NULL.
	}

	public function put_prefs() 
	{
		$this->load->helper('url');
		$tbl = 'ewp_teach_prefs';
		$uwnetid = $this->input->post('uwnetid');
		$year_q = $this->input->post('year_q');
		$where = "WHERE uwnetid = ? and year_q= ?";
		$where_vals = [$uwnetid, $year_q];
		$sql = "SELECT uwnetid FROM $tbl $where";
		$r = $this->db->query($sql, $where_vals);
		if (! $r->num_rows()) 
		{
			$action = 'INSERT';
			$where = '';
			unset($where_vals);
		}
		else
		{
			$action = 'UPDATE';
		}
		$fields = "
				uwnetid = ?
			,	year_q = ?
			,	days1 = ?
			,	times1 = ?
			,	days2 = ?
			,	times2 = ?
			,	days3 = ?
			,	times3 = ?
			,	days4 = ?
			,	times4 = ?
			,	notes = ?
		";
		$field_vals = [
				$uwnetid
			, $year_q
			,	$this->input->post('days1')
			,	$this->input->post('times1')
			,	$this->input->post('days2')
			,	$this->input->post('times2')
			,	$this->input->post('days3')
			,	$this->input->post('times3')
			,	$this->input->post('days4')
			,	$this->input->post('times4')
			,	$this->input->post('notes')
		];
		// If updating, append the values expected by the WHERE condition.
		if (isset($where_vals)) 
		{
			for ($i = 0, $max = count($where_vals); $i < $max; $i++) 
			{
				$field_vals[count($field_vals)] = $where_vals[$i];
			}
		}
		$sql = "$action $tbl SET $fields $where";
		$this->db->query($sql, $field_vals);
	}

}