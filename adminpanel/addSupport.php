<?php
include_once("../config/auto_loader.php");

if($_SERVER['REQUEST_METHOD']==='POST'){
	$id_shop = $_SESSION['shop'];	
	$id_company = $_POST['id_company'];	
	$id_contacts = $_POST['id_contacts'];
	$status = "1";
	$dateCreated = $_POST['createDate'];
	
	$serial = trim($_POST['serial']) ?? '';
	$id_product = $_POST['product_id'];
	
	$insertSupport = "INSERT INTO support (
    id_shop,
    id_company,
    id_contacts,
	id_product,
	serial,
    status,
    date_created,
    id_mst_user_created_by
) VALUES (
    '$id_shop',
    '$id_company',
    '$id_contacts',
	'$id_product',
	'$serial',
    '$status',
    '$dateCreated',
    '{$_SESSION['userId']}'
)";
	$run = mysqli_query($connNew, $insertSupport);
	if ($run) {
    $support_id = mysqli_insert_id($connNew); // Use the connection here
		echo "<script>
    alert('Support Customer Details Added Successfully!');
    window.location.href='support.php';
</script>";
		
} else {
    echo "Insert failed: " . mysqli_error($connNew);
}
	
};

 
?>
<?php include_once("includes/header.php") ?>
<?php include_once("includes/left.php") ?>

<div class="content-wrapper">
	

  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Add Support For Non Existing Customer <small>Support Calls</small> </h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Manage Support</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="box box-default" style="margin-bottom : 0!important;">
      <form name="DailyPickupForm" id="DailyPickupForm" action="" method="POST">

        <div class="box-body">

          <ul class="timeline">
            <!-- timeline item -->
            <li class="time-label"> <span class="bg-green"> Support </span> </li>
            <li> <i class="fa fa-phone bg-green"></i>
              <div class="timeline-item">
                <div class="row">

                  <!-- Support Date -->
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="createDate">Support Date</label>
                      <input type="text" class="form-control pickerdate_addreport" id="createDate" name="createDate" value="<?php echo date('Y-m-d'); ?>" readonly>

                    </div>
                  </div>

                  <!-- Supporting By -->
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="support_by">Supporting By</label>
                      <input readonly type="text" name="support_by" id="support_by" value="<?php echo selectColumn('fs_users','name','WHERE id = "'.$_SESSION['userId'].'"') ?>" class="form-control" />
                    </div>
                  </div>

                  <!-- Company Name -->
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="company_name">Company Name - City</label>
                      <div  class="input-group enquirypage" id="showcompanyby"> 
                      <select  class="form-control select2 itemName"   name="id_company" id="id_company" onChange="getExecutiveName(this.value,''); " data-parsley-errors-container="#idcompanyError"  data-parsley-required required>   
                      <?php
                        
                          
                       
                        ?>
                    </select> 
                      <div class="input-group-addon companyby_open"> <i class="fa fa-plus"></i> </div>
                  </div>
                    </div>
                  </div>

                  <!-- Person Met -->
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="id_contacts">Person Met</label>
                      <div class="input-group" id="showbookedby">
                        <select class="form-control select2" name="id_contacts" id="id_contacts" data-parsley-errors-container="#contactError" data-parsley-required onChange="ContactEditEnable();">
                          <option value="">Select Person Met</option>
                        </select>
                        <div id="EditContactName" class="input-group-addon bookedby_open"><i class="fa fa-pencil"></i></div>
                        <div id="addCon" class="input-group-addon bookedby_open"><i class="fa fa-plus"></i></div>
                      </div>
                      <span id="contactError"></span>
                    </div>
                  </div>
					
					<div class="col-md-4">
                    <div class="form-group">
                      <label for="company_name">Serial No</label>
                      <input type="text" name="serial" id="serial" class="form-control" />
                    </div>
                  </div>
					
					<div class="col-md-4">
                    <div class="form-group">
                      <label for="company_name">Product</label>
                      <select name="product_id" id="" class="form-control select2" style="width:100%" data-parsley-required data-parsley-errors-container="#outletError7" required>
                    <option>Select Item Code</option>
                    <?php 
    $sqlProduct = "SELECT * FROM fs_hotels WHERE id_shop = '".addslashes($_SESSION['shop'])."'";
    $Query	=	mysqli_query($connNew,  $sqlProduct); 
    while($rowProduct = mysqli_fetch_object($Query)){ 
								if($rowsID->id_product == $rowProduct->id){
									$selected = 'selected="selected"';
								}else{
									$selected = '';
								}
	
	?>
                    <option value="<?php echo $rowProduct->id; ?>" <?php echo $selected; ?>><?php echo addslashes($rowProduct->name); ?></option>
                    <?php } 
?>
                  </select>
                    </div>
                  </div>
					
					<!-- Remark -->

                </div> <!-- /.row -->
              </div> <!-- /.timeline-item -->
            </li>
          </ul>
			
			<div class="box-footer">
            <input type='submit' value='Add' class="btn btn-primary" name="Save" >
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("support.php"); '>
          </div>

        </div> <!-- /.box-body -->
      </form>
    </div>
  </section>
</div>



<div id="companyby" class="well" style="width:50%;">
        <h3>Add Company</h3>
           <!-- form start -->
         <?php    $companySql = "  SELECT * FROM `".TBL_COMPANY."`
                WHERE `id_company` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
  $db->query($companySql);
  if($db->num_rows() > 0){
    $companyrow = $db->fetch_object();

  }
  ?>
          <form   id="companybypopupform" method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off" >
        <input type="hidden" id="EditCompanyID" name="EditCompanyID" value="<?php echo $companyrow->id_company; ?>" > 
           <div class="form-group has-error" align="center">
              <?php if($_SESSION['errorMsg']){?>
              <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
              <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
              <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
              <?php unset($_SESSION['successMsg']);}?>
            </div>
           
            <div class="box-body">
              <div class="row">
                <div class="form-group col-sm-4">
                  <label for="id_default_group">Company Group<font color="#FF0000">*</font></label>
                   <?php $categoryDropDown = '<select class="form-control input-sm" name="designation" id="designation" data-parsley-errors-container="#designationError" data-parsley-required   >
                                 <option value="">Select Company Group</option>';
                                
                                 $resCat = selectSql(TBL_GROUP," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `id_group`');            
                                 if($db->num_rows2($resCat)){
                                 while($resultCat = $db->fetch_object2($resCat)){
                                     
                                   $categoryDropDown .= '<option  value="'.$resultCat->id_group.'">'.ucfirst($resultCat->name).'</option>';
                                 }
                                 }
                                 echo $categoryDropDown .= '</select>';
                                 ?>
                </div>
                <div class="form-group col-sm-4">
                  <label for="name">Company Name<font color="#FF0000">*</font></label>
                  <input autocomplete="off" type="text" class="form-control awesomplete" data-list="#mylist" placeholder="Enter Company name" id="name" name="name" value="<?php if($_POST) echo $_POST['name'];else echo stripslashes($row->name);?>" data-parsley-required >
                  <ul id="mylist" style="display:none;">
                    <?php  $resCat = selectSql(TBL_COMPANY," where status=1  and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `id_company`');
                        if($db->num_rows2($resCat)){
                          while($resultCat = $db->fetch_object2($resCat)){
                          $companyDropDown .= '<li>'.ucfirst($resultCat->name).'-'.ucfirst($resultCat->city).'</li>';
                        }
                        }
                        echo $companyDropDown;
          ?>
                  </ul>
                  <?php echo $err_name;?> </div>


                   <div class="form-group col-sm-4">
                  <label for="id_country" >Country<font color="#FF0000">*</font></label>               
                                 <?php $countryDropDown = '<select class="form-control input-sm" name="id_country" id="id_country" data-parsley-errors-container="#countryError" onchange="getState(this.value);"  data-parsley-required   >
                                 <option value="110">India</option>';                    
                                $resCat = selectSql(TBL_COUNTRY_LANG,"where id_lang='1' ",' ORDER BY `name`');       
                                 if($db->num_rows2($resCat)){
                                 while($resultCat = $db->fetch_object2($resCat)){
                                     
                                   $countryDropDown .= '<option  value="'.$resultCat->id_country.'">'.ucfirst($resultCat->name).'</option>';
                                 }
                                 }
                                 echo $countryDropDown .= '</select>';
                                 ?>
                  <span id="countryError"></span> </div>
              </div>


             <div class="row">

              

                          <div class="form-group col-sm-4">
                  <label for="id_state">State <font color="#FF0000">*</font></label>
                  <div id="state">
                    <?php $stateDropDown = '<select class="form-control input-sm" name="id_state" id="id_state" data-parsley-errors-container="#stateError"  data-parsley-required   >
                                 <option value="346">Delhi</option>';                    
                                $resCat = selectSql(TBL_STATE,"where status='1' and id_country=110  ",' ORDER BY `name`');       
                                 if($db->num_rows2($resCat)){
                                 while($resultCat = $db->fetch_object2($resCat)){
                                     
                                   $stateDropDown .= '<option  value="'.$resultCat->id_state.'">'.ucfirst($resultCat->name).'</option>';
                                 }
                                 }
                                 echo $stateDropDown .= '</select>';
                                 ?>
                   </div>              
   
                  <span id="stateError"></span> </div>


               <div class="form-group col-sm-4">
                  <label for="name">City<font color="#FF0000">*</font></label>
                  <input autocomplete="off" type="text" class="form-control awesomplete" data-list="#citylist" placeholder="Enter City" id="city" name="city" data-parsley-required >
                  <ul id="citylist" style="display:none;">
                    <?php  //$resCat = selectSql(TBL_COMPANY,'distinct',"  where status=1  and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `id_company`');

                    $citySql="SELECT DISTINCT city from `".TBL_COMPANY."` WHERE  status=1  and id_shop='".addslashes($_SESSION['shop'])."' ORDER BY `id_company`";

    $resCat = mysqli_query($connNew,$citySql);

                      

                        if($db->num_rows2($resCat)){
                          while($resultCat = $db->fetch_object2($resCat)){
                          $cityDropDown .= '<li>'.ucfirst($resultCat->city).'</li>';
                        }
                        }
                       echo $cityDropDown;
                        
          ?>
                  </ul>
                  <?php echo $err_name;?> </div>

                   <div class="form-group col-sm-3">
                  <label for="area">Area<font color="#FF0000">*</font></label>
                  <?php $areaDropDown = '<select class="form-control input-sm" name="area" id="area"  onchange="areaOnChg(this.value);"  data-parsley-required   >
                                 <option value="">Select Area</option>';                    
                               $resCat = selectSql(TBL_AREAS," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');
                                 if($db->num_rows2($resCat)){
                                 while($resultCat = $db->fetch_object2($resCat)){
                                     
                                   $areaDropDown .= '<option  value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
                                 }
                                 }
                                 echo $areaDropDown .= '</select>';
                                 ?>  

                  <span id="areaError"><?php echo $err_area;?></span> 
              <span id="areaExe" style="color: red"></span> </div>
                </div><!--end of row-->  
 
                <div class="otherdet">
                  <span>Other Details</span>
                  <hr>
                </div>  
        
              <div class="row">
                <div class="form-group col-sm-4">
                  <label for="secondary_email">Seconday Email</label>
                  <input type="text" class="form-control" placeholder="Enter seconday email id" id="secondary_email" name="secondary_email"  data-parsley-type="email"  >
                  <?php echo $err_email;?> </div>
                <div class="form-group  col-sm-4">
                  <label for="phone">Phone Number</label>
                  <input type="text" class="form-control" placeholder="Enter phone number" id="phone" name="phone"  >
                  <?php echo $err_phone;?> </div>
              
                <div class="form-group col-sm-4">
                  <label for="fax">GST Number</label>
                  <input type="text" class="form-control" placeholder="Enter fax number" id="fax" name="fax" >
                  <?php echo $err_fax;?> </div>
                <div class="form-group col-sm-4">
                  <label for="address">Address</label>
                  <textarea class="form-control" name="address" id="address"  rows="1" placeholder="Enter Address">
</textarea>
                  <?php echo $err_address;?> </div>

                   <div class="form-group col-sm-4">
                  <label for="postcode">Pincode</label>
                  <input type="text" class="form-control" placeholder="Enter pincode" id="postcode" name="postcode" >
                  <?php echo $err_postcode;?> </div>


                <div class="form-group col-sm-4">
                  <label for="details">Details</label>
                  <textarea class="form-control" name="details" id="details"  rows="1" placeholder="Enter Details" autocomplete="off"><?php if($_POST) echo $_POST['details'];else echo $row->details;?>
          </textarea>
                  <?php echo $err_details;?> </div>    

                <div class="form-group col-sm-3">
                  <label for="company_credibility">Company Credibility</label>
                  <select class="form-control" onChange="openCreditLimit(this.value);" name="company_credibility" id="company_credibility" data-parsley-errors-container="#company_credibilityError" data-parsley-required>
                    <option value="1" <?php if($_REQUEST['company_credibility']=='1'){echo 'selected="selected"';}elseif($row->company_credibility=='1'){echo 'selected="selected"';} ?>>Credit Allowed</option>
                    <option value="2"  <?php if($_REQUEST['company_credibility']=='2'){echo 'selected="selected"';}elseif($row->company_credibility!='1'){echo 'selected="selected"';}?>>Credit Not Allowed</option>
                  </select>
                  <span id="company_credibilityError"><?php echo $err_company_credibility;?></span> </div>
                <?php 
                if($row->company_credibility=='1'){
                  $dispalyBox ='style="display:visible"';
                }
                else{
                  $dispalyBox ='style="display:none"';
                }
                ?>


                <div  <?php echo $dispalyBox;?> class="form-group col-sm-2"  id="credit_limit">
                  <label for="company_credibility">Credit Limit (In Lacs)</label>
                  <input class="form-control"  type="text" name="credit_limit" value="<?php echo $row->credit_limit; ?>">
                  </select>
                </div>

                <div class="form-group col-sm-4">
                  <label for="deals_in">Deals In</label>
                   <?php $dealsInDropDown = '<select class="form-control input-sm" name="deals_in" id="deals_in"  data-parsley-errors-container="#deals_inError">
                                 <option value="">Select Company Domain</option>';                    
                              $resCat = selectSql(TBL_COMPANY_AREA," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');
                                 if($db->num_rows2($resCat)){
                                 while($resultCat = $db->fetch_object2($resCat)){
                                     
                                   $dealsInDropDown .= '<option  value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
                                 }
                                 }
                                 echo $dealsInDropDown .= '</select>';
                                 ?>

                
                  <span id="deals_inError"><?php echo $err_deals_in;?></span> </div>
          
                <div class="form-group col-sm-4" style="visibility: hidden;">
                  <label for="rate_level_id">Rate Level</label>
                  <select class="form-control select2" name="id_rate_level[]" id="id_rate_level" multiple="multiple"  >
                    <option value="">Select Rate Level</option>
           <?php $resCat = selectSql(TBL_RATE_LEVEL," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');
          $iCounterActions = 0;
                        if($db->num_rows2($resCat)){
                          while($resultCat = $db->fetch_object2($resCat)){
                        $chkSql = "SELECT * FROM `".TBL_COMPANY."` WHERE FIND_IN_SET('".$resultCat->id."',id_rate_level ) and id_company='".addslashes(encryptor('decrypt',$_REQUEST['eId']))."' ";
                          if($db->num_rows2(executeSql($chkSql)) > 0){
                            $selected = 'selected="selected"';
                          }else if($_POST[$selected]){
                            $selected = 'selected="selected"';
                          }                         
                          else{
                            $selected = '';
                          }
                          $levelData .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
                          $iCounterActions++;
                        }
                        }
                       echo $levelData;;
                        ?>
                  </select>
 
                  <span id="rate_level_idError"></span> </div>
     
                  </div>
              </div>

            <div class="form-group col-sm-12" align="left">
              <input  type="button" class="btn btn-default" onClick="saveRateCompanyPopupform();" value="Save">
              <button class="companyby_close btn btn-default">Close</button>
            </div>

          </form>
    </div>


<div id="bookedby" class="well" style="width:50%;">
  <form id="bookedbypopupform" data-parsley-validate autocomplete="off" method="post" >
    <?php $id_contact=selectColumn(TBL_CUSTOMER,'id_customer','WHERE id_company="'.$row->id_company.'" AND id_customer="'.$row->id_contacts.'" ');?>
    <input type="hidden" id="EditCustomerID" name="EditCustomerID" value="<?php echo $id_contact; ?>" >
    <div class="form-group col-sm-4">
      <label >Title <font color="#FF0000">*</font></label>
      <select name="Nametitle" id="Nametitle"  class="form-control input-sm" data-parsley-required >
        <option value="">-Select-</option>
        <option value="Dr.">Dr.</option>
        <option value="Miss.">Miss.</option>
        <option value="Mr.">Mr.</option>
        <option value="Mrs.">Mrs.</option>
        <option value="Ms.">Ms.</option>
        <option value="Pr.">Pr.</option>
        <option value="Prof.">Prof.</option>
        <option value="Rev.">Rev.</option>
      </select>
    </div>
    <div class="form-group col-sm-4">
      <label for="first_name">First Name <font color="#FF0000">*</font></label>
      <input type="text" class="form-control input-sm" placeholder="Enter first name" id="first_name" name="first_name" value="" data-parsley-required data-parsley-type="alphanum">
    </div>
    <div class="form-group col-sm-4">
      <label for="last_name">Last Name <font color="#FF0000">*</font></label>
      <input type="text" class="form-control input-sm" placeholder="Enter last name" id="last_name" name="last_name" value="" data-parsley-required>
    </div>
    <div class="form-group col-sm-4">
      <label for="email" >Email Id </label>
      <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email Id" data-parsley-type="email" autocomplete="off" >
    </div>
    <div class="form-group col-sm-4">
      <label for="mobile" >Mobile No. <font color="#FF0000">*</font></label>
      <input type="phone" name="mobile" id="mobile" class="form-control" placeholder="Enter mobile number"  data-parsley-type="digits" data-parsley-length="[10, 10]" autocomplete="off" data-parsley-required>
    </div>
    <div class="form-group col-sm-4">
      <label for="first_name">Designation <font color="#FF0000">*</font></label>
      <?php $marketDropDown = '<select class="form-control input-sm" name="designation" id="designation" data-parsley-errors-container="#designationError" data-parsley-required   >
                             <option value="">Select Designation</option>';
                            
                             $resCat = selectSql(TBL_DESIGNATION_MASTER," where status='1' and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name`');                   
                             if($db->num_rows2($resCat)){
                             while($resultCat = $db->fetch_object2($resCat)){
                                 
                               $marketDropDown .= '<option  value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
                             }
                             }
                             echo $marketDropDown .= '</select>';
                             ?>
    </div>
    <div class="form-group col-sm-6">
      <label for="mobile" style="width: 100%;">DOB.</label>
      <select name="dateofBirthMonth" id="dateofBirthMonth" class="form-control" style="width:50% !important; float:left">
        <option value="">Month</option>
        <?php 
        for($i = 1; $i <= 12; $i++){
       $dt = DateTime::createFromFormat('!m', $i);
       echo "<option value=\"$i\">".$dt->format('F')."</option>";
   }
        ?>
      </select>
      <select name="dateofBirthday" id="dateofBirthday" class="form-control" style="width:50%;">
        <option value="">Day</option>
        <?php 
        for($Birthday = 1; $Birthday <= 31; $Birthday++){
       echo "<option value=\"$Birthday\">$Birthday</option>";
   } 
        ?>
      </select>
    </div>
    <div class="form-group col-sm-6">
      <label for="mobile" style="width: 100%;" >DOA.</label>
      <select name="dateofanniversaryMonth" id="dateofanniversaryMonth" class="form-control" style="width:50% !important; float:left">
        <option value="">Month</option>
        <?php 
        for($i = 1; $i <= 12; $i++){
       $dt = DateTime::createFromFormat('!m', $i);
       echo "<option value=\"$i\">".$dt->format('F')."</option>";
   }
        ?>
      </select>
      <select name="dateofanniversaryday" id="dateofanniversaryday" class="form-control" style="width:50%;">
        <option value="">Day</option>
        <?php 
        for($DayS = 1; $DayS <= 31; $DayS++){
       echo "<option value=\"$DayS\" >$DayS</option>";
   } 
        ?>
      </select>
    </div>
    <div class="form-group col-sm-12" align="left">
      <input  type="button" class="btn btn-default" onClick="saveRateCustomerPopupform();" value="Save">
      <button class="bookedby_close btn btn-default">Close</button>
    </div>
  </form>
</div> 


<?php include_once("includes/footer.php") ?>

<script>
comCheck = () =>{
		window.location.href='https://www.roomstatushub.in/sync/adminpanel/index.php';
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



let supportHistoryXhr = null;
let serialDebounce = null;
let isApplyingHistory = false; // guard against infinite loop

function fetchContactsThenSelect(companyId, contactIdToSelect, contactName) {
    $.ajax({
        type: "GET",
        url: 'ajax/ajaxExecutiveName.php',
        data: 'companyId=' + companyId + '&contactId=',
        success: function (result) {
            $('#id_contacts').empty();
            $('#id_contacts').html(result);

            if (contactIdToSelect) {
                if ($('#id_contacts').find("option[value='" + contactIdToSelect + "']").length === 0) {
                    $('#id_contacts').append(new Option(contactName, contactIdToSelect, true, true));
                }
                $('#id_contacts').val(contactIdToSelect).trigger('change');
                ContactEditEnable();
            }

            isApplyingHistory = false;
        }
    });
}

function applySupportHistory(data) {
    if (!data || !data.found) return;

    isApplyingHistory = true;

    if (data.serial) {
        $('#serial').val(data.serial);
    }

    if (data.id_product) {
        $('select[name="product_id"]').val(data.id_product).trigger('change');
    }

    if (data.id_company) {
        if ($('#id_company').find("option[value='" + data.id_company + "']").length === 0) {
            $('#id_company').append(new Option(data.company_name, data.id_company, true, true));
        }
        $('#id_company').val(data.id_company).trigger('change');
        fetchContactsThenSelect(data.id_company, data.id_contacts, data.contact_name);
    } else {
        isApplyingHistory = false;
    }
}

function checkSupportHistory(type, value) {
    if (!value) return;
    if (supportHistoryXhr) supportHistoryXhr.abort();
    supportHistoryXhr = $.ajax({
        url: 'ajax/ajaxCheckSupportHistory.php',
        type: 'GET',
        dataType: 'json',
        data: { type: type, value: value },
        success: applySupportHistory
    });
}

// Serial field
$(document).on('input', '#serial', function () {
    if (isApplyingHistory) return;
    clearTimeout(serialDebounce);
    const val = $(this).val().trim();
    serialDebounce = setTimeout(function () {
        if (val.length >= 3) checkSupportHistory('serial', val);
    }, 500);
});

// Company field — only respond to REAL user changes, not our own programmatic ones
$(document).on('change', '#id_company', function () {
    if (isApplyingHistory) return; // ignore changes we triggered ourselves
    const val = $(this).val();
    if (val) checkSupportHistory('company', val);
});

		
</script>

	<script type="text/javascript">
$("#addCon").click(function(){
  $("#EditCustomerID").val('');
  $('#Nametitle').val('');  
  $('#first_name').val('');  
  $('#last_name').val('');  
  $('#email').val('');      
  $('#mobile').val('');
  $('#designation').val('');
  $('#dateofBirthMonth').val('');
  $('#dateofBirthday').val('');
  $('#dateofanniversaryMonth').val('');
  $('#dateofanniversaryday').val('');
  $("#addCon").addClass("bookedby_open");
});

$("#EditContactName").click(function(){
  var id_contacts = $("#id_contacts").val();
  $('#EditCustomerID').val(id_contacts);
  $.ajax({
    type: "GET",
    url: 'ajax/ajaxSaveContactEdit.php',
    data: 'id_contacts='+id_contacts, 
    success: function (result) {  
      if(result !=''){
        var resultArray = result.split('####');
        $('#bookedbypopupform #Nametitle').val(resultArray[0]); 
        $('#bookedbypopupform #first_name').val(resultArray[1]);  
        $('#bookedbypopupform #last_name').val(resultArray[2]);  
        $('#bookedbypopupform #email').val(resultArray[3]);      
        $('#bookedbypopupform #mobile').val(resultArray[4]);
        $('#bookedbypopupform #designation').val(resultArray[5]);
        $('#dateofBirthMonth').val(resultArray[6]);
        $('#bookedbypopupform #dateofBirthday').val(resultArray[7]);
        $('#bookedbypopupform #dateofanniversaryMonth').val(resultArray[8]);
        $('#bookedbypopupform #dateofanniversaryday').val(resultArray[9]);
      }
    }
  });
});

function ContactEditEnable(){  
  var id_contacts = $("#id_contacts").val();
  if(id_contacts!=''){    
    $('#EditCustomerID').val(id_contacts);       
    $("#EditContactName").show();
  }else{
    $("#EditContactName").hide();
  }
}
</script>

