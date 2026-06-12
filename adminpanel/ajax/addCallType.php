<?php
include_once("../../config/auto_loader.php");

$response = ['status' => 'error', 'message' => 'Something went wrong'];

if (!empty($_POST['callTypeName']) && !empty($_POST['format_type'])) {
  $type = addslashes(trim($_POST['callTypeName']));
  $format = addslashes(trim($_POST['format_type']));
	
  $insertSql = "INSERT INTO `call_list_name` SET 
                  id_shop = '".addslashes($_SESSION['shop'])."',
                  name = '$type',
				  format_type = '$format',
                  status = '1',
                  date_created = '".currenDateTime()."',
                  created_by = '".addslashes($_SESSION['userId'])."'";

  if (executeSql($insertSql)) {
    $response = [
      'status' => 'success',
      'id' => $db->insert_id()
    ];
  } else {
    $response['message'] = 'Insert failed.';
  }
}

echo json_encode($response);
?>