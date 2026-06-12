<?php include_once("../config/auto_loader.php");

checkUserLevelPermission($_SESSION['userLevel'],TBL_DAILY_ENQUERY,'view');



/////////////////////////////////////////////////////////////////////////////////////



//debugData($_REQUEST);




?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>

<div class="content-wrapper"> 
  
  <!-- Content Header (Page header) -->
  
  <section class="content-header">
    <h1> Invoice Manager<small>Invoice  Master</small> </h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Invoices</li>
    </ol>
  </section>
  
  <!-- Main content -->
  
  <section class="content">
    <div class="row">
    <div class="col-xs-12">
      <div class="nav-tabs-custom"> 
        
        <!--########## Company Import jump#######--> 
        
        <!-- Modal -->
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
       
        
        <!--########## Import Company  Modal End#######-->
        
        <div class="form-group has-error" align="center">
          <?php if($_SESSION['errorMsg']){?>
          <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
          <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
          <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
          <?php unset($_SESSION['successMsg']);}?>
        </div>
        <div class="box-header with-border">
          <?php /*?><h3 class="box-title">Search <small>Total Records: (<?=$numRows;?>) &nbsp;</small> </h3><?php */?>
        </div>
        <!-- /.box-header -->
       
  
   <div id="uform">

         <form id="image_form" enctype="multipart/form-data">
          <input type="hidden" value="1" name="searchFormSubmit" />
          <div class="box-body">
          <div class="row">
            <div class="form-group col-sm-6">
              <label for="terminalId">Upload Invoice</label>
                <input type="file" name="files" id="files" multiple >
              <br/>
              <br/>
            </div>
            <div class="form-group col-sm-12"></div>
            <!-- /.box-body --> 
          </div>
          <div class="box-footer">
            <input  class="btn btn-primary"  type="submit" name="submit" is="submit" />
            &nbsp;&nbsp;&nbsp; </div>
       </div></form>
       
      </div> 
       <div id='validateform'>
       <input type="text" name="id_invoice_request" id="id_invoice_request" value="" />
       <button onclick="listinvoice();">Validate </button>
       </div>
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Invoice List</h3>
          </div>
          <form name="listingForm" action="" method="post">
            <input type="hidden" value="" name="act" />
            <div id="listingDiv"></div>
            
            <!-- /.box-header -->
            
            <div class="box-body table-responsive">
             <div id="listuploadData2"></div>
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
<script>
function listinvoice(){ 

			//lert(form.serialize());
			 var id_invoice_request			= $("#id_invoice_request").val();
			 
	$.ajax({

			   type: "POST",
			   url: 'ajax/ajaxListInvoice.php',
			   data: 'id_invoice_request='+id_invoice_request, 
			   success: function (result) {
				  // alert('Test');	
			    // $('#rate_code').empty();
			 $('#listuploadData2').html(result);
				
                 
				}

		});
}

 $(document).ready(function() {
            $('#image_form').submit(function(e) {
                e.preventDefault();  
               $.ajax({  
                    url: "ajax/ajaxUploadInvoice.php",  
                    type: "POST",  
                    data: new FormData(this),  
                    contentType: false,  
                    processData:false,  
                    success: function(data) {
                       // $('#result').append(status);
					   data = JSON.parse(data);
					  // alert(data.status);
					   if(data.status=='1'){
						   $('#uform').hide();
						    $('#validateform').show();
							$('#id_invoice_request').val(data.id);	
						   					   
						   	$('form:input').val('');
						    alert(data.Msg);
						}else{
							$('form:input').val('');							
							alert(data.Msg);
							$('#id_invoice_request').val('');	
							
							}
                    }
                });
            });
        });
</script>
<?php include_once("includes/footer.php")?>
