<?php
require_once '../../../../html/require.php';

$models = array('SC_Model_Sale_Ex','SC_Model_Item_Ex','SC_Model_Payment_Ex','SC_Model_Category_Ex','SC_Model_Cost_Ex');

foreach($models as $model){
	$play = new $model();
	$play->createTable();
}
?>
