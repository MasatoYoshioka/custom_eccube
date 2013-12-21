<?php
require_once CLASS_EX_REALDIR . 'page_extends/LC_Page_Ex.php';

class SC_Library_Cache_Ex {
	private $name;
	public $ext = ".serial";
	public $path = MASTER_DATA_REALDIR;

	public function __construct($name)
	{
		$this->name = $name;
	}
	private function get_file_name()
	{
		return $this->path . $this->name . $this->ext;
	}
	public function is_exists()
	{
		return file_exists($this->get_file_name());
	}
	public function get()
	{
		return unserialize(file_get_contents($this->get_file_name()));
	}
	public function create($data)
	{
		$data = serialize($data);

        // ファイルを書き出しモードで開く
        $handle = fopen($this->get_file_name(), 'w');
        if (!$handle) {
            return false;
        }
        // ファイルの内容を書き出す.
        if (fwrite($handle, $data) === false) {
            fclose($handle);

            return false;
        }
        fclose($handle);

        return true;
	}
	public function delete()
	{
		unlink($this->get_file_name());
	}
}

?>
