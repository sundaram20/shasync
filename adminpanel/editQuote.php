<?php 

include_once("../config/auto_loader.php");

checkUserLevelPermission($_SESSION['userLevel'],TBL_SALES_QUOTE,'view');


unset($_SESSION['followup_hotel_id']); 
unset($_SESSION['followup_description']); 
unset($_SESSION['followup_date']); 
unset($_SESSION['followupCode']); 
unset($_SESSION['followupstatus']); 
unset($_SESSION['assign_user_id']); 
unset($_SESSION['assign_followup_user_id']); 
unset($_SESSION['feedback_hotel_id']); 
unset($_SESSION['feedback_description']); 
unset($_SESSION['feedback_date']); 





if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
	$sql = "  SELECT * FROM `".TBL_SALES_QUOTE."`
								WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";

	$db->query($sql);
	if($db->num_rows() > 0){
		$row = $db->fetch_object();
	}

  if($row->conveyance_approved==1 || $row->id_user!=$_SESSION['userId']){
    $readonly="readonly='readonly'";
    $disabledEdit = "disabled='disabled'";
  }
  else{
    $readonly='';
    $disabledEdit='';
  }

}	

							





include_once("includes/header.php");
include_once("includes/left.php");

?>



<div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1>

        Quotation 

      </h1>

      <ol class="breadcrumb">

        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>

        <li class="active">Quote</li>

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
          	  <h3 class="box-title" style="color: green;">Recently Added Enquiry :</h3>
          	  <table  class="table table-striped" id="recentAddData">
          	    <tr style="background-color:#c2c2c2;color: #000; ">
          	      <td><b>S.No.</b></td>
          	      <td><b>Company Name</b></td>
          	      <td><b>Hotel Name</b></td>
          	    </tr>
          	  </table>
          	</div>

            <div class="box-header with-border">


              <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> Quote</h3>

            </div>

            <!-- /.box-header -->

            <!-- form start jump-->  			        

			 <form name="form1" id="form1" method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off">

			 	<input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" />

					<div class="form-group has-error">

						<?php if($_SESSION['errorMsg']){?>

						 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>

						<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>

					 	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>

						<?php unset($_SESSION['successMsg']);}?>

					 </div>

              <div class="box-body">

                

			<div class="row">	 

              <div class="form-group col-md-2" style=" position: relative;margin-left:35px;">

              <?php 

			  if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){$report_date	= stripslashes(date('d-m-Y',strtotime($row->dated))); }else{  $report_date	=	date('d-m-Y');}	

			  ?>

                  <label for="start_date">Date:</label>

                  <input type="text" class="form-control pickerdate_addreport" placeholder="Enter Enquiry date" id="enquiryDate" name="enquiryDate" value="<?php echo  $report_date; ?>"  data-parsley-required>

				<?php echo $err_start_date;?>

              </div>

                         <!--Hotel select list-->

              <?php 
                   $comSQL = "SELECT  ".TBL_COMPANY.".* FROM ".TBL_COMPANY."  WHERE ".TBL_COMPANY.".status=1 and ".TBL_COMPANY.".`id_shop` = '".addslashes($_SESSION['shop'])."' and ".TBL_COMPANY.".name !='' ORDER BY name";
                   ?>
                  
                       <div class="row">
                      <div class="form-group col-sm-3">
                           <label for="id_company">Company Name - City<font style="color:red;">*</font></label>
                           <select class="form-control" name="id_company" id="id_company" onChange="getExecutiveName(this.value,'');  getRateLetter(this.value,'');   "  data-parsley-required data-parsley-errors-container="#companyError" >
                            <option value="">Select Company</option>
                            
                            <?php 
                            


                            $resCat = executeSql($comSQL);

                        if($db->num_rows2($resCat)){
                          while($resultCat = $db->fetch_object2($resCat)){
                          if($row->id_company == $resultCat->id_company){
                            $selected = 'selected="selected"';
                          }else{
                            $selected = '';
                          }
                          $companyData .= '<option '.$selected.' value="'.$resultCat->id_company.'">'.ucfirst($resultCat->name).'-'.ucfirst($resultCat->city).'</option>';
                        }
                        }
                       echo $companyData;
                        ?>
                          </select>
                           <span id="companyError"></span> </div>

                          <div class="form-group col-sm-3">

                            <label for="id_contacts" >Contact Person</label>

                            <div class="input-group" id="showbookedby">

                            <select class="form-control select2" name="id_contacts" id="id_contacts"  data-parsley-errors-container="#contactError" data-parsley-required>

                              <option value="">Select Contact Person </option>

                            </select>

                            <span id="contactError"></span> 

                            <div class="input-group-addon bookedby_open"> <i class="fa fa-plus"></i> </div>

                            </div></div>
 
	
      
			            <input type="hidden" value="<?php echo $OrderUniqueID;?>" name="OrderUniqueID" id="OrderUniqueID" />
                        <div class="form-group col-sm-3">
                           <label for="hotel_id" >Hotel<font style="color:red;">*</font></label>
                           <?php 
				$categoryDropDown = '<select data-parsley-required name="hotel_id" id="hotel_id" class="form-control select2" data-parsley-required data-parsley-errors-container="#hotelError"  onChange="getRoom(this.value,0); ajaxAddRoommsgUpdate(); getRateLetter();" '.$disabled.'>
									 					  <option value="">Select Hotel</option>';
	  $resCat = selectSql(TBL_HOTELS," where status='1' AND id_shop='".addslashes($_SESSION['shop'])."'".$_SESSION['HotelPerHotel']." ",' ORDER BY `name`');
											  if(num_rows($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($row->hotel_id == $resultCat->id){
													   $selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'-'.ucfirst($resultCat->city).'</option>';
												}
											  }
											 	echo $categoryDropDown .= '</select>';
									
									 ?>
                           <?php if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){?>
                           <input type="hidden" name="hotel_id" id="hotel_id" value="<?php  echo $row->hotel_id; ?>">
                         
                           <?PHP } ?>
                           <span id="hotelError"></span> </div>

			    <div class="row">      
			    <div class="form-group col-sm-3" style=" position: relative;margin-left:60px;">
                       <label for="reservation_date">Checkin Date - Checkout Date </label>
                           <div class="input-group">
                            <?php 
					if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
					
					?>
                             <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                               <input  type="text" class="form-control pull-right dateRangeEdit" id="reservation_date" name="reservation_date" data-parsley-required value="<?php if($row->checkin !='' ){echo date('d-m-Y',strtotime($row->checkin)).' to '.date('d-m-Y',strtotime($row->checkout));} ?>" data-parsley-errors-container="#reservation_dateError"  autocomplete="off">
                               
                               
                               
                            <?php }else{ ?>
                            
                            <?php if($_SESSION['userLevel']=='1'){		?>
                           <div class="input-group">
                               <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                               <input  type="text" class="form-control pull-right dateRangeEdit" id="reservation_date" name="reservation_date" data-parsley-required value="<?php echo date('d-m-Y').' to '.date('d-m-Y') ?>" data-parsley-errors-container="#reservation_dateError"  autocomplete="off">
                             </div>
                             
                              <?PHP }else{ ?>
                            <div class="input-group">
                               <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                               <input  type="text" class="form-control pull-right dateRangeEdit" id="reservation_date" name="reservation_date" data-parsley-required value="<?php echo date('d-m-Y').' to '.date('d-m-Y') ?>" data-parsley-errors-container="#reservation_dateError"  autocomplete="off">
                             </div>
                              <?PHP } ?>
                            <?php } ?>
                          </div>
                           <!-- /.input group --> 
                           <span id="reservation_dateError"></span> </div>            
                	
                    <div class="form-group col-sm-3 " >
                    	<label >Rate Letters<font style="color: red;">*</font></label>
  <select class="form-control" name="rate_id" id="rate_id" onChange="ajaxCheckAdogoRateletter();  showRateLetterView(this);" data-parsley-required data-parsley-errors-container="#rate_idError">
                        <?php 
						//`fs_rate_details`.hotel_id='".addslashes($row->id_hotel)."' AND;
			  $rate_level_assgin = selectColumn(TBL_COMPANY,'id_rate_level'," WHERE `id_company` = '".addslashes($row->id_company)."'");
			  
$resCat = executeSql("SELECT `".TBL_RATE."`.*, `".TBL_RATE_LEVEL."`.name as level_name ,`".TBL_RATE_MARKET."`.name as market_name from `".TBL_RATE."` LEFT JOIN `".TBL_RATE_LEVEL."` ON `".TBL_RATE."`.rate_level_id=`".TBL_RATE_LEVEL."`.id   LEFT JOIN `".TBL_RATE_MARKET."` ON `".TBL_RATE."`.market=`".TBL_RATE_MARKET."`.id  LEFT JOIN `fs_rate_details` ON `fs_rate`.id=`fs_rate_details`.rate_id  where   `fs_rate_details`.hotel_id='".addslashes($row->hotel_id)."' AND  `".TBL_RATE."`.id_shop='".addslashes($_SESSION['shop'])."' and (`".TBL_RATE."`.company_id='".$row->id_company."' || `".TBL_RATE."`.company_id='0' ) and (( `".TBL_RATE."`.start_date <=  '".date('Y-m-d',strtotime($row->checkin))."' and  `".TBL_RATE."`.end_date >= '".date('Y-m-d',strtotime($row->checkout))."') OR (  `".TBL_RATE."`.start_date between '".date('Y-m-d',strtotime($row->checkin))."' and '".date('Y-m-d',strtotime($row->checkout))."') OR (  `".TBL_RATE."`.end_date between '".date('Y-m-d',strtotime($row->checkin))."' and '".date('Y-m-d',strtotime($row->checkout))."')) group by `".TBL_RATE."`.rate_name" );
							  if($db->num_rows2($resCat)==0 and $row->id_rate!=''){
									  $planData .= '<option '.$selected.' value="0">ADHOC</option>';
									  }
							  
							  
							  if($db->num_rows2($resCat)){
								  
								  
                               $planData .= '<option '.$selected.' value="0">ADHOC</option>';
                            	
							
							
							
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
							  $planData .= '<option value="" >Rate Letter</option>
                              <option value="0" >ADHOC</option>';
							  
							  }
							 echo $planData; ?>
                      </select>
                       <span id="rate_idError"></span> 

                       

                   </div>  
                   <div class="box-tools col-md-2">
                   		<label>View Rates</label>
                       <div id="view"  <?php if($row->id_rate=='0' ){echo 'style="display:none;"';} ?> >
                          <button class="btn btn-danger" type="button" id="view" > <i class="fa fa-eye fa-lg"></i> View</button>
                          </div>
                          <div id="adhol" <?php if($row->id_rate !='0'){echo 'style="display:none;"';} ?>>
                          <button class="pull-left btn btn-success btn-xs" id="adhoc" type="button" onclick="ajaxAddRoom('.$row->id.','.$row->rate_assign_id.','.$rowRoom->room_id.','.$rowRoom->rate_plan_id.',0);" style="width: 50px;height: 36px;margin-right: 8px"><i class="fa fa-plus-circle" > Add </i></button></div>
                        </div> </div>  
                        </div>      

<div class="col-md-12">
              <!--<div class="form-group col-md-2" style=" position: relative;margin-left:45px;">

                  <label for="title">Title<font style="color: red;">*</font></label>

                  <select name="title" id="en_title" class="form-control input-sm" data-parsley-required >

<?php  if($row->title=='Dr'){ echo 'selected="selected"';} ?>

                     <option value="">-Select Title-</option>



                     <option <?php  if($row->title=='Dr.'){ echo 'selected="selected"';} ?> value="Dr.">Dr.</option>



                     <option <?php  if($row->title=='Miss.'){ echo 'selected="selected"';} ?> value="Miss.">Miss.</option>



                     <option <?php  if($row->title=='Mr.'){ echo 'selected="selected"';} ?> value="Mr.">Mr.</option>



                     <option <?php  if($row->title=='Mrs.'){ echo 'selected="selected"';} ?> value="Mrs.">Mrs.</option>



                     <option <?php  if($row->title=='Ms.'){ echo 'selected="selected"';} ?> value="Ms.">Ms.</option>



                     <option <?php  if($row->title=='Pr.'){ echo 'selected="selected"';} ?> value="Pr.">Pr.</option>



                     <option <?php  if($row->title=='Prof.'){ echo 'selected="selected"';} ?> value="Prof.">Prof.</option>



                     <option <?php  if($row->title=='Rev.'){ echo 'selected="selected"';} ?> value="Rev.">Rev.</option>



                   </select>

				

              </div>

            

			         <div class="form-group col-md-4">



			          <label for="first_name">First Name<font style="color: red;">*</font> </label>



			          <input type="text" class="form-control input-sm" placeholder="Enter first name" id="en_first_name" name="first_name" value="<?php echo $row->first_name; ?>" data-parsley-required>



			        </div>



			         <div class="form-group col-md-5">



			          <label for="last_name">Last Name</label>



			          <input type="text" class="form-control input-sm" placeholder="Enter last name" id="en_last_name" name="last_name" value="<?php echo $row->last_name; ?>">



			        </div>

			</div>        

			</div>         

			<div class="row">
					<div style=" position: relative;margin-left:30px;" class="form-group col-md-2">
			          <label for="mobile" >Mobile No.<font style="color: red;">*</font></label>
			          <input type="phone" name="mobile" id="en_mobile" class="form-control" placeholder="Enter mobile number"  data-parsley-type="digits" data-parsley-length="[10, 10]" automcomplete="off" value="<?php echo $row->mobile; ?>">
			        </div>

   		         	<div class="form-group col-md-3">
			          <label for="email" >Email Id<font style="color: red;">*</font></label>
			          <input type="email" name="email" id="en_email" class="form-control" placeholder="Enter Email Id" data-parsley-type="email" automcomplete="off" value="<?php echo $row->email; ?>">
			        </div>-->

			        <div class="form-group col-md-12">
			          <label for="email" >Special Offers</label>
			          <textarea class="form-control" name="discussion_summary" id="en_discussion_summary" data-parsley-required   rows="2" placeholder="Enter Discussion Summary" automcomplete="off"><?php if($_POST) echo $_POST['details'];else echo stripslashes($row->details);?></textarea>
		        	</div>	
			</div>
	     </div>	        
  

				

				<div class="row">
					<div class="col-sm-3">
					  <div class="form-group">
					    <label for="image" style="float:left;"> Assigned To &nbsp;&nbsp; </label>
					    <a <?php echo $disabledEdit?> class="pull-left btn btn-success btn-xs" onclick="addEqyFollowUp(0);"  type="button" id="enqFollowUp" >Assign  </a>
					  </div>



					  <?php echo $err_image;?> </div>
				</div>



               <div class="box"> 
	                <?php if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
                
                $max_id=selectColumn(TBL_SALES_QUOTE_FOLLOWUP,'MAX(id)',"WHERE id_quote = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."' ") ;
						
            $FollowupSql = executeSql("SELECT * from `".TBL_SALES_QUOTE_FOLLOWUP."` where   id_quote = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."' ");
				if(num_rows($FollowupSql) > 0){

				?>
			        <div id="showFolowup">
						<div class="box-body table-responsive">
				            <table id="example2" class="table table-bordered table-striped">

			                <thead>
	 		                    <tr>
				                    <!--<th>Added On</th>-->		
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

						$_SESSION['followupstatus'][$OtherChargesuniqueCode]		=	$FollowupSqlRow['lead_status'];


				if($FollowupSqlRow['lead_status'] == 1){
					$StatusEs	=	'btn-success';
					$ActiveINactive	=	"Open";
				}
				if($FollowupSqlRow['lead_status'] == 0){
					$StatusEs	=	   'btn-danger';
					$ActiveINactive	=	"Close";
					$NextFollowUpDisable	= "disabled";  
				}

				$DateVisitList	='<tr>';
 			    //$DateVisitList	.='<td>'.$FollowupSqlRow['created_date'].'</td>';
  			    $DateVisitList	.='<td>'.$FollowupSqlRow['details'].'</td>';
				$DateVisitList	.='<td>'.date('d M Y',strtotime($FollowupSqlRow['dated'])).'</td>';

        if($max_id==$FollowupSqlRow['id']){
				$DateVisitList .= '<td id="ChangeButton_'.$FollowupSqlRow['id'].'"><button data="'.$FollowupSqlRow['id'].'" class="btn '.$StatusEs.'" type="button" onclick="OpenPopup('.$FollowupSqlRow['lead_status'].','.$FollowupSqlRow['id'].','.$FollowupSqlRow['id_quote'].','.$FollowupSqlRow['lead_status'].',5);"    >'.$ActiveINactive.'</button>
     		 </td>';
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
           <!---Follow ups--End---------------------------------------------------->

				

					<!--<table class="table" id="followTable">

						<tr>

							<th>Added On</th>

							<th>Follow Up Summary</th>

							<th>Follow Up Date</th>

							<th>Status</th>

							<th>Action</th>

						</tr>

					</table>-->

				



				

								

				<div class="form-group">

                  <label for="status">Status</label>
                    <?php
                      if($row->status==0 && $row->id_mst_user_modified_by!=""){
                        $inactive="checked='checked'";
                      }
                      else{
                        $active="checked='checked'";
                      }
                    ?>
          <input type="radio" <?php echo $active; ?> class="flat-red"  value="1" name="status"/> Active

				 <input type="radio" <?php echo $inactive; ?> class="flat-red"  value="0" name="status"/> Inactive

				 <?php echo $err_status;?>

                </div>

				    <?php

                  if($row->id_mst_user_modified_by !=""){?>
                    <div class="col-sm-12">
                    <div class="form-group col-sm-4  descriptionBox" >
                       <label for="descripton">Created By : </label>
                       <input class="form-control" disabled="disabled" type="text" value="<?=selectColumn(TBL_USERS,'name','WHERE id="'.$row->id_mst_user_created_by.'" ') ?>" />
                      </div> 
                      <div class="form-group col-sm-4 descriptionBox">
                       <label for="descripton">Modified By : </label>
                       <input class="form-control" disabled="disabled" type="text" value="<?=selectColumn(TBL_USERS,'name','WHERE id="'.$row->id_mst_user_modified_by.'" ') ?>" />
                      </div> 
                      <div class="form-group col-sm-4 descriptionBox">
                       <label for="descripton">Modified Date : </label>
                       <input class="form-control" disabled="disabled" type="text" value="<?=$row->date_modified?>" />
                      </div> 
                    </div>  
                  <?php }  ?>

				        

              </div>

              <!-- /.box-body -->	

			 <div class="box-footer">   

			 	<input type='hidden' id="Save" value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save">                                    

				<input type='button' <?php echo $disabledEdit?> value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" onClick="SaveSalesReport();">

				&nbsp;&nbsp;&nbsp;&nbsp;

			   <input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("manageQuote.php"); '>

			 </div>

            </form>			

          </div>

         



      <div id="OpenListPopUpshow" class="well" style="display:none;"> </div>  
      <input type="hidden" id="uniqueCode" name="uniqueCode" >


       <!--########## Folloup Popup#######-->  
       <span class="my_popup_open" style="display:none;"></span>

       <div id="my_popup" class="well">

         <div id="FollowUpNextUpdate"></div>
         <!--<button id="my_popup_yes" class="my_popup_close btn btn-default pull-left">Yes</button>
         <button id="my_popup_no" style="margin-left: 5px;" class="my_popup_close btn btn-default pull-left">No</button>-->

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

 <div id="planDetail" class="well" style="display:none; min-width:55em;"> <a href="#" class="planDetail_close" style="float:right;padding:0 0.4em;"><i class="fa fa-times text-red"></i></a>
  <div id="ajaxPlanData"></div>
</div>

<div id="ColseSummaryPopUp" class="well" style="display:none;">

    <div id="" class="ajaxAddRoom">

        <div class="btn btn-default tablenew1 tablenewmobile1">

        <div class="col-md-9">

            <div class="form-group" style="text-align:left;">
         

            <label>Follow Up Status </label>

            <br>

            <input type="radio" name="HotelDuplicateInsert"  id="HotelDuplicateInsert" onClick="SelectHotelsList123(1);"   value="1"  checked="checked" />

            1) Action Required

            <input type="radio" name="HotelDuplicateInsert" id="HotelDuplicateInsert" onClick="SelectHotelsList123(2);" value="2" />

            2) Close</div>

          </div>

        <div id="cars1" class="desc">

            <form name="nextFollowup" id="nextFollowup"  method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off">

            

<input type="hidden" name="followup_id" id="followup_id" value="">

<input type="hidden" name="daily_Visit_id" id="daily_Visit_id" value="">

<input type="hidden" name="hotel_id_hidden" id="hotel_id" value="">

<input type="hidden" name="followup_status" id="followup" value="">

<input type="hidden" name="followup_type" id="followup_type" value="5">

<div class="form-group">

                

              <label style="float:left;">Follow Up Summary</label>

                                                   

                <textarea   name="followup_description" id="followup_description"  class="form-control" placeholder="Follow Up Summary"  data-parsley-required automcomplete="off"></textarea>

                

                

              </div>

              <div class="form-group">

                <input type="text" class="form-control datepickertest" placeholder="Enter date" id="followup_date" name="followup_date" value="<?php echo date('d-m-Y');?>"  data-parsley-required>

              </div>

           <?php   $availableData .='<div class="form-group"><label style="float:left;">Assign To</label>';

                 $salesHead=array();

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

				  $resUserLevel = selectSql(TBL_USERS," WHERE `status` = '1' AND  `id_shop` = '".addslashes($_SESSION['shop'])."'  ",' ORDER BY `name`');

											  if($db->num_rows2($resUserLevel)){

											  	while($resultUserLevel = $db->fetch_object2($resUserLevel)){

													if($_SESSION['userId'] == $resultUserLevel->id){

														$selected = 'selected="selected"';

													}else{

														$selected = '';

													}
                          if($_SESSION['unit_user']=='2' && in_array($resultUserLevel->id,$salesHead) ){
													 $availableData .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'-'.userTeamName($resultUserLevel->ids_team).'</option>';
                          }
                          else if($_SESSION['unit_user']!=2){
                            $availableData .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'-'.userTeamName($resultUserLevel->ids_team).'</option>';
                          }

												}

											  }

											 	 $availableData .= '</select>';

                                              

                                              	 echo $availableData .='</div>';

												 

												 ?>

												

            

            

            <div class="form-group" style="float:left;">

                <button class="btn btn-primary" onclick="savefollowupDate();" type="button">Save</button>

              &nbsp;<button class="ColseSummaryPopUp_close btn btn-default">Close</button>

              </div>

            '

          </form>

          </div>

          <!---- pop up--->
   
          

          

        <div id="cars2" class="desc" style="display: none;">

            <form id="ColseSummaryPopUpForm" class="ColseSummaryPopUpForm" data-parsley-validate autocomplete="off">

<input type="hidden" name="followup_id_hidden" id="followup_id_hidden" value="">

<input type="hidden" name="daily_Visit_id_hidden" id="daily_Visit_id_hidden" value="">

<input type="hidden" name="hotel_id_hidden" id="hotel_id_hidden" value="">

<input type="hidden" name="followup_status_hidden" id="followup_status_hidden" value="">

<input type="hidden" name="followup_hidden_type" id="followup_hidden_type" value="4">

            <div class="form-group">

                <input type="hidden" name="fs_daily_visit_followup_new" id="fs_daily_visit_followup_new" value="">

               <div class="form-group"> 

               <select name="close_type"  id="close_type" class="form-control input-sm" data-parsley-required >

									 	 <option value="">Select Close Type</option>';

											<?php  $resultClose = selectSql(TBL_CLOSING_MASTER,"where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY name ');
				  

											  while($resultData = $db->fetch_object2($resultClose)){
											
													if($row->id_closing_type	== $resultData->id){

														$selected2 = 'selected="selected"';

													}else{

														$selected2 = '';

													}

													echo $availableDatasales = '<option   '.$selected2.' value="'.$resultData->id.'">'.ucfirst($resultData->name).'</option>';

												}

											 

											 	 

												 

												 ?></select>

                

                

                </div>

                 <div class="form-group"> 

                <textarea   name="followup_close_summary" id="followup_close_summary" class="form-control" placeholder="Close Summary"  data-parsley-required automcomplete="off"></textarea>

                </div>

                <br/>

                <div class="form-group" style="float:left;">

                  <button class="btn btn-primary" onclick="saveColseSummaryPopUpform();" type="button">Save</button>

                 &nbsp;<button class="ColseSummaryPopUp_close btn btn-default">Close</button>

                 </div>

              </div>

          </form>

          </div>


      </div>

      </div>



  </div>




<script type="text/javascript">
	$(document).ready(function(){
		$("#my_popup_no").click(function(){
			window.location="editDailyReport.php";
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

		$("#view").click(function (){
			var hotel_id = $("#hotel_id").val();
			var company_id = $("#id_company").val();
			var rate_id = $("#rate_id").val();
			var reservation_date = $("#reservation_date").val();

			
		 $.ajax({
				   type: "POST",
				   url: 'ajax/ajaxGetPlanDetails.php',
				   data: 'hotel_id='+hotel_id+'&reservation_date='+reservation_date+'&rate_id='+rate_id+'&id_company='+company_id, 
				   success: function (result) {	

				   		//console.log(result);				
						$( "#ajaxPlanData" ).html(result);
						$('#planDetail').popup({
		        			 transition: 'all 0.3s',
		           			 autoopen: true,            
		        		})
		        		
		        		}
		        })		
						 //$("#hotelId").val('1').attr('selected','selected');					
			
		});

	});
	 function chkStatus(value){
    	 if(value==1){
      		$('.makeHide').show();
     	}
     	else{
      		$('.makeHide').hide();
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

	function savefollowupDate() {  
	  var form=$("#nextFollowup");

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
			}
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

	function ajaxRemovefollowup(){
		$('#OpenListPopUpshow').popup('hide'); 
	}

	function saveColseSummaryPopUpform(){	
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

			  // resultArray = result.split('&&&&');		

			  

			   // resultArray2 = resultArray[0].split('|||');	

				

			  /*$('#ChangeButton_'+resultArray[1]).html(resultArray2[0]);

			  $('#ChangeFollowUpSummary_'+resultArray[1]).html(resultArray2[1]);

			   $('#ChangeFollowupButton_'+resultArray[1]).html(resultArray2[2]);*/

				

				//$("#ColseSummaryPopUpForm").reset();

			  }

			},

		  complete: function(){

			  $('#followup_close_summary').val('');

			  $('#close_type').val('');

			  $('#close_status').val('');

			$('#ColseSummaryPopUp').popup('hide');
	    $( "#my_popup_yes" ).hide();
	     $( "#my_popup_no" ).hide();
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



function SaveSalesReport(){
	var FollowupCoditionType	=	$("#FollowupCoditionType").val();
	var form=$("#form1");
	if(form.parsley().validate()){
		$('.loading').show(); 
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxUpdateQuote.php',	   
		   data: form.serialize(), 
		   success: function (result) {

			    $( ".my_popup_open" ).click();	
				$( "#FollowUpNextUpdate" ).html(result);
	 		    /*if(FollowupCoditionType == 'addfollowup'){
			   		$('#showFolowup').html(result);		   
				}
				if(FollowupCoditionType == 'addfeedback'){
					$('#showFeedBack').html(result);		   
	 		    }*/	   
			},
		  complete: function(){		  
			$('#OpenListPopUpshow').popup('hide');
		  }
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




</script>

<script>

  $( function() {	 

    $( ".datepickertest").datepicker({ minDate: 0});
    //$( ".pickerdate").datetimepicker({  minDate:new Date()});
    

  } );

  <?php if($row->id != ''){ ?>
    window.onload = function() {    
        getExecutiveName(<?php echo $row->id_company; ?>,<?php echo $row->id_contact; ?>);
    };
<?php } ?>  

</script>

