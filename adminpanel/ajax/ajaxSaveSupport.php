<?php 
include_once("../../config/auto_loader.php");

$response = ['success' => false, 'message' => ''];
    
try {

	 $shop_id = $_SESSION['shop'];
	 $dp_id = $_POST['support_id'];
	 $product = $_POST['product_id'] ?? '';
	 $serial = $_POST['serial_number'] ?? '';
     $support_by = $_POST['id_user'];
     $company_id = $_POST['company_id'];
     $contact_id = $_POST['id_contacts'] ?? 0;
 	 $createDate = $_POST['createDate'];
	
	  // followup form data

     $followup_status = $_POST['followupstatus'] ?? '';
     $followup_date = !empty($_POST['followup_date']) ? date('Y-m-d', strtotime($_POST['followup_date'])) : '0000-00-00';
     $followup_description = $_POST['followup_description'] ?? '';
     $remark = $_POST['remark'] ?? '';
     $assign_user_id = $_POST['assign_followup_user_id'] ?? '';
	
		 $close_type = $_POST['close_type']??'';
	
	if($followup_status=="1"){
	
	$InsertSql = "INSERT INTO support_details (id_shop, id_daily_pickup, id_product, id_company, serial_number, id_user, assign_user_id, internal_remark, support_remark, support_status, followup_date, date_created, id_mst_user_created_by ) VALUES('$shop_id','$dp_id','$product', '$company_id', '$serial', '$support_by', '$assign_user_id', '$followup_description', '$remark', '$followup_status', '$followup_date', '$createDate', '$support_by' )";
		
	}else if($followup_status=="0"){
		
		$closeRemark = $close_type.' - '.$remark;
		
	$InsertSql = "INSERT INTO support_details (id_shop, id_daily_pickup, id_product, id_company, serial_number, id_user, assign_user_id, internal_remark, support_remark, support_status, followup_date, date_created, id_mst_user_created_by ) VALUES('$shop_id','$dp_id','$product', '$company_id', '$serial', '$support_by', '$assign_user_id', '$followup_description', '$closeRemark', '$followup_status', '$followup_date', '$createDate', '$support_by' )";
	}
	
	if (!mysqli_query($connNew, $InsertSql)) {
        throw new Exception("Failed to insert into fs_support: " . mysqli_error($connNew));
    }
	
	$support_det_id = mysqli_insert_id($connNew);

// Only insert to calendar if this is a Daily Pickup support (dp_id > 0)
if(!empty($dp_id) && $dp_id > 0){
	
	$sup_dp_id = selectColumn('support_details', 'id_daily_pickup', 
        " WHERE `id` = '".addslashes($support_det_id)."' 
        LIMIT 1");

    // Get the correct daily_pickup_details.id using dp_id and product
    $dpd_id = selectColumn('daily_pickup_details', 'id', 
        " WHERE `id_daily_pickup` = '".addslashes($sup_dp_id)."' 
        AND `id_product` = '".addslashes($product)."' 
        LIMIT 1");

    // Insert/Update fs_daily_calender for type 8 (Daily Pickup Call)
    $resCalCheck = executeSql("SELECT * FROM `".TBL_DAILY_CALENDER."` 
        WHERE `id_shop`  = '".addslashes($shop_id)."' 
        AND `type`       = '8'
        AND `visit_id`   = '".addslashes($sup_dp_id)."'
        AND `doc_id`     = '".addslashes($dpd_id)."'");

    if(num_rows($resCalCheck) > 0){
        $calSql = "UPDATE `".TBL_DAILY_CALENDER."` SET
            `enquiry_details` = '1',
            `type`            = '8',
            `id_shop`         = '".addslashes($shop_id)."',
            `id_user`         = '".addslashes($support_by)."',
            `assign_user_id`  = '".addslashes($assign_user_id)."',
            `doc_id`          = '".addslashes($dpd_id)."',
            `visit_id`        = '".addslashes($dp_id)."',
            `id_list_name`    = '0',
            `dated`           = '".addslashes($followup_date)."',
            `status`          = '".addslashes($followup_status)."'
            WHERE `visit_id`  = '".addslashes($dp_id)."'
            AND `doc_id`      = '".addslashes($dpd_id)."'
            AND `type`        = '8'";
    } else {
        $calSql = "INSERT INTO `".TBL_DAILY_CALENDER."` SET
            `enquiry_details` = '1',
            `type`            = '8',
            `id_shop`         = '".addslashes($shop_id)."',
            `id_user`         = '".addslashes($support_by)."',
            `assign_user_id`  = '".addslashes($assign_user_id)."',
            `doc_id`          = '".addslashes($dpd_id)."',
            `visit_id`        = '".addslashes($dp_id)."',
            `id_list_name`    = '0',
            `dated`           = '".addslashes($followup_date)."',
            `status`          = '".addslashes($followup_status)."'";
    }

    if(!mysqli_query($connNew, $calSql)){
        throw new Exception("Calendar update failed: " . mysqli_error($connNew));
    }

}
	
	$response['success'] = true;
	
}catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>