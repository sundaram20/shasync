<?php include_once("../config/auto_loader.php");



//---------------------------------------------------------------------------------------------------------

if($_POST['Save']){

	$err = 0;

	if(empty($_POST['name'])){

			$err++;

			$err_item_name = '<font style="color:red;font-weight:normal;" ><br>Please enter item name title.</font>';

	}/*else if(mysqli_num_rows(selectSql(TBL_INV_ITEMS,"WHERE `id` NOT IN('".addslashes($_REQUEST['eId'])."') AND `name` = '".addslashes(trim($_POST['name']))."'",'')) && $_POST['Save'] == 'Add'){

		$err++;

		$err_item_name = '<font style="color:red;font-weight:normal;" ><br>Item name already exists in our database.</font>';

	}*/

	if(empty($_POST['item_code'])){

			$err++;

			$err_item_code = '<font style="color:red;font-weight:normal;" ><br>Please enter item code title.</font>';

	}else if(mysqli_num_rows(selectSql(TBL_INV_ITEMS,"WHERE `id` NOT IN('".addslashes($_REQUEST['eId'])."') AND `item_code` = '".addslashes(trim($_POST['item_code']))."'",'')) && $_POST['Save'] == 'Add'){

		$err++;

		$err_item_code = '<font style="color:red;font-weight:normal;" ><br>Item code already exists in our database.</font>';

	}
	
	
if(empty($_POST['conversion_qty'])){

			$err++;

			$err_conversion_qty = '<font style="color:red;font-weight:normal;" ><br>Please enter Conversion Quantity.</font>';

	}
	
if($_REQUEST['conversion_qty']== '0'){

			$err++;

			$err_conversion_qty1 = '<font style="color:red;font-weight:normal;" ><br>Please enter Conversion Quantity greater than 0.</font>';

	}
		
	
	
	
	 

	if(($_POST['old_image'] == '') && ($_FILES['item_image']['name'] == '')){
	   //no error
		}else{
		if($_FILES['item_image']['name'] !=''){
		if($_FILES['item_image']['size']>0 && $_FILES['item_image']['size']<1048576){
			if(($_FILES['item_image']['type'] == 'image/jpeg') || ($_FILES['item_image']['type'] == 'image/png') || ($_FILES['item_image']['type'] == 'image/bmp') || ($_FILES['item_image']['type'] == 'image/gif')){
			$unique = rand(00000,99999);
        	$filename= basename($_FILES['item_image']['name']);
        	$fname = getNameExt($filename);
        	$insert_image = $_SESSION['shop_code'].'-'.$fname[0].$unique.".".$fname[1];			
				if(@move_uploaded_file($_FILES['item_image']['tmp_name'],$image_path.$insert_image)){	
					resize($insert_image,$image_path, $image_path, $width=350,$height=220,$thumb='medium-');
					resize($insert_image,$image_path, $image_path, $width=150,$height=100,$thumb='small-');	
					//////end resize////////
					if(@file_exists($image_path.$_POST['old_image']) && ($_POST['old_image'] != $_FILES['item_image']['name'])){
						@unlink($image_path.$_POST['old_image']);
						@unlink($image_path.'medium-'.$_POST['old_image']);
						@unlink($image_path.'small-'.$_POST['old_image']);
					}	
				}else{
					$err++;
					$err_image = '<font style="color:red;font-weight:normal;" ><br>Unable to upload file '.$_FILES['item_image']['name'].'.</font>';
				}
			}else{
				$err++;
				$err_image = '<font style="color:red;font-weight:normal;" ><br>Invalid file type '.$_FILES['item_image']['type'].'. Please use only JEPG,GIF,PNG,BMP only</font>';
			}
		}else{
			$err++;
			$err_image = '<font style="color:red;font-weight:normal;" ><br>Image not selected or size is greater than 1MB.</font>';
		}
		}else{
			//$err++;
			//$err_image = '<font style="color:red;font-weight:normal;" ><br>Image not selected or size is greater than 1MB.</font>';
		}
	}
	
	$doc_type = 100;
	//Insert Here
	
	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add 

			//checkUserLevelPermission($_SESSION['userLevel'],TBL_INV_ITEMS,'add');
			$addSql = "   	INSERT INTO `".TBL_INV_ITEMS."` SET
			
							`item_code` = '".addslashes(trim($_POST['item_code']))."',
							`name` = '".addslashes(trim($_POST['name']))."',
							`sac` = '".addslashes(trim($_POST['sac']))."',
							`id_mst_attributes_item_type` = '".addslashes($_POST['id_mst_attributes_item_type'])."', 
							`id_mst_attributes_unit_main` = '".addslashes($_POST['id_mst_attributes_unit_main'])."',
							`id_mst_attributes_unit_alt` = '".addslashes($_POST['id_mst_attributes_unit_alt'])."',
							`id_mst_attributes_group_main` = '".addslashes($_POST['id_mst_attributes_group_main'])."',
							`id_mst_attributes_group_sub` = '".addslashes($_POST['id_mst_attributes_group_sub'])."',
							`id_mst_charges_sales_local` = '".addslashes($_POST['id_mst_charges_sales_local'])."',

							`id_mst_charges_sales_interstate` = '".addslashes($_POST['id_mst_charges_sales_interstate'])."',
							`id_mst_charges_purchase_local` = '".addslashes($_POST['id_mst_charges_purchase_local'])."',
							`id_mst_charges_purchase_interstate` = '".addslashes($_POST['id_mst_charges_purchase_interstate'])."',

							`id_mst_attributes_store` = '".addslashes($_POST['id_mst_attributes_store'])."',
							`id_mst_attributes_printer` = '".addslashes($_POST['id_mst_attributes_printer'])."',
							`ids_mst_outlet` = '".implode(',',$_REQUEST['ids_mst_outlet'])."',
							`conversion_qty` = '".addslashes($_POST['conversion_qty'])."',
							`min_qty` = '".addslashes($_POST['min_qty'])."',
							`max_qty` = '".addslashes($_POST['max_qty'])."',
							`rol` = '".addslashes($_POST['rol'])."',
							`roq` = '".addslashes($_POST['roq'])."',
							`item_class` = '".addslashes($_POST['item_class'])."',

							`bal_qty` = '".addslashes($_POST['bal_qty'])."',
							`open_qty` = '".addslashes($_POST['open_qty'])."',
							`open_amount` = '".addslashes($_POST['open_amount'])."',
							`last_purchase_rate` = '".addslashes($_POST['last_purchase_rate'])."',
							`item_enable_desc_billing` = '".addslashes($_POST['item_enable_desc_billing'])."',
							`stockable_enable_disable` = '".addslashes($_POST['stockable_enable_disable'])."',
							`item_get_expiry_details` = '".addslashes($_POST['item_get_expiry_details'])."',
							`item_production_item` = '".addslashes($_POST['item_production_item'])."',
							`item_allow_additional` = '".addslashes($_POST['item_allow_additional'])."',
							`item_disable` = '".addslashes($_POST['item_disable'])."',
							`sale_rate` = '".addslashes($_POST['sale_rate'])."',
							`purchase_rate` = '".addslashes($_POST['purchase_rate'])."',
							`batch_details` = '".addslashes($_POST['batch_details'])."',
							`item_details` = '".addslashes($_POST['ItemDetails'])."',
							`display_order` = '".addslashes($_POST['display_order'])."',
							`deactivate_date` = '".addslashes($_POST['deactivate_date'])."',
							`edit_name_enable_disable` = '".addslashes($_POST['edit_name_enable_disable'])."'"; 

			if($_FILES['item_image']['name'] != ''){				
				$addSql .= "	,`item_image` = '".addslashes($insert_image)."'";
			}else{
				$addSql .= "	,`item_image` = '".addslashes($_POST['old_image'])."'";
			}

			$addSql .= "	,`id_shop` = '".addslashes($_SESSION['shop'])."'
							,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`id_mst_user_created_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'";



	$sql1 = executeSql("SELECT * FROM `".TBL_INV_ITEMS."` ORDER BY id DESC LIMIT 1");
	 while($row = $db->fetch_object2($sql1)){
	 $idd = $row -> id;
	 if($idd == '0'){
		 $last_id = '1';
	 }else{
		 $last_id =  $idd + 1;
	 }
 }



				   executeSql($addSql);
							$lastInsertId_items= $db->insert_id();
							
				
					if($_POST['ItemDetails']==2){
						$itemDetailSizeOf =	sizeof($_REQUEST['detail_name']);					
						for($i=0;$i<$itemDetailSizeOf;$i++){
							if($_REQUEST['detail_name'][$i]!=''){	
							
					    $itemDetailSql = " INSERT INTO `".TBL_INV_ITEMS_DETAILS."` SET
								`id_item` = '".$lastInsertId_items."', 
								`sub_code` = '".$_REQUEST['detail_subcode'][$i]."',
								`name` = '".$_REQUEST['detail_name'][$i]."',
								`enabled` = '".$_REQUEST['enabled'][$i]."',
								`id_unit` = '".$_REQUEST['detail_unit'][$i]."',
								`type` = '1',
								`rate` = '".$_REQUEST['detail_rate'][$i]."'";
							executeSql($itemDetailSql);
						$lastInsertId_purch1 = $db->insert_id();
		
	
							}
						}
					}

			
			if(1){
				//unset($_POST); 
				$_SESSION['successMsg'] = 'New  Items details has been added sucessfully.';
				header("location:manageItems.php?eId=".encryptor(encrypt,$lastInsertId_items)."&submenu=".$_GET['submenu']."&action=edit&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = ' Items details has not been saved. Please make corrections below.';
			}
		}

		//Update Section Here

		else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update
	//	echo '<pre>';
	 // print_r($_REQUEST);
 	//	echo '</pre>';
		 //die;
			echo '';
			//checkUserLevelPermission($_SESSION['userLevel'],TBL_INV_ITEMS,'update');
	
	

	
	
	
			
			
			 $editSql = "   UPDATE `".TBL_INV_ITEMS."`  SET  
							`item_code` = '".addslashes(trim($_POST['item_code']))."',
							`sac` = '".addslashes(trim($_POST['sac']))."',

							`name` = '".addslashes(trim($_POST['name']))."',
							`id_mst_attributes_item_type` = '".addslashes($_POST['id_mst_attributes_item_type'])."', 
							`id_mst_attributes_group_main` = '".addslashes($_POST['id_mst_attributes_group_main'])."',
							`id_mst_attributes_group_sub` = '".addslashes($_POST['id_mst_attributes_group_sub'])."',
							`id_mst_attributes_unit_main` = '".addslashes($_POST['id_mst_attributes_unit_main'])."',
							`id_mst_attributes_unit_alt` = '".addslashes($_POST['id_mst_attributes_unit_alt'])."',
							`id_mst_charges_sales_local` = '".addslashes($_POST['id_mst_charges_sales_local'])."',

							`id_mst_charges_sales_interstate` = '".addslashes($_POST['id_mst_charges_sales_interstate'])."',
							`id_mst_charges_purchase_local` = '".addslashes($_POST['id_mst_charges_purchase_local'])."',
							`id_mst_charges_purchase_interstate` = '".addslashes($_POST['id_mst_charges_purchase_interstate'])."',

							`id_mst_attributes_store` = '".addslashes($_POST['id_mst_attributes_store'])."',
							`id_mst_attributes_printer` = '".addslashes($_POST['id_mst_attributes_printer'])."',
							`ids_mst_outlet` = '".implode(',',$_REQUEST['ids_mst_outlet'])."',
							`conversion_qty` = '".addslashes($_POST['conversion_qty'])."',
							`min_qty` = '".addslashes($_POST['min_qty'])."',
							`max_qty` = '".addslashes($_POST['max_qty'])."',
							`rol` = '".addslashes($_POST['rol'])."',
							`roq` = '".addslashes($_POST['roq'])."',
							`item_class` = '".addslashes($_POST['item_class'])."',

							`bal_qty` = '".addslashes($_POST['bal_qty'])."',
							`open_qty` = '".addslashes($_POST['open_qty'])."',
							`open_amount` = '".addslashes($_POST['open_amount'])."',
							`last_purchase_rate` = '".addslashes($_POST['last_purchase_rate'])."',
							`item_enable_desc_billing` = '".addslashes($_POST['item_enable_desc_billing'])."',
							`stockable_enable_disable` = '".addslashes($_POST['stockable_enable_disable'])."',
							`item_get_expiry_details` = '".addslashes($_POST['item_get_expiry_details'])."',
							`item_production_item` = '".addslashes($_POST['item_production_item'])."',
							`item_allow_additional` = '".addslashes($_POST['item_allow_additional'])."',
							`item_disable` = '".addslashes($_POST['item_disable'])."',
							`sale_rate` = '".addslashes($_POST['sale_rate'])."',
							`purchase_rate` = '".addslashes($_POST['purchase_rate'])."',
							`batch_details` = '".addslashes($_POST['batch_details'])."',
							`item_details` = '".addslashes($_POST['ItemDetails'])."',
							`display_order` = '".addslashes($_POST['display_order'])."',
							`deactivate_date` = '".addslashes($_POST['deactivate_date'])."',
							`edit_name_enable_disable` = '".addslashes($_POST['edit_name_enable_disable'])."'"; 

			if($_FILES['item_image']['name'] != ''){				
				$editSql .= "	,`item_image` = '".addslashes($insert_image)."'";
			}else{
				$editSql .= "	,`item_image` = '".addslashes($_POST['old_image'])."'";
			}
			 
			$editSql .= "	,`id_shop` = '".addslashes($_SESSION['shop'])."'
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."' 
							WHERE `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'";
							executeSql($editSql);
					//Update Section Here
					//ITEM DETAIL UPDATE START
					//ITEM DETAIL UPDATE END
					
					
					$sql = "  SELECT * FROM `".TBL_INV_PURCH_DETAILS."`
								WHERE  `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='".$doc_type."' AND id_inv_items ='".addslashes(encryptor(decrypt,$_POST[eId]))."' ";
					 $db->query($sql);

					 if($_POST["id_mst_attributes_unit_main"] == $_POST["id_mst_attributes_unit_alt"]){
							$qty_total = $_POST['open_qty'];
					 		$alt_qty = $qty_total * $_POST['conversion_qty'];
						}else{
							$qty_total = ($_POST['open_qty']);
							$alt_qty = $_POST['open_qty']/$_POST['conversion_qty'];
						}
						//Main Unit
						$main_unit  =  selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($_POST["id_mst_attributes_unit_main"])."' AND table_name='unit'");
						//Alt Unit
						$alt_unit  =  selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($_POST["id_mst_attributes_unit_alt"])."' AND table_name='unit'");
					
					
			
					
			if($_POST['ItemDetails']==2){
				
$auditquery11 = "SELECT * From `".TBL_INV_ITEMS_DETAILS."` WHERE id_item = '".encryptor(decrypt,$_POST[eId])."'  ";
$auditresSQL11 = mysqli_query($connNew, $auditquery11);	
	while($auditrow11 = mysqli_fetch_object($auditresSQL11)){
	   $sub_code[] = $auditrow11 -> sub_code;
	   $namee[] = $auditrow11 -> name;
	   $unit[] = $auditrow11 -> id_unit;
	   $rate[] = $auditrow11 -> rate;
	   $enabled[] = $auditrow11 -> enabled;
	}				
				
						
			executeSql("DELETE FROM `".TBL_INV_ITEMS_DETAILS."` WHERE `id_item` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'");
			executeSql("DELETE FROM `".TBL_INV_PURCH_DETAILS."` WHERE `id_inv_items` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'");
			
						
						$itemDetailSizeOf=	sizeof($_REQUEST['detail_name']);					
						for($i=0;$i<$itemDetailSizeOf;$i++){
							if($_REQUEST['detail_name'][$i]!=''){	
							
						    $itemDetailSql1 = " INSERT INTO `".TBL_INV_ITEMS_DETAILS."` SET
								`id_item` = '".addslashes(encryptor(decrypt,$_POST[eId]))."', 
								`sub_code` = '".$_REQUEST['detail_subcode'][$i]."',
								`name` = '".$_REQUEST['detail_name'][$i]."',
								`id_unit` = '".$_REQUEST['detail_unit'][$i]."',
								`enabled` = '".$_REQUEST['enabled'][$i]."',
								`type` = '1',
								`rate` = '".$_REQUEST['detail_rate'][$i]."'";
							executeSql($itemDetailSql1);
						       $lastInsertId_purch2 = $db->insert_id();
							   
							   

if($i < count($name)){
	$name =  " Name Details Changed from " . $namee[$i]  ." - to - " . $_REQUEST['detail_name'][$i]  ;
}else{
	$name = $_REQUEST['detail_name'][$i]." Detail Inserted " ;
}

if($i < count($sub_code)){
	$qtyy =  " Subcode Details Changed from " . $sub_code[$i]  ." - to - " . $_REQUEST['detail_subcode'][$i]  ;
}else{
	$qtyy = $_REQUEST['detail_subcode'][$i]." Details Inserted " ;
}

if($i < count($rate)){
	$rate1 =  " Rate Details Changed from " . $rate[$i]  ." - to - " . $_REQUEST['detail_rate'][$i]  ;
}else{
	$rate1 = $_REQUEST['detail_rate'][$i]." Details Inserted " ;
}

if($i < count($enabled)){
	$enabledd =  " Enabled Details Changed from " . $enabled[$i]  ." - to - " . $_REQUEST['enabled'][$i]  ;
}else{
	$enabledd = $_REQUEST['enabled'][$i]." Details Inserted " ;
}

if($i < count($unit)){
	$old_data = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$unit[$i]."'");
		 $new_data = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$_POST['detail_unit'][$i]."'  ");
	$unit1 =  " Unit Details Changed from " . $old_data  ." - to - " . $new_data  ;
}else{
		 $new_data1 = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$_POST['detail_unit'][$i]."'  ");
	$unit1 =  $new_data1." Details Inserted " ;
}
					   
							   
							   
							   
							   

					$addSql = "   	INSERT INTO `".TBL_INV_PURCH."` SET

						`doc_type` = '".addslashes($doc_type)."',
						`doc_date` = '".date('Y-m-d' , strtotime($books_start_from))."', 		       
						`id_shop` = '".addslashes($_SESSION['shop'])."'";
						$addSql .= ",`date_created` = '".currenDateTime()."',
						`last_modified` = '".currenDateTime()."',
						`id_mst_user_modified_by` = '".$_SESSION['userId']."',
						`id_mst_user_created_by` = '".$_SESSION['userId']."',
						`status` = '".addslashes($_POST['status'])."'";
					executeSql($addSql);
						$lastInsertId_purch= $db->insert_id();


					$addSqled = " INSERT INTO `".TBL_INV_PURCH_DETAILS."` SET
							`id_inv_purch` = '".addslashes($lastInsertId_purch)."',
							`doc_type` = '".addslashes($doc_type)."',  
							`id_inv_items` = '".addslashes(encryptor(decrypt,$_REQUEST[eId]))."', 
							`id_inv_items_details` = '".addslashes($lastInsertId_purch2)."', 
							`qty` = '".addslashes($qty_total)."', 
							`main_unit` = '".$_REQUEST['detail_unit'][$i]."',  
							`alt_qty` = '".addslashes($alt_qty)."',  
							`item_amount` = '".$_REQUEST['detail_rate'][$i]."',  
							`conver_rate_per_unit` = '".addslashes($_POST['conversion_qty'])."',  
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`status` = '".addslashes($_POST['status'])."'  ";
					executeSql($addSqled);
					
							  }
							}
						}
						
                           // `id_mst_outlet` = '".implode(',',$_REQUEST['ids_mst_outlet'])."',
					if($db->num_rows() > 0){

						 $addSql = " UPDATE `".TBL_INV_PURCH_DETAILS."` SET
							`id_inv_items` = '".addslashes(encryptor(decrypt,$_POST[eId]))."',							
							`qty` = '".addslashes($qty_total)."', 
							`main_unit` = '".addslashes($main_unit)."',  
							`alt_unit` = '".addslashes($alt_unit)."', 
							`alt_qty` = '".addslashes($alt_qty)."',  
							`item_amount` = '".addslashes($_POST['sale_rate'])."',  
							`conver_rate_per_unit` = '".addslashes($_POST['conversion_qty'])."',  
							`id_shop` = '".addslashes($_SESSION['shop'])."'";

							$addSql .= ",`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`status` = '".addslashes($_POST['status'])."'
							WHERE `doc_type` = '".$doc_type."' and id_inv_items = '".addslashes(encryptor(decrypt,$_POST[eId]))."'";
							//echo $addSql;
							executeSql($addSql);
						
					}

								
			if(1){
				$_SESSION['successMsg'] = selectColumn(TBL_INV_ITEMS, 'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  'id' = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has been updated sucessfully.';
				header("location:manageItems.php?eId=".$_GET['eId']."&submenu=".$_GET['submenu']."&action=edit&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = selectColumn(TBL_INV_ITEMS,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  'id' = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = ' Items details has not been saved. Please make corrections.';
	}
}

// ----------cate---------

if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){

	$sql = "  SELECT * FROM `".TBL_INV_ITEMS."`
			WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
	 $db->query($sql);
	
	if($db->num_rows() > 0){
		$row = $db->fetch_object(); 
		
	}						
}	




?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
	
		
<!-- Audit Trail Modal -->
<div class="modal fade" id="auditModal" tabindex="-1" role="dialog" aria-labelledby="auditModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #1296f3; color: #fff;text-align: center;">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
               <!-- <h4 class="modal-title" id="roomtypeModalLabel">Rooms Select</h4>  -->
                <label class="modal-title" id="roomtitle1" style="font-size:22px;">Audit Trail</label>
            </div>
            <div class="modal-body" style="overflow-y: scroll; max-height:100%;height:250px ">
                <table class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>Details</th>   
					</tr>
				</thead>
				
				<tbody id="roombutton">
					
				</tbody>
			</table>
            </div>
			
            <div class="modal-footer"  style="background-color: #e4e4e4;color: #fff;text-align:center">
               <button type="button" class="btn c-btn" data-dismiss="modal"> <span class="glyphicon glyphicon-off"></span> Close</button> 
            </div>
     </form>
        </div>
    </div>
</div>
<!-- End Audit trail Modal -->


    <section class="content">
	
	
			
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
         
           
			 <div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
			   <li class="active" ><a href="#tab_1" data-toggle="tab">Items</a></li>    
            </ul>
			<div class="box-header with-border">
              <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?>: <span style="color:#3c8dbc"> <?php echo $row->name ?> </span>
			  
			  <a><?php echo selectColumn(TBL_INV_ITEMS,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND 'id' = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'"); ?></a></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->  			        
			 <form name="form1"  method="post" enctype="multipart/form-data" data-parsley-validate autocomplete="off" >
                <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" />
                <input type="hidden" value="<?php echo encryptor(decrypt,$_REQUEST[eId]);?>" name="mstid" id="mstid" />
					<div class="form-group has-error" align="center">
						<?php if($_SESSION['errorMsg']){?>
						 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
						<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
					 	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
						<?php unset($_SESSION['successMsg']);}?>
					 </div>

              <div class="box-body" style="padding-top : 0px;">

              	<div class="card text-dark bg-light">
              		<div class="bg-primary text-center" >
              			<h5 style="padding: 5px;">General Details</h5>
              		</div> 
              		<hr>
	              	<div class="row">
		              	<div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Item Type <font color="#FF0000">*</font></label>
		                  <div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-book"></i> 
						   	</div>
							
							
<?php							

		
	
	$sqlResult1 = "SELECT * FROM ".TBL_ATTRIBUTES." WHERE table_name = 'items_type' AND field_value IN ('Ingredients') AND id_shop = ".$_SESSION['shop'] ." ";
	$QuerySQL1	=	mysqli_query($connNew,$sqlResult1);
	
		while($sqlRow = mysqli_fetch_object($QuerySQL1)){
	        $list = $sqlRow->id;
			$string .= $list.',';
		} 
				
$item_list = rtrim($string,',');
	
	if($row->id_mst_attributes_item_type != ''){
		
		$categoryDropDown = '<select class="form-control select2" name="id_mst_attributes_item_type" data-parsley-required style="width:100%"> ';
		
		$resCat1 = selectSql(TBL_ATTRIBUTES," where  table_name ='items_type' AND status='0' AND id='".$row->id_mst_attributes_item_type."' ");
									if($db->num_rows2($resCat1)){
										while($resultCat1 = $db->fetch_object2($resCat1)){
											if($row->id_mst_attributes_item_type == $resultCat1->id){
												$selected = 'selected="selected"';
											}else{
												$selected = '';
											}
											$categoryDropDown .= '<option '.$selected.' value="'.$resultCat1->id.'">'.$resultCat1->field_value.'</option>';
										}
									}	
									
			 $resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."' and status = '1' and table_name ='items_type' and id IN ($item_list) ",' ORDER BY `field_value`');
			  if($db->num_rows2($resCat)){
				while($resultCat = $db->fetch_object2($resCat)){
					if($_REQUEST['id_mst_attributes_item_type'] == $resultCat->id){
						$selected = 'selected="selected"';
					}elseif($row->id_mst_attributes_item_type == $resultCat->id){
						$selected = 'selected="selected"';
					}else{
						$selected = '';
					}
					$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';
				}
			  }
			  
			  
			  
			  
	}else{
		$categoryDropDown = '<select class="form-control select2" name="id_mst_attributes_item_type" data-parsley-required style="width:100%">
			';
			
		$resCat1 = selectSql(TBL_ATTRIBUTES," where  table_name ='items_type' AND status='0' AND id='".$row->id_mst_attributes_item_type."' ");
									if($db->num_rows2($resCat1)){
										while($resultCat1 = $db->fetch_object2($resCat1)){
											if($row->id_mst_attributes_item_type == $resultCat1->id){
												$selected = 'selected="selected"';
											}if($_REQUEST['id_mst_attributes_item_type'] == $resultCat1->id){
												$selected = 'selected="selected"';
											}else{
												$selected = '';
											}
											$categoryDropDown .= '<option '.$selected.' value="'.$resultCat1->id.'">'.$resultCat1->field_value.'</option>';
										}
									}	
									
		 $resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."' and status = '1' and table_name ='items_type' and id IN ($item_list) ",' ORDER BY `field_value`');
		  if($db->num_rows2($resCat)){
			while($resultCat = $db->fetch_object2($resCat)){
				if($_REQUEST['id_mst_attributes_item_type'] == $resultCat->id){
					$selected = 'selected="selected"';
				}elseif($row->id_mst_attributes_item_type == $resultCat->id){
					$selected = 'selected="selected"';
				}else{
					$selected = '';
				}
				$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';
			}
		  }
	}						
							
				echo $categoryDropDown .= '</select>';
								  ?>
							<?php echo $err_id_mst_attributes_item_type;?></div>
		                </div>


                       <div class="form-group col-xs-12 col-md-6 col-sm-2">
		                    <label for="name">SAC/HSN No</label>
		                     <div class="input-group"> 
	              		     	<div class="input-group-addon">
								   <i class="fa fa-list-ol"></i> 
						      	</div>
		                    	<input type="text" class="form-control" placeholder="Enter Item Code" id="sac" name="sac" value="<?php if($_POST) echo $_POST['sac'];else echo stripslashes($row->sac);?>" >
		                       	<span><?php echo $err_sac;?></span>
						     </div>
		                </div>	
		                <!--end of sac-->	 


		                
		            	<div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Item Name<font color="#FF0000">*</font></label>
		                  <div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-bars"></i> 
						   	</div>
		                  	<input type="text" class="form-control" placeholder="Enter Item Name" id="name" name="name" value="<?php if($_POST) echo $_POST['name'];else echo stripslashes($row->name);?>"  data-parsley-required>
		                  	<span><?php echo $err_item_name;?></span>
						</div>
		                </div>
						<div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Item Code<font color="#FF0000">*</font></label>
		                  <div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-list-ol"></i> 
						   	</div>
		                  	<input type="text" class="form-control" placeholder="Enter Item Code" id="item_code" name="item_code" value="<?php if($_POST) echo $_POST['item_code'];else echo stripslashes($row->item_code);?>"  data-parsley-required>
		                  	<span><?php echo $err_item_code;?></span>
						  </div>
		                </div>		                
				
<?php					
/*$table_name = array(TBL_INV_PURCH_DETAILS);
$table_name3 = array(TBL_PURCH_DETAILS);
$table_name4 = array(TBL_INV_PO_DETAILS);
 $Id= encryptor(decrypt,$_REQUEST['eId']);
$ajaxCheckTransactions = CheckTransactionsItemss($Id, $table_name, $table_name3, $table_name4);  
if($ajaxCheckTransactions != '1'){ */

 

$Id= encryptor(decrypt,$_REQUEST['eId']);
$rows = mysqli_num_rows(mysqli_query($connNew,"SELECT * FROM ".TBL_INV_INDENT_DETAILS." WHERE id_inv_items='".$Id."' "));
$rows1 = mysqli_num_rows(mysqli_query($connNew,"SELECT * FROM ".TBL_PURCH_DETAILS." WHERE id_mst_items='".$Id."' "));
$rows2 = mysqli_num_rows(mysqli_query($connNew,"SELECT * FROM ".TBL_INV_PO_DETAILS." WHERE id_inv_items='".$Id."' "));


if($Id==''){
	$disabled="";							
}else if($rows <= 0 && $rows1 <= 0 && $rows2 <= 0){
	$disabled="";
 }else{ 
    $disabled="disabled"; ?>
	
<input type="hidden" value="<?php echo $row->id_mst_attributes_unit_main ?>" name="id_mst_attributes_unit_main" />
<input type="hidden" value="<?php echo $row->id_mst_attributes_unit_alt ?>" name="id_mst_attributes_unit_alt" />
	
<?php }  ?>				
		
		                <div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Item Main Unit <font color="#FF0000">*</font></label>
		                  <div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-underline"></i> 
						   	</div>
		                 <?php $categoryDropDown = '<select class="form-control select2" name="id_mst_attributes_unit_main" id="id_mst_attributes_unit_main" data-parsley-required onchange="changeFunc()" style="width:100%" '.$disabled.' >
									<option value="">Select Main Unit</option>';
									
								$resCat1 = selectSql(TBL_ATTRIBUTES," where  table_name ='unit' AND status='0' AND id='".$row->id_mst_attributes_unit_main."' ");
									if($db->num_rows2($resCat1)){
										while($resultCat1 = $db->fetch_object2($resCat1)){
											if($row->id_mst_attributes_unit_main == $resultCat1->id){
												$selected = 'selected="selected"';
											}else{
												$selected = '';
											}
											$categoryDropDown .= '<option '.$selected.' value="'.$resultCat1->id.'">'.$resultCat1->field_value.'</option>';
										}
									}	
									
									
								  $resCat = selectSql(TBL_ATTRIBUTES," where id_shop='".$_SESSION['shop']."' and status = '1' and table_name ='unit' ",' ORDER BY `field_value`');
								  if($db->num_rows2($resCat)){
								  	while($resultCat = $db->fetch_object2($resCat)){					  		
										if($_REQUEST['id_mst_attributes_unit_main'] == $resultCat->id){
											$selected = 'selected="selected"';
										}elseif($row->id_mst_attributes_unit_main == $resultCat->id){
											$selected = 'selected="selected"';
										}else{
											$selected = '';
										}
										$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';
									}
								  }
								 	echo $categoryDropDown .= '</select>';
								  ?>
						<?php echo $err_id_mst_attributes_item_type;?></div>
		                </div>

		                
		       
		            	<div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Item Alt Unit <font color="#FF0000">*</font></label>
		                  <div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-underline"></i> 
						   	</div>
		                 <?php $categoryDropDown = '<select class="form-control select2" name="id_mst_attributes_unit_alt" id="id_mst_attributes_unit_alt" data-parsley-required onchange="changeFunc()" style="width:100%" '.$disabled.'>
									<option value="">Select Alt Unit</option>';
									
									$resCat1 = selectSql(TBL_ATTRIBUTES," where  table_name ='unit' AND status='0' AND id='".$row->id_mst_attributes_unit_alt."' ");
									if($db->num_rows2($resCat1)){
										while($resultCat1 = $db->fetch_object2($resCat1)){
											if($row->id_mst_attributes_unit_alt == $resultCat1->id){
												$selected = 'selected="selected"';
											}else{
												$selected = '';
											}
											$categoryDropDown .= '<option '.$selected.' value="'.$resultCat1->id.'">'.$resultCat1->field_value.'</option>';
										}
									}	
									
									
									
								 $resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."' and status = '1' and table_name ='unit' ",' ORDER BY `field_value`');
								  if($db->num_rows2($resCat)){
								  	while($resultCat = $db->fetch_object2($resCat)){
										if($_REQUEST['id_mst_attributes_unit_alt'] == $resultCat->id){
											$selected = 'selected="selected"';
										}elseif($row->id_mst_attributes_unit_alt == $resultCat->id){
											$selected = 'selected="selected"';
										}else{
											$selected = '';
										}
										$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';
									}
								  }
								 	echo $categoryDropDown .= '</select>';
								  ?>
						<?php echo $err_id_mst_attributes_item_type;?></div>
		                </div>

		                <div class="form-group col-xs-12 col-md-6 col-sm-2">
			                  <label for="name">Item Class <font color="#FF0000">*</font></label>
			                  
			                 	<?php if($row->id == !''){  ?>
			                 		<div class="input-group"> 
				              			<div class="input-group-addon">
											<i class="fa fa-check-square-o"></i> 
									   	</div>
			                 		<?php $type=1;
			                 		if($row->item_class == 'A'){
			                 			$categoryDropDown = '<select class="form-control select2" name="item_class" data-parsley-required style="width: 100%">
										<option value="A">A</option>';
			                 		}elseif($row->item_class == "B"){
			                 			$categoryDropDown = '<select class="form-control select2" name="item_class" data-parsley-required style="width: 100%">
										<option value="B">B</option>';
			                 		}elseif($row->item_class == "C"){
			                 			$categoryDropDown = '<select class="form-control select2" name="item_class" data-parsley-required style="width: 100%">
										<option value="C">C</option>';
			                 		}else{
			                 			$categoryDropDown = '<select class="form-control select2" name="item_class" data-parsley-required style="width: 100%">
										<option value="D">D</option>';
			                 		}
			                 		echo $categoryDropDown;
			                 	?>
									<option value="A">A</option>
									<option value="B">B</option>
									<option value="C">C</option>
									<option value="D">D</option>
								</select></div>
			                 	<?php 
								} else{ ?>
									<div class="input-group"> 
			              			<div class="input-group-addon">
										<i class="fa fa-check-square-o"></i> 
								   	</div>
			                 	<select class="form-control select2" name="item_class" data-parsley-required style="width: 100%">
									<option value="">Select Item Class</option>
									<option value="A">A</option>
									<option value="B">B</option>
									<option value="C">C</option>
									<option value="D">D</option>
								</select></div>
							<?php } ?>
							<?php echo $err_item_class;?>
		                </div> 
		                <style type="text/css">
		                	#convhideandshow{
		                		display: none1;
		                	}
		                </style>
		                <style type="text/css">
							#main1{display: none; color: green;} 
							#alt1{display: none; color: green;}

							#main{color: green;} 
							#alt{color: green;} 
						</style>
		                <div class="form-group col-xs-12 col-md-6 col-sm-2" id="convhideandshow" name="convhideandshow">
		                <div class="col-md-3 col-sm-2">
						
<?php 	
$unitv = "SELECT * From `".TBL_ATTRIBUTES."` WHERE id = '".$row->id_mst_attributes_unit_main."'  ";
$unitt = mysqli_query($connNew, $unitv);	
$unit = mysqli_fetch_object($unitt);	
if($row->id_mst_attributes_unit_main=='')	{ ?>
	<div id="main" name="main" style="font-size: 15px;"><ul><li></li></ul></div>
	
<?php }else{ ?>

<div id="main" name="main" style="font-size: 15px;"> 1 - <?php echo $unit->field_value; ?></div>

<?php }	 ?>
				 
							 
		                </div>
		                <div class="col-md-6 col-sm-2">
			                  <label for="name">Conversion Quantity</label>
			                  <div class="input-group"> 
			              			<div class="input-group-addon">
										<i class="fa fa-list-ol"></i> 
								   	</div>
								   <?php /*
								   if($_POST['id_mst_attributes_unit_alt']==$_POST['id_mst_attributes_unit_main']){
								   	$_POST['conversion_qty']=1;
								   	$conDisabled="disabled";
								   } else{
								   	$_POST['conversion_qty'];
								   	$conDisabled="";

								   }  */
								   	?>
									
			                  <input type="text" class="form-control" placeholder="Enter Conversion Quantity" id="conversion_qty" name="conversion_qty" value="<?php if($_POST) echo $_POST['conversion_qty'];else echo stripslashes($row->conversion_qty);?>" onchange="conversion()"  data-parsley-required <?=$conDisabled?>>
							  
							  <span><?php echo $err_conversion_qty;?></span>
							  <span><?php echo $err_conversion_qty1;?></span>
							  </div>
							  
							  
							  
			              </div>
			              <div class="col-md-3 col-sm-2">
						  
<?php 
$unitv1 = "SELECT * From `".TBL_ATTRIBUTES."` WHERE id = '".$row->id_mst_attributes_unit_alt."'  ";
$unitt1 = mysqli_query($connNew, $unitv1);	
$unit1 = mysqli_fetch_object($unitt1);	
if($row->id_mst_attributes_unit_alt=='')	{ ?>
	
	 <div id="alt" name="alt" style="font-size: 15px;"><ul><li></li></ul></div> 
	
<?php }else{ ?>

<div id="alt" name="alt" style="font-size: 15px;"> <?php echo $unit1->field_value; ?></div>

<?php }	 ?>						  
						  
						  
		                	
							 
							 
							 
							 
		                </div>
						<?php echo $err_item_confact;?>
		                </div>
		            </div> 
		        </div>
		        <hr>
		        <div class="card text-dark bg-light">
              		<div class="bg-primary text-center ">
              			<h5 style="padding: 5px;">Item Group Configuration</h5>
              		</div>
              		<hr> 
	              	<div class="row"> 

		                <div class="form-group col-xs-12 col-md-6 col-sm-2">
						

		                  <label for="name">Main Group <font color="#FF0000">*</font></label>
		                  <div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-object-group"></i> 
							   	</div>
								
								
					<?php
						 $categoryDropDown = '<select class="form-control select2" name="id_mst_attributes_group_main" data-parsley-required style="width:100%">
								<option value="">Select Main Group</option>';
						 
						 		
								$resCat1 = selectSql(TBL_ATTRIBUTES," where  table_name ='".'item_group_main'."' AND status='0' AND id='".$row->id_mst_attributes_group_main."' ");
									if($db->num_rows2($resCat1)){
										while($resultCat1 = $db->fetch_object2($resCat1)){
											if($row->id_mst_attributes_group_main == $resultCat1->id){
												$selected = 'selected="selected"';
											}else{
												$selected = '';
											}
											$categoryDropDown .= '<option '.$selected.' value="'.$resultCat1->id.'">'.$resultCat1->field_value.'</option>';
										}
									}	
									
									
								  $resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."' and status = '1' AND table_name ='".'item_group_main'."' ",' ORDER BY `field_value`');
								  if($db->num_rows2($resCat)){
								  	while($resultCat = $db->fetch_object2($resCat)){
										
										
										if($_REQUEST['id_mst_attributes_group_main'] == $resultCat->id){
											$selected = 'selected="selected"';
										}else if($row->id_mst_attributes_group_main == $resultCat->id){
											$selected = 'selected="selected"';
										}else{
											$selected = '';
										}
										
										$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';
										
										
									}
								  }
								 	echo $categoryDropDown .= '</select>';
								  ?>
								  

						<?php echo $err_item_maingroup;?></div>
		                </div>
			                <div class="form-group col-xs-12 col-md-6 col-sm-2">
			                  <label for="name">Sub Group <font color="#FF0000">*</font></label>
			                  <div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-outdent"></i> 
							   	</div>
			                 <?php $categoryDropDown = '<select class="form-control select2" name="id_mst_attributes_group_sub" data-parsley-required style="width:100%">
										<option value="">Select Sub Group</option>';
										
									$resCat1 = selectSql(TBL_ATTRIBUTES," where  table_name ='item_group_sub' AND status='0' AND id='".$row->id_mst_attributes_group_sub."' ");
									if($db->num_rows2($resCat1)){
										while($resultCat1 = $db->fetch_object2($resCat1)){
											if($row->id_mst_attributes_group_sub == $resultCat1->id){
												$selected = 'selected="selected"';
											}else{
												$selected = '';
											}
											$categoryDropDown .= '<option '.$selected.' value="'.$resultCat1->id.'">'.$resultCat1->field_value.'</option>';
										}
									}	
										
										
									  $resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."' and status = '1' AND table_name ='".'item_group_sub'."' ",' ORDER BY `field_value`');
									  if($db->num_rows2($resCat)){
									  	while($resultCat = $db->fetch_object2($resCat)){
											if($_REQUEST['id_mst_attributes_group_sub'] == $resultCat->id){
												$selected = 'selected="selected"';
											}elseif($row->id_mst_attributes_group_sub == $resultCat->id){
												$selected = 'selected="selected"';
											}else{
												$selected = '';
											}
											$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';
										}
									  }
									 	echo $categoryDropDown .= '</select>';
									  ?>
							<?php echo $err_item_subgroup;?></div>
			                </div>
		            </div>
		            <div class="row" >
		       <?php   if($submenu !='88'){ ?>     
		                <div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Store  </label>
		                  <div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-star-half-o"></i> 
						   	</div>
		                 <?php $categoryDropDown = '<select class="form-control select2" name="id_mst_attributes_store" style="width:100%">
									<option value="">Select Store</option>';
									
									$resCat1 = selectSql(TBL_ATTRIBUTES," where  table_name ='store' AND status='0' AND id='".$row->id_mst_attributes_store."' ");
									if($db->num_rows2($resCat1)){
										while($resultCat1 = $db->fetch_object2($resCat1)){
											if($row->id_mst_attributes_store == $resultCat1->id){
												$selected = 'selected="selected"';
											}else{
												$selected = '';
											}
											$categoryDropDown .= '<option '.$selected.' value="'.$resultCat1->id.'">'.$resultCat1->field_value.'</option>';
										}
									}	
									
									
								  $resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."'  and status = '1' AND table_name ='".'store'."' ",' ORDER BY `field_value`');
								  if($db->num_rows2($resCat)){
								  	while($resultCat = $db->fetch_object2($resCat)){
										if($_REQUEST['id_mst_attributes_store'] == $resultCat->id){
											$selected = 'selected="selected"';
										}elseif($row->id_mst_attributes_store == $resultCat->id){
											$selected = 'selected="selected"';
										}else{
											$selected = '';
										}
										$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';
									}
								  }
								 	echo $categoryDropDown .= '</select>';
								  ?>
						<?php //echo $err_item_subgroup;?></div>
		                </div>
			   <?php } ?>   
		            
		                <div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Printer <font color="#FF0000">*</font></label>
		                  <div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-print"></i> 
						   	</div>
		                 <?php $categoryDropDown = '<select class="form-control select2" name="id_mst_attributes_printer" style="width:100%">
									<option value="">Select Printer</option>';
									
									$resCat1 = selectSql(TBL_ATTRIBUTES," where  table_name ='printer' AND status='0' AND id='".$row->id_mst_attributes_printer."' ");
									if($db->num_rows2($resCat1)){
										while($resultCat1 = $db->fetch_object2($resCat1)){
											if($row->id_mst_attributes_printer == $resultCat1->id){
												$selected = 'selected="selected"';
											}else{
												$selected = '';
											}
											$categoryDropDown .= '<option '.$selected.' value="'.$resultCat1->id.'">'.$resultCat1->field_value.'</option>';
										}
									}	
									
									
								  $resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."' and status = '1'  AND table_name ='".'printer'."' ",' ORDER BY `field_value`');
								  if($db->num_rows2($resCat)){
								  	while($resultCat = $db->fetch_object2($resCat)){
										if($_REQUEST['id_mst_attributes_printer'] == $resultCat->id){
											$selected = 'selected="selected"';
										}elseif($row->id_mst_attributes_printer == $resultCat->id){
											$selected = 'selected="selected"';
										}else{
											$selected = '';
										}
										$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';
									}
								  }
								 	echo $categoryDropDown .= '</select>';
						?>
						<?php echo $err_item_subgroup;?></div>
		                </div>
						
			
                        
		                 </div>
		                
		        </div>
		        <hr>
		        <div class="card text-dark bg-light">
              		<div class="bg-primary text-center ">
              			<h5 style="padding: 5px;">Tax Configuration</h5>
              		</div>
              		<hr> 
	              	<div class="row">	 
	              		   
		                <div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Sales Account Local </label>
		                   <div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-bolt"></i> 
							   	</div>
		                 <?php $categoryDropDown = '<select class="form-control select2" name="id_mst_charges_sales_local" id="id_mst_charges_sales_local" onchange="salesaccountlocal()" style="width:100%">
									<option value="">Select Sales Account Local</option>';
									
								$resCat1 = selectSql(TBL_CHARGES," where  status='0' and charges_account = '1' and transaction_type = '1' AND id='".$row->id_mst_charges_sales_local."' ");
									if($db->num_rows2($resCat1)){
										while($resultCat1 = $db->fetch_object2($resCat1)){
											if($row->id_mst_charges_sales_local == $resultCat1->id){
												$selected = 'selected="selected"';
											}else{
												$selected = '';
											}
											$categoryDropDown .= '<option '.$selected.' value="'.$resultCat1->id.'">'.$resultCat1->name.'</option>';
										}
									}	
									
								  $resCat = selectSql(TBL_CHARGES,"where id_shop='".$_SESSION['shop']."' and status = '1'  and charges_account = '1' and transaction_type = '1'",' ORDER BY `name`');
								  if($db->num_rows2($resCat)){
								  	while($resultCat = $db->fetch_object2($resCat)){
										if($_REQUEST['id_mst_charges_sales_local'] == $resultCat->id){
											$selected = 'selected="selected"';
										}elseif($row->id_mst_charges_sales_local == $resultCat->id){
											$selected = 'selected="selected"';
										}else{
											$selected = '';
										}
										$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
									}
								  }
								 	echo $categoryDropDown .= '</select>';
								  ?>
						<?php echo $err_item_chargestax;?></div>
						<style type="text/css">
							#sgst{display: none; color: green;} 
							#cgst{display: none; color: green;}
							#vat{display: none; color: green;} 
							#cess{display: none; color: green;}
							#surcharge{display: none; color: green;}
						</style>
		                <div id="sgst" name="sgst"><ul><li>sgst</li></ul></div>
		                <div id="cgst" name="cgst"><ul><li>cgst</li></ul></div>
		                <div id="vat" name="vat"><ul><li>vat</li></ul></div>
		                <div id="cess" name="cess"><ul><li>cess</li></ul></div>
                        <div id="surcharge" name="cess"><ul><li>cess</li></ul></div>
		                </div>
		                <div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Sales Account Interstate </label>
		                  <div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-outdent"></i> 
							   	</div>
								
								
		                 <?php $categoryDropDown = '<select class="form-control select2" name="id_mst_charges_sales_interstate" id="id_mst_charges_sales_interstate" onchange="salesaccountinterstate()" style="width:100%">
									<option value="">Select Sales Account Interstate</option>';
									
									$resCat1 = selectSql(TBL_CHARGES," where  status='0' and charges_account = '1' and transaction_type = '2' AND id='".$row->id_mst_charges_sales_interstate."' ");
									if($db->num_rows2($resCat1)){
										while($resultCat1 = $db->fetch_object2($resCat1)){
											if($row->id_mst_charges_sales_interstate == $resultCat1->id){
												$selected = 'selected="selected"';
											}else{
												$selected = '';
											}
											$categoryDropDown .= '<option '.$selected.' value="'.$resultCat1->id.'">'.$resultCat1->name.'</option>';
										}
									}
								  $resCat = selectSql(TBL_CHARGES,"where id_shop='".$_SESSION['shop']."' and status = '1' and charges_account = '1' and transaction_type = '2'",' ORDER BY `name`');
								  if($db->num_rows2($resCat)){
								  	while($resultCat = $db->fetch_object2($resCat)){
										if($_REQUEST['id_mst_charges_sales_interstate'] == $resultCat->id){
											$selected = 'selected="selected"';
										}elseif($row->id_mst_charges_sales_interstate == $resultCat->id){
											$selected = 'selected="selected"';
										}else{
											$selected = '';
										}
										$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
									}
								  }
								 	echo $categoryDropDown .= '</select>';
								  ?>
						<?php echo $err_item_chargestax;?></div>

						<style type="text/css">
							#saleigst{display: none; color: green;}  
						</style>
		                <div id="saleigst" name="saleigst"><ul><li>igst</li></ul></div> 

		                </div>
		                
		            </div>

		            <div class="row">

		                <div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Purchase Account Local</label>
		                  <div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-list-alt"></i> 
							   	</div>
		                 <?php $categoryDropDown = '<select class="form-control select2" name="id_mst_charges_purchase_local" id="id_mst_charges_purchase_local" onchange="purchaseaccountlocal()" style="width:100%">
									<option value="">Select Purchase Account Local</option>';
									
									$resCat1 = selectSql(TBL_CHARGES," where  status='0' and charges_account = '2' and transaction_type = '1' AND id='".$row->id_mst_charges_purchase_local."' ");
									if($db->num_rows2($resCat1)){
										while($resultCat1 = $db->fetch_object2($resCat1)){
											if($row->id_mst_charges_purchase_local == $resultCat1->id){
												$selected = 'selected="selected"';
											}else{
												$selected = '';
											}
											$categoryDropDown .= '<option '.$selected.' value="'.$resultCat1->id.'">'.$resultCat1->name.'</option>';
										}
									}
									
								  $resCat = selectSql(TBL_CHARGES,"where id_shop='".$_SESSION['shop']."' and status = '1'  and charges_account = '2' and transaction_type = '1' ",' ORDER BY `name`');
								  if($db->num_rows2($resCat)){
								  	while($resultCat = $db->fetch_object2($resCat)){
										if($_REQUEST['id_mst_charges_purchase_local'] == $resultCat->id){
											$selected = 'selected="selected"';
										}elseif($row->id_mst_charges_purchase_local == $resultCat->id){
											$selected = 'selected="selected"';
										}else{
											$selected = '';
										}
										$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
									}
								  }
								 	echo $categoryDropDown .= '</select>';
								  ?>
						<?php echo $err_item_chargestax;?></div>
						<style type="text/css"> 
							#purchasevat{display: none; color: green;} 
							#purchasecess{display: none; color: green;}
						</style> 
		                <div id="purchasevat" name="purchasevat"><ul><li>vat</li></ul></div>
		                <div id="purchasecess" name="purchasecess"><ul><li>cess</li></ul></div>
		                </div>
		                <div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Purchase Account Interstate</label>
		                   <div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-podcast"></i> 
							   	</div>
		                 <?php $categoryDropDown = '<select class="form-control select2" name="id_mst_charges_purchase_interstate" id="id_mst_charges_purchase_interstate" onchange="purchaseaccountinterstate()" style="width:100%">
									<option value="">Select Purchase Account Interstate</option>';
								  $resCat = selectSql(TBL_CHARGES,"where id_shop='".$_SESSION['shop']."' and status = '1' and charges_account = '2' and transaction_type = '2' ",' ORDER BY `name`');
								  if($db->num_rows2($resCat)){
								  	while($resultCat = $db->fetch_object2($resCat)){
										if($_REQUEST['id_mst_charges_purchase_interstate'] == $resultCat->id){
											$selected = 'selected="selected"';
										}elseif($row->id_mst_charges_purchase_interstate == $resultCat->id){
											$selected = 'selected="selected"';
										}else{
											$selected = '';
										}
										$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
									}
								  }
								 	echo $categoryDropDown .= '</select>';
								  ?>
						<?php echo $err_item_chargestax;?></div>
						<style type="text/css">
							#purchaseigst{display: none; color: green;}  
						</style>
		                <div id="purchaseigst" name="purchaseigst"><ul><li>igst</li></ul></div> 
		                </div>
		            </div>
		        </div>
		        <hr>
		        <div class="card text-dark bg-light">
              		<div class="bg-primary text-center ">
              			<h5 style="padding: 5px;">Rate</h5>
              		</div> 
	              	<div class="row">

		                <div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Standard Sale Rate</label>
		                  <div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-th-list"></i> 
							   	</div>
		                  <input type="text" class="form-control" placeholder="Enter Standard Sale Rate" id="sale_rate" name="sale_rate" value="<?php if($_POST) echo $_POST['sale_rate'];else echo stripslashes($row->sale_rate);?>" >
						<?php echo $err_item_srno;?></div>
		                </div>

		                <div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Standard Purchase Rate</label>
		                  <div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-th-large"></i> 
							   	</div>
		                  <input type="text" class="form-control" placeholder="Enter Standard Purchase Rate" id="purchase_rate" name="purchase_rate" value="<?php if($_POST) echo $_POST['purchase_rate'];else echo stripslashes($row->purchase_rate);?>" >
						<?php echo $err_item_srno;?></div>
		                </div>

		                <div class="form-group col-xs-12 col-md-6 col-sm-2" style="display: none;">
		                  <label for="name">Last Purchase Rate</label>
		                  <input type="text" class="form-control" placeholder="Enter Last Purchase Rate" id="last_purchase_rate" name="last_purchase_rate" value="<?php if($_POST) echo $_POST['last_purchase_rate'];else echo stripslashes($row->last_purchase_rate);?>" >
						<?php echo $err_item_srno;?>
		                </div>
		            </div>
		        </div>
		        <hr>

		        <div class="card text-dark bg-light">
              		<div class="bg-primary text-center ">
              			<h5 style="padding: 5px;">Stock Details</h5>
              		</div> 
              		<hr>
	              	<div class="row">

		                <div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Min Stock Level</label>
		                  <div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-arrows-alt"></i> 
							   	</div>
		                  <input type="text" class="form-control" placeholder="Enter Min Stock Level" id="min_qty" name="min_qty" value="<?php if($_POST) echo $_POST['min_qty'];else echo stripslashes($row->min_qty);?>" >
						<?php echo $err_item_srno;?></div>
		                </div>
						
						<div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Max Stock Level</label>
		                  <div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-caret-square-o-up"></i> 
							   	</div>
		                  <input type="text" class="form-control" placeholder="Enter Max Stock Level" id="max_qty" name="max_qty" value="<?php if($_POST) echo $_POST['max_qty'];else echo stripslashes($row->max_qty);?>" >
						<?php echo $err_item_srno;?></div>
		                </div>
		            </div>
		            <div class="row">
		                <div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Rol</label>
		                  <div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-arrows"></i> 
							   	</div>
		                  <input type="text" class="form-control" placeholder="Enter Rol" id="rol" name="rol" value="<?php if($_POST) echo $_POST['rol'];else echo stripslashes($row->rol);?>" >
						<?php echo $err_item_srno;?></div>
		                </div>

		                <div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Re Order Qty</label>
		                  <div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-caret-square-o-down"></i> 
							   	</div>
		                  <input type="text" class="form-control" placeholder="Enter Re Order Qty" id="roq" name="roq" value="<?php if($_POST) echo $_POST['roq'];else echo stripslashes($row->roq);?>" >
						<?php echo $err_item_srno;?></div>
		                </div>
		            </div>
		        </div>
		        <hr>
		        <div class="card text-dark bg-light">
              		<div class="bg-primary text-center ">
              			<h5 style="padding: 5px;">Opening Quantity</h5>
              		</div> 
              		<hr>
	              	<div class="row">
		                <div class="form-group  col-md-6 col-sm-2" style="display: none;">
		                  <label for="name">Balance Qty</label>
		                  <div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-check-square-o"></i> 
							   	</div>
		                  <input type="text" class="form-control" placeholder="Enter Balance Qty" id="bal_qty" name="bal_qty" value="<?php if($_POST) echo $_POST['bal_qty'];else echo stripslashes($row->bal_qty);?>" >
						<?php echo $err_item_srno;?></div>
		                </div>

		                <div class="form-group  col-md-6 col-sm-2">
		                  <label for="name">Opening Qty</label>
		                  <div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-plus-square-o"></i> 
							   	</div>
		                  <input type="text" class="form-control" placeholder="Enter Opening Qty" id="open_qty" name="open_qty" value="<?php if($_POST) echo $_POST['open_qty'];else echo stripslashes($row->open_qty);?>" >
						<?php echo $err_item_srno;?></div>
		                </div>

		                <div class="form-group  col-md-6 col-sm-2">
		                  <label for="name">Opening Value</label>
		                  <div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-square-o"></i> 
							   	</div>
		                  <input type="text" class="form-control" placeholder="Enter Opening Value" id="open_amount" name="open_amount" value="<?php if($_POST) echo $_POST['open_amount'];else echo stripslashes($row->open_amount);?>" >
						<?php echo $err_item_srno;?></div>
		                </div>
		            </div>
		         </div>
		         <hr>
		         <div class="card text-dark bg-light">
              		<div class="bg-primary text-center ">
              			<h5 style="padding: 5px;">Item Image </h5>
              		</div> 
              		<hr>
			         <div class="row">	
						
						<div class="col-sm-3">
							<div class="form-group">				
							 <label for="image">Item Image &nbsp;&nbsp;</label>
								<div class="btn btn-default btn-file">
								  <i class="fa fa-upload"></i> Upload
								 <input type="file" class="form-control" placeholder="Item Image" id="item_image" name="item_image" value="" onchange="readURL(this);">	
								 <input type="hidden" name="old_image" value="<?php echo stripslashes($row->item_image);?>"/>					 
							
								</div>
								<p class="help-block">Must be of width:600px and height:300px.<br />Max. Size: 1MB</p>							 
						</div>	
						<?php echo $err_image;?>
						</div>								
						<div class="col-sm-9">													
							<ul class="mailbox-attachments clearfix"> 
										<li id="imageCallback">
										<?php if(@file_exists($image_path.$row->item_image) && $row->item_image!=''){ ?>
										<span class="mailbox-attachment-icon has-img">							 
											<img src="<?php echo $image_display_path.$row->item_image; ?>" alt="Item Image">							  
										  </span>			
										  <div class="mailbox-attachment-info">
											<a href="javascript:void(0);" class="mailbox-attachment-name"><i class="fa fa-camera"></i> <?php echo $row->item_image; ?></a>
												<span class="mailbox-attachment-size">
												  <?php echo round(filesize($image_path.$row->item_image)/ 1024 ,2).' KB'; ?>
												  <a href="<?php echo $image_display_path.$row->item_image; ?>" download class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
												</span>
										  </div>
										<?php }else{ ?>							
										<span class="mailbox-attachment-icon has-img">							 
											<img src="../images/no-hotel-image.jpg" alt="Item Image" id="blah">							  
										  </span>			
										  <div class="mailbox-attachment-info">
											<a href="javascript:void(0);" class="mailbox-attachment-name"><i class="fa fa-camera"></i> no-hotel-image.jpg</a>
												<span class="mailbox-attachment-size">
												   <?php echo round(filesize('../images/no-hotel-image.jpg')/ 1024 ,2).' KB'; ?>
												  <a href="../images/no-hotel-image.jpg" download class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
												</span>
										  </div>							
										<?php }?> 
										  
										</li>                
									  </ul>			  
						 </div>
					</div>
				</div>
                <?php 
				        	if($row->item_details == '' || $row->item_details=='1'){
				        		 $item_details = 1;
								$styleclass	=	 'style="display: none;"';
								 
				        	}else{
				        		$item_details = $row->item_details;
								
				        	}
				        ?>
                   <div id="Displayview2" class="desc"  <?php echo $styleclass ;?> >
      
    			<div class="card text-dark bg-light">
              		<div class="bg-primary text-center ">
              			<h5 style="padding: 5px;">Item Details</h5>
              		</div> 
              		<hr>
	              	<div class="row">
					
					
						
				 <div class="pull-right " style="margin-right:150px;font-weight: 700;">Add Sub Service <button class="btn btn-success btn-sm" type="button"  onclick="addNewGrid();"  ><i class="fa fa-plus-circle"></i></button></div>  <br/> <br/> 
					
                      <?php 
					    $sqlitemDetail = mysqli_query($connNew,"SELECT *  from `".TBL_INV_ITEMS_DETAILS."` WHERE id_item='".$row->id."'");

						$numitemDetail=	mysqli_num_rows($sqlitemDetail);
						if($numitemDetail>0){
						$i=0;
						while($rowitemDetail=mysqli_fetch_object($sqlitemDetail)){
						?>
                      <div id="grid<?php echo $i;?>" style="float:left; width:100%;">  
			                 
                            
                            
                            <div class="form-group col-xs-12 col-md-2 col-sm-2">
			                  <label for="name">Name</label>
			                  <input type="text" class="form-control" placeholder="Enter Name" id="detail_name" name="detail_name[]" value="<?php if($_POST) echo $_POST['detail_name'];else echo stripslashes($rowitemDetail->name);?>"> 
			                </div> 

			                <div class="form-group col-xs-12 col-md-2 col-sm-2">
			                  <label for="name">Sub Code</label>
			                  <input type="text" class="form-control" placeholder="Enter Sub Code" id="detail_subcode" name="detail_subcode[]" value="<?php if($_POST) echo $_POST['detail_subcode'];else echo stripslashes($rowitemDetail->sub_code);?>"> 
			                </div> 

			                <div class="form-group col-xs-12 col-md-2 col-sm-2">
			                  <label for="name">Unit</label>
							  
							   <?php $categoryDropDown = '<select class="form-control select2" name="detail_unit[]" id="detail_unit"  onchange="changeFunc()" style="width:100%">
									<option value="">Select Unit</option>';
								  $resCat = selectSql(TBL_ATTRIBUTES," where id_shop='".$_SESSION['shop']."' and status = '1' and table_name ='unit' ",' ORDER BY `field_value`');
								  if($db->num_rows2($resCat)){
								  	while($resultCat = $db->fetch_object2($resCat)){					  		
										if($_REQUEST['detail_unit'] == $resultCat->id){
											$selected = 'selected="selected"';
										}elseif($rowitemDetail->id_unit == $resultCat->id){
											$selected = 'selected="selected"';
										}else{
											$selected = '';
										}
										$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';
									}
								  }
								 	echo $categoryDropDown .= '</select>';
								  ?>
							  
							  
			                <!--  <input type="text" class="form-control" placeholder="Enter Unit" id="detail_unit" name="detail_unit[]" value="<?php if($_POST) echo $_POST['detail_unit'];else echo stripslashes($rowitemDetail->id_unit);?>">  -->
			                </div> 

			                 <div class="form-group col-xs-12 col-md-2 col-sm-2">
			                  <label for="name">Rate</label>
			                  <input type="text" class="form-control" placeholder="Enter Rate" id="detail_rate" name="detail_rate[]" value="<?php if($_POST) echo $_POST['detail_rate'];else echo stripslashes($rowitemDetail->rate);?>"> 
			                </div>
							
							<div class="form-group col-xs-12 col-md-2 col-sm-2">
			                  <label for="name">Enabled</label>
			                  	<?php if($rowitemDetail->enabled == !''){  ?>
			                 		
			                 		<?php 
			                 		if($rowitemDetail->enabled == '1'){
			                 			$categoryDropDown = '<select class="form-control select2" name="enabled[]" data-parsley-required style="width: 100%">
										<option value="1">Yes</option>';
			                 		}elseif($rowitemDetail->enabled == "2"){
			                 			$categoryDropDown = '<select class="form-control select2" name="enabled[]" data-parsley-required style="width: 100%">
										<option value="2">No</option>';
			                 		}
			                 		echo $categoryDropDown;
			                 	?>
									<option value="1">Yes</option>
									<option value="2">No</option>
								</select>
			                 	<?php 
								} else{ ?>
									
			                 	<select class="form-control select2" name="enabled[]" data-parsley-required style="width: 100%">
									<option value="1">Yes</option>
									<option value="2">No</option>
								</select>
							<?php } ?>
			                </div>
                            <div class="form-group col-xs-12 col-md-2 col-sm-2">
                            
							
                          <!---   <button class="pull-left btn btn-success btn-sm" type="button"  onclick="addNewGrid();"  style="margin-top: 29px;float:right;" ><i class="fa fa-plus-circle"></i></button>  -->
                            
<?php 
$rows = mysqli_num_rows(mysqli_query($connNew,"SELECT * FROM pos_purch_details WHERE id_mst_items='".$rowitemDetail->id."' ")); 
if($rows <= 0){
?>
 <a class="btn btn-danger btn-sm" style="margin-top: 29px;" href="javascript:void(0);"  onclick="removeGrid(<?php echo $i++; ?>);"><i class="fa fa-trash-o fa-lg"></i> </a>
<!-- <i class="fa fa-commenting-o" style="font-size:30px" aria-hidden="true"></i> -->
<?php
}	else {
?>

<img src="../images/chat.png" style="cursor:pointer;margin-top: 29px; " title="In Use" /> 
<?php } ?>		
                         <!--    <a class="btn btn-danger btn-sm" style="margin-top: 29px;" href="javascript:void(0);"  onclick="removeGrid(<?php echo $i++; ?>);"><i class="fa fa-trash-o fa-lg"></i> </a> -->
                           

						   <?php
								
							 $i++;
							  ?>
                            </div>
                            <div class="form-group col-xs-12 col-md-2 col-sm-2"></div>
                    </div>
                    
                    <?php }
						}else{?>
					
							<div id="grid<?php echo $i;?>">  		                 
                            
                            
                            <div class="form-group col-xs-12 col-md-2 col-sm-2">
			                  <label for="name">Name</label>
			                  <input type="text" class="form-control" placeholder="Enter Name" id="detail_name" name="detail_name[]" value="<?php if($_POST) echo $_POST['start_no'];else echo stripslashes($rowDocCofig->start_no);?>"> 
			                </div> 

			                <div class="form-group col-xs-12 col-md-2 col-sm-2">
			                  <label for="name">Sub Code</label>
			                  <input type="text" class="form-control" placeholder="Enter Sub Code" id="detail_subcode" name="detail_subcode[]" value="<?php if($_POST) echo $_POST['numeric_part'];else echo stripslashes($rowDocCofig->numeric_part);?>"> 
			                </div> 

			                <div class="form-group col-xs-12 col-md-2 col-sm-2">
			                  <label for="name">Unit</label>
							  
							   <?php $categoryDropDown = '<select class="form-control select2" name="detail_unit[]" id="detail_unit" onchange="changeFunc()" style="width:100%">
									<option value="">Select Unit</option>';
									
									
									$resCat1 = selectSql(TBL_ATTRIBUTES," where  table_name ='unit' AND status='0' AND id='".$rowitemDetail->id_unit."' ");
									if($db->num_rows2($resCat1)){
										while($resultCat1 = $db->fetch_object2($resCat1)){
											if($rowitemDetail->id_unit == $resultCat1->id){
												$selected = 'selected="selected"';
											}else{
												$selected = '';
											}
											$categoryDropDown .= '<option '.$selected.' value="'.$resultCat1->id.'">'.$resultCat1->field_value.'</option>';
										}
									}	
									
									
								  $resCat = selectSql(TBL_ATTRIBUTES," where id_shop='".$_SESSION['shop']."' and status = '1' and table_name ='unit' ",' ORDER BY `field_value`');
								  if($db->num_rows2($resCat)){
								  	while($resultCat = $db->fetch_object2($resCat)){					  		
										if($_REQUEST['detail_unit'] == $resultCat->id){
											$selected = 'selected="selected"';
										}elseif($rowitemDetail->id_unit == $resultCat->id){
											$selected = 'selected="selected"';
										}else{
											$selected = '';
										}
										$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';
									}
								  }
								 	echo $categoryDropDown .= '</select>';
								  ?>
							  
							  
			               <!--   <input type="text" class="form-control" placeholder="Enter Unit" id="detail_unit" name="detail_unit[]" value="<?php if($_POST) echo $_POST['prefix'];else echo stripslashes($rowDocCofig->prefix);?>">  -->
							  
			                </div> 

			                 <div class="form-group col-xs-12 col-md-2 col-sm-2">
			                  <label for="name">Rate</label>
			                  <input type="text" class="form-control" placeholder="Enter Rate" id="detail_rate" name="detail_rate[]" value="<?php if($_POST) echo $_POST['suffix'];else echo stripslashes($rowDocCofig->suffix);?>"> 
			                </div>
							
							<div class="form-group col-xs-12 col-md-2 col-sm-2">
			                  <label for="name">Enabled</label>
			                  	<?php if($rowitemDetail->enabled == !''){  ?>
			                 		
			                 		<?php 
			                 		if($rowitemDetail->enabled == '1'){
			                 			$categoryDropDown = '<select class="form-control select2" name="enabled[]" data-parsley-required style="width: 100%">
										<option value="1">Yes</option>';
			                 		}elseif($rowitemDetail->enabled == "2"){
			                 			$categoryDropDown = '<select class="form-control select2" name="enabled[]" data-parsley-required style="width: 100%">
										<option value="2">No</option>';
			                 		}
			                 		echo $categoryDropDown;
			                 	?>
									<option value="1">Yes</option>
									<option value="2">No</option>
								</select>
			                 	<?php 
								} else{ ?>
									
			                 	<select class="form-control select2" name="enabled[]" data-parsley-required style="width: 100%">
									<option value="1">Yes</option>
									<option value="2">No</option>
								</select>
							<?php } ?>
			                </div>
							
                            <div class="form-group col-xs-12 col-md-2 col-sm-2">
                             <label for="name"></label>
							
							<a class="btn btn-danger btn-sm" style="margin-top: 29px;margin-right:10px;margin-left:-23px" href="javascript:void(0);"  onclick="removeGrid(<?php echo $i++; ?>);"><i class="fa fa-trash-o fa-lg"></i> </a>
							
                             <?php  $i++;  ?>
                            </div>
                            
                    </div>
							
							<?php } 
							?>
                             <?php 
				        	if($row->status == ''){
				        		$status = 1;
				        	}else{
				        		$status = $row->status;
				        	}
				        ?>
                    <div id="rowGrid"></div>
                    </div>

 </div></div>
				
		        <div class="card text-dark bg-light">
              		<!--<div class="bg-primary text-center ">
              			<h5 style="padding: 5px;">Additional Configuration</h5>
              		</div> -->
              		<hr>
              		<div class="boxx">
	              	<div class="row">
	              			<div class="form-group col-md-1 col-sm-3" id="containers">
		                	<div class="st-box">
		                	 Ingredients
		                	</div>
                           

			              
							 
		                </div>
		                <div class="form-group col-md-3 col-sm-3">
		                  <label for="item_enable_desc_billing">Enable Description :</label>
		                 	<input class="flat-red" type="radio"  <?php if($_POST['item_enable_desc_billing'] == '1'){echo "checked";}else{if($row->item_enable_desc_billing == 1)echo "checked";}?> value="1" name="item_enable_desc_billing"/> Active
						 	<input class="flat-red" type="radio" <?php if($_POST['item_enable_desc_billing'] == '0'){echo "checked";}else{if($row->item_enable_desc_billing == 0)echo "checked";}?> value="0" name="item_enable_desc_billing"/> Inactive
						 	<?php echo $err_status;?>
						</div>
						<div class="form-group col-md-4 col-sm-3">
							 <label for="item_allow_additional">Allow Additional :</label>
			                 <input class="flat-red" type="radio"  <?php if($_POST['item_allow_additional'] == '1'){echo "checked";}else{if($row->item_allow_additional == 1)echo "checked";}?> value="1" name="item_allow_additional"/> Active
							 <input class="flat-red" type="radio" <?php if($_POST['item_allow_additional'] == '0'){echo "checked";}else{if($row->item_allow_additional == 0)echo "checked";}?> value="0" name="item_allow_additional"/> Inactive
							 <?php echo $err_status;?>&nbsp;&nbsp;&nbsp;
						</div>
						<div class="form-group col-md-4 col-sm-3">	                
                        
                        <label for="item_details">Item Details :</label>
                        
                        <input class="flat-red" type="radio" name="ItemDetails" id="ItemDetails"onClick="SelectItemDetails(2);"  <?php if($_POST['ItemDetails'] == '2'){echo "checked";}else{if($item_details == 2)echo "checked";}?> value="2"/>Active
                        <input type="radio" name="ItemDetails"  id="ItemDetails" onClick="SelectItemDetails(1);" <?php if($_POST['ItemDetails'] == '1'){echo "checked";}else{if($item_details == 1)echo "checked";}?>  value="1"   />Inactive
                        
                        
                        <?php echo $err_status;?>&nbsp;&nbsp;&nbsp;				
                        </div>
                        <!--form group end-->
                    </div>
                </div>
                <div class="boxx">
                <div class="row">
                		<div class="form-group col-md-1 col-sm-3" id="containers">
		                	<div class="st-box">
		                		Stock
		                	</div>
                           

			              
							 
		                </div>
                        <!--Form Group Start-->
						<div class="form-group col-md-3 col-sm-3">	                

							 <label for="batch_details">Batch Details :</label>
			                 <input class="flat-red" type="radio"  <?php if($_POST['batch_details'] == '1'){echo "checked";}else{if($row->batch_details == 1)echo "checked";}?> value="1" name="batch_details" > Active
							 <input class="flat-red" type="radio" <?php if($_POST['batch_details'] == '0'){echo "checked";}else{if($row->batch_details == 0)echo "checked";}?> value="0" name="batch_details" > Inactive
							 <?php echo $err_status;?>&nbsp;&nbsp;&nbsp;				
		                </div>
		                  <!--Form Group End-->
		                <!--Form Group Strt-->
						<div class="form-group col-md-4 col-sm-3" style="display: none;">
		                  <label for="edit_name_enable_disable">Name Enable or Disable :</label>
		                 	<input class="flat-red" type="radio"  <?php if($_POST['edit_name_enable_disable'] == '1'){echo "checked";}else{if($row->edit_name_enable_disable == 1)echo "checked";}?> value="1" name="edit_name_enable_disable"/> Active
						 	<input class="flat-red" type="radio" <?php if($_POST['edit_name_enable_disable'] == '0'){echo "checked";}else{if($row->edit_name_enable_disable == 0)echo "checked";}?> value="0" name="edit_name_enable_disable"/> Inactive
						 	<?php echo $err_status;?>&nbsp;&nbsp;&nbsp;
						</div>

						<div class="form-group col-md-4 col-sm-3">
						  	<label for="item_production_item">Production Item :</label>
			                 <input class="flat-red" type="radio"  <?php if($_POST['item_production_item'] == '1'){echo "checked";}else{if($row->item_production_item == 1)echo "checked";}?> value="1" name="item_production_item"/> Active
							 <input class="flat-red" type="radio" <?php if($_POST['item_production_item'] == '0'){echo "checked";}else{if($row->item_production_item == 0)echo "checked";}?> value="0" name="item_production_item"/> Inactive
							 <?php echo $err_status;?>&nbsp;&nbsp;&nbsp;
						</div>

						
						<div class="form-group col-md-4 col-sm-3">
						 <label for="stockable_enable_disable">Non Stockable :</label>
		                 <input class="flat-red" type="radio"  <?php if($_POST['stockable_enable_disable'] == '1'){echo "checked";}else{if($row->stockable_enable_disable == 1)echo "checked";}?> value="1" name="stockable_enable_disable"/> Active
						 <input class="flat-red" type="radio" <?php if($_POST['stockable_enable_disable'] == '0'){echo "checked";}else{if($row->stockable_enable_disable == 0)echo "checked";}?> value="0" name="stockable_enable_disable"/> Inactive
						 <?php echo $err_status;?>

		                </div>
		            </div>
		        </div>
		        <div class="boxx">
		        <div class="row">
		        	    <div class="form-group col-md-1 col-sm-3" id="containers">
		                	<div class="st-box">
		                		Status
		                	</div>
                           

			              
							 
		                </div>
                        
                        <div class="form-group col-md-3 col-sm-3" id="containers">
		                	<label for="status">Status :</label>
                           

			                <input class="flat-red" type="radio"  <?php if($_POST['status'] == '1'){echo "checked";}else{if($status == 1)echo "checked";}?> value="1" 
			                name="status" id="status" /> Active
							<input class="flat-red" type="radio" <?php if($_POST['status'] == '0'){echo "checked";}else{if($status == 0)echo "checked";}?> value="0" 
							name="status"  id="status"   /> Inactive
							 <?php echo $err_status;?>
							 
		                </div>
		                <div class="form-group col-md-4 col-sm-3 d-flex" >
		                	<label for="deactivate_date" class="mt-7">Inactive Date</label>
		                	<div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-calendar"></i> 
							   	</div>
		                  <input type="text" class="form-control datepicker" placeholder="dd-mm-yyyy" id="deactivate_date" name="deactivate_date" value="<?php if($_POST) echo $_POST['deactivate_date'];else echo stripslashes($row->deactivate_date);?>" >
							<?php echo $err_item_srno;?></div>
		                </div>

		           		<div class="form-group col-md-4 col-sm-3 d-flex">
		                	<label class="mt-7" for="display_order">Display Order</label>
		                	<div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-sort"></i> 
							   	</div>
		                  <input type="text" class="form-control" placeholder="Dispaly Order" id="display_order" name="display_order" value="<?php if($_POST) echo $_POST['display_order'];else echo stripslashes($row->display_order);?>" >
							<?php echo $err_item_srno;?></div>
		                </div>
	
                        
                        
                        <div class="form-group col-md-4 col-sm-3" style="display: none;">
		                	 <label for="item_get_expiry_details">Get Expiry Details :</label>
			                 <input class="flat-red" type="radio"  <?php if($_POST['	'] == '1'){echo "checked";}else{if($row->item_get_expiry_details == 1)echo "checked";}?> value="1" name="item_get_expiry_details"/> Active
							 <input class="flat-red" type="radio" <?php if($_POST['item_get_expiry_details'] == '0'){echo "checked";}else{if($row->item_get_expiry_details == 0)echo "checked";}?> value="0" name="item_get_expiry_details"/> Inactive
							 <?php echo $err_status;?>&nbsp;&nbsp;&nbsp;
		                </div>
					</div>
					<div class="row">
						
						
		                

		                
		               
		                
<style>
    .box{
        color: #fff;
        padding: 20px;
        display: none;
        margin-top: 20px;
    }
    .red{ background: #ff0000; }
    .green{ background: #228B22; }
    .blue{ background: #0000ff; }

    label{ margin-right: 7px; }
</style>
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script>
$(document).ready(function(){
    $('input[type="radio"]').click(function(){
        var inputValue = $(this).attr("value");
        var targetBox = $("." + inputValue);
        $(".box").not(targetBox).hide();
        $(targetBox).show();
    });
});

<?php if($_REQUEST['eId']!=''){?>

var gridNo = <?php echo $i;  }else{ ?>

//alert('yes');

var gridNo=1;
<?php } ?>

//var gridNo=1;
var gridNo1=0;
var gridNo2=1;
function addNewGrid(){

$('.select2').select2();
$('#outlet').select2();
$('#outlet_'+gridNo).select2();


var grid ='<div id="grid'+gridNo+'"><div class="form-group col-xs-12 col-md-2 col-sm-12" style="width:185px;margin-right:27px"><input type="text" class="form-control" placeholder="Enter Name" id="detail_name" name="detail_name[]" value="" style="width:185px;"></div><div class="form-group col-xs-12 col-md-2 col-sm-12" style="width:185px;margin-right: 27px;"><input type="text" class="form-control" placeholder="Enter Sub Code" id="detail_subcode" name="detail_subcode[]" value="" style="width:185px;"></div><div class="form-group col-xs-12 col-md-2 col-sm-2"> <select class="form-control parsley-error" name="detail_unit[]" value="" id="detail_unit'+gridNo2+'" style="width: 185px;"></select>  </div><div class="form-group col-xs-12 col-md-2 col-sm-2"><input type="text" class="form-control" placeholder="Enter Rate" id="detail_rate" name="detail_rate[]" value="" style="width:185px;margin-right: 27px;" ></div><td><div class="form-group col-xs-12 col-md-2 col-sm-2"> <select class="form-control parsley-error" name="enabled[]" id="enabled" style="width: 185px;"><option value="1">Yes</option><option value="2">No</option></select>  </div></td><td style="width: 4%;float: left;"><a class="btn btn-danger btn-sm" href="javascript:void(0);"  onclick="removeGrid('+gridNo+');"><i class="fa fa-trash-o fa-lg"></i> </a> </td></div><br/>';

//var grid ='<div id="grid'+gridNo+'"><table id="myTableOrder1" class="" cellspacing="0" style="font-size:14px;padding: 0px 0px;" >   <tbody><tr><td style="width:185px;"><div class="form-group col-xs-12 col-md-2 col-sm-2"><input type="text" class="form-control" placeholder="Enter Name" id="detail_name" name="detail_name[]" value="" style="width:185px;"></div></td><td style="width:185px;"><div class="form-group col-xs-12 col-md-2 col-sm-2"><input type="text" class="form-control" placeholder="Enter Sub Code" id="detail_subcode" name="detail_subcode[]" value="" style="width:185px;"></div></td><td><div class="form-group col-xs-12 col-md-2 col-sm-2"> <select class="form-control parsley-error" name="detail_unit[]" value="" id="detail_unit'+gridNo2+'" style="width: 185px;"></select>  </div></td><td style="width:185px;"><div class="form-group col-xs-12 col-md-2 col-sm-2"><input type="text" class="form-control" placeholder="Enter Rate" id="detail_rate" name="detail_rate[]" value="" style="width:185px;" ></div></td><td><div class="form-group col-xs-12 col-md-2 col-sm-2"> <select class="form-control parsley-error" name="enabled[]" id="enabled" style="width: 185px;"><option value="1">Yes</option><option value="2">No</option></select>  </div></td><td style="width: 4%;float: left;"><a class="btn btn-danger btn-sm" href="javascript:void(0);"  onclick="removeGrid('+gridNo+');"><i class="fa fa-trash-o fa-lg"></i> </a> </td></tr> </tbody></table></div>';
                    
	   $('#outlet_'+gridNo).select2();
		$('#outlet').select2();
		$('#rowGrid').append(grid);
		
		$.ajax({
			url: "ajax/id_unit_load.php",
			type: 'POST',
			dataType: "JSON",
			success: function(data) {
			  $('#detail_unit'+gridNo1+'').append(data);
			}
		});
		 
        gridNo++;
        gridNo1++;
        gridNo2++;
		           
		
    }
function removeGrid(id){
		
    $('#grid'+id).remove();
   
}


</script>


    				<div class="red box">You have selected <strong>red radio button</strong> so i am here</div>
    
		                
                     
                        
                        </div>


		            <div class="row" style="display: none;">
		                <div class="form-group col-md-4 col-sm-3">	                

							 <label for="item_disable">Item Disable :</label>
			                 <input class="flat-red" type="radio"  <?php if($_POST['item_disable'] == '1'){echo "checked";}else{if($row->item_disable == 1)echo "checked";}?> value="1" name="item_disable"> Active
							 <input class="flat-red" type="radio" <?php if($_POST['item_disable'] == '0'){echo "checked";}else{if($row->item_disable == 0)echo "checked";}?> value="0" name="item_disable" > Inactive
							 <?php echo $err_status;?>&nbsp;&nbsp;&nbsp;				
		                </div>              		               	
		           	</div>

		           	<div class="row">
		           		 
		           			                
		           	</div>
		        </div> 
				 
				
				<?php if($row->date_created){?>
					<br>
					<div class="row">
						<div class="form-group col-md-3">
		                	<label for="date_created">Date Created</label>
		                	<input type="text" disabled="disabled" class="form-control" id="date_created"  value="<?php echo stripslashes(dateformat($row->date_created));?>">				
		                </div> 

		                <div class="form-group col-md-3">
		                  <label for="last_modified_by">Created By</label>
						   <?php $sqlUserDetail = selectColumn(TBL_USERS,'name','where id="'.$row->id_mst_user_created_by.'" ');?>
		                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail);?>">				
		                </div> 
				
						<div class="form-group col-md-3">
		                  <label for="last_modified">Last Updated</label>
		                  <input type="text" disabled="disabled" class="form-control" id="last_modified" value="<?php echo stripslashes(dateformat($row->last_modified));?>">				
		                </div>  
				
						<div class="form-group col-md-3">
		                  <label for="last_modified_by">Last Updated By</label>
						   <?php $sqlUserDetail = selectColumn(TBL_USERS,'name','where id="'.$row->id_mst_user_modified_by.'" ');?>
		                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail);?>">				
		                </div> 
					</div> 
					 <a type='button' value='Alteration History' class="btn o-btn"  onclick="audittrial(this.value);" style="float:right">
					 	Alteration History
					 </a>
				<?php } ?>  
				
              </div>
              <!-- /.box-body -->	
			 <div class="box-footer">                                       
				<input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn c-btn" name="Save" >
			
			   <input type='button' value='Close' class="btn c-btn" onclick='location.replace("manageItems.php?submenu=<?php echo $_GET['submenu'] ?>"); '>
		      <!-- <input type='button' value='Audit Trail' class="btn btn-success"  onclick="audittrial(this.value);" style="float:right">-->
			 </div>
            </form>			
          </div>
          <!-- /.box -->
        </div>
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>	
   <script>
<?php  
//if($_REQUEST['eId'] != ''){ ?>

//window.onload = function() { selectSaleslocalAccount(<?php echo $row->id_mst_attributes_outlet; ?>,<?php echo $row->id_mst_charges_sales_local; ?>);

//};
							
<?php //} ?>	
</script>


  <script type="text/javascript">

	function show1(){
  document.getElementById('div1').style.display ='none';
}
function show2(){
  document.getElementById('div1').style.display = 'block';
}
//serviceCharge($('#service_charge').val());


	function  SelectItemDetails(ItemDetails){
			var test = ItemDetails;
		        $("div.desc").hide();
		        $("#Displayview" + test).show();
			}
			
			
<?php if($_REQUEST['eId'] == '') { ?>
	$("#convhideandshow").hide();
<?php } ?>			
			
			
function conversion() { 		
	var conversion_qty = document.getElementById("conversion_qty").value;
	//alert(conversion_qty);
	if(conversion_qty == '0'){
		alert("Please enter Conversion Quantity greater than 0");
		//$("#conversion_qty").prop('required', true);
		document.getElementById('conversion_qty').value = "1";
	 	

	}
}	
	

$(document).ready(function(){
     var unit_alt =  $('#id_mst_attributes_unit_alt').val();
        var unit_main =  $('#id_mst_attributes_unit_main').val();
          if(unit_alt!='' && unit_main!='' && unit_alt==unit_main){
          		    document.getElementById('conversion_qty').setAttribute('readonly',true);
          		   // var cqty = 	document.getElementById('conversion_qty').value;

          		    	//alert(cqty);

          } else{
                   //  document.getElementById('conversion_qty').removeAttribute('readOnly');

          }


	})
			
//Unit Section Here
	function changeFunc() { 
	    var id_mst_attributes_unit_main = document.getElementById("id_mst_attributes_unit_main");
	    var id_mst_attributes_unit_main = id_mst_attributes_unit_main.options[id_mst_attributes_unit_main.selectedIndex].value;

	    var id_mst_attributes_unit_alt = document.getElementById("id_mst_attributes_unit_alt");
	    var id_mst_attributes_unit_alt = id_mst_attributes_unit_alt.options[id_mst_attributes_unit_alt.selectedIndex].value;

	    if(id_mst_attributes_unit_main == id_mst_attributes_unit_alt){
	    	// $("#convhideandshow").hide();
			  $("#convhideandshow").show();
	    	  document.getElementById('conversion_qty').value = "1";

	    	    	  document.getElementById('conversion_qty').setAttribute("readOnly",true);
			 
			 var account = 'mainaltunit';
			 
			 $.ajax({
		        type: "post",
		        url: "../ajax/charges_master.php",
		        cache: false, 
		        data: { id_mst_attributes_unit_main : id_mst_attributes_unit_main,id_mst_attributes_unit_alt:id_mst_attributes_unit_alt, account:account } ,
		        dataType: 'json',
		        success: function(data)
				{  
				
				
					console.log(data);
					if(data == null){
						$("#main").css("display", "none");
						$("#alt").css("display", "none"); 
					}else{
						console.log(data);
						$("#main").css("display", "block");	
						$("#alt").css("display", "block");	 	

						var node = document.createElement("LI"); 
						var textnode1 = document.createTextNode(data['main']);
						var textnode2 = document.createTextNode(data['alt']); 
						//alert(textnode2);  
					if(data['main'] == undefined) {
							$("#main").css("display", "none");
							$("#alt").css("display", "none");
		  				}else{ 

							var item = document.getElementById("main").childNodes[0];
			  				item.replaceChild(textnode1, item.childNodes[0]);
			  				var item = document.getElementById("alt").childNodes[0];
			  				item.replaceChild(textnode2, item.childNodes[0]);
		  				} 
		 
		 
						 
					}
				}
		    });
			 
			 
			 
	    }else{
			
	    	$("#convhideandshow").show();
	    	document.getElementById('conversion_qty').value = "";
	     document.getElementById('conversion_qty').removeAttribute("readOnly",true);

	    	//Main Alt Unit Section
	    	 var account = 'mainaltunit';
			$.ajax({
		        type: "post",
		        url: "../ajax/charges_master.php",
		        cache: false, 
		        data: { id_mst_attributes_unit_main : id_mst_attributes_unit_main,id_mst_attributes_unit_alt:id_mst_attributes_unit_alt, account:account } ,
		        dataType: 'json',
		        success: function(data)
				{  
					console.log(data);
					if(data == null){
						$("#main").css("display", "none");
						$("#alt").css("display", "none"); 
					}else{
						console.log(data);
						$("#main").css("display", "block");	
						$("#alt").css("display", "block");	 	

						var node = document.createElement("LI"); 
						var textnode1 = document.createTextNode(data['main']);
						var textnode2 = document.createTextNode(data['alt']); 
						  
					if(data['main'] == undefined) {
							$("#main").css("display", "none");
							$("#alt").css("display", "none");
		  				}else{ 

							var item = document.getElementById("main").childNodes[0];
			  				item.replaceChild(textnode1, item.childNodes[0]);
			  				var item = document.getElementById("alt").childNodes[0];
			  				item.replaceChild(textnode2, item.childNodes[0]);
		  				} 
		 
		 
						 
					}
				}
		    });
	    }
	}



	
</script>						
<?php include_once("includes/footer.php")?>

<script type="text/javascript">
	function salesaccountlocal(){ 

		var id_mst_charges_sales_local = document.getElementById("id_mst_charges_sales_local");
	 	var selectedValue = id_mst_charges_sales_local.options[id_mst_charges_sales_local.selectedIndex].value;

	 var account = 'salesaccountlocal';
	$.ajax({
        type: "post",
        url: "../ajax/charges_master.php",
        cache: false, 
        data: { selectedValue : selectedValue, account:account } ,
        dataType: 'json',
        success: function(data)
		{  
			if(data == null){
				$("#sgst").css("display", "none");
				$("#cgst").css("display", "none");
				$("#vat").css("display", "none");
				$("#cess").css("display", "none");
				$("#surcharge").css("display", "none");
				
			}else{
				console.log(data);
				$("#sgst").css("display", "block");	
				$("#cgst").css("display", "block");	
				$("#vat").css("display", "block");	
				$("#cess").css("display", "block");	
				$("#surcharge").css("display", "block");	

				var node = document.createElement("LI"); 
				var textnode1 = document.createTextNode('SGST: '+ data['sgst'] + ',   ');
				var textnode2 = document.createTextNode('CGST: '+ data['cgst'] + ',   ');
				var textnode3 = document.createTextNode('VAT: '+ data['vat'] + ',   ');
				var textnode4 = document.createTextNode('CESS: '+ data['cess'] + ',   ');
				var textnode5 = document.createTextNode('SURCHARGE: '+ data['surcharge'] + ',   ');
				  
				if(data['sgst'] == undefined) {
					$("#sgst").css("display", "none");
					$("#cgst").css("display", "none");
					$("#cess").css("display", "none");
  				}else{
  					
					var item = document.getElementById("sgst").childNodes[0];
	  				item.replaceChild(textnode1, item.childNodes[0]);
	  				var item = document.getElementById("cgst").childNodes[0];
	  				item.replaceChild(textnode2, item.childNodes[0]);
					var item = document.getElementById("cess").childNodes[0];
	  				item.replaceChild(textnode4, item.childNodes[0]);
  				}if(data['vat'] == undefined){
  					$("#vat").css("display", "none");					
					$("#surcharge").css("display", "none");
					
  				}else{
	  				var item = document.getElementById("vat").childNodes[0];
	  				item.replaceChild(textnode3, item.childNodes[0]);
	  				var item = document.getElementById("surcharge").childNodes[0];
	  				item.replaceChild(textnode5, item.childNodes[0]); 
	  			}
 
 
				 
			}
		}
    });
}

//Sale Account Interstate
function salesaccountinterstate(){ 

		var id_mst_charges_sales_interstate = document.getElementById("id_mst_charges_sales_interstate");
	 	var selectedValue = id_mst_charges_sales_interstate.options[id_mst_charges_sales_interstate.selectedIndex].value;

	 var account = 'salesaccountinterstate';
	$.ajax({
        type: "post",
        url: "../ajax/charges_master.php",
        cache: false, 
        data: { selectedValue : selectedValue, account:account } ,
        dataType: 'json',
        success: function(data)
		{  
			if(data == null){
				$("#saleigst").css("display", "none"); 
			}else{
				console.log(data);
				$("#saleigst").css("display", "block");	 	

				var node = document.createElement("LI"); 
				var textnode1 = document.createTextNode('IGST: '+ data['igst'] ); 
				  
				if(data['igst'] == undefined) {
					$("#saleigst").css("display", "none"); 
  				}else{
  					
					var item = document.getElementById("saleigst").childNodes[0];
	  				item.replaceChild(textnode1, item.childNodes[0]); 
  				} 
 
 
				 
			}
		}
    });
}
//Purchase Account Local
function purchaseaccountlocal(){ 

		var id_mst_charges_purchase_local = document.getElementById("id_mst_charges_purchase_local");
	 	var selectedValue = id_mst_charges_purchase_local.options[id_mst_charges_purchase_local.selectedIndex].value;

	 var account = 'purchaseaccountlocal';
	$.ajax({
        type: "post",
        url: "../ajax/charges_master.php",
        cache: false, 
        data: { selectedValue : selectedValue, account:account } ,
        dataType: 'json',
        success: function(data)
		{   
			if(data == null){  
				$("#purchasevat").css("display", "none");
				$("#purchasecess").css("display", "none");
			}else{
				console.log(data); 
				$("#purchasevat").css("display", "block");	
				$("#purchasecess").css("display", "block");	

				var node = document.createElement("LI");  

				var textnode3 = document.createTextNode('VAT: '+ data['vat'] + ',   ');
				var textnode4 = document.createTextNode('CESS: '+ data['cess'] + ',   ');

				 if(data['vat'] == undefined){
  					$("#purchasevat").css("display", "none");
					$("#purchasecess").css("display", "none");
  				}else{
	  				var item = document.getElementById("purchasevat").childNodes[0];
	  				item.replaceChild(textnode3, item.childNodes[0]);
	  				var item = document.getElementById("purchasecess").childNodes[0];
	  				item.replaceChild(textnode4, item.childNodes[0]); 
	  			}
 
 
				 
			}
		}
    });
}
//Purchase Account Interstate
function purchaseaccountinterstate(){ 

		var id_mst_charges_purchase_interstate = document.getElementById("id_mst_charges_purchase_interstate");
	 	var selectedValue = id_mst_charges_purchase_interstate.options[id_mst_charges_purchase_interstate.selectedIndex].value;

	 var account = 'purchaseaccountinterstate';
	$.ajax({
        type: "post",
        url: "../ajax/charges_master.php",
        cache: false, 
        data: { selectedValue : selectedValue, account:account } ,
        dataType: 'json',
        success: function(data)
		{  
			if(data == null){
				$("#purchaseigst").css("display", "none"); 
			}else{
				console.log(data);
				$("#purchaseigst").css("display", "block");	 	

				var node = document.createElement("LI"); 
				var textnode1 = document.createTextNode('IGST: '+ data['igst'] ); 
				  
				if(data['igst'] == undefined) {
					$("#purchaseigst").css("display", "none"); 
  				}else{
  					
					var item = document.getElementById("purchaseigst").childNodes[0];
	  				item.replaceChild(textnode1, item.childNodes[0]); 
  				} 
 
 
				 
			}
		}
    });
}

function selectSaleslocalAccount(ids_mst_outlet,id_mst_charges_sales_local){
		var ids_mst_outlet = $("#ids_mst_outlet").val();

	
		$.ajax({
		type: "POST",
		url: 'ajax/ajaxGetSalesAccountLocal.php',
		data: 'ids_mst_outlet='+ids_mst_outlet+'&id_mst_charges_sales_local='+id_mst_charges_sales_local, 
		success: function (result) {
				
				$("#id_mst_charges_sales_local").html(result);
	 	}
	});
	
	}

	function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah').attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

</script>

<script type="text/javascript">
	function audittrial(clicked_value){
		//alert(clicked_value);
		var id = document.getElementById("mstid").value;
		$('#auditModal').modal('show');
		var form_name ='Manage Items';
		$.ajax({
			url: "../functions/ajaxAuditTrail.php",
			  type: 'POST',
				data: 'form_name='+form_name+'&id='+id,
				dataType: "JSON",
				success: function(data) {
				// alert(data);
			  $('#roombutton').html(data);
			}
	   });
	}
	
	
</script>

