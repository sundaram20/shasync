<?php
include_once("../../config/auto_loader.php");

/*
	Expects (from the Update section on editTask.php, edit mode):
	  task_id                -> encrypted task.id
	  id_executive             -> user id (can be a reassignment)
	  status                   -> Pending / In Progress / Completed / QA / On Hold
	  estimated_delivery       -> dd-mm-yyyy
	  completed_date           -> dd-mm-yyyy (only meaningful when status = Completed)
	  remark                    -> optional free text

	Always INSERTs a new task_details row - never updates an existing one.
	That new row becomes the current state; the old row(s) remain as history.

	Response:
	  '1'  -> success
	  '7'  -> validation failure
	  '3'  -> task not found
	  '4'  -> permission denied
*/

$taskIdEnc     = isset($_POST['task_id']) ? trim($_POST['task_id']) : '';
$newExecutive  = isset($_POST['id_executive']) ? trim($_POST['id_executive']) : '';
$newStatus     = isset($_POST['status']) ? trim($_POST['status']) : '';
$newDelivery   = isset($_POST['estimated_delivery']) ? trim($_POST['estimated_delivery']) : '';
$newCompleted  = isset($_POST['completed_date']) ? trim($_POST['completed_date']) : '';
$remarkText    = isset($_POST['remark']) ? trim($_POST['remark']) : '';

$validStatuses = array('Pending','In Progress','Completed','QA','On Hold');

if($taskIdEnc == '' || $newExecutive == '' || $newStatus == '' || $newDelivery == '' || !in_array($newStatus,$validStatuses)){
	echo '7';
	exit;
}

if($newStatus == 'Completed' && $newCompleted == ''){
	echo '7';
	exit;
}

$taskId = addslashes(encryptor('decrypt',$taskIdEnc));

// ---- Look up the task item ----
$resTask = selectSql('task'," WHERE `id`='".$taskId."' AND `id_shop`='".addslashes($_SESSION['shop'])."' ",'');
if(!$db->num_rows2($resTask)){
	echo '3';
	exit;
}
$taskRow = $db->fetch_object2($resTask);

// ---- Look up its current state (latest task_details row) ----
$resCurrent = selectSql('task_details'," WHERE `task_id`='".$taskId."' ",' ORDER BY `id` DESC LIMIT 1');
if(!$db->num_rows2($resCurrent)){
	echo '3';
	exit;
}
$currentRow = $db->fetch_object2($resCurrent);

// ---- Permission: only admin, the currently assigned executive, or the item's creator may update ----
if($_SESSION['userLevel'] != '1' && $currentRow->id_executive != $_SESSION['userId'] && $taskRow->created_by != $_SESSION['userId']){
	echo '4';
	exit;
}

$completedDateSql = ($newStatus == 'Completed' && $newCompleted != '')
						? "'".addslashes(date('Y-m-d', strtotime($newCompleted)))."'"
						: 'NULL';

$newDeliverySql = "'".addslashes(date('Y-m-d', strtotime($newDelivery)))."'";

$insDetail = "INSERT INTO `task_details` SET
				`task_id`                  = '".$taskId."',
				`id_executive`             = '".addslashes($newExecutive)."',
				`estimated_delivery_date`   = ".$newDeliverySql.",
				`status`                    = '".addslashes($newStatus)."',
				`completed_date`            = ".$completedDateSql.",
				`remark`                    = ".($remarkText != '' ? "'".addslashes($remarkText)."'" : 'NULL').",
				`created_by`                = '".addslashes($_SESSION['userId'])."',
				`created_date`               = '".currenDateTime()."'";

if(executeSql($insDetail)){

	$newDetailId = 0;
	$lastDetailIdRes = executeSql("SELECT LAST_INSERT_ID() AS id");
	if($lastDetailIdRes){
		$lastDetailIdRow = $db->fetch_assoc2($lastDetailIdRes);
		$newDetailId = $lastDetailIdRow['id'];
	}

	// ---- Sync the calendar entry: drop the old one, then either leave it
	// gone (task reached Customer Verified) or re-add it against the
	// current estimated delivery date / assigned executive. ----
	executeSql("DELETE FROM `fs_daily_calender` WHERE `type`='9' AND `visit_id`='".$taskId."' ");

	if(!empty($newDetailId) && $newStatus != 'On Hold'){
		executeSql("INSERT INTO `fs_daily_calender` SET
						`id_shop`         = '".addslashes($_SESSION['shop'])."',
						`type`            = '9',
						`id_user`         = '".addslashes($_SESSION['userId'])."',
						`assign_user_id`  = '".addslashes($newExecutive)."',
						`visit_id`        = '".$taskId."',
						`doc_id`          = '".$newDetailId."',
						`id_list_name`    = '0',
						`dated`           = ".$newDeliverySql.",
						`followup_date`   = ".$newDeliverySql.",
						`status`          = '1',
						`enquiry_details` = '0'");
	}

	echo '1';
} else {
	echo '0';
}
exit;