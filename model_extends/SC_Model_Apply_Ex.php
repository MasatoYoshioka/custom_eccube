<?php
class SC_Model_Apply_Ex extends SC_Db_Ex
{
	var $key = 'id';
	var $table = 'dtb_apply';
	var $where = "del_flg = 0 ";
	public function set_col()
	{
		$this->col = array_keys($this->DefineTable());
	}
	public function get_all()
	{
		return $this->selectDb(implode(',',$this->col),$this->table,array(),array());
	}
	public function get_id($id)
	{
		$where = "id = ? AND del_flg = 0";
		return $this->selectDbRow($this->getCol($this->col,null,true),$this->table,$where,array($id));
	}
	public function get_recommend_id($user_id){
		$this->where .= "AND user_id = ? ";
		$order = ' create_date DESC';
		return $this->selectDb(rtrim(implode(',',$this->col),','),$this->table,$this->where,array($user_id),'',$order);
	}
	public function createTable(){
		$fileds = $this->DefineTable();
		$create = new SC_Model_Core_Create_Ex();
		$create->createTable($fileds,$this->table,DB_NAME);
	}
	public function DefineTable()
	{
		$fileds = array(
			'contest_id' => array(
				'type' => 'SMALLINT',
				'length' => 10,
				'comment' => 'コンテストID'
			),
			'email' => array(
				'type' => 'STRING',
				'comment' => 'メールアドレス'
			),
			'name01' => array(
				'type' => 'STRING',
				'comment' => 'お名前(姓)'
			),
			'name02' => array(
				'type' => 'STRING',
				'comment' => 'お名前(名)'
			),
			'kana01' => array(
				'type' => 'STRING',
				'comment' => 'フリガナ(セイ)'
			),
			'kana02' => array(
				'type' => 'STRING',
				'comment' => 'フリガナ(メイ)'
			),
			'sex' => array(
				'type' => 'SMALLINT',
				'length' => 1,
				'comment' => '性別'
			),
			'birth_y' => array(
				'type' => 'SMALLINT',
				'length' => 4,
				'comment' => '生年月日(年)'
			),
			'birth_m' => array(
				'type' => 'SMALLINT',
				'length' => 2,
				'comment' => '生年月日(月)'
			),
			'birth_d' => array(
				'type' => 'SMALLINT',
				'length' => 2,
				'comment' => '生年月日(日)'
			),
			'height' => array(
				'type' => 'SMALLINT',
				'length' => 3,
				'comment' => '身長'
			),
			'weight' => array(
				'type' => 'SMALLINT',
				'length' => 3,
				'comment' => '体重'
			),
			'zip01' => array(
				'type' => 'STRING',
				'comment' => '郵便番号1'
			),
			'zip02' => array(
				'type' => 'STRING',
				'comment' => '郵便番号2'
			),
			'pref' => array(
				'type' => 'SMALLINT',
				'length' => 3,
				'comment' => '都道府県番号'
			),
			'addr01' => array(
				'type' => 'STRING',
				'comment' => '住所1'
			),
			'addr02' => array(
				'type' => 'STRING',
				'comment' => '住所2'
			),
			'tel' => array(
				'type' => 'STRING',
				'comment' => '電話番号'
			),
			'office' => array(
				'type' => 'STRING',
				'comment' => '勤務先 及び 学校'
			),
			'hobby' => array(
				'type' => 'STRING',
				'comment' => '趣味・特技・資格'
			),
			'apply' => array(
				'type' => 'STRING',
				'comment' => '応募を知ったのは'
			),
			'apply_flg' => array(
				'type' => 'SMALLINT',
				'length' => 1,
				'null' => false,
				'default' => '0',
				'comment' => '応募フラグ'
			),
			'status' => array(
				'type' => 'SMALLINT',
				'length' => 1,
				'null' => false,
				'default' => '0',
				'comment' => 'ステータスフラグ'
			),
			'image1' => array(
				'type' => 'STRING',
				'comment' => 'バストアップ写真'
			),
			'image2' => array(
				'type' => 'STRING',
				'comment' => '全身写真'
			),
		);
		return $fileds;
	}
}
?>
