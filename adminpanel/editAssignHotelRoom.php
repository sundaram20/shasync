<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_ASSIGN_HOTEL_ROOM,'view');
$image_path = $UPLOAD_FILES.'/hotel_room/';
$image_display_path = $UPLOAD_FILES_PATH ."/hotel_room/";
/////////////////////////////////////////////////////////////////////////////////////
if($_REQUEST['eId'] == ''){ header("location:editHotels.php"); }
/////////////////////////////////////////////////////////////////////////////////////
//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){		
	$err = 0;
	
	if(empty($_POST['room_id'])){
		$err++;
		$err_room_id = '<font style="color:red;font-weight:normal;" ><br>Please select room.</font>';
	}
	if(empty($_POST['single_pax_price'])){
		$err++;
		$err_single_pax_price = '<font style="color:red;font-weight:normal;" ><br>Please enter single pax price.</font>';
	}
	if(empty($_POST['double_pax_price'])){
		$err++;
		$err_double_pax_price = '<font style="color:red;font-weight:normal;" ><br>Please enter double pax price.</font>';
	}
	
	if(empty($_POST['room_inventory'])){
		$err++;
		$err_inventory = '<font style="color:red;font-weight:normal;" ><br>Please enter inventory.</font>';
	}	
	if(($_POST['old_image'] == '') && ($_FILES['image']['name'] == '')){
	   //no error
	}else{
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
		}elseif($_POST['old_image'] == ''){
			$err++;
			$err_image = '<font style="color:red;font-weight:normal;" ><br>Image not selected or size is greater than 1MB.</font>';
		}
	}
	
	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['id'])){//add
			checkUserLevelPermission($_SESSION['userLevel'],TBL_ASSIGN_HOTEL_ROOM,'add');
			$addSql = "   	INSERT INTO `".TBL_ASSIGN_HOTEL_ROOM."` SET 
							`room_id` = '".addslashes($_POST['room_id'])."',
							`hotel_id` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."',
							`single_pax_price` = '".addslashes($_POST['single_pax_price'])."',
							`double_pax_price` = '".addslashes($_POST['double_pax_price'])."',
							`ids_room_amenities` = '".addslashes(implode(',',$_POST['room_amenities']))."',							
							`description` = '".addslashes($_POST['description'])."',
							`is_online_booking` = '".addslashes($_POST['is_online_booking'])."',
							`online_url` = '".addslashes($_POST['online_url'])."',
							`is_top_deal` = '".addslashes($_POST['is_top_deal'])."',
							`inventory` = '".addslashes($_POST['room_inventory'])."',
							`display_order` = '".addslashes($_POST['display_order'])."'";
			if($_FILES['image']['name'] != ''){				
				$addSql .= "	,`image` = '".addslashes($insert_image)."'";
			}else{
				$addSql .= "	,`image` = '".addslashes($_POST['old_image'])."'";
			}
			$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'";
			if(executeSql($addSql)){
				unset($_POST);
				$_SESSION['successMsg'] = 'New Hotel room assigned details has been added sucessfully.';
				header("location:manageAssignHotelRoom.php?eId=".$_REQUEST['eId']."&action=edit&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Hotel room assigned details has not been saved. Please make corrections below.';
			}
		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['id'])){//update
		
			checkUserLevelPermission($_SESSION['userLevel'],TBL_ASSIGN_HOTEL_ROOM,'update');
			$editSql = "   	UPDATE `".TBL_ASSIGN_HOTEL_ROOM."` SET 
							`room_id` = '".addslashes($_POST['room_id'])."',
							`hotel_id` = '".addslashes(encryptor('decrypt',$_REQUEST[eId]))."',
							`single_pax_price` = '".addslashes($_POST['single_pax_price'])."',
							`double_pax_price` = '".addslashes($_POST['double_pax_price'])."',
							`ids_room_amenities` = '".addslashes(implode(',',$_POST['room_amenities']))."',
							`description` = '".addslashes($_POST['description'])."',
							`is_online_booking` = '".addslashes($_POST['is_online_booking'])."',
							`online_url` = '".addslashes($_POST['online_url'])."',
							`is_top_deal` = '".addslashes($_POST['is_top_deal'])."',
							`inventory` = '".addslashes($_POST['room_inventory'])."',
							`display_order` = '".addslashes($_POST['display_order'])."'";
			if($_FILES['image']['name'] != ''){
				$editSql .= "	,`image` = '".addslashes($insert_image)."'";
			}else{
				$editSql .= "	,`image` = '".addslashes($_POST['old_image'])."'";
			}
			$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`status` = '".addslashes($_POST['status'])."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							WHERE `id` = '".addslashes(encryptor('decrypt',$_POST[id]))."'";								
			if(executeSql($editSql)){
				
				$_SESSION['successMsg'] = selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".encryptor('decrypt',$_REQUEST[eId])."'").'-'.selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$_POST['room_id']."'").' details has been updated sucessfully.';
				header("location:manageAssignHotelRoom.php?eId=".$_REQUEST['eId']."&action=edit&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".encryptor('decrypt',$_REQUEST[eId])."'").'-'.selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$_POST['room_id']."'").' details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'Hotel room assigned details has not been saved. Please make corrections.';
	}
}
// ----------cate---------
if(!empty($_REQUEST['id']) && $_REQUEST['action']=='edit'){
	$sql = "  SELECT * FROM `".TBL_ASSIGN_HOTEL_ROOM."`
								WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST[id]))."'";
	$db->query($sql);
	if($db->num_rows() > 0){
		$row = $db->fetch_object();
		$disable=	'disabled';
	}						
}	
							

?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Hotel Manager
        <small>Assign Room To Hotel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Assign Room To Hotel</li>
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
			   <li  ><a href="editHotels.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" >Overview</a></li>   
              <li ><a href="editHotelGallery.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>">Photo Gallery</a></li> 
			  <li class="active"><a href="manageAssignHotelRoom.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" data-toggle="tab">Room Types</a></li>        
			  
			  <li><a href="inventory.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" >Inventory</a></li> 
			  <li  ><a href="calendar.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" >Calendar</a></li>
            </ul> 
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $_REQUEST['id']==''?'Add':'Edit'?> Room : <a><?php echo selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST[eId]))."'"); ?> </a></h3>     
			   <a href="manageAssignHotelRoom.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" class="btn btn-success pull-right"><i class="fa fa-angle-double-left"></i> Back</a>  
			</div> 
            <!-- /.box-header -->
            <!-- form start -->  			        
			 <form name="form1"  method="post" enctype="multipart/form-data" role="form">
                <input type="hidden" value="<?php echo $_REQUEST['id'];?>" name="id" />
					<div class="form-group has-error" align="center">
						<?php if($_SESSION['errorMsg']){?>
						 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
						<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
					 	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
						<?php unset($_SESSION['successMsg']);}?>
					 </div>
              <div class="box-body">			  
			   <div class="form-group">
                  <label for="room_id">Room Type<font color="#FF0000">*</font></label>
                 <?php 
				  if($_REQUEST['id']!=''){?>
                  <input type="hidden" id="room_id" name="room_id" value="<?php echo $row->room_id;?>">
                  <?php 
				  
				  } ?>
				  <?php $categoryDropDown = '<select class="form-control select2" name="room_id" id="room_id" '.$disable.'>
											  <option value="">Select Room Type</option>';
											  $resCat = selectSql(TBL_ROOM_TYPE," where id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');
											  if(num_rows($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['room_id'] == $resultCat->id){
														$selected = 'selected="selected"';
													}elseif($row->room_id == $resultCat->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $categoryDropDown .= '</select>';
											  ?>
				<?php echo $err_room_id;?>
                </div>
				
				<div class="form-group">
                  <label for="room_inventory">Inventory<font color="#FF0000">*</font></label>
                 
				  <input type="text" class="form-control" placeholder="Enter Inventory" id="room_inventory" name="room_inventory" value="<?php if($_POST) echo $_POST['room_inventory'];else echo stripslashes($row->inventory);?>">
				<?php echo $err_inventory;?>
                </div>
				
				<div class="form-group">
                  <label for="single_pax_price">Single Pax Price<font color="#FF0000">*</font></label>
                  <input type="text" class="form-control" placeholder="Enter single pax price" id="single_pax_price" name="single_pax_price" value="<?php if($_POST) echo $_POST['single_pax_price'];else echo stripslashes($row->single_pax_price);?>">
				<?php echo $err_single_pax_price;?>
                </div>
				
				<div class="form-group">
                  <label for="double_pax_price">Double Pax Price<font color="#FF0000">*</font></label>
                  <input type="text" class="form-control" placeholder="Enter double pax price" id="double_pax_price" name="double_pax_price" value="<?php if($_POST) echo $_POST['double_pax_price'];else echo stripslashes($row->double_pax_price);?>">
				<?php echo $err_double_pax_price;?>
                </div>
				
				
								
				<div class="row">			
					<div class="col-sm-3">
						<div class="form-group">				
						 <label for="image">Upload Room Image &nbsp;&nbsp;</label>
							<div class="btn btn-default btn-file">
							  <i class="fa fa-upload"></i> Upload
							 <input type="file" class="form-control" placeholder="Select Room Image" id="image" name="image" value="<?php if($_POST) echo $_POST['image'];else echo stripslashes($row->image);?>">
							 <input type="hidden" name="old_image" value="<?php echo stripslashes($row->image);?>"/>					 
						<?php echo $err_image;?>
							</div>
							<p class="help-block">Must be of width:600px and height:300px.<br />Max. Size: 1MB</p>							
							<a href="javascript:void(0);" id="delete" class="btn btn-danger" onClick="removeImage('removeImage.php','<?php echo TBL_ASSIGN_HOTEL_ROOM; ?>','<?php echo $row->id; ?>','image','imageCallback','hotel_room','<?php echo $row->image; ?>');" >Remove</a>
					</div>	
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
				
				 <div class="form-group">
                  <label for="description">Description</label>
				   <textarea class="ckeditor" id="description" name="description" rows="10" cols="80"><?php if($_POST) echo $_POST['description'];else echo stripslashes($row->description);?></textarea>
                  
				<?php echo $err_description;?>
                </div>

                <div class="form-group">
                  <label for="userlevelId">Room Amenities</label>
				  <select class="form-control select2" name="room_amenities[]" multiple="multiple">				  
                  <?php 
					$sqlUserActions = selectSql(TBL_ROOM_AMENITIES," where id_shop='".$_SESSION['shop']."' ",'');
					$iCounterActions = 0;
					while($resUserActions = $db->fetch_object2($sqlUserActions)){
						$chkSql = "SELECT * FROM `".TBL_ASSIGN_HOTEL_ROOM."` WHERE FIND_IN_SET('".$resUserActions->id."',ids_room_amenities ) and id='".addslashes(encryptor('decrypt',$_REQUEST[id]))."' ";
						
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
				
				<div class="form-group">
                  <label for="is_top_deal">Home Page Deal &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <input type="checkbox"  class="flat-red" id="is_top_deal" name="is_top_deal" value="1" <?php if($_POST['is_top_deal']=='1'){echo 'checked="checked"'; }else if(stripslashes($row->is_top_deal)=='1'){echo 'checked="checked"'; }?>>
				  </label> 
				<?php echo $err_is_top_deal;?>
                </div>
				
				<div class="form-group">
                  <label for="is_online_booking">Is online booking &nbsp;&nbsp;&nbsp;
                  <input type="checkbox" class="flat-red"  id="is_online_booking" name="is_online_booking" value="1" <?php if($_POST['is_online_booking']=='1'){echo 'checked="checked"'; }else if(stripslashes($row->is_online_booking)=='1'){echo 'checked="checked"'; }?>> </label>
				<?php echo $err_is_online_booking;?>
                </div>
				
				<div class="form-group">
                  <label for="online_url">Online Url</label>
                  <input type="text" class="form-control" placeholder="Enter online url" id="online_url" name="online_url" value="<?php if($_POST) echo $_POST['online_url'];else echo stripslashes($row->online_url);?>">
				<?php echo $err_online_url;?>
                </div>
				
				<div class="form-group">
                  <label for="display_order">Display Order</label>
                  <input type="number" class="form-control" placeholder="Enter display order" id="display_order" name="display_order" value="<?php if($_POST) echo $_POST['display_order'];else echo stripslashes($row->display_order);?>">
				<?php echo $err_display_order;?>
                </div>
								
				<div class="form-group">
                  <label for="status">Status</label>
                 <input type="radio"  class="flat-red" <?php if($_POST['status'] == '1'){echo "checked";}else{if($row->status == 1)echo "checked";}?> value="1" name="status"/> Active
				 <input type="radio" class="flat-red" <?php if($_POST['status'] == '0'){echo "checked";}else{if($row->status == 0)echo "checked";}?> value="0" name="status"/> Inactive
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
				<input type='submit' value='<?=($_REQUEST['id']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
				&nbsp;&nbsp;&nbsp;&nbsp;
			   <input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("manageAssignHotelRoom.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>"); '>
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


