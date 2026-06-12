<?php 

include_once("../config/auto_loader.php");

checkUserLevelPermission($_SESSION['userLevel'],INVOICE,'view');


unset($_SESSION['followup_hotel_id']); 
unset($_SESSION['followup_description']); 
unset($_SESSION['followup_date']); 
unset($_SESSION['followupCode']); 
unset($_SESSION['followupstatus']); 
unset($_SESSION['assign_user_id']); 
unset($_SESSION['feedback_hotel_id']); 
unset($_SESSION['feedback_description']); 
unset($_SESSION['feedback_date']); 
unset($_SESSION['assign_followup_user_id']);
unset($_SESSION['username']); 
unset($_SESSION['user_created_date']);


$followupInvoiceType='6';


if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
	 $sql = "  SELECT * FROM `".INVOICE."`
								WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";

	$db->query($sql);
	if($db->num_rows() > 0){
		$row = $db->fetch_object();
	}
	if($row->id_user != $_SESSION['userId']){
		$disabled ="disabled='disabled'";
	}
	
}	
	
							





include_once("includes/header.php");
include_once("includes/left.php");

?>
<style>
.parsley-required {
	float: left;
}
</style>

<div class="content-wrapper"> 
  
  <!-- Content Header (Page header) -->
  
  <section class="content-header">
    <h1> Lead </h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Lead</li>
    </ol>
  </section>
  
  <!-- Main content -->
  
  <section class="content">
    <div class="row">
    
    <!-- left column -->
    
    <div class="col-md-12">
    
    <!-- general form elements -->
    
    <div class="box box-primary">
      <div class="box-header with-border" id="recentAdd" style="border:1px solid #252525; border-radius:3px;display: none; ">
        <h3 class="box-title" style="color: green;">Recently Added Follow Ups :</h3>
        <table  class="table table-striped" id="recentAddData">
          <tr style="background-color:#c2c2c2;color: #000; ">
            <td><b>S.No.</b></td>
            <td><b>Company Name</b></td>
            <td><b>Hotel Name</b></td>
          </tr>
        </table>
      </div>
      <div class="box-header with-border">
        <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> Follow Ups</h3>
      </div>
      <?php //print_r($_SESSION);?>
      <!-- /.box-header --> 
      
      <!-- form start jump-->
      
      <form name="form1" id="form1" method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off">
        <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" id="eId" />
        <div class="form-group has-error">
          <?php if($_SESSION['errorMsg']){?>
          <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
          <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
          <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
          <?php unset($_SESSION['successMsg']);}?>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="form-group col-md-3">
              <?php 

			  if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
				  $report_date	= stripslashes(date('d-m-Y',strtotime($row->dated))); 
				  }else{  
				  $report_date	=	date('d-m-Y');}	
// Calulating the difference in timestamps 
$date1	=date('d-m-Y');
    $diff = strtotime($row->dated) - strtotime($date1); 
      
    // 1 day = 24 hours 
    // 24 * 60 * 60 = 86400 seconds 
     $DateNoDays = abs(round($diff / 86400)); 
			  ?>
              <label for="start_date">Invoice Date:</label>
              <input type="text" class="form-control pickerdate_addreport" placeholder="Enter Enquiry date" id="enquiryDate" name="enquiryDate" value="<?php echo  $report_date; ?>"  data-parsley-required>
              <?php echo $err_start_date;?> </div>
            
            <!--Hotel select list-->
             <div class="form-group col-md-3">
              

              <label for="invoiceno">Invoice No:</label>
  <input type="text" class="form-control pickerdate_addreport" placeholder="Enter Invoice No" id="invoiceno" name="invoiceno" value="<?php echo  $row->id_invoice; ?>"  data-parsley-required>
              <?php echo $err_invoiceno;?> </div> 
            <div class="col-md-3" >
              <div class="form-group">
                <label for="start_date">Hotel<font style="color:red;">*</font></label>
                <?php 
                     	if($row->hotel_id==0){
			           			$selected="selected='selected'";
			           		}
			           		else{
			           			$selected='';
			           		} 
                       $hotelDropDown = '<select class="form-control select2" data-parsley-errors-container="#id_hotel_mdError" data-parsley-required name="id_hotel_md" id="id_hotel_md">

                                            <option value="">Select Hotel</option>

                                            ';

                                           $resCat = selectSql(TBL_HOTELS," where id_shop='".addslashes($_SESSION['shop'])."' AND status=1 ",' ORDER BY `name`');

                                     if($db->num_rows2($resCat)){

                                       while($resultCat = $db->fetch_object2($resCat)){

                                       if($row->hotel_id!="" && trim($row->hotel_id) == $resultCat->id){

                                         $selected = 'selected="selected"';

                                      }else{

                                         $selected = '';

                                       }

                                       $hotelDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'-'.ucfirst($resultCat->city).'</option>';

                                    }

                                     }

                                     echo $hotelDropDown .= '</select>';

                      ?>
                <span id="id_hotel_mdError"></span> </div>
            </div>
            
            <!--hotel select list end-->
           <div class="form-group col-md-3">
              <label>Source <font style="color:red;">*</font></label>
             <select  class="form-control select2 itemName" onchange="getCompanyGuestName(this.value,'');" name="id_company" id="id_company" data-parsley-errors-container="#idcompanyError"  data-parsley-required>
             
             </select> 
              <span id="idcompanyError"></span> </div>
              
              
              <div class="form-group col-md-3">
              

              <label for="invoiceno">Contact Person:</label>
              <input type="text" class="form-control " placeholder="Enter Contact Person " id="entityname" name="entityname" value="<?php echo  $entityname; ?>"  data-parsley-required>
              <?php echo $err_entityname;?> </div>
              
              
              <div class="form-group col-md-3">
              

              <label for="invoiceno">Contact Email:</label>
              <input type="text" class="form-control " placeholder="Enter Contact Email " id="entityname" name="entityname" value="<?php echo  $entityname; ?>"  data-parsley-required>
              <?php echo $err_entityname;?> </div>
              
              <div class="form-group col-md-3">
              

              <label for="invoiceno">Contact Mobile:</label>
              <input type="text" class="form-control " placeholder="Enter Contact Mobile " id="entityname" name="entityname" value="<?php echo  $entityname; ?>"  data-parsley-required>
              <?php echo $err_entityname;?> </div>
              
               
            
                         
                            
              <div class="form-group col-sm-3">
            <label for="id_guest" >Guest Name</label>
             <input type="text" class="form-control " placeholder="Enter Guest Name " id="entityname" name="entityname" value="<?php echo  $row->guest_name; ?>"  data-parsley-required>
              <?php echo $err_entityname;?>
                  <span id="guestError"></span> </div>
                  
             <?php 
          if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
				      $expected_check_in_date	= stripslashes(date('d-m-Y',strtotime($row->expected_check_in_date))); 
    				  if($row->expected_check_in_date=='0000-00-00'){
    				      $expected_check_in_date	=	'';
    				  }
				  }else{  
				  $expected_check_in_date	=	'';}	
          ?>
          <div class="form-group col-md-3">
              <label for="start_date">Check in:</label>
              <input type="text" class="form-control pickerdate_addreport" placeholder="Enter Expected check in date" id="expected_check_in_date" name="expected_check_in_date" value="<?php echo $row->checkin;?>">
              <?php echo $err_expected_check_in_date;?> 
        </div>
        
        
          <div class="form-group col-md-3">
              <label for="start_date">Check out:</label>
              <input type="text" class="form-control pickerdate_addreport" placeholder="Enter Expected check in date" id="expected_check_in_date" name="expected_check_in_date" value="<?php echo $row->checkout;?>">
              <?php echo $err_expected_check_in_date;?> 
        </div>     
                  
              <div class="form-group col-md-3">
              

              <label for="invoiceno">Amount:</label>
              <input type="text" class="form-control " placeholder="Enter Amount " id="amount" name="amount" value="<?php echo  $row->amount; ?>"  data-parsley-required>
              <?php echo $err_invoiceno;?> </div>    
                  
                <div class="form-group col-md-3">
              

              <label for="invoiceno">Received:</label>
              <input type="text" class="form-control " placeholder="Enter Received Amount" id="received" name="received" value="<?php echo  $row->received; ?>"  data-parsley-required>
              <?php echo $err_invoiceno;?> </div>  
              
              
              <div class="form-group col-md-3">
              

              <label for="invoiceno">Balance :</label>
              <input type="text" class="form-control " placeholder="Enter Balance" id="balance" name="balance" value="<?php echo  $row->balance; ?>"  data-parsley-required>
              <?php echo $err_invoiceno;?> </div>
              
              
              
              
              
			<div class="form-group col-md-3">
              

              <label for="invoiceno">Due Date :</label>
              <input type="text" class="form-control pickerdate_addreport" placeholder="Enter Due Date" id="duedate" name="duedate" value="<?php echo  $invoiceno; ?>"  data-parsley-required>
              <?php echo $err_invoiceno;?> </div>
              
              <div class="form-group col-md-3">
              <label for="invoiceno">Sales Manager :</label>
              <input type="text" class="form-control " placeholder="Enter Manager Name" id="balance" name="balance" value="<?php echo  $invoiceno; ?>"  data-parsley-required>
              <?php echo $err_invoiceno;?> </div>
              
              
              
            
            <?php
						if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
					    if($_SESSION['userId']!=$row->id_user){
			         			$TextAreaReadOnly="readonly='readonly'";
			           		}
			           		
			           		} ?>
            
        
          <!--CONVERTION Room Nights-->
          
          
                
                
        
                
                
    
          <input type="hidden" name="edit_id_enquiry" id="edit_id_enquiry" value="<?php echo addslashes(encryptor('decrypt',$_REQUEST['eId'])); ?>">
          <!--CONVERTION Room Nights-->
          </div>
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group">
                <label for="image" style="float:left;">Follow Ups Assigned To &nbsp;&nbsp; </label>
                <?php if($_REQUEST['eId']==''){?>
                <a class="pull-left btn btn-success btn-xs" onclick="addEqyFollowUp(0);" <?php echo $disabled ?> type="button" id="enqFollowUp" >Assign Follow Ups </a>
                <?php }else{?>
                <a class="pull-left btn btn-success btn-xs" <?php echo $disabled ?> type="button" >Assign Follow Ups </a>
                <?php }?>
              </div>
              <?php echo $err_image;?> </div>
          </div>
          <div class="box">
            <?php if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){ 

	                	$max_id=selectColumn(INVOICE_DETAILS,'MAX(id)',"WHERE  enquiry_id = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."' ");

						$FollowupSql = executeSql("SELECT * from `".INVOICE_DETAILS."` where   enquiry_id = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."' ");
				if(num_rows($FollowupSql) > 0){

				?>
            <div id="showFolowup">
              <div class="box-body table-responsive">
                <table id="example2" class="table table-bordered table-striped">
                  <thead>
                    <tr> 
                      <!--<th>Added On</th>-->
                      <th>Action By</th>
                      <th>Action Date</th>
                      <th>Follow Up Summary</th>
                      <th>Follow Up Date</th>
                      <th>Status</th>
                      <!--<th>Action</th>--> 
                    </tr>
                  </thead>
                  <tbody>
                    <?php
						while($FollowupSqlRow = $db->fetch_assoc2($FollowupSql)){

						$OtherChargesuniqueCode = 'FOLLOWUPS'.rand(0000,9999);	

						$_SESSION['followup_hotel_id'][$OtherChargesuniqueCode]		=	$FollowupSqlRow['hotel_id'];

						$_SESSION['followup_description'][$OtherChargesuniqueCode]	=	$FollowupSqlRow['details'];

						$_SESSION['followup_date'][$OtherChargesuniqueCode]			=	$FollowupSqlRow['dated'];
						$_SESSION['assign_user_id'][$OtherChargesuniqueCode]			=	$FollowupSqlRow['assign_user_id'];
						$_SESSION['assign_followup_user_id'][$OtherChargesuniqueCode]=$FollowupSqlRow['assign_user_id'];
						$_SESSION['followupstatus'][$OtherChargesuniqueCode]		=	$FollowupSqlRow['lead_status'];
						$_SESSION['username'][$OtherChargesuniqueCode]=ucwords(selectColumn(TBL_USERS,'name'," WHERE `id` = '".$FollowupSqlRow['id_user']."' "));
						$_SESSION['user_created_date'][$OtherChargesuniqueCode]		=	$FollowupSqlRow['created_date'];

                        $_SESSION['followup_modified_by'][$OtherChargesuniqueCode]		=	$FollowupSqlRow['modified_by'];
						$_SESSION['followup_created_by'][$OtherChargesuniqueCode]		=	$FollowupSqlRow['created_by'];


				if($FollowupSqlRow['lead_status'] == 1){
					$StatusEs	=	'btn-sm btn-success';
					$ActiveINactive	=	"Update";
				}
				if($FollowupSqlRow['lead_status'] == 0){
					$StatusEs	=	   'btn-sm btn-danger';
					$ActiveINactive	=	"Close";
					
					$NextFollowUpDisable	= "disabled='disabled'";
						$incentive_module_approved	=selectColumn(TBL_SHOP,'incentive_module_approved'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");
						if($incentive_module_approved	==1){
							$CheckEnquiry = executeSql("SELECT * from `".TBL_INCENTIVE."` where   id_enquiry = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."' ");
							if(num_rows($CheckEnquiry) ==0 && $row->assign_user_id==$_SESSION['userId']){
								$id_hotel_md=	selectColumn(TBL_INCENTIVE_PARTICIPATE_HOTEL, 'id' , "WHERE hotel_id = '".$FollowupSqlRow['hotel_id']."' and status=1");							  
									if($id_hotel_md>0){	
									$claimincentive	='&nbsp;<button  data="'.$FollowupSqlRow['id'].'" class="btn btn-success btn-sm" type="button" onclick="OpenPopupAddIncentive('.$FollowupSqlRow['enquiry_id'].','.$FollowupSqlRow['lead_status'].','.$followupInvoiceType.');"    >Claim Incentive</button>';            
									}
							}
						}
				}
				
				

				$DateVisitList	='<tr>';
 			    //$DateVisitList	.='<td>'.$FollowupSqlRow['created_date'].'</td>';
 			    $DateVisitList	.='<td>'.ucwords(selectColumn(TBL_USERS,'name'," WHERE `id` = '".$FollowupSqlRow['modified_by']."' ")).'</td>';
 			   $DateVisitList	.='<td>'.date('d M Y',strtotime($FollowupSqlRow['created_date'])).'</td>';
  			    $DateVisitList	.='<td>'.$FollowupSqlRow['details'].'</td>';
				$DateVisitList	.='<td>'.date('d M Y',strtotime($FollowupSqlRow['dated'])).'</td>';

				if($max_id==$FollowupSqlRow['id']){
				$DateVisitList .= '<td id="ChangeButton_'.$FollowupSqlRow['id'].'"><button  data="'.$FollowupSqlRow['id'].'" class="btn '.$StatusEs.'" type="button" onclick="OpenPopup('.$FollowupSqlRow['lead_status'].','.$FollowupSqlRow['id'].','.$FollowupSqlRow['enquiry_id'].','.$FollowupSqlRow['lead_status'].','.$followupInvoiceType.');"    >'.$ActiveINactive.'</button>';   
				                 
     		 	$DateVisitList	.=$claimincentive;	
				$DateVisitList	.='</td>';
     		 	}		

			  /*$DateVisitList	.='<td> <a class="btn btn-danger btn-sm" href="javascript:void(0);" id="'.$OtherChargesuniqueCode.'" onclick="ajaxFollowupRemove($(this).attr(\'id\'));");">

 				  	<i class="fa fa-trash-o fa-lg"></i> </a></td>';*/
				echo $DateVisitList	.='</tr>';
			}
		?>
                  </tbody>
                </table>
              </div>
            </div>
            <?php
	}
	//echo '<div  id="showFolowup"></div>';
}else{?>
            <div  id="showFolowup"></div>
            <?php	
	}
	
?>
          </div>
         
          
          <?php
                      if($row->status==0 && $row->modified_by !=""){
                        $inactive="checked='checked'";
                      }
                      else{
                        $active="checked='checked'";
                      }
                    ?>
          <div class="form-group">
            <label for="status" style="display: none;">Status</label>
            <input <?php echo $active;?> type="hidden" class="flat-red"  value="1" name="status"/>
            <input type="hidden" class="flat-red" <?php echo $inactive;?> value="0" name="status"/>
            <?php echo $err_status;?> </div>
          <?php

     	           	if($row->modified_by !=""){?>
          <div class="col-sm-12">
            <div class="form-group col-sm-6  descriptionBox" >
              <label for="descripton">Created By : </label>
              <input class="form-control" disabled="disabled" type="text" value="<?=selectColumn(TBL_USERS,'name','WHERE id="'.$row->id_user.'" ') ?>" />
            </div>
            <?php /*?><div class="form-group col-sm-4 descriptionBox">
              <label for="descripton">Modified By : </label>
              <input class="form-control" disabled="disabled" type="text" value="<?=selectColumn(TBL_USERS,'name','WHERE id="'.$row->modified_by.'" ') ?>" />
            </div><?php */?>
            <div class="form-group col-sm-6 descriptionBox">
              <label for="descripton">Modified Date : </label>
              <input class="form-control" disabled="disabled" type="text" value="<?=$row->date_modified?>" />
            </div>
          </div>
          <?php }  ?>
        </div>
        
        <!-- /.box-body -->
        
        <div class="box-footer">
          <input type='hidden' id="Save" value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save">
          <input type='button' <?php echo $NextFollowUpDisable; ?> value='<?=($_REQUEST['eId']==''?'Send':'Edit')?>' class="btn btn-primary" name="Save" onClick="SaveSalesReport();">
          &nbsp;&nbsp;&nbsp;&nbsp;
          <input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("manageEnquiry.php"); '>
          &nbsp; <span id="ajaxMsg" style="display:none;color: red">Please Wait...</span> </div>
      </form>
    </div>
    <div id="OpenListPopUpshow" class="well" style="display:none;"> </div>
    <div id="OpenListPopshafeer" class="well" style="display:none;"> </div>
    
    <!--########## Folloup Popup#######--> 
    <span class="my_popup_open" style="display:none;"></span>
    <div id="my_popup" class="well">
    
      <div id="FollowUpNextUpdate" > </div>
       <div id="SuccessMessageEmail"  class="help-block"> </div>
      
      <!-- <button id="my_popup_yes" class="my_popup_close btn btn-default pull-left">Yes</button>-->
      <button id="my_popup_no" style="margin-left: 5px;" class="my_popup_close btn btn-default pull-left">Close</button>
    </div>
    <span class="enquiry_failed_open" style="display:none;"></span>
    <div id="enquiry_failed" class="well">
      <div id="FollowUpNextUpdateerror"> </div>
      <br />
      <button class="enquiry_failed_close btn btn-default pull-right"></button>
    </div>
    
    <!--
        customer form 
        -->
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
          <label for="email" >Email Id <font color="#FF0000">*</font></label>
          <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email Id" data-parsley-type="email" automcomplete="off" data-parsley-required>
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
    <!--end--> 
    
    <!-- Modal -->
    
    <div class="modal fade" id="enqModal" role="dialog" >
    <div class="modal-dialog">
  </section>
  
  <!-- /.content --> 
  
</div>
<script type="text/javascript">
	var GetStartDate=0;
</script>
<?php include_once("includes/footer.php")?>
<div id="ColseSummaryPopUp" class="well" style="display:none;">
  <div id="" class="ajaxAddRoom">
    <div class="btn btn-default tablenew1 tablenewmobile1">
      <div class="col-md-9">
        <div class="form-group" style="text-align:left;">
          <label>Follow Up Status </label>
          <br>
          <input type="radio" name="HotelDuplicateInsert"  id="radion1" onClick="SelectHotelsList123(1);"   value="1"  checked="checked" />
          1) Action Required
          <input type="radio" name="HotelDuplicateInsert" id="radion12" onClick="SelectHotelsList123(2);" value="2" />
          2) Close
          
          
          </div>
          
          
      </div> 
      <div id="cars1" class="desc">
        <form name="nextFollowup" id="nextFollowup"  method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off">
          <input type="hidden" name="followup_id" id="followup_id" value="">
          <input type="hidden" name="daily_Visit_id" id="daily_Visit_id" value="">
          <input type="hidden" name="hotel_id_hidden" id="hotel_id" value="">
          <input type="hidden" name="followup_status" id="followup" value="">
          <input type="hidden" name="followup_type" id="followup_type" value="4">
          <div class="form-group">
            <label style="float:left;">Follow Up Summary</label>
            <textarea   name="followup_description" id="followup_description"  class="form-control" placeholder="Follow Up Summary"  data-parsley-required automcomplete="off" maxlength="150"></textarea>
          </div>
          <div class="form-group">
            <?php  
			//print_r($row);
			if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit')
			  {
				  $reportdate	= stripslashes(date('d-m-Y',strtotime($row->follow_up_date))); 
				if($reportdate>=date('d-m-Y')){	
				 $report_date	= stripslashes(date('d-m-Y',strtotime($row->follow_up_date))); 
				}else{
					$report_date	= date('d-m-Y'); 
					}
					
			  }else{
				$report_date	=	date('d-m-Y');

			 }?>
            <input type="text" class="form-control datepickertest" placeholder="Enter date" id="followup_date" name="followup_date" value="<?php echo $report_date;?>"  data-parsley-required>
          </div>
          <?php   $availableData .='<div class="form-group"><label style="float:left;">Assign To</label>';

               if($_SESSION['unit_user']=='2'){
                     $salesHead=array();
                     $teamSql = "SELECT id_user_level_1 FROM ".TBL_TEAM." WHERE id_shop='".$_SESSION['shop']."'  ";
                     $teamRes= mysqli_query($connNew,$teamSql);

                     while($rowTeam=mysqli_fetch_object($teamRes)){
                       array_push($salesHead,$rowTeam->id_user_level_1);
                     }
                  }

              

                 $availableData .= '<select class="form-control select2" name="assign_user_id" id="assign_user_id">
					<option value="">Select Assign UserName</option>';

				  $resUserLevel = selectSql(TBL_USERS," WHERE `status` = '1' AND  `id_shop` = '".addslashes($_SESSION['shop'])."' AND (user_type=1 OR user_type=0) ",' ORDER BY `name`');

											  if($db->num_rows2($resUserLevel)){

											  	while($resultUserLevel = $db->fetch_object2($resUserLevel)){

													if($_SESSION['userId'] == $resultUserLevel->id){

														$selected = 'selected="selected"';

													}else{

														$selected = '';

													}
												$designation= selectColumn('fs_designation_master','name','WHERE id="'.$resultUserLevel->designation.'"  AND id_shop="'.$_SESSION['shop'].'" ');	
												if($_SESSION['unit_user']=='2' && in_array($resultUserLevel->id,$salesHead) ){		
													$availableData .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'-'.$designation.'</option>';
												}
												elseif ($_SESSION['unit_user']!='2') {
														$availableData .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'-'.$designation.'</option>';
													}	

												}

											  }

											 	 $availableData .= '</select>';

                                              

                                              	 echo $availableData .='</div>';

												 

												 ?>
          <div class="form-group" style="float:left;">
            <button class="btn btn-primary" onclick="savefollowupDate();" type="button" >Save</button>
            &nbsp;
            <button class="ColseSummaryPopUp_close btn btn-default">Close</button>
          </div>
          '
        </form>
      </div>
      <div id="cars2" class="desc" style="display: none;">
        <form id="ColseSummaryPopUpForm" class="ColseSummaryPopUpForm" data-parsley-validate autocomplete="off">
          <input type="hidden" name="followup_id_hidden" id="followup_id_hidden" value="">
          <input type="hidden" name="daily_Visit_id_hidden" id="daily_Visit_id_hidden" value="">
          <input type="hidden" name="hotel_id_hidden" id="hotel_id_hidden" value="">
          <input type="hidden" name="followup_status_hidden" id="followup_status_hidden" value="">
          <input type="hidden" name="followup_hidden_type" id="followup_hidden_type" value="4">
         <div id="DisplayIncentiveCheckBox"></div> 
         
         <div class="form-group">
            <input type="hidden" name="fs_daily_visit_followup_new" id="fs_daily_visit_followup_new" value="">
            <div class="form-group">
            <?php 
			 $incentive_module_approved	=selectColumn(TBL_SHOP,'incentive_module_approved'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");
			if($incentive_module_approved	==1){$incentive_module_approved	='CheckCloseTypeConfirm(this.value);';};
			
			?>
              <select name="close_type"  id="close_type" class="form-control input-sm" data-parsley-required onchange="<?php echo $incentive_module_approved; ?>SelectCloseType(this.value);">
                <option value="">Select Close Type</option>
                ';

											
                <?php  $resultClose = selectSql(TBL_CLOSING_MASTER,"where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY name ');
				  

											  while($resultData = $db->fetch_object2($resultClose)){
											
													if($row->id_closing_type	== $resultData->id){

														$selected2 = 'selected="selected"';

													}else{

														$selected2 = '';

													}

													echo $availableDatasales = '<option   '.$selected2.' value="'.$resultData->id.'">'.ucfirst($resultData->name).'</option>';

												}

											 

											 	 

												 

												 ?>
              </select>
            </div>
           
            <div id="DisplayRevenue">
              
            </div>
            <div class="form-group">
              <textarea   name="followup_close_summary" id="followup_close_summary" class="form-control" placeholder="Close Summary"  data-parsley-required automcomplete="off"></textarea>
            </div>
            
            <!--------------------------------Claim Incentive Start-------------------------------------->
            <div id="showIncentiveAddEditForm"></div>
            
            
           <!--------------------------------Claim Incentive Start--------------------------------------> 
            
            <br/>
            <div class="form-group col-sm-12" style="float:left;">
              <button class="btn btn-primary" onclick="saveColseSummaryPopUpform();" type="button">Save</button>
              &nbsp;
              <button class="ColseSummaryPopUp_close btn btn-default">Close</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

		
<!--------------VIEW- INCENTIVE-STATUS  START--------------------------->
<div id="OpenIncentivePopup" class="well" style="display:none;">
  <div id="" class="ajaxAddRoom">
    <div class="btn btn-default tablenew1 tablenewmobile1">
      <div>
      <form name="statusIncentivePopUpForm" id="statusIncentivePopUpForm"  method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off">
        <input type="hidden" readonly="readonly" class="form-control input-sm" id="id_user_created" name="id_user_created" value=""  automcomplete="off"  >
        <input type="hidden" readonly="readonly" class="form-control input-sm" id="id_hotel_created" name="id_hotel_created" value=""  automcomplete="off"  >        
        <input type="hidden" readonly="readonly" class="form-control input-sm" id="id_incentive_previous" name="id_incentive_previous" value=""  automcomplete="off"  >
        <input type="hidden" readonly="readonly" class="form-control input-sm" id="id_user_previous" name="id_user_previous" value=""  automcomplete="off"  >
        <input type="hidden" readonly="readonly" class="form-control input-sm" id="id_forward_for_approval_previous" name="id_forward_for_approval_previous" value=""  automcomplete="off"  >
        
       <?php 
	   
	   $sqlRsoUser ="SELECT A.id_user_level_1 FROM ".TBL_TEAM." AS A  INNER JOIN ".TBL_USERS." AS B ON  FIND_IN_SET(B.id, A.id_user_level_1) WHERE A.id_shop='".$_SESSION['shop']."'  AND (B.user_type=1 OR B.user_type=0) AND A.id_user_level_1='".$_SESSION['userId']."' group by A.id_user_level_1";
		$queryRsoUser= mysqli_query($connNew,$sqlRsoUser);
		$resultRsoUser=mysqli_fetch_object($queryRsoUser);
         if($resultRsoUser->id_user_level_1==$_SESSION['userId']){
			 $optionRsoUser='<option value="1">Verified By Corporate</option>
			 <option value="2">Not Approved</option>';
			 }
          
		   $sqlHotelUser = "SELECT id FROM ".TBL_USERS." WHERE id_shop='".$_SESSION['shop']."' AND FIND_IN_SET(".$row->hotel_id.",hotel_access)";
			$queryHotelUser = mysqli_query($connNew,$sqlHotelUser);

			

			
			
			$arrResultHotelUserId = array();

			while($resultHotelUser=mysqli_fetch_object($queryHotelUser)){
				array_push($arrResultHotelUserId, $resultHotelUser->id);
			}
			
          if(in_array($_SESSION['userId'],$arrResultHotelUserId)){
			   $optionRsoUser ='<option value="3">Approved By Hotel</option>
			   					<option value="2">Not Approved</option>';
			  }else{
				 // $optionRsoUser1 ='<option value="0">Pending For Approval</option>';
				  }
		?>
          <div class="form-group">
            <label style="float:left;">Incentive  Status</label>
            <select class="form-control select2" onchange="IncentiveStatusConfirm(this.value);" data-parsley-required name="status_incentive_id" id="status_incentive_id">
             <option value="">Select Status</option>
              
              <!--<option value="1">Verified By Corporate</option>-->
              <?php  echo $optionRsoUser; ?>
              <?php  echo $optionRsoUser1; ?>
              
              
            </select>
          </div>
          <div id="DisplayHotelUser">
           </div>
          <div class="form-group">
            <label style="float:left;">Revenue for Approval</label>
            <input type="text" readonly="readonly" class="form-control input-sm" id="claim_amount" name="claim_amount" value=""  automcomplete="off"  >
          </div>
          <div class="form-group">
            <label style="float:left;">Revenue Approved</label>
            <input type="text"  class="form-control input-sm" id="approved_amount" name="approved_amount" value=""  automcomplete="off"  >
          </div>
          <div class="form-group">
            <label style="float:left;">Remarks.</label>
            <textarea   name="incentive_remark" id="incentive_remark"  class="form-control" placeholder="Remarks"  automcomplete="off" maxlength="150"></textarea>
          </div>
          <div class="form-group" style="float:left;">
            <button class="btn btn-primary" onclick="updateIncentivePopUpform();" type="button" >Save</button>
            &nbsp;
            <button class="OpenIncentivePopup_close btn btn-default">Close</button>
          </div>
          '
        </form>
      </div>
    </div>
  </div>
</div>

<!--------------VIEW- INCENTIVE-STATUS --END-------------------------> 

<!--------------VIEW- INCENTIVE-START--------------------------->
<div id="viewincPopUp" class="well" style="display:none;">
<div id="EditClaimIncentiveForm"></div>
</div>

<script type="text/javascript">

	function calculateTotalRevenue(id){
		
		//alert(id);
		 var room_rate_inc = $("#room_rate_inc").val();
		 var banquet_revenue_amount_inc = $("#banquet_revenue_amount_inc").val();
		 var totalRevenu = +room_rate_inc + +banquet_revenue_amount_inc;
		$('#revenue_inc').val(totalRevenu);
		}
	function Toggle(id) {
	
			if (document.getElementById(id).style.display == "none" || document.getElementById(id).style.display == "") {
				document.getElementById(id).style.display = "block";
						$('#guest_name_inc').attr('data-parsley-required', 'true');	
						$('#checkin_inc').attr('data-parsley-required', 'true');
						$('#checkout_inc').attr('data-parsley-required', 'true');
						$('#no_room_inc').attr('data-parsley-required', 'true');
						$('#no_pax_inc').attr('data-parsley-required', 'true');
						$('#room_rate_inc').attr('data-parsley-required', 'true');
						$('#banquet_revenue_amount_inc').attr('data-parsley-required', 'true');
						$('#id_forward_for_approval').attr('data-parsley-required', 'true');
						
						$('#revenue_inc').attr('data-parsley-required', 'true');
						$('#revenue').attr('data-parsley-required', 'false');
						
						$('#guest_name_inc').val('');	
						$('#checkin_inc').val('');	
						$('#checkout_inc').val('');	
						$('#no_room_inc').val('');	
						$('#no_pax_inc').val('');	
						$('#room_rate_inc').val('');	
						//$('#banquet_revenue_amount_inc').val('');	
						$('#revenue_inc').val('');	
						
				
			} else if (document.getElementById(id).style.display == "block") {
				document.getElementById(id).style.display = "none";
						$('#guest_name_inc').attr('data-parsley-required', 'false');
						$('#checkin_inc').attr('data-parsley-required', 'false');
						$('#checkout_inc').attr('data-parsley-required', 'false');
						$('#no_room_inc').attr('data-parsley-required', 'false');
						$('#no_pax_inc').attr('data-parsley-required', 'false');
						$('#room_rate_inc').attr('data-parsley-required', 'false');
						$('#banquet_revenue_amount_inc').attr('data-parsley-required', 'false');
						$('#revenue_inc').attr('data-parsley-required', 'false');
						$('#id_forward_for_approval').attr('data-parsley-required', 'false');
						$('#revenue').attr('data-parsley-required', 'true');
						$('#revenue').val('');
				
			} else {
				document.getElementById(id).style.display = "none";
				
			}
		}
		
		
		
	$(document).ready(function(){
		$("#my_popup_no").click(function(){
			window.location="manageEnquiry.php";
		});
		var count = 0;
		$("#my_popup_yes").click(function(){
			$("#form1").trigger("reset");
			$('#en_cmp').prepend('<option value="" selected="selected">Select Company</option>');
			$('#id_hotel_md').prepend('<option value="" selected="selected">Select Hotel</option>');
			var recentId = $("#recentEnqId").val();
			$.ajax({
				type: "POST",
				url: 'ajax/ajaxRecentEnquiry.php',
			  	data: 'id='+recentId+'&count='+count, 
			 	    success: function (result) {	
			 	    $('#recentAdd').show();
			 	    $('#listingForm').trigger("reset");
			 	    $('#listingForm').hide();
			 	    $('#recentAddData').append(result);
			 	    count++;
				}
				})
			});

		
	});

	$( document ).ajaxStart(function() {
  			$("#ajaxMsg").show();
	});

	$( document ).ajaxStop(function() {
  			$("#ajaxMsg").hide();
	});

	 function chkStatus(value){
    	 if(value==1){
			
      		$('.makeHide').show();
			$('.chkUser').attr('data-parsley-required', 'true');
			$('.chkUser').val('');	
     	}
     	else{
      		$('.makeHide').hide();
			$('.chkUser').attr('data-parsley-required', 'false');
     	}
  	}
	function addEqyFollowUp(followup_status){
		$.ajax({
			type: "POST",
		    url: 'ajax/ajaxAddEnquiryFollowUp.php',
  		    data: 'followup_status='+followup_status, 
 		    success: function (result) {		
					$('#OpenListPopUpshow').html(result);		
  				    $('#OpenListPopUpshow').popup('show');
					
					
			}
		})
	}

	function savefollowupDate() { 
	  var followup_date = $("#followup_date").val();
	  var form=$("#nextFollowup");
	  var nextFollowup = form.serialize();
	  
	  var form2=$("#form1");
	  var forwardEnquiryData = form2.serialize();
	

	  if(form.parsley().validate()){
		 $('#ColseSummaryPopUp').popup('hide'); 

		var nextFollowup = form.serialize(); 
		 $.ajax({
		   type: "GET",
		   url: 'ajax/ajaxfollowupCalanderUpdate.php',
		   data: nextFollowup,  
		   success: function (result) {
			 	
		     $( "#my_popup_yes" ).hide();
	         $( "#my_popup_no" ).hide();
  		     $( ".my_popup_open" ).click();	
 		     $( "#FollowUpNextUpdate" ).html(result);
			},
			complete: function(){/*	// Forward Enquiry Mail
		  	$.ajax({
		  		type:"POST",
		  		url:'ajax/ajaxSendEnquiryMail.php',
		  		data:forwardEnquiryData+'&'+nextFollowup+'&forwardEnquiryUser=forwardUser&followup_date='+followup_date,
		  		success:function(data){
					 
		  			result = JSON.parse(data);
					
		  			$( ".my_popup_open" ).click();
					$('#SuccessMessageEmail').html('<div><b>Lead Details sent to : </b></div><br/>'+result.msg+'<div><br/></div>');	
		  			window.location.href='manageEnquiry.php';
		  			
		  		},
		  		complete: function(){
		  			$('#OpenListPopUpshow').popup('hide');
		  		}
		  	})	  
			*/}
		})  	
	}

	return false;
	}

	function SelectHotelsList123(HotelDuplicateInsert){
		var test = HotelDuplicateInsert;
        $("div.desc").hide();
        $("#cars" + test).show();
	}

	function ajaxAddNextFollowup(followup_status){
		var followupCode = $("#followupCode").val();
		var rate_id = $("#rate_id").val();
		var hotel_id = $("#hotel_id").val();
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxAddNextFollowUp.php',
		   data: 'followup_status='+followup_status+'&followupCode='+followupCode, 
		   success: function (result) {					
				resultArray = result.split('|||');
				$('#AddNextFollowup_'+resultArray['1']).append(resultArray['0']);
			}
		})
	}
	function OpenPopupAddIncentive(enquiry_id,hotel_id,followup_type){
		
			/*$('#followup_id').val(followup_id);
			$('#daily_Visit_id').val(daily_Visit_id);
			$('#hotel_id').val(hotel_id);
			$('#followup_status').val(followup_status);
			$('#followup_type').val(followup_type);
			$('#followup_id_hidden').val(followup_id);
			$('#daily_Visit_id_hidden').val(daily_Visit_id);
			$('#hotel_id_hidden').val(hotel_id);
			$('#followup_status_hidden').val(followup_status);
			$('#followup_hidden_type').val(followup_type);
			
			$('#viewincPopUp').popup('show');*/
			
			var enquiryDate		=	$("#enquiryDate").val();
	var id_hotel_md	=	$("#id_hotel_md").val();
	var edit_id_enquiry	=	$("#edit_id_enquiry").val();
			//&id_hotel_md='+id_hotel_md+'&enquiryDate='+enquiryDate+'&revenue='+revenue+'&guest_name='+guest_name+'&checkin='+checkin+'&checkout='+checkout+'&no_room='+no_room+'&no_pax='+no_pax+'&banquet_revenue_amount='+banquet_revenue_amount+'&room_rate='+room_rate+'&forward_for_approval='+forward_for_approval
		
			$.ajax({
				type: "POST",
				url: 'ajax/ajaxIncentiveAddEditForm.php',
				data: 'selectType=Add&id_hotel_md='+id_hotel_md+'&enquiryDate='+enquiryDate+'&enquiry_id='+enquiry_id, 
				success: function (result) {
					$('#viewincPopUp').popup('show');
					$('#EditClaimIncentiveForm').html(result);
					
					$('#guest_name_inc').attr('data-parsley-required', 'true');	
						$('#checkin_inc').attr('data-parsley-required', 'true');
						$('#checkout_inc').attr('data-parsley-required', 'true');
						$('#no_room_inc').attr('data-parsley-required', 'true');
						$('#no_pax_inc').attr('data-parsley-required', 'true');
						$('#room_rate_inc').attr('data-parsley-required', 'true');
						$('#banquet_revenue_amount_inc').attr('data-parsley-required', 'true');
						$('#revenue_inc').attr('data-parsley-required', 'true');
						$('#id_forward_for_approval').attr('data-parsley-required', 'true');
						$('#revenue').attr('data-parsley-required', 'false');
						
							
					
				}
		});
		
	}
	function OpenPopup(followup_status,followup_id,daily_Visit_id,hotel_id,followup_type){
		
		if(followup_status	== '0'){		
			alert('Your Follow Up already Closed');
		}else{	
			$('#followup_id').val(followup_id);
			$('#daily_Visit_id').val(daily_Visit_id);
			$('#hotel_id').val(hotel_id);
			$('#followup_status').val(followup_status);
			$('#followup_type').val(followup_type);
			$('#followup_id_hidden').val(followup_id);
			$('#daily_Visit_id_hidden').val(daily_Visit_id);
			$('#hotel_id_hidden').val(hotel_id);
			$('#followup_status_hidden').val(followup_status);
			$('#followup_hidden_type').val(followup_type);
			$('#ColseSummaryPopUp').popup('show');
		}
	}
	function OpenIncentivePopup(claim_amount,approved_amount,id_forward_for_approval,id_user,id_incentive,id_user_created,id_hotel_created){ //Incentive
		$('#approved_amount').val(approved_amount);
		$('#claim_amount').val(claim_amount);
		$('#id_forward_for_approval_previous').val(id_forward_for_approval);
		$('#id_user_previous').val(id_user);
		$('#id_incentive_previous').val(id_incentive);
		$('#id_user_created').val(id_user_created);
		$('#id_hotel_created').val(id_hotel_created);
		$('#OpenIncentivePopup').popup('show');
		}
	function ajaxRemovefollowup(){
		$('#OpenListPopUpshow').popup('hide'); 
	}


	function updateIncentivePopUpform(){
		
		 var eId	=	$("#eId").val();
		 var claim_amount = $("#claim_amount").val();
		 var approved_amount = $("#approved_amount").val();
		 var status_incentive_id = $("#status_incentive_id").val();
		 
		 if(claim_amount!=approved_amount || status_incentive_id==2 || status_incentive_id==0){
			 $('#incentive_remark').attr('data-parsley-required', 'true');
			 }else{
				  $('#incentive_remark').attr('data-parsley-required', 'false');}
		 	
		var form=$("#statusIncentivePopUpForm");

		if(form.parsley().validate()){
			$('.loading').show(); 

		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxIncentiveUpdateStatus.php',
		   data: form.serialize(), 
		   success: function (result) {
			$('#OpenIncentivePopup').popup('hide');
			$( "#my_popup_yes" ).hide();
			$( "#my_popup_no" ).hide();
			$( ".my_popup_open" ).click();	
			
			$( "#FollowUpNextUpdate" ).html(result);
		  /*if(result!=''){
		    $('#followup_close_summary').val('');
			$('#close_type').val('');
			$('#close_status').val('');
			$('#ColseSummaryPopUp').popup('hide');
			$( ".my_popup_open" ).click();	
		   $( "#FollowUpNextUpdate" ).html(result);

			 
			  }*/
			  
/* $.ajax({
					type:"POST",
					url:'ajax/ajaxSendEnquiryMail.php',
					data:form.serialize()+'&FollowupClose=close&daily_Visit_id_hidden='+eId+'&eId='+eId+'&incentive=inc',
					success:function(data){
						
						result = JSON.parse(data);
					
		  			$( ".my_popup_open" ).click();
					$('#SuccessMessageEmail').html('<div><b>Lead Details sent to : </b></div><br/>'+result.msg+'<div><br/></div>');	
		  			window.location.href='manageIncentive.php';
					}
		  		})*/

			},
			
		  



		});

		return false;

		}

	}



	function saveColseSummaryPopUpform(){	
	
		/*var checkbox = document.getElementById('ClaimIncentive');alert(checkbox);
		if(checkbox!='null'){
			var ClaimIncentive	=	checkbox.checked;
		var clm=	'&ClaimIncentive='+ClaimIncentive;
		}if(checkbox=='null'){$('#revenue').attr('data-parsley-required', 'false');
			$('#followup_close_summary').attr('data-parsley-required', 'true'); 
		var clm=	'';
		}
		alert(clm);*/
		
		var form=$("#ColseSummaryPopUpForm");


		if(form.parsley().validate()){
			$('.loading').show(); 

		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxFollowupstatusChange.php',
		   data: form.serialize(), 
		   success: function (result) {
		  if(result!=''){
		    $('#followup_close_summary').val('');
			$('#close_type').val('');
			$('#close_status').val('');
			$('#ColseSummaryPopUp').popup('hide');
			$( ".my_popup_open" ).click();	
		   $( "#FollowUpNextUpdate" ).html(result);

			 
			  }

			},

		  complete: function(){
		      
		      
		    /*  $.ajax({
					type:"POST",
					url:'ajax/ajaxSendEnquiryMail.php',
					data:form.serialize()+'&FollowupClose=close',
					success:function(data){
						
						//$( ".my_popup_open" ).click();
						//console.log(data);	
						
					}
		  		})*/

			  $('#followup_close_summary').val('');

			  $('#close_type').val('');

			  $('#close_status').val('');

			$('#ColseSummaryPopUp').popup('hide');
	    $( "#my_popup_yes" ).hide();
	     $( "#my_popup_no" ).hide();
			$( ".my_popup_open" ).click();	
				result = JSON.parse(data);
					
		  			
					$('#SuccessMessageEmail').html('<div><b>Lead Details sent to : </b></div><br/>'+result.msg+'<div><br/></div>');
			       $( "#FollowUpNextUpdate" ).html(result);

		  }

		});

		return false;

		}

	}

function saveIncentiveExistingLeadform(){	
		
		
		var query_type		=	$("#query_type").val();
		var form=$("#saveIncentiveExistingLeadform");

		if(form.parsley().validate()){
			$('.loading').show(); 

		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxLeadIncentiveUpdate.php',
		   data: form.serialize()+'&query_type='+query_type, 
		   success: function (result) {
		  
			$('#followup_close_summary').val('');
			$('#close_type').val('');
			$('#close_status').val('');
			$('#viewincPopUp').popup('hide');
			$( ".my_popup_open" ).click();	
			$( "#FollowUpNextUpdate" ).html(result);
			}

		  

		});

		return false;

		}

	}
	
	
	function saveAddFollowupPopUpform(){
		var FollowupCoditionType	=	$("#FollowupCoditionType").val();
		var form=$("#AddFollowPopUpForm");
		
		//console.log(FollowupCoditionType);
		//console.log(form);
		if(form.parsley().validate()){
			$('.loading').show(); 
			$.ajax({
			   type: "POST",
			   url: 'ajax/ajaxDateEnqFollowupList.php',
			   data: form.serialize()+'&FollowupCoditionType='+FollowupCoditionType, 
			   success: function (result) {
				   if(FollowupCoditionType == 'addfollowup'){
						$('#showFolowup').html(result);	
						//console.log(result);	   
					}
				},
			  	complete: function(){		  
					$('#OpenListPopUpshow').popup('hide');
		    	}
			});
			return false;
		}
	}

//revenue,guest_name,checkin,checkout,no_room,no_pax,banquet_revenue_amount,room_rate,forward_for_approval
function OpenViewPopup(id_incentive){

	

	var enquiryDate		=	$("#enquiryDate").val();
	var id_hotel_md	=	$("#id_hotel_md").val();
	
			
		
			$.ajax({
				type: "POST",
				url: 'ajax/ajaxIncentiveAddEditForm.php',
				data: 'selectType=view&id_hotel_md='+id_hotel_md+'&enquiryDate='+enquiryDate+'&id_incentive='+id_incentive, 
				success: function (result) {
					$('#viewincPopUp').popup('show');
					$('#EditClaimIncentiveForm').html(result);	
					
				}
		});	
					
			
		
	
	}
function SaveSalesReport(){
	
	var mobile = $('#en_mobile').val();
	var email = $('#en_email').val();
	if(mobile=="" && email==""){
		return alert('Either fill mobile or email.Both Can\'t be blank');
	}
	
	var FollowupCoditionType	=	$("#FollowupCoditionType").val();
	var form=$("#form1");
	if(form.parsley().validate()){
		$('.loading').show(); 
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxUpdateInvoice.php',	   
		   data: form.serialize(), 
		   success: function (result) {
			 
			 if(result==7){
				$( ".enquiry_failed_open" ).click();
				 $( "#FollowUpNextUpdateerror" ).html('Please assign lead to continue');

				 return false;
				 exit;
				 }
				 else{/*	
			    $( "#FollowUpNextUpdate" ).html(result);
				$.ajax({
		  		type:"POST",
		  		url:'ajax/ajaxSendEnquiryMail.php',
		  		data:form.serialize(),
		  		success:function(data){
					
					result = JSON.parse(data);
					
		  			$( ".my_popup_open" ).click();
					$('#SuccessMessageEmail').html('<div><b>Lead Details sent to : </b></div><br/>'+result.msg+'<div><br/></div>');
		  			console.log(data);	
					
		  		},
		  		complete: function(){
		  			$('#OpenListPopUpshow').popup('hide');
		  		}
		  	})	
				 */}
				  return false;		
	 		},
		  /*complete: function(){	// new Enquiry Mail
		  	$.ajax({
		  		type:"POST",
		  		url:'ajax/ajaxSendEnquiryMail.php',
		  		data:form.serialize(),
		  		success:function(data){
					
		  			$( ".my_popup_open" ).click();
		  			console.log(data);	
					
		  		},
		  		complete: function(){
		  			$('#OpenListPopUpshow').popup('hide');
		  		}
		  	})	  
			}*/
		});
		return false;
	}

}



function ajaxFollowupRemove(uniqueCode){	
	$.ajax({
 	    type: "GET",
        url: 'ajax/ajaxUpdateSessionEnqFollowupEditPage.php',
 	    data: 'remove=removeOne'+'&uniqueCode='+uniqueCode, 
	    success: function (result) {	
	    	$('#showFolowup').html(result);		   
		}
	});
}


function SelectCloseType(id){
	
	var id_hotel_md= $('#id_hotel_md').val();
		$.ajax({
 	    type: "POST",
        url: 'ajax/ajaxRevenue.php',
 	    data: 'select=type'+'&id='+id+'&id_hotel_md='+id_hotel_md, 
	    success: function (result) {
		result = JSON.parse(result);
				if(result.id==1){
			
			$('#guest_name_inc').attr('data-parsley-required', 'false');
			/*$('#guest_name_inc').removeAttr('required','required');
			$('#checkin_inc').removeAttr('required','required');
			$('#checkout_inc').removeAttr('required','required');
			$('#no_room_inc').removeAttr('required','required');
			$('#no_pax_inc').removeAttr('required','required');
			$('#room_rate_inc').removeAttr('required','required');
			$('#banquet_revenue_amount_inc').removeAttr('required','required');
			$('#revenue_inc').removeAttr('required','required');*/
			
			
			$('#guest_name_inc').removeAttr('data-parsley-validate-if-empty');
			$('#checkin_inc').attr('data-parsley-required', 'false');
			$('#checkout_inc').attr('data-parsley-required', 'false');
			$('#no_room_inc').attr('data-parsley-required', 'false');
			$('#no_pax_inc').attr('data-parsley-required', 'false');
			$('#room_rate_inc').attr('data-parsley-required', 'false');
			$('#banquet_revenue_amount_inc').attr('data-parsley-required', 'false');
			$('#id_forward_for_approval').attr('data-parsley-required', 'false');
			$('#revenue_inc').attr('data-parsley-required', 'false');
			/*$('#guest_name_inc').removeAttr('data-parsley-required');

			$('#guest_name_inc').removeAttr('required','required');
			$('#guest_name_inc').attr('data-parsley-required', 'false');*/
			
			
			
			$('#DisplayRevenue').hide();
			$('#DisplayRevenue').html('');
			$('#DisplayIncentiveCheckBox').html('');
			$('#showIncentiveAddEditForm').html('');
			
			
			//$('#revenue_inc').removeAttr('required','required');
			







		}
		if(result.id!=1 && result.dvalue!='undefined'){
			
			//$("#DisplayIncentiveCheckBox").css("display","block");
			//document.getElementById('#DisplayIncentiveCheckBox').style.display = "block";
			
			$('#DisplayRevenue').show();
			$('#DisplayRevenue').html(result.dvalueRevenue);
			if(result.dvalue==''){
			//$('#showIncentiveAddEditForm').html('');			
			$('#showIncentiveAddEditForm').hide();
			$('#DisplayIncentiveCheckBox').hide();			
			}else{ //Only Confirmed with Incentive
			$('#showIncentiveAddEditForm').show();	
			$('#DisplayIncentiveCheckBox').show();
			$('#DisplayIncentiveCheckBox').html(result.dvalue);
			
			
			}
			/*$('#guest_name_inc').attr('data-parsley-required', 'true');
			
			
			$('#checkin_inc').attr('data-parsley-required', 'true');
			$('#checkout_inc').attr('data-parsley-required', 'true');
			$('#no_room_inc').attr('data-parsley-required', 'true');
			$('#no_pax_inc').attr('data-parsley-required', 'true');
			$('#room_rate_inc').attr('data-parsley-required', 'true');
			$('#banquet_revenue_amount_inc').attr('data-parsley-required', 'true');
			$('#revenue_inc').attr('data-parsley-required', 'true');*/
			
					
		}   
		}
	});
	}
function IncentiveStatusConfirm(id){
	var id_user_created	=	$("#id_user_created").val();
	var id_hotel_created	=	$("#id_hotel_created").val();
	var eId	=	$("#eId").val();
	
	if (id == "none" || id== "") { 
			
			$('#DisplayHotelUser').html('');
			$('#incentive_remark').attr('data-parsley-required', 'true');		
	} else if (id == "1") {   //Verifed By Corperator
			
			$.ajax({
				type: "POST",
				url: 'ajax/ajaxListHotelUser.php',
				data: 'select=1'+'&id_user_created='+id_user_created+'&id_hotel_created='+id_hotel_created, 
				success: function (result) {
				
						$('#DisplayHotelUser').html(result);	
						$('#incentive_remark').attr('data-parsley-required', 'false');	
							   
				}
			});
	} else if (id == "2") {  //Not Approved
	
		$('#incentive_remark').attr('data-parsley-required', 'true');
		$('#DisplayHotelUser').html('');
		return false;
	} else if (id == "3") {  //Not Approved
	
	

		$('#incentive_remark').attr('data-parsley-required', 'false');
		$('#DisplayHotelUser').html('');
	}else {
		
		$.ajax({
				type: "POST",
				url: 'ajax/ajaxListHotelUser.php',
				data: 'select=2'+'&id_user_created='+id_user_created+'&id_hotel_created='+id_hotel_created+'&id_enquiry='+eId, 
				success: function (result) {
				
						$('#DisplayHotelUser').html(result);	
						$('#incentive_remark').attr('data-parsley-required', 'false');	
							   
				}
			});
		$('#incentive_remark').attr('data-parsley-required', 'false');
		$('#DisplayHotelUser').html('');
			}
			
			
			
	}	
function CheckCloseTypeConfirm(id){
	
		$.ajax({
 	    type: "POST",
        url: 'ajax/ajaxIncentiveCheckBox.php',
 	    data: 'select=type'+'&id='+id, 
	    success: function (result) {
			
		result = JSON.parse(result);
			
			
		if(result.id==1){
			
			$('#guest_name_inc').attr('data-parsley-required', 'false');
			/*$('#guest_name_inc').removeAttr('required','required');
			$('#checkin_inc').removeAttr('required','required');
			$('#checkout_inc').removeAttr('required','required');
			$('#no_room_inc').removeAttr('required','required');
			$('#no_pax_inc').removeAttr('required','required');
			$('#room_rate_inc').removeAttr('required','required');
			$('#banquet_revenue_amount_inc').removeAttr('required','required');
			$('#revenue_inc').removeAttr('required','required');*/
			
			
			$('#guest_name_inc').removeAttr('data-parsley-validate-if-empty');
			$('#checkin_inc').attr('data-parsley-required', 'false');
			$('#checkout_inc').attr('data-parsley-required', 'false');
			$('#no_room_inc').attr('data-parsley-required', 'false');
			$('#no_pax_inc').attr('data-parsley-required', 'false');
			$('#room_rate_inc').attr('data-parsley-required', 'false');
			$('#banquet_revenue_amount_inc').attr('data-parsley-required', 'false');
			$('#id_forward_for_approval').attr('data-parsley-required', 'false');
			$('#revenue_inc').attr('data-parsley-required', 'false');
			/*$('#guest_name_inc').removeAttr('data-parsley-required');

			$('#guest_name_inc').removeAttr('required','required');
			$('#guest_name_inc').attr('data-parsley-required', 'false');*/
			
			
			
			$('#DisplayRevenue').show();
			
			$('#DisplayIncentiveCheckBox').html('');
			$('#showIncentiveAddEditForm').html('');
			
			
			//$('#revenue_inc').removeAttr('required','required');
			







		}
		if(result.id!=1 && result.dvalue!='undefined'){
			
			//$("#DisplayIncentiveCheckBox").css("display","block");
			//document.getElementById('#DisplayIncentiveCheckBox').style.display = "block";
			$('#DisplayIncentiveCheckBox').show();
			$('#DisplayIncentiveCheckBox').html(result.dvalue);
			/*$('#guest_name_inc').attr('data-parsley-required', 'true');
			
			
			$('#checkin_inc').attr('data-parsley-required', 'true');
			$('#checkout_inc').attr('data-parsley-required', 'true');
			$('#no_room_inc').attr('data-parsley-required', 'true');
			$('#no_pax_inc').attr('data-parsley-required', 'true');
			$('#room_rate_inc').attr('data-parsley-required', 'true');
			$('#banquet_revenue_amount_inc').attr('data-parsley-required', 'true');
			$('#revenue_inc').attr('data-parsley-required', 'true');*/
			
					
		}	
	    		
				   
		}
	});
	}	
function displayincentiveaddeditform(id){
	
	//alert(id);
	var ClaimIncentive	=	$("#ClaimIncentive").val();
	//alert(ClaimIncentive);
	var enquiryDate		=	$("#enquiryDate").val();
	var id_hotel_md	=	$("#id_hotel_md").val();
		var edit_id_enquiry	=	$("#edit_id_enquiry").val();
	var checkbox = document.getElementById('ClaimIncentive');
		if (checkbox.checked == true)
		{
			
			$.ajax({
				type: "POST",
				url: 'ajax/ajaxIncentiveAddEditForm.php',
				data: 'selectType=1'+'&id='+id+'&id_hotel_md='+id_hotel_md+'&enquiryDate='+enquiryDate+'&edit_id_enquiry='+edit_id_enquiry, 
				success: function (result) {
					$('#showIncentiveAddEditForm').html(result);	
					$('#DisplayRevenue').hide();
						$('#guest_name_inc').attr('data-parsley-required', 'true');	
						$('#checkin_inc').attr('data-parsley-required', 'true');
						$('#checkout_inc').attr('data-parsley-required', 'true');
						$('#no_room_inc').attr('data-parsley-required', 'true');
						$('#no_pax_inc').attr('data-parsley-required', 'true');
						$('#room_rate_inc').attr('data-parsley-required', 'true');
						$('#banquet_revenue_amount_inc').attr('data-parsley-required', 'true');
						$('#id_forward_for_approval').attr('data-parsley-required', 'true');
						$('#revenue_inc').attr('data-parsley-required', 'true');
						$('#revenue').attr('data-parsley-required', 'false');
						
						$('#guest_name_inc').val('');	
						$('#checkin_inc').val('');	
						$('#checkout_inc').val('');	
						$('#no_room_inc').val('');	
						$('#no_pax_inc').val('');	
						$('#room_rate_inc').val('');	
						//$('#banquet_revenue_amount_inc').val('');	
						$('#revenue_inc').val('');
				}
		});	
					
			
			
			
			
			/*document.getElementById(id).style.display = "block";
						$('#guest_name_inc').attr('data-parsley-required', 'true');	
						$('#checkin_inc').attr('data-parsley-required', 'true');
						$('#checkout_inc').attr('data-parsley-required', 'true');
						$('#no_room_inc').attr('data-parsley-required', 'true');
						$('#no_pax_inc').attr('data-parsley-required', 'true');
						$('#room_rate_inc').attr('data-parsley-required', 'true');
						$('#banquet_revenue_amount_inc').attr('data-parsley-required', 'true');
						$('#revenue_inc').attr('data-parsley-required', 'true');
						$('#revenue').attr('data-parsley-required', 'false');
						
						$('#guest_name_inc').val('');	
						$('#checkin_inc').val('');	
						$('#checkout_inc').val('');	
						$('#no_room_inc').val('');	
						$('#no_pax_inc').val('');	
						$('#room_rate_inc').val('');	
						$('#banquet_revenue_amount_inc').val('');	
						$('#revenue_inc').val('');	
						
						$('#DisplayRevenue').hide();*/
		//alert("you need to be fluent in English to apply for the job");
		}else{
			$('#DisplayRevenue').show();
			$('#showIncentiveAddEditForm').html('');	
						$('#guest_name_inc').attr('data-parsley-required', 'false');
						$('#checkin_inc').attr('data-parsley-required', 'false');
						$('#checkout_inc').attr('data-parsley-required', 'false');
						$('#no_room_inc').attr('data-parsley-required', 'false');
						$('#no_pax_inc').attr('data-parsley-required', 'false');
						$('#room_rate_inc').attr('data-parsley-required', 'false');
						$('#banquet_revenue_amount_inc').attr('data-parsley-required', 'false');
						$('#id_forward_for_approval').attr('data-parsley-required', 'false');
						$('#revenue_inc').attr('data-parsley-required', 'false');
						$('#revenue').attr('data-parsley-required', 'true');
						$('#revenue').val('');
			}
	/*
			if (document.getElementById(id).style.display == "none" || document.getElementById(id).style.display == "") {
				document.getElementById(id).style.display = "block";
						$('#guest_name_inc').attr('data-parsley-required', 'true');	
						$('#checkin_inc').attr('data-parsley-required', 'true');
						$('#checkout_inc').attr('data-parsley-required', 'true');
						$('#no_room_inc').attr('data-parsley-required', 'true');
						$('#no_pax_inc').attr('data-parsley-required', 'true');
						$('#room_rate_inc').attr('data-parsley-required', 'true');
						$('#banquet_revenue_amount_inc').attr('data-parsley-required', 'true');
						$('#revenue_inc').attr('data-parsley-required', 'true');
						$('#revenue').attr('data-parsley-required', 'false');
						
						$('#guest_name_inc').val('');	
						$('#checkin_inc').val('');	
						$('#checkout_inc').val('');	
						$('#no_room_inc').val('');	
						$('#no_pax_inc').val('');	
						$('#room_rate_inc').val('');	
						$('#banquet_revenue_amount_inc').val('');	
						$('#revenue_inc').val('');	
						
				$('#DisplayRevenue').hide();
			} else if (document.getElementById(id).style.display == "block") {
				document.getElementById(id).style.display = "none";
						$('#guest_name_inc').attr('data-parsley-required', 'false');
						$('#checkin_inc').attr('data-parsley-required', 'false');
						$('#checkout_inc').attr('data-parsley-required', 'false');
						$('#no_room_inc').attr('data-parsley-required', 'false');
						$('#no_pax_inc').attr('data-parsley-required', 'false');
						$('#room_rate_inc').attr('data-parsley-required', 'false');
						$('#banquet_revenue_amount_inc').attr('data-parsley-required', 'false');
						$('#revenue_inc').attr('data-parsley-required', 'false');
						$('#revenue').attr('data-parsley-required', 'true');
						$('#revenue').val('');
				$('#DisplayRevenue').show();
			} else {
				document.getElementById(id).style.display = "none";
				$('#DisplayRevenue').hide();
			}*/
		}
</script> 
<script>

  $( function() {	 

    $( ".datepickertest").datepicker({ dateFormat: 'dd-mm-yy',minDate: 0});
    //$( ".pickerdate").datetimepicker({  minDate:new Date()});
    
  $( ".datepickercheckin").datepicker({ dateFormat: 'dd-mm-yy', minDate: '-<?php echo $DateNoDays; ?>d' });
 $( ".datepickercheckout").datepicker({ dateFormat: 'dd-mm-yy',minDate: '-<?php echo $DateNoDays; ?>d'});
  } );

  <?php if($row->id != ''){ ?>
    window.onload = function() {   
		onLoadIdCompany(<?php echo $row->id_company; ?>);
        getExecutiveName(<?php echo $row->id_company; ?>,<?php echo $row->id_contact; ?>);
    };
<?php } ?> 
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
 
</script> 