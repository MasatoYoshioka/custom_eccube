<?php
class SC_Model_Cost_Ex extends SC_Model_Core_Main_Ex
{
	var $index = " FOREIGN KEY (category_id) REFERENCES category(id) ON DELETE CASCADE ";
	var $key = 'id';
	var $table = 'cost';
	var $where = "del_flg = 0 ";
	public function set_col()
	{
		$this->col = array_keys($this->DefineTable());
	}
	public function createTable(){
		$fileds = $this->DefineTable();
		$create = new SC_Model_Core_Create_Ex();
		$create->createTable($fileds,$this->table,DB_NAME,$this->index);
	}
	public function get_id($id)
	{
		$where = $this->where . " AND id = ? ";
		return $this->selectDbRow($this->getCol($this->col,null,true),$this->table,$where,array($id));
	}
	public function get_month_sum($yyyymm)
	{
		$col = "SUM(price * count) as sum_price";
		$where = $this->where . " AND pay_date LIKE ? " ;
		$dates = date('Y-m',strtotime($yyyymm . "01")) . "%";
		return $this->selectDbRow($col,$this->table,$where,$dates);
	}
	public function get_day_sum($yyyymmdd)
	{
		$col = "SUM(price * count) as sum_price";
		$where = $this->where . " AND pay_date = ?" ;
		$dates = date('Y-m-d',strtotime($yyyymmdd));
		$group = "pay_date";
		return $this->selectDbRow($col,$this->table,$where,$dates);
	}
	public function get_month($yyyymm)
	{
		$col = "pay_date,sum(price * count) as sum_price";
		$dates = date('Y-m',strtotime($yyyymm . "01")) . "%";
		$where = $this->where . " AND pay_date LIKE ? " ;
		$group = "pay_date";
		return SC_Utils::makeArrayIDToKey('pay_date',$this->selectDb($col,$this->table,$where,$dates,$group));
	}
	public function get_day($yyyymmdd)
	{
		$col = "CA.name,C.shop,C.product,C.price,C.count,C.price * C.count as sum_price";
		$dates = date('Y-m-d',strtotime($yyyymmdd));
		$table = $this->table . " C , category CA";
		$where = "C.del_flg = 0 AND C.category_id = CA.id AND pay_date is not null AND pay_date = ?";
		return $this->selectDb($col,$table,$where,$dates);
	}
	public function get_day_category($yyyymmdd,$category_id)
	{
		$col = "SUM(price * count) as price";
		$dates = date('Y-m-d',strtotime($yyyymmdd));
		$table = $this->table;
		$where = $this->where . " AND pay_date = ? AND category_id = ?";
		$param = array($dates,$category_id);
		$group = "pay_date";
		return $this->selectDbRow($col,$table,$where,$param,$group);
	}
	public function get_month_json($yyyymm)
	{
		$where = $this->where;
		return json_encode($this->selectDb(implode($this->col,','),$this->table,$this->where));
	}
	public function get_const_data($yyyymm,$category_id = NULL)
	{
		$val = array();
		$col = "C.id as id,C.pay_date as pay_date,CA.name as name,C.shop as shop,C.product as product ,CONCAT(FORMAT(C.price,0),'円') as price,C.count as count ,CONCAT(FORMAT(C.price * C.count,0),'円') as sum_price,paymaster_flg";
		$table = $this->table . " C , category CA";
		$d = new  SC_Library_Date_Ex($yyyymm . "01");
		$where = "C.del_flg = 0 AND C.category_id = CA.id AND pay_date is not null AND pay_date LIKE ?";
		$val[] = $d->get_year() . "-". $d->get_month() . "-%";
		$order = 'pay_date';
		if(isset($category_id)){
			$where .= " AND C.category_id = ?";
			array_push($val,$category_id);
		}
		return json_encode($this->selectDb($col,$table,$where,$val,null,$order));
	}
	public function get_only_val($array)
	{
		$result = array();
		foreach($array as $key => $val){
			array_push($result,array_values($val));
		}
		return $result;
	}
	public function update_paymaster($id)
	{
		$sqlval['paymaster_flg'] = 1;
		$where = $this->where . " AND id = ?";
		$this->updateDb($this->table,$sqlval,$where,array($id));
	}
	public function DefineTable()
	{
		$fileds = array(
			'category_id' => array(
				'type' => 'MEDIUMINT',
			),
			'shop' => array(
				'type' => 'STRING',
			),
			'product' => array(
				'type' => 'STRING',
			),
			'price' => array(
				'type' => 'STRING',
			),
			'count' => array(
				'type' => 'INT',
				'length' => 5,
			),
			'pay_date' => array(
				'type' => 'STRING',
			),
			'paymaster_flg' => array(
				'type' => 'INT',
				'length' => 1,
				'default' => 0,
				'null' => false
			),
		);
		return $fileds;
	}
}
?>
