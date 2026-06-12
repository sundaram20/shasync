<?php include_once("../config/auto_loader.php");

//---------------------------------------------------------------------------------------------------------

	
// ----------cate---------
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
	$sql = "  SELECT * FROM `".TBL_IN_HOUSE."`
								WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
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
    <h1> In House Entry <small>Manage In House</small> </h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">In House Entry </li>
    </ol>
  </section>
 
  <section class="content">
    <div class="row">

     <div class="col-md-12">
     	 <div class="nav-tabs-custom">
     	    <div class="form-group has-error" align="center">
     	        <?php if($_SESSION['errorMsg']){?>
     	        <p class="help-block success"><?php echo messageError($_SESSION['errorMsg']);?></p>
     	           <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
     	        <p class="help-block dander"><?php echo messageSuc($_SESSION['successMsg']);?></p>
     	           <?php unset($_SESSION['successMsg']);}?>
     	    </div>
     	    <div class="box-header with-border">
     	        <h3 class="box-title"><?php echo ($_REQUEST['eId']!=""?"Edit":"Add");?> In House Act :  </h3>
     	    </div>

     	    <div class="box-header with-border" id="recentAdd" style="border:1px solid #252525; border-radius:3px; display: none;">
              <h3 class="box-title" style="color: green;">Recently Added Activities :</h3>
              <table  class="table table-striped" id="recentAddData">
                <tr style="background-color:#c2c2c2;color: #000; ">
                  <td><b>S.No.</b></td>
                  <td><b>Activity Type</b></td>
                  <td><b>Description</b></td>
                </tr>
              </table>
            </div>
     	         <!-- /.box-header -->
     	    <form name="InHouseForm" id="InHouseForm" action="" method="post">
     	    	<input type="hidden" name="eId" id="eId" value="<?=addslashes(encryptor('decrypt',$_REQUEST['eId']));?>"/>	
     	        <div class="box-body">
     	             <div class="row">
     	               <div class="form-group col-sm-3">
     	                 <label for="seasonId">Date : </label>
     	                 <input <?php echo ($_REQUEST['eId']!=""?'disabled="disabled"':"");?> type="text" class="form-control pickerdate" placeholder="Enter Date" id="report_date" name="report_date" value="<?php echo ($row->dated!=''?date('d-m-Y',strtotime($row->dated)):date('d-m-Y'));?>"  data-parsley-required autocomplete="off">
     	               </div>
     	               <div class="col-md-3">
     	               		<label>Activity Type :<font color="#FF0000" id="actError">*</font> </label>
     	               		
     	               		<?php 

     	               			$actitvityDropDown = '<select class="form-control select2" name="id_activity" id="id_activity">
								 <option value="">Select Activity</option>';
								 $sqlActivity = "SELECT * FROM ".TBL_IN_HOUSE_ACTIVITY." WHERE id_shop=".$_SESSION['shop']." AND status='1' ";
								 
								 $resAct = mysqli_query($connNew,$sqlActivity);

								 while($rowAct = mysqli_fetch_object($resAct)){
								 	if($row->id_house_activity == $rowAct->id){
								 		$selected = "selected='selected'";
								 	}
								 	else{
								 		$selected="";
								 	}
								 	
								 	$actitvityDropDown .='<option '.$selected.' value="'.$rowAct->id.'">'.$rowAct->name.'</option>';
								 }

								echo $actitvityDropDown .= '</select>';
							 ?>
     	               </div>
     	            	<div class="form-group col-sm-6">
     	                 <label for="descripton">Description <font color="#FF0000" id="descError">*</font></label>
     	                 <!--<input class="form-control" type="text" name="description" id="description"/>-->
     	                 <textarea class="form-control" type="text" name="description" id="description"/><?php echo ($_REQUEST['eId']!=""?$row->description:"");?></textarea>
     	               </div>              
     	               
     	             </div>
     	           </div>
     	           <label for="StatFrom">Conveyance	</label>

                 <div class="btn btn-default" style="text-align:left;width:100%;">

                 

             <div class="col-md-2">                  

                <div class="form-group">

                  <label for="StatFrom">Details</label>

                  <!--<input type="text" class="form-control" name="StatFrom" id="StatFrom"  value="<?php if($_POST) echo $_POST['StatFrom'];else echo stripslashes($row->StatFrom);?>"  placeholder="Enter From" automcomplete="off"  data-parsley-errors-container="#StatFromError">-->

                  <textarea  class="form-control" automcomplete="off" name="StatFrom" id="StatFrom" data-parsley-errors-container="#StatFromError"><?php if($_POST) echo $_POST['StatFrom'];else echo stripslashes($row->StatFrom.($row->StatTo!=""?"-".$row->StatTo:""));?></textarea>

                  <?php echo $err_StatFrom;?> </div>

                  </div>

                  

                  <!--<div class="col-md-2">

                <div class="form-group">

                  <label for="StatTo">To</label>

                  <input type="text" class="form-control" name="StatTo" id="StatTo"  value="<?php if($_POST) echo $_POST['StatTo'];else echo stripslashes($row->StatTo);?>"  placeholder="Enter To" automcomplete="off"  data-parsley-errors-container="#StatToError">



                  <?php echo $err_StatTo;?> </div>

                  </div>-->



                  <div class="col-md-2">

                <div class="form-group">

                  <label for="KmsRun">Kms Run</label>

                  <input type="text" class="form-control calTotal" name="KmsRun" id="KmsRun"  value="<?php if($_POST) echo $_POST['KmsRun'];else echo stripslashes($row->KmsRun);?>"  placeholder="Enter Kms Run" automcomplete="off"  data-parsley-errors-container="#KmsRunError">

                  <?php echo $err_KmsRun;?> </div>

                  </div>







                  <div class="col-md-2">

                <div class="form-group">

                  <label for="RateKm">Rate/ Km </label>

                  <input type="text" class="form-control calTotal" name="RateKm" id="RateKm"  value="<?php if($_POST) echo $_POST['RateKm'];else echo stripslashes($row->RateKm);?>"  placeholder="Enter Rate / Km" automcomplete="off"  data-parsley-errors-container="#RateKmError">



                  <?php echo $err_RateKm;?> </div>

                  </div>



<div class="col-md-2">

                <div class="form-group">

                  <label for="Total">Entertainment </label>

                  <input type="text" class="form-control" name="entertainment" id="entertainment"  value="<?php if($_POST) echo $_POST['entertainment'];else echo stripslashes($row->entertainment);?>"  placeholder="Enter Entertainment " automcomplete="off"  data-parsley-errors-container="#EntertainmentError">



                  <?php echo $err_Total;?> </div>

                  </div>



                  <div class="col-md-2">

                <div class="form-group">

                  <label for="Parking">Parking</label>

                  <input type="text" class="form-control calTotal" name="Parking" id="Parking"  value="<?php if($_POST) echo $_POST['Parking'];else echo stripslashes($row->Parking);?>"  placeholder="Enter Parking" automcomplete="off"  data-parsley-errors-container="#ParkingError">



                  <?php echo $err_Parking;?> </div>

                  </div>

                  <div class="col-md-2">

                <div class="form-group">

                  <label for="Total">Total</label>

                  <input  type="text" class="form-control" name="Total" id="Total"  value="<?php if($_POST) echo $_POST['Total'];else echo stripslashes($row->Total);?>"  placeholder="Total" automcomplete="off"  data-parsley-errors-container="#TotaleError">



                  <?php echo $err_Total;?> </div>

                  </div>

</div><br/><br/>
     	           <!-- /.box-body -->

     	           <?php
     	           	if($row->id_mst_user_modified_by !=""){?>
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
     	                 <input class="form-control" disabled="disabled" type="text" value="<?=$row->last_modified?>" />
     	                </div> 
     	           	<?php }  ?>
     	           <div class="box-footer">
     	              <a href="#"  onClick="saveInHouse(this.id);" id="<?php echo ($_REQUEST['eId']!=""?"Edit":"Save");?>"
     	               class="btn btn-primary"><?php echo ($_REQUEST['eId']!=""?"Edit":"Add");?></a>
     	             <a href="editDailyReport.php" class="btn btn-primary">Close</a>
     	             </div>
     	             
     	         </form>
     	          	         
     	           
     	           <!-- /.box-body -->
     	         </div>

     </div>	

    <span class="my_popup_open" style="display: none;"></span>

  	<div id="my_popup" class="well">

    <div id="inHouseAfterUpdate"></div>
    	<button id="my_popup_yes" class="my_popup_close btn btn-default pull-left">Yes</button>
    	<button id="my_popup_no" class="my_popup_close btn btn-default pull-left">No</button>
    </div>

   </div>
 </section>
</div>
<?php include_once("includes/footer.php")?>
<script type="text/javascript">

	

		function saveInHouse(formType){

			var count=0;

			if(formType=="Save"){
				var date = $("#report_date").val();
				var desc = $("#description").val();
				var type = $("#id_activity").val();
				var action = "Save";
				if(desc==""){
					$("#descError").html("Can't be blank");
				}
				else if(type==""){
					$("#actError").html("Can't be blank");
				}
				else{
					//console.log(date+desc+type);
					count++;
					$.ajax({
				   		type: "POST",
				   		url: 'ajax/ajaxAddInHouse.php',
				  		 data: 'date='+date+'&type='+type+'&desc='+desc+'&action='+action+'&count='+count, 
				   		success: function (result) {	
				   			console.log(result);
				   			$('.my_popup_open').click();
				   			$('#inHouseAfterUpdate').html("Do you want to add More ?");
				   			$('#recentAddData').append(result);
				   			$('#recentAdd').show(result);
				   			
						}
					})
				}
			}
			else if(formType=="Edit"){
				var date = $("#report_date").val();
				var desc = $("#description").val();
				var type = $("#id_activity").val();
				var action = "Edit";
				var eId = $("#eId").val();
				if(desc==""){
					$("#descError").html("Can't be blank");
				}
				else if(type==""){
					$("#actError").html("Can't be blank");
				}
				else{
					//console.log(date+desc+type);
					count++;
					$.ajax({
				   		type: "POST",
				   		url: 'ajax/ajaxAddInHouse.php',
				  		 data: 'date='+date+'&type='+type+'&desc='+desc+'&action='+action+'&count='+count+'&eId='+eId, 
				   		success: function (result) {	
				   			console.log(result);
				   			$('.my_popup_open').click();
				   			$('#inHouseAfterUpdate').html("Do you want to add More ?");
				   			$('#recentAddData').append(result);
				   			$('#recentAdd').show(result);
				   			
						}
					})
				}
			}
	

			$("#my_popup_yes").click(function(){
				
				$("#report_date").removeAttr("disabled");
				$(".descriptionBox").hide();
				$("#Edit").html("Add");
				$("#Edit").attr("id","Save");
				$("#description").val("");
				$("#eId").val("");

			});

			$("#my_popup_no").click(function(){
				window.location.href="editDailyReport.php";
			});
		}
</script>

