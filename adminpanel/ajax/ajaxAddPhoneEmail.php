<?php
include_once("../../config/auto_loader.php");

$response = ['success' => false, 'message' => ''];



$callId = intval($_POST['call_id'] ?? 0);
$newPhone = trim($_POST['new_phone'] ?? '');
$newEmail = trim($_POST['new_email'] ?? '');
$newName = trim($_POST['new_name']??'');

if ($callId <= 0) {
    $response['message'] = 'Invalid call ID.';
    echo json_encode($response);
    exit;
}

if (empty($newPhone) && empty($newEmail)) {
    $response['message'] = 'Phone number or Email ID is required.';
    echo json_encode($response);
    exit;
}

// Fetch existing extra_data
$sql = "SELECT extra_data FROM `call_details` WHERE call_id = '$callId' LIMIT 1";
$res = mysqli_query($connNew, $sql);
if (!$res) {
    $response['message'] = 'Database query failed.';
    echo json_encode($response);
    exit;
}

$row = mysqli_fetch_assoc($res);
if (!$row) {
    $response['message'] = 'No call found with this ID.';
    echo json_encode($response);
    exit;
}

$extraData = json_decode($row['extra_data'], true) ?? [];

// Initialize arrays if they don't exist
$extraData['mobile'] = isset($extraData['mobile']) ? (is_array($extraData['mobile']) ? $extraData['mobile'] : [$extraData['mobile']]) : [];
$extraData['email'] = isset($extraData['email']) ? (is_array($extraData['email']) ? $extraData['email'] : [$extraData['email']]) : [];
$extraData['contact_person'] = isset($extraData['contact_person']) ? (is_array($extraData['contact_person']) ? $extraData['contact_person'] : [$extraData['contact_person']]) : [];

// Remove empty values and duplicates
$extraData['mobile'] = array_filter(array_unique($extraData['mobile']));
$extraData['email'] = array_filter(array_unique($extraData['email']));
$extraData['contact_person'] = array_filter(array_unique($extraData['email']));

// Append new phone number if provided and not already present
if (!empty($newPhone) && !in_array($newPhone, $extraData['mobile'])) {
    $extraData['mobile'][] = $newPhone;
}

// Append new email if provided and not already present
if (!empty($newEmail) && !in_array($newEmail, $extraData['email'])) {
    $extraData['email'][] = $newEmail;
}

// Append new name if provided and not already present
if (!empty($newName) && !in_array($newName, $extraData['contact_person'])) {
    $extraData['contact_person'][] = $newName;
}

// Update the call record
$updatedExtraData = json_encode($extraData, JSON_UNESCAPED_UNICODE);
$updateSql = "UPDATE `call_details` SET extra_data = ? WHERE call_id = ?";
$stmt = mysqli_prepare($connNew, $updateSql);
mysqli_stmt_bind_param($stmt, 'si', $updatedExtraData, $callId);

if (mysqli_stmt_execute($stmt)) {
    $response['success'] = true;
    $response['message'] = 'New Contact Details Added Successfully.';
} else {
    $response['message'] = 'Failed to update call record.';
}

mysqli_stmt_close($stmt);
echo json_encode($response);
?>