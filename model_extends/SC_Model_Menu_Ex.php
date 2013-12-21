<?php
class SC_Model_Menu_Ex
{
	private $name = 'menu';
	private $data;
	public function __construct()
	{
		$this->cache = new SC_Library_Cache_Ex($this->name);
		if(!$this->cache->is_exists()){
			$menu = new SC_Yubireji_Menu_Ex();
			$this->cache->create($menu->get_menu_list());
		}
		$this->data = $this->cache->get();
	}
	public function get()
	{
		return $this->data;
	}
	public function delete()
	{
		$this->cache->delete();
	}
}
?>
