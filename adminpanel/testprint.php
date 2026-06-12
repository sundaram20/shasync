<?php include_once("../config/auto_loader.php");


//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){

	$err = 0; 
	
	//Insert Here
	
if($err == 0){//No error
	if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add

?>
<script type="text/javascript">
window.print();
</script>
	
<?php

	    $cnt = 32;
      $tmpdir = sys_get_temp_dir();
     
      $file = tempnam($tmpdir, 'ctk');
      $Content='Test Printing Page ';	
      $Content .= "<br> Order No </br> Order Name <br>DISCOUNT(10%)";
     
      echo $Content;
     

     
     $handle = printer_open('HP LaserJet 1020');
     printer_set_option($handle, PRINTER_MODE, "RAW");
     printer_write($handle, $Content);
     printer_close($handle);

				header("location:testprint.php");
			
		}



		
	}
}

							

?>
<?php include_once("includes/header.php")?>

  <?php include_once("includes/left.php")?>
<div class="content-wrapper">
      <section class="content-header">
      <h1>
       Test Print Page
        <small>Manage Unit</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="manageUnits.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Orders</li>
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
			   <li class="active" ><a href="#tab_1" data-toggle="tab">Overview</a></li>  
            </ul>
			<div class="box-header with-border">
              <h3 class="box-title"></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->  			        
			 <form name="form1"  method="post" enctype="multipart/form-data" data-parsley-validate autocomplete="off" >
                <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" />
					<div class="form-group has-error" align="center">
						<?php if($_SESSION['errorMsg']){?>
						 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
						<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
					 	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
						<?php unset($_SESSION['successMsg']);}?>
					 </div>
              <div class="box-body">
                
				 <div class="form-group">
                  <label for="name">Unit Name<font color="#FF0000">*</font></label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-underline"></i> 
					   	</div>
                  <input type="text" class="form-control" placeholder="Enter Unit Name" id="field_value" name="field_value" value="<?php if($_POST) echo $_POST['field_value'];else echo stripslashes($row->field_value);?>"  data-parsley-required>
				<?php echo $err_unit_name;?>
					</div>
                </div>
				
				
				 <div class="form-group">
                  <label for="name">Description<font color="#FF0000">*</font></label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-audio-description"></i> 
					   	</div>
                  <input type="text" class="form-control" placeholder="Enter Description" id="field_description" name="field_description" value="<?php if($_POST) echo $_POST['field_field_description'];else echo stripslashes($row->field_description);?>" data-parsley-required>
				<?php echo $err_unit_field_description;?>
					</div>
                </div> 
           		

              </div>
              <!-- /.box-body -->	
			 <div class="box-footer">                                       
				<input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-success" name="Save" >
				&nbsp;&nbsp;&nbsp;&nbsp;
			   <input type='button' value='Cancel' class="btn btn-danger" onclick='location.replace("manageUnits.php"); '>
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
<?php include_once("../includes/footer.php")?>


