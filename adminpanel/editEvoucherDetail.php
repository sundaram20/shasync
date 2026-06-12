<?php include_once("../config/auto_loader.php"); ?>

<?php



if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){

	$sql = "  SELECT * FROM `".TBL_PROMO_CODE_DETAILS."`
						WHERE `promo_code_id` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";

	$db->query($sql);

	if($db->num_rows() > 0){

		$row = $db->fetch_object();
    $disabled = 'disabled="disabled"';

	}	

	

						

}	





?>

<?php include_once("includes/header.php")?>

  <?php include_once("includes/left.php")?>

  <div class="content-wrapper"> 

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1> Evoucher Manager <small>Manage Evouchers</small> </h1>

      <ol class="breadcrumb">

        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>

        <li class="active">Manage Evouchers</li>

      </ol>

    </section>

    <!-- Main content -->

    <section class="content">

      <div class="row"> 

        <!-- left column -->

        <div class="col-md-12"> 

          <!-- general form elements -->

          

          <div class="nav-tabs-custom">

            <div class="box-header with-border">

              <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> Evoucher : </h3>

            </div>

            <!-- /.box-header --> 

            <!-- form start -->

            

            <form name="duplicateform" id="duplicateform"  method="post" enctype="multipart/form-data"  data-parsley-validate autocomplete="off"  action="">

              <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId"  id="eId"/>

              <div class="form-group has-error" align="center">

                <?php if($_SESSION['errorMsg']){?>

                <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>

                <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>

                <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>

                <?php unset($_SESSION['successMsg']);}?>

              </div>

              <div class="nav-tabs-custom">

                <?php  if(empty($_REQUEST['eId']) && $_REQUEST['action']!='edit'){ ?>

                <div class="box-body">

                  <div class="form-group col-sm-4">

                    <label for="id_company">Company Name</label>

                    <select class="form-control" name="id_company" id="id_company"  data-parsley-errors-container="#companyError" data-parsley-required >

                      <option value="">Select Company</option>

                      <?php $resCat = selectSql(TBL_COMPANY," where status='1'  and `id_shop` = '".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');

											  if($db->num_rows2($resCat)){

											  	while($resultCat = $db->fetch_object2($resCat)){

													if($row->company_id == $resultCat->id_company){

														$selected = 'selected="selected"';

													}else{

														$selected = '';

													}

													$companyData .= '<option '.$selected.' value="'.$resultCat->id_company.'">'.ucfirst($resultCat->name).'</option>';

												}

											  }

											 echo $companyData;

											  ?>

                    </select>

                    <span id="companyError"></span> </div>

                  <div class="form-group col-sm-4">

                    <label for="hotel_code">Scheme Type<font color="#FF0000">*</font></label>

                    <select name="scheme_type" id="scheme_type" class="form-control">

                      <option value="">Select Scheme Type</option>

                      <option value="Evoucher" <?php if($row->scheme_type == 'Evoucher'){ echo 'selected="selected"';} ?>>Evoucher</option>

                      <option value="Mempership" <?php if($row->scheme_type == 'Mempership'){ echo 'selected="selected"';} ?>>Mempership</option>

                    </select>

                    <?php echo $err_hotel_code;?> </div>

                  <div class="form-group col-sm-4">

                    <label for="name">Length<font color="#FF0000">*</font></label>

                    <input class="form-control" type="number" name="length" id="length" value="5"  data-parsley-required />

                    <?php echo $err_name;?> </div>

                </div>

                <div class="box-body">

                  <!--<div class="form-group col-sm-4">

                    <label for="name">Numbers</label>

                    <select class="form-control" name="numbers">

                      <option value="" selected="selected">Select Numbers</option>

                      <option value="0">False</option>

                      <option selected value="1">True</option>

                    </select>

                  </div>-->
                  <input type="hidden" name="numbers" value="1">

                  <div class="form-group col-sm-4">

                    <label for="name">No Of Voucher<font color="#FF0000">*</font></label>

                    <input type="text" class="form-control" placeholder="Enter Number" id="no_of_coupons" name="no_of_coupons" value="<?php if($_POST) echo $_POST['name'];else echo stripslashes($row->name);?>" data-parsley-required>

                    <?php echo $err_name;?> </div>

                    

                  

                  <div class="form-group col-sm-4">

                    <label for="pincode">Voucher Value<font color="#FF0000">*</font></label>

                    <input type="text" class="form-control" placeholder="Enter Voucher Value" id="vaoucher_value" name="vaoucher_value" value="<?php if($_POST) echo $_POST['pincode'];else echo stripslashes($row->pincode);?>" data-parsley-required >

                    <?php echo $err_pincode;?> </div>

                     <div class="form-group col-sm-4">

                    <label for="Food Voucher">Food & Beverage Value<font color="#FF0000">*</font></label>

                    <input type="text" class="form-control" placeholder="Enter Food & Beverage Value" id="food_value" name="food_value" value="<?php if($_POST) echo $_POST['food_value'];else echo stripslashes($row->food_value);?>" data-parsley-required >

                    <?php echo $err_pincode;?> </div>

                </div>

                </div>

                <?php } ?>

                <?php  if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){ ?>

                <div class="box-body">

                  <div class="form-group col-sm-4">

                    <label for="id_company">Company Name</label>

                    <select class="form-control" name="id_company" id="id_company"  data-parsley-errors-container="#companyError" data-parsley-required <?php echo $disabled; ?> >

                      <option value="">Select Company</option>

                      <?php $resCat = selectSql(TBL_COMPANY," where status='1' and id_company = ".$row->company_id." and `id_shop` = '".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');

											  if($db->num_rows2($resCat)){

											  	while($resultCat = $db->fetch_object2($resCat)){

													if($row->company_id == $resultCat->id_company){

														$selected = 'selected="selected"';

													}else{

														$selected = '';

													}

													$companyData .= '<option '.$selected.' value="'.$resultCat->id_company.'">'.ucfirst($resultCat->name).'</option>';

												}

											  }

											 echo $companyData;;

											  ?>

                    </select>

                    <span id="companyError"></span> </div>
       

                    

                  <div class="form-group col-sm-4">

                    <label for="Vaoucher">Voucher Value<font color="#FF0000">*</font></label>

                    <input type="text" class="form-control" placeholder="Enter Voucher Value" id="vaoucher_value" name="vaoucher_value" value="<?php if($_POST) echo $_POST['vaoucher_value'];else echo stripslashes($row->vaoucher_value);?>" data-parsley-required >

                    <?php echo $err_pincode;?> </div>

                    <div class="form-group col-sm-4">

                    <label for="Food Voucher">Food & Beverage Value<font color="#FF0000">*</font></label>

                    <input type="text" class="form-control" placeholder="Enter Food & Beverage Value" id="food_value" name="food_value" value="<?php if($_POST) echo $_POST['food_value'];else echo stripslashes($row->food_value);?>" data-parsley-required >

                    <?php echo $err_pincode;?> </div>

                </div>
    

                 <div class="form-group col-sm-4">

                   

                      <label>Evoucher Code</label>

                      <?php $bookDropDown = '<select class="form-control" name="promo_code_edit" multiple="multiple" id="promo_code_edit" onclick="reportSelection(this.value,this.id);" >



											    <option value="0">Select All</option>';



											  $resCat = selectSql(TBL_PROMO_CODE_DETAILS," WHERE promo_code_id='".$row->promo_code_id."'",' ORDER BY `id`');



											 if($db->num_rows2($resCat)){



											  	while($resultCat = $db->fetch_object2($resCat)){



													



													if(isset($_REQUEST['booking_status']))



													if(in_array($resultCat->id,$_REQUEST['booking_status'])){



														$selected = 'selected="selected"';



													}else{



														$selected = '';



													}



													$bookDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->promo_code).'</option>';



												}



											  }







											 	echo $bookDropDown .= '</select>';







											  ?>

                    </div>

                    

                    <!-- /.form-group --> 

                    

                

                <?php } ?>

                

                

                

                <div class="box-body">

                  <div class="form-group col-sm-4">

                    <label for="start_date">Date valid from</label>

                    <input type="text" class="form-control pickerdate" placeholder="Enter Date valid from" id="date_valid_from" name="date_valid_from" value="<?php if($_POST) echo $_POST['date_valid_from'];elseif($row->date_valid_from) echo stripslashes(date('d-m-Y',strtotime($row->date_valid_from))); else echo date('d-m-Y'); ?>"  data-parsley-required>

                    <?php echo $err_start_date;?> </div>

                  <div class="form-group col-sm-4">

                    <label for="end_date">Date Valid To</label>

                    <input type="text" class="form-control pickerdate" placeholder="Enter end date" id="date_valid_to" name="date_valid_to" value="<?php if($_POST) echo $_POST['date_valid_to'];elseif($row->date_valid_to) echo stripslashes(date('d-m-Y',strtotime($row->date_valid_to))); else echo date('d-m-Y'); ?>"  data-parsley-required>

                    <?php echo $err_end_date;?> </div>

                </div>

                <div class="box-body">

                  <div class="form-group  col-sm-4">

                    <label for="status">Status</label>

                    <input class="flat-red" type="radio"  <?php if($_POST['status'] == '1'){echo "checked";}else{if($row->status == 1)echo "checked";}?> value="1" name="status" id="status"/>

                    Active

                    <input class="flat-red" type="radio" <?php if($_POST['status'] == '0'){echo "checked";}else{if($row->status == 0)echo "checked";}?> value="0" name="status" id="status"/>

                    Inactive <?php echo $err_status;?> </div>

                </div>

                

                <!--<div class="col-md-offset-8 col-md-4">

						<button type="button" onclick="exporttocsv()" class="btn btn-success pull-right">Export Codes to Excel</button>

					</div>-->

                

                <?php if($row->date_created){?>

                <div class="box-body">

                  <div class="form-group">

                    <label for="date_created">Date Created</label>

                    <input type="text" disabled="disabled" class="form-control" id="date_created"  value="<?php echo stripslashes(dateformat($row->date_created));?>">

                  </div>

                  <div class="form-group">

                    <label for="last_modified">Last Updated</label>

                    <input type="text" disabled="disabled" class="form-control" id="last_modified" value="<?php echo stripslashes(dateformat($row->last_modified));?>">

                  </div>

                  <div class="form-group">

                    <label for="last_modified_by">Last Updated By</label>

                    <?php $sqlUserDetail = $db->fetch_obj2(selectSql(TBL_USERS,"WHERE `id` = '".$row->last_modified_by."'",''));?>

                    <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail->username);?>">

                  </div>

                </div>

                <?php } ?>

              </div>

              <!-- /.box-body -->

              <div class="box-footer">

                <input type='button' value='<?=($_REQUEST['eId']==''?'Generate':'Edit')?>' onclick="GeneratePromocode();"  class="btn btn-primary" name="Save" >

                &nbsp;&nbsp;&nbsp;&nbsp;

                <input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("manageEvoucher.php"); '>

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

  <span class="my_popup_open" style="display:none;"></span>

  <div id="my_popup" class="well">

    <div id="rateUpdateData"></div>

    <button class="my_popup_close btn btn-default pull-right">Close</button>

  </div>

  <script>

  	function reportSelection(op,id){

     		if(document.getElementById(id).options[0].selected == true){

    			console.log("selected");

    			selectAll(id,true);

    		}

    	}

    		

  	

    	function selectAll(selectBox,selectAll) { 



    		 if (typeof selectBox == "string") { 

    		    selectBox = document.getElementById(selectBox);

    		   }

    		

    		   for (var i = 0; i < selectBox.options.length; i++) { 

    		       selectBox.options[i].selected = selectAll; 

    		    }		  		    

    	}



function GeneratePromocode() {

          

var eId					= $("#eId").val();   

var no_of_coupons		= $("#no_of_coupons").val();

var id_company 			= $("#id_company").val();

var length 				= $("#length").val();

var date_valid_to 		= $("#date_valid_to").val();

var date_valid_from 	= $("#date_valid_from").val();

var vaoucher_value 		= $("#vaoucher_value").val();

var scheme_type			= $("#scheme_type").val();

var numbers 			= $('select[name="numbers"]').val();
var food_value = $("#food_value").val();



var promo_code_edit		= $('#promo_code_edit').val();





if (document.getElementById('status').checked) {

  status = document.getElementById('status').value;

}else{

	  status = 0;

	}



		  var form=$("#duplicateform");		  

		 if(form.parsley().validate()){

   $('.loading').show();		  

  		 

		  $.ajax({

			   type: "POST",

			   url: 'ajax/ajaxSavePromocodeDetail.php',

			    data: 'id_company='+id_company+'&no_of_coupons='+no_of_coupons+'&length='+length+'&date_valid_to='+date_valid_to+'&date_valid_from='+date_valid_from+'&vaoucher_value='+vaoucher_value+'&scheme_type='+scheme_type+'&numbers='+numbers+'&status='+status+'&eId='+eId+'&promo_code_edit='+promo_code_edit+'&food_value='+food_value, 

			   success: function (result) {

					$( ".my_popup_open" ).click();			

					$( "#rateUpdateData" ).html(result);

      					

				}

		})

	return false;

	}

	

 }

</script>

  <?php include_once("includes/footer.php")?>