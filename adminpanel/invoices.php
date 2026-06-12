<?php include_once("../config/auto_loader.php");

checkUserLevelPermission($_SESSION['userLevel'],TBL_DAILY_ENQUERY,'view');



/////////////////////////////////////////////////////////////////////////////////////



//debugData($_REQUEST);



$sql = " SELECT * FROM `invoice` WHERE  `id_shop` = '".addslashes($_SESSION['shop'])."' ";

if(isset($_REQUEST['checkin_radio']) && $_REQUEST['checkin_radio']==2){	

	if($_REQUEST['invoice_date'] != ''){

		//list($checkin,$checkout) = split(" to ",$_REQUEST['reservation_date']);
		
		$reservation_date= explode(" to ",$_REQUEST['invoice_date']);
	$checkin = $reservation_date['0'];
	$checkout = $reservation_date['1'];	

		$sql .= " AND DATE(`invoice`.invoice_date) >='".date('Y-m-d',strtotime($checkin))."' AND DATE(`invoice`.invoice_date) <= '".date('Y-m-d',strtotime($checkout))."' ";

		$fromPrint = $checkin;

		$toPrint = $checkout;

	}

}else if(isset($_REQUEST['checkin_radio']) && $_REQUEST['checkin_radio']==1){

	if($_REQUEST['due_date'] != ''){

		//list($from_book,$to_book) = split(" to ",$_REQUEST['booking_date']);	
$booking_date= explode(" to ",$_REQUEST['due_date']);
	$from_book = $booking_date['0'];
	$to_book = $booking_date['1'];
		

			$sql .= " AND DATE(`invoice`.due_date) >='".date('Y-m-d',strtotime($from_book))."' AND DATE(`invoice`.due_date) <= '".date('Y-m-d',strtotime($to_book))."' ";


		

		$fromPrint = $from_book;

		$toPrint = $to_book;

	}

}



if($_REQUEST['id_hotel'] !="")

  $sql.=" AND `invoice`.hotel_id=".$_REQUEST['id_hotel']." ";
  
if($_REQUEST['id_company'] !="")

  $sql.=" AND `invoice`.id_company=".$_REQUEST['id_company']." ";
  
    
if($_REQUEST['invoice_no'] != ''){
	$sql .= " AND `invoice_no` LIKE '%".addslashes($_REQUEST['invoice_no'])."%'";
}
if($_REQUEST['sales_manager_str'] != ''){

	$sql .= " AND `".INVOICE."`.`sales_manager` LIKE '%".addslashes($_REQUEST['sales_manager_str'])."%'";

}


if($_REQUEST['status'] != ''){

	$sql .= " AND `".INVOICE."`.`lead_status` = '".addslashes($_REQUEST['status'])."'";

}
if($_REQUEST['order'] != ''){
	$sql .= " ORDER BY `date_created` DESC";
}else{
	$sql .= " ORDER BY `date_created` DESC";
}
//echo $sql;
$db->query($sql);
$numRows= $db->num_rows();
$pagging = new pagingClass($sql,$setpage);
$db->query($pagging->getQuery());
$total = $db->num_rows();
?>


<?php include_once("includes/header.php")?>



<?php include_once("includes/left.php")?>

<div class="content-wrapper">

  <!-- Content Header (Page header) -->

  <section class="content-header">

    <h1> Invoice Manager<small>Invoice  Master</small> </h1>

    <ol class="breadcrumb">

      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>

      <li class="active">Invoices</li>

    </ol>

  </section>

  <!-- Main content -->

  <section class="content">

  <div class="row">

    <div class="col-xs-12">

      <div class="nav-tabs-custom">

      		 <!--########## Company Import jump#######-->  
		   
		   <!-- Modal -->
		     <div class="modal fade" id="importInvoiceModal" role="dialog" >
		       <div class="modal-dialog">
		       
		         <!-- Modal content-->
		         <div class="modal-content" style="width: 300px; margin: 0px auto;">
		           <div class="modal-header">
		             <button type="button" class="close" data-dismiss="modal">&times;</button>
		             <h4 class="modal-title">Import Invoice</h4><br>
		             <span id="returnTxt" style="color: Green;"></span>
		           </div>
		           <div class="modal-body">
		             <form name="invoiceImport" method="post" enctype="multipart/form-data" id="invoiceImport">
		               <div >
		                 <label for="file">Choose File : <span style="color: red;">*</span></label>
		                 <input type="file" name="invoiceImport" class="form-control" id="invoiceImport">
		               </div><br>
		               <div >
		                 <input type="submit" value="uplaod" name="submit" class="btn btn-primary" id="importCompany"><span style="color:red;margin-left:50px; ">*</span> = Required 
		                 Field<br>
		               </div>

		            </form>
		           </div>
		         </div>
		         
		       </div>
		     </div>
		     
		   
		<!--########## Import Company  Modal End#######-->  

        <div class="form-group has-error" align="center">

          <?php if($_SESSION['errorMsg']){?>

          <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>

          <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>

          <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>

          <?php unset($_SESSION['successMsg']);}?>

        </div>

	      <div class="box-header with-border">
	          <?php /*?><h3 class="box-title">Search <small>Total Records: (<?=$numRows;?>) &nbsp;</small> </h3><?php */?>
			  <div class="btn-group  pull-right">
	                  <a type="button" class="btn btn-success" href="editInvoice.php">Add Invoice</a>
	                  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
	                    <span class="caret"></span>
	                    <span class="sr-only">Toggle Dropdown</span>
	                  </button>
	                 
	                  <ul class="dropdown-menu" role="menu">
	                  	<?php /*?><li><a title="Import to excel file" href="#" data-toggle="modal" data-target="#importInvoiceModal" ><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Import</a></li>
	                    <li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_COMPANY;?>"><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Export Creation Based</a></li>
	                    <li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_COMPANY;?>"><img  src="images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export Creation Based</a></li>
	                  <li><a title="Export to excel file" href="exportInvoiceTable.php?fileType=xls&tableName=<?php echo TBL_COMPANY;?>"><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Export Profile Based </a></li><?php */?>
	                 <li><a title="Import to excel file" href="UploadInvoice.php"  ><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Import</a></li>  
	                  </ul>
	           </div>          
	        </div>
	        <!-- /.box-header -->

        <form name="searchForm" action="" method="get">

          <input type="hidden" value="1" name="searchFormSubmit" />

          <div class="box-body">

            <div class="row">

              

              <div class="col-md-4">

                <div class="form-group">

                  <label>Hotel</label>

                  <?php $companyDropDown = '<select class="form-control select2" name="id_hotel" id="id_hotel" >

											    <option value="">Select Hotel</option>';

											  $resCat = selectSql(TBL_HOTELS,"where status='1' and id_shop='".addslashes($_SESSION['shop'])."' AND name!='' ",' ORDER BY `name`');

											  if($db->num_rows2($resCat)){

											  	while($resultCat = $db->fetch_object2($resCat)){

													if($_REQUEST['id_hotel'] == $resultCat->id){

														$selected = 'selected="selected"';

													}else{

														$selected = '';

													}

													$companyDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'-'.ucfirst($resultCat->city).'</option>';

												}

											  }

											 	echo $companyDropDown .= '</select>';

											  ?>

                </div>

              </div>

              

              <!-- /.col -->

		        <div class="col-md-4">

		                <div class="form-group">

		                  <label>Invoice No</label>

		                <input type="text" name="invoice_no" id="invoice_no"  class="form-control">

													
		                </div>

		              </div>
		              <!--col end-->



		                 <div class="col-md-4">

                <div class="form-group">

                  <label>Source</label>

              
<select  class="form-control select2 itemName" name="id_company" id="id_company" data-parsley-errors-container="#idcompanyError"  data-parsley-required>
</select>
                </div>

              </div>

                 
		              

            

            
           

                                

                                

          </div>
<div class="row">


<div class="col-md-12">
</div>
<div class="form-group col-sm-4">
<label for="booking_date">
<input type="radio" name="checkin_radio" value="2" <?php if($_REQUEST['checkin_radio']=='2'){echo 'checked="checked"'; }else if(isset($_REQUEST['checkin_radio']) && $_REQUEST['checkin_radio']=='1'  ){}else{ //echo 'checked="checked"';
}?>/>
&nbsp;Invoice Date  : From - To
</label>
<div class="input-group">
<div class="input-group-addon">
<i class="fa fa-calendar"></i>
</div>
<input type="text" class="form-control pull-right dateRangeEdit" placeholder="Enter booking date" id="invoice_date" name="invoice_date" value="<?php if($_REQUEST) echo $_REQUEST['invoice_date'];?>">
</div>

<!-- /.input group -->

</div>
<div class="form-group col-sm-4">
<label for="reservation_date">
<input type="radio" name="checkin_radio" value="1" <?php if($_REQUEST['checkin_radio']=='1'){echo 'checked="checked"'; }else if(isset($_REQUEST['checkin_radio']) && $_REQUEST['checkin_radio']=='2'  ){}?>/>
&nbsp;Due Date : From - To 
</label>
<div class="input-group">
<div class="input-group-addon">
<i class="fa fa-calendar"></i>
</div>
<input type="text" class="form-control pull-right dateRangeEdit" id="due_date" placeholder="Enter Due date" name="due_date" id="reservation_date" data-parsley-required value="<?php if(isset($_REQUEST['due_date'])) echo $_REQUEST['due_date'];?>"  automcomplete="off">
</div>

<!-- /.input group --> 

<!-- /.row -->

</div>


 <div class="col-md-4">

                <div class="form-group">

                  <label>Sales Manager </label>
					<input type="text" name="sales_manager_str" class="form-control">
                  <?php

				  

				 // $sql = ;

				  

				   /*$levelDropDown = '<select class="form-control select2" name="rateletter_id">

											    <option value="">Select Rate Number</option>';

											  $resCat = executeSql(" SELECT  `".TBL_RATE."`.*, `".TBL_RATE_DETAILS."`.hotel_id  FROM `".TBL_RATE."` LEFT JOIN `".TBL_RATE_DETAILS."` ON `".TBL_RATE."`.id=`".TBL_RATE_DETAILS."`.rate_id  WHERE `".TBL_RATE."`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND `".TBL_RATE."`.`company_id` != '0' group by `".TBL_RATE_DETAILS."`.rate_id order by id desc");

											  if($db->num_rows2($resCat)){

											  	while($resultCat = $db->fetch_object2($resCat)){

													if($_REQUEST['rateletter_id'] == $resultCat->id){

														$selected = 'selected="selected"';

													}else{

														$selected = '';

													}// $row->rate_name.'-'.$row->sub_code.$row->rate_details_id

													$levelDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->rate_name).'</option>';

												}

											  }

											 	echo $levelDropDown .= '</select>';*/

											  ?>

                </div>

              </div>
              </div>
              <div class="row">
				  <!--Status Starts-->
			  <div class="col-md-4">
					<div class="form-group">
						<label>Status</label>				
						<?php 
							if($_REQUEST['status'] == '1'){
									$selected1 = 'selected="selected"';
							}elseif($_REQUEST['status'] == '0'){
									$selected0 = 'selected="selected"';
							}elseif($_REQUEST['status'] == '2'){
									$selected2 = 'selected="selected"';
							}
							
						echo $statusDropDown = '<select class="form-control select2" name="status"> <option value="">All</option>
						<option '.$selected1.' value="1">Pending</option>
						<option '.$selected0.' value="0">Received</option>
						<option '.$selected2.' value="2">Parcially Received</option>
						</select>';?>
					</div>
              <!-- /.form-group -->
            </div>
</div>
          <!-- /.box-body -->

          <div class="box-footer">

            <input name="Search" type="submit" class="btn btn-primary" value="Search" style="float:left;"  /> &nbsp;&nbsp;&nbsp;

            

         <?php /*?>   <input name="Download" type="submit" class="btn btn-primary" value="Download" target="_blank" style="margin-left:10px;float:left;" />

        <?php */?>

          </div>

        </form>

      

 

       

 



  

        <div class="box">

          <div class="box-header">

            <h3 class="box-title">Invoice List</h3>

          </div>

          <form name="listingForm" action="" method="post">

            <input type="hidden" value="" name="act" />

            <div id="listingDiv"></div>

            <!-- /.box-header -->

            <div class="box-body table-responsive">

              <table id="" class="table table-bordered table-striped">

                <thead>

                  <tr>

					<!--<th width="10%">S.No.&nbsp;</th>-->
					<th>Invoice Number </th>
					<th>Invoice Date</th>
						
					<th>Due Date </th>
					<th>Hotel Name </th>
					<th>Source 	</th>
					
					<th>Guest Name </th>
					<th>Checkin </th>
					<th>Checkout </th>
					<th>Amount </th>
                    <th>Received </th>
                    <th>Balance </th>
                    <th>Sales Manager </th>
                    <th>Status </th>
					<th>Action</th>
  

                  </tr>

                </thead>
 			<tbody>
				<?php 
				 				
				if($total > 0){$counter = 1;
				  while($row = $db->fetch_object()){
					  if($row->lead_status=='2'){
						$LeadText	='Parcially Received';  
						$leadStatus='2';//parsley Paid
						}elseif($row->lead_status=='1'){
							$LeadText	='Pending';  
							$leadStatus='1';//Pending Paid
							}else{
								$LeadText	='Received';  
								//Received;
								}
					  
					  
					  
					//  print_r($row);
					  ?>
                <tr>
                <td><?=$row->invoice_no;?></td>
                <td><?=$row->invoice_date;?></td>
                
                <td><?=$row->due_date;?></td>
                <td><?=selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$row->hotel_id."'");?> - <?=selectColumn(TBL_HOTELS,'city'," WHERE `id` = '".$row->hotel_id."'");?></td>
                <td><?=selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$row->id_company."'");?> - <?=selectColumn(TBL_COMPANY,'city'," WHERE `id_company` = '".$row->id_company."'");?></td>
               
                <td><?=$row->guest_name;?></td>
                <td><?=$row->checkin;?></td>
                <td><?=$row->checkout;?></td>
                <td><?=$row->amount;?></td>
                <td><?=$row->received;?></td>
                <td><?=$row->balance;?></td>
                <td><?=$row->sales_manager;?></td>
                <td><?php  echo '<button  data="'.$FollowupSqlRow['id'].'" class="btn '.$StatusEs.'" type="button" onclick="OpenPopup('.$row->id.','.$row->amount.','.$row->received.','.$row->balance.');"    >'.$LeadText.'</button>'; ?></td>
                <td><img src="images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editInvoice.php?eId=<?=encryptor('encrypt',$row->id)?>&action=edit&page=<?=$_REQUEST['page']?>';" /></td>
                </tr>
                 <?php $Expand++;

				  

				  	}?>

                  <tr>

                    <td align="right" colspan="12"><?php  echo $pagging->getLinks();?>

                    </td>

                  </tr>

                  <?php }else {?>

                  <tr>

                    <td height="200" align="center" colspan="12">---- No Record Found ---- </td>

                  </tr>

                  <?php }?>

            </tbody>

              </table>

            </div>

          </form>

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

  <div id="ColseSummaryPopUp" class="well" style="display:none;">
  <div id="" class="ajaxAddRoom">
    <div class="btn btn-default tablenew1 tablenewmobile1">
     <form name="nextFollowup" id="nextFollowup"  method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off">
        
      <div class="">
        <div class="form-group" style="text-align:left;">
          <label>Payment Status </label>
          <select  name="invoice_status" id="invoice_status" class="form-control" data-parsley-required>



                            <option value="">Select Payment Status</option>


							<option value="1" >Pending</option>
                            <option value="0">Received</option>


							<option value="2">Parcially Received</option>
                            

							

                            



                          </select>
          
          
          </div>
          
          
      </div> 
      <div id="cars1" class="desc">
         <input type="hidden" name="invoice_id" id="invoice_id" value="">
           <input type="hidden" name="followup_id" id="followup_id" value="">
          <input type="hidden" name="daily_Visit_id" id="daily_Visit_id" value="">
          <input type="hidden" name="hotel_id_hidden" id="hotel_id" value="">
          <input type="hidden" name="followup_status" id="followup" value="">
          <input type="hidden" name="followup_type" id="followup_type" value="4">
          <div class="form-group">
            <label style="float:left;">Summary</label>
            <textarea   name="followup_description" id="followup_description"  class="form-control" placeholder="Summary"  data-parsley-required automcomplete="off" maxlength="150"></textarea>
          </div>
          
         <div class="form-group col-md-4">
              

              <label for="invoiceno">Amount:</label>
              <input type="text" class="form-control " readonly="readonly" placeholder="Enter Amount " id="new_amount" name="new_amount" value="0"  data-parsley-required>
              </div>   
                  
               
  <div class="form-group col-md-4">
              

              <label for="invoiceno">Received:</label>
              <input type="text" class="form-control " onkeyup="amount_calc(this.value,'1');" placeholder="Enter Received Amount" id="new_received" name="new_received" value="0"  data-parsley-required>
               </div>
              
              
              <div class="form-group col-md-4">
              

              <label for="invoiceno">Balance :</label>
              <input type="text" class="form-control " readonly="readonly" placeholder="Enter Balance" id="new_balance" name="new_balance" value="0"  data-parsley-require>
              </div>




         <?php /*?> <div class="form-group">
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
          </div><?php */?>
          

              

                
          <div class="form-group" style="float:left;">
            <button class="btn btn-primary" onclick="saveChangeStatusDate();" type="button" >Save</button>
            &nbsp;
            <button class="ColseSummaryPopUp_close btn btn-default">Close</button>
          </div>
          '
        </form>
      </div>
      
    </div>
  </div>
</div>

  
  <?php include_once("includes/footer.php")?>


<script>
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
	  
	  function OpenPopup(id,amount,received,balance){
		//alert();
		//if(followup_status	== '0'){		
		//	alert('Your Follow Up already Closed');
		//}else{
				
			$('#new_amount').val(amount);
			$('#new_received').val(received);
			$('#new_balance').val(balance);
			$('#invoice_id').val(id);
				
			
			$('#ColseSummaryPopUp').popup('show');
		//}
	}
	
	
	function saveChangeStatusDate() { 
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
		   url: 'ajax/ajaxInvoiceStatusUpdate.php',
		   data: nextFollowup,  
		   success: function (result) {
			 	
		     $( "#my_popup_yes" ).hide();
	         $( "#my_popup_no" ).hide();
  		     $( ".my_popup_open" ).click();	
 		     $( "#FollowUpNextUpdate" ).html(result);
			 window.location.reload();
			 			},
			complete: function(){
				$('#OpenListPopUpshow').popup('hide');
				
				/*	// Forward Enquiry Mail
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
function amount_calc(clicked_id,type){
		
		if(type==1){
		var new_amount=$('#new_amount').val();
		
		
		$('#new_balance').val(new_amount-clicked_id);
	}else{
		
		var new_amount1=$('#new_amount1').val();
		
		
		$('#new_balance1').val(new_amount1-clicked_id);
		}
	}	
 </script>


