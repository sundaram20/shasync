<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');

//debugData($_REQUEST);

if($_SESSION['unit_user']==2){
	$displayHide = 'style="display:none;"';
	$displayReadOnly = 'readonly="readonly"';

}


$image_path = $UPLOAD_FILES.'/hotel_gallery/';
$image_display_path = $UPLOAD_FILES_PATH ."/hotel_gallery/";
//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){

	$err = 0;
	if(empty($_POST['name'])){
		$err++;
		$err_name = '<font style="color:red;font-weight:normal;" ><br>Please enter hotel name.</font>';
	}else if($db->num_rows2(selectSql(TBL_HOTELS,"WHERE `id` NOT IN('".addslashes(encryptor('decrypt',$_POST[eId]))."') and `id_shop` = '".addslashes($_SESSION['shop'])."' AND `hotel_code` = '".addslashes($_POST['hotel_code'])."'",''))){
		$err++;
		$err_name = '<font style="color:red;font-weight:normal;" ><br>Product Code all-ready exists in our database.</font>';
	}
	if(empty($_POST['hotel_category'])){
		$err++;
		$err_hotel_category = '<font style="color:red;font-weight:normal;" ><br>Please select hotel category.</font>';
	}
	if(empty($_POST['address'])){
		$err++;
		$err_address = '<font style="color:red;font-weight:normal;" ><br>Please enter address.</font>';
	}
	if(empty($_POST['hotel_code'])){
		$err++;
		$err_hotel_code = '<font style="color:red;font-weight:normal;" ><br>Please enter hotel code.</font>';
	}
	
	if(empty($_POST['address'])){
		$err++;
		$err_address = '<font style="color:red;font-weight:normal;" ><br>Please enter address.</font>';
	}
	if(empty($_POST['city'])){
		$err++;
		$err_city = '<font style="color:red;font-weight:normal;" ><br>Please enter city.</font>';
	}
	if(empty($_POST['state'])){
		$err++;
		$err_state = '<font style="color:red;font-weight:normal;" ><br>Please enter state.</font>';
	}
	if(empty($_POST['pincode'])){
		$err++;
		$err_pincode = '<font style="color:red;font-weight:normal;" ><br>Please enter pincode.</font>';
	}else if(!preg_match("/^[0-9]{6}+$/", $_POST['pincode'])){
		$err++;
		$err_pincode = '<font style="color:#FF0000;"><br>Please enter valid pincode.</font>';
	}
	if(empty($_POST['phone1'])){
		$err++;
		$err_phone1 = '<font style="color:red;font-weight:normal;" ><br>Please enter phone number.</font>';
	}

	if(empty($_POST['email'])){
		$err++;
		$err_email = '<font style="color:red;font-weight:normal;" ><br>Please enter email id.</font>';
	}/*elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
		$err++;
		$err_email = '<font style="color:red;font-weight:normal;" ><br>Please enter valid email id.</font>';
	}*/
/*	if(empty($_POST['page_url'])){
		$err++;
		$err_page_url ='<font style="color:red;font-weight:normal;" ><br>Please enter Page Url.</font>';
	}else if($db->num_rows2(selectSql(TBL_HOTELS,"WHERE `id` NOT IN('".addslashes(encryptor('decrypt',$_POST[eId]))."') and `page_url` = '".addslashes(friendlyUrl($_POST['page_url']))."'",''))){
		$err++;
		$err_page_url = '<font style="color:red;font-weight:normal;" ><br>This page url all-ready exists in our database.</font>';
	}*/
	
		if(($_POST['old_image'] == '') && ($_FILES['image']['name'] == '')){
	   //no error
		}else{
		if($_FILES['image']['name'] !=''){
		if($_FILES['image']['size']>0 && $_FILES['image']['size']<1048576){
			if(($_FILES['image']['type'] == 'image/jpeg') || ($_FILES['image']['type'] == 'image/png') || ($_FILES['image']['type'] == 'image/bmp') || ($_FILES['image']['type'] == 'image/gif')){
			$unique = rand(00000,99999);
        	$filename= basename($_FILES['image']['name']);
        	$fname = getNameExt($filename);
        	$insert_image = $fname[0].$unique.".".$fname[1];			
				if(@move_uploaded_file($_FILES['image']['tmp_name'],$image_path.$insert_image)){	
					resize($insert_image,$image_path, $image_path, $width=350,$height=220,$thumb='medium-');
					resize($insert_image,$image_path, $image_path, $width=150,$height=100,$thumb='small-');	
					//////end resize////////
					if(@file_exists($image_path.$_POST['old_image']) && ($_POST['old_image'] != $_FILES['image']['name'])){
						@unlink($image_path.$_POST['old_image']);
						@unlink($image_path.'medium-'.$_POST['old_image']);
						@unlink($image_path.'small-'.$_POST['old_image']);
					}	
				}else{
					$err++;
					$err_image = '<font style="color:red;font-weight:normal;" ><br>Unable to upload file '.$_FILES['image']['name'].'.</font>';
				}
			}else{
				$err++;
				$err_image = '<font style="color:red;font-weight:normal;" ><br>Invalid file type '.$_FILES['image']['type'].'. Please use only JEPG,GIF,PNG,BMP only</font>';
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
	
	
	
	
	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add
			checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'add');
			$addSql = "   	INSERT INTO `".TBL_HOTELS."` SET 
							`id_shop_group` = '1',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`hotel_category` = '".addslashes($_POST['hotel_category'])."',
							`hotel_code` = '".addslashes($_POST['hotel_code'])."',
							`serial_number_applicable` = '".addslashes($_POST['serial_applicable'])."',
							`name` = '".addslashes($_POST['name'])."',
							`address` = '".addslashes($_POST['address'])."',
							`city` = '".addslashes($_POST['city'])."',
							`zonal` = '".addslashes($_POST['zonal'])."',
							`state` = '".addslashes($_POST['state'])."',
							`pincode` = '".addslashes($_POST['pincode'])."',
							`phone1` = '".addslashes($_POST['phone1'])."',
							`phone2` = '".addslashes($_POST['phone2'])."',
							`gm_email` = '".addslashes($_POST['gm_email'])."',
							`fax` = '".addslashes($_POST['fax'])."',
							`email` = '".addslashes($_POST['email'])."',
							`bank_detail` = '".addslashes($_POST['bank'])."',
							`manager` = '".addslashes($_POST['manager'])."',
							`product_cost` ='".addslashes($_POST['product_cost'])."',";
							
					/*$addSql .="	`ids_general_services` = '".addslashes(implode(',',$_POST['general_services']))."',
							`ids_kids_related_services` = '".addslashes(implode(',',$_POST['kids_services']))."',
							`ids_outdoor_services` = '".addslashes(implode(',',$_POST['outdoor_services']))."',
							`ids_conference_services` = '".addslashes(implode(',',$_POST['conference_services']))."',
							`ids_dining_services` = '".addslashes(implode(',',$_POST['dining_services']))."', ";*/

					$addSql .= "`general_services` = '".$_POST['general_services']."',
							`kids_services` = '".$_POST['kids_services']."',
							`outdoor_services` = '".$_POST['outdoor_services']."',
							`conference_services` = '".$_POST['conference_services']."',
							`dining_services` = '".$_POST['dining_services']."',
							";		
							
					$addSql .="`hotel_services` = '".$_POST['hotel_services']."',
							`historical_background` = '".addslashes($_POST['historical_background'])."',
							`hotel_tagline` = '".addslashes($_POST['hotel_tagline'])."',	
							`special_notes` = '".addslashes($_POST['special_notes'])."',						
							`brief_description` = '".addslashes($_POST['brief_description'])."',
							`excursions` = '".addslashes($_POST['excursions'])."',
							`latitude` = '".addslashes($_POST['latitude'])."',
							`longitude` = '".addslashes($_POST['longitude'])."',
							`gmap_url` = '".addslashes($_POST['gmap_url'])."',
							`review_url` = '".addslashes($_POST['review_url'])."',
							`meta_title` = '".addslashes($_POST['meta_title'])."',
							`meta_keyword` = '".addslashes($_POST['meta_keyword'])."',
							`meta_description` = '".addslashes($_POST['meta_description'])."',
							`page_url` = '".addslashes(friendlyUrl($_POST['page_url']))."',
							`display_order` = '".addslashes($_POST['display_order'])."'";
							
			if($_FILES['image']['name'] != ''){				
				$addSql .= "	,`image` = '".addslashes($insert_image)."'";
			}else{
				$addSql .= "	,`image` = '".addslashes($_POST['old_image'])."'";
			}
			$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							,`excel_display_weekday` = '".addslashes($_POST['excel_display_weekday'])."'
							,`status` = '".addslashes($_POST['status'])."'";
			if(executeSql($addSql)){
				//unset($_POST);
				$lastInsertId= $db->insert_id();
				$_SESSION['successMsg'] = 'New Product details has been added sucessfully.';
				header("location:editHotels.php?eId=".encryptor('encrypt',$lastInsertId)."&action=edit&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Product details has not been saved. Please make corrections below.';
			}
		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update
		
		
			checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'update');
			 $editSql = "   	UPDATE `".TBL_HOTELS."` SET 
							`id_shop_group` = '1',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`hotel_category` = '".addslashes($_POST['hotel_category'])."',
							`hotel_code` = '".addslashes($_POST['hotel_code'])."',
							`serial_number_applicable` = '".addslashes($_POST['serial_applicable'])."',
							`name` = '".addslashes($_POST['name'])."',
							`address` = '".addslashes($_POST['address'])."',
							`city` = '".addslashes($_POST['city'])."',
							`zonal` = '".addslashes($_POST['zonal'])."',
							`state` = '".addslashes($_POST['state'])."',
							`pincode` = '".addslashes($_POST['pincode'])."',
							`phone1` = '".addslashes($_POST['phone1'])."',
							`phone2` = '".addslashes($_POST['phone2'])."',
							`gm_email` = '".addslashes($_POST['gm_email'])."',
							`fax` = '".addslashes($_POST['fax'])."',
							`email` = '".addslashes($_POST['email'])."',
							`bank_detail` = '".addslashes($_POST['bank'])."',
							`manager` = '".addslashes($_POST['manager'])."',
							`product_cost` ='".addslashes($_POST['product_cost'])."',";
				/*$editSql .= "			

							`ids_general_services` = '".addslashes(implode(',',$_POST['general_services']))."',
							`ids_kids_related_services` = '".addslashes(implode(',',$_POST['kids_services']))."',
							`ids_outdoor_services` = '".addslashes(implode(',',$_POST['outdoor_services']))."',
							`ids_conference_services` = '".addslashes(implode(',',$_POST['conference_services']))."',
							`ids_dining_services` = '".addslashes(implode(',',$_POST['dining_services']))."',
							";*/
				$editSql .= "`general_services` = '".$_POST['general_services']."',
							`kids_services` = '".$_POST['kids_services']."',
							`outdoor_services` = '".$_POST['outdoor_services']."',
							`conference_services` = '".$_POST['conference_services']."',
							`dining_services` = '".$_POST['dining_services']."',
							";			

				$editSql .= " `hotel_services` = '".addslashes($_POST['hotel_services'])."',
							`historical_background` = '".addslashes($_POST['historical_background'])."',
							`hotel_tagline` = '".addslashes($_POST['hotel_tagline'])."',	
							`special_notes` = '".addslashes($_POST['special_notes'])."',							
							`brief_description` = '".addslashes($_POST['brief_description'])."',
							`excursions` = '".addslashes($_POST['excursions'])."',
							`latitude` = '".addslashes($_POST['latitude'])."',
							`longitude` = '".addslashes($_POST['longitude'])."',
							`gmap_url` = '".addslashes($_POST['gmap_url'])."',
							`review_url` = '".addslashes($_POST['review_url'])."',
							`meta_title` = '".addslashes($_POST['meta_title'])."',
							`meta_keyword` = '".addslashes($_POST['meta_keyword'])."',
							`meta_description` = '".addslashes($_POST['meta_description'])."',
							`page_url` = '".addslashes(friendlyUrl($_POST['page_url']))."',
							`display_order` = '".addslashes($_POST['display_order'])."' ";
						
			if($_FILES['image']['name'] != ''){				
				$editSql .= "	,`image` = '".addslashes($insert_image)."'";
			}else{
				$editSql .= "	,`image` = '".addslashes($_POST['old_image'])."'";
			}
			$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`status` = '".addslashes($_POST['status'])."'
							,`excel_display_weekday` = '".addslashes($_POST['excel_display_weekday'])."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							WHERE `id` = '".addslashes(encryptor('decrypt',$_POST[eId]))."'";
								
			if(executeSql($editSql)){
				$_SESSION['successMsg'] = selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".addslashes(encryptor('decrypt',$_POST[eId]))."'").' details has been updated sucessfully.';
				header("location:editHotels.php?eId=".$_GET['eId']."&action=edit&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".addslashes(encryptor('decrypt',$_POST[eId]))."'").' details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'Product details has not been saved. Please make corrections.';
	}
}
// ----------cate---------
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
	$sql = "  SELECT * FROM `".TBL_HOTELS."`
								WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
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
    <section class="content-header">
      <h1>
       Product Manager
        <small>Manage Products</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Products</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
	
	
			
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
         
           
			 <div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
			   <li class="active" ><a href="#tab_1" data-toggle="tab">Overview</a></li>   
              <li <?php echo $displayHide;?> ><a href="editHotelGallery.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>">Photo Gallery</a></li> 
			  <li <?php echo $displayHide;?> ><a href="manageAssignHotelRoom.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>">Room Types</a></li>   
			 
			  <li <?php echo $displayHide;?> ><a href="inventory.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" >Inventory</a></li>    
			  <!--<li  ><a href="calendar.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" >Calendar</a></li>-->    
            </ul>
			<div class="box-header with-border">
              <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> Product : <a><?php echo selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'"); ?></a></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->  			        
			 <form name="form1" action=""  method="post" enctype="multipart/form-data" data-parsley-validate autocomplete="off" >
                <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" />
					<div class="form-group has-error" align="center">
						<?php if($_SESSION['errorMsg']){?>
						 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
						<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
					 	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
						<?php unset($_SESSION['successMsg']);}?>
					 </div>
              <div class="box-body">			  
			   <div class="form-group" <?php echo $displayHide;?> >
                  <label for="name">Product Category<font color="#FF0000">*</font></label>
                 <?php $categoryDropDown = '<select class="form-control select2" name="hotel_category" data-parsley-required>
						<option value="">Select Product Category</option>';
						  $resCat = selectSql(TBL_HOTEL_TYPE,"where id_shop='".$_SESSION['shop']."' ",' ORDER BY `name`');
						  if($db->num_rows2($resCat)){
						  	while($resultCat = $db->fetch_object2($resCat)){
								if($_REQUEST['hotel_category'] == $resultCat->id){
									$selected = 'selected="selected"';
								}elseif($row->hotel_category == $resultCat->id){
									$selected = 'selected="selected"';
								}else{
									$selected = '';
								}
								$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
							}
						}
											 	echo $categoryDropDown .= '</select>';
											  ?>
				<?php echo $err_hotel_category;?>
                </div>
                
				<div class="form-group">
                  <label for="name">Product Name<font color="#FF0000">*</font></label>
                  <input <?php echo $displayReadOnly;?>  type="text" class="form-control" placeholder="Enter hotel name" id="name" name="name" value="<?php if($_POST) echo $_POST['name'];else echo stripslashes($row->name);?>" data-parsley-required>
				<?php echo $err_name;?>
                </div>
                
                
                <div class="form-group" <?php echo $displayHide;?>>
                  <label for="hotel_code">Product Code<font color="#FF0000">*</font></label>
                  <input type="text" class="form-control" placeholder="Enter hotel code" id="hotel_code" name="hotel_code" value="<?php if($_POST) echo $_POST['hotel_code'];else echo stripslashes($row->hotel_code);?>"  data-parsley-required>
				<?php echo $err_hotel_code;?>
                </div>
				
<div class="form-group" >
    <label for="serial_number" >Serial Number Mandatory?<font color="#FF0000">*</font></label>
    <div>
    <label for="yes" >Yes</label>
    <input type="radio" class="" name="serial_applicable" value="1" 
        <?php if($_POST) { if($_POST['serial_applicable']=='1') echo 'checked'; } else { if($row->serial_number_applicable=='1') echo 'checked'; } ?>>
    
    <label for="No" >No</label>
    <input type="radio" class="" name="serial_applicable" value="0" 
        <?php if($_POST) { if($_POST['serial_applicable']=='0') echo 'checked'; } else { if($row->serial_number_applicable!='1') echo 'checked'; } ?>>
    </div>
</div>
				
				<div class="form-group" <?php echo $displayHide;?>>
                  <label for="address">Address<font color="#FF0000">*</font></label>
                  <input type="text" class="form-control" placeholder="Enter address" id="address" name="address" value="<?php if($_POST) echo $_POST['address'];else echo stripslashes($row->address);?>" data-parsley-required>
				<?php echo $err_address;?>
                </div>
				
				
				<div class="form-group" <?php echo $displayHide;?>>
                  <label for="city">City<font color="#FF0000">*</font></label>
                  <input type="text" class="form-control" placeholder="Enter city" id="city" name="city" value="<?php if($_POST) echo $_POST['city'];else echo stripslashes($row->city);?>" data-parsley-required>
				<?php echo $err_city;?>
                </div>
			
			
		<div class="form-group" <?php echo $displayHide;?>>
                  <label for="zonal">Zonal<font color="#FF0000">*</font></label>
                  <?php $stateDropDown = '<select class="form-control select2" name="zonal">
					<option value="">Select zonal</option>';
											  $resCat = selectSql(TBL_ZONAL," ",' ORDER BY `name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($row->zonal == $resultCat->id){
														$selected = 'selected="selected"';
													}
													else{
														$selected = '';
													}
													$stateDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $stateDropDown .= '</select>';
											  ?>
				
				
                </div>	
			
			
				
				<div class="form-group" <?php echo $displayHide;?>>
                  <label for="state">State<font color="#FF0000">*</font></label>
                  <?php $stateDropDown = '<select class="form-control select2" name="state" data-parsley-required>
											  								<option value="">Select state</option>';
											  $resCat = selectSql(TBL_STATE," where id_country='110'",' ORDER BY `name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['state'] == $resultCat->id_state){
														$selected = 'selected="selected"';
													}elseif($row->state == $resultCat->id_state){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$stateDropDown .= '<option '.$selected.' value="'.$resultCat->id_state.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $stateDropDown .= '</select>';
											  ?>
				<?php echo $err_hotel_category;?>
				<?php echo $err_state;?>
                </div>
				
				<div class="form-group" <?php echo $displayHide;?>>
                  <label for="pincode">Pincode<font color="#FF0000">*</font></label>
                  <input type="text" class="form-control" placeholder="Enter pincode" id="pincode" name="pincode" value="<?php if($_POST) echo $_POST['pincode'];else echo stripslashes($row->pincode);?>" data-parsley-required >
				<?php echo $err_pincode;?>
                </div>
				
				<div class="form-group">
                  <label for="phone1">Phone Number<font color="#FF0000">*</font></label>
                  <input type="text" class="form-control" placeholder="Enter phone number" id="phone1" name="phone1" value="<?php if($_POST) echo $_POST['phone1'];else echo stripslashes($row->phone1);?>" data-parsley-required>
				<?php echo $err_phone1;?>
                </div>
				
				
				
				<div class="form-group" style="display:none;">
                  <label for="fax">Fax</label>
                  <input type="text" class="form-control" placeholder="Enter fax number" id="fax" name="fax" value="<?php if($_POST) echo $_POST['fax'];else echo stripslashes($row->fax);?>">
				<?php echo $err_fax;?>
                </div>
				
				<div class="form-group">
                  <label for="email">Email Id<font color="#FF0000">* Values must be comma(,) separated</font></label>
                  <input type="text" class="form-control" placeholder="Enter email id" id="email" name="email" value="<?php if($_POST) echo $_POST['email'];else echo stripslashes($row->email);?>">
				<?php echo $err_email;?>
                </div>

                <div class="form-group">
                  <label for="bank">Bank Details</label>
                  <!--<input type="text" class="form-control" placeholder="Enter Bank Details" id="bank" name="bank" value="<?php if($_POST) echo $_POST['bank'];else echo stripslashes($row->bank_detail);?>">-->
				<textarea class="ckeditor" id="bank" name="bank" rows="10" cols="80"><?php if($_POST) echo $_POST['bank'];else echo stripslashes($row->bank_detail);?></textarea>
                 
                 <?php echo $err_bank;?>
                </div>
				
				<div class="row">	
					
					<div class="col-sm-3">
						<div class="form-group">				
						 <label for="image">Featured Image &nbsp;&nbsp;</label>
							<div class="btn btn-default btn-file">
							  <i class="fa fa-upload"></i> Upload
							 <input type="file" class="form-control" placeholder="Select Room Image" id="image" name="image" value="">	
							 <input type="hidden" name="old_image" value="<?php echo stripslashes($row->image);?>"/>					 
						
							</div>
							<p class="help-block">Must be of width:600px and height:300px.<br />Max. Size: 1MB</p>							
							<a href="javascript:void(0);" id="delete" class="btn btn-danger" onClick="removeImage('ajax/ajaxremoveImage.php','<?php echo TBL_HOTELS; ?>','<?php echo $row->id; ?>','image','imageCallback','hotel_room','<?php echo $row->image; ?>');" >Remove</a>
					</div>	
					<?php echo $err_image;?>
					</div>								
					<div class="col-sm-9">													
						<ul class="mailbox-attachments clearfix"> 
									<li id="imageCallback">
									<?php if(@file_exists($image_path.$row->image) && $row->image!=''){ ?>
									<span class="mailbox-attachment-icon has-img">							 
										<img src="<?php echo $image_display_path.$row->image; ?>" alt="Room Image">							  
									  </span>			
									  <div class="mailbox-attachment-info">
										<a href="javascript:void(0);" class="mailbox-attachment-name"><i class="fa fa-camera"></i> <?php echo $row->image; ?></a>
											<span class="mailbox-attachment-size">
											  <?php echo round(filesize($image_path.$row->image)/ 1024 ,2).' KB'; ?>
											  <a href="<?php echo $image_display_path.$row->image; ?>" download class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
											</span>
									  </div>
									<?php }else{ ?>							
									<span class="mailbox-attachment-icon has-img">							 
										<img src="images/no-hotel-image.jpg" alt="Room Image">							  
									  </span>			
									  <div class="mailbox-attachment-info">
										<a href="javascript:void(0);" class="mailbox-attachment-name"><i class="fa fa-camera"></i> no-hotel-image.jpg</a>
											<span class="mailbox-attachment-size">
											   <?php echo round(filesize('images/no-hotel-image.jpg')/ 1024 ,2).' KB'; ?>
											  <a href="images/no-hotel-image.jpg" download class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
											</span>
									  </div>							
									<?php }?> 
									  
									</li>                
								  </ul>			  
					 </div>
				</div>
                 <div class="form-group" <?php echo $displayHide;?>>
                  <label for="special_notes">Product Cost</label>
                  <input type="text" class="form-control" placeholder="Enter Product Cost" id="product_cost" name="product_cost" value="<?php if($_POST) echo $_POST['product_cost']; else echo stripslashes($row->product_cost);?>">
				<?php // echo $err_name;?>
                </div>
				 <div class="form-group" <?php echo $displayHide;?>>
                  <label for="special_notes">Special Notes</label>
                  <input type="text" class="form-control" placeholder="Enter special notes" id="special_notes" name="special_notes" value="<?php if($_POST) echo $_POST['special_notes']; else echo stripslashes($row->special_notes);?>">
				<?php // echo $err_name;?>
                </div>
				
				<div class="form-group">
                  <label for="manager">General Manager Name</label>
                  <input type="text" class="form-control" placeholder="Enter manager name" id="manager" name="manager" value="<?php if($_POST) echo $_POST['manager'];else echo stripslashes($row->manager);?>">
				<?php echo $err_manager;?>
                </div>

                <div class="form-group">
                  <label for="phone2">General Manager Contact Number</label>
                  <input type="text" class="form-control" placeholder="Enter General Manager Contact Number" id="phone2" name="phone2" value="<?php if($_POST) echo $_POST['phone2'];else echo stripslashes($row->phone2);?>">
				<?php echo $err_phone2;?>
                </div>

                 <div class="form-group">
                  <label for="gm_email">General Manager Email</label>
                  <input type="text" class="form-control" placeholder="Enter General Manager Email ID" id="gm_email" name="gm_email" value="<?php if($_POST) echo $_POST['gm_email'];else echo stripslashes($row->gm_email);?>">
				
                </div>
				
				
				<div class="form-group">
                  <label for="hotel_tagline">Product Tagline</label>
                  <input type="text" class="form-control" placeholder="Enter hotel tagline" id="hotel_tagline" name="hotel_tagline" value="<?php if($_POST) echo $_POST['hotel_tagline'];else echo stripslashes($row->hotel_tagline);?>">
				<?php echo $err_hotel_tagline;?>
                </div>
				
				
				
				
				
				 <div class="form-group">
                  <label for="brief_description">Brief Description</label>
				   <textarea class="ckeditor" id="description" name="brief_description" rows="10" cols="80"><?php if($_POST) echo $_POST['brief_description'];else echo stripslashes($row->brief_description);?></textarea>
                  
				<?php echo $err_brief_description;?>
                </div>

                <div class="form-group">
                  <label for="excursions">Excursions</label>
				   <textarea class="ckeditor" id="excursion" name="excursions" rows="10" cols="80"><?php if($_POST) echo $_POST['excursions'];else echo stripslashes($row->excursions);?></textarea>
                  
				<?php echo $err_excursions;?>
                </div>
				
				
				<div class="form-group">
                  <label for="historical_background">Historical Background</label>
				   <textarea class="ckeditor" id="historical_background" name="historical_background" rows="10" cols="80"><?php if($_POST) echo $_POST['historical_background'];else echo stripslashes($row->historical_background);?></textarea>
                  
				<?php echo $err_historical_background;?>
                </div>

                <div class="form-group">
                  <label for="general_services">General Services</label>
				   <textarea class="ckeditor" id="general_services" name="general_services" rows="10" cols="80"><?php if($_POST) echo $_POST['general_services'];else echo stripslashes($row->general_services);?></textarea>
                </div>

                <div class="form-group">
                  <label for="outdoor_services">Outdoor Services</label>
				   <textarea class="ckeditor" id="outdoor_services" name="outdoor_services" rows="10" cols="80"><?php if($_POST) echo $_POST['outdoor_services'];else echo stripslashes($row->outdoor_services);?></textarea>
                </div>

                <div class="form-group">
                  <label for="conference_services">Conference Services</label>
				   <textarea class="ckeditor" id="conference_services" name="conference_services" rows="10" cols="80"><?php if($_POST) echo $_POST['conference_services'];else echo stripslashes($row->conference_services);?></textarea>
                </div>

                <div class="form-group">
                  <label for="kids_services">Kids Related Services</label>
				   <textarea class="ckeditor" id="kids_services" name="kids_services" rows="10" cols="80"><?php if($_POST) echo $_POST['kids_services'];else echo stripslashes($row->kids_services);?></textarea>
                </div>

                <div class="form-group">
                  <label for="dining_services">Dining Services</label>
				   <textarea class="ckeditor" id="dining_services" name="dining_services" rows="10" cols="80"><?php if($_POST) echo $_POST['dining_services'];else echo stripslashes($row->dining_services);?></textarea>
                </div>

				<?php /*	
                <!--General Services-->
                <div class="form-group">
                  <label for="userlevelId">General Services</label>
				  <select class="form-control select2" name="general_services[]" multiple="multiple">				  
                  <?php 
					$sqlUserActions = selectSql(TBL_GENERAL_SERVICES," where id_shop='".$_SESSION['shop']."' ",'');
					$iCounterActions = 0;
					while($resUserActions = $db->fetch_object2($sqlUserActions)){
						$chkSql = "SELECT * FROM `".TBL_HOTELS."` WHERE FIND_IN_SET('".$resUserActions->id."',ids_general_services ) and id='".addslashes(encryptor('decrypt',$_REQUEST['eId']))."' ";
						
						if($db->num_rows2(executeSql($chkSql)) > 0){
							$selected = 'selected="selected"';
						}else if($_POST[$selected]){
						$selected = 'selected="selected"';
						}													
						else{
							$selected = '';
						}
						echo '<option '.$selected.' value="'.$resUserActions->id.'">'.$resUserActions->name.'</option>';
						
						$iCounterActions++;
					}
					?>
					</select>
                    <?php echo $err_hotel_services;?>
                </div>

                <!--Outdoor Services-->
                <div class="form-group">
                  <label for="userlevelId">Outdoor Actitvites</label>
				  <select class="form-control select2" name="outdoor_services[]" multiple="multiple">				  
                  <?php 
					$sqlUserActions = selectSql(TBL_OUTDOOR_ACTIVITIES," where id_shop='".$_SESSION['shop']."' ",'');
					$iCounterActions = 0;
					while($resUserActions = $db->fetch_object2($sqlUserActions)){
						$chkSql = "SELECT * FROM `".TBL_HOTELS."` WHERE FIND_IN_SET('".$resUserActions->id."',ids_outdoor_services ) and id='".addslashes(encryptor('decrypt',$_REQUEST['eId']))."' ";
						if($db->num_rows2(executeSql($chkSql)) > 0){
							$selected = 'selected="selected"';
						}else if($_POST[$selected]){
						$selected = 'selected="selected"';
						}													
						else{
							$selected = '';
						}
						echo '<option '.$selected.' value="'.$resUserActions->id.'">'.$resUserActions->name.'</option>';
						
						$iCounterActions++;
					}
					?>
					</select>
                    <?php echo $err_hotel_services;?>
                </div>
				
				<!--Dining Services-->
				<div class="form-group">
                  <label for="userlevelId">Dining Services</label>
				  <select class="form-control select2" name="dining_services[]" multiple="multiple">				  
                  <?php 
					$sqlUserActions = selectSql(TBL_DINING_SERVICES," where id_shop='".$_SESSION['shop']."' ",'');
					$iCounterActions = 0;
					while($resUserActions = $db->fetch_object2($sqlUserActions)){
						$chkSql = "SELECT * FROM `".TBL_HOTELS."` WHERE FIND_IN_SET('".$resUserActions->id."',ids_dining_services ) and id='".addslashes(encryptor('decrypt',$_REQUEST['eId']))."' ";
						if($db->num_rows2(executeSql($chkSql)) > 0){
							$selected = 'selected="selected"';
						}else if($_POST[$selected]){
						$selected = 'selected="selected"';
						}													
						else{
							$selected = '';
						}
						echo '<option '.$selected.' value="'.$resUserActions->id.'">'.$resUserActions->name.'</option>';
						
						$iCounterActions++;
					}
					?>
					</select>
                    <?php echo $err_hotel_services;?>
                </div>
				
                

				<!--Conferences & Meetings-->
                <!--<div class="form-group">
                  <label for="userlevelId">Conferences & Meetings Services</label>
				  <select class="form-control select2" name="conference_services[]" multiple="multiple">				  
                  <?php 
					$sqlUserActions = selectSql(TBL_CONFERENCE_SERVICES," where id_shop='".$_SESSION['shop']."' ",'');
					$iCounterActions = 0;
					while($resUserActions = $db->fetch_object2($sqlUserActions)){
						$chkSql = "SELECT * FROM `".TBL_HOTELS."` WHERE FIND_IN_SET('".$resUserActions->id."',ids_conference_services ) and id='".addslashes(encryptor('decrypt',$_REQUEST['eId']))."' ";
						if($db->num_rows2(executeSql($chkSql)) > 0){
							$selected = 'selected="selected"';
						}else if($_POST[$selected]){
						$selected = 'selected="selected"';
						}													
						else{
							$selected = '';
						}
						echo '<option '.$selected.' value="'.$resUserActions->id.'">'.$resUserActions->name.'</option>';
						
						$iCounterActions++;
					}
					?>
					</select>
                    <?php echo $err_hotel_services;?>
                </div>-->

                <!--Kids Services-->
                <div class="form-group">
                  <label for="userlevelId">Kids Related Facilities</label>
				  <select class="form-control select2" name="kids_services[]" multiple="multiple">				  
                  <?php 
					$sqlUserActions = selectSql(TBL_KIDS_SERVICES," where id_shop='".$_SESSION['shop']."' ",'');
					$iCounterActions = 0;
					while($resUserActions = $db->fetch_object2($sqlUserActions)){
						$chkSql = "SELECT * FROM `".TBL_HOTELS."` WHERE FIND_IN_SET('".$resUserActions->id."',ids_kids_related_services ) and id='".addslashes(encryptor('decrypt',$_REQUEST['eId']))."' ";
						if($db->num_rows2(executeSql($chkSql)) > 0){
							$selected = 'selected="selected"';
						}else if($_POST[$selected]){
						$selected = 'selected="selected"';
						}													
						else{
							$selected = '';
						}
						echo '<option '.$selected.' value="'.$resUserActions->id.'">'.$resUserActions->name.'</option>';
						
						$iCounterActions++;
					}
					?>
					</select>
                    <?php echo $err_hotel_services;?>
                </div>
				*/?>
				
				<!--<div class="form-group">
                  <label for="latitude">Latitude</label>
                  <input type="text" class="form-control" placeholder="Enter latitude number" id="latitude" name="latitude" value="<?php //if($_POST) //echo $_POST['latitude'];else echo stripslashes($row->latitude);?>">
				<?php //echo $err_latitude;?>
                </div>-->
				
				<!--<div class="form-group">
                  <label for="longitude">Longitude</label>
                  <input type="text" class="form-control" placeholder="Enter longitude number" id="longitude" name="longitude" value="<?php //if($_POST) echo $_POST['longitude'];else echo stripslashes($row->longitude);?>" >
				<?php //echo $err_longitude;?>
                </div>-->
				<div class="form-group" <?php echo $displayHide;?>>
                  <label for="gmap_url">Google Map URL</label>
                  <input type="text" class="form-control" placeholder="Enter Google Map url" id="gmap_url" name="gmap_url" value="<?php if($_POST) echo $_POST['gmap_url'];else echo stripslashes($row->gmap_url);?>">
				<?php echo $err_gmap_url;?>
                </div>

				<div style="display:none;" class="form-group" <?php echo $displayHide;?>>
                  <label for="review_url">Review URL</label>
                  <input type="text" class="form-control" placeholder="Enter review url" id="review_url" name="review_url" value="<?php if($_POST) echo $_POST['review_url'];else echo stripslashes($row->review_url);?>">
				<?php echo $err_review_url;?>
                </div>
				
				<div style="display:none;" class="form-group" <?php echo $displayHide;?>>
                  <label for="page_url">Page URL Slug</label>
                  <input type="text" class="form-control" placeholder="Enter page url" id="page_url" name="page_url" value="<?php if($_POST) echo $_POST['page_url'];else echo stripslashes($row->page_url);?>" >
				<?php echo $err_page_url;?>
                </div>
				
				<div style="display:none;" class="form-group" <?php echo $displayHide;?>>
                  <label for="meta_title">Meta Title</label>
                  <input type="text" class="form-control" placeholder="Enter meta title" id="meta_title" name="meta_title" value="<?php if($_POST) echo $_POST['meta_title'];else echo stripslashes($row->meta_title);?>">
				<?php echo $err_meta_title;?>
                </div>
				
				
				<div style="display:none;" class="form-group" <?php echo $displayHide;?>>
                  <label for="meta_keyword">Meta Keyword</label>
                  <textarea class="form-control" id="meta_keyword" name="meta_keyword" ><?php if($_POST) echo $_POST['meta_keyword'];else echo stripslashes($row->meta_keyword);?></textarea>
				<?php echo $err_meta_keyword;?>
                </div>
				
				
				<div style="display:none;" class="form-group" <?php echo $displayHide;?>>
                  <label for="meta_description">Meta Description</label>
                  <textarea class="form-control" id="meta_description" name="meta_description" ><?php if($_POST) echo $_POST['meta_description'];else echo stripslashes($row->meta_description);?></textarea>
				<?php echo $err_meta_description;?>
                </div>
				
				<div class="form-group" <?php echo $displayHide;?>>
                  <label for="display_order">Display Order</label>
                  <input type="number" class="form-control" placeholder="Enter display order" id="display_order" name="display_order" value="<?php if($_POST) echo $_POST['display_order'];else echo stripslashes($row->display_order);?>">
				<?php echo $err_display_order;?>
                </div>
				
                <div class="form-group" <?php echo $displayHide;?>>
                  <label for="excel_display_weekday">Excel Weekday List</label>
                 <input class="flat-red" type="radio"  <?php if($_POST['excel_display_weekday'] == '1'){echo "checked";}else{if($row->excel_display_weekday == 1)echo "checked";}?> value="1" name="excel_display_weekday"/> Weekday & Weekend ((FRI-SUN))
                <input class="flat-red" type="radio"  <?php if($_POST['excel_display_weekday'] == '2'){echo "checked";}else{if($row->excel_display_weekday == 2)echo "checked";}?> value="2" name="excel_display_weekday"/> Weekday & Weekend ((FRI-SAT))
				 <input class="flat-red" type="radio" <?php if($_POST['excel_display_weekday'] == '0'){echo "checked";}else{if($row->excel_display_weekday == 0)echo "checked";}?> value="0" name="excel_display_weekday"/> Single & Double
				 <?php echo $err_excel_display_weekday;?>
                </div>
                				
				<div class="form-group" <?php echo $displayHide;?>>
                  <label for="status">Status</label>
                 <input class="flat-red" type="radio"  <?php if($_POST['status'] == '1'){echo "checked";}else{if($row->status == 1)echo "checked";}?> value="1" name="status"/> Active
				 <input class="flat-red" type="radio" <?php if($_POST['status'] == '0'){echo "checked";}else{if($row->status == 0)echo "checked";}?> value="0" name="status"/> Inactive
				 <?php echo $err_status;?>
                </div>
				
				<?php if($row->date_created){?>
				  
				<div class="form-group">
                  <label for="date_created">Date Created</label>
                  <input type="text" disabled="disabled" class="form-control" id="date_created"  value="<?php echo stripslashes(dateformat($row->date_created));?>">				
                </div> 
				
				<div class="form-group">
                  <label for="last_modified">Last Updated</label>
                  <input type="text" disabled="disabled" class="form-control" id="last_modified" value="<?php echo stripslashes(dateformat($row->last_modified));?>">				
                </div> 
				
				<div class="form-group">
                  <label for="last_modified_by">Last Updated By</label>
				   <?php $sqlUserDetail = $db->fetch_obj2(selectSql(TBL_USERS,"WHERE `id` = '".$row->last_modified_by."'",''));?>
                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail->username);?>">				
                </div>  
				  
				  <?php } ?>            
              </div>
              <!-- /.box-body -->	
			 <div class="box-footer">                                       
				<input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
				&nbsp;&nbsp;&nbsp;&nbsp;
			   <input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("manageHotels.php"); '>
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
<?php include_once("includes/footer.php")?>


