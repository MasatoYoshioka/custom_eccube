<?php
class SC_Model_Sale_Ex extends SC_Model_Core_Main_Ex
{
	var $index = "unique (yubireji_id)";
	var $key = 'id';
	var $table = 'sale';
	var $where = "del_flg = 0 ";
	public function set_col()
	{
		$this->col = array_keys($this->DefineTable());
	}
	public function insert_one_day($dates)
	{
		$this->set_col();
		//ゆびれじAPIからデータを取得する
		$yubireji = new SC_Yubireji_Sale_Ex();
		$sale_datas = $yubireji->get_sale_one_day($dates);
		if(count($sale_datas->checkouts) <= 0){
			$e = "該当日の売上がありませんでした。";
			return $e;
		}
		foreach($sale_datas->checkouts as $checkout){
			if(count($this->get_yubireji_id($checkout->id)) > 0){
				continue;
			}
			$sqlval = "";
			$sqlval = $this->getSqlValObj($checkout,$this->col);
			$sqlval['yubireji_id'] = $checkout->id;
			$sqlval['change_money'] = $checkout->change;
			$this->regist($sqlval);
			$payment_model = new SC_Model_Payment_Ex();
			$item_model = new SC_Model_Item_Ex();
			foreach($checkout->payments as $payment){
				$temp = "";
				$temp = $this->getSqlValObj($payment,$payment_model->col);;
				$temp['perent_yubireji_id'] = $checkout->id;
				$temp['yubireji_id'] = $payment->id;
				$payment_model->regist($temp);
			}
			foreach($checkout->items as $item){
				$temp = "";
				$temp = $this->getSqlValObj($item,$item_model->col);;
				$temp['perent_yubireji_id'] = $checkout->id;
				$temp['yubireji_id'] = $item->id;
				$item_model->regist($temp);
			}
		}
	}
	public function get_yubireji_id($yubireji_id)
	{
		$where = "yubireji_id = ? ";
		return $this->selectDb(implode(',',$this->col),$this->table,$where,$yubireji_id);
	}
	public function get_date($dates)
	{
		$dates = date('Y-m-d',strtotime($dates));
		$where = $this->where . " AND sales_date = ?" ;
		return $this->selectDb(implode(',',$this->col),$this->table,$where,$dates);
	}
	public function get_day_sum($yyyymmdd)
	{
		$col = "SUM(price) as sum_price";
		$where = $this->where . " AND sales_date = ? AND STATUS = 'CLOSE'" ;
		$dates = date('Y-m-d',strtotime($yyyymmdd));
		$group = "sales_date";
		return $this->selectDbRow($col,$this->table,$where,$dates);
	}

	public function get_month_sum($yyyymm)
	{
		$col = "SUM(price) as sum_price";
		$where = $this->where . " AND sales_date LIKE ? AND STATUS = 'CLOSE'" ;
		$dates = date('Y-m',strtotime($yyyymm . "01")) . "%";
		$group = "sales_date";
		return $this->selectDbRow($col,$this->table,$where,$dates);
	}
	public function get_month($yyyymm)
	{
		$col = "sales_date,count(id) as count,sum(price) as sum_price ,sum(customers_count) as sum_customers_count";
		$dates = date('Y-m',strtotime($yyyymm. "01")) . "%";
		$where = $this->where . " AND sales_date LIKE ? AND STATUS = 'CLOSE'" ;
		$group = "sales_date";
		return SC_Utils::makeArrayIDToKey('sales_date',$this->selectDb($col,$this->table,$where,$dates,$group));
	}
	public function get_day($yyyymmdd)
	{
		$col = "I.menu_item_id as menu_id,sum(I.sales + I.tax) as sum_all_sales,sum(I.sales) as sum_sales,sum(I.tax) as sum_tax,sum(I.count) as sum_count, sum(I.discount_sales + I.discount_tax) as sum_all_discount, sum(I.sales + I.tax) - sum(I.discount_sales + I.discount_tax) as pay_price";

		$dates = date('Y-m-d',strtotime($yyyymmdd));
		$table = "sale S, item I";
		$where = "S.del_flg = 0 AND I.del_flg = 0 AND S.yubireji_id = I.perent_yubireji_id AND S.status = 'CLOSE' AND S.sales_date = ?";
		$group = "menu_item_id";
		return $this->selectDb($col,$table,$where,$dates,$group);
	}
	public function get_day_reji($yyyymmdd)
	{
		$col = "price,ADDTIME(paid_at,'0 09:00:00.000000') as paid_at, price,yubireji_id";
		$dates = date('Y-m-d',strtotime($yyyymmdd));
		$table = "sale";
		$where = "del_flg = 0 AND status = 'CLOSE' AND sales_date = ?";
		return $this->selectDb($col,$table,$where,$dates);
	}
	public function createTable(){
		$fileds = $this->DefineTable();
		$create = new SC_Model_Core_Create_Ex();
		$create->createTable($fileds,$this->table,DB_NAME,$this->index);
	}
	public function DefineTable()
	{
		$fileds = array(
			'yubireji_id' => array(
				'type' => 'INT',
				'length' => 20,
			),
			'guid' => array(
				'type' => 'STRING',
			),
			'account_id' => array(
				'type' => 'INT',
				'length' => 20,
			),
			'paid_at' => array(
				'type' => 'TIMESTAMP',
				'null' => false,
				'default' => "'0000-00-00 00:00:00'",
			),
			'close_at' => array(
				'type' => 'TIMESTAMP',
				'null' => false,
				'default' => "'0000-00-00 00:00:00'",
			),
			'deleted_at' => array(
				'type' => 'TIMESTAMP',
				'null' => true,
				'default' => "'0000-00-00 00:00:00'",
			),
			'created_at' => array(
				'type' => 'TIMESTAMP',
				'null' => false,
				'default' => "'0000-00-00 00:00:00'",
			),
			'updates_at' => array(
				'type' => 'TIMESTAMP',
				'null' => true,
				'default' => "'0000-00-00 00:00:00'",
			),
			'price' => array(
				'type' => 'STRING',
			),
			'change_money' => array(
				'type' => 'STRING',
			),
			'cashir_id' => array(
				'type' => 'STRING',
			),
			'customers_count' => array(
				'type' => 'INT',
				'length' => 20,
			),
			'modifier' => array(
				'type' => 'STRING',
			),
			'status' => array(
				'type' => 'STRING',
			),
			'sales_date' => array(
				'type' => 'STRING',
			),
			'device_id' => array(
				'type' => 'STRING',
			),
		);
		return $fileds;
	}
}
?>
