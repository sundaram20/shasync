<?php include_once("../config/auto_loader.php");

checkUserLevelPermission($_SESSION['userLevel'],TBL_DAILY_ENQUERY,'view');



/////////////////////////////////////////////////////////////////////////////////////



//debugData($_REQUEST);




?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>
<style>

</style>
<div class="content-wrapper"> 
  
  <!-- Content Header (Page header) -->
  
  <section class="content-header">
    <h1> Call Manager<small>Call  Master</small> </h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Calls</li>
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
    <!--progressbar starts-->
        <div class="row progbar">
            <input type="radio" name="bright" checked  class="profile" id="profile">
            <input type="radio"name="bright"  class="settings" id="settings" >
            <input type="radio" name="bright" class="posts"  id="posts">
          
            <div class="head">
                <ul class="nav">
                    <li class="st st1 active">
                        <h2 class="inner">
                         Step 1 : Select Call
                        </h2>
                    </li>

                    <li class="st st2">
                        <h2 class="inner">
                           Step 2 :  Validate
                        </h2>
                    </li>
                    <li class="st st3">
                        <h2 class="inner">
                           Step 3: Submit
                        </h2>
                    </li>
                  
                </ul>
                <div class="line">
                    <span>

                    </span>
                </div>

            </div>
              <div class="content">
              
                  <section class="profile-section">
                      <span>
                          <i class="fa fa-house"></i>
                      </span>
                
                      <!--prgressbar endss-->
  
                       <div id="uform">

                             <form id="image_form" enctype="multipart/form-data">
                                  <input type="hidden" value="1" name="searchFormSubmit" />
                                      <div class="box-body">
                                          <div class="row">
                                                <div class="form-group col-sm-6">
                                                     <label for="terminalId">Upload Call</label>
                                                    <input type="file" name="files" id="files" multiple >
                                                    
                                                </div>
                                               
                                          </div><!-- end od row-->

                                          <div class="box-footer">
                                                  <input  class="btn btn-primary barsubmit"  type="submit" name="submit" is="submit" value="Upload" />
                                        
                                         </div>
                                      </div><!--end of box-body-->

                              </form>
                           
                     </div> 
                     <!--end of id uform-->
                 
                 
                  </section>
                  <section class="account-section">
                      <span>
                          <i class="fa fa-house"></i>
                      </span>
                 
                      <!--validate form starts-->

                      <div id='validateform' class="text-center">
                         <input type="hidden" name="id_call_request" id="id_call_request" value="" />
                         <button class="btn btn-primary valinv" onclick="listcall();">Validate </button>
                          <button class="btn btn-success valsubmit"  style="display:none;" id="importCall" onclick="InsertCall();">Submit</button>
                     </div>
                     <br/>
                     <!--end of validate-->
                        <div class="box">
                              <div class="">
                                <h4 class="mb-0 text-center"><b>Call List</b></h4>
                              </div>
                              <form name="listingForm" action="" method="post">
                                <input type="hidden" value="" name="act" />
                                <div id="listingDiv"></div>
                                
                                <!-- /.box-header -->
                                
                                <div class="box-body table-responsive">
                                 <div id="listuploadData2"></div>
                                </div>
                              </form>  
                        
                        </div>
                        <!-- end of Invocie box --> 
                                 
                 
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
      
      <!-- /.col --> 
      
    </div>
    
    <!-- /.row --> 
    
  </section>
  
  <!-- /.content --> 
  
</div>
<script>
function InsertCall(){
	 var id_call_request			= $("#id_call_request").val();
			 
	$.ajax({

			   type: "POST",
			   url: 'ajax/ajaxInsertCall.php',
			   data: 'id_call_request='+id_call_request, 
			   success: function (result) {
				   data = JSON.parse(result);
					   
					   if(data.count>'0'){
				   
				   $('#InsertStatus').html(data.count+' Records Imported. ');
					   }
				   // alert(result);
				  /*data = JSON.parse(result);
					   alert(data.status);
					   if(data.status=='1'){
			 				 //Hide import Button
							
							$('#importCall').hide();
					   }else{
						   $('#importCall').show();
						     //Show Import Button
						   }
                 $('#listuploadData2').html(data.content);*/
				}

		});
	
	}

function listcall(){ 

			//lert(form.serialize());
			 var id_call_request			= $("#id_call_request").val();
			 
	$.ajax({

			   type: "POST",
			   url: 'ajax/ajaxListCall.php',
			   data: 'id_call_request='+id_call_request, 
			   success: function (result) {
				  data = JSON.parse(result);
					  // alert(data.status);
					   if(data.status=='1'){
			 				 //Hide import Button
							
							$('#importCall').hide();
					   }else{
						   $('#importCall').show();
						     //Show Import Button
						   }
                 $('#listuploadData2').html(data.content);
				}

		});
}

 $(document).ready(function() {
            $('#image_form').submit(function(e) {
                e.preventDefault();  
               $.ajax({  
                    url: "ajax/ajaxUploadCall.php",  
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
							$('#id_call_request').val(data.id);	
						   					   
						   	$('form:input').val('');
						   // alert(data.Msg);
						}else{
							$('form:input').val('');							
							alert(data.Msg);
							$('#id_call_request').val('');	
							
							}
                    }
                });
            });
        });
</script>

    <script>

      //progressbar 
  $(".st1").click(function (){
    $(".profile").prop("checked", true);
  })

  /*

  $(".st2").click(function (){
    $(".settings").prop("checked", true);
  })
*/
    $(".barsubmit").click(function (){
    $(".settings").prop("checked", true);
  })


/*
  $(".st3").click(function (){
    $(".posts").prop("checked", true);
  })

*/
  $(".valsubmit").click(function (){
    $(".posts").prop("checked", true);
  })


  $("progbar ul li").click(function (){
    $(this).addClass("active").siblings().removeClass("active");
  })
    </script>
<?php include_once("includes/footer.php")?>
