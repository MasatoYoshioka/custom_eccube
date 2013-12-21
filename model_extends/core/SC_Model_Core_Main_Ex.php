<?php
# 各Modelの基幹機能を設定

class SC_Model_Core_Main_Ex {
	var $_table;
	var $_key ;
	var $where ;
	var $_err ;
	#テーブルとキーを設定する
	public function __construct(){
		$this->set_col();
	}
	public function set_col(){}
	#初期化
	public function init()
	{
		#各 key,table,whereが設定されているかチェック
		$checkArray = array('_table','_key');
		foreach($checkArray as $key){
			if(empty($this->$key)) $this->setErr($key, "$keyが設定されていません");
		}
	}
	#設定初期化
	public function refresh($key,$table)
	{
		$this->_key = $key;
		$this->_table = $table;
		$this->where = "";
		$this->init();
	}
	public function setErr($key,$msg)
	{
		$this->_err[$key] = $msg;
	}
	public function getErr()
	{
		return $this->_err;
	}
	public function checkErr()
	{
		if(isset($this->_err)) return true;
		return false;
	}
	public function setKey($key){
		$this->_key = $key;
	}
	public function setTable($table){
		$this->_table = $table;
	}
	public function delete($id)
	{
		if($this->checkErr()) return $this->getErr();
		$sqlval = array('del_flg'=>'1');
		$objQuery =& SC_Query_Ex::getSingletonInstance();
		$objQuery->update($this->table,$sqlval,"$this->key = ?",array($id));
	}
	public function delete_table($table,$where = "",$arrval = array())
	{ 
		$objQuery =& SC_Query_Ex::getSingletonInstance();
		$objQuery->delete($table,$where,$arrval);
	}

	public function query($query,$arrval = null)
	{
		$objQuery =& SC_Query_Ex::getSingletonInstance();
		return $objQuery->query($query,$arrval);
	}
	public function getOne($query,$arrval = null)
	{
		$objQuery =& SC_Query_Ex::getSingletonInstance();
		return $objQuery->getOne($query,$arrval);
	}
	public function insertDb($table,$sqlval){
		$objQuery =& SC_Query_Ex::getSingletonInstance();
		$sqlval['create_date'] = 'CURRENT_TIMESTAMP';
		$sqlval['del_flg'] = '0';
		return $objQuery->insert($table,$sqlval);
	}
	public function updateDb($table,$sqlval,$where,$arrVal){
		$objQuery =& SC_Query_Ex::getSingletonInstance();
		$sqlval['update_date'] = 'CURRENT_TIMESTAMP';
		$sqlval['del_flg'] = '0';
		$objQuery->update($table,$sqlval,$where,$arrVal);
		return true;
	}
	public function selectDbRow($col,$table,$where,$values,$group = null,$order = null,$limit = null,$offset = null){
		$objQuery =& SC_Query_Ex::getSingletonInstance();
		if(!empty($group)) $objQuery->setGroupBy($group);
		if(!empty($order)) $objQuery->setOrder($order);
		if(!empty($limit)) $objQuery->setLimit($limit);
		if(!empty($offset)) $objQuery->setOffset($offset);
		return $objQuery->getRow($col,$table,$where,$values);
	}
	public function selectDb($col,$table,$where,$values,$group = null,$order = null,$limit = null,$offset = null){
		$objQuery =& SC_Query_Ex::getSingletonInstance();
		if(!empty($group)) $objQuery->setGroupBy($group);
		if(!empty($order)) $objQuery->setOrder($order);
		if(!empty($limit)) $objQuery->setLimit($limit);
		if(!empty($offset)) $objQuery->setOffset($offset);
		return $objQuery->select($col,$table,$where,$values);
	}
	public function getSelectSql($col,$table,$where,$values,$group = null,$order = null,$limit = null,$offset = null){
		$objQuery =& SC_Query_Ex::getSingletonInstance();
		if(!empty($group)) $objQuery->setGroupBy($group);
		if(!empty($order)) $objQuery->setOrder($order);
		if(!empty($limit)) $objQuery->setLimit($limit);
		if(!empty($offset)) $objQuery->setOffset($offset);
		return $objQuery->getSql($col,$table,$where,$values);
	}
	public function maxDb($table,$where,$values){
		$objQuery =& SC_Query_Ex::getSingletonInstance();
		return $objQuery->count($table,$where,$values);
	}
	public function nextVal($table){
		$objQuery =& SC_Query_Ex::getSingletonInstance();
		return $objQuery->nextVal($table);
	}
	public function buildQuery($objFormParam,$prefix){
		foreach($objFormParam->keyname as $keyname ){
			$searchData = $objFormParam->getSearchData($keyname);
			if($searchData['val'] == "" || $searchData['key'] == "" || $searchData['search'] == "") continue;
			if(is_array($searchData['val'])){
				$this->whereArray($searchData,$prefix);
			}else{
				$this->whereOne($searchData,$prefix);
			}
		}
		//固定値を追加
		$this->where .= $this->_selectWhere;
	}
	public function addWhere($where,$val = null){
		$this->where .= $where;
		if($val) $this->values = array_merge($this->values,$val);
	}
	public function whereOne($searchData,$prefix){
		$val = $searchData['val'];
		$key = $searchData['key'];
		$search = $searchData['search'];
		$this->where .= " AND " . $prefix . $key . " " .  $search  . "  ? ";	
		$this->values[] = $val;
	}
	public function whereArray($searchData,$prefix){
		$tmpwhere;
		$val = $searchData['val'];
		$key = $searchData['key'];
		$search = $searchData['search'];
		foreach($val as $element){
			if($element == "") continue;
			if(empty($tmpwhere)){
				$tmpwhere = " AND ( " . $prefix . $key . " " . $search . " ? ";
			}else{
				$tmpwhere .= " OR ". $prefix . $key . " " . $search . " ?";
			}
			$this->values[] = $element;
		}
		if(!empty($tmpwhere)){
			$tmpwhere .= " ) ";
			$this->where .= $tmpwhere;
		}
	}
	public function addTable($table){
		if(empty($table)) return;
		$this->_table .= ", " . $table;
	}
	public function getCol($array,$prefix,$default = false){
		if($default){
			$default = array('id','create_date','update_date');
			$array = array_merge($array,$default);
		}
		foreach($array as $key){
			$result .= $prefix . $key . " ,";
		}
		
		return rtrim($result,',');
	}

	public function getSqlVal($array,$checkArray){
		$result = array();
		foreach($checkArray as $key){
			$sqlval[$key] = $array[$key];
		}
		return $sqlval;
	}
    public function getSqlValObj($array,$checkArray){
        $result = array();
        foreach($checkArray as $key){
            $sqlval[$key] = $array->$key;
        }
        return $sqlval;
    }
	//新規かどうかチェック $key=$this->_key が入っているかいないかで確認 新規登録ができなくなるバグあり
	//$keyが入っている場合にその対象のテーブルに該当のKEYが入っているかを確認
	public function checkNew($id = null,$table){
		if(empty($id)){
			return 'insert';
		}else{
			if(0 >= $this->maxDb($table,$this->_key,$id)){
				return 'insert';
			}else{
				return 'update';
			}
		}
	}

    public function regist($arrForm)
    {
        $this->setKey($this->key);
		if($this->checkErr()) return $this->getErr();
        $sqlval = $this->getSqlVal($arrForm,$this->col);
        switch($this->checkNew($arrForm[$this->key],$this->table)){
            case 'insert':
                return $this->insertDb($this->table,$sqlval);
            break;
            case 'update':
                unset($sqlval[$this->_key]);
                $where = "$this->_key = ? ";
                return $this->updateDb($this->table,$sqlval,$where,array($arrForm[$this->_key]));
            break;
        }
    }
	#直近のIDを取得
	#
	public function get_last_id()
	{
		$query = 'select last_insert_id() ';
		return $this->getOne($query);
	}
}
?>
