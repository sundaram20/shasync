<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_DOCUMENT_CONFIG,'view');
$image_path = $UPLOAD_FILES.'/users/';
$image_display_path = $UPLOAD_FILES_PATH ."/users/";

/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";
exit;*/
//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){
	$err = 0;	
	
	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add
			checkUserLevelPermission($_SESSION['userLevel'],TBL_DOCUMENT_CONFIG,'add');
			$chk = selectColumn(TBL_DOCUMENT_CONFIG,'id','WHERE id_shop="'.$_SESSION['shop'].'" AND doc_type="'.$_REQUEST['docType'].'" ');

			if($chk>0){
				$_SESSION['errorMsg'] = 'Document Type already exists.Use edit option to modify.';

				$err++;
				header("location:manageDocumentConfig.php");
				exit;
			}

			$addSql = "   	INSERT INTO `".TBL_DOCUMENT_CONFIG."` SET 
							`doc_type` = '".addslashes($_POST['docType'])."',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`rateletter_url` = '".addslashes($_POST['rateletter_url'])."',
							`allow_multiple_date_rates`= '".addslashes($_POST['allow_multiple_date_rates'])."',
							`ids_rate_level` = '".addslashes(implode(',',$_REQUEST['rate_level']))."',
							`ids_rate_points` = '".addslashes(implode(',',$_REQUEST['rate_points']))."',
							`id_mail_header` = '".addslashes($_REQUEST['mailHeader'])."',
							`id_mail_content` = '".addslashes($_REQUEST['mailContent'])."',
							`id_mail_footer` = '".addslashes($_REQUEST['mailFooter'])."',	
							`id_shop_group` = '1'";
			$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_created_by` = '".$_SESSION['userId']."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'";
							
			if(executeSql($addSql)){
				
				for($i=0;$i<count($_REQUEST['rate_level']);$i++){

					$rateSql="INSERT INTO ".TBL_DOCUMENT_CONFIG_DETAILS." SET 
						id_doc_type='".$_POST['docType']."'
						,id_shop='".$_SESSION['shop']."'
						,id_rate_level='".$_REQUEST['rate_level'][$i]."'
						 ,id_general_term='".$_REQUEST['generalterms'][$i]."' ";
					
					executeSql($rateSql);

				}

				
				$_SESSION['successMsg'] = 'New Document Config details has been added sucessfully.';
				
				header("location:manageDocumentConfig.php");
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Document Config has not been saved. Please make corrections below.';
			}
		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update
			checkUserLevelPermission($_SESSION['userLevel'],TBL_DOCUMENT_CONFIG,'update');
			 $editSql = "   	UPDATE `".TBL_DOCUMENT_CONFIG."` SET 
							`doc_type` = '".addslashes($_POST['docType'])."',
							`rateletter_url` = '".addslashes($_POST['rateletter_url'])."',
							`allow_multiple_date_rates`= '".addslashes($_POST['allow_multiple_date_rates'])."',
							`ids_rate_level` = '".addslashes(implode(',',$_REQUEST['rate_level']))."',
							`ids_rate_points` = '".addslashes(implode(',',$_REQUEST['rate_points']))."',
							`id_mail_header` = '".addslashes($_REQUEST['mailHeader'])."',
							`id_mail_content` = '".addslashes($_REQUEST['mailContent'])."',
							`id_mail_footer` = '".addslashes($_REQUEST['mailFooter'])."'";
			$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`status` = '".addslashes($_POST['status'])."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							WHERE `id` = '".addslashes(encryptor('decrypt',$_POST[eId]))."'";
								
			if(executeSql($editSql)){
				
				executeSql("DELETE from `".TBL_DOCUMENT_CONFIG_DETAILS."` where id_doc_type='".$_REQUEST['docType']."' ");
	
				for($i=0;$i<count($_REQUEST['rate_level']);$i++){
					$chkLev = selectColumn(TBL_DOCUMENT_CONFIG_DETAILS,'id','WHERE id_rate_level="'.$_REQUEST['rate_level'][$i].'" AND id_doc_type="'.$_REQUEST['docType'].'" ');
					if($chkLev>0){
						//print_r($_REQUEST);
						//echo '===='.$_REQUEST['rate_level'][$i];
					/*$rateSql="UPDATE ".TBL_DOCUMENT_CONFIG_DETAILS." SET 
						id_general_term='".$_REQUEST['generalterms'][$i]."' 
						 WHERE id_doc_type='".$_REQUEST['docType']."' AND
						 id_rate_level=".$_REQUEST['rate_level'][$i]." AND id_shop=".$_SESSION['shop']."  ";
					}*/
					
					$rateSql="INSERT INTO ".TBL_DOCUMENT_CONFIG_DETAILS." SET 
						id_doc_type='".$_POST['docType']."'
						,id_shop='".$_SESSION['shop']."'
						,id_rate_level='".$_REQUEST['rate_level'][$i]."'
						 ,id_general_term='".$_REQUEST['generalterms'][$i]."' ";
					
					executeSql($rateSql);
				}
					else{
						$rateSql="INSERT INTO ".TBL_DOCUMENT_CONFIG_DETAILS." SET 
						id_doc_type='".$_POST['docType']."'
						,id_shop='".$_SESSION['shop']."'
						,id_rate_level='".$_REQUEST['rate_level'][$i]."'
						 ,id_general_term='".$_REQUEST['generalterms'][$i]."' ";
					}
					executeSql($rateSql);

				}

				$_SESSION['successMsg'] = 'Document Config  details has been updated sucessfully.';
				header("location:manageDocumentConfig.php?&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Documnet Config  details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'Document Config details has not been saved.Please make corrections.';
	}
}
// ----------cate---------
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
	$sql = " SELECT * FROM `".TBL_DOCUMENT_CONFIG."` WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
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
        Attributes Manager
        <small>Document Config Master</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Document Config Master</li>
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
              <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> Document Config</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->  			        
			 <form name="form1"  method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off">
                <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" />
					<div class="form-group has-error">
						<?php if($_SESSION['errorMsg']){?>
						 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
						<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
					 	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
						<?php unset($_SESSION['successMsg']);}?>
					 </div>
              <div class="box-body">
                
				 <div class="form-group col-md-6">
                  <label for="name">Document Type<font color="#FF0000">*</font></label>
                  <select name="docType" data-parsley-required class="form-control select2">
                  	<option value="">Select Document Type</option>
                  	<option <?php echo ($row->doc_type==1?'selected="selected"':'');?> value="1">Rate Letter</option>
                  	<option <?php echo ($row->doc_type==2?'selected="selected"':'');?> value="2">Rate Letter Unit</option>
                  	
                  </select>
                </div>
                	     	
                
            	<div class="form-group col-md-6">
            		
                  <label for="userlevelId">Default Rate Points<font color="#FF0000">*</font></label>
				  <select data-parsley-required class="form-control select2" name="rate_points[]"  id="rate_points" multiple="multiple" data-parsley-errors-container="#rate_pointsError">				  
                  <?php 
					$sqlUserActions = selectSql(TBL_RATE_POINTS," where  status='1' and id_shop='".$_SESSION['shop']."' ",'');
					$iCounterActions = 0;
					while($resUserActions = $db->fetch_object2($sqlUserActions)){
						
						$chkSql = "SELECT * FROM `".TBL_DOCUMENT_CONFIG."` WHERE FIND_IN_SET('".$resUserActions->id."',ids_rate_points) and id='".encryptor('decrypt',$_REQUEST['eId'])."' ";

						if($db->num_rows2(executeSql($chkSql)) > 0){
							$selected = 'selected="selected"';
						}else if($_POST[$selected]){
						$selected = 'selected="selected"';
						}													
						else{
							$selected = '';
						}
						echo '<option '.$selected.' value="'.$resUserActions->id.'">'.$resUserActions->title.'</option>';
						
						$iCounterActions++;
					}
					?>
					</select>
                    
                </div>
                <hr width="100%" height="5px;">
                <div class="col-md-12">
                	 <h4>Default Mail Settings</h4>
                </div>
               	<div class="form-group col-md-4">
            		<label for="general_term" >Mail Header</label>
            		<select class="form-control select2" name="mailHeader"  >
                			<option value="">Select Mail Header</option>
                			<?php 

								$resHeadCat = selectSql(TBL_RATE_MAIL_FORMAT,"where status='1' AND type=1 and id_shop='".addslashes($_SESSION['shop'])."' ");
									if(num_rows($resHeadCat)){
										while($resultHeadCat = $db->fetch_object2($resHeadCat)){
										if($row->id_mail_header == $resultHeadCat->id){
											$selected = 'selected="selected"';
										}else{
											$selected = '';
										}
										$mailDropDown .= '<option '.$selected.' value="'.$resultHeadCat->id.'">'.$resultHeadCat->name.'</option>';
									}
								}
							echo $mailDropDown;
									
							?>
              			</select>
               		
               		 </div>

				<div class="form-group col-md-4">
            		<label for="general_term" >Mail Content</label>
            		<select class="form-control select2" name="mailContent"   >
                			<option value="">Select Mail Content</option>
                			<?php 
								$resCat = selectSql(TBL_RATE_MAIL_FORMAT,"where status='1' and type=2 and id_shop='".addslashes($_SESSION['shop'])."' ");
									if(num_rows($resCat)){
										while($resultCat = $db->fetch_object2($resCat)){
										if($row->id_mail_content == $resultCat->id){
											$selected = 'selected="selected"';
										}else{
											$selected = '';
										}
										$contDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.$resultCat->name.'</option>';
									}
								}
							echo $contDropDown;
									
							?>
              			</select>
               		
               		 </div>

				<div class="form-group col-md-4">
            		<label for="general_term" >Mail Footer</label>
            		<select class="form-control select2" name="mailFooter">
                			<option value="">Select Mail Footer</option>
                			<?php 
								$resCat = selectSql(TBL_RATE_MAIL_FORMAT,"where status='1' and type=3 and id_shop='".addslashes($_SESSION['shop'])."' ");
									if(num_rows($resCat)){
										while($resultCat = $db->fetch_object2($resCat)){
										if($row->id_mail_footer == $resultCat->id){
											$selected = 'selected="selected"';
										}else{
											$selected = '';
										}
										$footDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.$resultCat->name.'</option>';
									}
								}
							echo $footDropDown;
									
							?>
              			</select>
               		
               		 </div>
               		 <hr width="100%" height="5px;">
                     
                     
                  <div class="form-group col-md-12">
                  <label for="feedback_url">Rate Letter Url <font color="#FF0000">*</font></label>
                  <input type="text" class="form-control" placeholder="Enter your Rate Letter url" id="rateletter_url" name="rateletter_url" value="<?php if($_POST) echo $_POST['rateletter_url'];else echo stripslashes($row->rateletter_url);?>">
				 <?php echo $err_rateletter_url;?>
                </div>   
				</div>	
				
				<div class="col-md-12">
                	 <h4>Default Terms Settings &nbsp; <input type="button" style="width:8%;" class="form-control btn btn-primary" value="Add" id="addGrid">	
                     
                     
	               		
	               		</h4>
	               	
                     
                </div>
                
                <?php
                	if($_REQUEST['eId'] !=''){
                		$count=0;
                		$rateLevel="SELECT * FROM ".TBL_DOCUMENT_CONFIG_DETAILS." WHERE  id_doc_type='".$row->doc_type."' ";
                		
                		$resRate = mysqli_query($connNew,$rateLevel);
						unset($_SESSION['docconfic']);
                	while($rowRate=mysqli_fetch_object($resRate)){
                	$DocmentConficCode 		= rand(0000,9999);
					$_SESSION['docconfic'][$DocmentConficCode]['rate_level']	=$rowRate->id_rate_level;
					$_SESSION['docconfic'][$DocmentConficCode]['generalterms']	=$rowRate->id_general_term;
               	?>

                <div class="form-group col-md-12">
                 <div id="button<?php echo $DocmentConficCode; ?>">
	                <div class="form-group col-md-5">
	            		<label for="general_term" >Rate Level</label>
	            		<select class="form-control select2" name="rate_level[]" id="rate_level[]"  >
	                			<option value="">Select Level</option>
	                			<?php 
									$resCat = selectSql(TBL_RATE_LEVEL,"where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ");
										if(num_rows($resCat)){
											while($resultCat = $db->fetch_object2($resCat)){
											if($rowRate->id_rate_level == $resultCat->id){
												$selected = 'selected="selected"';
											}else{
												$selected = '';
											}
											$rateDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.$resultCat->name.'</option>';
										}
									}
								echo $rateDropDown;
										
								?>
	              			</select>
	               	</div>

					<div class="form-group col-md-5" >
	            		<label for="general_term" >General Terms</label>
	            		<select class="form-control select2" name="generalterms[]" id="generalterms[]">
	                			<option value="">Select Term</option>
	                			<?php 
									$resCat = selectSql(TBL_GENERAL_TERMS,"where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ");
										if(num_rows($resCat)){
											while($resultCat = $db->fetch_object2($resCat)){
											if($rowRate->id_general_term == $resultCat->id){
												$selected = 'selected="selected"';
											}else{
												$selected = '';
											}
											$guestDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.$resultCat->title.'</option>';
										}
									}
								echo $guestDropDown;
										
								?>
	              			</select>
	               		
	               		 </div>
                         <div   class="form-group col-md-2">               		<label for="general_term" >Remove grid</label>         		<input onclick="removeGrid(<?php echo $DocmentConficCode; ?>);" type="button" class="form-control btn btn-danger"  value="Remove" ></div>
	               	<?php if($count<1){?>	 
	               	
	               <?php }$prevTerm=$guestDropDown;$prevLev=$rateDropDown; $count++; $guestDropDown='';$rateDropDown='';?>
               	</div> 
                </div> 
               	<?php }
               		}
               		else{
						$DocmentConficCode 		= rand(0000,9999);
               	?>
               		<div class="form-group col-md-12">
	                

					<div id="button<?php echo $DocmentConficCode; ?>">
	                <div class="form-group col-md-5">
	            		<label for="general_term" >Rate Level</label>
	            		<select class="form-control select2" name="rate_level[]" id="rate_level[]"  >
	                			<option value="">Select Level</option>
	                			<?php 
									$resCat = selectSql(TBL_RATE_LEVEL,"where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ");
										if(num_rows($resCat)){
											while($resultCat = $db->fetch_object2($resCat)){
											if($rowRate->id_rate_level == $resultCat->id){
												$selected = 'selected="selected"';
											}else{
												$selected = '';
											}
											$rateDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.$resultCat->name.'</option>';
										}
									}
								echo $rateDropDown;
										
								?>
	              			</select>
	               	</div>

					<div class="form-group col-md-5" >
	            		<label for="general_term" >General Terms</label>
	            		<select class="form-control select2" name="generalterms[]" id="generalterms[]">
	                			<option value="">Select Term</option>
	                			<?php 
									$resCat = selectSql(TBL_GENERAL_TERMS,"where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ");
										if(num_rows($resCat)){
											while($resultCat = $db->fetch_object2($resCat)){
											if($rowRate->id_general_term == $resultCat->id){
												$selected = 'selected="selected"';
											}else{
												$selected = '';
											}
											$guestDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.$resultCat->title.'</option>';
										}
									}
								echo $guestDropDown;
										
								?>
	              			</select>
	               		
	               		 </div>
                         <div   class="form-group col-md-2">             
  		<label for="general_term" >Remove grid</label>         		<input onclick="removeGrid(<?php echo $DocmentConficCode; ?>);" type="button" class="form-control btn btn-danger"  value="Remove" ></div>
	               	<?php if($count<1){?>	 
	               	
	               <?php }$prevTerm=$guestDropDown;$prevLev=$rateDropDown; $count++; $guestDropDown='';$rateDropDown='';?>
               	</div>
	               		
               	</div>
               	<?php }?>
               	<div class="form-group col-md-12" id="rateLevelGrid">
               		
               	</div>	 
  				 <div class="box-body">
                 <div class="form-group">

                  <label for="name">Allow Multiple Date Rates:</label>&nbsp&nbsp

                  <input type="radio" class="flat-red" <?php if($_POST['allow_multiple_date_rates'] == '1'){echo "checked";}else{if($row->allow_multiple_date_rates == 1)echo "checked";}?> value="1" name="allow_multiple_date_rates"/> Allowed

				 <input type="radio" class="flat-red" <?php if($_POST['allow_multiple_date_rates'] == '0'){echo "checked";}else{if($row->allow_multiple_date_rates == 0)echo "checked";}?> value="0" name="allow_multiple_date_rates"/> Not Allowed

				<?php echo $err_allow_multiple_date_rates;?>

                </div>
				<div class="form-group ">
                  <label for="status">Status</label>
                 <input type="radio" class="flat-red" <?php if($_POST['status'] == '1'){echo "checked";}else{if($row->status == 1)echo "checked";}?> value="1" name="status"/> Active
				 <input type="radio" class="flat-red" <?php if($_POST['status'] == '0'){echo "checked";}else{if($row->status == 0)echo "checked";}?> value="0" name="status"/> Inactive
				 <?php echo $err_status;?>
                </div>
				
				<?php
				if($row->date_created){?>
				  
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
				   <?php $sqlUserDetail = $db->fetch_obj2(selectSql(TBL_USERS,"WHERE `id` = '".$row->id_mst_user_modified_by."'",''));?>
                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail->username);?>">				
                </div>  
				  
				  <?php } ?>            
              	
              <!-- /.box-body -->	
			 <div class="box-footer">                                       
				<input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
				&nbsp;&nbsp;&nbsp;&nbsp;
			   <input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("manageDocumentConfig.php"); '>
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
<script type="text/javascript">
	$(document).ready(function(){
		var rateBox=1;
		$('#addGrid').click(function(){
			var rate_level = '<div id="rate'+rateBox+'" class="form-group col-md-5">            		<label for="general_term"  >Rate Level</label>            		<select class="form-control select2" name="rate_level[]"   ><option value="">Rate Level</option>'+'<?php echo $prevLev ;?>'+'</select></div>'; 
			
			var general_term ='<div id="gen'+rateBox+'" class="form-group col-md-5">            		<label for="general_term" >General Terms</label>            		<select class="form-control select2" name="generalterms[]" >                			<option value="">General Terms</option>'+'<?php echo $prevTerm ;?>'+'</select></div>';
			var remove ='<div id="button'+rateBox+'"  class="form-group col-md-2">               		<label for="general_term" >Remove grid</label>         		<input onclick="removeGrid('+rateBox+');" type="button" class="form-control btn btn-danger"  value="Remove" ></div>';

			$('#rateLevelGrid').append(rate_level+general_term+remove);
			rateBox++;
			
		});

		
	});
	function removeGrid(rateBox){
		
		$('#gen'+rateBox).remove();
		$('#rate'+rateBox).remove();
		$('#button'+rateBox).remove();
	}
</script>

