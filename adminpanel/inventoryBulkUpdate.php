<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_INVENTORY,'view');
/////////////////////////////////////////////////////////////////////////////////////
if($_REQUEST['eId'] == ''){ header("location:editHotels.php"); }
/////////////////////////////////////////////////////////////////////////////////////
//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){		
	$err = 0;
	
	if(empty($_POST['roomId'])){
		$err++;
		$err_room_id = '<font style="color:red;font-weight:normal;" ><br>Please select room.</font>';
	}
	if(empty($_POST['inventory_date'])){
		$err++;
		$err_inventory_date = '<font style="color:red;font-weight:normal;" ><br>Please enter inventory date.</font>';
	}
		
	if($err == 0){//No error
		 if(($_POST['Save'] == 'Edit') && !empty($_POST['hotelId'])){//update
			
			foreach($_POST['dataId'] as $data =>$value){
			$checkExisting = executeSql("Select id from `".TBL_INVENTORY."` where hotel_id='".addslashes(encryptor('decrypt',$_REQUEST['eId']))."' and room_id='".addslashes($_REQUEST['roomId'])."' and allocation_date= '".addslashes($_POST['allocation_date'.$value])."'");
			
			if(num_rows($checkExisting )>0){
			checkUserLevelPermission($_SESSION['userLevel'],TBL_INVENTORY,'update');
			$Sql = "   	UPDATE `".TBL_INVENTORY."` SET	
							`offline_block_hotel` = '".addslashes($_POST['blocked_hotel'.$value])."',
							`crs_available` = '".addslashes($_POST['crs_available'.$value])."',
							`online_allocation` = '".addslashes($_POST['online_allocation'.$value])."',
							`color` = '".addslashes('#3c8dbc')."'";		
			$Sql .= "		,`last_modified` = '".currenDateTime()."'
							,`status` = '1'
							,`last_modified_by` = '".$_SESSION['userId']."'
							WHERE `room_id` = '".addslashes($_POST['roomId'])."' and 
							`hotel_id` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."' and 
							`allocation_date` = '".addslashes($_POST['allocation_date'.$value])."'";
							executeSql($Sql);
			}else{
			checkUserLevelPermission($_SESSION['userLevel'],TBL_INVENTORY,'add');
			$Sql = "   	INSERT INTO `".TBL_INVENTORY."` SET 
							`room_id` = '".addslashes($_POST['roomId'])."',
							`hotel_id` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."',
							`allocation_date` = '".addslashes($_POST['allocation_date'.$value])."',
							`offline_block_hotel` = '".addslashes($_POST['blocked_hotel'.$value])."',
							`crs_available` = '".addslashes($_POST['crs_available'.$value])."',
							`online_allocation` = '".addslashes($_POST['online_allocation'.$value])."',
							`color` = '".addslashes('#3c8dbc')."'";			
			$Sql .= "		,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							,`status` = '1'";
			
				executeSql($Sql);			
				}
			
			}		
															
			if($Sql){				
				$_SESSION['successMsg'] = selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".encryptor('decrypt',$_REQUEST['eId'])."'").'-'.selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$_POST['room_id']."'").' details has been updated sucessfully.';
				header("location:inventory.php?eId=".$_REQUEST['eId']."&action=edit&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".encryptor('decrypt',$_REQUEST['eId'])."'").'-'.selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$_POST['room_id']."'").' details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'Hotel inventory details has not been saved. Please make corrections.';
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
        <small>Inventory</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Inventory</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
           <div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
			  <li ><a href="editHotels.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" >Overview</a></li>   
				  <li><a href="editHotelGallery.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>">Photo Gallery</a></li> 
				  <li><a href="manageAssignHotelRoom.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>">Room Types</a></li>   
				  
				  <li class="active" ><a href="inventory.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" data-toggle="tab">Inventory</a></li>        
				   <li  ><a href="calendar.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" >Calendar</a></li>  
            </ul> 
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> Inventory : <a><?php echo selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'"); ?> </a></h3>     
			   <a href="inventory.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" class="btn btn-success pull-right"><i class="fa fa-angle-double-left"></i> Back</a>  
			</div> 
            <!-- /.box-header -->
            <!-- form start -->  			        
			 <form name="bulkInventory" id="bulkInventory"  method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off">
                <input type="hidden" value="<?php echo encryptor('decrypt',$_REQUEST['eId']);?>" name="hotelId" />
					<div class="form-group has-error" align="center">
						<?php if($_SESSION['errorMsg']){?>
						 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
						<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
					 	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
						<?php unset($_SESSION['successMsg']);}?>
					 </div>
              <div class="box-header">
              <div class="row">
                <div class="form-group col-sm-5">
                  <label for="roomId">Room Type<font color="#FF0000">*</font></label>
                  <?php $roomDropDown = '<select class="form-control select2" data-placeholder="Please Select Room" name="roomId" id="roomId" data-parsley-required data-parsley-errors-container="#room_idError" onchange="showInventory()">';
						$resRoom = executeSql("SELECT rt.name, ahr.hotel_id, ahr.room_id from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'");
						$roomDropDown .= '<option value="">Select Room</option>';
											  if(num_rows($resRoom)){
											  	while($resultRoom = $db->fetch_object2($resRoom)){													
													$roomDropDown .= '<option '.$selected.' value="'.$resultRoom->room_id.'">'.ucfirst($resultRoom->name).'</option>';
												}
											  }
											 	echo $roomDropDown .= '</select>';
											  ?>
                  <span id="room_idError"><?php echo $err_room_id;?></span> </div>
                <div class="form-group col-sm-5">
                    <label for="inventory_date">From Date - To Date </label>
                    <div class="input-group">
                      <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                      <input type="text" class="form-control pull-right dateRange" id="inventory_date" name="inventory_date" data-parsley-required value="" data-parsley-errors-container="#inventory_dateError" automcomplete="off">
                    </div>
                    <!-- /.input group -->
                    <span id="reservation_dateError"></span> </div>
              </div>
               <div class="box-body box-primary" id="bulkInventoryDetail"> </div>
            </div>
              <!-- /.box-body -->	
			 <div class="box-footer">                                       
				<input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
				&nbsp;&nbsp;&nbsp;&nbsp;
			   <input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("manageAssignHotelRoom.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>"); '>
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
<script>
function showInventory(){
  //alert('test');
  var form=$("#bulkInventory");
  if(form.parsley().validate()){
   $('.loading').show(); 
  $.ajax({
	   type: "POST",
	   url: 'ajax/ajaxInventoryBulk.php',
	   data: form.serialize(), 
	   success: function (result) {
		  if(result!=''){
			$('#bulkInventoryDetail').html(result);
		  }
		},
	  complete: function(){
		$('.loading').hide();
	  }
	});
	}else {
			$('#bulkInventoryDetail').html('');
	}
		return false;

}

function updateInventoryUp(id){

	value= id.split('|');	
	mainId = value[0];
	dateId = value[1];
	inventoryDate = $("#inventory_date").val().split(' to ');
	var startDate = inventoryDate[0].split("-").reverse().join("-");
	var endDate = inventoryDate[1].split("-").reverse().join("-");	
	var start = new Date(startDate);
    var end = new Date(dateId);	
	var thisidValue = $('#'+mainId+'\\|'+dateId).val();	
    while(start <= end){       
	    var thisDate = start.toISOString().slice(0,10);		
		var date = thisDate.split("/").reverse().join("-");
		var room_available = parseFloat($('#room_available\\|'+date).val());
		//var crs_available = parseFloat(room_available-thisidValue);
		$('#'+mainId+'\\|'+date).val(thisidValue);
		if(mainId=='crs_available'){
	//	$('#crs_available\\|'+date).val(crs_available);
		//$('#online_allocation\\|'+date).val(crs_available);
		$('#blocked_hotel\\|'+date).val(room_available-thisidValue);
		}
       var newDate = start.setDate(start.getDate() + 1);
       start = new Date(newDate);
    }	
}

function updateInventoryDown(id){
	value= id.split('|');	
	mainId = value[0];
	dateId = value[1];
	inventoryDate = $("#inventory_date").val().split(' to ');
	var startDate = inventoryDate[0].split("-").reverse().join("-");
	var endDate = inventoryDate[1].split("-").reverse().join("-");	
	var start = new Date(dateId);
    var end = new Date(endDate);	
	var thisidValue = $('#'+mainId+'\\|'+dateId).val();	
    while(start <= end){       
	    var thisDate = start.toISOString().slice(0,10);		
		var date = thisDate.split("/").reverse().join("-");
		var room_available = parseFloat($('#room_available\\|'+date).val());
		//var crs_available = parseFloat(room_available-thisidValue);
		$('#'+mainId+'\\|'+date).val(thisidValue);
		if(mainId=='crs_available'){
		$('#blocked_hotel\\|'+date).val(room_available-thisidValue);
		//$('#online_allocation\\|'+date).val(crs_available);
		}
       var newDate = start.setDate(start.getDate() + 1);
       start = new Date(newDate);
    }	
}


function calculateCrsAvailable(id){

var form=$("#bulkInventory");
if(form.parsley().validate()){
	value= id.split('|');	
	mainId = value[0];
	dateId = value[1];	
	var room_available = parseFloat($('#room_available\\|'+dateId).val());
	var blocked_hotel = parseFloat($('#blocked_hotel\\|'+dateId).val());
	var crs_available = parseFloat(room_available-blocked_hotel);
	$('#crs_available\\|'+dateId).val(crs_available);
	$('#online_allocation\\|'+dateId).val(crs_available);
	}
}



function calculateRoomBlock(id){

var form=$("#bulkInventory");
if(form.parsley().validate()){
	value= id.split('|');	
	mainId = value[0];
	dateId = value[1];	
	var room_available = parseFloat($('#room_available\\|'+dateId).val());
	var crs_available = parseFloat($('#crs_available\\|'+dateId).val());
	
	
	var blocked_hotel = parseFloat(room_available-crs_available);
	
	
	
	$('#blocked_hotel\\|'+dateId).val(blocked_hotel);
	$('#online_allocation\\|'+dateId).val(crs_available);
	}
}

</script>

