<?php include_once("../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_ORDERS,'view');
?>

<?php
//unset($_SESSION['editCart']);
/*$array 	=	$_SESSION['editCart'];
foreach( $array as $key => $value ){	
	    if( $key == "" ){ 
		unset($_SESSION['editCart'][$key]);
		}
}*///unset($_SESSION['editCart']);

/*echo "<pre>";
print_r($_SESSION);
echo "</pre>";*/
//($_SESSION['editCart']);
////unset previously set session///////////////

//unset($_SESSION);
/*unset($_SESSION['editCart']);
unset($_SESSION['eId']);
unset($_SESSION['editCart']['charges_total']);
unset($_SESSION['editCart']['charges_price']);
unset($_SESSION['editCart']['charges_description']);
unset($_SESSION['editCart']['charges_total']);*/
//////////////////////////////////////////////


// UPDATE tarrif_price_per_day------------------------------------
/*
$sql_order = mysqli_query($connNew,"SELECT * FROM `".TBL_ORDERS."`");
while($record = mysqli_fetch_array($sql_order)){
	$sqlOrderDetail2 = executeSql("Select * from `".TBL_ORDER_DETAIL."` where id_order=".addslashes($record['id_order']));
			if(num_rows($sqlOrderDetail2) >0 ){
				while($rowOrderDetail_update= $db->fetch_object2($sqlOrderDetail2)){
					$room_quantity	 = $rowOrderDetail_update->room_quantity;
					$tarrif_price	 = $rowOrderDetail_update->total_price;//tarrif_price;
					$tarrif_price_per_day1	=	$tarrif_price/$room_quantity;
					$updateInventory = executeSql("UPDATE  `".TBL_ORDER_DETAIL."`  SET 
						  `tarrif_price_per_day`='".addslashes($tarrif_price_per_day1)."'
								where  `id_order_detail`='".addslashes($rowOrderDetail_update->id_order_detail)."'");
				}				
			}
	}*/
// UPDATE tarrif_price_per_day------------------------------------	



if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
	$sql = "SELECT * FROM `".TBL_ORDERS."` where `id_order` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'";
	$_SESSION['eId']	=	$_REQUEST['eId'];
	$db->query($sql);
	if($db->num_rows() > 0){
		$row = $db->fetch_object();
	}	
	
 	  $_SESSION['OrderUniqueID'] = addslashes(encryptor('decrypt',$_REQUEST['eId']));
 	  $OrderUniqueID			 = $_SESSION['OrderUniqueID'];


unset($_SESSION['editCart'][$OrderUniqueID]);
unset($_SESSION[$OrderUniqueID]['eId']);
unset($_SESSION['editCart'][$OrderUniqueID]['charges_total']);
unset($_SESSION['editCart'][$OrderUniqueID]['charges_price']);
unset($_SESSION['editCart'][$OrderUniqueID]['charges_description']);
unset($_SESSION['editCart'][$OrderUniqueID]['charges_total']);


	$disabled = 'disabled="disabled"';
	
	$_SESSION['editCart'][$OrderUniqueID]['orderReference']		 = $row->reference;
	$_SESSION['editCart'][$OrderUniqueID]['amountReceived']		 = round($row->amount_received,2);						
	$_SESSION['editCart'][$OrderUniqueID]['discountPrice'] 		 = round($row->total_discounts,2);
	$_SESSION['editCart'][$OrderUniqueID]['AdditionalChargesPrice'] = round($row->total_addcharges,2);
	$_SESSION['editCart'][$OrderUniqueID]['addcharges_var']		 = round($row->addcharges_var,2);
	$_SESSION['editCart'][$OrderUniqueID]['addcharges_type']	 = round($row->addcharges_type,2);
	$_SESSION['editCart'][$OrderUniqueID]['noOfDays']			 = $row->no_of_days;
	$_SESSION['editCart'][$OrderUniqueID]['id_company']			 = $row->id_company;
	$_SESSION['editCart'][$OrderUniqueID]['id_guest']			 = $row->id_customer;
	$_SESSION['editCart'][$OrderUniqueID]['id_contacts']		 = $row->id_company_person;
	$_SESSION['editCart'][$OrderUniqueID]['hotel_id']			 = $row->id_hotel;
	$_SESSION['editCart'][$OrderUniqueID]['rate_id']			 = $row->id_rate;
	
	$id_shop = $row->id_shop;
	
	$_SESSION['editCart'][$OrderUniqueID]['series']['series']		=	$row->series_id;				  
	$_SESSION['editCart'][$OrderUniqueID]['series']['operator']		=	$row->operator_id;
	$_SESSION['editCart'][$OrderUniqueID]['series']['type']			=	$row->type;
				  
				  
	$_SESSION['editCart'][$OrderUniqueID]['reservation_date'] = date('d-m-Y',strtotime($row->checkin)).' to '. date('d-m-Y',strtotime($row->checkout));
	$days =  abs((strtotime($row->checkin) - strtotime($row->checkout))/ 86400 );
	if($days == '0'){
	$noOfDays = '1';
	}else {
	$noOfDays = $days;
	}		
}elseif($_REQUEST['type']=='N' || $_REQUEST['type']=='C'){
	 $_SESSION['OrderUniqueID'] = rand(0000,9999);
 	 $OrderUniqueID	= $_SESSION['OrderUniqueID'];
 
 
	}	
	

?>
<?php include_once("includes/header.php")?>
   <?php include_once("includes/left.php")?>
   <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <section class="content-header">
       <h1>View Rates  <small></small> </h1>
       <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">View Rates</li>
      </ol>
     </section>
    <!-- Main content -->
    <section class="content">
       <div class="row"> 
        <!-- left column -->
        <div class="col-md-12"> 
           <!-- general form elements -->
           
           <div class="nav-tabs-custom">
            <div class="box-header with-border">
            <?php 
			$AmendmentCount	= $row->code;
			
			if($AmendmentCount >0){					
				$AmendmentTotalCount	= '-'.$row->code;
				}	
			?>
               <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'View':'View'?> Rates <a><?php echo $row->reference.$AmendmentTotalCount; ?></a></h3>
             </div>
            <!-- /.box-header --> 
            <!-- form start -->
            
            <form name="form1" id="availabiltyForm"  method="post" enctype="multipart/form-data" action="checkoutEdit.php" data-parsley-validate>
               <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" id="eId" />
                
                <input type="hidden" name="book_type" id="book_type" value="<?php echo $_REQUEST['type']?>" />    
               <div class="form-group has-error" align="center">
                <?php if($_SESSION['errorMsg']){?>
                <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
                <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
                <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
                <?php unset($_SESSION['successMsg']);}?>
              </div>
               <div class="box-body">
                <ul class="timeline">
                   <!-- timeline item -->
                   <li class="time-label"> <span class="bg-red"> Main Information </span> </li>
                  
                   <li> <i class="fa fa-angle-double-right bg-red"></i>
                    <div class="timeline-item">
                       <div class="row">
                       
                       
                       
                       <div class="form-group col-sm-3">
                           <label for="reservation_date">Checkin Date - Checkout Date </label>
                           <div class="input-group">
                                                       
                            
                            
                           
                           <div class="input-group">
                               <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                               <input  type="text" class="form-control pull-right dateRangeEditRateLetter" id="reservation_date" name="reservation_date" data-parsley-required value="<?php echo date('d-m-Y').' to '.date('d-m-Y') ?>" data-parsley-errors-container="#reservation_dateError"  autocomplete="off">
                             </div>
                          
                           
                          </div>
                           <!-- /.input group --> 
                           <span id="reservation_dateError"></span> </div>
                           
                       <?php 
                   $comSQL = "SELECT DISTINCT ".TBL_COMPANY.".* FROM ".TBL_COMPANY." LEFT JOIN ".TBL_RATE." ON ".TBL_COMPANY.".id_company= ".TBL_RATE.".company_id 
                   
                   WHERE ".TBL_COMPANY.".status=1 and ".TBL_COMPANY.".`id_shop` = '".addslashes($_SESSION['shop'])."' and ".TBL_COMPANY.".name !='' AND ".TBL_COMPANY.".status=1 AND ".TBL_RATE.".status=1  ORDER BY ".TBL_COMPANY.".name ";

                   ?>     
                      <div class="form-group col-sm-5">
                           <label for="id_company">Company Name - City</label>
                           <select class="form-control" name="id_company" id="id_company" onChange=" getcreditvalueNew(this.value,''); getContact(this.value,''); getHotelListRateAndUnit();   " data-parsley-errors-container="#companyError" data-parsley-required >
                            <option value="">Select Company</option>
                            
                        
                          </select>
                           <span id="companyError"></span> </div>
	
      
			            <input type="hidden" value="<?php echo $OrderUniqueID;?>" name="OrderUniqueID" id="OrderUniqueID" />
                        <div class="form-group col-sm-5">
                           <label for="hotel_id" >Hotel</label>
                           <?php 
				$categoryDropDown = '<select name="hotel_id" id="hotel_id" class="form-control select2" data-parsley-required data-parsley-errors-container="#hotelError"  onChange="getRoom(this.value,0); ajaxAddRoommsgUpdate(); getRateLetterForView();" '.$disabled.'>
									 					  <option value="">Select Hotel</option>';
	  $resCat = selectSql(TBL_HOTELS," where status='1' AND id_shop='".addslashes($_SESSION['shop'])."'".$_SESSION['HotelPerHotel']." ",' ORDER BY `name`');
											  if(num_rows($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($row->id_hotel == $resultCat->id){
													   $selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'-'.ucfirst($resultCat->city).'</option>';
												}
											  }
											 	echo $categoryDropDown .= '</select>';
									
									 ?>
                           
                           <span id="hotelError"></span> </div>
                        
                        
                       
                        
            
                           
                           
                           
                        
                       
                            
                                            <div class="form-group col-sm-3 " >

                                            	<label for="id_company">Rate Letters</label>
                          <select class="form-control" name="rate_id" id="rate_id" onChange="ajaxCheckAdogoRateletter();  showRateLetterView(this);" data-parsley-required data-parsley-errors-container="#rate_idError">
                                                <?php 
                        						//`fs_rate_details`.hotel_id='".addslashes($row->id_hotel)."' AND;
                        			  $rate_level_assgin = selectColumn(TBL_COMPANY,'id_rate_level'," WHERE `id_company` = '".addslashes($row->id_company)."'");
                        			  
                        $resCat = executeSql("SELECT `".TBL_RATE."`.*, `".TBL_RATE_LEVEL."`.name as level_name ,`".TBL_RATE_MARKET."`.name as market_name from `".TBL_RATE."` LEFT JOIN `".TBL_RATE_LEVEL."` ON `".TBL_RATE."`.rate_level_id=`".TBL_RATE_LEVEL."`.id   LEFT JOIN `".TBL_RATE_MARKET."` ON `".TBL_RATE."`.market=`".TBL_RATE_MARKET."`.id  LEFT JOIN `fs_rate_details` ON `fs_rate`.id=`fs_rate_details`.rate_id  where   `fs_rate_details`.hotel_id='".addslashes($row->id_hotel)."' AND  `".TBL_RATE."`.id_shop='".addslashes($_SESSION['shop'])."' and (`".TBL_RATE."`.company_id='".$row->id_company."' || `".TBL_RATE."`.company_id='0' ) and (( `".TBL_RATE."`.start_date <=  '".date('Y-m-d',strtotime($row->checkin))."' and  `".TBL_RATE."`.end_date >= '".date('Y-m-d',strtotime($row->checkout))."') OR (  `".TBL_RATE."`.start_date between '".date('Y-m-d',strtotime($row->checkin))."' and '".date('Y-m-d',strtotime($row->checkout))."') OR (  `".TBL_RATE."`.end_date between '".date('Y-m-d',strtotime($row->checkin))."' and '".date('Y-m-d',strtotime($row->checkout))."')) group by `".TBL_RATE."`.rate_name" );
                        							  if($db->num_rows2($resCat)==0 and $row->id_rate!=''){
                        									  $planData .= '<option '.$selected.' value="0"></option>';
                        									  }
                        							  
                        							  
                        							  if($db->num_rows2($resCat)){
                        								  
                        								  
                                                       $planData .= '<option '.$selected.' value="0"></option>';
                                                    	
                        							
                        							
                        							
                        								while($resultCat = $db->fetch_object2($resCat)){
                        									if($row->id_rate == $resultCat->id){
                        										$selected = 'selected="selected"';
                        									}else{
                        										$selected = '';
                        									}
                        									$planData .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->rate_name).' | '.ucfirst($resultCat->level_name).' | '.ucfirst($resultCat->market_name).'</option>';
                        								}
                        							  //}elseif(){
                        								  
                        								  }else{
                        							  $planData .= '<option value="" >Rate Letter</option>';
                        							  
                        							  }
                        							 echo $planData; ?>
                                              </select>
                                              
                                               <span id="rate_idError"></span> </div>

                                              <div class="col-sm-2"> 

                                               <div class="box-tools pull-right">
                                               	<label></label>
                                            <div id="view"  <?php if($row->id_rate=='0' ){echo 'style="display:none;"';} ?> >
                                               <button class="btn btn-danger" type="button" id="view" > <i class="fa fa-eye fa-lg"></i> View</button>
                                               </div>
                                               <div id="adhol" <?php if($row->id_rate !='0'){echo 'style="display:none;"';} ?>>
                                               <button class="pull-left btn btn-success btn-xs" id="adhoc" type="button" onclick="ajaxAddRoom('.$row->id.','.$row->rate_assign_id.','.$rowRoom->room_id.','.$rowRoom->rate_plan_id.',0);" style="width: 50px;height: 36px;margin-right: 8px"><i class="fa fa-plus-circle" > Add </i></button></div>
                                             </div>
                                         </div>
                        

                       
                        
                        <!--<div class="form-group col-sm-5">
                           <label for="id_contacts" >Contact Person</label>
                           <div class="input-group" id="showbookedby">
                            <select class="form-control select2" name="id_contacts" id="id_contacts" data-parsley-errors-container="#contactError" >
                               <option value="">Select User</option>
                             </select>
                            <span id="contactError"></span>
                            <div class="input-group-addon bookedby_open"> <i class="fa fa-plus"></i> </div>
                          </div>
                         </div>-->
                        
                           <div style="color:red; float:left; margin-left:15px; margin-top:29px;" id="getcredit_value"></div>
                        
                        
                      </div>
                                             <div style="color:red; text-align:center; margin-top:29px;" id="getRecordExist"></div>

                     </div>
                  </li>
                   <!-- /.timeline-label --> 
                   <!-- timeline item -->
                 </ul>
                 
                  
   
   
                <?php if($row->date_created){?>
                <div class="clearfix"></div>
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
                <?php } ?>
              </div>
              <?php // } ?>
   
   <div  id="SeriesBookingMasterDetail"> </div>
   
              
               <!-- /.box-body -->
               <!--<div class="box-footer" style="float: left;width: 98%;">
                <input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("manageOrders.php"); '>
              </div>-->
             </form>
          </div>
           <!-- /.box --> 
         </div>
      </div>
       <!-- /.row --> 
     </section>
    <!-- /.content --> 
  </div>
  
  
   
   <div id="pricePopUp" class="well" style="display:none;">
    <form id="pricePopUpform" data-parsley-validate autocomplete="off" method="post"  >
       <input type="hidden" id="uniqueCode" name="uniqueCode" >
       <div class="form-group">
        <label for="tarrif">Tarrif Per Night/Room</label>
        <input type="text" class="form-control input-sm" placeholder="Enter tarrif price" id="tarrif" name="tarrif" value="" data-parsley-required data-parsley-type="digits">
      </div>
       <div class="form-group">
        <label for="meal">Meal Price</label>
        <input type="text" class="form-control input-sm" placeholder="Enter meal price" id="meal" name="meal" value="" data-parsley-type="digits">
      </div>
       <div class="form-group">
        <label for="extra">Extra Price</label>
        <input type="text" class="form-control input-sm" placeholder="Enter extra price" id="extra" name="extra" value="" data-parsley-type="digits">
      </div>
       <input  type="button" class="btn btn-default" onClick="savepricePopUpform();" value="Save">
       <button class="pricePopUp_close btn btn-default">Close</button>
     </form>
  </div>
   <!---- pop up--->
   <div id="planDetail" class="well" style="display:none; min-width:55em;"> <a href="#" class="planDetail_close" style="float:right;padding:0 0.4em;"><i class="fa fa-times text-red"></i></a>
    <div id="ajaxPlanData"></div>
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
   <div id="bookedby" class="well">
    <form id="bookedbypopupform" data-parsley-validate autocomplete="off" method="post"  >
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
         </select>
      </div>
       <div class="form-group">
        <label for="first_name">First Name </label>
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
       <input  type="button" class="btn btn-default" onClick="saveBookedbyPopupform();" value="Save">
       <button class="bookedby_close btn btn-default">Close</button>
     </form>
  </div>
     <div id="OldBookingTaxConfig" class="well" style="display:none; text-align:none !important; width:24%;"> <!--<a href="#" class="OldBookingTaxConfig_close" style="float:right;padding:0 0.4em;"><i class="fa fa-times text-red"></i></a>-->
     <form id="inventorypopupForm" data-parsley-validate autocomplete="off" style="text-align:center;">
	  <input type="hidden" name="start_date" id="start_date" value="" >
	  <input type="hidden" name="end_date" id="end_date" value="" >	
     <p> New Tax value will be Applied </p>
    <button class="btn btn-danger" onclick="NewTaxUpdate();" type="button">Continue</button>
    <button class="OldBookingTaxConfig_close btn btn-primary">Cancel</button>
  </form>
    
  </div>
   
    <span class="my_popup_open" style="display:none;"></span>
<div id="my_popup" class="well">
    <div id="TaxUpdateData"></div>
   <!-- <button class="my_popup_close btn btn-default pull-right">Close</button>-->
</div>
   <!---- pop up end---> 
   
   <script>
<?php  
if($row->id_order != ''){ ?>

window.onload = function() { getContact(<?php echo $row->id_company; ?>,<?php echo $row->id_company_person; ?>);
							
							 document.getElementById("arrival_time").value = '<?php if($_POST) echo $_POST['arrival_time'];else if($row->arrival_time)  echo date('h:i a',strtotime(stripslashes($row->arrival_time))); else echo ''; ?>';	
  							
							};
							
<?php } ?>	
</script>
<!--
Below Code is added by Hitesh Aloney on 10-08-2018 and uncommented on 13-09-2018
This code check the credit allowed or not option on page load 
-->
<script type="text/javascript">
  var cmpId = document.getElementById("id_company").value; 
 	
  function checkCreditFirst(id_comp){
    var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
			var resultArray = this.responseText.split('&&&&');			
          document.getElementById("getcredit_value").innerHTML = resultArray['1'];
        }
      };
      xhttp.open("GET", "ajax/ajaxgetcreditvalue.php?id_company="+id_comp, true);
      xhttp.send();
  }

  checkCreditFirst(cmpId);
</script>
<!-- Above Code Ends Here-->

<script>


function getGetSeriesDate(){
	
	var seriesId = $('#series').val();
	var operatorId = $('#operator').val();
	var hotel_id = $('#hotel_id').val();
	var reservation_date = $("#reservation_date").val();
	
		var id_company = $('#id_company').val();
			 $.ajax({
			   type: "GET",
			   url: 'ajax/ajaxSeriesBookingDate.php',
			   data: 'seriesId='+seriesId+'&operatorId='+operatorId+'&hotel_id='+hotel_id+'&id_company='+id_company+'&reservation_date='+reservation_date, 
			   success: function (result) {	
			   
			   
			   $('#reservation_date').val(result);
			   
				// document.getElementById("reservation_date").innerHTML = this.responseText;	
			}
		})

}



function NewTaxUpdate(){
	
	
	$.ajax({
			   type: "GET",
			   url: 'ajax/ajaxupdateNewTax.php',
			   data: 'dataValue=1', 
			   success: function (result) {
				  // alert(result);

				 $( ".my_popup_open" ).click();	
				 $("#TaxUpdateData").html(result);  }
				})
	$('#OldBookingTaxConfig').popup('hide');
	
	exit;
	}
function newTaxOnChange(uniqueCode){	

	var arrayCode = uniqueCode.split("|");
	var uniqueId = arrayCode['1'];
	
	var tax_group_id = $('#tax_group_id\\|'+uniqueId).val();
	var TaxPerdayPerroom = $('#TaxPerdayPerroom\\|'+uniqueId).val();
	
	

if(tax_group_id ==1 &&  TaxPerdayPerroom ==0){
				$('#OldBookingTaxConfig').popup({
        			transition: 'all 0.3s',
           			 autoopen: true,            
        			});
					

	
	exit;
			}	
}


function getcreditvalueNew(id_company){
	
var id_company = $("#id_company").val();
var reservation_date = $("#reservation_date").val();
var rate_id = $("#rate_id").val();
var hotel_id = $("#hotel_id").val();
	
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxgetcreditvalue.php',
		   data: 'id_company='+id_company+'&reservation_date='+reservation_date+'&hotel_id='+hotel_id,  
		   success: function (result) {	
		   resultArray = result.split('&&&&');
		   		$( "#getcredit_value" ).html(resultArray[1]);
				$( "#getRecordExist" ).html(resultArray[0]);					
			}
		})

}

//////////////////////check availabilty -book-now.php///////////////////////////////////////////////// 

function ajaxCheckAvailability() {
          
  		  var form=$("#availabiltyForm");		  
		  form.parsley().validate();		  
  		  $('.loading').show(); 
		  $.ajax({
			   type: "POST",
			   url: 'ajax/ajaxcheckAvailability.php',
			   data: form.serialize(), 
			   success: function (result) {
					$('#availabilty').html(result)
				},
			  complete: function(){
				$('.loading').hide();
			  }
		})
	return false;
 }
/////////////////////////////////show events on date -book-now.php/////////////////////////////////////////////
function getEvents(dated){
//$('#eventsPopup').popup('show');
 $('#eventsPopup').popup({
            //pagecontainer: '.container',
        	transition: 'all 0.3s',
            autoopen: true,            
        });
}





$("#view").click(function (){
 var form1=$("#availabiltyForm");	
 var form2=$("#addRoomForm");
 var dataString = $("#availabiltyForm, #addRoomForm").serialize();	
	if(form1.parsley().validate() && form2.parsley().validate()){
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxGetPlanDetails.php',
		   data: dataString, 
		   success: function (result) {					
				$( "#ajaxPlanData" ).html(result);
				$('#planDetail').popup({
        			 transition: 'all 0.3s',
           			 autoopen: true,            
        		});
				 //$("#hotelId").val('1').attr('selected','selected');					
			}
		})
	}
})


////////////////////////////////////////////////////////////////////////////////


function ajaxAddRoom(rate_id,rate_assign_id,room_id,rate_plan_id,type){
   var form1=$("#availabiltyForm");	
   var form2=$("#addRoomForm");
   var dataString = $("#availabiltyForm, #addRoomForm").serialize();
	if(form1.parsley().validate() && form2.parsley().validate()){
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxaddRoom.php',
		   data: dataString+'rate_id='+rate_id+'&rate_assign_id='+rate_assign_id+'&room_id='+room_id+'&rate_plan_id='+rate_plan_id+'&type='+type, 
		   success: function (result) {					
				resultArray = result.split('|||');
					if(resultArray['0']!=''){
						$('#roomLimitMsg').css('display', 'block');
						$('#roomLimitMsg').html(resultArray['0']);
					}
					$('#showRoom').append(resultArray['1']);
					$('#addRoommsg').css('display', 'none');
					$('#createBooking').css('visibility', 'visible');					
			}
		})
	}


}

//////////////////////////////save price popup form common//////////////////////////////////////////////////////////


function pricePopUp(id){
	var Id = id.split('_');
	var uniqueId= Id[1];
	$('#uniqueCode').val(uniqueId);
	
}

function savepricePopUpform(){
	var uniqueCode = $("#uniqueCode").val();
	var dataValue = $('#dataValue'+'\\|'+uniqueCode).val();		
	var form=$("#pricePopUpform");
	if(form.parsley().validate()){
	$('.loading').show(); 
	$.ajax({
	   type: "POST",
	   url: 'ajax/ajaxSavePrice.php',
	   data: form.serialize()+'&dataValue='+dataValue, 
	   success: function (result) {
	    $('#pricePopUp').popup('hide');
		$("#pricePopUpform")[0].reset();
		 alert('Price has been updated.');
		 $('#price_'+uniqueCode).html(result);		
		},
	  complete: function(){
		$('.loading').hide();
	  }
	});
	return false;
	}
}





</script>

<?php include_once("includes/footer.php")?>

   
 
<script>


function calculateOthercharge(id){
var tot = 0;
var data = new Array();
value= id.split('|');	
	mainId = value[0];
	dateId = value[1];
	
var otherChargeType 		= parseFloat($('#otherChargeType\\|'+dateId).val());
var charges_price 			= (isNaN(parseFloat($('#charges_price\\|'+dateId).val())) ? 0 : parseFloat($('#charges_price\\|'+dateId).val()));
var charges_tax 			= (isNaN(parseFloat($('#charges_tax\\|'+dateId).val())) ? 0 : parseFloat($('#charges_tax\\|'+dateId).val()));
var charges_total 			= (isNaN(parseFloat($('#charges_total\\|'+dateId).val())) ? 0 : parseFloat($('#charges_total\\|'+dateId).val()));
//var charges_tax 			= parseFloat($('#charges_tax\\|'+dateId).val());
//var charges_total 		= parseFloat($('#charges_total\\|'+dateId).val());
var charges_net 			= parseFloat($('#charges_net\\|'+dateId).val());
var charges_description 	= $('#charges_description\\|'+dateId).val();


 var otherChargeTypeNoOfDays			= $('#otherChargeTypeNoOfDays\\|'+dateId).val();
var totalPrice = parseFloat($('#totalPrice').val());

if(charges_tax !== null && charges_tax !== '') {

var total = parseFloat((charges_price*charges_tax)/100)*otherChargeTypeNoOfDays;
}
$('#charges_total\\|'+dateId).val(total);

var netamount = +total + +charges_price*otherChargeTypeNoOfDays;

$('#charges_net\\|'+dateId).val(netamount);
var OrderUniqueID = $("#OrderUniqueID").val();

$.ajax({
	    type: "GET",
	  	url: 'ajax/ajaxUpdateAdditionalChargesEditPage.php',
    	data: 'discount=apply'+'&charges_total='+total+'&otherchagersid='+dateId+'&charges_tax='+charges_tax+'&charges_price='+charges_price+'&charges_description='+charges_description+'&charges_net='+charges_net+'&otherChargeType='+otherChargeType+'&otherChargeTypeNoOfDays='+otherChargeTypeNoOfDays+'&OrderUniqueID='+OrderUniqueID, 
    cache: false,
	   
	   success: function (result) {
		 var  resultArray = result.split('|||');
		// $('#discount').html(resultArray['0']);		
		//alert(result);
		 $('#addchargesvalue').html(resultArray['0']);	
		 $('#totalPrice').html(resultArray['1']);	
		 $('#balance').html(resultArray['3']);			
		 $('#tax').html(resultArray['2']);	
		 var  resultArray1 = resultArray['4'].split('###');
		 
		 $('#otherChargeTypeNoOfDays_'+resultArray1['1']).html(resultArray1['0']);	
		// $('#TaxPerdayPerroom_'+uniqueId).html(resultArray['1']);	 	
	/*	// $('#balance').html(resultArray['3']);
		 var  resultArray1 = resultArray['4'].split('###');
		$('#otherChargeTypeNoOfDays|'+resultArray1['1']).html(resultArray['3']);*/	  		
		}
	});
 
}






//window.onbeforeunload = function() { return "Your work will be lost."; };
function discounttype(discountType) {
	if(discountType == '1' ){
		$("#flat").css('display', 'block');
		$("#percent").css('display', 'none');
	}else{
		$("#flat").css('display', 'none');
		$("#percent").css('display', 'block');
	}
}
function applyDiscount() {
var discountType = $('#discountType').val();
	if(discountType == '1' ){
		var discountVar = $('#flatDiscount').val();
	}else{
		var discountVar = $('#percentDiscount').val();
	}
var OrderUniqueID = $("#OrderUniqueID").val();
 $.ajax({
	   type: "GET",
	   url: 'ajax/ajaxUpdateDiscountEditPage.php',
	   data: 'discount=apply'+'&discountType='+discountType+'&discountVar='+discountVar+'&OrderUniqueID='+OrderUniqueID, 
	   success: function (result) {
		 var  resultArray = result.split('|||');	
		 $('#discount').html(resultArray['0']);	
		 $('#totalPrice').html(resultArray['1']);				
		 $('#discountMsg').html(resultArray['2']);	
		 $('#balance').html(resultArray['3']);	  
		
		}
	});

}

function applyEvoucher() {
	
var EvoucherPassCode = $('#EvoucherPassCode').val();

var EvoucherValue = $("#EvoucherValue").val();
var OrderUniqueID = $("#OrderUniqueID").val();

 $.ajax({
	   type: "GET",
	   url: 'ajax/ajaxUpdateEvoucherEditPage.php',
	   data: 'Evoucher=apply'+'&EvoucherPassCode='+EvoucherPassCode+'&EvoucherValue='+EvoucherValue+'&OrderUniqueID='+OrderUniqueID+'&OrderUniqueID='+OrderUniqueID, 
	   success: function (result) {		 
		   if(result	== '1'){
			   $('#EvoucherMsg').html('Please Enter Evoucher Code.');
			   $('#EvoucherValueDisplay').html('<input type="text" class="form-control" id="EvoucherValue" name="EvoucherValue" value="0" autocomplete="off" disabled>');
			}
			else if(result	== '2'){
			   $('#EvoucherMsg').html('Invalid Evoucher Code.');
			   			   $('#EvoucherValueDisplay').html('<input type="text" class="form-control" id="EvoucherValue" name="EvoucherValue" value="0" autocomplete="off" disabled>');

			   }   
			else{
			   $('#EvoucherValueDisplay').html(result);
			   $('#EvoucherMsg').html('');
			   }  
			      
			  
		
		}
	});

}

/*function getRateLetter(){		
	
	var reservation_date = $("#reservation_date").val();
	var id_company = $("#id_company").val();
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxGetRateLetter.php',
		   data: 'reservation_date='+reservation_date+'&id_company='+id_company, 
		   success: function (result) {			  	    
				$( "#rate_id" ).html(result);
				//ajaxRoomRemoveAll();										
			}

		})
}*/
function changeEditData(){		
	
	var reservation_date = $("#reservation_date").val();
	var id_company = $("#id_company").val();
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxGetRateLetter.php',
		   data: 'reservation_date='+reservation_date+'&id_company='+id_company, 
		   success: function (result) {			  	    
				//$( "#rate_id" ).html(result);
				//ajaxRoomRemoveAll();										
			}
		})
}




function changePaymentDate(startDate){
	
	var paymentDate = moment(startDate, "YYYY-MM-DD");
	paymentDate.subtract(45, 'days');
	
	var d= moment();
	$('#payment_date').datepicker('destroy');
	if(paymentDate < d){
	$('#payment_date').val(d.format('DD-MM-YYYY'));
	
	}else{
	$('#payment_date').val(paymentDate.format('DD-MM-YYYY'));
	}
	$("#payment_date").datepicker({
	dateFormat : 'dd-mm-yy',
	minDate : new Date(),
	maxDate : new Date(startDate),
	});

}


$("#view").click(function (){
	var reservation_date = $("#reservation_date").val();
	var id_company = $("#id_company").val();
	var rate_id = $("#rate_id").val();
	var hotel_id = $("#hotel_id").val();
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxGetPlanDetails.php',
		   data: 'reservation_date='+reservation_date+'&id_company='+id_company+'&rate_id='+rate_id+'&hotel_id='+hotel_id, 
		   success: function (result) {					
				$( "#ajaxPlanData" ).html(result);
				$('#planDetail').popup({
        			transition: 'all 0.3s',
           			 autoopen: true,            
        			});
				//$("#hotelId").val('1').attr('selected','selected');					
			}
		})

	
})



function ajaxAddRoom(rate_id,rate_assign_id,room_id,rate_plan_id,type){
	var reservation_date = $("#reservation_date").val();
	var rate_id = $("#rate_id").val();
	var hotel_id = $("#hotel_id").val();
	var OrderUniqueID = $("#OrderUniqueID").val();	
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxaddRoomEditPage.php',
		   data: 'reservation_date='+reservation_date+'&hotel_id='+hotel_id+'&rate_id='+rate_id+'&rate_assign_id='+rate_assign_id+'&room_id='+room_id+'&rate_plan_id='+rate_plan_id+'&type='+type+'&OrderUniqueID='+OrderUniqueID, 
		   success: function (result) {				
		   	
				resultArray = result.split('|||');
								
					$('#showRoom').append(resultArray['1']);
					$('#pricingValue').html(resultArray['2']);
					$('#addRoommsg').css('display', 'none');
					$('#flatDiscount').val();
					$('#percentDiscount').val();
					$('#flatAdditionalCharges').val();
					$('#percentAdditionalCharges').val();				
			}
		})

}

function ajaxAddothercharges(rate_id,rate_assign_id,room_id,rate_plan_id,type){
	var reservation_date = $("#reservation_date").val();
	var rate_id = $("#rate_id").val();
	var hotel_id = $("#hotel_id").val();
	var OrderUniqueID = $("#OrderUniqueID").val();
	var book_type = $("#book_type").val();
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxaddOtherChargesEditPage.php',
		   data: 'reservation_date='+reservation_date+'&hotel_id='+hotel_id+'&rate_id='+rate_id+'&rate_assign_id='+rate_assign_id+'&room_id='+room_id+'&rate_plan_id='+rate_plan_id+'&type='+book_type+'&OrderUniqueID='+OrderUniqueID, 
		   success: function (result) {					
				resultArray = result.split('|||');					
					$('#showOtherCharges').append(resultArray['1']);
					$('#pricingValue').html(resultArray['2']);
					$('#addRoommsg').css('display', 'none');
					$('#flatDiscount').val();
					$('#percentDiscount').val();
					$('#flatAdditionalCharges').val();
					$('#percentAdditionalCharges').val();				
			}
		})

}

function ajaxRoomRemove(uniqueCode){	
var OrderUniqueID = $("#OrderUniqueID").val();
		$.ajax({
			   type: "GET",
			   url: 'ajax/ajaxUpdateSessionBookingEditPage.php',
			   data: 'remove=removeOne'+'&uniqueCode='+uniqueCode+'&OrderUniqueID='+OrderUniqueID, 
			   success: function (result) {	
			   resultArray = result.split('|||');			
				 $('#'+uniqueCode).remove();
				 	if(resultArray['1']=='removeroomLimitMsg'){
						$('#roomLimitMsg').css('display', 'none');	
					}
					if(resultArray['2']=='roomLimitMsgRoomType'){
						$('#roomLimitMsgRoomType').css('display', 'none');	
					}
					
					$('#pricingValue').html(resultArray['2']);
					$('#flatDiscount').val();
					$('#percentDiscount').val();
					$('#flatAdditionalCharges').val();
					$('#percentAdditionalCharges').val();
					
					
				}
		});
}

function ajaxOtherChargesRemove(uniqueCode){
	var OrderUniqueID = $("#OrderUniqueID").val();
		$.ajax({
			   type: "GET",
			   url: 'ajax/ajaxUpdateSessionOtherChargesEditPage.php',
			   data: 'remove=removeOne'+'&uniqueCode='+uniqueCode+'&OrderUniqueID='+OrderUniqueID, 
 success: function (result) {	
			   resultArray = result.split('|||');			
				 $('#'+uniqueCode).remove();
				 	if(resultArray['1']=='removeroomLimitMsg'){
						$('#roomLimitMsg').css('display', 'none');	
					}
					if(resultArray['2']=='roomLimitMsgRoomType'){
						$('#roomLimitMsgRoomType').css('display', 'none');	
					}
					$('#pricingValue').html(resultArray['0']);
					$('#flatDiscount').val();
					$('#percentDiscount').val();
					$('#flatAdditionalCharges').val();
					$('#percentAdditionalCharges').val();
					
					
				}
		});
}


function ajaxRoomRemoveAll(){	
	var rate_id = $("#rate_id").val();
	var OrderUniqueID = $("#OrderUniqueID").val();
	//
 $.ajax({
   type: "GET",
   url: 'ajax/ajaxUpdateSessionBookingEditPage.php',
   data: 'remove=removeAll'+'&rate_id='+rate_id+'&OrderUniqueID='+OrderUniqueID, 
   success: function (result) {	
   resultArray = result.split('###');
   if(resultArray[1]!=0){	   			
		$( ".ajaxAddRoom" ).remove();
		$('#addRoommsg').show();
		$('#subtotal').html('<i class="fa fa-inr"></i> 0');
		$('#discount').html('<i class="fa fa-inr"></i> 0');
		$('#addchargesvalue').html('<i class="fa fa-inr"></i> 0');
		$('#tax').html('<i class="fa fa-inr"></i> 0');
		$('#totalPrice').html('<i class="fa fa-inr"></i> 0');
		$('#amountReceived').html('<i class="fa fa-inr"></i> 0');
		$('#balance').html('<i class="fa fa-inr"></i> 0');
		$('#flatDiscount').val(0);
		$('#percentDiscount').val(0);
		$('#flatAdditionalCharges').val(0);
		$('#percentAdditionalCharges').val(0);
   }else{
	   //ajaxAdogaRoomRemoveAll();
		resultArray2 = result.split('|||');
		
		//resultArrayKey = resultArray2.split('%^&');
		$( ".ajaxAddRoom" ).remove();
		$('#addRoommsg').show();
		$('#subtotal').html('<i class="fa fa-inr"></i> 0');
		$('#discount').html('<i class="fa fa-inr"></i> 0');
		$('#addchargesvalue').html('<i class="fa fa-inr"></i> 0');
		$('#tax').html('<i class="fa fa-inr"></i> 0');
		$('#totalPrice').html('<i class="fa fa-inr"></i> 0');
		$('#amountReceived').html('<i class="fa fa-inr"></i> 0');
		$('#balance').html('<i class="fa fa-inr"></i> 0');
		$('#flatDiscount').val(0);
		$('#percentDiscount').val(0);
		$('#flatAdditionalCharges').val(0);
		$('#percentAdditionalCharges').val(0);
		
		var len = resultArray2.length;
		for (var i = 1; i < len; i++) {
			resultArrayKey = resultArray2[i].split('%^&');	
			resultArrayKeyRateId = resultArrayKey['1'].split('###');   
			$('#trafficprice_'+resultArrayKeyRateId['0']).html(resultArrayKey['0']);	
		
		}
		
		
				
				
				
	   }
		
	}
	})
	
}
function ajaxCheckAdogoRateletter(){	
	var rate_id 		= $("#rate_id").val();
	var OrderUniqueID 	= $("#OrderUniqueID").val();
	var eId 			= $('#eId').val();
	var book_type 		= $("#book_type").val();
	
 $.ajax({
   type: "GET",
   url: 'ajax/ajaxCheckAdogoRateletter.php',
   data: 'remove=removeAll'+'&rate_id='+rate_id+'&OrderUniqueID='+OrderUniqueID+'&eId='+eId+'&book_type='+book_type, 
   success: function (result) {	
    	
   resultArray = result.split('###');
   if(resultArray[1]!=0){	  
  		
		$( ".ajaxAddRoom" ).remove();
		$('#addRoommsg').show();
		$('#subtotal').html('<i class="fa fa-inr"></i> 0');
		$('#discount').html('<i class="fa fa-inr"></i> 0');
		$('#addchargesvalue').html('<i class="fa fa-inr"></i> 0');
		$('#tax').html('<i class="fa fa-inr"></i> 0');
		$('#totalPrice').html('<i class="fa fa-inr"></i> 0');
		$('#amountReceived').html('<i class="fa fa-inr"></i> 0');
		$('#balance').html('<i class="fa fa-inr"></i> 0');
		$('#flatDiscount').val(0);
		$('#percentDiscount').val(0);
		$('#flatAdditionalCharges').val(0);
		$('#percentAdditionalCharges').val(0);
   }else{   
	   
	   //ajaxAdogaRoomRemoveAll();
		resultArray2 = resultArray[0].split('|||');
		resultArray2 = resultArray2.filter(Boolean);	
		var len = resultArray2.length;		
		for (var i = 0; i < len; i++) {		
			resultArray3 = resultArray2[i].split('&&&&');		
			$('#trafficprice_'+resultArray3[0]).html(resultArray3[1]);	
		}
			
			
	   }
		
	}
	})
	
}

function ajaxAdogaRoomRemoveAll(rate_id,rate_assign_id,room_id,rate_plan_id,type){
	
	var reservation_date = $("#reservation_date").val();
	var rate_id = $("#rate_id").val();
	var hotel_id = $("#hotel_id").val();
	
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxAdogoUpdateSessionBookingEditPage.php',
		   data: 'reservation_date='+reservation_date+'&hotel_id='+hotel_id+'&rate_id='+rate_id+'&rate_assign_id='+rate_assign_id+'&room_id='+room_id+'&rate_plan_id='+rate_plan_id+'&type='+type, 
		   success: function (result) {					
				resultArray = result.split('|||');	
				
				$('#trafficprice_'+resultArray['2']).html(resultArray['1']);				
					/*$('#showOtherCharges').append(resultArray['1']);
					$('#pricingValue').html(resultArray['2']);
					$('#addRoommsg').css('display', 'none');
					$('#flatDiscount').val();
					$('#percentDiscount').val();
					$('#flatAdditionalCharges').val();
					$('#percentAdditionalCharges').val();*/				
			}
		})

}






$("#payment_date").datepicker({
	dateFormat : 'dd-mm-yy',
	minDate : new Date(),
	maxDate: new Date('<?php echo date('Y-m-d',strtotime($row->checkin) ); ?>')
});


//////////////////////////////save price popup form common//////////////////////////////////////////////////////////


function pricePopUp(id){
	var Id = id.split('_');
	var uniqueId= Id[1];
	$('#uniqueCode').val(uniqueId);
	
}
function savepricePopUpform(){
	var uniqueCode = $("#uniqueCode").val();
	var dataValue = $('#dataValue'+'\\|'+uniqueCode).val();		
	var form=$("#pricePopUpform");
	if(form.parsley().validate()){
	$('.loading').show(); 
	$.ajax({
	   type: "POST",
	   url: 'ajax/ajaxSavePriceEdit.php',
	   data: form.serialize()+'&dataValue='+dataValue, 
	   success: function (result) {
	    $('#pricePopUp').popup('hide');
		$("#pricePopUpform")[0].reset();
		 alert('Price has been updated.');
		resultArray = result.split('|||');	
						$('#roomLimitMsg').css('display', 'block');
						$('#roomLimitMsg').html(resultArray['0']);
						$('#roomLimitMsgRoomType').css('display', 'block');
						$('#roomLimitMsgRoomType').html(resultArray['1']);
					
					$('#price_'+uniqueCode).html(resultArray['2']);
					$('#pricingValue').html(resultArray['3']);	
					$('#flatDiscount').val();
					$('#percentDiscount').val();
					$('#flatAdditionalCharges').val();
					$('#percentAdditionalCharges').val();						
		},
	  complete: function(){
		$('.loading').hide();
	  }
	});
	return false;
	}
}




function MultiPlanSelect(uniqueCode){
	
 var form1=$("#availabiltyForm");	
 var form2=$("#addRoomForm");
 var dataString = $("#availabiltyForm, #addRoomForm").serialize();	
	
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxGetMultiPlanDetails.php',
		   data: dataString+'&uniqueCode='+uniqueCode, 
		   success: function (result) {					
				$( "#ajaxPlanData" ).html(result);
				$('#planDetail').popup({
        			 transition: 'all 0.3s',
           			 autoopen: true,            
        		});
				 //$("#hotelId").val('1').attr('selected','selected');					
			}
		})
	
}

 
function getRateEdit(uniqueCode){	

	var arrayCode = uniqueCode.split("|");
	var uniqueId = arrayCode['1'];
	
	
	var eId = $('#eId').val();
	var room_quantity = $('#room_quantity\\|'+uniqueId).val();
	var dataValue = $('#dataValue\\|'+uniqueId).val();
	var adult_no = $('#adult_no\\|'+uniqueId).val();
	var infant_no = $('#infant_no\\|'+uniqueId).val();
	var child_no = $('#child_no\\|'+uniqueId).val();
	var uniqueCode = $('#uniqueCode\\|'+uniqueId).val();
	var room_type_id = $('#room_type_id\\|'+uniqueId).val();
	var rate_plan_id = $('#rate_plan_id\\|'+uniqueId).val();
	var reservation_date = $("#reservation_date").val();
	var meal = $('#meal\\|'+uniqueId).val();
	var tarrif = $('#tarrif\\|'+uniqueId).val();
	var inclusive_tax = $('#inclusive_tax\\|'+uniqueId).val();
	var OrderUniqueID = $("#OrderUniqueID").val();


	  	
	 $.ajax({
			   type: "GET",
			   url: 'ajax/ajaxupdateRoomDetailsEditPage.php',
			   data: 'dataValue='+dataValue+'&room_quantity='+room_quantity+'&adult_no='+adult_no+'&child_no='+child_no+'&infant_no='+infant_no+'&uniqueCode='+uniqueCode+'&reservation_date='+reservation_date+'&rate_plan_id='+rate_plan_id+'&room_type_id='+room_type_id+'&tarrif='+tarrif+'&meal='+meal+'&inclusive_tax='+inclusive_tax+'&eId='+eId+'&OrderUniqueID='+OrderUniqueID, 
			   success: function (result) {
			   	resultArray = result.split('|||');						
						$('#roomLimitMsg').css('display', 'block');
						$('#roomLimitMsg').html(resultArray['0']);
						$('#roomLimitMsgRoomType').css('display', 'block');

						
						if(resultArray['1']  === undefined || resultArray['1'] == 0){
							//$('#trafficprice_'+uniqueId).html(resultArray['1']);
					$('#TaxPerdayPerroom_'+uniqueId).html(resultArray['2']);					
					$('#price_'+uniqueId).html(resultArray['3']);					
					$('#pricingValue').html(resultArray['4']);	
					$('#flatDiscount').val();
					$('#percentDiscount').val();
					$('#flatAdditionalCharges').val();
					$('#percentAdditionalCharges').val();
							
						}else{
							
						$('#trafficprice_'+uniqueId).html(resultArray['1']);
					$('#TaxPerdayPerroom_'+uniqueId).html(resultArray['2']);					
					$('#price_'+uniqueId).html(resultArray['3']);					
					$('#pricingValue').html(resultArray['4']);	
					$('#flatDiscount').val();
					$('#percentDiscount').val();
					$('#flatAdditionalCharges').val();
					$('#percentAdditionalCharges').val();	
							
							}
										
				   }
				})	
	
				
}

 function RemoveRateLetter(){		
	var hotel_id = $("#hotel_id").val();
	var reservation_date = $("#reservation_date").val();
	var id_company = $("#id_company").val();
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxRemoveRateLetter.php',
		   data: 'reservation_date='+reservation_date+'&id_company='+id_company+'&hotel_id='+hotel_id, 
		   success: function (result) {			  	    
				$( "#rate_id" ).html(result);
				//ajaxRoomRemoveAll();										
			}
		})
}


function getCompanyListRateAndUnit(){		

	
	var reservation_date = $("#reservation_date").val();

		$.ajax({

		   type: "POST",

		   url: 'ajax/ajaxGetCompanyListRateAndUnit.php',

		   data: 'reservation_date='+reservation_date, 

		   success: function (result) {			  	    

				$( "#id_company" ).html(result);
				$( "#rate_id" ).val('');
				$( "#rate_id" ).html('');
				//ajaxRoomRemoveAll();										

			}

		})

}
function getHotelListRateAndUnit(){		

	
	var reservation_date = $("#reservation_date").val();
	var id_company = $("#id_company").val();
	var  resultArray = id_company.split('####');		

		$.ajax({

		   type: "POST",

		   url: 'ajax/ajaxGetHotelListRateAndUnit.php',

	 data: 'reservation_date='+reservation_date+'&id_company='+resultArray[0]+'&rateType='+resultArray[1], 

		   success: function (result) {			  	    
$('#hotel_id').html(result);
				//$( "#hotel_id" ).html(result);

				//ajaxRoomRemoveAll();										

			}

		})

}
function getRateLetterForView(){		

	
	var reservation_date = $("#reservation_date").val();

	var id_company = $("#id_company").val();
	
var hotel_id = $("#hotel_id").val();
var  resultArray = hotel_id.split('####');		

		$.ajax({

		   type: "POST",

		   url: 'ajax/ajaxGetRateLetterForView.php',

		   data: 'reservation_date='+reservation_date+'&id_company='+id_company+'&hotel_id='+resultArray[0]+'&rateType='+resultArray[1], 

		   success: function (result) {			  	    

				$( "#rate_id" ).html(result);

				//ajaxRoomRemoveAll();										

			}

		})

}
 </script>
