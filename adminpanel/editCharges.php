<?php include_once("../config/auto_loader.php");


//---------------------------------------------------------------------------------------------------------

if($_POST['Save']){

	$err = 0;
	
	
	
	//Insert Here
	
	if($err == 0){//No error
		//print_r($_REQUEST);
		//die;
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add
			//print_r($_POST['transaction_type']); exit;
	

			//checkUserLevelPermission($_SESSION['userLevel'],TBL_CHARGES,'add');
			$addSql = "   	INSERT INTO `".TBL_CHARGES."` SET

							`name` = '".addslashes(trim($_POST['name']))."',
							`display_alias_name` = '".addslashes(trim($_POST['display_alias_name']))."',
							`charges_account` = '".addslashes($_POST['charges_account'])."',
							`tax_applicable` = '".addslashes($_POST['tax_applicable'])."',
							`transaction_type` = '".addslashes($_POST['transaction_type'])."',
							`tax_type` = '".addslashes($_POST['tax_type'])."',
							`percentage` = '".addslashes($_POST['percentage'])."',
							`id_mst_charges_sgst` = '".addslashes($_POST['id_mst_charges_sgst'])."',
							`id_mst_charges_cgst` = '".addslashes($_POST['id_mst_charges_cgst'])."',
							`id_mst_charges_igst` = '".addslashes($_POST['id_mst_charges_igst'])."',
							`id_mst_charges_vat` = '".addslashes($_POST['id_mst_charges_vat'])."',
							`id_mst_charges_cess` = '".addslashes($_POST['id_mst_charges_cess'])."',
							`id_mst_charges_surcharge` = '".addslashes($_POST['id_mst_charges_surcharge'])."',
							`id_shop` = '".addslashes($_SESSION['shop'])."'";

			$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`id_mst_user_created_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'";
			
							$sql1 = executeSql("SELECT * FROM `".TBL_CHARGES."` ORDER BY id DESC LIMIT 1");
								while($row = $db->fetch_object2($sql1)){
								$idd = $row -> id;
								if($idd == '0'){
									$last_id = '1';
								}else{
									$last_id =  $idd + 1;
								}
							}

			
			
			if(executeSql($addSql)){
				//unset($_POST);
				$lastInsertId= $db->insert_id();
				$_SESSION['successMsg'] = 'New Charges Master details has been added sucessfully.';
				header("location:manageCharges.php");
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Charges Master details has not been saved. Please make corrections below.';
			}
			
		}

		//Update Section Here

			 $editSql = "  UPDATE `".TBL_CHARGES."` SET 
							`name` = '".addslashes(trim($_POST['name']))."',
							`display_alias_name` = '".addslashes(trim($_POST['display_alias_name']))."',
							`charges_account` = '".addslashes($_POST['charges_account'])."',
							`tax_applicable` = '".addslashes($_POST['tax_applicable'])."',
							`transaction_type` = '".addslashes($_POST['transaction_type'])."',
							`tax_type` = '".addslashes($_POST['tax_type'])."',
							`percentage` = '".addslashes($_POST['percentage'])."',
							`id_mst_charges_sgst` = '".addslashes($_POST['id_mst_charges_sgst'])."',
							`id_mst_charges_cgst` = '".addslashes($_POST['id_mst_charges_cgst'])."',
							`id_mst_charges_igst` = '".addslashes($_POST['id_mst_charges_igst'])."',
							`id_mst_charges_vat` = '".addslashes($_POST['id_mst_charges_vat'])."',
							`id_mst_charges_cess` = '".addslashes($_POST['id_mst_charges_cess'])."',
							`id_mst_charges_surcharge` = '".addslashes($_POST['id_mst_charges_surcharge'])."',
							`id_shop` = '".addslashes($_SESSION['shop'])."'";
			 
			$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."' 
							WHERE `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'";
								
			
		
			
			
			if(executeSql($editSql)){
				$_SESSION['successMsg'] = selectColumn(TBL_CHARGES,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  'id' = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has been updated sucessfully.';
			header("location:manageCharges.php");
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = selectColumn(TBL_CHARGES,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  'id' = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has not been saved.Please make corrections below.';
			}
		}else{//Error
			$err++;
			$_SESSION['errorMsg'] = 'Charges details has not been saved. Please make corrections.';
		}
	}
// ----------cate---------
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){

	$sql = "  SELECT * FROM `".TBL_CHARGES."`
				WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
	 $db->query($sql);
	
	if($db->num_rows() > 0){
		$row = $db->fetch_object(); 
		$idsection = $row->id;
		
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
               <button type="button" class="btn btn-danger" data-dismiss="modal"> <span class="glyphicon glyphicon-off"></span> Close</button> 
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
			   <li class="active" ><a href="#tab_1" data-toggle="tab">Charges</a></li>   
               
            </ul>
			<div class="box-header with-border">
              <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?>  : <span style="color:#3c8dbc"> <?php echo $row->name ?> </span>

			  <a><?php echo selectColumn(TBL_CHARGES,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  'id' = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'"); ?></a></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->  			        
			 <form name="form1"  method="post" enctype="multipart/form-data" data-parsley-validate autocomplete="off" >
                <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" />
					<div class="form-group has-error" align="center">
						<?php if($_SESSION['errorMsg']){?>
						 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
						<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
					 	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
						<?php unset($_SESSION['successMsg']);}?>
					 </div>
              <div class="box-body">
                
				 <div class="form-group">
                  <label for="name">Charges Name <font color="#FF0000">*</font></label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-check-circle-o"></i> 
					   	</div>
                  <input type="text" class="form-control" placeholder="Enter Charges Name" id="name" name="name" value="<?php if($_POST) echo $_POST['name'];else echo stripslashes($row->name);?>"  data-parsley-required>
				<?php echo $err_unit_name;?></div>
                </div>
<div class="form-group">
                  <label for="name">Display / Alias Name <font color="#FF0000">*</font></label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-check-circle-o"></i> 
					   	</div>
                  <input type="text" class="form-control" placeholder="Enter display aliasname" id="display_alias_name" name="display_alias_name" value="<?php if($_POST) echo $_POST['display_alias_name'];else echo stripslashes($row->display_alias_name);?>"  >
				<?php echo $err_display_alias_name;?></div>
                </div>
                <div class="form-group">
                  <label for="name">Charges Account <font color="#FF0000">*</font></label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-shield"></i> 
					   	</div> 
					<select class="form-control select2" name="charges_account" id="charges_account" onchange="changeFunc()" data-parsley-required>
                 		<?php if($row->charges_account == '1'){
                 			$categoryDropDown = '<option value="1">SALES</option>';
                 		}elseif($row->charges_account == '2'){
                 			$categoryDropDown = '<option value="2">PURCHASE</option>';
                 		}elseif($row->charges_account == '3'){
                 			$categoryDropDown = '<option value="3">INCOME</option>';
                 		}elseif($row->charges_account == '4'){
                 			$categoryDropDown = '<option value="4">EXPENSE</option>';
                 		}elseif($row->charges_account == '5'){
                 			$categoryDropDown = '<option value="5">TAXES</option>';
                 		}elseif($row->charges_account == '6'){
                 			$categoryDropDown = '<option value="6">DISCOUNT</option>';
                 		}elseif($row->charges_account == '7'){
                 			$categoryDropDown = '<option value="7">OTHERS</option>';
                 		}elseif($row->charges_account == '8'){
                 			$categoryDropDown = '<option value="8">BANK</option>';
                 		}
                 		else{

                 		}
                 		echo $categoryDropDown;
                 	?>
                 	    <option value="0">Select Charges Account</option>
						<option value="1">SALES</option>
						<option value="2">PURCHASE</option>
						<option value="3">INCOME</option>
						<option value="4">EXPENSE</option>
						<option value="5">TAXES</option>
						<option value="6">DISCOUNT</option> 
						<option value="8">BANK</option> 
						<option value="7">OTHERS</option> 
						
					</select>
                 </div> 
				<?php echo $err_tax_type;?>
                </div> 
                
                 
                <div class="form-group" id="taxapplicable" name="taxapplicable">
                  <label for="name">Tax Applicable  <font color="#FF0000">*</font></label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-tags"></i> 
					   	</div>
                 	<?php
                 		if($row->tax_applicable == '1'){
							$selected4	='selected="selected"'; 
							
                 		}elseif($row->tax_applicable == '2'){
							$selected5	='selected="selected"';
                 		}
                 		//echo $categoryDropDown;
                 	?><select class="form-control select2" name="tax_applicable" id="tax_applicable" onchange="changetaxtype();" >
						<option value="0">Select Tax Applicable</option>
						<option value="1" <?php echo $selected4;?>>GST</option>
						<option value="2" <?php echo $selected5;?>>VAT</option>

					</select></div>
                 	 
				<?php echo $err_tax_type;?>
                </div> 
            	 
       
                <div class="form-group" id="transctiontype" name="transctiontype">
                  <label for="name">Transaction Type  <font color="#FF0000">*</font></label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-telegram"></i> 
					   	</div>
					   	<select class="form-control select2" name="transaction_type"  id="transaction_type" onchange="changetaxtype();">
							
                 	<?php
                 		if($row->transaction_type == '1'){
                 			$categoryDropDown = '<option value="1" selected="selected">Local</option>';
                 		}elseif($row->transaction_type == '2'){
                 			$categoryDropDown = '<option value="2" selected="selected">Interstate</option>';
                 		}else{
                 			$categoryDropDown = '<option value="0" selected="selected">Not Applicable</option>';
                 		}
                 		echo $categoryDropDown;
                 	?> 
						<option value="1">Local</option>
						<option value="2">Interstate</option> 
					</select>
                 	 </div> 
			 
				<?php echo $err_transaction_type;?>
                </div> 
				 
				  
                  <div class="form-group" id="taxtype" name="taxtype"> 

                 		<label for="name">Tax Type  <font color="#FF0000">*</font></label>
                 		 <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-plus-square-o"></i> 
					   	</div>
					   	<select class="form-control select2" name="tax_type" id="tax_type" onchange="changeFunc1()" >
							
                 	<?php	if($row->tax_type == '1'){
                 			$categoryDropDown = '<option value="1" selected="selected">SGST</option>';
                 		}elseif($row->tax_type == '2'){
                 			$categoryDropDown = '<option value="2" selected="selected">CGST</option>';
                 		}
                 		elseif($row->tax_type == '3'){
                 			$categoryDropDown = '<option value="3" selected="selected">IGST</option>';
                 		}
                 		elseif($row->tax_type == '4'){
                 			$categoryDropDown = '<option value="4" selected="selected">VAT</option>';
                 		}
                 		elseif($row->tax_type == '5'){
                 			$categoryDropDown = '<option value="5" selected="selected">CESS</option>';
                 			
                 		}elseif($row->tax_type == '6'){
                 			$categoryDropDown = '<option value="6" selected="selected">SURCHARGE</option>';
                 		}else{
                 			 $categoryDropDown = '<option value="0" selected="selected">Not Applicable</option>';
                 		}
                 		echo $categoryDropDown;
                 	?>
                 	    
						<option value="1">SGST</option>
						<option value="2">CGST</option>
						<option value="3">IGST</option>
						<option value="4">VAT</option>
						<option value="5">CESS</option>
						<option value="6">SURCHARGE</option> 
					</select></div>
                 	  </div>
				 
				<?php echo $err_tax_type;?>
               
  
                <div class="form-group" id="percen" name="percen">
                  <label for="name">PERCENTAGE <font color="#FF0000">*</font></label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-percent"></i> 
					   	</div>
                  <input type="text" class="form-control" placeholder="Enter PERCENTAGE" id="percentage" name="percentage" value="<?php if($_POST) echo $_POST['percentage'];else echo stripslashes($row->percentage);?>" > 
                  	</div>
                </div> 
            <style type="text/css">
            	#taxtypesectionss{
            		display: none;
            	} 
            </style>  
            <div  id="taxtypesectionss" name="taxtypesectionss">
             <?php 
             if($row->transaction_type == '1' && $row->tax_applicable == '1'){
             	$id_mst_charges_sgst = 1;
             	$id_mst_charges_cgst = 1;
             	$id_mst_charges_igst = 0;
             	$id_mst_charges_vat = 0;
             	$id_mst_charges_cess = 0;
             	$id_mst_charges_surcharge=0;
             }
             if($row->transaction_type == '2' && $row->tax_applicable == '1'){
             	$id_mst_charges_sgst = 0;
             	$id_mst_charges_cgst = 0;
             	$id_mst_charges_igst = 1;
             	$id_mst_charges_vat = 0;
             	$id_mst_charges_cess = 0;
             	$id_mst_charges_surcharge=0;
             }
             if($row->transaction_type == '1' && $row->tax_applicable == '2'){
             	$id_mst_charges_sgst = 0;
             	$id_mst_charges_cgst = 0;
             	$id_mst_charges_igst = 0;
             	$id_mst_charges_vat = 1;
             	//$id_mst_charges_cess = 1;
             	$id_mst_charges_surcharge=1;
             }
             if($row->transaction_type == '2' && $row->tax_applicable == '2'){
             	$id_mst_charges_sgst = 0;
             	$id_mst_charges_cgst = 0;
             	//$id_mst_charges_igst =1;
             	$id_mst_charges_igst =0;
             	//$id_mst_charges_vat = 0;
             	$id_mst_charges_vat = 1;
             	$id_mst_charges_cess = 0;
			    $id_mst_charges_surcharge=1;
             }
             if($row->charges_account == '4'){
             	$id_mst_charges_sgst = 1;
             	$id_mst_charges_cgst = 1;
             	$id_mst_charges_igst =1;
             }

        ?>
                 <div class="form-group" id="sgst" name="sgst">
                  <label for="name">SGST  <font color="#FF0000">*</font></label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-snowflake-o"></i> 
					   	</div>
                 <?php $categoryDropDown = '<select class="form-control select2" name="id_mst_charges_sgst" id="id_mst_charges_sgst"   style="width: 100%;">

							<option value="0">Not Applicable</option> ';
						  $resCat = selectSql(TBL_CHARGES,"where id_shop='".$_SESSION['shop']."' and tax_type='1' ",' ORDER BY `name`');
						  if($db->num_rows2($resCat)){
						  	while($resultCat = $db->fetch_object2($resCat)){
								if($_REQUEST['id_mst_charges_sgst'] == $resultCat->id){
									//$selected = 'selected="selected"';
								}
								elseif($row->id_mst_charges_sgst == $resultCat->id){
									$selected = 'selected="selected"';
								}else{
									$selected = "";
								}  
									$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
								 
							}
						  }
						 	echo $categoryDropDown .= '</select>';
						  ?>
				<?php echo $err_id_mst_charges_sgst;?></div>
                </div>
                 
                 <div class="form-group" id="cgst" name="cgst">
                  <label for="name">CGST  <font color="#FF0000">*</font></label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-life-ring"></i> 
					   	</div>
                 <?php $categoryDropDown = '<select class="form-control select2" name="id_mst_charges_cgst" id="id_mst_charges_cgst"  style="width: 100%;"
                 >
							<option value="0">Not Applicable</option> ';
						  $resCat = selectSql(TBL_CHARGES,"where id_shop='".$_SESSION['shop']."' and tax_type='2' ",' ORDER BY `name`');
						  if($db->num_rows2($resCat)){
						  	while($resultCat = $db->fetch_object2($resCat)){
								if($_REQUEST['id_mst_charges_cgst'] == $resultCat->id){
									$selected = 'selected="selected"';
								}elseif($row->id_mst_charges_cgst == $resultCat->id){
									$selected = 'selected="selected"';
								}else{
									$selected = '';
								}
								$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
							}
						  }
						 	echo $categoryDropDown .= '</select>';
						  ?>
				<?php echo $err_id_mst_charges_cgst;?></div>
                </div>
         
                 <div class="form-group" id="igst" name="igst">
                  <label for="name">IGST  <font color="#FF0000">*</font></label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-map-pin"></i> 
					   	</div>
                 <?php $categoryDropDown = '<select class="form-control select2" name="id_mst_charges_igst" id="id_mst_charges_igst"  style="width: 100%;"
                 >
							<option value="0">Not Applicable</option> ';
						  $resCat = selectSql(TBL_CHARGES,"where id_shop='".$_SESSION['shop']."'  and tax_type='3' ",' ORDER BY `name`');
						  if($db->num_rows2($resCat)){
						  	while($resultCat = $db->fetch_object2($resCat)){
								if($_REQUEST['id_mst_charges_igst'] == $resultCat->id){
									$selected = 'selected="selected"';
								}elseif($row->id_mst_charges_igst == $resultCat->id){
									$selected = 'selected="selected"';
								}else{
									$selected = '';
								}
								$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
							}
						  }
						 	echo $categoryDropDown .= '</select>';
						  ?>
				<?php echo $err_id_mst_charges_igst;?></div>
                </div>
           
                <div class="form-group" id="vat" name="vat">
                  <label for="name">VAT <font color="#FF0000">*</font></label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-caret-square-o-right"></i> 
					   	</div>
                 <?php $categoryDropDown = '<select class="form-control select2" name="id_mst_charges_vat" id="id_mst_charges_vat"  style="width: 100%;"
                 >
							<option value="0">Select</option>';
						  $resCat = selectSql(TBL_CHARGES,"where id_shop='".$_SESSION['shop']."' and tax_type='4' ",' ORDER BY `name`');
						  if($db->num_rows2($resCat)){
						  	while($resultCat = $db->fetch_object2($resCat)){
								if($_REQUEST['id_mst_charges_vat'] == $resultCat->id){
									$selected = 'selected="selected"';
								}elseif($row->id_mst_charges_vat == $resultCat->id){
									$selected = 'selected="selected"';
								}else{
									$selected = '';
								}
								$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
							}
						  }
						 	echo $categoryDropDown .= '</select>';
						  ?>
				<?php echo $err_id_mst_charges_igst;?></div>
                </div>
           
                <div class="form-group" id="cess" name="cess">
                  <label for="name">CESS <font color="#FF0000">*</font></label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-th-list"></i> 
					   	</div>
                 <?php $categoryDropDown = '<select class="form-control select2" name="id_mst_charges_cess" id="id_mst_charges_cess"  style="width: 100%;"
                 >
							<option value="0">Not Applicable</option> ';

							
						  $resCat = selectSql(TBL_CHARGES,"where id_shop='".$_SESSION['shop']."' and tax_type='5' ",' ORDER BY `name`');
						  if($db->num_rows2($resCat)){
						  	while($resultCat = $db->fetch_object2($resCat)){
								if($_REQUEST['id_mst_charges_cess'] == $resultCat->id){
									$selected = 'selected="selected"';
								}elseif($row->id_mst_charges_cess == $resultCat->id){
									$selected = 'selected="selected"';
								}else{
									$selected = '';
								} 

								 
								$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
								 
							}
						  }
						 	echo $categoryDropDown .= '</select>';
						  ?>
				<?php echo $err_id_mst_charges_igst;?></div>
                </div>
         
                <div class="form-group" id="surcharge" name="surcharge">
                  <label for="name">Surcharge <font color="#FF0000">*</font></label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-th-list"></i> 
					   	</div>
                 <?php $categoryDropDown = '<select class="form-control select2" name="id_mst_charges_surcharge" id="id_mst_charges_surcharge"  style="width: 100%;"
                 >
							<option value="0">Not Applicable</option> ';

							
						  $resCat = selectSql(TBL_CHARGES,"where id_shop='".$_SESSION['shop']."' and tax_type='6' ",' ORDER BY `name`');
						  if($db->num_rows2($resCat)){
						  	while($resultCat = $db->fetch_object2($resCat)){
								if($_REQUEST['id_mst_charges_surcharge'] == $resultCat->id){
									$selected = 'selected="selected"';
								}elseif($row->id_mst_charges_surcharge == $resultCat->id){
									$selected = 'selected="selected"';
								}else{
									$selected = '';
								} 

								 
								$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
								 
							}
						  }
						 	echo $categoryDropDown .= '</select>';
						  ?>
				<?php echo $err_id_mst_charges_igst;?></div>
                </div>


      	</div>
   
                 <?php 
		        	if($row->status == ''){
		        		$status = 1;
		        	}else{
		        		$status = $row->status;
		        	}
		        ?>          
           				                				
				<div class="form-group">
                  <label for="status">Status</label>
                 <input class="flat-red" type="radio"  <?php if($_POST['status'] == '1'){echo "checked";}else{if($status == 1)echo "checked";}?> value="1" name="status"/> Active
				 <input class="flat-red" type="radio" <?php if($_POST['status'] == '0'){echo "checked";}else{if($status == 0)echo "checked";}?> value="0" name="status"/> Inactive
				 <?php echo $err_status;?>
                </div>
				
				<?php if($row->date_created){?>
				  
					<div class="row">
						<div class="form-group col-md-3">
		                  	<label for="date_created">Date Created</label>
		                  	<input type="text" disabled="disabled" class="form-control" id="date_created"  value="<?php echo stripslashes(dateformat($row->date_created));?>">				
		                </div> 
				
						<div class="form-group col-md-3">
		                  <label for="last_modified">Last Updated</label>
		                  <input type="text" disabled="disabled" class="form-control" id="last_modified" value="<?php echo stripslashes(dateformat($row->last_modified));?>">				
		                </div> 

		                <div class="form-group col-md-3">
		                  <label for="last_modified_by">Created By</label>
						   <?php $sqlUserDetail = selectColumn(TBL_USERS,'name','where id="'.$row->id_mst_user_created_by.'" ');?>
		                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail);?>">				
		                </div>  
				
						<div class="form-group col-md-3">
		                  <label for="last_modified_by">Last Updated By</label>
						   <?php $sqlUserDetail = selectColumn(TBL_USERS,'name','where id="'.$row->id_mst_user_modified_by.'" ');?>
		                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail);?>">				
		                </div>  
					</div>
				  
				<?php }  ?>            
              </div>
              <!-- /.box-body -->	
			 <div class="box-footer">                                       
				<input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
				&nbsp;&nbsp;&nbsp;&nbsp;
			   <input type='button' value='Close' class="btn btn-danger" onclick='location.replace("manageCharges.php"); '>
			<input type='button' value='Audit Trail' class="btn btn-success"  onclick="audittrial(this.value);" style="float:right">
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
<?php if($row->id != ''){?>
<script type="text/javascript">
	 $(document).ready(function(){ 
		var charges_account = document.getElementById("charges_account");
		var selectedValue = charges_account.options[charges_account.selectedIndex].value; 

    if(selectedValue == 1 || selectedValue == 2){
    	$("#taxtypesectionss").css("display", "block");
    	$("#sgst").show();
	    $("#cgst").show();

	    $("#igst").hide();
    	$("#vat").hide();
    	$("#cess").hide();
    	$("#surcharge").hide();
    	
    	//Hide Method
    	$('#tax_type').removeAttr('required');
    	$('#percentage').removeAttr('required');
    	//Show Method
    	$('#transaction_type').attr('required', 'required');
    	$('#tax_applicable').attr('required', 'required');

    	$("#taxtype").hide();
    	$("#percen").hide();

    	$("#transctiontype").show();
    	$("#taxapplicable").show();


    }else if(selectedValue == 3){
		 
		$("#taxtypesectionss").css("display", "none");
    	//Hide Method
    	$('#tax_type').removeAttr('required');
    	$('#tax_applicable').removeAttr('required');
    	$('#transaction_type').removeAttr('required');
    	//Show Method
    	$('#percentage').attr('required', 'required'); 

    	//$("#taxapplicable").hide();
		$("#taxapplicable").show();
    	$("#taxtype").hide();    	
		$("#transctiontype").show();
		$("#sgst").show();
	    $("#cgst").show();
    	$("#percen").show();

    	//Hide
    	$("#cess").hide();
		$("#igst").hide();
		$("#surcharge").hide();

    }else if(selectedValue == 4){
    	$("#taxtypesectionss").css("display", "block");
    	//Hide Method
    	$('#tax_type').removeAttr('required');
    	$('#tax_applicable').removeAttr('required');
    	$('#transaction_type').removeAttr('required');
    	$('#percentage').removeAttr('required');
    	//Show Method    	 

    	$("#taxapplicable").hide();
    	$("#taxtype").hide();
    	$("#transctiontype").hide();
    	$("#percen").hide();

    	//Show Method
    	$("#sgst").show();
    	$("#cgst").show();
		$("#igst").show();

		//Hide Method
		$("#vat").hide();
    	$("#cess").hide();
    	$("#surcharge").hide();

    }else if(selectedValue == 5){ 
	 
	 //Show Method
		
		$("#transctiontype").show();
    	$("#taxapplicable").show();
    	$("#percen").show();
    	$("#taxtype").show();		
		
    	$('#percentage').attr('required', 'required');  
    	$('#transaction_type').attr('required', 'required');   
    	$('#tax_type').attr('required', 'required');  

    	 //Hide Method 
    	 $("#taxtypesectionss").css("display", "none");
    	//Hide
    	$("#sgst").hide();
    	$("#cgst").hide();
		$("#igst").hide();
		$("#surcharge").hide();

    }else if(selectedValue == 6 || selectedValue == 7){

    	//Hide Method
    	$('#tax_type').removeAttr('required');
    	$('#tax_applicable').removeAttr('required');
    	$('#transaction_type').removeAttr('required');
    	
    	//Hide
    	$("#sgst").hide();
    	$("#cgst").hide();
		$("#igst").hide();
		$("#surcharge").hide();
    	$("#taxapplicable").hide();
    	$("#transctiontype").hide();
    	$("#taxtype").hide();

    	//Show Method
    	$('#percentage').attr('required', 'required'); 
    	$("#percen").show();

    }else if( selectedValue == 8){

    	//Hide Method
    	$('#tax_type').hide();
    	$('#tax_applicable').hide();
    	$('#transaction_type').hide();
    	//Show Method
    	$('#percentage').hide(); 

    	$("#taxapplicable").hide();
    	$("#transctiontype").hide();
    	$("#taxtype").hide();
    	$("#percen").hide();

    	//Hide
    	$("#sgst").hide();
    	$("#cgst").hide();
		$("#igst").hide();
		$("#surcharge").hide();

    }
	
	
	
	
	 // Hide and Show section Available Here
	//Charge Account
   		 //var charges_account = document.getElementById("charges_account");
   		 var charges_account = $("#charges_account").val(); 
    //
	   	//var transaction_type = document.getElementById("transaction_type");
	   	var transaction_type = $("#transaction_type").val();
	   	//Tax Applicable Section
	   	var tax_applicable = document.getElementById("tax_applicable");
	    var tax_applicable =$("#tax_applicable").val();

	    //GST = 1

	    	//alert(charges_account);
	  if(charges_account != 5){


	    if(transaction_type == 1 && tax_applicable == 1 ){
			
   			$("#taxtypesectionss").css("display", "block");

   			//Hide Method
	    	$('#id_mst_charges_igst').removeAttr('required');
	    	$('#id_mst_charges_vat').removeAttr('required');
	    	$('#id_mst_charges_cess').removeAttr('required');
	    	$('#id_mst_charges_surcharge').removeAttr('required');

	    	//Show Method
	    	$('#id_mst_charges_sgst').attr('required', 'required'); 
	    	$('#id_mst_charges_cgst').attr('required', 'required'); 

	    	$("#sgst").show();
	    	$("#cgst").show();
	    	if(charges_account != 3){$("#cess").show();}
			
			$("#surcharge").hide();
	    	$("#igst").hide();
	    	$("#vat").hide();
	    	

	    }else if(transaction_type == 2 && tax_applicable == 1){
	    	$("#taxtypesectionss").css("display", "block");

	    	//Hide Method
	    	$('#id_mst_charges_sgst').removeAttr('required');
	    	$('#id_mst_charges_cgst').removeAttr('required');
	    	$('#id_mst_charges_vat').removeAttr('required');
	    	$('#id_mst_charges_cess').removeAttr('required');
	    	$('#id_mst_charges_surcharge').removeAttr('required');
	    	//Show Method
	    	$('#id_mst_charges_igst').attr('required', 'required');  

	    	$("#sgst").hide();
	    	$("#cgst").hide();
	    	$("#vat").hide();
	    	$("#cess").hide();

	    	$("#igst").show();
	    	$("#surcharge").show();
	    }
	    //VAT = 2
	    if(transaction_type == 1 && tax_applicable == 2 ){
	    	$("#taxtypesectionss").css("display", "block");

	    	//Hide Method
	    	$('#id_mst_charges_sgst').removeAttr('required');
	    	$('#id_mst_charges_cgst').removeAttr('required'); 
	    	$('#id_mst_charges_igst').removeAttr('required'); 
	    	//Show Method
	    	$('#id_mst_charges_vat').attr('required', 'required'); 
	    	$('#id_mst_charges_cess').attr('required', 'required'); 

	    	$("#sgst").hide();
	    	$("#cgst").hide();
	    	$("#igst").hide();
	    	$("#surcharge").show();
	    	$("#vat").show();
	    	$("#cess").hide();
	    	

	    }else if(transaction_type == 2 && tax_applicable == 2){	    		    	
	    	$("#taxtypesectionss").css("display", "block"); 

	    	//Hide Method
	    	$('#id_mst_charges_sgst').removeAttr('required');
	    	$('#id_mst_charges_cgst').removeAttr('required');
	    	$('#id_mst_charges_vat').removeAttr('required');
	    	$('#id_mst_charges_cess').removeAttr('required');
	    	//Show Method
	    	$('#id_mst_charges_igst').attr('required', 'required');  

	    	$("#sgst").hide();
	    	$("#cgst").hide();
	    	$("#igst").hide();
	    	$("#cess").hide();
	    	$("#surcharge").show();
	    	$("#vat").show(); 
	    }
	  } else if(charges_account == 3){
		  //Hide Method
	    	$('#id_mst_charges_sgst').removeAttr('required');
	    	$('#id_mst_charges_cgst').removeAttr('required');
	    	//$('#id_mst_charges_vat').removeAttr('required');
	    	$('#id_mst_charges_cess').removeAttr('required');
	    	$('#id_mst_charges_igst').removeAttr('required');
	    	//Show Method 

	   		$("#sgst").hide();
	    	$("#cgst").hide();
	    	$("#igst").hide();
	    	$("#vat").hide();
	    	$("#cess").hide();
	    	//$("#surcharge").hide();
	  }else{

	   		//Hide Method
	    	$('#id_mst_charges_sgst').removeAttr('required');
	    	$('#id_mst_charges_cgst').removeAttr('required');
	    	$('#id_mst_charges_vat').removeAttr('required');
	    	$('#id_mst_charges_cess').removeAttr('required');
	    	$('#id_mst_charges_igst').removeAttr('required');
	    	//Show Method 

	   		$("#sgst").hide();
	    	$("#cgst").hide();
	    	$("#igst").hide();
	    	$("#vat").hide();
	    	$("#cess").hide();
	    	$("#surcharge").hide();
	   }
	});
</script>
<?php } ?>
<script type="text/javascript">

   function changeFunc() { 
    var charges_account = document.getElementById("charges_account");
    var selectedValue = charges_account.options[charges_account.selectedIndex].value; 
 
    if(selectedValue == 1 || selectedValue == 2){
    	$("#taxtypesectionss").css("display", "block");
    	$("#sgst").show();
	    $("#cgst").show();

	    $("#igst").hide();
    	$("#vat").hide();
    	$("#cess").hide();
    	$("#surcharge").hide();
    	
    	//Hide Method
    	$('#tax_type').removeAttr('required');
    	$('#percentage').removeAttr('required');
    	//Show Method
    	$('#transaction_type').attr('required', 'required');
    	$('#tax_applicable').attr('required', 'required');

    	$("#taxtype").hide();
    	$("#percen").hide();

    	$("#transctiontype").show();
    	$("#taxapplicable").show();


    }else if(selectedValue == 3){
		 
		$("#taxtypesectionss").css("display", "none");
    	//Hide Method
    	$('#tax_type').removeAttr('required');
    	$('#tax_applicable').removeAttr('required');
    	$('#transaction_type').removeAttr('required');
    	//Show Method
    	$('#percentage').attr('required', 'required'); 

    	//$("#taxapplicable").hide();
		$("#taxapplicable").show();
    	$("#taxtype").hide();    	
		$("#transctiontype").show();
		$("#sgst").show();
	    $("#cgst").show();
    	$("#percen").show();

    	//Hide
    	$("#cess").hide();
		$("#igst").hide();
		$("#surcharge").hide();

    }else if(selectedValue == 4){ 

    	$("#taxtypesectionss").css("display", "block");
    	//Hide Method
    	$('#tax_type').removeAttr('required');
    	$('#tax_applicable').removeAttr('required');
    	$('#transaction_type').removeAttr('required');
    	$('#percentage').removeAttr('required');
    	//Show Method    	 

    	$("#taxapplicable").hide();
    	$("#taxtype").hide();
    	$("#transctiontype").hide();
    	$("#percen").hide();

    	//Show Method
    	$("#sgst").show();
    	$("#cgst").show();
		$("#igst").show();

		//Hide Method
		$("#vat").hide();
    	$("#cess").hide();
    	$("#surcharge").hide();

    }else if(selectedValue == 5){ 
	 
	 //Show Method
		
		$("#transctiontype").show();
    	$("#taxapplicable").show();
    	$("#percen").show();
    	$("#taxtype").show();		
		
    	$('#percentage').attr('required', 'required');  
    	$('#transaction_type').attr('required', 'required');   
    	$('#tax_type').attr('required', 'required');  

    	 //Hide Method 
    	 $("#taxtypesectionss").css("display", "none");
    	//Hide
    	$("#sgst").hide();
    	$("#cgst").hide();
		$("#igst").hide();
		$("#surcharge").hide();

    }else if(selectedValue == 6 || selectedValue == 7){

    	//Hide Method
    	$('#tax_type').removeAttr('required');
    	$('#tax_applicable').removeAttr('required');
    	$('#transaction_type').removeAttr('required');
    	//Show Method
    	$('#percentage').attr('required', 'required'); 

    	$("#taxapplicable").hide();
    	$("#transctiontype").hide();
    	$("#taxtype").hide();
    	$("#percen").show();

    	//Hide
    	$("#sgst").hide();
    	$("#cgst").hide();
		$("#igst").hide();
		$("#surcharge").hide();

    }else if( selectedValue == 8){

    	//Hide Method
    	$('#tax_type').hide();
    	$('#tax_applicable').hide();
    	$('#transaction_type').hide();
    	//Show Method
    	$('#percentage').hide(); 

    	$("#taxapplicable").hide();
    	$("#transctiontype").hide();
    	$("#taxtype").hide();
    	$("#percen").hide();

    	//Hide
    	$("#sgst").hide();
    	$("#cgst").hide();
		$("#igst").hide();
		$("#surcharge").hide();

    }
   } 
   //Change Function Type
   function changetaxtype(){ 

   		//Charge Account
   		 //var charges_account = document.getElementById("charges_account");
   		 var charges_account = $("#charges_account").val(); 
    //
	   	//var transaction_type = document.getElementById("transaction_type");
	   	var transaction_type = $("#transaction_type").val();
	   	//Tax Applicable Section
	   //	var tax_applicable = document.getElementById("tax_applicable");
	    var tax_applicable =$("#tax_applicable").val();

	    //GST = 1
 
 
	  if(charges_account != 5){


	    if(transaction_type == 1 && tax_applicable == 1 ){
			 
   			$("#taxtypesectionss").css("display", "block");

   			//Hide Method
	    	$('#id_mst_charges_igst').removeAttr('required');
	    	$('#id_mst_charges_vat').removeAttr('required');
	    	$('#id_mst_charges_cess').removeAttr('required');
	    	$('#id_mst_charges_surcharge').removeAttr('required');

	    	//Show Method
	    	$('#id_mst_charges_sgst').attr('required', 'required'); 
	    	$('#id_mst_charges_cgst').attr('required', 'required'); 

	    	$("#sgst").show();
	    	$("#cgst").show();
	    	if(charges_account != 3){$("#cess").show();}
			
			$("#surcharge").hide();
	    	$("#igst").hide();
	    	$("#vat").hide();
	    	

	    }else if(transaction_type == 2 && tax_applicable == 1){
	    	
	    	$("#taxtypesectionss").css("display", "block");

	    	//Hide Method
	    	$('#id_mst_charges_sgst').removeAttr('required');
	    	$('#id_mst_charges_cgst').removeAttr('required');
	    	$('#id_mst_charges_vat').removeAttr('required');
	    	$('#id_mst_charges_cess').removeAttr('required');
	    	$('#id_mst_charges_surcharge').removeAttr('required');
	    	//Show Method
	    	$('#id_mst_charges_igst').attr('required', 'required');  

	    	$("#sgst").hide();
	    	$("#cgst").hide();
	    	$("#vat").hide();
	    	$("#cess").hide();

	    	$("#igst").show();
	    	$("#surcharge").show();
	    }
	    //VAT = 2
	    if(transaction_type == 1 && tax_applicable == 2 ){
	    	
	    	$("#taxtypesectionss").css("display", "block");

	    	//Hide Method
	    	$('#id_mst_charges_sgst').removeAttr('required');
	    	$('#id_mst_charges_cgst').removeAttr('required'); 
	    	$('#id_mst_charges_igst').removeAttr('required'); 
	    	//Show Method
	    	$('#id_mst_charges_vat').attr('required', 'required'); 
	    	$('#id_mst_charges_cess').attr('required', 'required'); 

	    	$("#sgst").hide();
	    	$("#cgst").hide();
	    	$("#igst").hide();
	    	$("#surcharge").show();
	    	$("#vat").show();
	    	$("#cess").hide();

	    }else if(transaction_type == 2 && tax_applicable == 2){
	    	
	    	$("#taxtypesectionss").css("display", "block");

	    	//Hide Method
	    	$('#id_mst_charges_sgst').removeAttr('required');
	    	$('#id_mst_charges_cgst').removeAttr('required');
	    	$('#id_mst_charges_vat').removeAttr('required');
	    	$('#id_mst_charges_cess').removeAttr('required');
	    	//Show Method
	    	$('#id_mst_charges_igst').attr('required', 'required');  

	    	$("#sgst").hide();
	    	$("#cgst").hide();
	    	//$("#vat").hide();
	    	$("#vat").show();
	    	$("#cess").hide();
	    	$("#surcharge").show();
	    	$("#igst").hide();
	    }
	  } else if(charges_account == 3){
		  //Hide Method
	    	$('#id_mst_charges_sgst').removeAttr('required');
	    	$('#id_mst_charges_cgst').removeAttr('required');
	    	//$('#id_mst_charges_vat').removeAttr('required');
	    	$('#id_mst_charges_cess').removeAttr('required');
	    	$('#id_mst_charges_igst').removeAttr('required');
	    	//Show Method 

	   		$("#sgst").hide();
	    	$("#cgst").hide();
	    	$("#igst").hide();
	    	$("#vat").hide();
	    	$("#cess").hide();
	    	//$("#surcharge").hide();
	  }else{

	   		//Hide Method
	    	$('#id_mst_charges_sgst').removeAttr('required');
	    	$('#id_mst_charges_cgst').removeAttr('required');
	    	$('#id_mst_charges_vat').removeAttr('required');
	    	$('#id_mst_charges_cess').removeAttr('required');
	    	$('#id_mst_charges_igst').removeAttr('required');
	    	//Show Method 

	   		$("#sgst").hide();
	    	$("#cgst").hide();
	    	$("#igst").hide();
	    	$("#vat").hide();
	    	$("#cess").hide();
	    	$("#surcharge").hide();
	   }
   }
   //
   function showfunction() { 
    var transaction_type = document.getElementById("transaction_type");
    var selectedValue = transaction_type.options[transaction_type.selectedIndex].value;
   
    if(selectedValue == 1){
    	 $("#local").css("display", "block");
    	 $("#interstate").css("display", "none");
    }else{
    	 $("#local").css("display", "none");
    	 $("#interstate").css("display", "block");
    }
   }  
   //function 
   function changeFunc1(){

   		 /*var tax_applicable = document.getElementById("tax_applicable");
	     var tax_applicable = tax_applicable.options[tax_applicable.selectedIndex].value;
	    

	     if(tax_applicable == '2'){
	     	$("#sgst").hide();
	    	$("#cgst").hide();
	     	$("#igst").hide();
	    	$("#vat").hide();
	     	$("#cess").show();
	     }else{
	     	$("#sgst").hide();
	    	$("#cgst").hide();
	     	$("#igst").hide();
	     	$("#vat").hide();
	    	$("#cess").hide();
	     }*/
   }

  </script>
 
<script type="text/javascript">
	function audittrial(clicked_value){
		//alert(clicked_value);
		//var id = document.getElementById('id_mst_hotels').value;
		$('#auditModal').modal('show');
		var table ='mst_charges';
		$.ajax({
			url: "../functions/ajaxAuditTrail.php",
			  type: 'POST',
				data: { tablename : table },
				dataType: "JSON",
				success: function(data) {
				// alert(data);
			  $('#roombutton').html(data);
			}
	   });
	}
	
	
</script>

