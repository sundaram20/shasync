<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'view');

/////////////////////////////////////////////////////////////////////////////////////
//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){		
	$err = 0;
	
	
	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['id'])){//add
			checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'add');
			$addSql = "   	INSERT INTO `".TBL_CUSTOMER."` SET 
							`id_shop_group` = '1',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_company` = '".addslashes($_POST['company'])."',
							`designation` = '".addslashes($_POST['designation'])."',
							`title` = '".addslashes($_POST['Nametitle'])."',
							`first_name` = '".addslashes($_POST['first_name'])."',
							`last_name` = '".addslashes($_POST['last_name'])."',
							`email` = '".addslashes($_POST['email'])."',
							`dob` = '".addslashes(date('Y-m-d',strtotime($_POST['dob'])))."',
							`doa` = '".addslashes(date('Y-m-d',strtotime($_POST['doa'])))."',
							`mobile` = '".addslashes($_POST['mobile'])."',
							`type` = '2'";
			
			$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'";
			if(executeSql($addSql)){
				unset($_POST);
				$_SESSION['successMsg'] = 'New contact details has been added sucessfully.';
				header("location:customerList.php?page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'New Guest details has not been saved. Please make corrections below.';
			}
		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['id'])){//update
		
			checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'update');
			$editSql = "   	UPDATE `".TBL_CUSTOMER."` SET 
							`id_shop_group` = '1',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_company` = '".addslashes($_POST['company'])."',
							`designation` = '".addslashes($_POST['designation'])."',
							`title` = '".addslashes($_POST['Nametitle'])."',
							`first_name` = '".addslashes($_POST['first_name'])."',
							`last_name` = '".addslashes($_POST['last_name'])."',
							`email` = '".addslashes($_POST['email'])."',
							`dob` = '".addslashes(date('Y-m-d',strtotime($_POST['dob'])))."',
							`doa` = '".addslashes(date('Y-m-d',strtotime($_POST['doa'])))."',
							`mobile` = '".addslashes($_POST['mobile'])."',
							`type` = '2'";			
			$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`status` = '".addslashes($_POST['status'])."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							WHERE `id_customer` = '".addslashes(encryptor('decrypt',$_POST[id]))."'";								
			if(executeSql($editSql)){				
				$_SESSION['successMsg'] = selectColumn(TBL_CUSTOMER,'first_name'," WHERE `id_customer` = '".addslashes(encryptor('decrypt',$_POST[id]))."'").' details has been updated sucessfully.';
				header("location:customerList.php?page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = selectColumn(TBL_CUSTOMER,'first_name'," WHERE `id_customer` = '".addslashes(encryptor('decrypt',$_POST[id]))."'").' details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'Guest details has not been saved. Please make corrections.';
	}
}
// ----------cate---------
//print_r($_REQUEST);
if(!empty($_REQUEST['id']) && $_REQUEST['action']=='edit'){
	
	$sql = "  SELECT * FROM `".TBL_CUSTOMER."`
								WHERE `id_customer`= '".addslashes(encryptor('decrypt',$_REQUEST['id']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."' and type='2'";
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
       Hotel Manager
        <small>Manage Guests</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Guests</li>
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
              <h3 class="box-title"><?php echo $_REQUEST['id']==''?'Add':'Edit'?> Guest</h3>
            </div>
             
            <!-- /.box-header -->
            <!-- form start -->  			        
			 <form name="form1"  method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off">
                <input type="hidden" value="<?php echo $_REQUEST['id'];?>" name="id" />
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
          <label >Title <font color="#FF0000">*</font></label>
          <select name="Nametitle" id="Nametitle"  class="form-control input-sm" data-parsley-required >
            <option value="">-Select-</option>
            <option value="Dr.">Dr.</option>
            <option value="Miss.">Miss.</option>
            <option value="Mr.">Mr.</option>
            <option value="Mrs.">Mrs.</option>
            <option value="Ms.">Ms.</option>
            <option value="Pr.">Pr.</option>
            <option value="Prof.">Prof.</option>
            <option value="Rev.">Rev.</option>
          </select>
        </div>
					
			     <div class="form-group col-sm-4">
                  <label for="first_name">First Name<font color="#FF0000">*</font></label>
                  <input type="text" class="form-control" placeholder="Enter First name" id="first_name" name="first_name" value="<?php if($_POST) echo $_POST['first_name'];else echo stripslashes($row->first_name);?>" data-parsley-required data-parsley-type="alphanum">
				<?php echo $err_first_name;?>
                </div>                
				 <div class="form-group col-sm-4">
                  <label for="last_name">Last Name<font color="#FF0000">*</font></label>
                  <input type="text" class="form-control" placeholder="Enter Last name" id="last_name" name="last_name" value="<?php if($_POST) echo $_POST['last_name'];else echo stripslashes($row->last_name); data-parsley-required ?>" >
				<?php echo $err_last_name;?>
                </div>				

			   </div>	
				<div class="row">
									 <div class="form-group col-sm-4">
                  <label for="email">Email Id</label>
                  <input type="text" class="form-control" placeholder="Enter email id" id="email" name="email" value="<?php if($_POST) echo $_POST['email'];else echo stripslashes($row->email);?>" data-parsley-type="email"  >
				<?php echo $err_email;?>
                </div>
					
					<div class="form-group  col-sm-4">
					  <label for="mobile">Mobile Number<font color="#FF0000">*</font></label>
					  <input type="text" class="form-control" placeholder="Enter mobile number" id="mobile" name="mobile" value="<?php if($_POST) echo $_POST['mobile'];else echo stripslashes($row->mobile);?>" data-parsley-type="digits" data-parsley-length="[10, 10]" data-parsley-required>
					<?php echo $err_mobile;?>
					</div>	
					
					 <div class="form-group col-sm-4">
                  <label for="last_name">Company<font color="#FF0000">*</font></label>
                  <select class="form-control select2 itemName" name="company" id="search_name"  data-parsley-required >

                  </select>
				<?php echo $err_mobile;?>
                </div>
					
				  </div>				
			    <div class="row">	
			  			 
										 <div class="form-group col-sm-4">
          <label for="first_name">Designation <font color="#FF0000">*</font></label>
          <?php $marketDropDown = '<select class="form-control input-sm" name="designation" id="designation" data-parsley-errors-container="#designationError" data-parsley-required   >
                                 <option value="">Select Designation</option>';
                                
                                 $resCat = selectSql(TBL_DESIGNATION_MASTER," where status='1' and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name`');                   
                                 if($db->num_rows2($resCat)){
                                 while($resultCat = $db->fetch_object2($resCat)){
                                     
                                   $marketDropDown .= '<option  value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
                                 }
                                 }
                                 echo $marketDropDown .= '</select>';
                                 ?>
        </div>
			   
					
					<div class="form-group col-sm-4">
                  <label for="dob">Date of Birth</label>
                  <input type="text" class="form-control pickerdate" placeholder="Enter date of birth" id="dob" name="dob" value="<?php if($_POST) echo $_POST['dob'];else echo stripslashes(date('d-m-Y',strtotime($row->dob)));?>">
				<?php echo $err_dob;?>
                </div>
				
					<div class="form-group col-sm-4">
                  <label for="dob">Date of Anniversary</label>
                  <input type="text" class="form-control pickerdate" placeholder="Enter date of anniversary" id="doa" name="doa" value="<?php if($_POST) echo $_POST['doa'];else echo stripslashes(date('d-m-Y',strtotime($row->doa)));?>">
				<?php echo $err_doa;?>
                </div>
	

			<div class="row">		
				<div class="form-group col-sm-4" style="margin-top:10px; padding-left:30px">
                  <label for="status">Status </label>
                 <input type="radio" class="flat-red"  <?php if($_POST['status'] == '1'){echo "checked";}else{if($row->status == 1)echo "checked";}?> value="1" name="status"/> Active 
				 <input type="radio" class="flat-red" <?php if($_POST['status'] == '0'){echo "checked";}else{if($row->status == 0)echo "checked";}?> value="0" name="status"/> Inactive
				 <?php echo $err_status;?>
                </div>
			  </div>			  
				
		
				<?php if($row->date_created){?>
				<div class="row">  
				<div class="form-group col-sm-4" style="margin-top:10px; padding-left:30px; ">
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
              </div>
              <!-- /.box-body -->	
			 <div class="box-footer">                                       
				<input type='submit' value='<?=($_REQUEST['id']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
				&nbsp;&nbsp;&nbsp;&nbsp;
			   <input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("customerList.php?page=<?php echo $_GET['page']; ?>"); '>
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
<script>
	comCheck = () =>{
		window.location.href='https://www.roomstatushub.in/sync/adminpanel/index.php';
	}
     $('.itemName').select2({
        placeholder: 'Select Company',
        ajax: {
          url: "ajax/ajaxSearchCompanyName.php",
          dataType: 'json',
          delay: 50,
		  processResults: function (data) {
			  console.log(data[0].id);
			  //data1 = JSON.parse(data);
			  //alert(data1);
			 if(data[0].id){
			 	return { results: data};
			 }
			 else{
				comCheck(); 
				return { results: data};
				
			 }
          },
           cache: true
        }//ajax end
		
      });
	</script>

