<?php include_once("../config/auto_loader.php");


//checkUserLevelPermission($_SESSION['userLevel'],'call','view');

//---------------------------------------------------------------------------------------------------------
						

?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Call Manager
        <small>Calls </small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Calls  </li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Add Calls</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->  			        
			 <form name="form1" method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off">
  <div class="box-body">
    <div class="row">
      <!-- Name -->
      <div class="form-group col-md-4">
        <label for="name">Company Name<font color="#FF0000">*</font></label>
        <input class="form-control" type="text" name="name" id="name" data-parsley-required>
      </div>

      <!-- Mobile -->
      <div class="form-group col-md-4">
        <label for="phone">Mobile <font color="#FF0000">*</font></label>
        <input type="text" class="form-control" placeholder="Enter Mobile number" id="phone" name="phone" data-parsley-required>
      </div>
		
		<!-- serial -->
		  <div class="form-group col-md-4">
        <label for="phone">Serial</label>
        <input type="text" class="form-control" placeholder="Enter Mobile number" id="phone" name="serial">
      </div>
	
    </div>
	  
	  <div class="row">
		  <!-- Format -->
		<div class="form-group col-sm-4">
        <label for="format_type">Select Format<span style="color:red;">*</span></label>
       <select name="format_type" id="format_type" class="form-control" required>
          <option value="">-- Select Format --</option>
          <option value="tss">TSS</option>
          <option value="mau">MAU</option>
          <!--<option value="format_c">Format C</option>-->
        </select>
      </div>
		  
		  <div class="col-md-3">
		  <label>Select Call Type<span style="color:red;">*</span></label>
        <select class="form-control" name="call_type" id="id_type" required> 
          <option value="">--Select type--</option>
        </select>
      </div>
		  
	  </div>
	  
	  
	  
  </div>

<div class="box box-info" id="tss_format" style="display:none; padding: 15px; margin-top: 20px;">
  <div class="box-header with-border">
    <h4 class="box-title">TSS Format Fields</h4>
  </div>
  
  <div class="box-body">
    <div class="row">
      <div class="form-group col-md-4">
        <label>Expiry Date</label>
        <input type="date" class="form-control">
      </div>
      <div class="form-group col-md-4">
        <label>Flavour</label>
        <input type="text" class="form-control">
      </div>
      <div class="form-group col-md-4">
        <label>Release</label>
        <input type="text" class="form-control">
      </div>
    </div>

    <div class="row">
      <div class="form-group col-md-4">
        <label>Contact Person</label>
        <input type="text" class="form-control">
      </div>
      <div class="form-group col-md-4">
        <label>Email ID</label>
        <input type="text" class="form-control">
      </div>
      <div class="form-group col-md-4">
        <label>Admin ID</label>
        <input type="text" class="form-control">
      </div>
    </div>

    <div class="row">
      <div class="form-group col-md-4">
        <label>Account ID</label>
        <input type="text" class="form-control">
      </div>
      <div class="form-group col-md-4">
        <label>State</label>
        <input type="text" class="form-control">
      </div>
      <div class="form-group col-md-4">
        <label>City</label>
        <input type="text" class="form-control">
      </div>
    </div>
	  <div class="row">
      <div class="form-group col-md-4">
        <label>Pincode</label>
        <input type="text" class="form-control">
      </div>
      <div class="form-group col-md-4">
        <label>Land Line</label>
        <input type="text" class="form-control">
      </div>
      
    </div>
  </div>
</div>

				 <div class="box box-info" id="mau_format" style="display:none; padding: 15px; margin-top: 20px;">
  <div class="box-header with-border">
    <h4 class="box-title">MAU Format Fields</h4>
  </div>
  
  <div class="box-body">
    <div class="row">
      <div class="form-group col-md-4">
        <label>Expiry Date</label>
        <input type="date" class="form-control">
      </div>
      <div class="form-group col-md-4">
        <label>Product</label>
        <input type="text" class="form-control">
      </div>
      <div class="form-group col-md-4">
        <label>Release</label>
        <input type="text" class="form-control">
      </div>
    </div>

    <div class="row">
      <div class="form-group col-md-4">
        <label>Contact Person</label>
        <input type="text" class="form-control">
      </div>
      <div class="form-group col-md-4">
        <label>Email ID</label>
        <input type="text" class="form-control">
      </div>
      
    </div>

    
	  
  </div>
</div>
				 
  <!-- /.box-body -->
  <div class="box-footer">
    <input type='submit' value='Add' class="btn btn-primary" name="Save">
    &nbsp;&nbsp;&nbsp;&nbsp;
    <input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("calls.php");'>
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
document.getElementById('format_type').addEventListener('change', function () {
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
  });
	
	/*document.getElementById('format_type').addEventListener('change',function(){
		const format = this.value;
		const tss_card = document.getElementById('tss_format');
		const mau_card = document.getElementById('mau_format');
		if(format == 'tss'){
			tss_card.style.display='inline-block';
		}else if(format == 'mau'){
			mau_card.style.display='inline-block';
		}
	});*/
	
	document.getElementById('format_type').addEventListener('change', function () {
  const format = this.value;

  const tssCard = document.getElementById('tss_format');
  const mauCard = document.getElementById('mau_format');

  // Helper function to toggle field disabled state
  function toggleFields(container, enable) {
    const inputs = container.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
      input.disabled = !enable;
    });
  }

  // First, show both cards (you can still use display: none for visual)
  tssCard.style.display = (format === 'tss') ? 'block' : 'none';
  mauCard.style.display = (format === 'mau') ? 'block' : 'none';

  // Enable or disable based on selected format
  toggleFields(tssCard, format === 'tss');
  toggleFields(mauCard, format === 'mau');
});

</script>


