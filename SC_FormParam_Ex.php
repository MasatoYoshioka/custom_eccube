<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2013 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

require_once CLASS_REALDIR . 'SC_FormParam.php';

class SC_FormParam_Ex extends SC_FormParam
{
	public function implodeParam($implode,$key)
	{
		$this->arrValue[$key] = implode(split($implode,$this->arrValue[$key]));
	}
    // パラメーターの追加 オーバーライド
	//検索で使用する項目追加 arraySearch
    public function addParam($disp_name, $keyname, $length = '', $convert = '', $arrCheck = array(), $default = '', $input_db = true ,$arrSearch = array())
    {
        $this->disp_name[] = $disp_name;
        $this->keyname[] = $keyname;
        $this->length[] = $length;
        $this->convert[] = $convert;
        $this->arrCheck[] = $arrCheck;
        // XXX このタイミングで arrValue へ格納するほうがスマートかもしれない。しかし、バリデーションや変換の対象となるので、その良し悪しは気になる。
        $this->arrDefault[$keyname] = $default;
        $this->input_db[] = $input_db;
		$this->arrSearch[$keyname] = $arrSearch;
    }
	//検索情報取得
	public function getSearchData($keyname)
	{
		$result = array();
		$result['key'] = $this->arrSearch[$keyname][0];
		$result['val'] = $this->getValue($keyname);
		$result['search'] = $this->arrSearch[$keyname][1];
		return $result;
	}
}
