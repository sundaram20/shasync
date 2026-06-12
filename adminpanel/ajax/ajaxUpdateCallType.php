<?php 
include_once("../../config/auto_loader.php");

if (!empty($_REQUEST['id_calls_type']) && !empty($_REQUEST['id_calls'])) {
    // Update logic
    $idCalls = addslashes($_REQUEST['id_calls']);
    $idCallsType = addslashes($_REQUEST['id_calls_type']);

    $editSql1 = "UPDATE `call` SET `calls_type` = '$idCallsType' WHERE `id` = '$idCalls'";
    if (executeSql($editSql1)) {
        echo json_encode(['status' => 'success', 'id_calls' => $idCalls]);
    }
    exit;
}

if (!empty($_REQUEST['id_calls_only'])) {
    $id = addslashes($_REQUEST['id_calls_only']);
    $res = mysqli_query($connNew, "SELECT calls_type, extra_data FROM `call` WHERE id = '$id' LIMIT 1");
    $row = mysqli_fetch_assoc($res);
    $row['data'] = [];
    if (!empty($row['extra_data'])) {
        $row['data']['extra_data'] = json_decode($row['extra_data'], true);
    }
    echo json_encode($row);
    exit;
}
?>
