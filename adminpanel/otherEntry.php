<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_OTHER,'view');
//---------------------------------------------------------------------------------------------------------

// ----------cate---------
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
	$sql = "  SELECT * FROM `".TBL_OTHER."`
								WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
	$db->query($sql);
	if($db->num_rows() > 0){
		$row = $db->fetch_object();
	}	
  if($row->conveyance_approved==1 || $row->id_user!=$_SESSION['userId']){
    $readonly="readonly='readonly'";
    $disabledEdit = "disabled='disabled'";
    $tootip = "Action Taken";
  }
  else{
    $readonly='';
    $disabledEdit='';
  }					
}	
							

?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Sales Activity <small></small> </h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Sales Activity </li>
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
     	        <h3 class="box-title"><?php echo ($_REQUEST['eId']!=""?"Edit":"Add");?> Sales Activity </h3>
     	    </div>

     	    <div class="box-header with-border" id="recentAdd" style="border:1px solid #252525; border-radius:3px; display: none;">
              <h3 class="box-title" style="color: green;">Recently Added Activities :</h3>
              <table  class="table table-striped" id="recentAddData">
                <tr style="background-color:#c2c2c2;color: #000; ">
                  <td><b>S.No.</b></td>
                  <td><b>Activity Type</b></td>
                  <td><b>Description</b></td>
                  <td><b>Total Conveyance</b></td>
                  <td><b>Entertainment</b></td>
                  <td><b>Lunch</b></td>
                  <td><b>Action</b></td>
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
     	                 <input <?php echo ($_REQUEST['eId']!=""?'disabled="disabled"':"");?> type="text" class="form-control pickerdate_addreport" placeholder="Enter Date" id="report_date" name="report_date" value="<?php echo ($row->dated!=''?date('d-m-Y',strtotime($row->dated)):date('d-m-Y'));?>"  data-parsley-required autocomplete="off">
     	               </div>
     	               <div class="col-md-3">
     	               		<label>Activity Type :<font color="#FF0000" id="actError">*</font> </label>
     	               		
     	               		<?php 

     	               			$actitvityDropDown = '<select class="form-control select2" name="id_activity" id="id_activity">
								 <option value="">Select Activity</option>';
								 $sqlActivity = "SELECT * FROM ".TBL_OTHER_ACTIVITY." WHERE id_shop=".$_SESSION['shop']." AND status='1' order by name ";
								 
								 $resAct = mysqli_query($connNew,$sqlActivity);

								 while($rowAct = mysqli_fetch_object($resAct)){
								 	if($row->id_other_activity == $rowAct->id){
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

     	        <label for="StatFrom">&nbsp;&nbsp;&nbsp;Conveyance  </label>

                 <div class="btn btn-default" style="text-align:left;width:100%;">

             <div class="col-md-5">                  

                <div class="form-group">

                  <label for="StatFrom">Area Covered</label>

                  <!--<input type="text" class="form-control" name="StatFrom" id="StatFrom"  value="<?php if($_POST) echo $_POST['StatFrom'];else echo stripslashes($row->StatFrom);?>"  placeholder="Enter From" automcomplete="off"  data-parsley-errors-container="#StatFromError">-->

                  <textarea rows="5"  class="form-control" automcomplete="off" name="StatFrom" id="StatFrom" data-parsley-errors-container="#StatFromError"><?php if($_POST) echo $_POST['StatFrom'];else echo stripslashes($row->details);?></textarea>

                  <?php echo $err_StatFrom;?> </div>

                  </div>

                  

                  <!--<div class="col-md-2">

                <div class="form-group">

                  <label for="StatTo">To</label>

                  <input type="text" class="form-control" name="StatTo" id="StatTo"  value="<?php if($_POST) echo $_POST['StatTo'];else echo stripslashes($row->StatTo);?>"  placeholder="Enter To" automcomplete="off"  data-parsley-errors-container="#StatToError">



                  <?php echo $err_StatTo;?> </div>

                  </div>-->
                  <div class="col-md-3"> 
                    <div class="form-group">
                    <label for="userlevelId">Travel Mode 
                     </label> <br>

                    <?php $categoryDropDown = '<select class="form-control select2" name="travelMode" id="travelMode" >
                          <option value="">Select Travel Mode</option>
                          ';
                        $resUserLevel = selectSql(TBL_TRAVEL_MODES," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' and`status` = '1'",' ORDER BY `name`');
                        if($db->num_rows2($resUserLevel)){
                          while($resultUserLevel = $db->fetch_object2($resUserLevel)){
                       
                          if($_REQUEST['travelMode'] == $resultUserLevel->id){

                            $selected = 'selected="selected"';

                          }elseif($row->id_travel_mode == $resultUserLevel->id){

                            $selected = 'selected="selected"';

                          }else{

                            $selected = '';

                          }

                          $categoryDropDown .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'</option>';
                         }

                        }

                        echo $categoryDropDown .= '</select>';

                        ?>
                      </div>
        

                   </div>




                  <div class="col-md-2">

                <div class="form-group">

                  <label for="KmsRun">Kms Run</label>

                  <input type="text" class="form-control calTotal" name="KmsRun" id="KmsRun"  value="<?php if($_POST) echo $_POST['KmsRun'];else echo stripslashes($row->KmsRun);?>"  placeholder="Enter Kms Run" automcomplete="off"  data-parsley-errors-container="#KmsRunError">

                  <?php echo $err_KmsRun;?> </div>

                  </div>







                  <div class="col-md-2">

                <div class="form-group">

                  <label for="RateKm">Rate/Km or Actuals</label>

                  <input type="text" class="form-control calTotal" name="RateKm" id="RateKm"  value="<?php if($_POST) echo $_POST['RateKm'];else echo stripslashes($row->RateKm);?>"  placeholder="Enter Rate / Km" automcomplete="off"  data-parsley-errors-container="#RateKmError">



                  <?php echo $err_RateKm;?> </div>

                  </div>



                



                  <div class="col-md-2">

                <div class="form-group">

                  <label for="Parking">Parking / Toll Charges</label>

                  <input type="text" class="form-control calTotal" name="Parking" id="Parking"  value="<?php if($_POST) echo $_POST['Parking'];else echo stripslashes($row->Parking);?>"  placeholder="Enter Parking" automcomplete="off"  data-parsley-errors-container="#ParkingError">



                  <?php echo $err_Parking;?> </div>

                  </div>

                  <div class="col-md-1">

                <div class="form-group">

                  <label for="Total">Total</label>

                  <input  type="hidden" class="form-control" name="Total"   value="<?php if($_POST) echo $_POST['Total'];else echo stripslashes($row->Total);?>"  placeholder="Total" automcomplete="off"  data-parsley-errors-container="#TotaleError">

                  <input  type="text"  disabled="disabled" class="form-control"  id="Total"  value="<?php if($_POST) echo $_POST['Total'];else echo stripslashes($row->Total);?>"  placeholder="Total" automcomplete="off"  data-parsley-errors-container="#TotaleError">
                  <?php echo $err_Total;?> </div>

                  </div>
                  <div class="col-md-2">

                  <div class="form-group">

                  <label for="Total">Entertainment</label>
                  <input type="text" class="form-control" name="entertainment" id="entertainment"  value="<?php if($_POST) echo $_POST['entertainment'];else echo stripslashes($row->entertainment);?>"  placeholder="Enter Entertainment " automcomplete="off"  data-parsley-errors-container="#EntertainmentError">
                   <?php echo $err_Total;?> </div>

                  </div>

                  <div class="col-md-2">

                  <div class="form-group">

                  <label for="Total">Lunch</label>
                  <input type="text" class="form-control calTotal" name="lunch" id="lunch"  value="<?php if($_POST) echo $_POST['lunch'];else echo stripslashes($row->lunch);?>"  placeholder="Enter lunch " automcomplete="off"  data-parsley-errors-container="#lunchError">
                  <?php echo $err_Total;?> </div>

                  </div>

</div>


           
         <!-- <div class="box-header ">
              <hr></hr> 
              <h4 class="box-title"><b>Conveyance:</b></h4>
          </div>   
                 

             <div class="col-md-2">                  

                <div class="form-group">

                  <label for="StatFrom">Details</label>

                  
                  <textarea  class="form-control" automcomplete="off" name="StatFrom" id="StatFrom" data-parsley-errors-container="#StatFromError"><?php if($_POST) echo $_POST['StatFrom'];else echo stripslashes($row->details);?></textarea>

                  <?php echo $err_StatFrom;?> </div>

                  </div>

                                   


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

                  <input type="text" class="form-control" name="entertainment" id="entertainment"  value="<?php if($_POST) echo $_POST['entertainment'];else echo stripslashes($row->entertainment);?>"  placeholder="Enter Entertainment " autocomplete="off"  data-parsley-errors-container="#EntertainmentError">



                  <?php echo $err_Total;?> </div>

                  </div>



                  <div class="col-md-2">

                <div class="form-group">

                  <label for="Parking">Parking</label>

                  <input type="text" class="form-control calTotal" name="Parking" id="Parking"  value="<?php if($_POST) echo $_POST['Parking'];else echo stripslashes($row->Parking);?>"  placeholder="Enter Parking" autocomplete="off"  data-parsley-errors-container="#ParkingError">



                  <?php echo $err_Parking;?> </div>

                  </div>

                  <div class="col-md-2">

                <div class="form-group">

                  <label for="Total">Total</label>

                  <input  type="text" class="form-control" name="Total" id="Total"  value="<?php if($_POST) echo $_POST['Total'];else echo stripslashes($row->Total);?>"  placeholder="Total" automcomplete="off"  data-parsley-errors-container="#TotaleError">



                  <?php echo $err_Total;?> </div>

                  </div>

              <br/><br/>-->
     	           <!-- /.box-body -->


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
     	                 <input class="form-control" disabled="disabled" type="text" value="<?=$row->last_modified?>" />
     	                </div> 
                    </div>  
     	           	<?php }  ?>
     	           <div class="box-footer">
                  <div class="col-sm-12">
                    <hr/>
     	              <input type="button" title="<?php echo $tootip;?>" <?php echo $disabledEdit;?>  onClick="saveInHouse(this.id);" value="<?php echo ($_REQUEST['eId']!=""?"Edit":"Save");?>" id="<?php echo ($_REQUEST['eId']!=""?"Edit":"Save");?>"
     	               class="btn btn-primary">
     	             <a href="manageInHouse.php" class="btn btn-primary">Close</a>
                 </div>
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
<?php $dsrNoDays  =selectColumn(TBL_USERS,'dsr_num_days'," WHERE `id` = '".$_SESSION['userId']."'");?>
<script type="text/javascript">
  var GetStartDate = <?php echo $dsrNoDays ;?>;
</script>
<?php include_once("includes/footer.php")?>
<script type="text/javascript">
	

		function saveInHouse(formType){

			var count=0;

			if(formType=="Save"){
				
        var date = $("#report_date").val();
				var desc = $("#description").val();
				var type  = $("#id_activity").val();
        var kms    = $("#KmsRun").val();
        var rateKm = $("#RateKm").val();
        var park = $("#Parking").val();
        var travelMode = $("#travelMode").val();
        var Total=$("#Total").val(); 
        var lunch=$("#lunch").val(); 
        var entertainment=$("#entertainment").val(); 
        var details = $("#StatFrom").val(); 


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
				  		 data: 'date='+date+'&type='+type+'&desc='+desc+'&action='+action+'&count='+count+'&details='+details+'&kms='+kms+'&rateKm='+rateKm+'&park='+park+'&entertainment='+entertainment+'&total='+Total+'&travelMode='+travelMode+'&lunch='+lunch, 
				   		success: function (result) {	
				          $("#report_date").removeAttr("disabled");
                  $(".descriptionBox").hide();
                  $("#Edit").html("Add");
                  $("#Edit").attr("id","Save");
                  $("#description").val("");
                  $("#eId").val("");
                  $("#KmsRun").val("0");
                  $("#RateKm").val("0");
                  $("#Parking").val("0");
                  $("#Total").val("0"); 
                  $("#lunch").val("0");
                  $("#entertainment").val("0"); 
                  $("#StatFrom").val("");
                  $("#travelMode").prepend("<option selected='seleted' value=''>Select Travel Mode</option>");
                  $("#id_activity").prepend("<option selected='seleted' value=''>Select Activity</option>");
                  
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
        var travelMode = $("#travelMode").val();
        var kms    = $("#KmsRun").val();
        var rateKm = $("#RateKm").val();
        var park = $("#Parking").val();
        var Total=$("#Total").val(); 
        var lunch=$("#lunch").val();
        var entertainment=$("#entertainment").val(); 
        var details = $("#StatFrom").val();
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
				  		 data: 'date='+date+'&type='+type+'&desc='+desc+'&action='+action+'&count='+count+'&eId='+eId+'&details='+details+'&kms='+kms+'&rateKm='+rateKm+'&park='+park+'&entertainment='+entertainment+'&total='+Total+'&travelMode='+travelMode+'&lunch='+lunch, 
				   		success: function (result) {	
				        $("#report_date").removeAttr("disabled");
                $(".descriptionBox").hide();
                $("#Edit").html("Add");
                $("#Edit").attr("id","Save");
                $("#description").val("");
                $("#eId").val("");
                $("#KmsRun").val("0");
                $("#RateKm").val("0");
                $("#travelMode").prepend("<option selected='seleted' value=''>Select Travel Mode</option>");
                $("#id_activity").prepend("<option selected='seleted' value=''>Select Activity</option>");
                $("#Parking").val("0");
                $("#Total").val("0"); 
                $("#entertainment").val("0"); 
                $("#lunch").val("0"); 
                $("#StatFrom").val("");
				   			$('.my_popup_open').click();
				   			$('#inHouseAfterUpdate').html("Do you want to add More ?");
				   			$('#recentAddData').append(result);
				   			$('#recentAdd').show(result);
				   			
						}
					})
				}
			}
			
				

			

			$("#my_popup_no").click(function(){
				window.location.href="editDailyReport.php";
			});
		}
</script>

<script type="text/javascript">

     $(".calTotal").change(function(){

        var kms = $("#KmsRun").val();
        if(kms=="")
            kms=0;
 

        var rateKm = $("#RateKm").val();
          if(rateKm=="")
            rateKm=0;

        var park = $("#Parking").val();
        var lunch = $("#lunch").val();
        var Total   = Number(kms*rateKm)+Number(park);

        $("#Total").val(Total); 

        

     });
     
        
     

   </script>

