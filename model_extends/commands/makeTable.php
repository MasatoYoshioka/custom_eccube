<?php
require_once '../../../../html/require.php';

$models = array('SC_Model_Apply_Ex');

foreach($models as $model){
	$play = new $model();
	$play->createTable();
}
?>
