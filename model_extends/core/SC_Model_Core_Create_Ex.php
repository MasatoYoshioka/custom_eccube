<?php

class SC_Model_Core_Create_Ex extends SC_Model_Core_Main_Ex{
# テーブル作成関数
# fileds 定義
# name => 
#	type => SMALLINT or STRING
#	length => NUMBER型 (STRINGの場合はNULL)
#	null => ヌル
#	default => default値
#	comment => コメント
#
#テーブルの default設定
# id  オートインクリメント
# create_date 作成日付
# update_date 更新日付
# del_flg 削除フラグ
#
# 主キーはID
# return void(0)
	public function createTable($fileds,$table,$db_name,$index = NULL)
	{
		if(empty($fileds)) return false;
		$text = " text CHARACTER SET utf8 COLLATE utf8_general_ci";
		$time = " timestamp ";
		$id = "id MEDIUMINT NOT NULL AUTO_INCREMENT ,";
		$default = "create_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,  update_date timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' , del_flg smallint(2) NOT NULL DEFAULT 0 , PRIMARY KEY (id)";
		$end = ")";
		$sql = "CREATE TABLE IF NOT EXISTS $db_name.$table (";
		$sql .= $id;
		foreach($fileds as $filed => $val){
			$tmp = "";
			$tmp = $filed;
			switch($val['type']){
				case 'STRING':
					$tmp .= $text ;
				break;
				case 'SMALLINT':
					$tmp .= " " . $val['type'] . "(" . $val['length'] . ")";
				break;
				case 'INT':
					$tmp .= " " . $val['type'] . "(" . $val['length'] . ")";
				break;
				case 'MEDIUMINT':
					$tmp .= " " . $val['type'] ;
				break;
				case 'TIMESTAMP':
					$tmp .= $time ;
				break;
				default:
					return false;
				break;
			}
			if(isset($val['null'])){
				if(!$val['null']){
					$tmp .= ' NOT NULL';
				}
			}
			if(isset($val['default'])){
				$tmp .= ' DEFAULT ' . $val['default'];
			}
			if(isset($val['comment'])){
				$tmp .= " COMMENT '" . $val['comment'];
			}
			$sql .= $tmp . ",";
		}
		$sql .= $default;
		if(isset($index)){
			$sql .= "," . $index;
		}
		$sql .= $end;
		$this->query($sql,null);
	}
}
?>
