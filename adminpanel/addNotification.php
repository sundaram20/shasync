<?php include_once("../config/auto_loader.php");

//---------------------------------------------------------------------------------------------------------
	
// ----------cate---------
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
	$sql = "  SELECT * FROM `".TBL_NOTIFICATION."`
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
    <h1> Notification Entry <small>Notification</small> </h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Notification Entry </li>
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
     	        <h3 class="box-title"><?php echo ($_REQUEST['eId']!=""?"Edit":"Add");?> Notification </h3>
     	    </div>

     	    <div class="box-header with-border" id="recentAdd" style="border:1px solid #252525; border-radius:3px; display: none;">
              <h3 class="box-title" style="color: green;">Recently Added Notifications :</h3>
              <table  class="table table-striped" id="recentAddData">
                <tr style="background-color:#c2c2c2;color: #000; ">
                  <td><b>S.No.</b></td>
                  <td><b>Message</b></td>
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
     	               	<label for="state">Message To :<font color="#FF0000" id="userError">*</font> </label>
                  <?php
                  	if($_REQUEST['eId']!=""){
                  		$multiple='';
                  	}
                  	else{
                  		
                  		$multiple = 'multiple="multiple"';
                  	}
                  ?>
                  
				  <select class="form-control select2" id="ids_user" name="ids_user[]" <?php echo $multiple; ?> >				  
                  <?php			
                  	$sqlUserActions	="SELECT * FROM `".TBL_USERS."` WHERE  `sales_status_active` = '1'  AND id_shop='".$_SESSION['shop']."' ".str_replace('id_state','location',$_SESSION['Ids_user_access_company'])." ";
                  	$resUser = mysqli_query($connNew,$sqlUserActions);
					while($resUserActions = mysqli_fetch_object($resUser)){
						
						if($row->id_user_assigned_to == $resUserActions->id){
							$selected = 'selected="selected"';
						}													
						else{
							$selected = '';
						}
						echo '<option '.$selected.' value="'.$resUserActions->id.'">'.$resUserActions->name.'-'.userTeamName($resUserActions->ids_team).'</option>';
						
						$iCounterActions++;
					}
					?>
					</select>                    
                </div>
     	               

     	            	<div class="form-group col-sm-6">
     	                 <label for="descripton">Message <font color="#FF0000" id="descError">*</font></label>
     	                 <!--<input class="form-control" type="text" name="description" id="description"/>-->
     	                 <textarea class="form-control" type="text" name="message" id="message"/><?php echo ($_REQUEST['eId']!=""?$row->message:"");?></textarea>
     	               </div>              
     	               
     	             </div>
     	           </div>
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
     	              <a href="#"  onClick="saveNotification(this.id);" id="<?php echo ($_REQUEST['eId']!=""?"Edit":"Save");?>"
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

	function saveNotification(formType){
			var count=0;

			if(formType=="Save"){
				var date = $("#report_date").val();
				var desc = $("#message").val();
				var ids_user = $("#ids_user").val();
				var action = "Save";
				if(desc==""){
					$("#descError").html("Can't be blank");
				}
				else if(ids_user=="" || ids_user==null){
					$("#userError").html("Can't be blank");
				}
				else{
					//console.log(date+desc+type);
					count++;
					$.ajax({
				   		type: "POST",
				   		url: 'ajax/ajaxAddNotification.php',
				  		 data: 'date='+date+'&id_assigned='+ids_user+'&desc='+desc+'&action='+action+'&count='+count, 
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
				var desc = $("#message").val();
				var ids_user = $("#ids_user").val();
				var action = "Edit";
				var eId = $("#eId").val();
				if(desc==""){
					$("#descError").html("Can't be blank");
				}
				else if(ids_user=="" || ids_user==null){
					$("#userError").html("Can't be blank");
				}
				else{
					//console.log(date+desc+type);
					count++;
					$.ajax({
				   		type: "POST",
				   		url: 'ajax/ajaxAddNotification.php',
				  		 data: 'date='+date+'&ids_user='+ids_user+'&desc='+desc+'&action='+action+'&count='+count+'&eId='+eId, 
				   		success: function (result) {	
				   			//console.log(result);
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
				$("#message").val("");
				$("#eId").val("");

			});

			$("#my_popup_no").click(function(){
				window.location.href="editDailyReport.php";
			});
		}
</script>

