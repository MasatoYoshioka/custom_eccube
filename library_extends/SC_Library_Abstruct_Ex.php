<?php
# 
# 基本的なclass処理を抽出したclass
# 継承後にlfInitParamを再定義すると使える
# add by Masato Yoshioka 2013.10.22
#
abstract class SC_Library_Abstruct_Ex {
    public function __construct()
	{
        $this->objFormParam = new SC_FormParam_Ex();
		$this->objErr = new SC_CheckError_Ex();
	}
	#入力項目
    abstract function lfInitParam();
	public function init($method = null)
	{
		$this->setForms($method);
		$this->checkErr();
		$this->getForms();
	}
    public function setForms($request = null)
	{
        $this->lfInitParam();
        $this->objFormParam->setParam($this->requestCheck($request));
        $this->objFormParam->convParam();
    }
	public function checkErr()
	{
		//エラー配列の追加 doFuncを使用したい場合はこのcheckErrの前に使う
        $this->objErr->arrErr += $this->objFormParam->checkError();
		$this->setErr();
	}
	public function setErr()
	{
		$this->arrErr = $this->objErr->arrErr;
	}
    public function getForms()
	{
        $this->arrForm = $this->objFormParam->getHashArray();
    }
    public function requestCheck($request){
        if(empty($request)) return $_POST;
        switch($request){
            case 'get':
                $request = $_GET;
            break;
            case 'post':
                $request = $_POST;
            break;
        }
        return $request;
    }
	public function setVal($values)
	{
		list($name,$val) = each($values);
		if(is_array($val)){
			$this->setVal($val);
		}else{
			$this->objFormParam->setValue($name,$val);
		}
	}
	public function resetVal($key,$values)
	{
		$this->objFormParam->setValue($key,$values);
	}
}
?>
