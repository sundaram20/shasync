<?php
include_once("../../config/auto_loader.php");

if($_SESSION['userLevel']!=1 && $_SESSION['teamMemberLevel']==''){
	echo 0;
	exit;
}

if($_REQUEST['rso_id']!=''){
	$sql = "SELECT id,name from ".TBL_USERS." WHERE FIND_IN_SET(".$_REQUEST['rso_id'].",ids_team) AND id_shop='".$_SESSION['shop']."' ";
}
else{
	$sql = "SELECT id,name from ".TBL_USERS." WHERE FIND_IN_SET(ids_team,'".$_SESSION['teamId']."') AND id_shop='".$_SESSION['shop']."' ";
}


$res = mysqli_query($connNew,$sql);
$return = "<option value=''>All</option>";
while($row = mysqli_fetch_object($res)){
	$return .= "<option value='".$row->id."'>".$row->name."</option>";
}
echo $return;
?>