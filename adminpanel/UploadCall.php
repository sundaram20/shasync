<?php
include_once("../config/auto_loader.php");

checkUserLevelPermission($_SESSION['userLevel'], TBL_DAILY_ENQUERY, 'view');
?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>
<!--<style>
/*.file-upload { display: none; } */
</style>-->
<div class="content-wrapper">
  <section class="content-header">
    <h1> Call Manager <small>Call Master</small> </h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Calls</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="nav-tabs-custom">
          <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
          <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

          <div class="form-group has-error" align="center">
            <?php if ($_SESSION['errorMsg']) { ?>
              <p class="help-block"><?php echo messageError($_SESSION['errorMsg']); ?></p>
              <?php unset($_SESSION['errorMsg']); } elseif ($_SESSION['successMsg']) { ?>
                <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']); ?></p>
                <?php unset($_SESSION['successMsg']); } ?>
          </div>

          <div class="row progbar">
            <input type="radio" name="bright" checked class="profile" id="profile">
            <input type="radio" name="bright" class="settings" id="settings">
            <input type="radio" name="bright" class="posts" id="posts">
            <div class="head">
              <ul class="nav">
                <li class="st st1 active">
                  <h2 class="inner">Step 1: Select Call</h2>
                </li>
                <li class="st st2">
                  <h2 class="inner">Step 2: Validate</h2>
                </li>
                <li class="st st3">
                  <h2 class="inner">Step 3: Submit</h2>
                </li>
              </ul>
              <div class="line">
                <span></span>
              </div>
            </div>
            <div class="content">
              <section class="profile-section">
                <span><i class="fa fa-house"></i></span>
                <div id="uform">
                  <form id="image_form" enctype="multipart/form-data">
                                  <input type="hidden" value="1" name="searchFormSubmit" />
                                      <div class="box-body">
                                          <div class="row">
											  
											   <div class="form-group col-sm-6">
        <label for="format_type">Select Format</label>
       <select name="format_type" id="format_type" class="form-control" required>
          <option value="">-- Select Format --</option>
          <option value="tss">TSS</option>
          <option value="mau">MAU</option>
		   <option value="salesync">Sale sync</option>
		   <option value="webinar">Webinar Call</option>
		   <option value="aws">AWS Subscription</option>
		   <option value="amc">AMC</option>
		   <option value="cocloud">Co-Cloud</option>
          <!--<option value="format_c">Format C</option>-->
        </select>
      </div>
											  
		<div class="form-group col-sm-6">
        <label for="format_type">Call List Name</label>
       <select name="call_list" id="id_type" class="form-control" required>
          <option value="">-- Select List --</option>
          
        </select>
			<!--<div id="EditContactName" class="input-group-addon bookedby_open" ><i class="fa fa-pencil"></i></div>-->
			<div id="addTyp" class="input-group-addon bookedby_open"><i class="fa fa-plus"></i></div>
      </div>
											  
											  
                                                <div class="form-group col-sm-6" id="uploadWrapper" style="display: none;">
                                                     <label for="terminalId">Upload Call</label>
                                                    <input type="file" name="files" id="files" multiple >
                                                    
                                                </div>
                                               
                                          </div><!-- end od row-->

                                          <div class="box-footer">
                                                  <input id="submit_button" style="display: none;" class="btn btn-primary barsubmit"  type="submit" name="submit" is="submit" value="Upload" />
                                        
                                         </div>
                                      </div><!--end of box-body-->

                              </form>
                </div>
              </section>
              <section class="account-section">
                <span><i class="fa fa-house"></i></span>
                <div id="validateform" class="text-center">
                  <input type="hidden" name="id_call_request" id="id_call_request" value="" />
                  <button class="btn btn-primary valinv" onclick="listcall();">Validate</button>
                  <button class="btn btn-success valsubmit" style="display:none;" id="importCall" onclick="InsertCall();">Submit</button>
                </div>
                <br/>
                <div class="box">
                  <div class="">
                    <h4 class="mb-0 text-center"><b>Call List</b></h4>
                  </div>
                  <form name="listingForm" action="" method="post">
                    <input type="hidden" value="" name="act" />
                    <div id="listingDiv"></div>
                    <div class="box-body table-responsive">
                      <div id="listuploadData2"></div>
                    </div>
                  </form>
                </div>
              </section>
              <section class="post-section">
                <div class="progsuccess">
                  <h1 class="text-success" style="font-size:22px;">Uploaded Successfully !!!</h1>
                  <div id="InsertStatus" style="font-size:20px; text-align:center;"></div>
                </div>
              </section>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
	
	<div class="modal fade" id="callTypeModal" tabindex="-1" role="dialog" aria-labelledby="callTypeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <form id="addCallTypeForm">
        <div class="modal-header">
          <h5 class="modal-title" id="callTypeModalLabel">Add Call Type</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <label for="callTypeName">Call Type Name</label>
          <input type="text" class="form-control" name="callTypeName" id="callTypeName" required>
        </div>
		  <div class="modal-body">
			  <label for="callTypeName">Call Format<span style="color:red;">*</span></label>
          		<select name="format" id="format" class="form-control" required>
          <option value="">-- Select Format --</option>
          <option value="tss">TSS</option>
          <option value="mau">MAU</option>
		<option value="salesync">Sale sync</option>
					<option value="webinar">Webinar Call</option>
					<option value="aws">AWS Subscription</option>
					<option value="amc">AMC</option>
					 <option value="cocloud">Co-Cloud</option>
          <!--<option value="format_c">Format C</option>-->
        </select>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Add</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>
	
</div>

<script>
$(document).ready(function () {
  // Show modal when plus button is clicked
  $('#addTyp').on('click', function () {
    $('#callTypeModal').modal('show');
  });

  // Handle form submit
  $('#addCallTypeForm').on('submit', function (e) {
    e.preventDefault();

    var callTypeName = $('#callTypeName').val().trim();
	var format_type = $('#format').val().trim();
    if (callTypeName === '') {
      alert("Call type name cannot be empty.");
      return;
    }else if(format_type === ''){
		alert("Please Select a Format For Your List");
		return;
	}

    // Ajax to save
    $.post('ajax/addCallType.php', { callTypeName: callTypeName, format_type: format_type }, function (response) {
      if (response.status === 'success') {
        $('#id_type').append('<option value="' + response.id + '">' + callTypeName + '</option>');
        $('#id_type').val(response.id); // select the new type
        $('#callTypeModal').modal('hide');
        $('#callTypeName').val('');
		$('#format').val('');
		  
		  $('#format_type').val('');
		  $('#id_type').val('');
		  
      } else {
        alert('Error: ' + response.message);
      }
    }, 'json');
  });
});
	
	document.getElementById('format_type').addEventListener('change',function(){
		const formatType = this.value;
		const callListSelect = document.getElementById('id_type');
		
		callListSelect.innerHTML = '<option value="">-- Select List --</option>';
		
		if(formatType != ''){
			fetch('ajax/get_list_names.php?format_type=' + encodeURIComponent(formatType))
			.then(response=>response.json())
			.then(data=>{
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
	});
	
	document.getElementById('files').addEventListener('change',function(){
		const uploadBtn = document.getElementById('submit_button');
		if(this.files.length > 0){
			uploadBtn.style.display = 'block';
		}else{
			uploadBtn.style.display = 'none';
		}
	})
	
</script>


<script>
$(document).ready(function() {
 

  $('#image_form').submit(function(e) {
    e.preventDefault();
	   const form = document.getElementById('image_form');
	  
  const formData = new FormData(form);
    $.ajax({
      url: "ajax/ajaxUploadCall.php",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function(data) {
        data = JSON.parse(data);
        if (data.status == '1') {
          $('#uform').hide();
          $('#validateform').show();
          $('#id_call_request').val(data.id);
          $('form :input').val('');
        } else {
          $('form :input').val('');
          alert(data.Msg);
          $('#id_call_request').val('');
        }
      }
    });
  });
});

function InsertCall() {
  var id_call_request = $("#id_call_request").val();
	$('#InsertStatus').html('Importing... Please wait.');
  $.ajax({
    type: "POST",
    url: 'ajax/ajaxInsertCall.php',
    data: 'id_call_request=' + id_call_request,
    success: function(result) {
      data = JSON.parse(result);
      if (data.count > '0') {
        $('#InsertStatus').html(data.count + ' Records Imported.');
      }else{
	  	$('#InsertStatus').html('No records were imported.');
	  }
    },
	   error: function() {
      $('#InsertStatus').html('An error occurred during import.');
    }
  });
}

function listcall() {
  var id_call_request = $("#id_call_request").val();
  $.ajax({
    type: "POST",
    url: 'ajax/ajaxListCall.php',
    data: 'id_call_request=' + id_call_request,
    success: function(result) {
      data = JSON.parse(result);
      if (data.status == '1') {
        $('#importCall').hide();
      } else {
        $('#importCall').show();
      }
      $('#listuploadData2').html(data.content);
    }
  });
}

// Progressbar
$(".st1").click(function() {
  $(".profile").prop("checked", true);
});

$(".barsubmit").click(function() {
  $(".settings").prop("checked", true);
});

$(".valsubmit").click(function() {
  $(".posts").prop("checked", true);
});

$(".progbar ul li").click(function() {
  $(this).addClass("active").siblings().removeClass("active");
});
</script>

<script>
$(document).ready(function(){
function toggleUploadVisibility(){
	const formatSelected = $('#format_type').val();
	const listSelected = $('#id_type').val();
	
	if(formatSelected && listSelected){
	$('#uploadWrapper').show();
	}else{
	$('#uploadWrapper').hide();
	}
}
	$('#format_type, #id_type').on('change', toggleUploadVisibility);
})
</script>

<?php include_once("includes/footer.php")?>