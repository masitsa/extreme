<?php

class Dates_model extends CI_Model 
{
	public function last_seven_days()
	{	
		$now = new DateTime( "7 days ago");
		$now = new DateTime( '2014-08-15' );
		$interval = new DateInterval( 'P1D'); // 1 Day interval
		$period = new DatePeriod( $now, $interval, 7); // 7 Days
		
		return $period;
	}
}
?>