<?php include_once("../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'add');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$hotel_id	=	$_REQUEST['id_hotel_md'];

$enquiryDate	=	$_REQUEST['enquiryDate'];
 $report_date	= stripslashes(date('d-m-Y',strtotime($enquiryDate))); 
				 	
// Calulating the difference in timestamps 
$date1	=date('d-m-Y');
    $diff = strtotime($row->dated) - strtotime($date1); 
      
    // 1 day = 24 hours 
    // 24 * 60 * 60 = 86400 seconds 
     $DateNoDays = abs(round($diff / 86400)); 
	 
if($_REQUEST['selectType']=='view'){
	
	
$incentiveSql = executeSql("SELECT * from `".TBL_INCENTIVE."` where   id = '".$_REQUEST['id_incentive']."' ");
$incentiveSqlRow = $db->fetch_assoc2($incentiveSql);

$id_enquiry=$incentiveSqlRow['id_enquiry'];
$id_user_created=$incentiveSqlRow['id_user'];
$id_hotel_created=$incentiveSqlRow['hotel_id'];
$revenue=	$incentiveSqlRow['revenue'];
$guest_name=	$incentiveSqlRow['guest_name'];
$checkin=	date('d-m-Y', strtotime($incentiveSqlRow['checkin']));
$checkout=	date('d-m-Y', strtotime($incentiveSqlRow['checkout']));
$no_room=	$incentiveSqlRow['no_room'];
$no_pax=	$incentiveSqlRow['no_pax'];
$banquet_revenue_amount=	$incentiveSqlRow['banquet_revenue_amount'];
$room_rate=	$incentiveSqlRow['room_rate'];
$remarks_inc=	$incentiveSqlRow['follow_up_close_summary'];

$id_currently_with =$incentiveSqlRow['id_currently_with'];
//$incentiveDetailsSql = executeSql("SELECT * from `".TBL_INCENTIVE_DETAILS."` where  `id_incentive` = '".$_REQUEST['id_incentive']."' ORDER BY id desc ");
	$statusid	=selectColumn(TBL_INCENTIVE_DETAILS,'max(id)'," WHERE `id_incentive` = '".$_REQUEST['id_incentive']."'");
$incentiveDetailsSql = executeSql("SELECT * from `".TBL_INCENTIVE_DETAILS."` where  id=  '".$statusid."' AND `id_incentive` = '".$_REQUEST['id_incentive']."'  ");
$incentiveDetailsRow = $db->fetch_assoc2($incentiveDetailsSql);
 $incentiveDetailstatus=	$incentiveDetailsRow['status'];
	$status=	$incentiveDetailsRow['status'];
if($status==1){
		$status='Verified By GM - S & M';
	}elseif($status==2){
		$status='Not Approved';		
	}elseif($status==3){
		$status='Verified By Hotel';		
	}else{
		$status='Pending For Approval';
		}
$query_type='edit';
if($id_user_created==$_SESSION['userId'] && $incentiveDetailstatus==0 && $id_currently_with==$_SESSION['userId']){
	
	$readonly	='';
	}else{
	$readonly	='readonly="readonly"';	
	}
}else{
	$query_type='add';
	$id_enquiry=$_REQUEST['edit_id_enquiry'];
	}
	
	 $created_by	= selectColumn(TBL_DAILY_ENQUERY,'created_by'," WHERE `id` = '".$id_enquiry."'");
	
	
		 $hotel_access	=selectColumn(TBL_USERS,'hotel_access'," WHERE `id` = '".$created_by."'");
	
	 ?>
            <!--------------------------------Claim Incentive Start-------------------------------------->
        <?php if($_REQUEST['selectType']!='1'){?>  
        <form id="saveIncentiveExistingLeadform" method="post"  class="saveIncentiveExistingLeadform" data-parsley-validate autocomplete="off">
        
          <input type="hidden" name="enquiry_id" id="enquiry_id" value="<?php echo $id_enquiry;?>">
          
         
          <input type="hidden" name="followup_hidden_type" id="followup_hidden_type" value="4">
         <?php } ?> 
          <input type="hidden" name="query_type" id="query_type" value="<?php echo $query_type;?>">
            <div  style="max-width:40em;">
            
            <div class="box-header with-border">
              <h3 class="box-title">Sales Lead Award</h3>

              <div class="box-tools pull-right">
               <?php if($_REQUEST['selectType']!='1'){?> 
                <button type="button" class="viewincPopUp_close btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
               <?php } ?> </div>
            </div>
            
            
            <div class="form-group col-sm-12" style="background-color:#3C8DBC; color:#fff;"> 
 
 </div>
              <div class="form-group col-sm-6">
                <label for="pkg_title" style="float:left;">Hotel Name</label>
                <?php 
                     	if($hotel_id==0){
			           			$selected="selected='selected'";
			           		}
			           		else{
			           			$selected='';
			           		} 
                       $hotelDropDown = '<select class="form-control input-sm select2" '.$readonly.' data-parsley-errors-container="#id_hotel_incError" data-parsley-required name="id_hotel_inc" id="id_hotel_inc">

                                            

                                            ';

                                           $resCat = selectSql(TBL_HOTELS," where id_shop='".addslashes($_SESSION['shop'])."' AND status=1 and  id='".$hotel_id."'",' ORDER BY `name`');

                                     if($db->num_rows2($resCat)){

                                       while($resultCat = $db->fetch_object2($resCat)){

                                       if($hotel_id!="" && trim($hotel_id) == $resultCat->id){

                                         $selected = 'selected="selected"';

                                      }else{

                                         $selected = '';

                                       }

                                       $hotelDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'-'.ucfirst($resultCat->city).'</option>';

                                    }

                                     }

                                     echo $hotelDropDown .= '</select>';

                      ?>
                <span id="id_hotel_mdError"></span></div>
                
                
                
                <div class="form-group col-sm-6">
                <label for="pkg_title" style="float:left;">Source Hotel Name</label>
                <?php 
                     	if($hotel_id==0){
			           			//$selected="selected='selected'";
			           		}
			           		else{
			           			//$selected='';
			           		} 
                       $hotelDropDown = '<select class="form-control input-sm select2" '.$readonly.' data-parsley-errors-container="#id_hotel_incError" data-parsley-required name="id_hotel_inc" id="id_hotel_inc">

                                            

                                            ';

                                           $resCat = selectSql(TBL_HOTELS," where id_shop='".addslashes($_SESSION['shop'])."' AND status=1 and  id='".$hotel_access."'",' ORDER BY `name`');

                                     if($db->num_rows2($resCat)){

                                       while($resultCat = $db->fetch_object2($resCat)){

                                       if($hotel_access!="" && trim($hotel_access) == $resultCat->id){

                                         $selected = 'selected="selected"';

                                      }else{

                                         $selected = '';

                                       }

                                       $hotelDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'-'.ucfirst($resultCat->city).'</option>';

                                    }

                                     }

                                     echo $hotelDropDown .= '</select>';

                      ?>
                <span id="id_hotel_inc_sourceError"></span></div>
                
                <div class="form-group col-sm-6">
                <label for="pkg_title" style="float:left;">Guest Name</label>
                <input type="text" class="form-control input-sm" <?php echo $readonly; ?>  placeholder="Enter Guest Name" id="guest_name_inc" name="guest_name_inc" value="<?php echo $guest_name;?>"  >
              </div>
              <div class="form-group col-sm-6">
                <label for="pkg_min_nights" style="float:left;">No of Rooms</label>
                <input type="text" class="form-control input-sm" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  <?php echo $readonly; ?> placeholder="Enter No Of Rooms" id="no_room_inc" name="no_room_inc" value="<?php echo $no_room;?>"  >
              </div>
              
              <div class="form-group col-sm-6">
                <label for="checkout" style="float:left;">Check In</label>
                <input type="text" class="form-control datepickercheckin"  <?php echo $readonly; ?> placeholder="Enter checkin Date" id="checkin_inc" name="checkin_inc" value="<?php echo $checkin;?>"  >
              </div>
              <div class="form-group col-sm-6">
                <label for="checkin" style="float:left;">Check Out</label>
                <input type="text" class="form-control datepickercheckout"  <?php echo $readonly; ?> placeholder="Enter checkout Date" id="checkout_inc" name="checkout_inc" value="<?php echo $checkout;?>"  >
              </div>
              
              <div class="form-group col-sm-6" style="float:left;">
                <label for="pkg_min_nights" style="float:left;">Rate</label>
                <input type="text" class="form-control input-sm"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" <?php echo $readonly; ?> placeholder="Enter No Of Pax" id="no_pax_inc" name="no_pax_inc" value="<?php echo $no_pax;?>"  />
              </div>
              <div class="form-group col-sm-6" style="float:right;">
                <label for="rack_rate" style="float:left;">Total Room Revenue</label>
                <input type="text" data-parsley-type="digits" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" onKeyUp="calculateTotalRevenue(this.value);" class="form-control input-sm"   <?php echo $readonly; ?> placeholder="Enter Room Rate" id="room_rate_inc" name="room_rate_inc" value="<?php echo $room_rate;?>"  >
              </div>
              <div class="form-group col-sm-6" style="float:left;">
                <label for="pkg_discount" style="float:left;">Total Banquet Revenue</label>
                <input type="text"   class="form-control input-sm" readonly="readonly" id="banquet_revenue_amount_inc" name="banquet_revenue_amount_inc" value="0"  automcomplete="off"  >
              </div>
              <div class="form-group col-sm-6">
                <label for="pkg_extra_price" style="float:left;">Total Revenue</label>
                <input type="text" data-parsley-type="digits"  class="form-control input-sm" readonly="readonly"   placeholder="Enter revenue" id="revenue_inc" name="revenue_inc" value="<?php echo $revenue;?>" >
              </div>
              <?php if($_REQUEST['selectType']=='view'){?>
              
               <div class="form-group col-sm-6" style="float:right;">
                <label for="pkg_extra_price" style="float:left;">Current Status</label>
                <input type="text" class="form-control input-sm"  <?php echo $readonly; ?> placeholder="Enter status" id="status" name="status" value="<?php echo $status;?>" >
              </div>
              <div class="form-group col-sm-6">
                <label for="pkg_extra_price" style="float:left;">Revenue Approved</label>
                <input type="text" class="form-control input-sm"  readonly="readonly" placeholder="Enter status" id="status12" name="status12" value="<?php echo $incentiveSqlRow['approved_amount'];?>" >
              </div>
              
              
              
              <?php } ?>
              <?php 
			 
			  if($query_type!='edit'){
			  
			  
			  
			  ?>
              <div class="form-group col-sm-6">
                <label for="pkg_extra_price" style="float:left;">Forward for Approval</label>
                <?php   
               
                     $salesHead=array();
                      $teamSql = "SELECT id_user_level_1 FROM ".TBL_TEAM." WHERE id_shop='".$_SESSION['shop']."'  ";
                     $teamRes= mysqli_query($connNew,$teamSql);

                     while($rowTeam=mysqli_fetch_object($teamRes)){
                       array_push($salesHead,$rowTeam->id_user_level_1);
                     }
                 

              



                 $availableData2 .= '<select class="form-control select2"  '.$readonly.' name="id_forward_for_approval" id="id_forward_for_approval">
					<option value="">Select Assign UserName</option>';

				  $resUserLevel = selectSql(TBL_USERS," WHERE `status` = '1' AND  `id_shop` = '".addslashes($_SESSION['shop'])."' AND (user_type=1 OR user_type=0) ",' ORDER BY `name`');

											  if($db->num_rows2($resUserLevel)){

											  	while($resultUserLevel = $db->fetch_object2($resUserLevel)){

													if($_SESSION['userId'] == $resultUserLevel->id){

														$selected = 'selected="selected"';

													}else{

														$selected = '';


													}
												if( in_array($resultUserLevel->id,$salesHead) ){		
												$availableData2 .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'-'.userTeamName($resultUserLevel->ids_team).'</option>';
												}
													

												}

											  }

											 	echo $availableData2 .= '</select>';

                                              

                                              	

												 

												 ?>
              </div>
              <?php }else{?>
				  <input type="hidden" name="id_forward_for_approval" id="id_forward_for_approval" value="0" />
				  
				  <?php } ?>
              <div class="form-group col-sm-12"> <label for="pkg_extra_price" style="float:left;">Sales Lead Award Internal Remarks
               <div style="float:right;color:#888;margin-left:150px;"> Words left: <span id="word_left2">8</span></div></label>
              <textarea   name="remarks_inc" <?php echo $readonly; ?> id="remarks_inc" class="form-control" placeholder="Sales Lead Award Internal Remarks"  data-parsley-required automcomplete="off"><?php echo $remarks_inc;?></textarea>
            </div>
            
             <?php if($incentiveDetailsRow['remarks']!='' && $_REQUEST['selectType']=='view'){?>
            <div class="form-group col-sm-12">  <label for="pkg_extra_price" style="float:left;">Last Sales Lead Award Internal Remarks</label>
              <textarea   name="remarks_incee" <?php echo $readonly; ?> id="remarks_incee" class="form-control" placeholder="Sales Lead Award Remarks"  data-parsley-required automcomplete="off"><?php echo $incentiveDetailsRow['remarks'];?></textarea>
            </div>
            <?php } ?>
            
            </div>
            
            <?php if($_REQUEST['selectType']!='1'){?>
            <div class="form-group col-sm-12" style="float:left;">
            
            <?php if($query_type=='edit'){
				
				if($id_user_created==$_SESSION['userId'] && $incentiveDetailstatus==0 && $id_currently_with==$_SESSION['userId']){ ?>
             <button class="btn btn-primary" onclick="saveIncentiveExistingLeadform();" type="button">Edit</button>
              <?php } ?>
              
              
              &nbsp;<button class="viewincPopUp_close btn btn-default">Close</button>
            <?php } ?>
              
			 <?php 
			
			if($_REQUEST['selectType']=='Add'){?>
				
				`
				
              <button class="btn btn-primary" onclick="saveIncentiveExistingLeadform();" type="button">Save</button>
              &nbsp;
              <button class="viewincPopUp_close btn btn-default">Close</button>
				
				
				
				
			<?php }
			//if($_REQUEST['selectType']=='view'){ ?> 
            
           

            </div></form>
                        <?php  } ?>
            <!--------------------------------Claim Incentive Start--------------------------------------> 
 <script>

  $( function() {	 

    
  $( ".datepickercheckin").datepicker({ dateFormat: 'dd-mm-yy', minDate: '-<?php echo $DateNoDays; ?>d' });
 $( ".datepickercheckout").datepicker({ dateFormat: 'dd-mm-yy',minDate: '-<?php echo $DateNoDays; ?>d'});
 
 
  } );

  

$(document).ready(function() {
  $("#remarks_inc").on('keyup', function() {
    var words = 0;

    if ((this.value.match(/\S+/g)) != null) {
      words = this.value.match(/\S+/g).length;
    }

    if (words > 8) {
      // Split the string on first 200 words and rejoin on spaces
      var trimmed = $(this).val().split(/\s+/, 8).join(" ");
      // Add a space at the end to make sure more typing creates new words
      $(this).val(trimmed + " ");
    }
    else {
      $('#display_count2').text(words);
      $('#word_left2').text(8-words);
    }
  });
}); 
</script>            
           <?php /*?> <br/>
            <div class="form-group col-sm-12" style="float:left;">
              <button class="btn btn-primary" onclick="saveColseSummaryPopUpform();" type="button">Save</button>
              &nbsp;
              <button class="ColseSummaryPopUp_close btn btn-default">Close</button>
            </div>
          </div>
        </form>
      </div><?php */?>