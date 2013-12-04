<?php
/*
* SC_template クラス
* 2013/05/02 add by yoshioka
*/
class SC_Library_Template_Ex {
	var $_co_temp_path;
	var $_co_temp_name;
	var $_co_temp_full_path;
	var $_file;
	public function __construct($co_temp_path,$co_temp_name){
		$this->_co_temp_path = $co_temp_path;
		$this->_co_temp_name = $co_temp_name;
		$this->_co_temp_full_path = $co_temp_path . $co_temp_name;
	}
	public function fileOpen($values){
		$file = file_get_contents($this->_co_temp_full_path);
		$this->_file = preg_replace('/{(.+?)}/e','$values["$1"]',$file);
	}
	public function get()
	{
		return $this->_file;
	}
}
?>
