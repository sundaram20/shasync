<?php include_once("../config/auto_loader.php");

checkUserLevelPermission($_SESSION['userLevel'],TBL_AGENT_ACHIEVED,'add');

/////////////////////////////////////////////////////////////////////////////////////

$unitUser = selectColumn(TBL_USERS,'user_type','WHERE id="'.$_SESSION['userId'].'" '); 

if($_SESSION['userLevel'] !=1){
  $perSql="SELECT * FROM `fs_user_levels` WHERE id=".$_SESSION['userLevel']." AND id_shop=".$_SESSION['shop']." ";
  $resPer = mysqli_query($connNew,$perSql);

  if($resPer){
      $perData  = mysqli_fetch_object($resPer);
      if($perData->calendar_user_list_approved == 0){
       $UserRestriction =" AND id='".$_SESSION['userId']."'"; 
      }
  }
}

if($_SESSION['teamMembers'] !=""){
  $teamMembers = "AND id IN (".$_SESSION['teamMembers'].")";
}
else{
  $teamMembers ="";
}

// ----------cate---------

if(!empty($_REQUEST['id']) && $_REQUEST['action']=='edit'){

	$sql = "  SELECT * FROM `".TBL_RATE."`

								WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";

	//$sql = "SELECT * FROM `".TBL_RATE."` as a join `".TBL_RATE_DETAILS."` as b where b.`id` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."'  AND a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND a.id=b.rate_id ";

	$db->query($sql);

	if($db->num_rows() > 0){	

	

		$row = $db->fetch_object();

	}						

}	

	

if($_REQUEST['id'] !=''){

	$disabled = 'disabled="disabled"';

}						





?>

<?php include_once("includes/header.php")?>

<?php include_once("includes/left.php")?>



<div class="content-wrapper">

  <!-- Content Header (Page header) -->

  <section class="content-header">

    <h1> Company Monthly Achievement <small></small> </h1>

    <ol class="breadcrumb">

      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>

      <li class="active">Company Monthly Achievement</li>

    </ol>

  </section>

  <!-- Main content -->

  <section class="content">

    <div class="row">

      <!-- left column -->

      <div class="col-md-12">

        <!-- general form elements -->

        <div class="nav-tabs-custom">

        

 <style>

 table {

    border-collapse: collapse;

}

th, td {

    padding:3px !important;

}

 

 

 </style>



<?php
//debugData(encryptor('decrypt',$_REQUEST['hotelId']));
//debugData(encryptor('decrypt',$_REQUEST['id']));


?>

          <div class="box-header with-border">

            <h3 class="box-title"><?php echo $_REQUEST['id']==''?'Add':'Edit'?> Achieved <?php echo $row->rate_name.'-'.$row->sub_code;  ?> </h3>

             </div>

          <!-- /.box-header -->

          <!-- form start -->		 

          <div class="form-group has-error" align="center" >

            <?php if($_SESSION['errorMsg']){?>

            <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>

            <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>

            <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>

            <?php unset($_SESSION['successMsg']);}?>

          </div>

          <form name="rateMaster" id="rateMaster"  method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off">           

            <input type="hidden" value="<?php echo $_REQUEST['id'];?>" name="id" id="id" />

            <div class="box-header">

			  <div class="row">

			  

            

                  

				 <div class="form-group col-sm-3">

                  <label for="seasonId">Season<font color="#FF0000">*</font></label>

                  <?php $seasonDropDown = '<select class="form-control select2" name="seasonId" id="seasonId" data-parsley-errors-container="#seasonError"
                   onchange="getbudgetyear(this.value); budgetAchievedMasterFunction();" data-parsley-required  >

											  <option value="">Select Season</option>';

											  $resCat = selectSql(TBL_BUDGET_YEAR," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');

											  if($db->num_rows2($resCat)){

											  	while($resultCat = $db->fetch_object2($resCat)){

													if($resultCat->id == encryptor('decrypt',$_REQUEST['id'])){

														$selected = 'selected="selected"';

													}else{

														$selected = '';

													}	

													$seasonDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';

												}

											  }

											 	echo $seasonDropDown .= '</select>';

											  ?>

                  <span id="seasonError"><?php echo $err_season;?></span> </div>

				  <div class="form-group col-sm-2">

                  <label for="start_date">Start Date</label>

                  <div class="input-group">

                    <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>

                    <input type="text" class="form-control " id="start_date" name="start_date" value="<?php if($row->start_date){echo date('d-m-Y',strtotime($row->start_date));}else{ echo date("d-m-Y");}  ?>" data-parsley-required data-parsley-errors-container="#start_dateError" <?php echo $disabled; ?> readonly>

                    <input type="hidden" class="form-control " id="start_date" name="start_date" value="<?php if($row->start_date){echo date('d-m-Y',strtotime($row->start_date));}else{ echo date("d-m-Y");}  ?>" data-parsley-required data-parsley-errors-container="#start_dateError"  readonly="true">

                  </div>

                  <!-- /.input group -->

                  <span id="start_dateError"><?php echo $err_start_date;?></span> </div>

                <div class="form-group col-sm-2">

                  <label for="end_date">End Date </label>

                  <div class="input-group">

                    <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>

                    <input type="text" class="form-control " id="end_date" name="end_date" value="<?php if($row->start_date){echo date('d-m-Y',strtotime($row->end_date));}else{ echo date('d-m-Y',strtotime("+1 days"));} ?>" data-parsley-required data-parsley-errors-container="#end_dateError"  <?php echo $disabled; ?> readonly>

                    <input type="hidden" class="form-control " id="end_date" name="end_date" value="<?php if($row->start_date){echo date('d-m-Y',strtotime($row->end_date));}else{ echo date('d-m-Y',strtotime("+1 days"));} ?>" data-parsley-required data-parsley-errors-container="#end_dateError"   readonly="true">

                  </div>

                  <!-- /.input group -->

                  <span id="end_dateError"><?php echo $err_end_date;?></span> </div> 

			  </div>

              <div class="row">

                

					<div class="form-group col-sm-3">

					  <label for="hotelId">Users <font color="#FF0000">*</font></label>

					  <?php $hotelDropDown = '<select class="form-control select2" name="hotelId" id="hotelId" data-parsley-errors-container="#hotelError" onchange="getRoom(this.value,1); selectcheckUserType(this.value);  " data-parsley-required>

												  <option value="">Select User</option>';

												  

												  if(empty($_SESSION['hotel_access'])){

													$resCat = selectSql(TBL_USERS," where status='1' AND `sales_status_active` = '1' and id_shop='".addslashes($_SESSION['shop'])."' ".$teamMembers." ".$UserRestriction." ",' ORDER BY `name`');		

												  }else{

												  $resCat = selectSql(TBL_USERS," where status='1' AND `sales_status_active` = '1' and id_shop='".addslashes($_SESSION['shop'])."' ".$teamMembers." ".$UserRestriction." ",' ORDER BY `name`'); 											}

												  if($db->num_rows2($resCat)){

													while($resultCat = $db->fetch_object2($resCat)){

													if(encryptor('decrypt',$_REQUEST['hotelId'])== $resultCat->id){

															$selected = 'selected="selected"';

														}else{

															$selected = '';

														}	

														$hotelDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';

													}

												  }

													echo $hotelDropDown .= '</select>';

												  ?>

					  <span id="hotelError"><?php echo $err_hotel;?></span> </div>

					  
<div id="displayHotelID">
					  <?php if($unitUser==2){ 
					  //WHERE id IN (".$_SESSION['hotel_access'].")
                $hotSql = "SELECT id,CONCAT(name,', ',city) AS name FROM ".TBL_HOTELS." WHERE id IN (".$_SESSION['hotel_access'].")";
                $hotRes = mysqli_query($connNew,$hotSql);
              ?>
            <div class="col-md-4">
              <label>Select Hotel</label>

              <select onChange="budgetAchievedMasterFunction();" data-parsley-errors-container="#id_hotelError" name="id_hotel" id="id_hotel" class="select2 form-control" data-parsley-required>
                <option value="">Select Hotel</option>
                <?php
                while($hotRow = mysqli_fetch_object($hotRes)){

                  if(encryptor('decrypt',$_REQUEST['id_hotel'])==$hotRow->id){
                    $selected='selected="selected"';
                  }
                  else{
                    $selected="";
                  }

                  echo "<option ".$selected." value='".$hotRow->id."'>".$hotRow->name."</option>";
                }
                ?>
              </select><span id="id_hotelError"><?php echo $err_id_hotel;?></span>
            </div>
          <?php }  ?>

				</div>
				<div class="clearfix"></div>	

            

				 

				

				

                

				

				

				<?php $data= json_decode($row->rate_points,true); ?>

				

                

				

              </div>

            </div>

          </form>

          <form name="form1" id="rateUpdate"  method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off">

            <div class="box-body no-padding text-center loading" >

              <button type="button" class="btn btn-default btn-lrg ajax" title="Loading..."> <i class="fa fa-spin fa-refresh"></i>&nbsp; Loading... </button>

            </div>

			

            <div class="box-body box-primary" id="rateMasterDetail"> </div>

            

            

                

                

            <!-- /.box-body -->

          

              

              

              

                            <br/>

           <?php /*?> <div class="box-footer ">

              <input type='hidden' value='<?=($_REQUEST['id']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >

              <input type='button' value='<?=($_REQUEST['id']==''?'Add':'Edit')?>' class="btn btn-primary" onclick="submitBudgetAchievedForm();"  >

              &nbsp;&nbsp;&nbsp;&nbsp;

              <input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("manageAgentAchieved.php?page=<?php echo $_GET['page']; ?>"); '>
             
            </div><?php */?>

          </form>

        </div>

        <!-- /.box -->

      </div>

    </div>

    <!-- /.row -->

  </section>

  <!-- /.content -->

</div>



<!--save data in popup-->

<div id="fadeandscale" class="well">

  <form id="popupForm" >

    <input type="hidden" id="roomEid" >

    <input type="hidden" id="ratePlanId" >

    <input type="hidden" id="tarrifId" >

    <input type="hidden" id="tarrifName" >

	<input type="hidden" id="planType" >

    <div class="form-group">

      <label for="tarrif">Tarrif Price</label>

      <input type="text" class="form-control input-sm" placeholder="Enter tarrif price" id="tarrif" name="tarrif" value="0" data-parsley-required data-parsley-type="digits">

    </div>

    <div class="form-group">

      <label for="meal">Meal Price</label>

      <input type="text" class="form-control input-sm" placeholder="Enter meal price" id="meal" name="meal" value="0" data-parsley-required data-parsley-type="digits" readonly>

    </div>

    <button class="fadeandscale_close btn btn-default" onclick="SavePopup();">Save</button>

    <button class="fadeandscale_close btn btn-default">Close</button>

  </form>

</div>



<!--show msg in popup-->

<span class="my_popup_open" style="display:none;"></span>

<div id="my_popup" class="well">

    <div id="rateUpdateData"></div>

    <button class="my_popup_close btn btn-default pull-right">Close</button>

</div>





<!--show msg in popup-->

<div id="ratePoint" class="well" style="display:none;">

	<form id="ratePointForm" autocomplete="off">

    <div id="ratePoinData"></div>

    

	</form>

	<button class="ratePoint_close btn btn-primary pull-right" onclick="SaveRatePointPopup();">Add</button>

</div>











<!--create pkg popup for ----->

	









<!--create new pkg row popup  for add --- currently hidden -->

	











<?php if($_REQUEST['id']!=''){ ?>

<script>

window.onload = function() {budgetAchievedMasterFunction(); }



</script>

<?php } ?>

<?php include_once("includes/footer.php")?>

<script type="text/javascript">

 

  getbudgetyear('<?php echo encryptor('decrypt',$_REQUEST["id"])?>');
  function budgetAchievedMasterFunction() {


 //if ( $.fn.DataTable.isDataTable('#example2') ) {
  //$('#example2').DataTable().destroy();
//	}

//$('#example2 tbody').empty();
    var form=$("#rateMaster");

    if(form.parsley().validate()){

     $('.loading').show(); 

    $.ajax({
       type: "POST",
       url: 'ajax/ajaxAgentBudgetAchieved.php',
       data: form.serialize(), 
       success: function (result) {
       
       
        if(result!=''){

        $('#rateMasterDetail').html(result);

        }

      },

      complete: function(){

      $('.loading').hide();

      }

  });

  }else {
 $('#example2').DataTable().clear();
	 $('#example2').DataTable().destroy();
    $('#rateMasterDetail').html('');

  }

    return false;

  }
function submitBudgetAchievedForm_1(){

   
  $("#rate_points").attr('data-parsley-required',true);

    var form=$("#rateMaster");

    var form1=$("#rateUpdate");

    var dataString = $("#rateMaster, #rateUpdate").serialize();

    

    if(form.parsley().validate() && form1.parsley().validate()){

      $.ajax({

         type: "POST",

         url: 'ajax/ajaxAgentAchievedBudgetUpdate.php',

         data: dataString, 

         success: function (result) {   

            $( ".my_popup_open" ).click();      

          $( "#rateUpdateData" ).html(result);   
         // window.onload = function() {budgetAchievedMasterFunction(); }
         //budgetAchievedMasterFunction();
         setTimeout(function(){budgetAchievedMasterFunction(); },1000)   ;

         //setTimeout(function(){location.href="editAgentAchieved.php";},1000)   ;

         // setTimeout(function(){location.href="manageAgentAchieved.php";},1000)   ;

          //$("#hotelId").val('1').attr('selected','selected');         

        }

      })

    }

  }

  function submitBudgetAchievedForm(){
 

  /*$("#rate_points").attr('data-parsley-required',true);

    var form=$("#rateMaster");

    var form1=$("#rateUpdate");

    var dataString = $("#rateMaster, #rateUpdate").serialize();

    

    if(form.parsley().validate() && form1.parsley().validate()){

      $.ajax({

         type: "POST",

         url: "<?php echo $url ; ?>",

         data: dataString, 

         success: function (result) {   

            $( ".my_popup_open" ).click();      

          $( "#rateUpdateData" ).html(result);   

         
          setTimeout(function(){location.href="manageAgentAchieved.php";},1000)   ;

          //$("#hotelId").val('1').attr('selected','selected');         

        }

      })

    }*/
		$( ".my_popup_open" ).click();      		
		$( "#rateUpdateData" ).html('User Budget has been updated sucessfully.');  
		setTimeout(function(){location.href="manageAgentAchieved.php";},1000)   ;
  }

  function getTotal(value,id,hidden_id){
    var prevValue = $("#hiddenBox"+hidden_id).val();
    var total=$('#totalRow'+id).val();
    $('#totalRow'+id).val(Number(total-prevValue)+Number(value));
    var grandTotal = $("#grandTotal").val();
    $("#grandTotal").val(Number(grandTotal-prevValue)+Number(value));
    
  }

  function getTotalVer(value,id,hidden_id){
    var prevValue = $("#hiddenBox"+hidden_id).val();
    //console.log(prevValue);
    var total=$('#totalCol'+id).val();
    $('#totalCol'+id).val(Number(total-prevValue)+Number(value));
    $("#hiddenBox"+hidden_id).val(value);
  }
  
  function updateRowValue(value,userId,id_company,seasonId,DateValue){
  //alert('RSO');	  
 
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxAgentAchievedUpdateRow.php',
		data: 'value='+value+'&id_company='+id_company+'&seasonId='+seasonId+'&userId='+userId+'&DateValue='+DateValue, 
		success: function (result) {			
				//$( ".my_popup_open" ).click();
			//	$( "#rateUpdateData" ).html(result);   
			resultArray = result.split('####');					
          
			$('#totalCol_'+id_company).val(resultArray[1])
			$('#totalCol'+DateValue).val(resultArray[2])		
			$( ".my_popup_open" ).click();
			$( "#rateUpdateData" ).html(resultArray[0]); 
				
	 	}
	});

  }
function selectcheckUserType(value){
	 $('#equictntbl').DataTable().clear();
	 $('#equictntbl').DataTable().destroy();
	$('#rateMasterDetail').html('');
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxSelectUserTypeListHotel.php',
		data: 'value='+value, 
		dataType:'JSON',
		success: function (result) {
				//console.log(result,'My data');
				if(result.user_type!=2){
					$("#displayHotelID").html('');
					budgetAchievedMasterFunction();
				}
				$("#displayHotelID").html(result.data);
				
	 	}
	});
	
	}
 function updateRowValueUnit(value,userId,id_company,seasonId,DateValue,id_hotel){
  //alert('UNIT');
 //alert(value);alert(userId);alert(id_company);alert(seasonId);alert(DateValue);alert(id_hotel);
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxAgentAchievedUpdateRowUnit.php',
		data: 'value='+value+'&id_company='+id_company+'&seasonId='+seasonId+'&userId='+userId+'&DateValue='+DateValue+'&id_hotel='+id_hotel, 
		success: function (result) {
				resultArray = result.split('####');		
          
			$('#totalCol_'+id_company).val(resultArray[1])
			$('#totalCol'+DateValue).val(resultArray[2])		
			$( ".my_popup_open" ).click();
			$( "#rateUpdateData" ).html(resultArray[0]);
				
	 	}
	});

  }
function updatecompanybudget(id_company){
	var selectuserid = $("#selectuserid").val();
	var selectseasonId = $("#selectseasonId").val();
	var id_hotel = $("#id_hotel").val();
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxListSingleCompanyBudget.php',
		data: 'id_company='+id_company+'&selectuserid='+selectuserid+'&selectseasonId='+selectseasonId+'&id_hotel='+id_hotel, 
		success: function (result) {
				
				$( "#Listbudgetvalue" ).html(result); 
				
	 	}
	});
	}
</script>
<script type="text/javascript">
  $(document).ajaxStart(function(){
    $("#loaderAni").html("Please wait...");
  });

  $(document).ajaxComplete(function(){
    $("#loaderAni").html("");
  });
</script>
