<?php include_once("../config/auto_loader.php");

checkUserLevelPermission($_SESSION['userLevel'],TBL_ORDERS,'view');
error_reporting(E_ALL);
?>





<?php 

//---------------------------------------------------------------------------------------------------------

 if(@$_REQUEST["act"] == "delete" && !empty($_REQUEST['ids'])){}



// ----------cate---------

$sql = " SELECT * FROM `call` where `id_shop` = '".addslashes($_SESSION['shop'])."'  AND `id_call_master` ='".addslashes(encryptor('decrypt',$_POST['eId']))."'";

if(@$_REQUEST['id_guest'] != ''){
	$sql .= " AND `id_customer` ='".addslashes($_REQUEST['id_guest'])."'";
}

if(@$_REQUEST['search_name'] != ''){
	$sql .= " AND `id_company` ='".addslashes($_REQUEST['search_name'])."'";
}


if(@$_REQUEST['reservation_id'] != ''){

	$sql .= " AND (`reference` LIKE '%".addslashes($_REQUEST['reservation_id'])."%' || concat(reference,'-', code) LIKE '%".addslashes($_REQUEST['reservation_id'])."%' )";

}



if(@$_REQUEST['other_reference'] != ''){

	$sql .= " AND (`other_reference` LIKE '%".addslashes($_REQUEST['other_reference'])."%' || concat(other_reference,'-', code) LIKE '%".addslashes($_REQUEST['other_reference'])."%' )";

}

if(@$_REQUEST['hotelId'] != ''){//FIND_IN_SET('".$resActionId."',user_actions) 

	$sql .= " AND FIND_IN_SET (".addslashes($_REQUEST['hotelId']).",`call`.`id_hotel`)";

}



if(@$_SESSION['HotelUserPermission'] != ''){//FIND_IN_SET('".$resActionId."',user_actions) 

	 $sql .= " AND `call`.`id_hotel` IN  (".addslashes($_SESSION['HotelUserPermission']).")";

}

if(@$_REQUEST['booking_status'] != ''){

	$sql .= " AND `call`.`booking_status` = '".addslashes($_REQUEST['booking_status'])."%'";

}

if(@$_REQUEST['company_id'] != ''){

	$sql .= " AND `call`.`id_company` = '".addslashes($_REQUEST['company_id'])."'";

}

if(@$_REQUEST['guest'] != ''){

	$sql .= " AND `call`.`id_customer` = '".addslashes($_REQUEST['guest'])."'";

}

if(@$_REQUEST['payment_status'] != ''){

	$sql .= " AND `call`.`payment_status` = '".addslashes($_REQUEST['payment_status'])."'";

}



if(@$_REQUEST['booking_date'] != ''){

	$sql .= " AND `call`.`invoice_date` = '".date('Y-m-d',strtotime($_REQUEST['booking_date']))."'";

}



if(@$_REQUEST['checkin_date'] != ''){

	$sql .= " AND `call`.`checkin` = '".date('Y-m-d',strtotime($_REQUEST['checkin_date']))."'";

}



if(@$_REQUEST['searchFormSubmit']!='1'){

	$date = new DateTime();
	$date->modify('-12 hours -30 minutes');
	$time	=$date->format('H:i:s');

	$UniqueDateFor = date ('Y-m-d h:i:s'); 

	$StartDateListFor	=	strtotime("-1 day", strtotime($UniqueDateFor));

	$UniqueDateFor = date ("Y-m-d h:i:s", $StartDateListFor); 

	

	$sql .= " AND `call`.`date_modified` >= '".$UniqueDateFor."'";

	 

}

	// $sql .= ' order by id_order desc ';

echo $sql;

$db->query($sql);

$numRows= $db->num_rows();

$pagging = new pagingClass($sql,500);

//$db->query($pagging->getQuery());

$total = $db->num_rows();

?>

<?php include_once("includes/header.php")?>

<?php include_once("includes/left.php")?>
<?php 
	

?>
<div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1>

        Call Manager

        <small>Manage Call</small>

      </h1>

      <ol class="breadcrumb">

        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>

        <li class="active">Manage Call</li>

      </ol>

    </section>

    <!-- Main content -->

    <section class="content">		

	<div class="box box-default">

	 <div class="form-group has-error" align="center">

		<?php if(@$_SESSION['errorMsg']){?>

		 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>

		<?php unset($_SESSION['errorMsg']);}elseif(@$_SESSION['successMsg']){?>

		<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>

		<?php unset($_SESSION['successMsg']);}?>

		</div>

        <div class="box-header with-border">

          <h3 class="box-title">Search <small>Total Records: (<?=$numRows;?>) &nbsp;</small> </h3>

		  

          

        </div>

        <!-- /.box-header -->

		<form name="searchForm" action="" method="get">

            <input type="hidden" value="1" name="searchFormSubmit" />

        <div class="box-body">

          <div class="row">

		  <div class="col-md-4">

              <div class="form-group">

                <label>Call No</label>				

				<input type="text" name="reservation_id" id="reservation_id" value="<?php echo trim(@$_REQUEST['reservation_id']);?>" class="form-control" placeholder="Enter Reservation Id" />

              </div>

			  

			  

              <!-- /.form-group -->

            </div>

            

            <!-- /.col -->  

			<?php /*?><div class="col-md-4">

              <div class="form-group">

                <label>Guest</label>				

				 <?php $guestDropDown = '<select class="form-control select2" name="guest">

											    <option value="">Select Guest</option>';

											  $resCat = selectSql(TBL_CUSTOMER," where type='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `first_name`');

											  if($db->num_rows2($resCat)){

											  	while($resultCat = $db->fetch_object2($resCat)){

													if($_REQUEST['guest'] == $resultCat->id_customer){

														$selected = 'selected="selected"';

													}else{

														$selected = '';

													}

													$guestDropDown .= '<option '.$selected.' value="'.$resultCat->id_customer.'">'.ucfirst($resultCat->first_name).' '.ucfirst($resultCat->last_name).'</option>';

												}

											  }

											 	echo $guestDropDown .= '</select>';

											  ?>

              </div>

			  			

          </div><?php */?>

		  

		  

			<?php /*?><div class="col-md-4">

              <div class="form-group">

                <label>Source</label>				

				<?php $companyDropDown = '<select class="form-control select2" name="company_id">

											    <option value="">Select Source</option>';

											  $resCat = selectSql(TBL_COMPANY," where id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name`');

											  if($db->num_rows2($resCat)){

											  	while($resultCat = $db->fetch_object2($resCat)){

													if($_REQUEST['company_id'] == $resultCat->id_company){

														$selected = 'selected="selected"';

													}else{

														$selected = '';

													}

													$companyDropDown .= '<option '.$selected.' value="'.$resultCat->id_company.'">'.ucfirst($resultCat->name).'</option>';

												}

											  }

											 	echo $companyDropDown .= '</select>';

											  ?>

              </div>

              <!-- /.form-group -->

            </div><?php */?>

			

			

			

					

					






          <!-- /.row -->

        </div>

		</div>

        <!-- /.box-body -->

        <div class="box-footer">

        <input name="Search" type="submit" class="btn btn-primary" value="Search" />

        </div>

		</form>		

      </div>

      <div class="row">

        <div class="col-xs-12">		     

          <!-- /.box -->

          <div class="box">

            <div class="box-header">

              <h3 class="box-title">Call List</h3>
				<!--<div style="float:right;color:red;font-size:14px;">Red marked hotels are live inventory</div>-->
            </div>

			<form name="listingForm" action="" method="post">

               <input type="hidden" value="" name="act" />

			     <div id="listingDiv"></div>

            <!-- /.box-header -->

            <div class="box-body table-responsive">

              <table id="example2" class="table table-bordered table-striped">

                <thead>

                <tr>

                  <th> SNo</th>

				  <th>Reservation No</th>

				 <th>Executive Name</th>
 <th>Date</th>  <th>Action</th>
                </tr>

                </thead>

                <tbody>

		

<?php 	
				if($total > 0){$counter = 1;

				  while($row = $db->fetch_object()){
					  
					  
				?>

                <tr>		



                  <td> <?php echo $counter++;?></td>

				  <td><?=$row->reference; ?></td>
 <td><?php echo selectColumn(TBL_USERS,'name'," WHERE `id` = '".$row->id_user."'");?></td>
				 <td><?=dateformat_date($row->date_created);?></td>
 <td>
                  
                  
                  
                  
                  &nbsp;&nbsp;&nbsp;&nbsp;<img src="images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editCronMaster.php?eId=<?=encryptor('encrypt',$row->id)?>&action=edit&page=<?=$_REQUEST['page']?>';" />
                  &nbsp;&nbsp;&nbsp;&nbsp;<img src="images/delete.gif" style="cursor:pointer;" title="Delete" name="<?php echo $row->name; ?>" id="<?php echo $row->id;?>" onClick="deleteMe(this.id,this.name);"/></td>
                </tr>

               <?php }?> 

			    <!--<tr>

                     <td align="left" colspan="8">

					 <input name="delete_sel" type="button" class="btn btn-warning" value="Delete" onClick="javascript:formSubmit('delete');"/>&nbsp;&nbsp;&nbsp;&nbsp; 

					  </td>

				</tr>-->

				<!--<tr>	 

					  <td align="right" colspan="5"> </td>

                 </tr> -->              

				<?php }else {?>

				

				 <!--<tr>

                      <td height="200" align="center" colspan="8">---- No Record Found ---- </td>

                 </tr> -->               

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
<div class="modal fade" id="bookingCopy" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <form method="post" role="form"  enctype="multipart/form-data">
          <input name="copy_id_order" value="" type="hidden" id="copy_id_order">
          <input type="hidden" name="formType" value="forCopyBookings">
    	  <input name="copy_hotel_id" type="hidden" id="copy_hotel_id">	  
    	   <input name="copy_booking_type" type="hidden" id="copy_booking_type">	  
    	   
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title">Create Duplicate Booking </h4>
            </div>
            <div class="modal-body">
              <div class="row">     		       		             
                <div class="col-xs-12 col-md-12">
                  <div class="form-group">
                    Are You sure You want to copy this Booking?
                  </div>
                </div>
                <div class="col-xs-12 col-md-6">
                  <div class="form-group">
                    <label>No Of Copy<font color="#FF0000">*</font></label>
                     <select name="no_of_copy_dup"  id="no_of_copy_dup" class="form-control input-sm" data-parsley-required >
                    
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    
                    </select>
                  </div>
                </div>
                 <div class="box-body no-padding text-center loading" >
          <button type="button" class="btn btn-default btn-lrg ajax" title="Loading..."> <i class="fa fa-spin fa-refresh"></i>&nbsp; Loading... </button>
        </div>				
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" name="SaveGal" onClick="SaveCopyBookings();" value="1">Save</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <!--show msg in popup--> 
<span class="my_popup_open" style="display:none;"></span>
<div id="my_popup" class="well">
  <div id="rateUpdateData"></div>
  <button class="my_popup_close btn btn-default pull-right">Close</button>
</div>


<?php include_once("includes/footer.php")?>  

<script type="text/javascript">
	
	function SendWhatsApp(id_order){
	
	$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxSendWhatappMsg.php',
		   data: 'id_order='+id_order, 
		   success: function (result) {	
		   
		   data1 = JSON.parse(result);	
		   alert(data1.Msg);		
		   		//$(".modal").modal("hide");
				//$( ".my_popup_open" ).click();			
				//$( "#rateUpdateData" ).html(result);				
			},
	 	 
		})
	
	}
	$(document).ready(function(){

		$('#example2').DataTable( 
			{ 	responsive: true, "dom": '<f<t>lip>',
				 "oLanguage": 
				 	{ "sSearch": "Search By Call No:"} ,
				"columnDefs": [
				   { "searchable": false, "targets": [0,2] }
				 ]	 	
			}
			
		);

	});

	function copyfornewbooking(copy_id_order, copy_hotel_id,copy_booking_type)
{ 
	$('#copy_id_order').val(copy_id_order);
	$('#copy_hotel_id').val(copy_hotel_id);
	$('#copy_booking_type').val(copy_booking_type);
	$('#no_of_copy_dup').val('1');
}
function SaveCopyBookings(){
	var copy_id_order = $("#copy_id_order").val();
	var copy_hotel_id= $("#copy_hotel_id").val();
	var no_of_copy_dup= $("#no_of_copy_dup").val();
	var copy_booking_type= $("#copy_booking_type").val();
	$('.loading').show();
	$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxSaveCopyBooking.php',
		   data: 'copy_id_order='+copy_id_order+'&copy_hotel_id='+copy_hotel_id+'&no_of_copy_dup='+no_of_copy_dup+'&copy_booking_type='+copy_booking_type, 
		   success: function (result) {				
		   		$(".modal").modal("hide");
				$( ".my_popup_open" ).click();			
				$( "#rateUpdateData" ).html(result);				
			},
	 	 complete: function(){
			 $(".modal").modal("hide");
			$('.loading').hide();
	  	 }
		})
	
	}	
</script>