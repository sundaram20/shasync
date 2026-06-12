<?php include_once("../config/auto_loader.php");
$image_path = $UPLOAD_FILES.'/users/';
$image_display_path = $UPLOAD_FILES_PATH ."/users/";
//---------------------------------------------------------------------------------------------------------
/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";*/

if($_POST['Save']){
	$err = 0;
	if(empty($_POST['name'])){
		$err++;
		$err_name = '<font style="color:red;font-weight:normal;" ><br>Please enter user name.</font>';
	}else if(mysql_num_rows(mysql_query("	SELECT * FROM `".TBL_USERS."` 
											WHERE `id` NOT IN('".addslashes($_SESSION['userId'])."') AND `name` = '".addslashes($_POST['name'])."'"))){
		$err++;
		$err_name = '<font style="color:red;font-weight:normal;" ><br>Name all-ready exists in our database.</font>';
	}
	if(empty($_POST['username'])){
		$err++;
		$err_username = '<font style="color:red;font-weight:normal;" ><br>Please enter username.</font>';
	}else if(mysql_num_rows(mysql_query("	SELECT * FROM `".TBL_USERS."` 
											WHERE `id` NOT IN('".addslashes($_SESSION['userId'])."') AND `username` = '".addslashes($_POST['username'])."'"))){
		$err++;
		$err_username = '<font style="color:red;font-weight:normal;" ><br>Username all-ready exists in our database.</font>';
	}
	//------------------------------
	if(empty($_POST['email'])){
		$err++;
		$err_email = '<font style="color:red;font-weight:normal;" ><br>Please enter user email.</font>';
	}else if(mysql_num_rows(mysql_query("	SELECT * FROM `".TBL_USERS."` 
											WHERE `id` NOT IN('".$_SESSION['userId']."') AND `email` = '".addslashes($_POST['email'])."'"))){
		$err++;
		$err_email = '<font style="color:red;font-weight:normal;" ><br>Email all-ready exists in our database.</font>';
	}
	if(empty($_POST['password'])){
		$err++;
		$err_password = '<font style="color:red;font-weight:normal;" ><br>Please enter password.</font>';
	}
	//---------------------------------
	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_SESSION['userId'])){//add			
		}else if(($_POST['Save'] == 'Edit') && !empty($_SESSION['userId'])){//update
			checkUserLevelPermission($_SESSION['userLevel'],TBL_USERS,'update');
			//`user_level` = '".addslashes($_POST['userlevelId'])."',
			$editSql = "   	UPDATE `".TBL_USERS."` SET 
			                `name` = '".addslashes($_POST['name'])."',
							`email` = '".addslashes($_POST['email'])."',
							`username` = '".addslashes($_POST['username'])."'";
			$editSql .= "	,`designation` = '".trim(addslashes($_POST['designation']))."'";

			$editSql .= "	,`phone` = '".addslashes($_POST['phone'])."'";

			$editSql .= "	,`mobile` = '".addslashes($_POST['mobile'])."'";

			$editSql .= "	,`company` = '".addslashes($_POST['company'])."'";

			$editSql .= "	,`address` = '".addslashes($_POST['address'])."'";

			$editSql .= "	,`address2` = '".addslashes($_POST['address2'])."'";

			$editSql .= "	,`city` = '".addslashes($_POST['city'])."'";

			$editSql .= "	,`location` = '".addslashes($_POST['location'])."'";

			$editSql .= "	,`zip` = '".addslashes($_POST['zip'])."'";

			$editSql .= "	,`skype` = '".addslashes($_POST['skype'])."'";

			

			$editSql .= "	,`comments` = '".addslashes($_POST['comments'])."'";				
			$editSql .= "	,`password` = '".base64_encode($_POST['password'])."'";
			$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							WHERE `id` = '".addslashes($_SESSION['userId'])."'";
			if(executeSql($editSql)){
				$_SESSION['successMsg'] = 'User details for '.selectColumn(TBL_USERS,'name'," WHERE `id` = '".addslashes($_SESSION['userId'])."'").' has been updated sucessfully.';				
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'User '.selectColumn(TBL_USERS,'name'," WHERE `id` = '".addslashes($_SESSION['userId'])."'").' details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'User details has not been saved.Please make corrections.';
	}
}
// ----------cate---------
if(!empty($_SESSION['userId'])){
	$sqlUserDetail = "  SELECT * FROM `".TBL_USERS."`
						WHERE `id` = '".addslashes($_SESSION['userId'])."'";
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
       Profile Manager
        <small>Preview</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">User</a></li>
        <li class="active">Profile</li>
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
              <h3 class="box-title"><?php echo $_SESSION['userId']==''?'Add':'Edit'?> Profile </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form name="form1"  method="post" enctype="multipart/form-data" role="form">
              <input type="hidden" value="<?php echo $_SESSION['userId'];?>" name="eId" />
					<div class="form-group has-error">
						<?php if($_SESSION['errorMsg']){?>
						 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
						<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
					 	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
						<?php unset($_SESSION['successMsg']);}?>
					 </div>
              <div class="box-body">
                <div class="form-group">
                  <label for="userlevelId">User Level</label>
                  <?php $categoryDropDown = '<select  class="form-control" name="userlevelId" disabled id="userlevelId">
											<option value="">Select User Level</option>';
											  $resUserLevel = selectSql(TBL_USER_LEVELS," WHERE `status` = '1'",' ORDER BY `name`');
											  if(mysql_num_rows($resUserLevel)){
											  	while($resultUserLevel = mysql_fetch_object($resUserLevel)){
													if($rowUserDetail->user_level == $resultUserLevel->id){
														$selected = 'selected="selected"';
													}elseif($rowUserDetail->user_level == $resultUserLevel->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'</option>';
												}
											  }
											 	echo $categoryDropDown .= '</select>';
											  ?>
                                            <?php echo $err_userlevelId;?>
                </div>
				 <div class="form-group">
                  <label for="name">Name</label>
                  <input type="text" class="form-control" placeholder="Enter your name" id="name" name="name" value="<?php if($_POST) echo $_POST['name'];else echo stripslashes($rowUserDetail->name);?>">
				<?php echo $err_name;?>
                </div>
				
				<div class="form-group">
                  <label for="username">Username</label>
                  <input type="text" class="form-control" placeholder="Enter your username" id="username" name="username" value="<?php if($_POST) echo $_POST['username'];else echo stripslashes($rowUserDetail->username);?>">
				 <p class="help-block">&nbsp;Must be unique.</p><?php echo $err_username;?>
                </div>
				
				<div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" class="form-control" placeholder="Enter your email" id="email" name="email"  value="<?php if($_POST) echo $_POST['email'];else echo stripslashes($rowUserDetail->email);?>">
				 <p class="help-block">&nbsp;Must be unique.</p><?php echo $err_email;?>
                </div>
				
				<div class="form-group">
                  <label for="password">Password</label>
                  <input type="password" class="form-control" placeholder="Enter your password" id="password" name="password"  value="<?php if($_POST) echo $_POST['password'];else echo stripslashes(base64_decode($rowUserDetail->password));?>">
				 <?php echo $err_password;?>
                </div> 

                      <div class="form-group">

                      

                      

                     

                      <label for="first_name">Designation <font color="#FF0000">*</font></label>

                       <?php $marketDropDown = '<select class="form-control input-sm" name="designation" id="designation" data-parsley-errors-container="#designationError" data-parsley-required   >

                												  <option value="">Select Designation</option>';

                												 

                												  $resCat = selectSql(TBL_DESIGNATION_MASTER," where status='1' and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name`');										

                												  if($db->num_rows2($resCat)){

                													while($resultCat = $db->fetch_object2($resCat)){

                													if($_REQUEST['designation'] == $resultCat ->id){

                														$selected = 'selected="selected"';

                													}elseif($rowUserDetail->designation== $resultCat ->id){

                														

                														$selected = 'selected="selected"';

                														}else{

                															$selected = '';

                														}

                																

                														$marketDropDown .= '<option  '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';

                													}

                												  }

                													echo $marketDropDown .= '</select>';

                												  ?>			

                   

                    <?php echo $err_designation;?>

                    </div>

                      <div class="form-group">

                        <label for="phone">Phone</label>

                        <input type="text" class="form-control" placeholder="Enter your phone" id="phone" name="phone" value="<?php if($_POST) echo $_POST['phone'];else echo stripslashes($rowUserDetail->phone);?>">

                        <?php echo $err_phone;?> </div>

                        

                        

                      <div class="form-group">

                        <label for="phone">Mobile<font color="#FF0000">*</font></label>

                        <input type="text" class="form-control" placeholder="Enter your mobile" id="mobile" name="mobile" value="<?php if($_POST) echo $_POST['mobile'];else echo stripslashes($rowUserDetail->mobile);?>">

                        <?php echo $err_mobile;?> </div>

                      <div class="form-group">

                        <label for="company">Company</label>

                        <textarea class="form-control" id="company" name="company" ><?php if($_POST) echo $_POST['company'];else echo stripslashes($rowUserDetail->company);?>

                </textarea>

                        <?php echo $err_company;?> </div>

                      <div class="form-group">

                        <label for="address">Address 1</label>

                        <textarea class="form-control" id="address" name="address"><?php if($_POST) echo $_POST['address'];else echo stripslashes($rowUserDetail->address);?>

                </textarea>

                        <?php echo $err_address;?> </div>

                      <div class="form-group">

                        <label for="address2">Address 2</label>

                        <textarea class="form-control" id="address2" name="address2" ><?php if($_POST) echo $_POST['address2'];else echo stripslashes($rowUserDetail->address2);?>

                </textarea>

                        <?php echo $err_address2;?> </div>

                      <div class="form-group">

                        <label for="city">City<font color="#FF0000">*</font></label>

                        <input type="text" class="form-control" placeholder="Enter your mobile" id="city" name="city" value="<?php if($_POST) echo $_POST['city'];else echo stripslashes($rowUserDetail->city);?>">

                        <?php echo $err_city;?> </div>

                      <div class="form-group">

                        <label for="location">State<font color="#FF0000">*</font></label>

                        <?php $categoryDropDown = '<select class="form-control" name="location" id="location">

                											  	<option value="">Select State</option>';

                											  $resLocation = selectSql(TBL_STATE," WHERE `status` = '1' AND `id_country` ='110'",' ORDER BY `name`');

                											  if($db->num_rows2($resLocation)){

                											  	while($resultLocation = $db->fetch_object2($resLocation)){

                													if($_REQUEST['location'] == $resultLocation->id_state){

                														$selected = 'selected="selected"';

                													}elseif($rowUserDetail->location == $resultLocation->id_state){

                														$selected = 'selected="selected"';

                													}else{

                														$selected = '';

                													}

                													$categoryDropDown .= '<option '.$selected.' value="'.$resultLocation->id_state.'">'.ucfirst($resultLocation->name).'</option>';

                												}

                											  }

                											 	echo $categoryDropDown .= '</select>';

                											  ?>

                        <?php echo $err_location;?> </div>

                      <div class="form-group">

                        <label for="zip">Zip Code <font color="#FF0000">*</font></label>

                        <input type="text" class="form-control" placeholder="Enter your zip code" id="zip" name="zip" value="<?php if($_POST) echo $_POST['zip'];else echo stripslashes($rowUserDetail->zip);?>">

                        <?php echo $err_zip;?> </div>

                      <div class="form-group">

                        <label for="skype">Skype</label>

                        <input type="text" class="form-control" placeholder="Enter your skype id" id="skype" name="skype" value="<?php if($_POST) echo $_POST['skype'];else echo stripslashes($rowUserDetail->skype);?>">

                        <?php echo $err_skype;?> </div>

                      <div class="form-group">

                        <label for="comments">About</label>

                        <textarea class="form-control" id="comments" name="comments" ><?php if($_POST) echo $_POST['comments'];else echo stripslashes($rowUserDetail->comments);?>

                </textarea>

                        <?php echo $err_comments;?> </div>
   
				
				<?php if($rowUserDetail->date_created){?>
				  
				<div class="form-group">
                  <label for="date_created">Date Created</label>
                  <input type="text" disabled="disabled" class="form-control" id="date_created"  value="<?php echo stripslashes($rowUserDetail->date_created);?>">				
                </div> 
				
				<div class="form-group">
                  <label for="last_modified">Last Updated</label>
                  <input type="text" disabled="disabled" class="form-control" id="last_modified" value="<?php echo stripslashes($rowUserDetail->last_modified);?>">				
                </div> 
				
				<div class="form-group">
                  <label for="last_modified_by">Last Updated By</label>
				   <?php $sqlUserDetail = @mysql_fetch_object(@mysql_query("SELECT `username` FROM `".TBL_USERS."` WHERE `id` = '".$rowUserDetail->last_modified_by."'"));?>
                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail->username);?>">				
                </div>  
				  
				  <?php } ?>            
              </div>
              <!-- /.box-body -->	
			 <div class="box-footer">                                       
				<input type='submit' value='<?=($_SESSION['userId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
				&nbsp;&nbsp;&nbsp;&nbsp;
			   <input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("home.php"); '>
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