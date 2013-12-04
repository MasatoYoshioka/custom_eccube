<?php

class SC_Model_Core_Create_Ex extends SC_Db_Ex{
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
	public function createTable($fileds,$table,$db_name)
	{
		if(empty($fileds)) return false;
		$text = " text CHARACTER SET utf8 COLLATE utf8_general_ci";
		$id = "id MEDIUMINT NOT NULL AUTO_INCREMENT COMMENT 'ID',";
		$default = "create_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '登録日', update_date timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新日', del_flg smallint(2) NOT NULL DEFAULT 0 COMMENT '削除フラグ', PRIMARY KEY (id))";
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
			$sql .= $tmp . " COMMENT '" . $val['comment'] . "',";
		}
		$sql .= $default;
		$this->query($sql,null);
	}
}
?>
