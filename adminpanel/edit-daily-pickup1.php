<?php include_once("../config/auto_loader.php");


$createDate	=	date('d-m-Y');
$billing_date	=	date('d-m-Y');
$payment_date	=	date('d-m-Y');


if($_REQUEST['Save']){

	$err = 0;
	
	
	//Insert Here
	
	if($err == 0){//No error
		if(($_REQUEST['Save'] == 'Add')){    
			
			$addSql = "   	INSERT INTO `daily_pickup` SET

							`bill_no` = '".$_REQUEST['bill_no']."',
							`id_company` = '".$_REQUEST['id_company']."', 
							`id_contacts` = '".$_REQUEST['id_contacts']."', 
							
							`id_executive` = '".$_REQUEST['user_id']."',
							`id_payment_status` = '".$_REQUEST['id_payment_status']."'";
							
						if($_REQUEST['id_payment_status']=='2'){									
							$addSql .= ", `payment_date` = '".date('Y-m-d' , strtotime($_REQUEST['payment_date']))."'";
					}else{
							$addSql .= ", `payment_date` = '0000-00-00'";
					}	
							
							  
							 
							$addSql .= "	,`doc_date` = '".date('Y-m-d' , strtotime($_REQUEST['createDate']))."',  
							`bill_date` = '".date('Y-m-d' , strtotime($_REQUEST['billing_date']))."', 							
							`status` = '1',
							`id_shop` = '".$_SESSION['shop']."',							
							`id_renewal_required` = '".$_REQUEST['id_renewal_required']."'
							";
							
				if($_REQUEST['id_renewal_required']=='1'){									
					$addSql .= " ,`renewal_date` = '".date('Y-m-d' , strtotime($_REQUEST['renewal_date']))."'";
					$addSql .= "	,`id_renewal_status` = '".$_REQUEST['id_renewal_status']."'" ;
					
					if($_REQUEST['id_renewal_status']=='1'){									
					$addSql .= "	,`renewal_reference_no` = ''";
					}else{
					$addSql .= "	,`renewal_reference_no` = '".$_REQUEST['renewal_reference_no']."'";
					}
					
				}else{
					$addSql .= "	,`renewal_date` = ''";
					$addSql .= "	,`id_renewal_status` = '1'" ;
					$addSql .= "	,`renewal_reference_no` = ''";
					}	
							
							
							
							
				
							
				
							
							
							

							$addSql .= "	,`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."'
							";
			
			
		//	echo $addSql;
		//die;
				executeSql($addSql);

							$lastInsertId= $db->insert_id();

				//Indent Details Table Here Detault Value Set
				
			foreach($_REQUEST['dailypickup'] as $datapickup){
				
				//debugData($datapickup);
				
				
				
				
				$addSql = " INSERT INTO `daily_pickup_details` SET

							`id_daily_pickup` = '".$lastInsertId."', 
							`id_product` = '".$datapickup['id_inv_items']."',  
							`qty` = '".$datapickup['qty']."',  
							`rate` = '".$datapickup['rate']."',  
							`sales_revenue` = '".$datapickup['sales_revenue']."',  
							`cost` = '".$datapickup['cost']."',  
							`orginal_cost` = '".$datapickup['orginal_cost']."',  
							`comission` = '".$datapickup['comission']."', 
							`discount` = '".$datapickup['discount']."', 
							`other_expenses` = '".$datapickup['other_expenses']."', 
							`total_cost` = '".$datapickup['total_cost']."',
							`profit` = '".$datapickup['profit']."',
							`points` = '".$datapickup['points']."',
							`variable_in_rs` = '".$datapickup['variable_in_rs']."',
							`orginal_cost_active` = '".$datapickup['orginal_cost_active']."'
							
							";

							$addSql .= "	,`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."'";
			
			
					executeSql($addSql);
					
			}
			
			
				$_SESSION['successMsg'] = 'Daily Pickup details has been added sucessfully.';
				header("location:ManagerDailyPickup.php?page=".$_REQUEST['page']);
				exit;
			
		}else{ //UPDATE=========================================
		//debugData($_REQUEST); die;
			//debugData($_REQUEST); die;
				$editSql = "  UPDATE `daily_pickup` SET  
							`bill_no` = '".$_REQUEST['bill_no']."',
							`id_company` = '".$_REQUEST['id_company']."', 
							`id_contacts` = '".$_REQUEST['id_contacts']."', 
							`id_executive` = '".$_REQUEST['user_id']."',  
							`id_payment_status` = '".$_REQUEST['id_payment_status']."',  
							`doc_date` = '".date('Y-m-d' , strtotime($_REQUEST['createDate']))."',  
							`bill_date` = '".date('Y-m-d' , strtotime($_REQUEST['billing_date']))."', 
							`id_renewal_required` = '".$_REQUEST['id_renewal_required']."'";
							
							if($_REQUEST['id_payment_status']=='2'){									
							$editSql .= ", `payment_date` = '".date('Y-m-d' , strtotime($_REQUEST['payment_date']))."'";
					}else{
							$editSql .= ", `payment_date` = '0000-00-00'";
					}	
										
				if($_REQUEST['id_renewal_required']=='1'){									
					$editSql .= " ,`renewal_date` = '".date('Y-m-d' , strtotime($_REQUEST['renewal_date']))."'";
					$editSql .= "	,`id_renewal_status` = '".$_REQUEST['id_renewal_status']."'" ;
					
					if($_REQUEST['id_renewal_status']=='1'){									
					$editSql .= "	,`renewal_reference_no` = ''";
					}else{
					$editSql .= "	,`renewal_reference_no` = '".$_REQUEST['renewal_reference_no']."'";
					}
					
				}else{
					$editSql .= "	,`renewal_date` = ''";
					$editSql .= "	,`id_renewal_status` = '1'" ;
					$editSql .= "	,`renewal_reference_no` = ''";
					}	

							
							
							$editSql .= "	
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							
							WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'";
							
							executeSql($editSql);

						//Update Indent Details
						foreach($_REQUEST['dailypickup'] as $datapickup){
				
				//debugData($datapickup);
				
				
				if(!empty($datapickup['id_pickup_details'])){
				
				$editSql = " UPDATE  `daily_pickup_details` SET

							`id_daily_pickup` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."', 
							`id_product` = '".$datapickup['id_inv_items']."',  
							`qty` = '".$datapickup['qty']."',  
							`rate` = '".$datapickup['rate']."',  
							`sales_revenue` = '".$datapickup['sales_revenue']."',  
							`cost` = '".$datapickup['cost']."',  
							`orginal_cost` = '".$datapickup['orginal_cost']."',  
							`comission` = '".$datapickup['comission']."', 
							`discount` = '".$datapickup['discount']."', 
							`other_expenses` = '".$datapickup['other_expenses']."', 
							`total_cost` = '".$datapickup['total_cost']."',
							`profit` = '".$datapickup['profit']."',
							`points` = '".$datapickup['points']."',
							`variable_in_rs` = '".$datapickup['variable_in_rs']."',
							`orginal_cost_active` = '".$datapickup['orginal_cost_active']."'
							
							";

							$editSql .= "	
							,`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							 WHERE `id` = '".addslashes($datapickup['id_pickup_details'])."' and `id_daily_pickup` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'";
			
			
					executeSql($editSql);
				}else{
					
				
				$addSql = " INSERT INTO `daily_pickup_details` SET

							`id_daily_pickup` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."', 
							`id_product` = '".$datapickup['id_inv_items']."',  
							`qty` = '".$datapickup['qty']."',  
							`rate` = '".$datapickup['rate']."',  
							`sales_revenue` = '".$datapickup['sales_revenue']."',  
							`cost` = '".$datapickup['cost']."',  
							`orginal_cost` = '".$datapickup['orginal_cost']."',  
							`comission` = '".$datapickup['comission']."', 
							`discount` = '".$datapickup['discount']."', 
							`other_expenses` = '".$datapickup['other_expenses']."', 
							`total_cost` = '".$datapickup['total_cost']."',
							`profit` = '".$datapickup['profit']."',
							`points` = '".$datapickup['points']."',
							`variable_in_rs` = '".$datapickup['variable_in_rs']."',
							`orginal_cost_active` = '".$datapickup['orginal_cost_active']."'
							
							";

							$addSql .= "	,`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."'";
			
			
					executeSql($addSql);		
					}
					
			}
			
			
				$_SESSION['successMsg'] = 'Daily Pickup details has been added sucessfully.';
				header("location:ManagerDailyPickup.php?page=".$_REQUEST['page']);
				exit;
				
				
					
			
			
			}
	}
	}
	
	
//debugData($_REQUEST);
//debugData($_SESSION);


if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){

	//Indent Table

	$sql = "  SELECT * FROM `daily_pickup`
			WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";					
	 $db->query($sql);
	
	if($db->num_rows() > 0){
		$row = $db->fetch_object();
		
		
	}  
		  			 
}
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
	
		$doc_date	= stripslashes(date('d-m-Y',strtotime($row->doc_date)));
		$bill_date	= stripslashes(date('d-m-Y',strtotime($row->bill_date)));
		$payment_date	= stripslashes(date('d-m-Y',strtotime($row->payment_date)));
		$renewal_date	= $row->renewal_date=='0000-00-00'?'':stripslashes(date('d-m-Y',strtotime($row->renewal_date)));
		
		if($row->id_renewal_status=='1'){
		$ViewRenewalStatusRefNo='style="display:none"';
		}else{
			$ViewRenewalStatusRefNo='style="display:block"';			
			}
		if($row->id_renewal_required=='1'){
		$DisplayRenewalRequired='style="display:block"';
		}else{
			$DisplayRenewalRequired='style="display:none"';			
			}	

	 }else{
		 
		   $doc_date	=	date('d-m-Y');
		   $bill_date	=	date('d-m-Y');
		   $payment_date	=	date('d-m-Y');
		   $renewal_date	=	date('d-m-Y');
		   $dispalyBox ='style="display:none"';
		   $ViewRenewalStatusRefNo='style="display:none"';
		   $DisplayRenewalRequired='style="display:none"';
		   
	 }
	 
	 	
?>
<?php   

	if($_GET['eId'] == ''){
		$id_dailypickup_id =  encryptor(decrypt,$_GET['id_indent_id']);
	}else{
 
		$id_dailypickup_id = encryptor(decrypt,$_GET['id_indent_id']);
		encryptor(decrypt, $_REQUEST['eId']); 
 
	} 
?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>
<style>
.select2-container {
	width:100%!important;
}
table.dataTable tfoot th, table.dataTable tfoot td {
	border-top: none;
}
</style>
<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Edit Daily Pickup <small>Daily Report</small> </h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Manage Company</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="box box-default" style="margin-bottom : 0!important;"> 
      <!--########## Company Import jump#######--> 
      
      <!-- Modal -->
      <div class="modal fade" id="importComapnyModal" role="dialog" >
        <div class="modal-dialog"> 
          
          <!-- Modal content-->
          <div class="modal-content" style="width: 300px; margin: 0px auto;">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Import Company</h4>
              <br>
              <span id="returnTxt" style="color: Green;"></span> </div>
            <div class="modal-body">
              <form name="companyimport" method="post" enctype="multipart/form-data" id="companyimport">
                <div >
                  <label for="file">Choose File : <span style="color: red;">*</span></label>
                  <input type="file" name="companyImport" class="form-control" id="companyImport">
                </div>
                <br>
                <div >
                  <input type="submit" value="uplaod" name="submit" class="btn btn-primary" id="importCompany">
                  <span style="color:red;margin-left:50px; ">*</span> = Required 
                  Field<br>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      
      <!--########## Import Company  Modal End#######-->
      
      <div class="form-group has-error " align="center" style="display : none;">
        <?php if($_SESSION['errorMsg']){?>
        <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
        <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
        <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
        <?php unset($_SESSION['successMsg']);}?>
      </div>
      <form name="DailyPickupForm" id="DailyPickupForm" action="" method="get">
        <input type="hidden" value="1" name="searchFormSubmit" />
        <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" />
           <div class="box-body">
           
           
           <ul class="timeline">
  <!-- timeline item -->
  <li class="time-label"> <span class="bg-red"> Pickup  </span> </li>
  <li> <i class="fa fa-globe bg-red"></i>
    <div class="timeline-item">
     
          <div class="row"> 
            <!--<div class="col-md-6">
              <div class="form-group">
                <label>Company Name</label>				
				<input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />
              </div>
              
            </div>-->
            
            
            <div class="col-md-4">
              <div class="form-group">
                <label>Bill Date</label>
                <input type="text" class="form-control pickerdate_addreport" placeholder="Enter Enquiry date" id="createDate" name="createDate" value="<?php echo  $doc_date; ?>"  data-parsley-required>
              </div>
              <!-- /.form-group --> 
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Bill No.</label>
                
               <?php $billNumber = $row->bill_no; ?>
                <input class="form-control"  placeholder="Enter Bill No" id="bill_no" name="bill_no" value="<?php echo $billNumber; ?>"  data-parsley-required onkeyup="checkBillNo(this.value);"/>
                 <span id="bill_no_duplicate_error"></span>
              </div>
              <!-- /.form-group --> 
            </div>
            <div class="form-group col-md-4 ">
              <label>User/Executive</label>
              <!--<input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />-->
              <?php 
                        
                         
                         $categoryDropDown = '<select class="form-control select2 " name="user_id" id="user_id" data-parsley-errors-container="#user_idError" data-parsley-required>
                                                        <option value="">Select Executive</option>';
                                       
                                        $resUserLevel = selectSql(TBL_USERS," WHERE `status` = '1' AND  `id_shop` = '".addslashes($_SESSION['shop'])."' ".$teamMembers."  $UserRestriction ",' ORDER BY `name`');
                                        
                                        if($db->num_rows2($resUserLevel)){
                                          while($resultUserLevel = $db->fetch_object2($resUserLevel)){
                                         if($row->id_executive == $resultUserLevel->id){
									$selected = 'selected="selected"';
								}else{
									$selected = '';
								}
	
                                          $categoryDropDown .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'</option>';
                                        }
                                        }
                                        echo $categoryDropDown .= '</select>';
                                        ?>
                                        
                                         <span id="user_idError"></span> 
            </div>
            <!--Area Executive-->
            <div class="form-group col-sm-6">

                  <label for="id_company">Company Name - City </label>

                 <!-- <select class="form-control select2 itemName" name="id_company" id="id_company"  onChange="getExecutiveName(this.value,''); areaExecutive();" data-parsley-errors-container="#companyError" data-parsley-required >
 <?php
 /* if($_REQUEST['eId']!=''){
	 $CompanyName	=	 selectColumn(TBL_COMPANY,'CONCAT(name," - ",city)'," WHERE `id_company` = '".$row->id_company."'");
	 echo '<option  '.$selected.' value="'.$row->id_company.'">'.$CompanyName.'</option>';
 } */
 ?>

                  </select>-->
                  <div  class="input-group enquirypage" id="showcompanyby"> 
                      <select  class="form-control select2 itemName"   name="id_company" id="id_company" onChange="getExecutiveName(this.value,''); " data-parsley-errors-container="#idcompanyError"  data-parsley-required>   
                      <?php
                        if($_REQUEST['eId']!=''){
                          $CompanyName	=	 selectColumn(TBL_COMPANY,'CONCAT(name," - ",city)'," WHERE `id_company` = '".$row->id_company."'");
                          echo '<option  '.$selected.' value="'.$row->id_company.'">'.$CompanyName.'</option>';
                        } 
                        ?>
                    </select> 
                      <div class="input-group-addon companyby_open"> <i class="fa fa-plus"></i> </div>
                  </div>
                  <span style="color:red;" id="areaExe"></span>
                  <span id="idcompanyError"></span> </div>
           
            
            
                        
            <?php /*?><div class="col-md-3">
              <div class="form-group">
                <label>Bill Date</label>
                <input type="text" class="form-control pickerdate_addreport" placeholder="Enter Billing date" id="billing_date" name="billing_date" value="<?php echo  $bill_date; ?>"  data-parsley-required>
              </div>
              <!-- /.form-group --> 
            </div><?php */?>

            <div class="form-group col-sm-6">

                  <label for="id_contacts" >Person Met</label>

                  <div class="input-group" id="showbookedby">

                  <select class="form-control select2" name="id_contacts" id="id_contacts"  data-parsley-errors-container="#contactError" data-parsley-required>

                    <option value="">Select Person Met</option>

                  </select>

                 

                  <div class="input-group-addon bookedby_open"> <i class="fa fa-plus"></i> </div>

                  </div>
                   <span id="contactError"></span> 
                  
                  </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Payment Status</label>
                    <?php
                	if($row->id_payment_status==1)
                		$Pendingselect1 = 'selected="selected"';
                	else
                		$Pendingselect1 = '';

                	if($row->id_payment_status==2)
                		$Pendingselect2 = 'selected="selected"';
                	else
                		$Pendingselect2 = '';
                ?>
                <select class="form-control select2" name="id_payment_status" id="id_payment_status"  onChange="openpaymentDate(this.value);" data-parsley-errors-container="#id_payment_statusError" data-parsley-required>
                	<option  value="">--Payment Status--</option>
                	<option <?php echo $Pendingselect1; ?> value="1">Pending</option>
                	<option <?php echo $Pendingselect2; ?> value="2">Received</option>
                </select>
                
                <span id="id_payment_statusError"></span> 
                
               
              </div>
            </div>
            	<?php
                
				if($row->id_payment_status=='1'){
              							            
              		$dispalyBox ='style="display:none"';
              	}

              	//if($rowUserDetail->user_custom_smtp=='1'){
              							            
                    // $required="required";
              	//}
              		

              	?>            
                  
           <div  <?php echo $dispalyBox;?>  id="userpaymentdate"> 
            <div class="col-md-3">
              <div class="form-group">
                <label>Payment Date</label>
                <input type="text" class="form-control pickerdate_addreport" placeholder="Enter Payment date" id="payment_date" name="payment_date" value="<?php echo  $payment_date; ?>"  data-parsley-required>
              </div>
              <!-- /.form-group --> 
            </div>
            </div>
            
        </li>
        
  <li class="time-label"> <span class="bg-green"> Renewal</span> </li>
  <li> <i class="fa fa-window-maximize bg-green"></i>
    <div class="timeline-item">
      <div class="row">
        <div class="col-md-2">
          <div class="form-group">
          
          	<?php
                	if($row->id_renewal_required==1){
                		$renewal_required1 = 'selected="selected"';               	

                	}elseif($row->id_renewal_required==0){
                		$renewal_required0 = 'selected="selected"';
					}else{
                		$renewal_required0 = 'selected="selected"';
					}
                ?>
          
            <label for="offer_type">Renewal Required</label>
            <select name="id_renewal_required" id="id_renewal_required" class="form-control select2 select2-hidden-accessible" onchange="RenewalRequired(this.value);" tabindex="-1" aria-hidden="true">
              <option <?php echo $renewal_required1; ?> value="1">Yes</option>
              <option  <?php echo $renewal_required0; ?> value="0">No</option>
            </select>
             
            
            </div>
        </div>
        
        <div  <?php echo $DisplayRenewalRequired;?>  id="showRenewalRequired"> 
            <div class="col-md-3">
              <div class="form-group">
                <label>Renewal Date</label>
                <input type="text" class="form-control pickerdate_addreport" placeholder="Enter Renewal date" id="renewal_date" name="renewal_date" value="<?php echo  $renewal_date; ?>"  >
              </div>
              <!-- /.form-group --> 
            </div>
            
            
            <div class="col-md-2">
          <div class="form-group">
          
          <?php
                	if($row->id_renewal_status==1){
                		$renewal_status1 = 'selected="selected"';               	

                	}elseif($row->id_renewal_status==2){
                		$renewal_status2 = 'selected="selected"';
					}else{
                		$renewal_status3 = 'selected="selected"';
					}
                ?>
            <label for="offer_type">Renewal Status</label>
            <select name="id_renewal_status" id="id_renewal_status" class="form-control select2 select2-hidden-accessible" onchange="inputrenewal_status(this.value);" tabindex="-1" aria-hidden="true">
            <option  <?php echo $renewal_status3; ?> value="">--Renewal Status--</option>
              <option <?php echo $renewal_status1; ?> value="1">Pending</option>
              <option <?php echo $renewal_status2; ?> value="2">Done</option>
            </select>
            
            </div>
        </div>
         <div  <?php echo $ViewRenewalStatusRefNo;?>  id="showRenewalStatusRefNo"> 
           <div class="col-md-3">
              <div class="form-group">
                <label>Reference no.</label>
                
               <?php $renewal_reference_no = $row->renewal_reference_no; ?>
                <input class="form-control"  placeholder="Enter Reference No" id="renewal_reference_no" name="renewal_reference_no" value="<?php echo $renewal_reference_no; ?>"   />
                 <span id="renewal_reference_no_error"></span>
              </div>
              <!-- /.form-group --> 
            </div> 
           </div> 
            </div>
        
        
        
      </div>
    </div>
        
        </ul>  
          
          
            <!-- /.row --> 
          </div>
        
        <!-- /.box-body -->
        <div class="box-body table-responsive">
          <table id="myTable1" class="table table-striped  table-bordered dataTable no-footer   order-list1 max-h2">
            <thead>
              <tr>
                <th style=" width:200px;padding: 5px 9px;">Item Code</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Sale Revenue</th>
                <th>Cost</th>
                <th>Comission</th>
                <th>Discount</th>
                <th>Other Expenses</th>
                <?php /*?> <th>Total Cost</th>
                <th>Profit</th>
                <th>Points</th>
                <th>Variable In Rs</th><?php */?>
              </tr>
            </thead>
            <?php 
				$TextBoxHidden='hidden';
				
				$k=1;?>
            <tbody id="tableBody">
            
           <?php if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
			   
			   $sql2 = "  SELECT * FROM  `daily_pickup_details` WHERE  `id_daily_pickup` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."' ";
								 $db->query($sql2); 
$k=1;
								while($rowsID = $db->fetch_object()){
							 		 $k++;
									 
									 
								if($rowsID->orginal_cost_active=='0'){
									
									$TextBoxHiddenOrginalCost='hidden';
									
									}	else{
										$TextBoxHiddenOrginalCost='text';
										} 
									 
									
									 ?>
           
          
           <tr> 
           <input type="hidden"  autocomplete="off"  name="dailypickup[<?php echo $k;?>][id_pickup_details]" id="id_pickup_details<?php echo $k;?>"  class="form-control" value="<?php echo $rowsID->id; ?>"  />
           
                <td><select name="dailypickup[<?php echo $k;?>][id_inv_items]" id="id_inv_items<?php echo $k;?>" class="form-control select2" style="width:100%" data-parsley-required data-parsley-errors-container="#outletError7" onchange="GetProductCast(this.value,<?php echo $k;?>)" required>
                    <option>Select Item Code</option>
                    <?php 
    $sqlProduct = "SELECT * FROM fs_hotels WHERE id_shop = '".addslashes($_SESSION['shop'])."'";
    $Query	=	mysqli_query($connNew,  $sqlProduct); 
    while($rowProduct = mysqli_fetch_object($Query)){ 
								if($rowsID->id_product == $rowProduct->id){
									$selected = 'selected="selected"';
								}else{
									$selected = '';
								}
	
	?>
                    <option value="<?php echo $rowProduct->id; ?>" <?php echo $selected; ?>><?php echo addslashes($rowProduct->name); ?></option>
                    <?php } 
?>
                  </select></td>
                <td><input type="text"  autocomplete="off"  name="dailypickup[<?php echo $k;?>][qty]" id="qty<?php echo $k;?>" placeholder="0" onkeyup="qtycalc_rows(this.id,<?php echo $k;?>)" class="form-control" value="<?php echo $rowsID->qty; ?>" required /></td>
                <td><input type="text"  autocomplete="off"  name="dailypickup[<?php echo $k;?>][rate]" id="rate<?php echo $k;?>" placeholder="0" onkeyup="qtycalc_rows(this.id,<?php echo $k;?>)" class="form-control" value="<?php echo $rowsID->rate; ?>"   /></td>
                <td><input type="text"  autocomplete="off"  name="dailypickup[<?php echo $k;?>][sales_revenue]" id="sales_revenue<?php echo $k;?>" placeholder="0"  class="form-control" value="<?php echo $rowsID->sales_revenue; ?>" readonly=""  /></td>
                
                <td><input type="<?php echo $TextBoxHiddenOrginalCost; ?>"  autocomplete="off"  name="dailypickup[<?php echo $k;?>][cost]" id="cost<?php echo $k;?>" placeholder="0"  class="form-control" value="<?php echo $rowsID->cost; ?>"  onkeyup="CostFormula(this.id,<?php echo $k;?>)" />
                
                  <input type="hidden"  autocomplete="off"  name="dailypickup[<?php echo $k;?>][orginal_cost]" id="orginal_cost<?php echo $k;?>" placeholder="0"  class="form-control" value="<?php echo $rowsID->orginal_cost; ?>" onkeyup="orginal_cost_rows(this.id,<?php echo $k;?>)" />
                  
                  <input type="hidden"  autocomplete="off"  name="dailypickup[<?php echo $k;?>][orginal_cost_active]" id="orginal_cost_active<?php echo $k;?>" placeholder="0"  class="form-control" value="<?php echo $rowsID->orginal_cost_active; ?>"  /></td>
                  
                <td><input type="text"  autocomplete="off"  name="dailypickup[<?php echo $k;?>][comission]" id="comission<?php echo $k;?>" placeholder="0"  class="form-control" value="<?php echo $rowsID->comission; ?>"  required onkeyup="qtycalc_rows(this.id,<?php echo $k;?>)" /></td>
                
                <td><input type="text"  autocomplete="off"  name="dailypickup[<?php echo $k;?>][discount]" id="discount<?php echo $k;?>" placeholder="0"  class="form-control" value="<?php echo $rowsID->discount; ?>"  required onkeyup="qtycalc_rows(this.id,<?php echo $k;?>)" /></td>
                
                <td><input type="text"  autocomplete="off"  name="dailypickup[<?php echo $k;?>][other_expenses]" id="other_expenses<?php echo $k;?>" placeholder="0"  class="form-control" value="<?php echo $rowsID->other_expenses; ?>"  required onkeyup="qtycalc_rows(this.id,<?php echo $k;?>)" /></td>
                
                <input type="<?php echo $TextBoxHidden; ?>"  autocomplete="off"  name="dailypickup[<?php echo $k;?>][total_cost]" id="total_cost<?php echo $k;?>" placeholder="0"  class="form-control" value="<?php echo $rowsID->total_cost; ?>"  readonly="" />
                
                <input type="<?php echo $TextBoxHidden; ?>"  autocomplete="off"  name="dailypickup[<?php echo $k;?>][profit]" id="profit<?php echo $k;?>" placeholder="0"  class="form-control" value="<?php echo $rowsID->profit; ?>"  readonly="" />
                
                <input type="<?php echo $TextBoxHidden; ?>"  autocomplete="off"  name="dailypickup[<?php echo $k;?>][points]" id="points<?php echo $k;?>" placeholder="0"  class="form-control" value="<?php echo $rowsID->points; ?>"  readonly="" />
                
                <input type="<?php echo $TextBoxHidden; ?>"  autocomplete="off"  name="dailypickup[<?php echo $k;?>][variable_in_rs]" id="variable_in_rs<?php echo $k;?>" placeholder="0"  class="form-control" value="<?php echo $rowsID->variable_in_rs; ?>"  readonly="" />
                
                
                <td><a class="btn btn-danger ibtnEditDelete" id="<?php echo $rowsID->id; ?>" style="cursor:pointer;" title="Delete"><i class="fa fa-trash-o"></i></a></td>
                <td style="text-align : center!important;">&nbsp;</td>
              </tr>
		   
		    <?php } ?>
		  <?php }else{?>
            
            
              <tr>
             
                <td><select name="dailypickup[<?php echo $k;?>][id_inv_items]" id="id_inv_items<?php echo $k;?>" class="form-control select2" style="width:100%" data-parsley-required data-parsley-errors-container="#outletError7" onchange="GetProductCast(this.value,<?php echo $k;?>)" required>
                    <option>Select Item Code</option>
                    <?php 
    $sql = "SELECT * FROM fs_hotels WHERE id_shop = '".addslashes($_SESSION['shop'])."'";
    $db->query($sql); 
    while($row1 = $db->fetch_object()){ ?>
                    <option value="<?php echo $row1->id; ?>"><?php echo addslashes($row1->name); ?></option>
                    <?php } 
?>
                  </select></td>
                <td><input type="text"  autocomplete="off"  name="dailypickup[<?php echo $k;?>][qty]" id="qty<?php echo $k;?>" placeholder="0" onkeyup="qtycalc_rows(this.id,<?php echo $k;?>)" class="form-control" value="1" required /></td>
                <td><input type="text"  autocomplete="off"  name="dailypickup[<?php echo $k;?>][rate]" id="rate<?php echo $k;?>" placeholder="0" onkeyup="qtycalc_rows(this.id,<?php echo $k;?>)" class="form-control" value="0"   /></td>
                <td><input type="text"  autocomplete="off"  name="dailypickup[<?php echo $k;?>][sales_revenue]" id="sales_revenue<?php echo $k;?>" placeholder="0"  class="form-control" value="<?php if($_POST) echo $_POST['sales_revenue'];else echo stripslashes($array['sales_revenue'.''.$j]); ?>" readonly=""  /></td>
                
                
                <td><input type="text"  autocomplete="off"  name="dailypickup[<?php echo $k;?>][cost]" id="cost<?php echo $k;?>" placeholder="0"  class="form-control" value="0"  onkeyup="CostFormula(this.id,<?php echo $k;?>)" />
                  <input type="hidden"  autocomplete="off"  name="dailypickup[<?php echo $k;?>][orginal_cost]" id="orginal_cost<?php echo $k;?>" placeholder="0"  class="form-control" value="0"  /><input type="hidden"  autocomplete="off"  name="dailypickup[<?php echo $k;?>][orginal_cost_active]" id="orginal_cost_active<?php echo $k;?>" placeholder="0"  class="form-control" value="0"  /></td>
                <td><input type="text"  autocomplete="off"  name="dailypickup[<?php echo $k;?>][comission]" id="comission<?php echo $k;?>" placeholder="0"  class="form-control" value="0" required onkeyup="qtycalc_rows(this.id,<?php echo $k;?>)" /></td>
                <td><input type="text"  autocomplete="off"  name="dailypickup[<?php echo $k;?>][discount]" id="discount<?php echo $k;?>" placeholder="0"  class="form-control" value="0" required onkeyup="qtycalc_rows(this.id,<?php echo $k;?>)" /></td>
                <td><input type="text"  autocomplete="off"  name="dailypickup[<?php echo $k;?>][other_expenses]" id="other_expenses<?php echo $k;?>" placeholder="0"  class="form-control" value="0" required onkeyup="qtycalc_rows(this.id,<?php echo $k;?>)" /></td>
                 <input type="<?php echo $TextBoxHidden; ?>"  autocomplete="off"  name="dailypickup[<?php echo $k;?>][total_cost]" id="total_cost<?php echo $k;?>" placeholder="0"  class="form-control" value="0" readonly="" />
                <input type="<?php echo $TextBoxHidden; ?>"  autocomplete="off"  name="dailypickup[<?php echo $k;?>][profit]" id="profit<?php echo $k;?>" placeholder="0"  class="form-control" value="0" readonly="" />
               <input type="<?php echo $TextBoxHidden; ?>"  autocomplete="off"  name="dailypickup[<?php echo $k;?>][points]" id="points<?php echo $k;?>" placeholder="0"  class="form-control" value="0" readonly="" />
                <input type="<?php echo $TextBoxHidden; ?>"  autocomplete="off"  name="dailypickup[<?php echo $k;?>][variable_in_rs]" id="variable_in_rs<?php echo $k;?>" placeholder="0"  class="form-control" value="0" readonly="" />
                <?php /*?><td style="text-align : center!important;"><a class="btn btn-danger" style="cursor:pointer;" title="Delete"><i class="fa fa-trash-o"></i></a></td>
					<?php */?>
                <td style="text-align : center!important;">&nbsp;</td>
              </tr>
              <?php } ?>
              
              
              
            </tbody>
            <tfoot>
              <tr>
                <td colspan="13" style="text-align:right;">
                  <a  type="button" class="btn n-btn btn-block" style="background-color: #00a65a;color:#fff" id="addrow1" value="Add Row" /> <i class="fa fa-plus"></i> Add Row</a></td>
              </tr>
              <tr> </tr>
            </tfoot>
            <?php  
				 if($row->id ==''){
						$counts 	= $k++;
				 }else{
						$counts 	= $k++;
				 }
				 
									 
				                	 ?>
            <input type="hidden" name="counter1" id="counter1" value="<?php echo $counts; ?>" >
          </table>
          <hr class="br-line mb-10">
          <div class="box-footer">
            <input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save"  onclick="SaveDailyPickup();">
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("ManagerDailyPickup.php"); '>
          </div>
        </div>
      </form>
    </div>
    <div class="row">
      <div class="col-xs-12"> 
        <!-- /.box -->
        <div class="box">
          <style>
			  .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
    padding: 4px;
    line-height: 1.42857143;
				  vertical-align: middle;}
			   
			 .table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
    border: 1px solid #cec9c9;
}
			  </style>
          
          <!-- /.box-body --> 
        </div>
        <!-- /.box --> 
      </div>
      <!-- /.col --> 
    </div>
    <!-- /.row --> 
  </section>
  <!-- /.content --> 
</div>
<!-- jQuery -->
<div id="companyby" class="well" style="width:50%;">
        <h3>Add Company</h3>
           <!-- form start -->
         <?php    $companySql = "  SELECT * FROM `".TBL_COMPANY."`
                WHERE `id_company` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
  $db->query($companySql);
  if($db->num_rows() > 0){
    $companyrow = $db->fetch_object();

  }
  ?>
          <form   id="companybypopupform" method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off" >
        <input type="hidden" id="EditCompanyID" name="EditCompanyID" value="<?php echo $companyrow->id_company; ?>" > 
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
                   <?php $categoryDropDown = '<select class="form-control input-sm" name="designation" id="designation" data-parsley-errors-container="#designationError" data-parsley-required   >
                                 <option value="">Select Company Group</option>';
                                
                                 $resCat = selectSql(TBL_GROUP," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `id_group`');            
                                 if($db->num_rows2($resCat)){
                                 while($resultCat = $db->fetch_object2($resCat)){
                                     
                                   $categoryDropDown .= '<option  value="'.$resultCat->id_group.'">'.ucfirst($resultCat->name).'</option>';
                                 }
                                 }
                                 echo $categoryDropDown .= '</select>';
                                 ?>
                </div>
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


               <!-- <div class="form-group col-sm-4">
                  <label for="email">Email Id<font color="#FF0000">*</font></label>
                  <input type="email" class="form-control"  placeholder="Enter email id" id="email" name="email"  >
                  </div>-->

                   <div class="form-group col-sm-4">
                  <label for="id_country" >Country<font color="#FF0000">*</font></label>               
                                 <?php $countryDropDown = '<select class="form-control input-sm" name="id_country" id="id_country" data-parsley-errors-container="#countryError" onchange="getState(this.value);"  data-parsley-required   >
                                 <option value="110">India</option>';                    
                                $resCat = selectSql(TBL_COUNTRY_LANG,"where id_lang='1' ",' ORDER BY `name`');       
                                 if($db->num_rows2($resCat)){
                                 while($resultCat = $db->fetch_object2($resCat)){
                                     
                                   $countryDropDown .= '<option  value="'.$resultCat->id_country.'">'.ucfirst($resultCat->name).'</option>';
                                 }
                                 }
                                 echo $countryDropDown .= '</select>';
                                 ?>
                  <span id="countryError"></span> </div>
              </div>


             <div class="row">
               <!-- <div class="form-group  col-sm-4">
                  <label for="mobile">Mobile Number<font color="#FF0000">*</font></label>
                  <input type="text" class="form-control" placeholder="Enter mobile number" id="mobile" name="mobile" data-parsley-type="digits" data-parsley-length="[10, 10]" >
                  <?php // echo $err_mobile;?> </div>-->
              

                          <div class="form-group col-sm-4">
                  <label for="id_state">State <font color="#FF0000">*</font></label>
                  <div id="state">
                    <?php $stateDropDown = '<select class="form-control input-sm" name="id_state" id="id_state" data-parsley-errors-container="#stateError"  data-parsley-required   >
                                 <option value="346">Delhi</option>';                    
                                $resCat = selectSql(TBL_STATE,"where status='1' and id_country=110  ",' ORDER BY `name`');       
                                 if($db->num_rows2($resCat)){
                                 while($resultCat = $db->fetch_object2($resCat)){
                                     
                                   $stateDropDown .= '<option  value="'.$resultCat->id_state.'">'.ucfirst($resultCat->name).'</option>';
                                 }
                                 }
                                 echo $stateDropDown .= '</select>';
                                 ?>
                   </div>              
                  <!--  <div id="state">
                  <select class="form-control" name="id_state" id="id_state" data-parsley-errors-container="#stateError">
                      <option value="346">Delhi</option>
                    </select>
                  </div>-->
                  <span id="stateError"></span> </div>


               <div class="form-group col-sm-4">
                  <label for="name">City<font color="#FF0000">*</font></label>
                  <input autocomplete="off" type="text" class="form-control awesomplete" data-list="#citylist" placeholder="Enter City" id="city" name="city" data-parsley-required >
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

                   <div class="form-group col-sm-3">
                  <label for="area">Area<font color="#FF0000">*</font></label>
                  <?php $areaDropDown = '<select class="form-control input-sm" name="area" id="area"  onchange="areaOnChg(this.value);"  data-parsley-required   >
                                 <option value="">Select Area</option>';                    
                               $resCat = selectSql(TBL_AREAS," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');
                                 if($db->num_rows2($resCat)){
                                 while($resultCat = $db->fetch_object2($resCat)){
                                     
                                   $areaDropDown .= '<option  value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
                                 }
                                 }
                                 echo $areaDropDown .= '</select>';
                                 ?>  

                  <span id="areaError"><?php echo $err_area;?></span> 
              <span id="areaExe" style="color: red"></span> </div>
                </div><!--end of row-->  
 
                <div class="otherdet">
                  <span>Other Details</span>
                  <hr>
                </div>  
        

              <div class="row">
                <div class="form-group col-sm-4">
                  <label for="secondary_email">Seconday Email</label>
                  <input type="text" class="form-control" placeholder="Enter seconday email id" id="secondary_email" name="secondary_email"  data-parsley-type="email"  >
                  <?php echo $err_email;?> </div>
                <div class="form-group  col-sm-4">
                  <label for="phone">Phone Number</label>
                  <input type="text" class="form-control" placeholder="Enter phone number" id="phone" name="phone"  >
                  <?php echo $err_phone;?> </div>
              
            <!--   </div>
              <div class="row">-->
                <div class="form-group col-sm-4">
                  <label for="fax">GST Number</label>
                  <input type="text" class="form-control" placeholder="Enter fax number" id="fax" name="fax" >
                  <?php echo $err_fax;?> </div>
                <div class="form-group col-sm-4">
                  <label for="address">Address</label>
                  <textarea class="form-control" name="address" id="address"  rows="1" placeholder="Enter Address">
</textarea>
                  <?php echo $err_address;?> </div>

                   <div class="form-group col-sm-4">
                  <label for="postcode">Pincode</label>
                  <input type="text" class="form-control" placeholder="Enter pincode" id="postcode" name="postcode" >
                  <?php echo $err_postcode;?> </div>
               <!-- <div class="form-group col-sm-4">
                  <label for="city">City<font color="#FF0000">*</font></label>
                  <input type="text" class="form-control" placeholder="Enter city" id="city" name="city" value="<?php if($_POST) echo $_POST['city'];else echo $row->city;?>" data-parsley-required>
                  <?php echo $err_city;?> </div>
              -->


                <!--<div class="form-group col-sm-4">

                <label for="city">City </label>

                <select class="form-control select2 itemName" name="city" id="city"   >

                </select>
             </div> --> 

             <!-- </div>
            
              <div class="row">-->
                <div class="form-group col-sm-4">
                  <label for="details">Details</label>
                  <textarea class="form-control" name="details" id="details"  rows="1" placeholder="Enter Details" automcomplete="off"><?php if($_POST) echo $_POST['details'];else echo $row->details;?>
          </textarea>
                  <?php echo $err_details;?> </div>
               
                

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

              <!--
                </div>
              <div class="row">-->
                <div class="form-group col-sm-4">
                  <label for="deals_in">Deals In</label>
                   <?php $dealsInDropDown = '<select class="form-control input-sm" name="deals_in" id="deals_in"  data-parsley-errors-container="#deals_inError">
                                 <option value="">Select Company Domain</option>';                    
                              $resCat = selectSql(TBL_COMPANY_AREA," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');
                                 if($db->num_rows2($resCat)){
                                 while($resultCat = $db->fetch_object2($resCat)){
                                     
                                   $dealsInDropDown .= '<option  value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
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

                  


                      
                  </div>
              </div>
        
    
     
            <div class="form-group col-sm-12" align="left">
              <input  type="button" class="btn btn-default" onClick="saveRateCompanyPopupform();" value="Save">
              <button class="companyby_close btn btn-default">Close</button>
            </div>

          </form>
    </div>
    
    
    
    
    
    
    <!--end--> 
    <!--Company Modal Plus Ends-->
    
    
    <div id="bookedby" class="well" style="width:50%;">
  <form id="bookedbypopupform" data-parsley-validate autocomplete="off" method="post"  >
    <?php $id_contact=selectColumn(TBL_CUSTOMER,'id_customer','WHERE id_company="'.$row->id_company.'" AND id_customer="'.$row->id_contacts.'" ');?>
  <input type="hidden" id="EditCustomerID" name="EditCustomerID" value="<?php echo $id_contact; ?>" >
  
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
      <label for="first_name">First Name <font color="#FF0000">*</font></label>
      <input type="text" class="form-control input-sm" placeholder="Enter first name" id="first_name" name="first_name" value="" data-parsley-required data-parsley-type="alphanum">
    </div>
   <div class="form-group col-sm-4">
      <label for="last_name">Last Name <font color="#FF0000">*</font></label>
      <input type="text" class="form-control input-sm" placeholder="Enter last name" id="last_name" name="last_name" value="" data-parsley-required>
    </div>
   <div class="form-group col-sm-4">
      <label for="email" >Email Id</label>
      <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email Id" data-parsley-type="email" automcomplete="off" >
    </div>
   <div class="form-group col-sm-4">
      <label for="mobile" >Mobile No. <font color="#FF0000">*</font></label>
      <input type="phone" name="mobile" id="mobile" class="form-control" placeholder="Enter mobile number"  data-parsley-type="digits" data-parsley-length="[10, 10]" automcomplete="off" data-parsley-required>
    </div>
    
    
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
    
    
      <div class="form-group col-sm-6">

        <label for="mobile" style="width: 100%;">DOB.</label>
<select name="dateofBirthMonth" id="dateofBirthMonth" class="form-control" style="width:50% !important; float:left">
        <option value="">Month</option>
         <?php 
     for($i = 1; $i <= 12; $i++){
    $dt = DateTime::createFromFormat('!m', $i);
    echo "<option value=\"$i\">".$dt->format('F')."</option>";
}
     ?>
</select>

<select name="dateofBirthday" id="dateofBirthday" class="form-control" style="width:50%;">
         <option value="">Day</option>
     <?php 
     for($Birthday = 1; $Birthday <= 31; $Birthday++){
    echo "<option value=\"$Birthday\">$Birthday</option>";
} 
     ?>
</select>

      </div>
      
     
      
     <div class="form-group col-sm-6">

        <label for="mobile" style="width: 100%;" >DOA.</label>

        <select name="dateofanniversaryMonth" id="dateofanniversaryMonth" class="form-control" style="width:50% !important; float:left">
        <option value="">Month</option>
         <?php 
     for($i = 1; $i <= 12; $i++){
    $dt = DateTime::createFromFormat('!m', $i);
    echo "<option value=\"$i\">".$dt->format('F')."</option>";
}
     ?>
</select>

<select name="dateofanniversaryday" id="dateofanniversaryday" class="form-control" style="width:50%;">
         <option value="">Day</option>
     <?php 
     for($DayS = 1; $DayS <= 31; $DayS++){
    echo "<option value=\"$DayS\" >$DayS</option>";
} 
     ?>
</select>
      </div>
     <div class="form-group col-sm-12" align="left"> 
    <input  type="button" class="btn btn-default" onClick="saveRateCustomerPopupform();" value="Save">
    <button class="bookedby_close btn btn-default">Close</button>
    </div>
  </form>
</div>  

 <div id="guest" class="well">

    <form id="guestpopupform" data-parsley-validate autocomplete="off" method="post"  >

       <div class="form-group">

        <label class="title">Title</label>

        <select name="title"  class="form-control input-sm" data-parsley-required >

           <option value="">-Select-</option>

           <option value="Dr.">Dr.</option>

           <option value="Miss.">Miss.</option>

           <option value="Mr.">Mr.</option>

           <option value="Mrs.">Mrs.</option>

           <option value="Ms.">Ms.</option>

           <option value="Pr.">Pr.</option>

           <option value="Prof.">Prof.</option>

           <option value="Rev.">Rev.</option>

           <option value="Group.">Group.</option>

         </select>

      </div>

       <div class="form-group">

        <label for="first_name">First Name</label>

        <input type="text" class="form-control input-sm" placeholder="Enter first name" id="first_name" name="first_name" value="" data-parsley-required >

      </div>

       <div class="form-group">

        <label for="last_name">Last Name</label>

        <input type="text" class="form-control input-sm" placeholder="Enter last name" id="last_name" name="last_name" value="" data-parsley-required>

      </div>

       <div class="form-group">

        <label for="email" >Email Id</label>

        <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email Id" data-parsley-type="email" automcomplete="off">

      </div>

       <div class="form-group">

        <label for="mobile" >Mobile No.</label>

        <input type="phone" name="mobile" id="mobile" class="form-control" placeholder="Enter mobile number"  data-parsley-type="digits" data-parsley-length="[10, 10]" automcomplete="off">

      </div>

       <div class="form-group">

        <label for="id_country" >Country</label>

        <select class="form-control" name="id_country" id="id_country" data-parsley-required>

           <option value="">Select Country</option>

           <?php 

						$resCat = selectSql(TBL_COUNTRY_LANG,"where id_lang='1' ",' ORDER BY `name`');

									  

										while($resultCat = $db->fetch_object2($resCat)){

											

													

											$countryDropDown .= '<option  value="'.$resultCat->id_country.'">'.ucfirst($resultCat->name).'</option>';

									  }

												  echo $countryDropDown;

									

									 ?>

         </select>

      </div>

       <div class="form-group">

        <label class="user_type">Guest type</label>

        <select name="user_type"  class="form-control input-sm"  >

           <option value="">-Select-</option>

           <option value="VIP">VIP</option>

           <option value="CIP">CIP</option>

         </select>

      </div>

       <input  type="button" class="btn btn-default" onClick="saveGuestPopupform();" value="Save">

       <button class="guest_close btn btn-default">Close</button>

     </form>

  </div>

   

  <span class="my_popup_open" style="display:none;"></span>

  <div id="my_popup" class="well">

    <div id="FollowUpNextUpdate"></div>

    <button id='my_popup_ok'  class="my_popup_close btn btn-default pull-left">Yes</button>
    <button id='alertForMail'  class="my_popup_close btn btn-default pull-left">No</button>

    <!--<button  style="margin-left: 5px;" class="btn btn-default pull-left">Preview</button>
    <button id="my_popup_no" style="margin-left: 5px;" class="my_popup_close btn btn-default pull-left">Close and send mail</button>-->

  </div>
  
   <span class="enquiry_failed_open" style="display:none;"></span>
    <div id="enquiry_failed" class="well">
      <div id="FollowUpNextUpdateerror"> </div>
      <br />
      <button id="enquiry_failed_no" style="margin-left: 5px;" class="enquiry_failed_close btn btn-default pull-left">Close</button>
      <button class="enquiry_failed_close btn btn-default pull-right"></button>
    </div>

    
    
    
    
    
    
    
    
    
    
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>

<!-- Bootbox -->
<script type="text/javascript">

<?php if($row->id != ''){ ?>



window.onload = function() { 		

		getExecutiveName(<?php echo $row->id_company; ?>,<?php echo $row->id_contacts; ?>);

							

							};

							

<?php } ?>
   function openpaymentDate(val){ 
		if(val==2){
			$("#userpaymentdate").show();								

		}
		else{
			$("#userpaymentdate").hide();
		
		}
	}
	
	
   function RenewalRequired(val){ 
		if(val==1){ 
									$("#showRenewalRequired").show();
									//$("#smtp_password").attr('required','true');
									//$("#renewal_reference_no").attr('required','true');

		}
		else{
					$("#renewal_reference_no").removeAttr('required');
					$("#showRenewalRequired").hide();
						//$("#smtp_password").removeAttr('required');
					   


		}
	}
	
	  function inputrenewal_status(val){ 
		if(val==2){
						$("#showRenewalStatusRefNo").show();
						$("#renewal_reference_no").attr('required','true');
									//$("#smtp_password").attr('required','true');
									//$("#smtp_email").attr('required','true');

		}
		else{ 
		
					$("#renewal_reference_no").removeAttr('required');
					$("#showRenewalStatusRefNo").hide();
					
						//$("#smtp_password").removeAttr('required');
					  // $("#smtp_email").removeAttr('required');


		}
	}
	 
            
	
	
	
$("table.order-list1").on("click", ".ibtnEditDelete", function (event) {
    var clickedButton = $(this); // Store the reference to $(this)

    var id = clickedButton.attr("id");

    bootbox.confirm({
        title: "Daily Pickup",
        message: "Are you sure you want to Delete?",
        buttons: {
            cancel: {
                label: '<i class="fa fa-times"></i> Cancel'
            },
            confirm: {
                label: '<i class="fa fa-check"></i> Confirm'
            }
        },
        callback: function (result) {
            if (result == true) {
                $.ajax({
                    type: "POST",
                    url: "ajax/ajaxDeleteDailyPickup.php",
                    data: {value: id},
                    success: function (result) {
                        var mydata = JSON.parse(result);
                        alert(mydata.successMsg);
                        clickedButton.closest("tr").remove(); // Use clickedButton here
                    }
                });
            }
        }
    });
});


checkBillNo = (val) =>{ 
		$.ajax({
			'url':'ajax/ajaxCheckBillNumbers.php',//registrationVal.ajax.php',
			'type':'post',
			'data':'bill_no='+val,
			success:(data) => {
				if(data==1){
					$("#bill_no").css("border","2px solid red");
					$('#bill_no_duplicate_error').html('<span style="color:red;align:center;">This Bill No is already Exist.</span>');
				}
				else{
					$("#bill_no").css("border","2px solid green");
					$("#bill_no_duplicate_error").html('');
				}
			}
		});
	}
</script>
<?php include_once("includes/footer.php")?>
 

<script type="text/javascript">
$('.select2').each(function () {
    $(this).select2({
        dropdownParent: $(this).parent(),// fix select2 search input focus bug
    })
})

// fix select2 bootstrap modal scroll bug
$(document).on('select2:close', '.select2', function (e) {
    var evt = "scroll.select2"
    $(e.target).parents().off(evt)
    $(window).off(evt)
})

	$(".itemName").select2({ 
		width: '120' 
	});

var counter1 =  document.getElementById("counter1").value;  

	 

    $("#addrow1").on("click", function () { 
       
        counter1++;  

        var newRow1 = $("<tr >");
        var cols1 = ""; 
      
		
		cols1 += '<td><select onchange="GetProductCast(this.value,' + counter1 + ')" name="dailypickup[' + counter1 + '][id_inv_items]" id="id_inv_items' + counter1 + '" class="form-control select2" style="width:100%" data-parsley-required data-parsley-errors-container="#outletError7" required><option>Select Item Code</option><?php 
    $sql = "SELECT * FROM fs_hotels WHERE id_shop = '".addslashes($_SESSION['shop'])."'";
    $db->query($sql); 
    while($row1 = $db->fetch_object()){ ?>
    <option value="<?php echo $row1->id; ?>"><?php echo addslashes($row1->name); ?></option> <?php } 
?></select><span id="outletError7"></span></td>';

$(document).on('select2:select', '.select2-results__option', function() {
    $(this).closest('.select2-container').prev('select').select2('close');
});

		
		

		cols1 += '<td><input  type="text"  autocomplete="off" placeholder="0" class="form-control" value="1"   name="dailypickup[' + counter1 + '][qty]" id="qty' + counter1 + '" required onkeyup="qtycalc_rows(this.id,' + counter1 + ')" /></td>';  

cols1 += '<td><input  type="text"  autocomplete="off" placeholder="0" class="form-control" value="0"   name="dailypickup[' + counter1 + '][rate]" id="rate' + counter1 + '" required  onkeyup="qtycalc_rows(this.id,' + counter1 + ')" /></td>';

cols1 += '<td><input  type="text"  autocomplete="off" placeholder="0" class="form-control"   name="dailypickup[' + counter1 + '][sales_revenue]" id="sales_revenue' + counter1 + '" required  readonly="" onkeyup="qtycalc_rows(this.id,' + counter1 + ')"/></td>';



cols1 += '<td><input  type="text"  autocomplete="off" placeholder="0" class="form-control" value="0"   name="dailypickup[' + counter1 + '][cost]" id="cost' + counter1 + '" required   onkeyup="CostFormula(this.id,' + counter1 + ')"/>';  
cols1 += '<input  type="hidden"  autocomplete="off" placeholder="0" class="form-control" value="0"   name="dailypickup[' + counter1 + '][orginal_cost]" id="orginal_cost' + counter1 + '" required  readonly=""/><input  type="hidden"  autocomplete="off" placeholder="0" class="form-control" value="0"   name="dailypickup[' + counter1 + '][orginal_cost_active]" id="orginal_cost_active' + counter1 + '" required  readonly=""/></td>';



cols1 += '<td><input  type="text"  autocomplete="off" placeholder="0" class="form-control" value="0"   name="dailypickup[' + counter1 + '][comission]" id="comission' + counter1 + '" required onkeyup="qtycalc_rows(this.id,' + counter1 + ')"/></td>';  

cols1 += '<td><input  type="text"  autocomplete="off" placeholder="0" class="form-control"  value="0"   name="dailypickup[' + counter1 + '][discount]" id="discount' + counter1 + '" required onkeyup="qtycalc_rows(this.id,' + counter1 + ')"/></td>';  

cols1 += '<td><input  type="text"  autocomplete="off" placeholder="0" class="form-control"  value="0"   name="dailypickup[' + counter1 + '][other_expenses]" id="other_expenses' + counter1 + '" required onkeyup="qtycalc_rows(this.id,' + counter1 + ')"/></td>';  


 	  

   
   cols1 += '<input  type="<?php echo $TextBoxHidden; ?>"  autocomplete="off" placeholder="0" class="form-control"  value="0"   name="dailypickup[' + counter1 + '][total_cost]" id="total_cost' + counter1 + '" required  readonly=""/>';  
   
   cols1 += '<input  type="<?php echo $TextBoxHidden; ?>"  autocomplete="off" placeholder="0" class="form-control"  value="0"   name="dailypickup[' + counter1 + '][profit]" id="profit' + counter1 + '" required  readonly=""/>';  
   
   cols1 += '<input  type="<?php echo $TextBoxHidden; ?>"  autocomplete="off" placeholder="0" class="form-control"  value="0"    name="dailypickup[' + counter1 + '][points]" id="points' + counter1 + '" required  readonly=""/>';  
   
   cols1 += '<input  type="<?php echo $TextBoxHidden; ?>"  autocomplete="off" placeholder="0" class="form-control" value="0"    name="dailypickup[' + counter1 + '][variable_in_rs]" id="variable_in_rs' + counter1 + '" required  readonly="" />';  
        	  
		cols1 += '<td><a class="btn btn-danger ibtnDel1" style="cursor:pointer;" title="Delete"><i class="fa fa-trash-o"></i></a></td>'; 
		
		document.getElementById("counter1").value=counter1; 
		
		newRow1.append(cols1);

        $("table.order-list1").append(newRow1); 
        $(".select2").select2({});
        //$(".select2").last().next().next().remove();
                   
        
    });

    $("table.order-list1").on("click", ".ibtnDel1", function (event) {
        $(this).closest("tr").remove();                
    });    


/* $("table.order-list1").on("click", ".ibtnEditDelete", function (event) {
	
	 var id = $(this).attr("id");
	  bootbox.confirm({
    title: "Night Audit ",
    message: "Are you sure you want to Day Close ?",
    buttons: {
        cancel: {
            label: '<i class="fa fa-times"></i> Cancel'
        },
        confirm: {
            label: '<i class="fa fa-check"></i> Confirm'
        }
    },
    callback: function (result) { //alert(result);
        //console.log('This was logged in the callback: ' + result);
		if(result==true){
	 $.ajax({

					type: "POST",
					url: "ajax/ajaxDeleteDailyPickup.php",
					data:{value:id},
					success: function(result){
						//console.log(data); 
						var mydata = JSON.parse(result);
 						alert(mydata.successMsg);
						//$(this).closest("tr").remove(); 
						
	 
					}
				}); 
				
        $(this).closest("tr").remove();
		}
				 }
				})                
    });*/



  $( function() {	 
$( ".pickerdate_addreport").datepicker({ dateFormat: 'dd-mm-yy' });
    
  //$( ".pickerdate_addreport").datepicker({ dateFormat: 'dd-mm-yy', minDate: '-<?php echo $DateNoDays; ?>d' });
 
 
  } );

  //COMPANY AUTO COMPLETE START==================================================================
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
	  //COMPANY AUTO COMPLETE END==================================================================
 
  	function deleteMe(id,name){
  		//var companyName='<?php echo $_REQUEST['search_name'];?>';
  		var companyName=$("#search_name").val();
  		var xhttp = new XMLHttpRequest();
  		  xhttp.onreadystatechange = function() {
  		    if (this.readyState == 4 && this.status == 200) {
  		    	console.log(this.responseText);
  		      if(this.responseText == 1){
  		      	alert("Transaction Found In the Table");
  		      }
  		      else{
  		      	if(confirm('Are you sure that you want to delete this record '+name+'?')){
  		      		window.location.href='manageCompany.php?delId='+id+'&action=delete&page=<?=$_REQUEST['page']?>&search_name='+companyName+'&searchFormSubmit=1&Search=Search';
  		      	}
  		      }
  		    }
  		  };
  		  xhttp.open("GET", "ajax/ajaxCheckCompanyDomain.php?id_company="+id, true);
  		  xhttp.send();
  	}
  </script> 

<!--jump--> 
<script type="text/javascript">
	//jump
	$("document").ready(function(){
		$("#importCompany").click(function(){
        $("#companyimport").submit(function(e){
          e.preventDefault();	
          var fileName = $("#companyImport").val();
          console.log(fileName);
          if(fileName == ""){
          	$("#returnTxt").css("color","red");
          	$("#returnTxt").html(" !! Kindly Select a file !!");
          }  
          else{
            $.ajax({
            type        : 'POST',
            contentType : false,
            processData : false, 
            url         : 'ajax/ajaxCompanyImport.php', 
            data        : new FormData(this),
            success     : function(data){
              $("#returnTxt").html(data);
              /*$("#credithidden").val(data[1]);*/
              //alert(data);
            } 
           })
          }
        });
      });
	});
	
	
	
	function CostFormula(value,clicked_id){

		var qty = document.getElementById("qty"+clicked_id).value;
		var rate = document.getElementById("rate"+clicked_id).value;
		
		var cost1 = document.getElementById("cost"+clicked_id).value;
		var orginal_cost = document.getElementById("orginal_cost"+clicked_id).value;
		var comission = document.getElementById("comission"+clicked_id).value;
		
		var discount = document.getElementById("discount"+clicked_id).value;
		var other_expenses = document.getElementById("other_expenses"+clicked_id).value;
		var cost =  cost1/qty;
		
		var SaleRevenue = qty * rate;
		document.getElementById("sales_revenue"+clicked_id).value = SaleRevenue;
		
		var TotalCost = parseFloat(cost) + parseFloat(comission) + parseFloat(discount) +parseFloat(other_expenses);
		
		//alert(TotalCost);
		//alert(TotalCost);
		var profit = parseFloat(SaleRevenue) - parseFloat(TotalCost);
		var points = parseFloat(profit) * 2;
		var variable_in_rs = parseFloat(points) * 5;
		
		document.getElementById("total_cost"+clicked_id).value = TotalCost; 
		document.getElementById("orginal_cost"+clicked_id).value = cost/qty ; 
		
		document.getElementById("profit"+clicked_id).value = profit; 
		document.getElementById("points"+clicked_id).value = points/100;		
		document.getElementById("variable_in_rs"+clicked_id).value = variable_in_rs/100; 
		
	
	}
	
	
	function qtycalc_rows(value,clicked_id){

		var qty = document.getElementById("qty"+clicked_id).value;
		var rate = document.getElementById("rate"+clicked_id).value;
		
		var cost = document.getElementById("cost"+clicked_id).value;
		var orginal_cost = document.getElementById("orginal_cost"+clicked_id).value;
		var comission = document.getElementById("comission"+clicked_id).value;
		
		var discount = document.getElementById("discount"+clicked_id).value;
		var other_expenses = document.getElementById("other_expenses"+clicked_id).value;
		
		
		var SaleRevenue = qty * rate;
		document.getElementById("sales_revenue"+clicked_id).value = SaleRevenue;
		
		//alert(parseFloat(cost) + '----'+parseFloat(comission) + '----'+ parseFloat(discount)+ '----'+parseFloat(other_expenses));
		
		var TotalCost = parseFloat(qty * orginal_cost) + parseFloat(comission) + parseFloat(discount) +parseFloat(other_expenses);
		
		//alert(TotalCost);
		//alert(TotalCost);
		var profit = parseFloat(SaleRevenue) - parseFloat(TotalCost);
		var points = parseFloat(profit) * 2;
		var variable_in_rs = parseFloat(points) * 5;
		
		document.getElementById("total_cost"+clicked_id).value = TotalCost; 
		document.getElementById("cost"+clicked_id).value = qty * orginal_cost; 
		
		document.getElementById("profit"+clicked_id).value = profit; 
		document.getElementById("points"+clicked_id).value = points/100;		
		document.getElementById("variable_in_rs"+clicked_id).value = variable_in_rs/100; 
		
	
	}
	function GetProductCast(value,clicked_id){
		
		 $.ajax({

					type: "POST",
					url: "ajax/ajaxGetProductCost.php",
					data:{value:value,clicked_id:clicked_id},
					success: function(data){
						//console.log(data); 
						var mydata = JSON.parse(data);
 
						 document.getElementById("cost"+mydata.clicked_id).value = mydata.product_cost;
						 document.getElementById("orginal_cost"+mydata.clicked_id).value = mydata.product_cost;
						 qtycalc_rows('0',clicked_id);
						//document.getElementById("alt_unit"+match).value = mydata['alt_unit'];
						//document.getElementById("main_unit"+match).value = mydata['main_unit'];

						if (mydata.product_cost == 0) {
                // If product cost is 0, change the input type to "text" (or any visible type)
                document.getElementById("cost" + mydata.clicked_id).type = "text";
				//document.getElementById("orginal_cost" + mydata.clicked_id).type = "text";
				document.getElementById("orginal_cost_active"+mydata.clicked_id).value = '1';
            } else {
                // If product cost is not 0, keep the input type as it is
                document.getElementById("cost" + mydata.clicked_id).type = "hidden";
				 //document.getElementById("orginal_cost" + mydata.clicked_id).type = "hidden";
				 document.getElementById("orginal_cost_active"+mydata.clicked_id).value = '0';
            }
	 
					}
				}); 
		
		}
		
	function SaveDailyPickup(){ 

	 var form=$("#DailyPickupForm");

	if(form.parsley().validate()){

	$('.loading').show(); 


	$.ajax({

	   type: "POST",

	   url: 'ajax/ajaxUpdateDailyPickup.php',	   

	   data: form.serialize(), 

	   success: function (result) {

			data = JSON.parse(result);
			if(data.status==1){
				// $( ".enquiry_failed_open" ).click();
				//$( "#FollowUpNextUpdateerror" ).html(data.Message);
				
				exit;
			}else{
			
		   // $( ".my_popup_open" ).click();
			//$( "#FollowUpNextUpdate" ).html(data.Message);	
			}
		   
		
		},

	  complete: function(){		  

		$('#OpenListPopUpshow').popup('hide');

		

	  }

	});

	return false;


	}

}	
		
</script>