<?php 

function format_term($year_q) 
{
	$qnames = ['1' => 'Winter', '2' => 'Spring', '3' => 'Summer', '4' => 'Autumn'];
	return ['year' => substr($year_q, 0, 4), 'qtr_name' => $qnames[ substr($year_q, -1, 1) ] ];
}

function get_current_quarter()
{
	return ceil( date("m", time()) / 3 );
}

function get_next_quarter()
{
	// Used on form-is-closed page.
	$next_qtr = get_current_quarter() + 1;
	$year = ($next_qtr > 1) ? date('Y') : date('Y')+1;
	return $year.$next_qtr;

}

function teach_prefs_closed($date_ranges)
{
	$qtr = get_current_quarter();
	$next_qtr = ($qtr % 4) + 1;
	$now = date('m-d');
	if ($now < $date_ranges[$next_qtr]['open'] or $now > $date_ranges[$next_qtr]['closed']) 
	{
		return $date_ranges[$next_qtr];
	} 
	else
	{
		return false;
	}
}
