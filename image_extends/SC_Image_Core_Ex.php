<?php
/*
* Co_image クラス
* Common_image の 略のつもり
* 2013/05/02 add by yoshioka
*/
class SC_Image_Core_Ex{
	var $temp_dir;
	var $save_dir;
	var $arrExt = array('jpg', 'gif', 'png');
	var $max_size = "4000";
	var $_file;
	var $_err;
	var $_tmp_file_name = array();
	var $_resize_width = 1024;
	var $_resize_height = 768;
	public function __construct($temp_dir,$save_dir){
		$this->temp_dir = (preg_match("|/$|", $temp_dir) == 0) ? $temp_dir. "/" : $temp_dir;
		$this->save_dir = (preg_match("|/$|", $save_dir) == 0) ? $save_dir. "/" : $save_dir;

	}
	public function init()
	{
		$this->setFile($_FILES);
		array_walk($this->_file,array($this,'moveImg'));
		$this->checkFile();
		if(!empty($this->_err)){
			return $this->_err;
		}
	}
	public function setFile($files){
		foreach($files as $name => $file){
			//ファイルがアップされてなかったら次へ
			if(is_array($file['name'])){
				if($this->checkEmpty($file,$name)) continue;
				for($i = 0;$i < count($file['name']);$i++){
					$this->_file[$name][$i]['name'] = $file['name'][$i];
					$this->_file[$name][$i]['type'] = $file['type'][$i];
					$this->_file[$name][$i]['tmp_name'] = $file['tmp_name'][$i];
					$this->_file[$name][$i]['error'] = $file['error'][$i];
					$this->_file[$name][$i]['size'] = $file['size'][$i];
				}
			}else{
				if($this->checkEmpty($file,$name)) continue;
				$this->_file[$name]['name'] = $file['name'];
				$this->_file[$name]['type'] = $file['type'];
				$this->_file[$name]['tmp_name'] = $file['tmp_name'];
				$this->_file[$name]['error'] = $file['error'];
				$this->_file[$name]['size'] = $file['size'];
			}
		}
	}
	public function checkFile(){
		foreach($this->_file as $name => $file){
			$this->checkSize($file,$name);
			$this->checkExt($file,$name);
		}
	}
	public function checkEmpty($file,$name){
		$check = false;
		if(is_array($file['size'])){
			if($file['size'][0] <= 0){
				$check = true;
			}
		}else{
			if($file['size'] <= 0){
				$check = true;
#画像ファイルが大きすぎるとサーバーに画像がアップされないで、name属性だけ反映されている。
				if($file['name']){
					$this->_err[$name][] = "※" .  $file['name'] .  "のファイルサイズが" .  $this->max_size . "kbより大きいです";
				}
			}
		}
		return $check;
	}
	public function checkSize($file,$name){
		if($file['size'] >= $this->max_size * 1024){
			$this->_err[$name][] = "※" .  $file['name'] .  "のファイルサイズが" .  $this->max_size . "kbより大きいです";
		}
	}
	public function checkExt($file,$name){
		$joinext = implode($this->arrExt,"・");
		//拡張子を取得
		$ext = strtolower(substr($file['name'], strrpos($file['name'], '.') + 1));
		if(!in_array($ext,$this->arrExt)) {
			//対象外の拡張子の場合はエラー
			$this->_err[$name][] =  "※" . $file['name'] . "は" . $joinext . "に対応していません";
		}
	}
	//public function moveImg($name,$tmp_name,$width,$height){
	public function moveImg($file,$name){
		$this->_file[$name]['name'] = $this->resize($file['tmp_name'],$this->_resize_width,$this->_resize_height,$name);
	}
	public function resize($filepath,$width,$height,$name){
		$objThumb = new gdthumb();
		$uniqname = date('mdHi') . "_" . uniqid("");
		$ret = $objThumb->Main($filepath,$width,$height,$this->temp_dir . $uniqname);
		if($ret[0] != 1) {
			$this->_err[$name] = $name . " " . $filepath.'画像サイズ変換に失敗しました。' . $ret[1];
			return;
		}
		$ext = strtolower(substr($ret[1], strrpos($ret[1], '.') + 1));
		return $uniqname . "." .$ext;
	}
	public function getErr(){
		return $this->_err;
	}
	public function moveComImg($file){
		if(is_file($this->temp_dir . $file)){
			rename($this->temp_dir . $file, $this->save_dir . $file);
			unlink($this->temp_dir . $file);
		}else{
			$this->_err[$name] = $file . "が存在していません。";
		}
	}
	public function getFilesPath()
	{
		$result = array();
		foreach($this->_file as $name => $file){
			$result[$name] = $this->getFilePath($file);
		}
		return $result;
	}
	public function getFilePath($filename)
	{
		if(is_file($this->temp_dir . $filename)){
			return '/upload/temp_image/' . $filename;
		}
		if(is_file($this->save_dir . $filename)){
			return '/upload/save_image/'  . $filename;
		}
	}
	public function getFileName()
	{
		$result = array();
		foreach($this->_file as $key => $file){
			$result[] = array($key => $file['name']);
		}
		return $result;
	}
}
?>
