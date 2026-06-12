<?php  include_once("../../config/auto_loader.php");

$Sql = "SELECT * FROM `invoice_request` where   `id` = '".addslashes($_REQUEST['id_invoice_request'])."' AND status='0'  ";	
$row = mysqli_fetch_object(mysqli_query($connNew,$Sql));
$row->json_data;
$Records=array();
$Records['Invoice']	=json_decode($row->json_data,true);	
$listarray=array();
//$arrayList ='<tbody>';
			$In=0;
				foreach($Records as $DataList){
					foreach($DataList as $Invoice){
						$SqlInvoicw = "SELECT * FROM `invoice` where   `id` = '".addslashes($Invoice['Invoice No'])."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."' ";	
						$rowInvoice = mysqli_num_rows(mysqli_query($connNew,$SqlInvoicw));
						 
						 if($rowInvoice>0){
							 $InvoiceText ='Invoice No Already Exist';
							 $InvoiceStatus='1';//
							 }else{
								  $InvoiceText ='Success';
							 	  $InvoiceStatus='0';//
								 }
								 
							$SQLCompany	= "
select *  from ".TBL_COMPANY." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and name !='' AND name LIKE '{$Invoice['Source']}%'  and city='".$Invoice['Source City']."'
";
	
	
	$queryCompany=mysqli_query($connNew, $SQLCompany);
	$rowCompany = mysqli_num_rows($queryCompany);
				if($rowCompany==0){
							 $InvoiceText ='Invalid Company Name';
							 $InvoiceStatus='1';//
							 $InvoiceStatusSet='1';
							 $color='red';
							 }else{
								 
								 $ResultCompany=mysqli_fetch_object($queryCompany);
								 }	 
								 
		$SQLHotel	= "
				select *  from ".TBL_HOTELS." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and name !='' AND name LIKE '{$Invoice['Hotel Name']}%'  and city='".$Invoice['Hotel City']."'
				";
				
				
				$queryHotel=mysqli_query($connNew, $SQLHotel);
				$rowHotel = mysqli_num_rows($queryHotel);
				if($rowHotel==0){
				$InvoiceText ='Invalid Hotel Name';
				$InvoiceStatus='1';//
				$InvoiceStatusSet='1';
				$color='red';
				}else{
								 
								 $ResultHotel=mysqli_fetch_object($queryHotel);
								 }						 
								 
								 
						  $addInvoice = "  INSERT INTO `invoice` SET 
										`id_shop` = '".addslashes($_SESSION['shop'])."',			
										`invoice_no`= '".addslashes($Invoice['Invoice No'])."',
										`amount`= '".addslashes($Invoice['Amount'])."',
										`id_company`= '".addslashes($ResultCompany->id_company)."',
										`received`= '".addslashes($Invoice['Advance'])."',
										`balance`= '".addslashes($Invoice['Balance'])."',
										`checkin`= '".addslashes(date('Y-m-d',strtotime($Invoice['Checkin'])))."',
										`checkout`= '".addslashes(date('Y-m-d',strtotime($Invoice['Checkout'])))."',
										`due_date`= '".addslashes(date('Y-m-d',strtotime($Invoice['Due Date'])))."',
										`guest_name`= '".addslashes($Invoice['Guest Name'])."',
										`hotel_id`= '".addslashes($ResultHotel->id)."',
										`contact_mobile`= '".addslashes($Invoice['Accounts No'])."', 
										`invoice_date`= '".addslashes(date('Y-m-d',strtotime($Invoice['Invoice Date'])))."',
										`contact_person`= '".addslashes($Invoice['Accounts Head'])."',
										`sales_manager`= '".addslashes($Invoice['Sales Manager'])."', 
										`contact_email`= '".addslashes($Invoice['Accounts  Email Id'])."',
										`hotel_last_remarks`= '".addslashes($Invoice['Hotels Last Remarks'])."', 
										`hotel_fresh_remarks`= '".addslashes($Invoice['Hotels Fresh Remarks'])."', 
										`id_user`= '".$_SESSION['userId']."',
										`date_created`= '".currenDateTime()."', 
										`created_by`= '".$_SESSION['userId']."',
										`date_modified`= '".currenDateTime()."',
										`modified_by`= '".$_SESSION['userId']."'";

//echo $addInvoice;die;

													

								executeSql($addInvoice);

								$addInvoiceAssignId = $db->insert_id();
						$In++;
					}
		
				}
			   
			                 
				
                
			
			
			$listarray['count']=$In;
			$listarray['status']=$InvoiceStatus;
			echo json_encode($listarray);
?>