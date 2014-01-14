<?php
class SC_Model_Item_Ex extends SC_Model_Core_Main_Ex
{
	var $index = "UNIQUE(yubireji_id),FOREIGN KEY (perent_yubireji_id) REFERENCES sale(yubireji_id) ON DELETE CASCADE";
	var $key = 'id';
	var $table = 'item';
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
	public function get_yubireji_id($yubireji_id)
	{
        $col = implode(',',$this->col) . ",discount_sales + discount_tax as discount, sales + tax as all_sales,(sales + tax) - (discount_sales + discount_tax) as pay_price";
        $table = "item";
        $where = "del_flg = 0 AND perent_yubireji_id = ?";
        return $this->selectDb($col,$table,$where,array($yubireji_id));
	}
	public function DefineTable()
	{
		$fileds = array(
			'perent_yubireji_id' => array(
				'type' => 'INT',
				'length' => 20,
			),
			'yubireji_id' => array(
				'type' => 'INT',
				'length' => 20,
			),
			'guid' => array(
				'type' => 'STRING',
			),
			'menu_item_id' => array(
				'type' => 'INT',
				'length' => 20,
			),
			'count' => array(
				'type' => 'INT',
				'length' => 5,
			),
			'sales' => array(
				'type' => 'STRING',
			),
			'tax' => array(
				'type' => 'STRING',
			),
			'discount_sales' => array(
				'type' => 'STRING',
			),
			'discount_tax' => array(
				'type' => 'STRING',
			),
		);
		return $fileds;
	}
}
?>
