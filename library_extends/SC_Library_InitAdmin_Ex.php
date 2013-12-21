<?php
require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';

abstract class SC_Library_InitAdmin_Ex extends LC_Page_Admin_Ex{

	public function init()
	{
		parent::init();
		$yubireji = new SC_Yubireji_Lib_Session_Ex();
		$this->yubireji_login_status = $yubireji->login_status();
	}
	public function process()
	{
		$this->action();
		$this->sendResponse();
	}
	public function action()
	{
		$this->obj = $this->changeMode($this->getMode());
		if(!$this->obj){
			return false;
		}
		$this->obj->init();
		$this->tpl_mainpage = $this->setTplMain();
		#検索パラメータ取得
#		$this->arrSearch = $this->getSearchParam();
	}
	public function getSearchParam($search_obj)
	{
		$search_obj->lfInitParam();
		$search_obj->setForms();
		$search_obj->getForms();
		return $search_obj->arrForm;
	}
	public function setTplMain(){
		//エラーが1以上あった場合は、mainページ
		$page = (0 < count($this->obj->arrErr)) ? $this->_tpl_backpage : $this->_tpl_mainpage ;
		return $page;
	}
	abstract public function changeMode($mode);
}

?>
