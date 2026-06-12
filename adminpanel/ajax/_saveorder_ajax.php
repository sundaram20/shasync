<?
include_once("../../config/auto_loader.php");

$idsArr =  explode(',' , str_replace(' ', '', $_GET['order']));

	for ($i= 0; $i < sizeof($idsArr); $i++)
	{
	$ordArr[$i] = $i+1+$orderAdd;
	}	
	
	for ($i=0; $i < sizeof($idsArr); $i++)

	{
	    $query="update ".$_GET['tbl']." set `display_order`='".$ordArr[$i]."' where id='".$idsArr[$i]."'";
	    executeSql($query);	    
	}
	
?>