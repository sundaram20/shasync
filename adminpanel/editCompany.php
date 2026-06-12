<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_COMPANY,'view');

//debugeData($_SESSION['teamMemberAreas']);
//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){
	$err = 0;
	/*if(empty($_POST['name'])){
		$err++;
		$err_name = '<font style="color:red;font-weight:normal;" ><br>Please enter Company name.</font>';
	}else if($db->num_rows2(selectSql(TBL_COMPANY,"WHERE `id_company` NOT IN('".addslashes(encryptor('decrypt',$_POST[eId]))."') and `id_shop` = '".addslashes($_SESSION['shop'])."'  AND `name` = '".addslashes($_POST['name'])."' AND city='".$_POST['city']."' ",''))){
		$err++;
		$err_name = '<font style="color:red;font-weight:normal;" ><br>Company Name all-ready exists in our database.</font>';
	}*/
	if(empty($_POST['address'])){
		$err++;
		$err_address = '<font style="color:red;font-weight:normal;" ><br>Please enter address.</font>';
	}
	if(empty($_POST['city'])){
		$err++;
		$err_city = '<font style="color:red;font-weight:normal;" ><br>Please enter city.</font>';
	}	
	if(empty($_POST['mobile'])){
		$err++;
		$err_mobile = '<font style="color:red;font-weight:normal;" ><br>Please enter mobile number.</font>';
	}
	if(empty($_POST['email'])){
		$err++;
		$err_email = '<font style="color:red;font-weight:normal;" ><br>Please enter email id.</font>';
	}/*elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
		$err++;
		$err_email = '<font style="color:red;font-weight:normal;" ><br>Please enter valid email id.</font>';
	}
    */
	/*if(empty($_POST['eId'])){
		$chkSql="SELECT id_company FROM `".TBL_COMPANY."` WHERE UPPER(name)='".strtoupper($_POST['name'])."' AND id_shop='".$_SESSION['shop']."' AND area='".$_POST['area']."' AND status=1 ";

		$resChk = mysqli_query($connNew,$chkSql);

		if(mysqli_num_rows($resChk)>0){
			$_SESSION['errorMsg'] = 'Duplicate Found ';
			$err++;
		}
	}*/	


	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add
			checkUserLevelPermission($_SESSION['userLevel'],TBL_COMPANY,'add');



			$addSql = "   	INSERT INTO `".TBL_COMPANY."` SET 
							`id_shop_group` = '1',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_default_group` = '".addslashes($_POST['id_default_group'])."',
							`id_lang` = '1',
							`id_rate_level` = '".addslashes(implode(',',$_POST['id_rate_level']))."',
							`name` = '".addslashes(trim($_POST['name']))."',
							`email` = '".addslashes($_POST['email'])."',
							`credit_limit`='".addslashes($_POST['credit_limit'])."',
							`secondary_email` = '".addslashes($_POST['secondary_email'])."',
							`id_country` = '".addslashes($_POST['id_country'])."',
							`id_state` = '".addslashes($_POST['id_state'])."',
							`postcode` = '".addslashes($_POST['postcode'])."',
							`city` = '".addslashes($_POST['city'])."',
							`other_state` = '".addslashes($_POST['other_state'])."',
							`address` = '".addslashes($_POST['address'])."',
							`phone` = '".addslashes($_POST['phone'])."',
							`mobile` = '".addslashes($_POST['mobile'])."',							
							`fax` = '".addslashes($_POST['fax'])."',
							`area` = '".addslashes($_POST['area'])."',
							`company_credibility` = '".addslashes($_POST['company_credibility'])."',
							`deals_in` = '".addslashes($_POST['deals_in'])."',
							`details` = '".addslashes($_POST['details'])."',
							`credit_form` = '".trim($_POST['credithidden'])."',
							`created_by` = '".$_SESSION['userId']."',
							`booking` = '".addslashes($_POST['booking'])."'";
			$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'";
			if(executeSql($addSql)){
				unset($_POST);
				$lastInsertId= $db->insert_id();
				$_SESSION['successMsg'] = 'New Company details has been added sucessfully.';
				header("location:editCompany.php?eId=".addslashes(encryptor('encrypt',$lastInsertId))."&action=edit&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Company details has not been saved. Please make corrections below.';
			}
		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update
			checkUserLevelPermission($_SESSION['userLevel'],TBL_COMPANY,'update');
			$editSql = "   	UPDATE `".TBL_COMPANY."` SET 
							`id_shop_group` = '1',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_default_group` = '".addslashes($_POST['id_default_group'])."',
							`id_lang` = '1',
							`id_rate_level` = '".addslashes(implode(',',$_POST['id_rate_level']))."',
							`name` = '".addslashes(trim($_POST['name']))."',
							`email` = '".addslashes($_POST['email'])."',
							`secondary_email` = '".addslashes($_POST['secondary_email'])."',
							`id_country` = '".addslashes($_POST['id_country'])."',
							`credit_limit`='".addslashes($_POST['credit_limit'])."',
							`id_state` = '".addslashes($_POST['id_state'])."',
							`postcode` = '".addslashes($_POST['postcode'])."',
							`city` = '".addslashes($_POST['city'])."',
							`other_state` = '".addslashes($_POST['other_state'])."',
							`address` = '".addslashes($_POST['address'])."',
							`phone` = '".addslashes($_POST['phone'])."',
							`mobile` = '".addslashes($_POST['mobile'])."',							
							`fax` = '".addslashes($_POST['fax'])."',
							`area` = '".addslashes($_POST['area'])."',
							`company_credibility` = '".addslashes($_POST['company_credibility'])."',
							`deals_in` = '".addslashes($_POST['deals_in'])."',
							`details` = '".addslashes($_POST['details'])."',	
							`credit_form` = '".trim($_POST['credithidden'])."',						
							`booking` = '".addslashes($_POST['booking'])."'";
			$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`status` = '".addslashes($_POST['status'])."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							WHERE `id_company` = '".addslashes(encryptor('decrypt',$_POST[eId]))."'";
							
								
			if(executeSql($editSql)){
				$_SESSION['successMsg'] = selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".addslashes(encryptor('decrypt',$_POST[eId]))."'").' details has been updated sucessfully.';
				header("location:editCompany.php?eId=".$_GET['eId']."&action=edit&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".addslashes(encryptor('decrypt',$_POST[eId]))."'").' details has not been saved. Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'Company details has not been saved. Please make corrections.';
	}
}
// ----------cate---------
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
	$sql = "  SELECT * FROM `".TBL_COMPANY."`
								WHERE `id_company` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
	$db->query($sql);
	if($db->num_rows() > 0){
		$row = $db->fetch_object();
	}

	$userType=selectColumn(TBL_USERS,'user_type','WHERE id="'.$_SESSION['userId'].'" ');
	if($row->created_by != $_SESSION['userId'] && $userType==2 ){
		$disable="disabled='disabled'";
	}						
}	
							

?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>
<style type="text/css">
	.fa-cloud-upload:hover{
		color: #3C8DBC;
	}

	.fa-cloud-download:hover{
		color: #3C8DBC;
	}
</style>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Company Manager <small>Manage Company</small> </h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Manage Company</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">

    	 <!--########## Credit Form Upload jump#######-->  
    	   
    	   <!-- Modal -->
    	     <div class="modal fade" id="creditFormModal" role="dialog" >
    	       <div class="modal-dialog">
    	       
    	         <!-- Modal content-->
    	         <div class="modal-content" style="width: 300px; margin: 0px auto;">
    	           <div class="modal-header">
    	             <button type="button" class="close" data-dismiss="modal">&times;</button>
    	             <h4 class="modal-title">Upload Credit Form </h4><br>
    	             <span id="returnTxt" style="color: Green;"></span>
    	           </div>
    	           <div class="modal-body">
    	             <form name="creditimport" method="post" enctype="multipart/form-data" id="creditimport">
    	               <div >
    	                 <label for="file">Choose File : <span style="color: red;">*</span></label>
    	                 <input type="file" name="creditImport" class="form-control" id="creditImport">
    	               </div><br>
    	               <div >
    	                 <input type="submit" value="uplaod" name="submit" class="btn btn-primary" id="importCredit"><span style="color:red;margin-left:50px; ">*</span> = Required 
    	                 Field<br><span id="returnTxt" style="color: #3C8DBC;margin-left:75px;">File size should be less than 5MB.</span>
    	               </div>

    	            </form>
    	           </div>
    	         </div>
    	         
    	       </div>
    	     </div>
    	     
    	   
    	<!--########## credit form uplaod  Modal End#######-->  





      <!-- left column -->
      <div class="col-md-12">
        <!-- general form elements -->
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="active" ><a href="#tab_1" data-toggle="tab">Overview</a></li>
            <li><a href="manageCustomer.php?eId=<?php echo $_REQUEST['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>">Contacts</a></li>
          </ul>
          <div class="box-header with-border">
            <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> Company : <a><?php echo selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'"); ?></a></h3>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <form name="form1"  method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off" >
            <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" />
            <div class="form-group has-error" align="center">
              <?php if($_SESSION['errorMsg']){?>
              <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
              <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
              <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
              <?php unset($_SESSION['successMsg']);}?>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="form-group col-sm-4">
                  <label for="id_default_group">Company Group<font color="#FF0000">*</font></label>
                  <?php $categoryDropDown = '<select class="form-control select2" name="id_default_group" id="id_default_group" data-parsley-required data-parsley-errors-container="#err_default_group">
					<option value="">Select Company  Group</option>';
											  $resCat = selectSql(TBL_GROUP," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `id_group`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['id_default_group'] == $resultCat->id_group){
														$selected = 'selected="selected"';
													}elseif($row->id_default_group == $resultCat->id_group){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id_group.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $categoryDropDown .= '</select>';
											  ?>
                  <span id="err_default_group"><?php echo $err_default_group;?></span> </div>
                <div class="form-group col-sm-4">
                  <label for="name">Company Name<font color="#FF0000">*</font></label>
                  <input autocomplete="off" type="text" class="form-control awesomplete" data-list="#mylist" placeholder="Enter Company name" id="name" name="name" value="<?php if($_POST) echo $_POST['name'];else echo stripslashes($row->name);?>" data-parsley-required >
                  <ul id="mylist" style="display:none;">
                    <?php  $resCat = selectSql(TBL_COMPANY," where status=1  and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `id_company`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													$companyDropDown .= '<li>'.ucfirst($resultCat->name).'-'.ucfirst($resultCat->city).'</li>';
												}
											  }
											 	echo $companyDropDown;
					?>
                  </ul>
                  <?php echo $err_name;?> </div>
                <div class="form-group col-sm-4">
                  <label for="email">Email Id<font color="#FF0000">*</font></label>
                  <input type="email" class="form-control" placeholder="Enter email id" id="email" name="email" value="<?php if($_POST) echo $_POST['email'];else echo $row->email;?>" data-parsley-type="email" data-parsley-required >
                  <?php echo $err_email;?> </div>
              </div>
              <div class="row">
                <div class="form-group col-sm-4">
                  <label for="secondary_email">Seconday Email</label>
                  <input type="text" class="form-control" placeholder="Enter seconday email id" id="secondary_email" name="secondary_email" value="<?php if($_POST) echo $_POST['secondary_email'];else echo $row->secondary_email;?>" data-parsley-type="email"  >
                  <?php echo $err_email;?> </div>
                <div class="form-group  col-sm-4">
                  <label for="phone">Phone Number</label>
                  <input type="text" class="form-control" placeholder="Enter phone number" id="phone" name="phone" value="<?php if($_POST) echo $_POST['phone'];else echo $row->phone;?>" >
                  <?php echo $err_phone;?> </div>
                <div class="form-group  col-sm-4">
                  <label for="mobile">Mobile Number<font color="#FF0000">*</font></label>
                  <input type="text" class="form-control" placeholder="Enter mobile number" id="mobile" name="mobile" value="<?php if($_POST) echo $_POST['mobile'];else echo $row->mobile;?>" data-parsley-type="digits" data-parsley-length="[10, 10]" data-parsley-required>
                  <?php // echo $err_mobile;?> </div>
              </div>
              <div class="row">
                <div class="form-group col-sm-4">
                  <label for="fax">GST Number</label>
                  <input type="text" class="form-control" placeholder="Enter fax number" id="fax" name="fax" value="<?php if($_POST) echo $_POST['fax'];else echo $row->fax;?>">
                  <?php echo $err_fax;?> </div>
                <div class="form-group col-sm-4">
                  <label for="address">Address<font color="#FF0000">*</font></label>
                  <textarea class="form-control" name="address" id="address"  rows="1" placeholder="Enter Address" data-parsley-required><?php if($_POST) echo $_POST['address'];else echo $row->address;?>
</textarea>
                  <?php echo $err_address;?> </div>
               <!-- <div class="form-group col-sm-4">
                  <label for="city">City<font color="#FF0000">*</font></label>
                  <input type="text" class="form-control" placeholder="Enter city" id="city" name="city" value="<?php if($_POST) echo $_POST['city'];else echo $row->city;?>" data-parsley-required>
                  <?php echo $err_city;?> </div>
              -->

               <div class="form-group col-sm-4">
                  <label for="name">City<font color="#FF0000">*</font></label>
                  <input autocomplete="off" type="text" class="form-control awesomplete" data-list="#citylist" placeholder="Enter City" id="city" name="city" value="<?php if($_POST) echo $_POST['city'];else echo stripslashes($row->city);?>" data-parsley-required >
                  <ul id="citylist" style="display:none;">
                    <?php  //$resCat = selectSql(TBL_COMPANY,'distinct',"  where status=1  and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `id_company`');

                    $citySql="SELECT DISTINCT city from `".TBL_COMPANY."` WHERE  status=1  and id_shop='".addslashes($_SESSION['shop'])."' ORDER BY `id_company`";

		$resCat = mysqli_query($connNew,$citySql);

                    	


											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													$cityDropDown .= '<li>'.ucfirst($resultCat->city).'</li>';
												}
											  }
											 echo $cityDropDown;
											 	
					?>
                  </ul>
                  <?php echo $err_name;?> </div>

	              <!--<div class="form-group col-sm-4">

			          <label for="city">City </label>

			          <select class="form-control select2 itemName" name="city" id="city"   >

			          </select>
	           </div> --> 

              </div>
              <div class="row">
                <div class="form-group col-sm-4">
                  <label for="id_country" >Country<font color="#FF0000">*</font></label>
                  <select class="form-control select2" name="id_country" id="id_country" data-parsley-errors-container="#countryError" onchange="getState(this.value,'','');" required="required" data-parsley-required>
                    <option value="">Select Country</option>
                    <?php 
									$resCat = selectSql(TBL_COUNTRY_LANG,"where id_lang='1' ",' ORDER BY `name`');
												  if(num_rows($resCat)){
													while($resultCat = $db->fetch_object2($resCat)){
														if($_REQUEST['id_country'] == $resultCat->id_country){
															$selected = 'selected="selected"';
														}elseif($row->id_country == $resultCat->id_country){
														$selected = 'selected="selected"';
														}elseif(110 == $resultCat->id_country){
														$selected = 'selected="selected"';
														}else{
															$selected = '';
														}
														$countryDropDown .= '<option '.$selected.' value="'.$resultCat->id_country.'">'.ucfirst($resultCat->name).'</option>';
													}
												  }
												  echo $countryDropDown;
									
									 ?>
                  </select>
                  <span id="countryError"></span> </div>
                <div class="form-group col-sm-4">
                  <label for="id_state">State <font color="#FF0000">*</font></label>
                  <div id="state">
                    <select class="form-control" name="id_state" id="id_state" data-parsley-errors-container="#stateError">
                      <option value="">Please Select Country</option>
                    </select>
                  </div>
                  <span id="stateError"></span> </div>
                <div class="form-group col-sm-4">
                  <label for="postcode">Pincode</label>
                  <input type="text" class="form-control" placeholder="Enter pincode" id="postcode" name="postcode" value="<?php if($_POST) echo $_POST['postcode'];else echo $row->postcode;?>">
                  <?php echo $err_postcode;?> </div>
              </div>
              <div class="row">
                <div class="form-group col-sm-4">
                  <label for="details">Details</label>
                  <textarea class="form-control" name="details" id="details"  rows="1" placeholder="Enter Details" automcomplete="off"><?php if($_POST) echo $_POST['details'];else echo $row->details;?>
</textarea>
                  <?php echo $err_details;?> </div>
                <div class="form-group col-sm-3">
                  <label for="area">Area<font color="#FF0000">*</font></label>
                  <?php $areaDropDown = '<select class="form-control select2" name="area" id="area" data-parsley-required onChange="areaOnChg(this.value);">
								<option value="">Select Area</option>';
											  $resCat = selectSql(TBL_AREAS," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['area'] == $resultCat->id){
														$selected = 'selected="selected"';
													}elseif($row->area == $resultCat->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$areaDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $areaDropDown .= '</select>';
											  ?>
                  <span id="areaError"><?php echo $err_area;?></span> 
              <span id="areaExe" style="color: red"></span>	</div>
                

                <div class="form-group col-sm-3">
                  <label for="company_credibility">Company Credibility</label>
                  <select class="form-control" onChange="openCreditLimit(this.value);" name="company_credibility" id="company_credibility" data-parsley-errors-container="#company_credibilityError" data-parsley-required>
                    <option value="1" <?php if($_REQUEST['company_credibility']=='1'){echo 'selected="selected"';}elseif($row->company_credibility=='1'){echo 'selected="selected"';} ?>>Credit Allowed</option>
                    <option value="2"  <?php if($_REQUEST['company_credibility']=='2'){echo 'selected="selected"';}elseif($row->company_credibility!='1'){echo 'selected="selected"';}?>>Credit Not Allowed</option>
                  </select>
                  <span id="company_credibilityError"><?php echo $err_company_credibility;?></span> </div>
              	<?php 
              	if($row->company_credibility=='1'){
              		$dispalyBox ='style="display:visible"';
              	}
              	else{
              		$dispalyBox ='style="display:none"';
              	}
              	?>


              	<div  <?php echo $dispalyBox;?> class="form-group col-sm-2"  id="credit_limit">
                  <label for="company_credibility">Credit Limit (In Lacs)</label>
                  <input class="form-control"  type="text" name="credit_limit" value="<?php echo $row->credit_limit; ?>">
                  </select>
                </div>


                </div>
              <div class="row">
                <div class="form-group col-sm-4">
                  <label for="deals_in">Deals In</label>
                  <?php $dealsInDropDown = '<select class="form-control select2" name="deals_in" id="deals_in"  data-parsley-errors-container="#deals_inError">
						<option value="">Select Company Domain</option>';
											  $resCat = selectSql(TBL_COMPANY_AREA," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['deals_in'] == $resultCat->id){
														$selected = 'selected="selected"';
													}elseif($row->deals_in == $resultCat->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$dealsInDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $dealsInDropDown .= '</select>';
											  ?>
                  <span id="deals_inError"><?php echo $err_deals_in;?></span> </div>
				  
                <div class="form-group col-sm-4" style="visibility: hidden;">
                  <label for="rate_level_id">Rate Level</label>
                  <select class="form-control select2" name="id_rate_level[]" id="id_rate_level" multiple="multiple"  >
                    <option value="">Select Rate Level</option>
                    <?php $resCat = selectSql(TBL_RATE_LEVEL," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');
					$iCounterActions = 0;
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
												$chkSql = "SELECT * FROM `".TBL_COMPANY."` WHERE FIND_IN_SET('".$resultCat->id."',id_rate_level ) and id_company='".addslashes(encryptor('decrypt',$_REQUEST['eId']))."' ";
													if($db->num_rows2(executeSql($chkSql)) > 0){
														$selected = 'selected="selected"';
													}else if($_POST[$selected]){
														$selected = 'selected="selected"';
													}													
													else{
														$selected = '';
													}
													$levelData .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
													$iCounterActions++;
												}
											  }
											 echo $levelData;;
											  ?>
                  </select>
                  

                 
                  <span id="rate_level_idError"></span> </div>

                  
                  <!--- Credit form part-->
                  <input type="hidden" name="credithidden" id="credithidden" value="<?php if($row->credit_form != '')echo $row->credit_form; else echo '' ; ?>">
                  <div class="form-group col-sm-3">
                      	 <label for="creditform">Credit Form</label><br>
                           	<i  class="fa fa-cloud-upload fa-3x" value="" data-toggle="modal" data-target="#creditFormModal"></i>&nbsp Upload

                           	<?php
                           	if($row->credit_form != '')
                           		$link = "ajax/ajaxCreditFormDownload.php?fileName=".$row->credit_form;
                           	else
                           		$link = "#";
                           	?>

                           	<a style="color:#333333;" href="<?php echo $link;?>"><i  class="fa fa-cloud-download fa-3x" value="" ></i></a> &nbsp Download
                   </div>
                   <!--- Credit form part End-->

                      
                  </div>
              </div>
			  

			  <div class="row">
			  
			  	  
			  
			  <!--<div class="form-group col-sm-4" style="margin-top:10px;">
                  <label for="booking">Booking &nbsp;&nbsp;&nbsp; </label>
                  <input type="checkbox" class="flat-red"  id="booking" name="booking" value="1" <?php if($_POST['booking']=='1'){echo 'checked="checked"'; }else if(stripslashes($row->booking)=='1'){echo 'checked="checked"'; } ?>>
                  <?php echo $err_is_online_booking;?> </div>-->
				  
				  
                <?php 
				 
				  if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){?>
                <div class="form-group col-sm-4" style="margin-top:10px;">
                  <label for="status">Status </label>
                  <input type="radio" class="flat-red"  <?php if($_POST['status'] == '1'){echo "checked";}else{if($row->status == 1)echo "checked";}?> value="1" name="status"/>
                  Active
                  <input type="radio" class="flat-red" <?php if($_POST['status'] == '0'){echo "checked";}else{if($row->status == 0)echo "checked";}?> value="0" name="status"/>
                  Inactive <?php echo $err_status;?> </div>



			  <?php }else{?>
              
              <div class="form-group col-sm-4" style="margin-top:10px;">
                  <label for="status">Status </label>
                  <input type="radio" class="flat-red"  <?php echo 'checked="checked"'; ?> value="1" name="status"/>
                  Active
                  <input type="radio" class="flat-red" value="0" name="status"/>
                  Inactive <?php echo $err_status;?> </div>
              
              
               			  <?php } ?>
</div>			  
			  
              <?php if($row->date_created){?>
              <div class="row">
                <div class="form-group col-sm-4">
                  <label for="date_created">Date Created</label>
                  <input type="text" disabled="disabled" class="form-control" id="date_created"  value="<?php echo stripslashes(dateformat($row->date_created));?>">
                </div>
                <div class="form-group col-sm-4">
                  <label for="last_modified">Last Updated</label>
                  <input type="text" disabled="disabled" class="form-control" id="last_modified" value="<?php echo stripslashes(dateformat($row->last_modified));?>">
                </div>
                <div class="form-group col-sm-4">
                  <label for="last_modified_by">Last Updated By</label>
                  <?php $sqlUserDetail = $db->fetch_obj2(selectSql(TBL_USERS,"WHERE `id` = '".$row->last_modified_by."'",''));?>
                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail->username);?>">
                </div>
              </div>
              <?php } ?>
            
            <!-- /.box-body -->
            <div class="box-footer">
              <input type='submit' <?php echo $disable;?> value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
              &nbsp;&nbsp;&nbsp;&nbsp;
              <a   class="btn btn-default" href='manageCompany.php'>Cancel</a>
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
  window.onload = function() { getState(<?php if($_REQUEST['id_country']){echo "'".$_REQUEST['id_country']."'";}elseif($row->id_country != ''){echo "'".$row->id_country."'";}else { echo "'"."'";} ?>,<?php if($_REQUEST['id_state']){echo "'".$_REQUEST['id_state']."'";}elseif($row->id_state != ''){echo "'".$row->id_state."'";}else { echo "'"."'";} ?>,<?php if($_REQUEST['other_state'] != ''){echo "'".$_REQUEST['other_state']."'";}elseif($row->other_state != ''){echo "'".$row->other_state."'";}else { echo "'"."'";} ?>); };
  </script>
<?php include_once("includes/footer.php")?>

<script type="text/javascript">
	$("document").ready(function(){
		var areaId = $("#area").val();
		 $.ajax({
		 type        : 'POST',
		 url         : 'ajax/ajaxAreaExecutive.php', 
		 data        : 'areaId='+areaId,
		 success     : function(data){
		   $("#areaExe").html(data);
		 } 
		})

		
	});
	function areaOnChg(id){
			var areaId = id;
			 $.ajax({
			 type        : 'POST',
			 url         : 'ajax/ajaxAreaExecutive.php', 
			 data        : 'areaId='+areaId,
			 success     : function(data){
			   $("#areaExe").html(data);
			 } 
			})
		} 
</script>

<script type="text/javascript">
	function openPage(){
		window.location = "manageCompany.php";
	}
</script>

<script type="text/javascript">
	//jump
	$("document").ready(function(){
		$("#importCredit").click(function(){
        $("#creditimport").submit(function(e){
          e.preventDefault();	
          var fileName = $("#creditImport").val();
          console.log(fileName);
          if(fileName == ""){
          	alert("Kindly Select a file.");
          }  
          else{
            $.ajax({
            type        : 'POST',
            contentType : false,
            processData : false,
            dataType	:'json', 
            url         : 'ajax/ajaxCreditFormUpload.php', 
            data        : new FormData(this),
            success     : function(data){
              $("#returnTxt").html(data[0]);
              $("#credithidden").val(data[1]);
            } 
           })
          }
        });
      });

	/*$(".fa-cloud-download").click(function(){
		var fileName = $("#credithidden").val();
		console.log(fileName);
		if(fileName == ""){
			alert("Credit Form not uploaded yet !")
		}
		else{
			$.ajax({
            type        : 'POST', 
            url         : 'ajax/ajaxCreditFormDownload.php', 
            data        : 'fileName='+fileName,
            success     : function(data){
              alert()
            } 
           })
		}
	});*/
	});
	function openCreditLimit(val){
		if(val==1){
			$("#credit_limit").show();
		}
		else{
			$("#credit_limit").hide();
		}
	}



</script>
