<?php
include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'], TBL_DAILY_ENQUERY, 'view');

$formatFields = [
  'tss' => [
    'serial' => 'Serial',
	  'expiry_date'=>'Expiry Date'
    
  ],
  'mau'=>[
	  'serial' => 'Serial',
  	'expiry_date'=>'Expiry Date'

  ],
	'salesync'=>[
	  'serial' => 'Serial'
  	

  ],
	'webinar'=>[
	  'serial' => 'Serial'
  	

  ],
	
	'aws'=>[
	  'serial' => 'Serial',
  	  'expiry_date'=>'Expiry Date'

  ],
	'amc'=>[
	  'serial' => 'Serial',
  	'expiry_date'=>'Expiry Date'

  ],
	'cocloud'=>[
		 'sub_id' => 'Sub ID'
	]
	//add more if needed
];


$selectedFormat = $_GET['format_req'] ?? '';
$selectedCallType = $_GET['call_type'] ?? '';
$fieldsToShowInList = $formatFields[$selectedFormat] ?? [];




$sql = "";

if (!empty($selectedFormat) && !empty($selectedCallType)) {
$sql = "SELECT
    c.date_created, c.name, c.mobile, c.email, cd.extra_data,c.id,
    cd.id_user, cd.assign_user_id, cd.call_status,cd.call_remark,cd.followup_date,
	u1.name AS source_name,
    u2.name AS handle_name
FROM
    `call` AS c
LEFT JOIN
    `call_details` AS cd ON c.id = cd.call_id
LEFT JOIN
    `fs_users` AS u1 ON u1.id = cd.id_user
LEFT JOIN
    `fs_users` AS u2 ON u2.id = cd.assign_user_id	
WHERE
    c.`id_shop` = '" . addslashes($_SESSION['shop']) . "'
    AND cd.`format_type` = '".$selectedFormat."'
	AND cd.`id_list_name` = '".$selectedCallType."'
	";
	
	 if ($_SESSION['userLevel'] != 1) {
        $sql .= " AND (cd.id_user = '" . addslashes($_SESSION['userId']) . "' OR cd.assign_user_id = '" . addslashes($_SESSION['userId']) . "' OR cd.id IS NULL)";
    }
}


if (!empty($_REQUEST['name'])) {
  $sql .= " AND c.`name` LIKE '%" . addslashes($_REQUEST['name']) . "%'";
}

if (!empty($_REQUEST['mobile'])) {
  $sql .= " AND c.`mobile` LIKE '%" . addslashes($_REQUEST['mobile']) . "%'";
}

if (!empty($_REQUEST['serial'])) {
  $serialSearch = addslashes($_REQUEST['serial']);
  $sql .= " AND JSON_UNQUOTE(JSON_EXTRACT(cd.extra_data, '$.serial')) LIKE '%$serialSearch%'";
}

if (!empty($_REQUEST['source'])) {
  $sql .= " AND cd.`assign_user_id` = '" . addslashes($_REQUEST['source']) . "'";
}

if (!empty($_REQUEST['date_check']) && !empty($_REQUEST['generation_date'])) {
    $generation_date = $_REQUEST['generation_date'];
    $generation_date = explode(" to ", $generation_date);
    $from_date = $generation_date[0];
    $to_date = $generation_date[1];

    $sql .= " AND c.`date_created` >= '" . date('Y-m-d', strtotime($from_date)) . "' 
              AND c.`date_created` <= '" . date('Y-m-d', strtotime($to_date)) . "'";
}

if (isset($_REQUEST['call_status']) && $_REQUEST['call_status'] !== '') {
  $sql .= " AND cd.`call_status` = '" . addslashes($_REQUEST['call_status']) . "'";
}

$sql .= " AND (
    cd.id = (
        SELECT MAX(cd_inner.id)
        FROM `call_details` AS cd_inner
        WHERE cd_inner.call_id = c.id
		AND cd_inner.id_list_name = '" . addslashes($selectedCallType) . "'
    ) 
    OR cd.id IS NULL
)
ORDER BY c.`id` DESC";

$countSql = "SELECT COUNT(*) as total FROM (
    $sql
) AS total_table";

$db->query($countSql);
$totalRow = $db->fetch_assoc();
$numRows = $totalRow['total'];

$setpage = 100;
$pagging = new pagingClass($sql, $setpage);
$db->query($pagging->getQuery());
$total = $db->num_rows();
//$numRows = $total;
//echo $sql;
?>

<?php include_once("includes/header.php") ?>
<?php include_once("includes/left.php") ?>

<div class="content-wrapper">
	
	<style>
	.drawer {
  position: fixed;
  top: 50px; /* Match header height */
  right: 0;
  height: calc(100vh - 50px); /* Full screen minus header */
  width: calc(90vw - 230px); /* 230px = sidebar width */
  background-color: #fff;
  box-shadow: -2px 0 5px rgba(0, 0, 0, 0.3);
  transform: translateX(100%); /* Fully hidden */
  transition: transform 0.4s ease;
  z-index: 999;
  padding: 20px;
  overflow-y: auto;
}

.drawer.open {
  transform: translateX(0); /* Slide in */
}

.close-btn {
  position: absolute;
  top: 10px;
  left: 10px; /* Top-left corner */
  background: red;
  color: white;
  border: none;
  padding: 5px 10px;
  cursor: pointer;
  z-index: 1000;
}




</style>
	
	

	<script>
		const selectedCallIds = new Set();
		$(document).ready(function () {
  

  // Select all toggle
  $('#select-all').on('change', function () {
    
    const isChecked = $(this).is(':checked');
    $('.row-checkbox').prop('checked', isChecked);
    selectedCallIds.clear();

    if (isChecked) {
      $('.row-checkbox').each(function () {
        selectedCallIds.add($(this).val());
		  
      });
		
    }
  });

		
		$(document).on('change', '.row-checkbox', function () {
    const id = $(this).val();
    if ($(this).is(':checked')) {
      selectedCallIds.add(id);
		console.log(selectedCallIds);
    } else {
      selectedCallIds.delete(id);
      $('#select-all').prop('checked', false); // Uncheck "Select All" if any box is unchecked
		console.log(selectedCallIds);
    }
  });
	
			
});
		function openBulkAssPopup() {
			
			if (selectedCallIds.size === 0) {
      alert("Please select at least one row.");
      return;
			}
			
  $('#myPopupModal').modal('show');
  console.log("Selected Call IDs:", Array.from(selectedCallIds));
}
		
		
	
		
		function submitPopupForm() {
			console.log(selectedCallIds);
  const data = {
    dropdown1: $('#status').val(),
    dropdown2: $('#assUser').val(),
    textInput: $('#internal_remark').val(),
	  lists: $('#call_list').val(),
	  format_type: $('#format_type').val(),
	  current_list_id: $('#id_type').val()
  };
			console.log(data);
			$.ajax({
    url: 'ajax/ajaxBulkAss.php', 
    method: 'POST',
    data: {ids: Array.from(selectedCallIds),data:data},
    dataType: 'json',
    success: function (response) {
      if (response.success) {
        alert('Assigning Completed');
        $('#myPopupModal').modal('hide');
        $('#popupForm')[0].reset();
		  window.location.reload();
      } else {
        alert('Error: ' + response.message);
      }
    },
    error: function () {
      alert('Something went wrong while Assigning.');
    }
  });
}
		

		
		
		
		

	
		function addEqyFollowUp1(call_id,list_id,format){
		$.ajax({
			type: "POST",
		    url: 'ajax/ajaxAddAssign.php',
  		    data: {call_id:call_id, list:list_id, format_type:format}, 
			//data: 'call_id='+call_id,
 		    success: function (result) {		
					$('#OpenListPopUpshow').html(result);
				$('.datepickertest').datepicker({
            dateFormat: 'dd-mm-yy',
            minDate: 0
        });
  				    $('#OpenListPopUpshow').popup('show');
					
					
			},
			error:function (xhr, status, error) {
            alert('Failed to load assign form.');
            console.error('Assign AJAX error:', status, error);
        }
		});
	}
		
		function saveAddFollowupPopUpform(){
		var FollowupCoditionType	=	$("#FollowupCoditionType").val();
		var form=$("#AddFollowPopUpForm");
		var callId = $("#AddFollowPopUpForm input[name='call_id']").val();
		
		if(form.parsley().validate()){
			$('.loading').show(); 
			$.ajax({
			   type: "POST",
			   url: 'ajax/ajaxSaveAss.php',
			   data: form.serialize()+'&FollowupCoditionType='+FollowupCoditionType, 
			   dataType: 'json',
				timeout: 10000,
			   success: function (result) {
				   if (result.status === 'success') {
				   	if (FollowupCoditionType === 'addfollowup') {
                         $('#showFollowup').html('<div>' + result.message + '</div>');
                         window.location.reload();
                        //viewCall(result.call_id || callId);
					}
				   }else{
				   	alert('Error: ' + result.message);
				   }
				},
				
				error: function (xhr, status, error) {
                alert('Failed to save follow-up details. Please try again.');
                console.error('Save AJAX error:', status, error);
            },
            complete: function () {
                $('.loading').hide();
                $('#OpenListPopUpshow').popup('hide');
            }
			  	
			});
			return false;
		}
			
			
			
	}
		
		function closePopupAndRefresh() {
    $("#OpenListPopUpshow").popup("hide");
    }
		
				
function addPhonePop(callId) {
  // Reset form and show modal
  $('#addPhoneEmailForm')[0].reset();
  $('#addPhoneEmailForm').parsley().reset();
	$('#call_id').val(callId);
  $('#addPhoneEmailModal').modal('show');
}

function submitPhoneEmailForm() {
  var form = $('#addPhoneEmailForm');
  if (form.parsley().validate()) {
    $('.loading').show();
    $.ajax({
      url: 'ajax/ajaxAddPhoneEmail.php',
      type: 'POST',
      data: {
        call_id: $('#call_id').val(),
		new_name: $('#new_name').val(),
        new_phone: $('#new_phone').val(),
        new_email: $('#new_email').val()
      },
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          alert('Phone and email updated successfully!');
          $('#addPhoneEmailModal').modal('hide');
          // Refresh drawer content
			closeDrawer();
			console.log('drawer closed!')
			setTimeout(function() {
				const callId = $('#call_id').val();
				
           viewCall(response.callId || callId);
				
          }, 500);
          
        } else {
          alert('Error: ' + response.message);
        }
      },
      error: function(xhr, status, error) {
        alert('Failed to update phone and email.');
        console.error('AJAX error:', status, error);
      },
      complete: function() {
        $('.loading').hide();
      }
    });
  }
}

		
		
		function deleteCall(id,call_id,list_id){
			if(!confirm('Are you sure you want to delete this call?'))return;
		$.ajax({
			url:'ajax/ajaxDeleteCallRecord.php',
			type:'post',
			data: {id:id , call_id:call_id, list_id:list_id},
			dataType: 'json',
			success: function(response){
				if(response.success){
				alert('Call Record Deleted Successfully!');
					window.location.reload();
				}else{
				alert('Failed to delete call: ' + response.message);
				}
			},
			error: function(xhr, status, error){
			alert('An error occurred while deleting the call.');
      		console.log(error);
			}
		});
		}
		


  	
	</script>
	
	
	
  <section class="content-header" style="padding-top:15px;">
    <span class="float-right"> 
    
	          	  <?php 
				 $addCall=' <div class="btn-group  pull-right" style="margin-right:3px;">
		                  <a type="button" class="btn  nbtn" href="editCalls.php">Add Call</a>
		                  <button type="button" class="btn nbtn dropdown-toggle" data-toggle="dropdown">
		                    <span class="caret"></span>
		                    <span class="sr-only">Toggle Dropdown</span>
		                  </button>
		                 
		                  <ul class="dropdown-menu" role="menu">
		                  
		                 <li><a title="Import to excel file" href="UploadCall.php"  ><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Import</a></li>  
		                  </ul>
		           </div> ';

if($_SESSION['userLevel']==1) echo $addCall;
							 
							 ?>
	  
   </span>
	  <ol class="breadcrumb breadcrum2 float-left">

      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>

      <li class="active">Calls</li>

  
     </ol>
  </section>
	
  <section class="content" style="margin-top:20px;">
	  <div class="box-header with-border">

          <h3 class="box-title">Search <small>Total Records: (

            <?=$numRows;?>

			  ) &nbsp;</small> </h3> </div>
    <div class="box">
  <form method="get">
    <div class="box-body row">

      <!-- Row 1 -->
      <div class="col-md-3">
        <label>Select Format<span style="color:red;">*</span></label>
        <select class="form-control" name="format_req" id="format_type">
          <option value="">--Select Format--</option>
          <?php foreach ($formatFields as $key => $labelSet) { ?>
            <option value="<?= $key ?>" <?= ($selectedFormat == $key ? 'selected' : '') ?>><?= strtoupper($key) ?></option>
          <?php } ?>
        </select>
      </div>
		
      <div class="col-md-3">
		  <label>Select Call Type<span style="color:red;">*</span></label>
		  
        <select class="form-control" name="call_type" id="id_type">
          <option value="">--Select type--</option>
          
        </select>
      </div>
		
		<!-- Hidden trackers -->
<input type="hidden" id="last_format_type" value="">
<input type="hidden" id="selected_call_list" value="">
		
		<div class="col-md-3">
  			<label>Serial</label>
  			<input type="text" class="form-control" name="serial" value="<?= htmlspecialchars($_GET['serial'] ?? '') ?>">
		</div>


      <div class="col-md-3">
        <label>Name</label>
        <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($_GET['name'] ?? '') ?>">
      </div>

      <div class="col-md-3">
        <label>Mobile</label>
        <input type="text" class="form-control" name="mobile" value="<?= htmlspecialchars($_GET['mobile'] ?? '') ?>">
      </div>

      <!-- Row 2 -->
      <div class="col-md-3">
        <label>Source</label>
        <?php
        $sourceDropDown = '<select class="form-control select2" name="source">
          <option value="">Select Source</option>';
				   
        $resCat2 = selectSql(TBL_USERS, "where status='1' and id_shop='" . addslashes($_SESSION['shop']) . "' AND name!='' ", ' ORDER BY `name`');
        if ($db->num_rows2($resCat2)) {
          while ($resultCat2 = $db->fetch_object2($resCat2)) {
			  if($_SESSION['userLevel']!=1){
				  $selected = (($_REQUEST['source'] ?? $_SESSION['userId']) == $resultCat2->id) ? 'selected="selected"' : '';
			  }else{$selected = ($_REQUEST['source'] == $resultCat2->id) ? 'selected="selected"' : '';}

            $sourceDropDown .= '<option ' . $selected . ' value="' . $resultCat2->id . '">' . ucfirst($resultCat2->name) . '</option>';
          }
        }
        echo $sourceDropDown .= '</select>';
        ?>
      </div>

		
		
      <div class="col-md-3">
    	<label for="date-check">
			<input type="checkbox" name='date_check' id="date_check">
			Generation Date : From - To
		</label>
		  <div class="input-group">
		  <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
		  <input type="text" class="form-control pull-right dateRangeEdit" 
		   		 placeholder="Enter Generation date" 
				 id="generation_date" name="generation_date" disabled>
		  </div>
</div>
			

      <div class="col-md-3">
        <label>Status</label>
        <?php
        $selected1 = ($_REQUEST['call_status'] == '1') ? 'selected="selected"' : '';
        $selected0 = ($_REQUEST['call_status'] == '0') ? 'selected="selected"' : '';
        echo $statusDropDown = '<select class="form-control select2" name="call_status">
          <option value="">Both</option>
          <option ' . $selected1 . ' value="1">Open</option>
          <option ' . $selected0 . ' value="0">Close</option>
        </select>';
        ?>
      </div>
		
		  

      <!-- Row 3: Button -->
      <div class="col-md-12 text-left" style="margin-top: 20px;">
        <button class="btn btn-primary" type="submit">Search</button>
      </div>

    </div>
  </form>
</div>


    <div class="box">
	 <div class="box-header">
    <div class=" flex-wrap justify-content-start align-items-start mb-2" style="gap: 0.5rem;">
      <button class="btn btn-primary" onclick="openBulkAssPopup()">Assign</button>
      
    </div>
      <div class="box-body table-responsive">
        <table class="table table-bordered">
			
          <thead>
	

            <tr>
				<th>All<input type="checkbox" id="select-all"></th>
				<th>Generation Date</th>
              <?php foreach ($fieldsToShowInList as $field => $label) { ?>
				
                <th><?= $label ?></th>
              <?php } ?>
				<th>Source</th>
				<th>Customer Name</th>
    			<!--<th>Mobile</th>-->
    			<!--<th>Email</th>-->
				<th>Call Remarks</th>
				<th>Next Follow Up</th>
				<th>Handled By</th>
              <?php if (!empty($fieldsToShowInList)) { ?>
                <!--<th>Type Of Call</th>-->
				<th>Closed Type</th>
                <th>Status</th>
                <th>Action</th>
              <?php } ?>
            </tr>
          </thead>
          <tbody>
            <?php if ($total > 0 && !empty($fieldsToShowInList)) {
              while ($row = $db->fetch_object()) {
				  
				  //names for source and handleby
				  
				
				  
				 //////////////////////////////////////////////////////// 
				$sourceName = $row->source_name;
$handleName = $row->handle_name;
				  /////////////////////////////////
			
				  
				  $extraData = json_decode($row->extra_data, true);
				  
				  $followup_date = $row->followup_date;
				  
				  $remark = htmlspecialchars($row->call_remark);
				  $parts = explode(' - ', $remark);

                  $closedType  = trim($parts[0] ?? ''); // Confirmed
                  $comment = trim($parts[1] ?? ''); // renewed by us

$format_follow_up = (!empty($followup_date) && $followup_date !== '0000-00-00')
    ? date('d-M-Y', strtotime($followup_date))
    : '';
				  
                echo "<tr>";
				  echo '<td><input type="checkbox" class="row-checkbox" value="' . $row->id . '"></td>';
				  echo "<td>" . date("d-M-Y",strtotime($row->date_created)) . "</td>";
                foreach ($fieldsToShowInList as $field => $label) {
                  echo "<td>" . htmlspecialchars($extraData[$field] ?? '-') . "</td>";
                }
				   echo "<td>" . htmlspecialchars($sourceName) . "</td>";
				  echo "<td>" . htmlspecialchars($row->name) . "</td>";
    //echo "<td>" . htmlspecialchars($row->mobile) . "</td>";
    //echo "<td>" . htmlspecialchars($row->email) . "</td>";
				  echo "<td>" . htmlspecialchars($row->call_remark) . "</td>";
				  echo "<td>" . htmlspecialchars($format_follow_up) . "</td>";
				  
				   echo "<td>" .htmlspecialchars($handleName). "</td>";
                //echo "<td>";
                ?><!--
                 <select class="form-control" onchange="changeType(this, <?= $row->id ?>)">
                  <option value="0">--Select--</option>
					<option value="1" <?= ($row->calls_type == '1' ? 'selected' : '') ?>>Booking Query</option>
					<option value="2" <?= ($row->calls_type == '2' ? 'selected' : '') ?>>Lead</option>
					<option value="3" <?= ($row->calls_type == '3' ? 'selected' : '') ?>>General Query</option>
					<option value="4" <?= ($row->calls_type == '4' ? 'selected' : '') ?>>Accounts</option>
					<option value="5" <?= ($row->calls_type == '5' ? 'selected' : '') ?>>Marketing</option>
                </select> -->
			  
                <?php
					
               // echo "</td>";
					if($row->call_status==0){
						echo "<td>" .$closedType. "</td>";
                echo "<td class='text-danger'>Closed</td>";
						
					}else{
						echo "<td> - </td>";
					echo "<td class='text-success'>Open</td>";
					}
				  /*echo "<td><button class='btn btn-primary btn-sm mt-1 d-none' id='viewBtn_<?= $row->id ?>' onclick='viewCall(".$row->id.")'>View</button></td>";*/
				  
				  echo "<td><a id='viewBtn_{$row->id}' onclick='viewCall({$row->id})'><i class='fa fa-pencil-square-o'></i></a></td>";

				  
                echo "</tr>";
              }
              echo "<tr><td colspan=" . (count($fieldsToShowInList) + 3) . ">" . $pagging->getLinks() . "</td></tr>";
            } else {
              echo "<tr><td colspan=" . (count($fieldsToShowInList) + 3) . ">No Records Found</td></tr>";
            } ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>
	
	<!-- Drawer Starts Here -->
  
<div id="OpenListPopUpshow" class="well" style="display:none;"> </div>
	
	
	<!-- Add Phone and Email Popup Modal -->
<div class="modal fade" id="addPhoneEmailModal" tabindex="-1" role="dialog" aria-labelledby="addPhoneEmailModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="addPhoneEmailForm">
        <div class="modal-header">
          <h5 class="modal-title" id="addPhoneEmailModalLabel">Add Phone and Email</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
			 <div class="form-group">
			  <input type="hidden" id="call_id" name="call_id">
            <label for="new_phone">Name</label>
            <input type="text" class="form-control" id="new_name" name="new_name" placeholder="Enter Name">
          </div>
          <div class="form-group">
			  <input type="hidden" id="call_id" name="call_id">
            <label for="new_phone">Phone Number</label>
            <input type="text" class="form-control" id="new_phone" name="new_phone" placeholder="Enter phone number" data-parsley-pattern="^\+?[1-9]\d{1,14}$" data-parsley-error-message="Please enter a valid phone number">
          </div>
          <div class="form-group">
            <label for="new_email">Email</label>
            <input type="email" class="form-control" id="new_email" name="new_email" placeholder="Enter email" data-parsley-type="email" data-parsley-error-message="Please enter a valid email">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-success" onclick="submitPhoneEmailForm()">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Drawer -->
<div id="rightDrawer" class="drawer position-relative">
  <div id="drawerContent"><!-- AJAX content will be loaded here --></div>
</div>
	
	
		<!--Bulk assign to user pop-up-->
	<div class="modal fade" id="myPopupModal" tabindex="-1" role="dialog" aria-labelledby="popupModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="popupForm">
        <div class="modal-header">
          <h5 class="modal-title" id="popupModalLabel">Call Assign</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span>&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <div class="form-group">
			 <input id="format_type" type="hidden" value="<?=$selectedFormat?>">
            <label for="dropdown1">Status</label>
            <select class="form-control" id="status" name="status">
              <option value="">-- Select --</option>
              <option value="1">Open</option>
              <option value="0">Close</option>
            </select>
          </div>

         <div class="form-group">
  <label for="dropdown2">Select Assigning User</label>
  <select class="form-control" id="assUser" name="assign_user">
    <option value="">-- Select --</option>
    <?php
    $resUserLevel = selectSql(TBL_USERS, " WHERE `status` = '1' ", ' ORDER BY `name`');

    if ($db->num_rows2($resUserLevel)) {
      while ($resultUserLevel = $db->fetch_object2($resUserLevel)) {
        echo '<option value="' . $resultUserLevel->id . '">' . htmlspecialchars($resultUserLevel->name) . '</option>';
      }
    }
    ?>
  </select>
</div>

          <div class="form-group">
            <label for="textInput">Internal Remark For User</label>
            <input type="text" class="form-control" id="internal_remark" name="internal_remark" placeholder="Internel Remark">
          </div>
			
			<div class="form-group">
  <label for="call_list" class="mb-1" style="display: block; margin-bottom: 6px;">Select Call List</label>
  <select class="form-control select2" style="width:100%" id="call_list" name="call_list[]" multiple required>
    <?php
    $callLists = mysqli_query($connNew, "SELECT id, name FROM call_list_name WHERE format_type='$selectedFormat' AND status = 1 ORDER BY name");
    while ($row = mysqli_fetch_assoc($callLists)) {
      echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['name']) . '</option>';
    }
    ?>
  </select>
</div>
			
        </div>

        <div class="modal-footer">
          <button type="button" onclick="submitPopupForm()" class="btn btn-success">Submit</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>
	
	
	




	
	
</div>

	


<?php include_once("includes/footer.php") ?>

	<script>
	
	function viewCall(viewId) {
    const format = '<?= $selectedFormat ?>';
		const list = '<?=$selectedCallType?>'
    $('#drawerContent').html('Loading...');
		sessionStorage.setItem('currentCallId', viewId);
    $.ajax({
        url: 'ajax/ajaxDrawerContent.php',
        type: 'GET',
        data: { id: viewId, format: format, list:list },
        timeout: 10000,
        success: function (html) {
            $('#drawerContent').html(html);
            document.getElementById("rightDrawer").classList.add("open");
        },
        error: function () {
            $('#drawerContent').html('<div>Failed to load drawer content.</div>');
            alert("Failed to load drawer content.");
        }
    });
}

function openDrawer() {
  document.getElementById("rightDrawer").classList.add("open");
}
function closeDrawer() {
  document.getElementById("rightDrawer").classList.remove("open");
}
	
	$(document).ready(function(){
		$('#date_check').change(function(){
			if($(this).is(':checked')){
				$('#generation_date').prop('disabled', false);
			}else{
				$('#generation_date').prop('disabled', true);
			}
		});
		$('#generation_date').daterangepicker({
    autoUpdateInput: false,
    locale: {
      cancelLabel: 'Clear'
    }
  });

  $('#generation_date').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('MM/DD/YYYY') + ' to ' + picker.endDate.format('MM/DD/YYYY'));
  });

  $('#generation_date').on('cancel.daterangepicker', function(ev, picker) {
    $(this).val('');
  });
});
		

	$(document).ready(function () {
  const urlParams = new URLSearchParams(window.location.search);
  const viewId = urlParams.get('view_id');
  if (viewId) {
    viewCall(viewId);
	  
  }
});
		
	
/*  document.getElementById('format_type').addEventListener('change', function () {
    const formatType = this.value;
	
    const callListSelect = document.getElementById('id_type');
	  
	  
    // Clear existing options except the first one
    callListSelect.innerHTML = '<option value="">-- Select List --</option>';

    if (formatType !== '') {
      fetch('ajax/get_list_names.php?format_type=' + encodeURIComponent(formatType))
        .then(response => response.json())
        .then(data => {
          data.forEach(item => {
            const opt = document.createElement('option');
            opt.value = item.id;
            opt.textContent = item.name;
            callListSelect.appendChild(opt);
          });
        })
        .catch(error => {
          console.error('Error fetching call list:', error);
        });
    }
	  
  });*/

document.addEventListener('DOMContentLoaded', function () { 
  const formatSelect = document.getElementById('format_type');
  const callListSelect =document.getElementById('id_type');
  const selectedCallListInput = document.getElementById('selected_call_list');

  // Load call list if format is preselected (e.g., in edit mode)
  if (formatSelect.value !== '') { //alert('Load');
    //loadCallList(formatSelect.value, callListSelect.value);
	  loadCallList(formatSelect.value, '<?= $_REQUEST['call_type'] ?? '' ?>');
  }

  formatSelect.addEventListener('change', function () {
    const selectedFormat = this.value;
    selectedCallListInput.value = ''; // Clear retained call_list if format changes
    //loadCallList(selectedFormat, <?php //echo $_REQUEST['call_type']?>);
	  loadCallList(selectedFormat, callListSelect.value);
  });

  function loadCallList(formatType, preselectedCallListId) {
	  callListSelect.innerHTML = '<option value="">-- Select List --</option>';

    if (formatType === '') return;

    fetch('ajax/get_list_names.php?format_type=' + encodeURIComponent(formatType))
      .then(response => response.json())
      .then(data => {
        data.forEach(item => {
          const opt = document.createElement('option');
          opt.value = item.id;
          opt.textContent = item.name;
          if (String(item.id) === String(preselectedCallListId))
 {
            opt.selected = true;
          }
          callListSelect.appendChild(opt);
        });
      })
      .catch(error => {
        console.error('Error loading call list:', error);
      });
  }
});
		
</script>
	