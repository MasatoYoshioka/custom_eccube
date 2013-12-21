<?php

class SC_Library_Date_Ex{
	private $timestamp;
	private $timstamp_real;

	public function __construct($date = NULL)
	{
		if(!$date){
			$date = date('Ymd');
		}
		$this->set_timestamp($date);
	}
	public function set_timestamp($date)
	{
		$this->timestamp_real = strtotime($date);
		$date = getdate(strtotime($date));
		$this->timestamp = strtotime(date($date['mon'] . '/1/'. $date['year']));
	}
	public function get_days_count()
	{
		return date('d',(strtotime('next month',$this->timestamp)) - 1);
	}
	public function get_day()
	{
		return date('d',$this->timestamp_real);
	}
	public function get_next_day($format)
	{
		return date($format,strtotime('+1 day',$this->timestamp_real));
	}

	public function get_prev_day($format)
	{
		return date($format,strtotime('-1 day',$this->timestamp_real));
	}
	public function get_month()
	{
		return date('m',$this->timestamp);
	}
	public function get_year()
	{
		return date('Y',$this->timestamp);
	}
	public function get_this_month($format)
	{
		return date($format,$this->timestamp);
	}
	public function get_next_month($format)
	{
		return date($format,strtotime('+1 month',$this->timestamp));
	}
	public function get_prev_month($format)
	{
		return date($format,strtotime('-1 month',$this->timestamp));
	}
}
?>
