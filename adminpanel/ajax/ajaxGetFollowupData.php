<?php
include_once("../../config/auto_loader.php");

$followup_id = addslashes($_POST['followup_id']);

$sql = executeSql("SELECT * FROM `".TBL_DAILY_ENQUERY_DETAILS."` WHERE id = '".$followup_id."'");
$row = $db->fetch_assoc2($sql);

// Format date for the datepicker (dd-mm-yyyy)
if($row['dated'] != '0000-00-00' && $row['dated'] != ''){
    $row['dated'] = date('d-m-Y', strtotime($row['dated']));
}

echo json_encode($row);
?>