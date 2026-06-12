<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_SHOP,'view');
$image_path = $UPLOAD_FILES.'/shop/';
$image_display_path = $UPLOAD_FILES_PATH ."/shop/";
//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){
	$err = 0;
	
	if(empty($_POST['name'])){
		$err++;
		$err_name = '<font style="color:red;font-weight:normal;" >Please enter user title.</font>';
	}	
	//------------------------------
	if(empty($_POST['email'])){
		$err++;
		$err_email = '<font style="color:red;font-weight:normal;" >Please enter user email.</font>';
	}else if($db->num_rows2(selectSql(TBL_SHOP,"WHERE `id` NOT IN('".$_REQUEST[eId]."') AND `email` = '".addslashes($_POST['email'])."'",''))){
		$err++;
		$err_email = '<font style="color:red;font-weight:normal;" >Email all-ready exists in our database.</font>';
	}	
	if(empty($_POST['phone'])){	
	//no error	
	}
	else if(!preg_match("/^[0-9]+$/", $_POST['phone']))
	{ 
		$err++;
		$err_phone = '<br/><font style="color:#FF0000;font-weight:normal;">Please enter Valid Phone No..</font>';
	}
	
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
	//---------------------------------
	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add
			checkUserLevelPermission($_SESSION['userLevel'],TBL_SHOP,'add');
			$addSql = "   	INSERT INTO `".TBL_SHOP."` SET 
							`id_shop_group` = '1',
							`name` = '".addslashes($_POST['name'])."',
							`email` = '".addslashes($_POST['email'])."',
							`fax` = '".addslashes($_POST['fax'])."',
						
							`email_format_url` = '".addslashes($_POST['email_format_url'])."',
							`crs_phone` = '".addslashes($_POST['crs_phone'])."',							
							`incentive_module_approved` = '".addslashes($_POST['incentive_module_approved'])."',
							`quotation_module_approved` = '".addslashes($_POST['quotation_module_approved'])."',
							`apptracking_module_approved` = '".addslashes($_POST['apptracking_module_approved'])."',
							`short_code` = '".addslashes($_POST['short_code'])."'";
							
			$addSql .= "	,`phone` = '".addslashes($_POST['phone'])."'";
				if($_FILES['image']['name'] != ''){				
				$addSql .= "	,`image` = '".addslashes($insert_image)."'";
			}else{
				$addSql .= "	,`image` = '".addslashes($_POST['old_image'])."'";
			}
			$addSql .= "	,`address` = '".addslashes($_POST['address'])."'";
			$addSql .= "	,`city` = '".addslashes($_POST['city'])."'";
			$addSql .= "	,`state` = '".addslashes($_POST['state'])."'";
			$addSql .= "	,`country` = '105'";
			$addSql .= "	,`postcode` = '".addslashes($_POST['postcode'])."'";			
			$addSql .= "	,`note` = '".addslashes($_POST['note'])."'";
			$addSql .= "	,`about_brand` = '".addslashes($_POST['about_brand'])."'";
			$addSql .= "	,`cancellation_policy` = '".addslashes($_POST['cancellation_policy'])."'";
			$addSql .= "	,`other_policy` = '".addslashes($_POST['other_policy'])."'";
			$addSql .= "	,`feedback_url` = '".addslashes($_POST['feedback_url'])."'";
			$addSql .= "	,`social_links` = '".addslashes($_POST['social_links'])."'";
			$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'";
			if(executeSql($addSql)){
				$_SESSION['successMsg'] = 'New Shop details has been added sucessfully.';
				header("location:manageShop.php");
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Shop details has not been saved. Please make corrections below.';
			}
		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update
			checkUserLevelPermission($_SESSION['userLevel'],TBL_SHOP,'update');
			$editSql = "   	UPDATE `".TBL_SHOP."` SET 
			               `id_shop_group` = '1',
							`name` = '".addslashes($_POST['name'])."',
							`email` = '".addslashes($_POST['email'])."',
							
							`crs_phone` = '".addslashes($_POST['crs_phone'])."',
							`fax` = '".addslashes($_POST['fax'])."',
							`email_format_url` = '".addslashes($_POST['email_format_url'])."',	
							`incentive_module_approved` = '".addslashes($_POST['incentive_module_approved'])."',
							`quotation_module_approved` = '".addslashes($_POST['quotation_module_approved'])."',
							`apptracking_module_approved` = '".addslashes($_POST['apptracking_module_approved'])."',
							`short_code` = '".addslashes($_POST['short_code'])."'";							
			$editSql .= "	,`phone` = '".addslashes($_POST['phone'])."'";
				if($_FILES['image']['name'] != ''){				
				$editSql .= "	,`image` = '".addslashes($insert_image)."'";
			}else{
				$editSql .= "	,`image` = '".addslashes($_POST['old_image'])."'";
			}
			$editSql .= "	,`address` = '".addslashes($_POST['address'])."'";
			$editSql .= "	,`city` = '".addslashes($_POST['city'])."'";
			$editSql .= "	,`state` = '".addslashes($_POST['state'])."'";
			$editSql .= "	,`country` = '105'";
			$editSql .= "	,`postcode` = '".addslashes($_POST['postcode'])."'";			
			$editSql .= "	,`note` = '".addslashes($_POST['note'])."'";
			$editSql .= "	,`about_brand` = '".addslashes($_POST['about_brand'])."'";	
			$editSql .= "	,`cancellation_policy` = '".addslashes($_POST['cancellation_policy'])."'";
			$editSql .= "	,`other_policy` = '".addslashes($_POST['other_policy'])."'";
			$editSql .= "	,`feedback_url` = '".addslashes($_POST['feedback_url'])."'";
			$editSql .= "	,`social_links` = '".addslashes($_POST['social_links'])."'";		
			$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`status` = '".addslashes($_POST['status'])."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							WHERE `id` = '".addslashes($_POST['eId'])."'";
			if(executeSql($editSql)){
				$_SESSION['successMsg'] = 'Shop details '.selectColumn(TBL_SHOP,'name'," WHERE `id` = '".addslashes($_POST['eId'])."'").' has been updated sucessfully.';
				header("location:manageShop.php");
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Shop '.selectColumn(TBL_SHOP,'name'," WHERE `id` = '".addslashes($_POST['eId'])."'").' details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'Shop details has not been saved.Please make corrections.';
	}
}
// ----------cate---------
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
	$sqlUserDetail = "  SELECT * FROM `".TBL_SHOP."`
						WHERE `id` = '".addslashes($_REQUEST['eId'])."'";
	$db->query($sqlUserDetail);
	if($db->num_rows() > 0){
		$rowUserDetail = $db->fetch_object();
	}						
}	
?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       User Manager
        <small>Manage Shop</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="manageShop.php">User Manager</a></li>
        <li class="active">Manage Shop</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> Shop</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->          
			  <form name="form1"  method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off" >
                  <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" />
					<div class="form-group has-error">
						<?php if($_SESSION['errorMsg']){?>
						 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
						<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
					 	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
						<?php unset($_SESSION['successMsg']);}?>
					 </div>
              <div class="box-body">
                
				
				 <div class="form-group">
                  <label for="name">Name<font color="#FF0000">*</font></label>
                  <input type="text" data-parsley-required class="form-control" placeholder="Enter your name" id="name" name="name" value="<?php if($_POST) echo $_POST['name'];else echo stripslashes($rowUserDetail->name);?>">
				<?php echo $err_name;?>
                </div>
                
                <div class="form-group">
                  <label for="name">Short Code<font color="#FF0000">*</font></label>
                  <input type="text" data-parsley-required class="form-control" data-parsley-pattern="^[a-zA-Z]+$"  placeholder="Enter your Short code" id="short_code" name="short_code" value="<?php if($_POST) echo $_POST['short_code'];else echo stripslashes($rowUserDetail->short_code);?>">
				<?php echo $err_short_code;?>
                </div>
                
                
                
				
				
				
				<div class="form-group">
                  <label for="email">Email<font color="#FF0000">*</font></label>
                  <input type="email" class="form-control" placeholder="Enter your email" id="email" name="email" value="<?php if($_POST) echo $_POST['email'];else echo stripslashes($rowUserDetail->email);?>">
				 <p class="help-block">&nbsp;Must be unique.</p><?php echo $err_email;?>
                </div>
				
				 
				
				
				<div class="form-group">
                  <label for="phone">Phone</label>
                  <input type="text" class="form-control" placeholder="Enter your phone" id="phone" name="phone" value="<?php if($_POST) echo $_POST['phone'];else echo stripslashes($rowUserDetail->phone);?>">
				 <?php echo $err_phone;?>
                </div>  
                <div class="form-group">
                  <label for="phone">CRS phone</label>
                  <input type="text" class="form-control" placeholder="Enter your crs phone" id="crs_phone" name="crs_phone" value="<?php if($_POST) echo $_POST['crs_phone'];else echo stripslashes($rowUserDetail->crs_phone);?>">
				 <?php echo $err_phone;?>
                </div> 
				<div class="form-group">
                  <label for="fax">Fax</label>
                  <input type="text" class="form-control" placeholder="Enter  fax number" id="fax" name="fax" value="<?php if($_POST) echo $_POST['fax'];else echo stripslashes($rowUserDetail->fax);?>">
				 <?php echo $err_phone;?>
                </div>  
				
				<div class="row">					
					<div class="col-sm-3">
						<div class="form-group">				
						 <label for="image">Logo &nbsp;&nbsp;</label>
							<div class="btn btn-default btn-file">
							  <i class="fa fa-upload"></i> Upload
							 <input type="file" class="form-control" placeholder="Select Room Image" id="image" name="image" value="">	
							 <input type="hidden" name="old_image" value="<?php echo stripslashes($rowUserDetail->image);?>"/>					 
						
							</div>
							<p class="help-block">Must be of width:600px and height:300px.<br />Max. Size: 1MB</p>							
							<a href="javascript:void(0);" id="delete" class="btn btn-danger" onClick="removeImage('ajax/ajaxremoveImage.php','<?php echo TBL_SHOP; ?>','<?php echo $rowUserDetail->id; ?>','image','imageCallback','shop','<?php echo $rowUserDetail->image; ?>');" >Remove</a>
					</div>	
					<?php echo $err_image;?>
					</div>								
					<div class="col-sm-9">													
						<ul class="mailbox-attachments clearfix"> 
									<li id="imageCallback">
									<?php if(@file_exists($image_path.$rowUserDetail->image) && $rowUserDetail->image!=''){ ?>
									<span class="mailbox-attachment-icon has-img">							 
										<img src="<?php echo $image_display_path.$rowUserDetail->image; ?>" alt="Room Image">							  
									  </span>			
									  <div class="mailbox-attachment-info">
										<a href="javascript:void(0);" class="mailbox-attachment-name"><i class="fa fa-camera"></i> <?php echo $rowUserDetail->image; ?></a>
											<span class="mailbox-attachment-size">
											  <?php echo round(filesize($image_path.$rowUserDetail->image)/ 1024 ,2).' KB'; ?>
											  <a href="<?php echo $image_display_path.$rowUserDetail->image; ?>" download class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
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
				 
				
				
				<div class="form-group">
                  <label for="address">Address</label>
                  <textarea class="form-control" id="address" name="address"><?php if($_POST) echo $_POST['address'];else echo stripslashes($rowUserDetail->address);?></textarea>
				 <?php echo $err_address;?>
                </div>  
				
				 
				
				<div class="form-group">
                  <label for="city">City<font color="#FF0000">*</font></label>
                  <input type="text" class="form-control" placeholder="Enter your city" id="city" name="city" value="<?php if($_POST) echo $_POST['city'];else echo stripslashes($rowUserDetail->city);?>">
				 <?php echo $err_city;?>
                </div> 
				
				<div class="form-group">
                  <label for="state">State<font color="#FF0000">*</font></label>
                 <?php $categoryDropDown = '<select class="form-control select2" name="state" id="state">
											  	<option value="">Select State</option>';
											  $resLocation = selectSql(TBL_STATE," WHERE `status` = '1' AND `id_country` ='110'",' ORDER BY `name`');
											  if($db->num_rows2($resLocation)){
											  	while($resultLocation = $db->fetch_object2($resLocation)){
													if($_REQUEST['state'] == $resultLocation->id_state){
														$selected = 'selected="selected"';
													}elseif($rowUserDetail->state == $resultLocation->id_state){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$resultLocation->id_state.'">'.ucfirst($resultLocation->name).'</option>';
												}
											  }
											 	echo $categoryDropDown .= '</select>';
											  ?>
                                            <?php echo $err_location;?>
                </div>
				
				
				<div class="form-group">
                  <label for="postcode">Pin Code <font color="#FF0000">*</font></label>
                  <input type="text" class="form-control" placeholder="Enter your pincode" id="postcode" name="postcode" value="<?php if($_POST) echo $_POST['postcode'];else echo stripslashes($rowUserDetail->postcode);?>">
				 <?php echo $err_postcode;?>
                </div>
				
				<div class="form-group">
                  <label for="note">Note</label>
                  <textarea class="form-control" id="note" name="note" ><?php if($_POST) echo $_POST['note'];else echo stripslashes($rowUserDetail->note);?></textarea>
				 <?php echo $err_note;?>
                </div> 

                <div class="form-group">
                  <label for="about_brand">About Brand</label>
				   <textarea class="ckeditor" id="about_brand" name="about_brand" rows="10" cols="80"><?php if($_POST) echo $_POST['about_brand'];else echo stripslashes($rowUserDetail->about_brand);?></textarea>
                  
				<?php echo $err_about_brand;?>
                </div>
				
				 <div class="form-group">
                  <label for="cancellation_policy">Cancellation Policy</label>
				   <textarea class="ckeditor" id="cancellation_policy" name="cancellation_policy" rows="10" cols="80"><?php if($_POST) echo $_POST['cancellation_policy'];else echo stripslashes($rowUserDetail->cancellation_policy);?></textarea>
                  
				<?php echo $err_cancellation_policy;?>
                </div>
				
				 <div class="form-group">
                  <label for="other_policy">Membership Policy</label>
				   <textarea class="ckeditor" id="other_policy" name="other_policy" rows="10" cols="80"><?php if($_POST) echo $_POST['other_policy'];else echo stripslashes($rowUserDetail->other_policy);?></textarea>
                  
				<?php echo $err_other_policy;?>
                </div>
				<div class="form-group">
                  <label for="feedback_url">Feedback Url <font color="#FF0000">*</font></label>
                  <input type="text" class="form-control" placeholder="Enter your feedback url" id="feedback_url" name="feedback_url" value="<?php if($_POST) echo $_POST['feedback_url'];else echo stripslashes($rowUserDetail->feedback_url);?>">
				 <?php echo $err_feedback_url;?>
                </div>
				 <div class="form-group">
                  <label for="social_links">Social Links</label>
				   <textarea class="ckeditor" id="social_links" name="social_links" rows="10" cols="80"><?php if($_POST) echo $_POST['social_links'];else echo stripslashes($rowUserDetail->social_links);?></textarea>
                  
				<?php echo $err_social_links;?>
                </div>
                
                <div class="form-group">
                  <label for="email_format_url">Email Format Url <font color="#FF0000">*</font></label>
                  <input type="text" class="form-control" placeholder="Enter your Rate Letter url" id="email_format_url" name="email_format_url" value="<?php if($_POST) echo $_POST['email_format_url'];else echo stripslashes($rowUserDetail->email_format_url);?>">
				 <?php echo $err_email_format_url;?>
                </div>
                
                
                
               <?php /*?> <div class="form-group">
                  <label for="feedback_url">Rate Letter Url <font color="#FF0000">*</font></label>
                  <input type="text" class="form-control" placeholder="Enter your Rate Letter url" id="rateletter_url" name="rateletter_url" value="<?php if($_POST) echo $_POST['rateletter_url'];else echo stripslashes($rowUserDetail->rateletter_url);?>">
				 <?php echo $err_rateletter_url;?>
                </div>
                <?php */?>
                 
				
				<div class="form-group">
                  <label for="status">Status</label>
                 <input type="radio" class="flat-red" <?php if($_POST['status'] == '1'){echo "checked";}else{if($rowUserDetail->status == 1)echo "checked";}?> value="1" name="status"/> Active
				 <input type="radio" class="flat-red" <?php if($_POST['status'] == '0'){echo "checked";}else{if($rowUserDetail->status == 0)echo "checked";}?> value="0" name="status"/> Inactive
				 <?php echo $err_status;?>
                </div>
				<div class="form-group">
                  <label for="status">Incentive module </label>
                 <input type="radio" class="flat-red" <?php if($_POST['incentive_module_approved'] == '1'){echo "checked";}else{if($rowUserDetail->incentive_module_approved == 1)echo "checked";}?> value="1" name="incentive_module_approved"/> Yes
				 <input type="radio" class="flat-red" <?php if($_POST['incentive_module_approved'] == '0'){echo "checked";}else{if($rowUserDetail->incentive_module_approved == 0)echo "checked";}?> value="0" name="incentive_module_approved"/> No
				 <?php echo $err_status;?>
                </div>
                 <div class="form-group">
                  <label for="status">Quotation module </label>
                 <input type="radio" class="flat-red" <?php if($_POST['quotation_module_approved'] == '1'){echo "checked";}else{if($rowUserDetail->quotation_module_approved == 1)echo "checked";}?> value="1" name="quotation_module_approved"/> Yes
				 <input type="radio" class="flat-red" <?php if($_POST['quotation_module_approved'] == '0'){echo "checked";}else{if($rowUserDetail->quotation_module_approved == 0)echo "checked";}?> value="0" name="quotation_module_approved"/> No
				
                </div>
                <div class="form-group">
                  <label for="status">App Tracking module </label>
                 <input type="radio" class="flat-red" <?php if($_POST['apptracking_module_approved'] == '1'){echo "checked";}else{if($rowUserDetail->apptracking_module_approved == 1)echo "checked";}?> value="1" name="apptracking_module_approved"/> Yes
				 <input type="radio" class="flat-red" <?php if($_POST['apptracking_module_approved'] == '0'){echo "checked";}else{if($rowUserDetail->apptracking_module_approved == 0)echo "checked";}?> value="0" name="apptracking_module_approved"/> No
				 <?php echo $err_status;?>
                </div>
				<?php if($rowUserDetail->date_created){?>
				  
				<div class="form-group">
                  <label for="date_created">Date Created</label>
                  <input type="text" disabled="disabled" class="form-control" id="date_created"  value="<?php echo stripslashes(dateformat($rowUserDetail->date_created));?>">				
                </div> 
				
				<div class="form-group">
                  <label for="last_modified">Last Updated</label>
                  <input type="text" disabled="disabled" class="form-control" id="last_modified" value="<?php echo stripslashes(dateformat($rowUserDetail->last_modified));?>">				
                </div> 
				
				<div class="form-group">
                  <label for="last_modified_by">Last Updated By</label>
				    <?php $sqlUserDetail = $db->fetch_obj2(selectSql(TBL_USERS,"WHERE `id` = '".$rowUserDetail->last_modified_by."'",''));?>
                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail->username);?>">				
                </div>  
				  
				  <?php } ?>            
              </div>
              <!-- /.box-body -->	
			 <div class="box-footer">                                       
				<input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
				&nbsp;&nbsp;&nbsp;&nbsp;
			   <input type='button' value='Cancel' class="btn btn-default" onclick='window.location.replace("manageShop.php"); '>
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