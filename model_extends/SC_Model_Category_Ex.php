<?php
class SC_Model_Category_Ex extends SC_Model_Core_Main_Ex
{
	var $index;
	var $key = 'id';
	var $table = 'category';
	var $where = "del_flg = 0 ";
	public function set_col()
	{
		$this->col = array_keys($this->DefineTable());
	}
	public function get_html_option_all()
	{
		$categories = $this->selectDb('id,name',$this->table,$this->where);
		$result = array();
		foreach($categories as $key => $val){
			$result[$val['id']] = $val['name'];
		}
		return $result;
	}
	public function get_all()
	{
		return SC_Utils::makeArrayIDToKey('id',$this->selectDb($this->getCol($this->col,null,true),$this->table,$this->where));
	}
	public function get_month($yyyymmdd)
	{
		$col = "sales_date,count(id) as count,sum(price) as sum_price ,sum(customers_count) as sum_customers_count";
		$dates = date('Y-m',strtotime($yyyymmdd)) . "%";
		$where = $this->where . " AND sales_date LIKE ? AND STATUS = 'CLOSE'" ;
		$group = "sales_date";
		return SC_Utils::makeArrayIDToKey('sales_date',$this->selectDb($col,$this->table,$where,$dates,$group));
	}
	public function createTable(){
		$fileds = $this->DefineTable();
		$create = new SC_Model_Core_Create_Ex();
		$create->createTable($fileds,$this->table,DB_NAME,$this->index);
	}
	public function DefineTable()
	{
		$fileds = array(
			'name' => array(
				'type' => 'STRING',
			),
		);
		return $fileds;
	}
}
?>
