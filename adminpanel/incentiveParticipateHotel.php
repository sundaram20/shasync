<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');
$image_path = $UPLOAD_FILES.'/hotel_gallery/';
$image_display_path = $UPLOAD_FILES_PATH ."/hotel_gallery/";

if($_SESSION['unit_user']==2){
	$displayHide = 'style="display:none;"';
	$displayReadOnly = 'readonly="readonly"';
}
//---------------------------------------------------------------------------------------------------------
if($_REQUEST['action'] == 'change'){
	if($_REQUEST['activeId'] != ''){
		
		checkUserLevelPermission($_SESSION['userLevel'],'fs_incentive_participate_hotel','activate');
		$statusId = addslashes(encryptor('decrypt',$_REQUEST['activeId']));
		$statusSql = "	UPDATE `fs_incentive_participate_hotel`
						SET `status` = '1',
						`modified_by`='".$_SESSION['userId']."',	
					`date_modified`='".date('Y-m-d H:i:s')."'
						WHERE `id` = '".addslashes($statusId)."'";
	}elseif($_REQUEST['inactiveId'] != ''){
		
				
		checkUserLevelPermission($_SESSION['userLevel'],'fs_incentive_participate_hotel','deactivate');
		 $statusId = addslashes(encryptor('decrypt',$_REQUEST['inactiveId']));
		$statusSql = "	UPDATE `fs_incentive_participate_hotel` 
						SET `status` = '0' ,
						`modified_by`='".$_SESSION['userId']."',	
					`date_modified`='".date('Y-m-d H:i:s')."'
						WHERE `id` = '".addslashes($statusId)."'";
	}
	$hotel_id	=	selectColumn('fs_incentive_participate_hotel','hotel_id'," WHERE `id` = '".$statusId."'");
	if(executeSql($statusSql)){
		$err = 0;
		$_SESSION['successMsg'] = ''.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$hotel_id."'").' status has been changed sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = ''.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$hotel_id."'").' status has not been changed sucessfully.';
	}
}else if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){
	}
if($_REQUEST["act"] == "activate" && !empty($_REQUEST['ids'])){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'activate');	
	$activateIds = implode(',',$_REQUEST['ids']);	
	$statusSql = "	UPDATE `".TBL_HOTELS."`
						SET `status` = '1'
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` IN (".addslashes($activateIds).")";	
										
	if(executeSql($statusSql)){
		$err = 0;
		$_SESSION['successMsg'] = 'Selected records status has been activated sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Selected records status has not been activated sucessfully.';
	}	
}else if($_REQUEST["act"] == "inactivate" && !empty($_REQUEST['ids'])){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'deactivate');	
	$deactivateIds = implode(',',$_REQUEST['ids']);	
	$statusSql = "	UPDATE 'fs_incentive_participate_hotel'
						SET `status` = '0'
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` IN (".addslashes($deactivateIds).")";	
										
	if(executeSql($statusSql)){
		$err = 0;
		$_SESSION['successMsg'] = 'Selected records status has been inactivated sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Selected records status has not been inactivated sucessfully.';
	}	
}else if($_REQUEST["act"] == "delete" && !empty($_REQUEST['ids'])){}

// ----------cate---------
$sql = " SELECT * FROM `fs_incentive_participate_hotel` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' ";
if(!empty($_SESSION['hotel_access'])){
$sql .= " AND `id` in (".addslashes($_SESSION['hotel_access']).")";
}
/*if($_REQUEST['search_name'] != ''){
	//$sql .= " AND `name` LIKE '%".addslashes($_REQUEST['search_name'])."%'";
	$sql .= " AND `id` = '".addslashes($_REQUEST['search_name'])."'";
}
if($_REQUEST['status'] != ''){
	$sql .= " AND `status` = '".addslashes($_REQUEST['status'])."%'";
}
if($_REQUEST['hotel_category'] != ''){
	$sql .= " AND `hotel_category` = '".addslashes($_REQUEST['hotel_category'])."'";
}

if($_REQUEST['order'] != ''){
	$sql .= " ORDER BY `display_order` asc";
}else{
	$sql .= " ORDER BY `display_order` asc";
}*/
//echo $sql;
$sql .= " ORDER BY `id` asc";
$db->query($sql);
$numRows= $db->num_rows();
//$pagging = new pagingClass($sql,$setpage);
//$db->query($pagging->getQuery());
$total = $db->num_rows();
?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Incentive Participate Hotel Manager
        <small>Manage Incentive Participate Hotels</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Incentive Participate Hotel</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">		
	  <!------------------------------------->
      <div class="row">
        <div class="col-xs-12">		     
          <!-- /.box -->
        
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Add Incentive Participate Hotel </h3>
              
		<?php if($_SESSION['errorMsg']){?>
		 <p style="color:#ed9797;width: 700px;float: right;" class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
		<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
		<p style="color:#ed9797;width: 700px;float: right;" class="help-block">
		<?php echo messageSuc($_SESSION['successMsg']);?></p>
		<?php unset($_SESSION['successMsg']);}?>
	
            </div>
             
			<form name="listingincentiveHotelForm" id="listingincentiveHotelForm" method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off">
               <input type="hidden" value="" name="act" />
			     <div id="listingDiv"></div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table id="example2" class="table table-bordered table-striped">
               
                <tbody>
				
                <tr>
                  
                  <td style="width:270px;">Hotel Name</br><?php $hotelDropDown = '<select class="form-control select2"  name="hotel_id" id="hotel_id" '.$disabledHotel.' data-parsley-required onChange="incentiveparticipateHotel(this.value);">

														  <option value="">Select Hotel</option>';

														if(empty($_SESSION['hotel_access'])){

															$resCat = selectSql(TBL_HOTELS," where  id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');		

														  }else{

														  $resCat = selectSql(TBL_HOTELS," where  id_shop='".addslashes($_SESSION['shop'])."' and find_in_set(id,'".$_SESSION['hotel_access']."') ",' ORDER BY `name`');												}

														  if($db->num_rows2($resCat)){

															while($resultCat = $db->fetch_object2($resCat)){

																if($resultCat->id == $row->hotelId){

																	$selected = 'selected="selected"';

																}else if($_REQUEST['search_name']== $resultCat->id){

																	$selected = 'selected="selected"';

																}else{

																	$selected = '';

																}	

																$hotelDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).' - '.strtoupper($resultCat->city).'</option>';

															}

														  }

															echo $hotelDropDown .= '</select>';

														  ?></td>
				  <td colspan="4" rowspan="2"><div id="ListHotelParticipate"></div></td>
				
				  
				  
                  
                </tr>
               
			    
				             
				
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
      
      
      
      
      
      <!--------------------------------->
      <div class="row">
        <div class="col-xs-12">		     
          <!-- /.box -->
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Incentive Participate Hotel List</h3>
            </div>
			<form name="listingForm" action="" method="post">
               <input type="hidden" value="" name="act" />
			     <div id="listingDiv"></div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th width="10%"><!--<input type='checkbox' name='CheckAll' id="CheckAll" value='Check All' />--> S.No.&nbsp;</th>
                  <th>Hotel Name</th>
				  <th>Hotel Percentage</th>
				    <th>Executive Percentage</th>
                  <th>Status</th>
			
                </tr>
                </thead>
                <tbody>
				<?php 				 				
				if($total > 0){$counter = 1;
				  while($row = $db->fetch_object()){
				  $HoletListID	=	  $row->id;?>
                <tr>
                  <td><!--<input type="checkbox" name="ids[]" id="ids" value="<?=$row->id;?>"/>--> <?php echo $counter++;?>.&nbsp;</td>
                  <td><?=selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$row->hotel_id."'").' - '.strtoupper(selectColumn(TBL_HOTELS,'city'," WHERE `id` = '".$row->hotel_id."'"));?></td>
				  <td style="width:10%;" ><input readonly type="text" class="form-control"  name="display_order|<?php echo $HoletListID;	?>" id="display_order|'.$OtherChargesuniqueCode.'" value="<?php echo $row->hotel_percentage;   ?>"  onKeyUp="UpdateDisplayOrder(this.value,<?php echo $HoletListID;	?>);" style="width:60px;"></td>
				  
				  <td style="width:10%;" ><input  readonly type="text" class="form-control"  name="display_order|<?php echo $HoletListID;	?>" id="display_order|'.$OtherChargesuniqueCode.'" value="<?php echo $row->executive_percentage;   ?>"  onKeyUp="UpdateDisplayOrder(this.value,<?php echo $HoletListID;	?>);" style="width:60px;"></td>
				  
                  <td><?=$row->status=='1'?'<span onclick="location.href=\'incentiveParticipateHotel.php?inactiveId='.encryptor('encrypt',$row->id).'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'incentiveParticipateHotel.php?activeId='.encryptor('encrypt',$row->id).'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>			 
				  
                </tr>
               <?php }?> 
			    
				<tr>	 
					  <td align="right" colspan="5"><?php  //echo $pagging->getLinks();?> </td>
                 </tr>               
				<?php }else {?>
				
				 <tr>
                      <td height="200" align="center" colspan="5">---- No Record Found ---- </td>
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
 <!--########## Folloup Popup#######--> 
    <span class="my_popup_open" style="display:none;"></span>
    <div id="my_popup" class="well">
      <div id="FollowUpNextUpdate" > </div>
      <!-- <button id="my_popup_yes" class="my_popup_close btn btn-default pull-left">Yes</button>-->
      <button id="my_popup_no" style="margin-left: 5px;" class="my_popup_close btn btn-default pull-left">Close</button>
    </div>
  <script type="text/javascript">
 
 function submitIncentiveParticipateHotel(){
	 
	 
		 	
		var form=$("#listingincentiveHotelForm");

		if(form.parsley().validate()){
			$('.loading').show(); 

		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxIncentiveParticipateHotelUpdate.php',
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

			}



		});

		return false;

		}
	 }
 function incentiveparticipateHotel(hotel_id){
	
	
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxIncentiveParticipateHotel.php',
		data: 'hotel_id='+hotel_id, 
		success: function (result) {
				
				$( "#ListHotelParticipate" ).html(result); 
				
	 	}
	});
	}
 
 
 
 
 
  function UpdateDisplayOrder(SortValue,HoletListID){
	 
	  
	  $.ajax({
		   type: "POST",
		   url: 'ajax/ajaxUpdateDisplayOrder.php',
		   data: 'HoletListID='+HoletListID+'&SortValue='+SortValue, 
		   success: function (result) {			  	    
				//alert(result);
			}
		})
		
			
	 
	  }
  	function deleteMe(id,name){
  		var xhttp = new XMLHttpRequest();
  		  xhttp.onreadystatechange = function() {
  		    if (this.readyState == 4 && this.status == 200) {
  		    	console.log(this.responseText);
  		      if(this.responseText == 1){
  		      	alert("Transaction Found In the Table");
  		      }
  		      else{
  		      	if(confirm('Are you sure that you want to delete this record '+name+'?')){
  		      		window.location.href='manageHotels.php?delId='+id+'&action=delete&page=<?=$_REQUEST['page']?>';
  		      	}
  		      }
  		    }
  		  };
  		  xhttp.open("GET", "ajax/ajaxCheckCompanyDomain.php?id_hotel="+id, true);
  		  xhttp.send();
  	}
  </script> 

<?php include_once("includes/footer.php")?>  