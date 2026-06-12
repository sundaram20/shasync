<?php 
include_once("../../config/auto_loader.php");

?>
<style>
.itemName {
	z-index: 9001;
}
.error {
	color:#F00;
	font-size:12px;
}
.deleteBox {
	width: 35px;
	height: 35px;
	background-color: #fff;
	/* White background by default */
    display: flex;
	align-items: center;
	justify-content: center;
	cursor: pointer;
	transition: background-color 0.3s;
	border: 1px solid #d2d6de !important;/* margin-top : 7px; */

}
.deleteBox:hover {
	background-color: #db3434;/* Blue color on hover */
}
.deleteBox:active {
	background-color: #2980b9;/* Darker blue color when clicked */
}
.deleteBox i {
	color: #db3434;
	/* Blue color for the icon by default */
    font-size: 15px;
	transition: color 0.3s;
}
.deleteBox:hover i {
	color: #fff;/* White color for the icon on hover */
}
.deleteBox:active i {
	color: #fff;/* White color for the icon when clicked */
}
#EditReservationModal .modal-dialog {
	width: 100% !important;
	margin: 0 !important;
}
#EditReservationModal {
	padding: 0px !important;
	min-height: 100vh !important;
}
#EditReservationModal .modal-content {
	min-height: 100vh !important;
}
.select2-container--open .select2-dropdown {
	z-index: 1051; /* Adjust as needed */
}
.inventorySoldOut {
	z-index: -1; /* Adjust as needed */
}
.select2-container--open {
	z-index:9999999
}
</style>
<script>

$('.select2').each(function () {
    $(this).select2({
        dropdownParent: $(this).parent(),// fix select2 search input focus bug
    })
})

// fix select2 bootstrap modal scroll bug
$(document).on('select2:close', '.select2', function (e) {
    var evt = "scroll.select2"
    $(e.target).parents().off(evt)
    $(window).off(evt)
})

	$(".itemName").select2({ 
		width: '120' 
	});
</script>
<?php 
if($_REQUEST['editId']!=''){

$resState = executeSql("SELECT * from `fs_weeklyplanner` where  id='".addslashes($_REQUEST['editId'])."'");

	if(num_rows($resState) > 0){

		$row = $db->fetch_assoc2($resState)	;
			
			
		
	}

}

?>


<script>
<?php  
if($_REQUEST['editId'] != '' && $row['id_company']=='0'){ ?>
	
	$('#search_name').attr('data-parsley-required', 'false');
			$('#id_contacts').attr('data-parsley-required', 'false');
							
<?php } ?>	
</script>
<div id="viewincPopUp_wrapper" class="popup_wrapper popup_wrapper_visible" style="opacity: 1; visibility: visible; position: fixed; overflow: auto; z-index: 100001; width: 100%; height: 100%; top: 0px; left: 0px; text-align: center; display: block;">
  <div id="viewincPopUp" class="well popup_content" style="display: inline-block; opacity: 1; visibility: visible; outline: none; text-align: left; position: relative; vertical-align: middle;" data-popup-initialized="true" aria-hidden="false" role="dialog">
    <div id="EditweeklyplannerForm"> 
      <!--------------------------------Claim Incentive Start-------------------------------------->
      
      <form id="saveweeklyplanner" method="post" class="saveweeklyplanner" data-parsley-validate="" autocomplete="off">
       
        <input type="hidden" name="editId" id="editId" value="<?php echo $_REQUEST['editId'];?>">
       
        <div style="max-width:40em;">
        <div class="box-header with-border">
          <h3 class="box-title"><?php echo ucfirst($_REQUEST['set']);?> Planner</h3>
          <div class="box-tools pull-right">
            <button type="button" class="inventorySoldOut_close btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="form-group col-sm-12" style="background-color:#3C8DBC; color:#fff;"> </div>
        <div class="form-group col-sm-12">
          <input type="radio" name="weeklyplanner"  id="radion1" onClick="Selectweeklyplanner(1);" value="1" 
				 <?php if($_REQUEST['editId']==''){echo "checked";}else{if($row['type'] == 1){echo "checked";}}?> />
          1) Visit
          <input type="radio" name="weeklyplanner" id="radion12" onClick="Selectweeklyplanner(2);" value="2" <?php if($row['type'] == 2){echo "checked";}?>/>
          2) Other Activity </div>
		 <?php 
			 if($row['type']=='1'){
				
				$Type1 ='style="display:block;"';
			    $Type2 ='style="display:none;"';
			} 	
			if($row['type']=='2'){
				
				
			   $Type1 ='style="display:none;"';
			    $Type2 ='style="display:block;"';
			
			}
            	if($_REQUEST['editId']!=''){		            
              	$allocation_date=	date('d-m-Y',strtotime($row['allocation_date']));
				}else{
					$allocation_date=	date('d-m-Y',strtotime($_REQUEST['start']));
				}
			?>	
		<div class="form-group col-sm-12">
            <label style="float:left;">Date </label>
			
			 <input type="text" class="form-control datepickercheckin"  <?php echo $readonly; ?> placeholder="Enter Date" id="start_date" name="start_date" value="<?php echo $allocation_date;?>"  >
          </div>
			
			
        <div id="cars1" class="desc" <?php echo $Type1;?>>
          <div class="form-group col-sm-12">
            <label style="float:left;">Account </label>
            <select class="form-control select2"  name="user_account" id="user_account"  onChange="opencustom(this.value);" data-parsley-errors-container="#user_customError" data-parsley-required>
              <option value="1"  <?php if($row['id_account']=='1'){echo 'selected="selected"';} ?> >Existing Customer</option>
              <option value="2" <?php if($row['id_account']=='2'){echo 'selected="selected"';} ?> >New Account</option>
            </select>
          </div>
          <?php 
			 if($row['id_account']=='1'){
				
				$account1 ='style="display:block;"';
			    $account2 ='style="display:none;"';
			} 	
			if($row['id_account']=='2'){
				
				
			   $account1 ='style="display:none;"';
			    $account2 ='style="display:block;"';
			
			}
            						            
              		
              
			?>
          <div   <?php echo $account1;?> id="existing_customer">
            <div class="form-group col-sm-6">
              <label for="pkg_title" >Company Name - City </label>
              <select class="form-control select2 itemName" name="search_name" id="search_name"  onChange="getContactWeeklyPlanner(this.value,''); " data-parsley-errors-container="#search_nameError" data-parsley-required>
              </select><span id="search_nameError"><?php echo $err_search_name;?></span>
            </div>
            <div class="form-group col-sm-6">
              <label for="pkg_min_nights" >Company Contact</label>
              <select class="form-control select2" name="id_contacts" id="id_contacts" data-parsley-errors-container="#id_contactsError" data-parsley-required>
                <option value="">Company Contact</option>
              </select><span id="id_contactsError"><?php echo $err_id_contacts;?></span>
            </div>
          </div>
          <div  <?php echo $account2;?> id="new_customer" >  <div class="form-group col-sm-6">
      <label for="pkg_title" style="float:left;"> Name</label>
      <input type="text" class="form-control input-sm"  placeholder="Enter Contact Name" id="new_contact_name" name="new_contact_name" value="<?php echo $row['contact_name']?>">
    </div>
    <div class="form-group col-sm-6">
      <label for="pkg_min_nights" style="float:left;">Mobile</label>
      <input type="text" class="form-control input-sm" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  placeholder="Enter Mobile No" id="new_contact_mobile" name="new_contact_mobile" value="<?php echo $row['contact_mobile']?>">
    </div> </div>
          
          
          
        </div>
        <div id="cars2" class="desc" <?php echo $Type2;?> >
          <div class="form-group col-sm-12">
            <label for="pkg_extra_price" style="float:left;">Activity</label>
            <br/>
            <select name="other_activity_type"  id="other_activity_type" class="form-control select2">
              <option value="">Select Activity </option>
              <?php  $resultClose = selectSql(TBL_OTHER_ACTIVITY,"where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY name ');
				  

											  while($resultData = $db->fetch_object2($resultClose)){
											
													if($row['id_other_activity']	== $resultData->id){

														$selected2 = 'selected="selected"';

													}else{

														$selected2 = '';

													}

													echo $availableDatasales = '<option   '.$selected2.' value="'.$resultData->id.'">'.ucfirst($resultData->name).'</option>';

												}

											 

											 	 

												 

												 ?>
            </select>
          </div>
          
        </div>
        <div class="form-group col-sm-12">
            <label for="pkg_extra_price" style="float:left;">
            Description
            <div style="float:right;color:#888;margin-left:150px;"> Words left: <span id="word_left">15</span></div>
            </label>
            <textarea name="executive_remarks" id="executive_remarks" class="form-control" placeholder="Description" data-parsley-required="" automcomplete="off"><?php echo $row['description']; ?></textarea>
          </div>
        <div class="form-group col-sm-12" style="float:left;"> &nbsp;
          <button class="btn btn-danger" onclick="weeklyPlannerSave();" type="button"><?php echo ($_REQUEST['editId']==''?'Add':'Edit'); ?></button>
          <button class="inventorySoldOut_close btn btn-primary">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>
</div>
<script>
	  $( function() {	 

    
  $( ".datepickercheckin").datepicker({ dateFormat: 'dd-mm-yy', minDate: '-<?php echo $DateNoDays; ?>d' });
 
 
  } );
function weeklyPlannerSave(){
	
	
	
	/*var search_name = $('#search_name').val();
	var id_contacts = $('#id_contacts').val();
	
	var weeklyplanner = $('input[name="weeklyplanner"]:checked').val();//$('#weeklyplanner').val();
	var user_account = $('#user_account').val();
	
	var new_contact_name = $('#new_contact_name').val();
	var new_contact_mobile = $('#new_contact_mobile').val();
	var other_activity_type = $('#other_activity_type').val(); 
	if(weeklyplanner=='1'  && user_account=='1'){ 
		if(id_contacts=="" || search_name==""){
			 alert('Either Select Company or Contact.Both Can\'t be blank');
			 exit;
		}
	}
	if(weeklyplanner=='1' && user_account=='2'){
		if(new_contact_name=="" ){
			 alert('Fill Contact Name Can\'t be blank');
			exit;
		}
		if(new_contact_mobile==""){
			 alert('Fill Contact Mobile Can\'t be blank');
			exit;
		}
	}
	
	
	if(weeklyplanner=='2' ){
		if(other_activity_type=="" ){
			 alert('Select activity Can\'t be blank');
			exit;
		}
		
	}
	
	var executive_remarks = $('#executive_remarks').val();
	if(executive_remarks=="" ){
			 alert('Fill Description Can\'t be blank');
			exit;
		}*/
var form=$("#saveweeklyplanner");
if(form.parsley().validate()){
$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxSaveWeeklyPlanner.php',
		   data: form.serialize(), 
		   success: function (result) {
		   var data	= JSON.parse(result);
			
			   alert(data.Msg);
 		if(data.keystatus=='2'){  
			   $('#inventorySoldOut').popup('hide');
			 }else{
			// document.getElementById("saveIncentiveExistingLeadform").reset();
			//$('#saveIncentiveExistingLeadform').trigger("reset");
				 $('#search_name').val('');	
				 $('#search_name').val(null).trigger('change');
				 $('#other_activity_type').val(null).trigger('change');
	$('#id_contacts').val('');	
	$('#new_contact_name').val('');	
	$('#new_contact_mobile').val('');
				 $('#executive_remarks').val('');
			 }
$('#calendar').fullCalendar('refetchEvents');		
		   
		   
		   
		   }

		});
		return false;
		}
}
 //COMPANY AUTO COMPLETE START==================================================================
	comCheck = () =>{
		window.location.href='https://www.roomstatushub.in/sales/adminpanel/index.php';
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
 function getContactWeeklyPlanner(companyId,contactId){	

		$.ajax({

			   type: "GET",

			   url: 'ajax/ajaxContactsWeeklyPlanner.php',

			   data: 'companyId='+companyId+'&contactId='+contactId, 

			   success: function (result) {				   

			     $('#id_contacts').empty();

				 $('#id_contacts').html(result);

				 

				}

		});

}
 function opencustom(val){ 
		if(val==1){
			$("#new_customer").hide();
			$("#existing_customer").show();
			
									$("#search_name").attr('required','true');
									$("#id_contacts").attr('required','true');
									$("#new_contact_name").removeAttr('required');
					   				$("#new_contact_mobile").removeAttr('required');
			$('#new_contact_mobile').attr('data-parsley-required', 'false');
			$('#new_contact_name').attr('data-parsley-required', 'false');
		}
		else{
			$('#search_name').attr('data-parsley-required', 'false');
			$('#id_contacts').attr('data-parsley-required', 'false');
			
			$("#existing_customer").hide();
			$("#new_customer").show();
						$("#new_contact_name").attr('required','true');
						$("#new_contact_mobile").attr('required','true');			
						$("#search_name").removeAttr('required');
					   $("#id_contacts").removeAttr('required');


		}
	}
	
	 $(document).ready(function() {
  $("#executive_remarks").on('keyup', function() {
    var words = 0;

    if ((this.value.match(/\S+/g)) != null) {
      words = this.value.match(/\S+/g).length;
    }

    if (words > 15) {
      // Split the string on first 200 words and rejoin on spaces
      var trimmed = $(this).val().split(/\s+/, 15).join(" ");
      // Add a space at the end to make sure more typing creates new words
      $(this).val(trimmed + " ");
    }
    else {
      $('#display_count').text(words);
      $('#word_left').text(15-words);
    }
  });
}); 
	function onLoadIdCompany(id){	  //alert('123');
	 $.ajax({	  
	   url: "ajax/ajaxSearchCompanyName.php?id_company="+id,
	   dataType: 'json',
          delay: 1,
	  success: function(data){
		
                var id = data[0].id;
                var companyname = data[0].text;
				var tr_str = "<option value=" + id +">" + companyname + "</option>" ;
				$("#search_name").append(tr_str);
				//$("#CompanyGroupDetails").html(companyname);
					    
          }           
	})

	}
</script>
<script>
<?php  
if($_REQUEST['editId'] != '' && $row['id_company']!='0'){ ?>
	
	onLoadIdCompany(<?php echo $row['id_company']; ?>);
	getContactWeeklyPlanner(<?php echo $row['id_company']; ?>,<?php echo $row['id_contact']; ?>);

							
<?php } ?>	
</script>