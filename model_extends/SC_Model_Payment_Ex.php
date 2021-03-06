<?php
class SC_Model_Payment_Ex extends SC_Model_Core_Main_Ex
{
	var $index = "UNIQUE(yubireji_id),FOREIGN KEY (perent_yubireji_id) REFERENCES sale(yubireji_id) ON DELETE CASCADE";
	var $key = 'id';
	var $table = 'payment';
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
			'payment_type_id' => array(
				'type' => 'INT',
				'length' => 10,
			),
			'amount' => array(
				'type' => 'STRING',
			),
		);
		return $fileds;
	}
}
?>
