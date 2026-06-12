<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE,'view');
/////////////////////////////////////////////////////////////////////////////////////
// ----------cate---------


	
$err = 0;

	
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add
			
$CheckDuplicateDateSQl = executeSql("SELECT * FROM `".TBL_TAX_RULE."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' and `start_date` > '".addslashes(date('Y-m-d',strtotime($_POST['start_date'])))."'");
		if(num_rows($CheckDuplicateDateSQl) == 0){
			
			$addDateSql = "   	INSERT INTO `".TBL_TAX_DATE_RULE."` SET 	
							`start_date` = '".addslashes(date('Y-m-d',strtotime($_POST['start_date'])))."',
							`id_shop` = '".addslashes($_SESSION['shop'])."'							
							";
			 $addDateSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'";
					 $addDateSql;		
							executeSql($addDateSql);
							$date_rule_id= $db->insert_id();			
			foreach($_REQUEST['tax_slabs_from'] as $data =>$value){
			$addSql = "   	INSERT INTO `".TBL_TAX_RULE."` SET 							
							`tax_uniqueid` = '".addslashes($date_rule_id)."',
							`start_date` = '".addslashes(date('Y-m-d',strtotime($_POST['start_date'])))."',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_shop_group` = '1',							
							`tax_slabs_from` = '".addslashes($_POST['tax_slabs_from'][$data])."',
							`tax_slabs_to` = '".addslashes($_POST['tax_slabs_to'][$data])."',
							`tax_percent` = '".addslashes($_POST['tax_percentage'][$data])."'
							";
			 $addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'";
					 $addSql;		
							executeSql($addSql);
							
				
			
				}
				unset($_POST);
				$_SESSION['successMsg'] = 'New Tax Configuration details has been added sucessfully.';
				header("location:manageTaxRule.php");
				exit;
			
		}else{
			$_SESSION['successMsg'] = 'Select Date is less the Previous Date.';
			}
			
		
		}else if(($_POST['Save'] == 'Edit')){//update
			foreach($_REQUEST['tax_slabs_from'] as $data =>$value){
				
			$CheckSQlAval = executeSql("SELECT * FROM `".TBL_TAX_RULE."` WHERE `id` = '".addslashes($_POST['tax_id'][$data])."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."' ");
										
			if(num_rows($CheckSQlAval) == 1){							
				$editSql = "   	UPDATE `".TBL_TAX_RULE."` SET 							
							`tax_uniqueid` = '".addslashes($_REQUEST['uniqueCode'])."',
							`start_date` = '".addslashes(date('Y-m-d',strtotime($_POST['start_date'])))."',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_shop_group` = '1',							
							`tax_slabs_from` = '".addslashes($_POST['tax_slabs_from'][$data])."',
							`tax_slabs_to` = '".addslashes($_POST['tax_slabs_to'][$data])."',
							`tax_percent` = '".addslashes($_POST['tax_percentage'][$data])."'
							";
			 $editSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'
							WHERE `id` = '".addslashes($_POST['tax_id'][$data])."'";
								
			executeSql($editSql);
						
			}else{
				
			$addSql = "   	INSERT INTO `".TBL_TAX_RULE."` SET 							
							`tax_uniqueid` = '".addslashes($_REQUEST['uniqueCode'])."',
							`start_date` = '".addslashes(date('Y-m-d',strtotime($_POST['start_date'])))."',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_shop_group` = '1',							
							`tax_slabs_from` = '".addslashes($_POST['tax_slabs_from'][$data])."',
							`tax_slabs_to` = '".addslashes($_POST['tax_slabs_to'][$data])."',
							`tax_percent` = '".addslashes($_POST['tax_percentage'][$data])."'
							";
			 $addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'";
					 $addSql;		
							executeSql($addSql);
			}
			
			
			
			}//Foreach
			$_SESSION['successMsg'] = 'Tax Configuration details has been updated sucessfully.';
			header("location:manageTaxRule.php");
				exit;
		}
	



if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
	
	$sql = "  SELECT * FROM `".TBL_TAX_RULE."`
								 WHERE `tax_uniqueid` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
	$db->query($sql);
	if($db->num_rows() > 0){
		$row = $db->fetch_object();
	}	
	
	
	$Disable='disabled="disabled"';					
}	
							

?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Tax Manager <small>Tax Configuration Master</small> </h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Tax Configuration  Master</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <!-- left column -->
      <div class="col-md-12">
        <!-- general form elements -->
        <div class="nav-tabs-custom">
        
 

<script type="text/javascript">
function GetDynamicTextBox(value){
    return '<table class="table table-hover" style="margin-bottom:none !important;"><tr><td><input type="text" class="form-control  tax_slabs_from" id="tax_slabs_from" name="tax_slabs_from[]" value="" data-parsley-required automcomplete="off" placeholder="Tax Slabs From" data-parsley-type="number" style="width:160px;"></td><td><input type="text" class="form-control  tax_slabs_to" id="tax_slabs_to" name="tax_slabs_to[]" value="" data-parsley-required automcomplete="off" placeholder="Tax Slabs From" data-parsley-type="number" style="width:160px;"></td>' + '<td><input class="form-control  tax_percentage" type="text"  id="tax_percentage[]" name="tax_percentage[]" placeholder="Tax %" value="" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:160px;"></td>' + '<td><button type="button" value="Remove" onclick = "RemoveTextBox(this)" class="btn btn-danger btn-sm remove"><span class="glyphicon glyphicon-minus"></span></button></td></tr></table>'
}

function AddTextBox() {
    var div = document.createElement('DIV');
    div.innerHTML = GetDynamicTextBox("");
    document.getElementById("TextBoxContainer").appendChild(div);
}
 
function RemoveTextBox(div) {
	
	document.getElementById("TextBoxContainer").removeChild(table.parentNode);
  //document.getElementById("TextBoxContainer").removeChild(div.parentNode);
   
	//y.remove();
}
 
function RecreateDynamicTextboxes() {
    var values = eval('<%=Values%>');
    if (values != null) {
        var html = "";
        for (var i = 0; i < values.length; i++) {            		
			html += "<div>" + room_type_id(values[i]) + "</div>";
			html += "<div>" + rate_plan_id(values[i]) + "</div>";	
        }
        document.getElementById("TextBoxContainer").innerHTML = html;
    }
}
window.onload = RecreateDynamicTextboxes;
</script>

<script type="text/javascript">
        function addTextArea(){}
		
		 $(document).on('click', '.remove', function(){
  		$(this).closest('div').remove();
 });
   
   
   
  </script>
          <div class="box-header with-border">
            <h3 class="box-title"><?php echo $_REQUEST['id']==''?'Add':'Edit'?> Tax Configuration </h3>
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
          <form name="rateMaster" id="rateMaster"  method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off">          <div class="box-body"> 
          
          <?php 
		  if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
			   $uniqueCode = $row->tax_uniqueid; 
		  }else{
			  $uniqueCode = rand(0000,9999); 
			  }
		  ?> 
           <input type="hidden" value="<?php echo $uniqueCode;?>" name="uniqueCode"  />
          <div class="form-group">
                  <label for="start_date">Start Date</label>
                <?php 
			if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){?>
              <input type="text" class="form-control pickerdate" placeholder="Enter start date" id="start_date" name="start_date" value="<?php if($_POST) echo $_POST['start_date'];elseif($row->start_date) echo stripslashes(date('d-m-Y',strtotime($row->start_date))); else echo date('d-m-Y'); ?>"  disabled  readonly data-parsley-required>
              <input type="hidden" class="form-control pickerdate" placeholder="Enter start date" id="start_date" name="start_date" value="<?php if($_POST) echo $_POST['start_date'];elseif($row->start_date) echo stripslashes(date('d-m-Y',strtotime($row->start_date))); else echo date('d-m-Y'); ?>"   readonly data-parsley-required>
            <?php }else{?>     

              <input type="text" class="form-control pickerdate" placeholder="Enter start date" id="start_date" name="start_date" value="<?php if($_POST) echo $_POST['start_date'];elseif($row->start_date) echo stripslashes(date('d-m-Y',strtotime($row->start_date))); else echo date('d-m-Y'); ?>"  <?php ?>   data-parsley-required>

            <?php }?>     
				<?php echo $err_start_date;?>
                </div>
				<!--<div class="form-group">
                  <label for="end_date">End Date</label>
                  <input type="text" class="form-control pickerdate" placeholder="Enter end date" id="end_date" name="end_date" value="<?php if($_POST) echo $_POST['end_date'];elseif($row->end_date) echo stripslashes(date('d-m-Y',strtotime($row->end_date))); else echo date('d-m-Y'); ?>"  data-parsley-required>
				<?php echo $err_end_date;?>
                </div>-->
                
                
           <div class="box-body no-padding text-center loading" >
              <button type="button" class="btn btn-default btn-lrg ajax" title="Loading..."> <i class="fa fa-spin fa-refresh"></i>&nbsp; Loading... </button>
            </div>
			<div class="box box-success  table-responsive no-padding" style="margin-bottom:0px !important;">
				  <table class="table table-hover" style="margin-bottom:0px !important; height:10px; float:left;">
		
		<tr>
		<th style="float:left;width: 17%;">Tariff From</th>
			
		<th style="float:left;width: 17%;">Tariff To</th>	
		<th style="float:left;width: 17%;">Tax %</th>	
	
		</tr>
				</table></div><?php 
			if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
				
	$editsql1 = executeSql("SELECT * FROM `".TBL_TAX_RULE."` WHERE `tax_uniqueid` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."' order by id asc ");
										
										
				while($editrow = $db->fetch_object2($editsql1)){
					
			$tax_slabs_from				=	$editrow->tax_slabs_from;
			$tax_id				=	$editrow->id;
			$tax_slabs_to			=	$editrow->tax_slabs_to;
			$tax_percentage			=	$editrow->tax_percent;
			$TaxGrid	='<input type="hidden" value="'.$tax_id.'" name="tax_id[]" id="tax_id" />';		
			$TaxGrid	.='<table class="table table-hover" style="margin-bottom:none !important;"><tr>';
			
			
			$TaxGrid	.='<td style=" float:left;"><input type="text" class="form-control  tax_slabs_from" id="tax_slabs_from" name="tax_slabs_from[]" data-parsley-required automcomplete="off" placeholder="Tax Slabs From" value="'.$tax_slabs_from.'" data-parsley-type="number" style="width:160px;"></td>';
			
			
			$TaxGrid	.='<td  style="float:left;"><input type="text" class="form-control  tax_slabs_to" id="tax_slabs_from" name="tax_slabs_to[]"  data-parsley-required automcomplete="off" placeholder="Tax Slabs From" value="'.$tax_slabs_to.'" data-parsley-type="number" style="width:160px;"></td>';
			
			 $TaxGrid	.='<td  style="float:left;"><input class="form-control  tax_percentage" type="text"  id="tax_percentage[]" name="tax_percentage[]" placeholder="Tax %" value="'.$tax_percentage.'" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:160px;"></td>'; 
			 
			 // $TaxGrid	.='<td><button type="button" value="Remove" onclick = "RemoveTextBox(this)" class="btn btn-danger btn-sm remove"><span class="glyphicon glyphicon-minus"></span></button></td>';
			  
			  
			  echo $TaxGrid	.='</tr></table>';
				}
			}
				  ?>
            <div class="box-body box-primary" id="rateMasterDetail"> </div>
            
            <div class="form-group">
                  <label for="status">Status</label>
                 <input type="radio" class="flat-red" <?php if($_POST['status'] == '1'){echo "checked";}else{if($row->status == 1)echo "checked";}?> value="1" name="status"/> Active
				 <input type="radio" class="flat-red" <?php if($_POST['status'] == '0'){echo "checked";}else{if($row->status == 0)echo "checked";}?> value="0" name="status"/> Inactive
				 <?php echo $err_status;?>
                </div>
         </div>
            <div class="box-footer ">
             <input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
              &nbsp;&nbsp;&nbsp;&nbsp;
              <input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("manageTaxRule.php?page=<?php echo $_GET['page']; ?>"); '>
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

<!--save data in popup-->


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
	






<script>
window.onload = function() {editTaxConfigurationOneFunction(); }

</script>

<?php include_once("includes/footer.php")?>
