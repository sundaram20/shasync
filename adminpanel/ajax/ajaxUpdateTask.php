<?php
include_once("../../config/auto_loader.php");

/*
	Handles the "Add Task" bulk form only. Editing an existing task item
	is handled separately by ajaxUpdateTaskDetail.php (it appends a new
	task_details row rather than touching the task table at all).

	Expects:
	  task_date             -> single date, dd-mm-yyyy (shared across all rows)
	  id_executive           -> single user id (initial assignment, shared across all rows)
	  id_team                -> single team id (shared across all rows)
	  module[]                -> array, one per row
	  description[]           -> array, one per row
	  estimated_delivery[]    -> array, one per row
	  task_status[]           -> array, one per row
	  completed_date[]        -> array, one per row (only used when status = Completed)

	Each row creates its own independent `task` row plus one initial
	`task_details` row.

	Response:
	  '7'  -> validation failure
	  '1'  -> success
	  '0'  -> nothing could be saved
*/

$taskDate       = $_POST['task_date'];
$idExecutive    = $_POST['id_executive'];
$idTeam         = $_POST['id_team'];
$modules        = isset($_POST['module']) ? $_POST['module'] : array();
$descriptions   = isset($_POST['description']) ? $_POST['description'] : array();
$deliveryDates  = isset($_POST['estimated_delivery']) ? $_POST['estimated_delivery'] : array();
$statuses       = isset($_POST['task_status']) ? $_POST['task_status'] : array();
$completedDates = isset($_POST['completed_date']) ? $_POST['completed_date'] : array();

if($taskDate == '' || $idExecutive == '' || $idTeam == '' || empty($modules) || empty($descriptions)){
	echo '7';
	exit;
}

$savedCount = 0;

foreach($modules as $key => $moduleId){

	$description   = isset($descriptions[$key])   ? $descriptions[$key]   : '';
	$deliveryDate   = isset($deliveryDates[$key])   ? $deliveryDates[$key]   : '';
	$status         = isset($statuses[$key])        ? $statuses[$key]        : 'Pending';
	$completedDate  = isset($completedDates[$key])  ? $completedDates[$key]  : '';

	if($moduleId == '' || $description == ''){
		continue; // skip incomplete rows
	}

	// ---- 1. Create the task item (fixed facts, never changes again) ----
	$insTask = "INSERT INTO `task` SET
					`id_shop`       = '".addslashes($_SESSION['shop'])."',
					`dated`         = '".addslashes(date('Y-m-d', strtotime($taskDate)))."',
					`id_team`       = '".addslashes($idTeam)."',
					`task_code`     = '',
					`id_module`     = '".addslashes($moduleId)."',
					`description`   = '".addslashes($description)."',
					`created_by`    = '".addslashes($_SESSION['userId'])."',
					`created_date`  = '".currenDateTime()."'";

	if(!executeSql($insTask)){
		continue;
	}

	$newTaskId = 0;
	$lastIdRes = executeSql("SELECT LAST_INSERT_ID() AS id");
	if($lastIdRes){
		$lastIdRow = $db->fetch_assoc2($lastIdRes);
		$newTaskId = $lastIdRow['id'];
	}

	if(empty($newTaskId)){
		continue;
	}

	// ---- 2. Generate and store its Task ID / task_code ----
	$taskCode = 'TSK-'.date('y').'-'.str_pad($newTaskId, 5, '0', STR_PAD_LEFT);
	executeSql("UPDATE `task` SET `task_code` = '".addslashes($taskCode)."' WHERE `id` = '".$newTaskId."' ");

	// ---- 3. Create its initial task_details (history) entry ----
	$completedDateSql = ($status == 'Completed' && $completedDate != '')
							? "'".addslashes(date('Y-m-d', strtotime($completedDate)))."'"
							: 'NULL';

	$estimatedDeliverySql = ($deliveryDate != '')
							? "'".addslashes(date('Y-m-d', strtotime($deliveryDate)))."'"
							: "'0000-00-00'";

	$insDetail = "INSERT INTO `task_details` SET
					`task_id`                  = '".$newTaskId."',
					`id_executive`             = '".addslashes($idExecutive)."',
					`estimated_delivery_date`   = ".$estimatedDeliverySql.",
					`status`                    = '".addslashes($status)."',
					`completed_date`            = ".$completedDateSql.",
					`created_by`                = '".addslashes($_SESSION['userId'])."',
					`created_date`               = '".currenDateTime()."'";

	if(executeSql($insDetail)){
		$savedCount++;
	}
}

if($savedCount > 0){
	$_SESSION['successMsg'] = $savedCount.' task(s) added successfully.';
	echo '1';
} else {
	$_SESSION['errorMsg'] = 'No tasks could be saved.';
	echo '0';
}
exit;