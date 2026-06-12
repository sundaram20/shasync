<?php include_once("../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],fs_rate,'view');
/////////////////////////////////////////////////////////////////////////////////////
// ----------cate---------
if($_SESSION['userLevel'] !=1  ){
	if(addslashes(encryptor('decrypt',$_REQUEST['id'])) !=""){
		restrictRateForZone($connNew,addslashes(encryptor('decrypt',$_REQUEST['id'])));
	}	
}
$allow_multiple_date_rates	=selectColumn(TBL_DOCUMENT_CONFIG,'allow_multiple_date_rates'," WHERE status='1' AND `id_shop` = '".addslashes($_SESSION['shop'])."' AND `doc_type`='1'");
if($allow_multiple_date_rates==1){
	$getSpecialRateLetterMaster	='getSpecialRateLetterMaster();';
}


if(!empty($_REQUEST['id']) && $_REQUEST['action']=='edit'){
	$sql = "  SELECT * FROM `".TBL_RATE."`
								WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
	//$sql = "SELECT * FROM `".TBL_RATE."` as a join `".TBL_RATE_DETAILS."` as b where b.`id` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."'  AND a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND a.id=b.rate_id ";
	$db->query($sql);
	if($db->num_rows() > 0){	
	
		$row = $db->fetch_object();
	}						
}	
	
if($_REQUEST['id'] !=''){
	$disabled = 'disabled="disabled"';
}						


?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Rate Letter Manager <small>Rate Letter</small> </h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Rate Letter Master</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <!-- left column -->
      <div class="col-md-12">
        <!-- general form elements -->
        <div class="nav-tabs-custom">
        
 

<?php
if($row->sub_code	>0){
	$subCode=	'-'.$row->sub_code;
}
?>
          <div class="box-header with-border">
            <h3 class="box-title"><?php echo $_REQUEST['id']==''?'Add':'Edit'?> Rate Letter <?php echo $row->rate_name.$subCode;  ?> </h3>
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
          


          
          <form name="rateMaster" id="rateMaster"  method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off">           
            <input type="hidden" value="<?php echo $_REQUEST['id'];?>" name="id" id="id" />
            <input type="hidden" name="doc_type" id="doc_type" value="1" />
            <div class="box-header">
			  <div class="row">
			  <div class="form-group col-sm-4">
                  <label for="seasonId">Company-City <font color="#FF0000">*</font> </label>
                   <select required="required" class="form-control select2 itemName" name="company_id" id="company_id" data-parsley-errors-container="#companyError" data-parsley_required <?php //echo $disabled; ?> onChange="getCompanyGuestName(this.value,'');areaExecutive(this.value); companyInsideZone();" ><option value="">Select Company</option>
								 <?php			  $resCat = selectSql(TBL_COMPANY," where name !='' and status='1' and id_shop='".addslashes($_SESSION['shop'])."'  AND id_company='".$row->company_id."'  ",' ORDER BY `name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($resultCat->id_company == $row->company_id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}	
			$companyDropDown .= '<option '.$selected.' value="'.$resultCat->id_company.'">'.ucfirst($resultCat->name).'-'.ucfirst($resultCat->city).'</option>';
												}
											  }
											 	echo $companyDropDown .= '</select>';
											  ?>

					<span id="companyError" style="color:red; font-size: 15px;"><?php echo $err_company;?></span><br>	
					<span id="companyError1" style="color:red; font-size: 15px;"><?php echo $err_company;?></span>					  
                  </div>
                  
                  
 

                	  
					  <?php   
                  
         if($_REQUEST['id'] !=''){
			 
			 
		 $resContact = executeSql("SELECT * from `".TBL_CUSTOMER."` where status='1' and id_company='".addslashes($row->company_id)."' and type='2' order by first_name");?>
<div class="form-group col-sm-7" >
 <label for="id_contacts" >Name</label>
 <div class="input-group" id="showContact">
	<select class="form-control" name="id_contacts" id="id_contacts" data-parsley-errors-container="#contactError"  onChange="CustomerEditEnable();" <?php //echo $disabled; ?>>
					<option value="">Company Person</option>';
		<?php while($rowContact = $db->fetch_object2($resContact)){	
			if($row->id_contacts == $rowContact->id_customer){
				$selected = 'selected="selected"';
			}else {
				$selected = '';
				
			}
			?>
            <option value="<?php echo $rowContact->id_customer; ?>" <?php echo $selected?>  ><?php echo 'Name : '.ucfirst($rowContact->title).''.ucfirst($rowContact->first_name).' '.ucfirst($rowContact->last_name) .' | Email : '.$rowContact->email.' | Mobile : '.$rowContact->mobile ?></option>
<?php 		}				 ?>
		</select>
        
        <div id="EditcusterName" class="input-group-addon bookedby_open"><i class="fa fa-pencil"></i></div>
        <div class="input-group-addon" id="addCon"> <i class="fa fa-plus"></i></div>
        </div>
        </div>
		
		<?php 
}else{  			 ?>
            <div class="form-group col-sm-7" >
            <label for="id_contacts" >Name</label>
			 <div class="input-group" id="showbookedby">
            <select class="form-control select2" name="id_contacts" id="id_contacts" data-parsley-errors-container="#contactError">
              <option value="">Select User</option>
            </select>
			<div class="input-group-addon bookedby_open"> <i class="fa fa-plus"></i> </div>
            </div>
            
            <span id="contactError"></span> 
            </div>
      
      <?php } 
	//echo $resultCat->id.'|'.$resultCat->rate_level_id .'=='. $row->id.'|'.$row->rate_level_id;
			//echo "SELECT `".TBL_RATE."`.*, `".TBL_RATE_LEVEL."`.name as level_name ,`".TBL_RATE_LEVEL."`.id as rate_level_id ,`".TBL_RATE_MARKET."`.name as market_name from `".TBL_RATE."` LEFT JOIN `".TBL_RATE_LEVEL."` ON `".TBL_RATE."`.rate_level_id=`".TBL_RATE_LEVEL."`.id   LEFT JOIN `".TBL_RATE_MARKET."` ON `".TBL_RATE."`.market=`".TBL_RATE_MARKET."`.id where   `".TBL_RATE."`.id_shop='".addslashes($_SESSION['shop'])."'  and (`".TBL_RATE."`.company_id='".$row->id_company."' || `".TBL_RATE."`.company_id='0' )"		?>            
                    <?php 
					
					if(!empty($_REQUEST['id']) && $_REQUEST['action']=='edit'){?>
                    
                    
                    <input type="hidden" name="revise_code" id="revise_code" value="<?php echo $_REQUEST['revise_code']; ?>" />
						<input type="hidden" name="sub_code" id="sub_code" value="<?php echo $_REQUEST['sub_code']; ?>" />
                        <input type="hidden" name="market" id="market"  value="<?php echo $row->market; ?>" />
                        <input type="hidden" name="seasonId" id="seasonId" value="<?php echo $row->seasonId; ?>" />
					<?php } ?>
                    
                  <div class="form-group col-sm-4">
                  <label for="seasonId">Season<font color="#FF0000">*</font></label>
                  <?php $seasonDropDown = '<select class="form-control select2" name="seasonId" id="seasonId" data-parsley-errors-container="#seasonError" onchange="getseasonDate(this.value); ajaxMasterRateLetterLoad(this.value); rateLetterMasterFunction();'.$getSpecialRateLetterMaster.'" data-parsley-required '.$disabled.' >
											  <option value="">Select Season</option>
											  ';
											  $resCat = selectSql(TBL_RATE_SEASON," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `id` desc ');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($resultCat->id == $row->seasonId){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}	
													$seasonDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $seasonDropDown .= '</select>';
											  ?>
                  <span id="seasonError"><?php echo $err_season;?></span> </div>
                    
			  
                  
				 
				  <div class="form-group col-sm-2">
                  <label for="start_date">Start Date</label>
                  <div class="input-group">
                    <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                    <input type="text" class="form-control " id="start_date" name="start_date" value="<?php if($row->start_date){echo date('d-m-Y',strtotime($row->start_date));}else{ echo date("d-m-Y");}  ?>" data-parsley-required data-parsley-errors-container="#start_dateError" <?php // echo $disabled; ?> >
                    <!--<input type="hidden" class="form-control " id="start_date" name="start_date" value="<?php if($row->start_date){echo date('d-m-Y',strtotime($row->start_date));}else{ echo date("d-m-Y");}  ?>" data-parsley-required data-parsley-errors-container="#start_dateError" >-->
                  </div>
                  <!-- /.input group -->
                  <span id="start_dateError"><?php echo $err_start_date;?></span> </div>
                <div class="form-group col-sm-2">
                  <label for="end_date">End Date </label>
                  <div class="input-group">
                    <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                    <input type="text" class="form-control " id="end_date" name="end_date" value="<?php if($row->start_date){echo date('d-m-Y',strtotime($row->end_date));}else{ echo date('d-m-Y',strtotime("+1 days"));} ?>" data-parsley-required data-parsley-errors-container="#end_dateError"  <?php // echo $disabled; ?> >
                   <!-- <input type="hidden" class="form-control " id="end_date" name="end_date" value="<?php if($row->start_date){echo date('d-m-Y',strtotime($row->end_date));}else{ echo date('d-m-Y',strtotime("+1 days"));} ?>" data-parsley-required data-parsley-errors-container="#end_dateError"  >-->
                  </div>
                  <!-- /.input group -->
                  <span id="end_dateError"><?php echo $err_end_date;?></span> </div> 
			  </div>
              
              
              <div class="row">
               <?php
			   				
					//if(!empty($_REQUEST['id']) && $_REQUEST['action']=='edit'){?>
                    
				<div class="form-group col-sm-3">
                  <label for="rate_level_id">Master Rate Letter<font color="#FF0000">*</font></label>
                  <select class="form-control" name="rate_level_id" id="rate_level_id" data-parsley-required data-parsley-errors-container="#rate_level_idError" onchange="updateLevelAndMarket(); rateLetterMasterFunction();getGeneralTerms(0,1,this.value);<?php echo $getSpecialRateLetterMaster; ?>" <?php  echo $disabled ?>>
                    <option value="">Select Rate Master</option>
                    
                    
                 
					
                    <?php
					$resCat = executeSql("SELECT `".TBL_RATE."`.*, `".TBL_RATE_LEVEL."`.name as level_name ,`".TBL_RATE_LEVEL."`.id as rate_level_id ,`".TBL_RATE_MARKET."`.name as market_name from `".TBL_RATE."` LEFT JOIN `".TBL_RATE_LEVEL."` ON `".TBL_RATE."`.rate_level_id=`".TBL_RATE_LEVEL."`.id   LEFT JOIN `".TBL_RATE_MARKET."` ON `".TBL_RATE."`.market=`".TBL_RATE_MARKET."`.id where   `".TBL_RATE."`.id_shop='".addslashes($_SESSION['shop'])."'  and (`".TBL_RATE."`.company_id='".$row->id_company."' || `".TBL_RATE."`.company_id='0' ) ");
											  if($db->num_rows2($resCat)){

												 
												   $i=1;
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($i==1){
													if($row->rate_category_id==0){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
														
													 $availableData .= '<option '.$selected.' value="0">Not Applicable</option>';
													 
													}
													
													if($resultCat->id == $row->rate_category_id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													
													$availableData .= '<option '.$selected.' value="'.$resultCat->id.'|'.$resultCat->rate_level_id.'">'.ucfirst($resultCat->rate_name).' | '.ucfirst($resultCat->level_name).' | '.ucfirst($resultCat->market_name).'</option>';
												$i++;
												}
												
											  }
											 echo $availableData;;
											  ?>
                  </select>
                  <span id="rate_level_idError"></span> </div>
                  
                  	  <?php // }else{?> 
                 
               <!-- <div class="form-group col-sm-4">
                  <label for="rate_level_id">Master Rate Letter<font color="#FF0000">*</font></label>
                  <select class="form-control" name="rate_level_id" id="rate_level_id" data-parsley-required data-parsley-errors-container="#rate_level_idError" onchange="updateLevelAndMarket(); rateLetterMasterFunction();" <?php  echo $disabled ?>>
                    <option value="">Select Rate Level</option>
                             </select>
                            <span id="rate_level_idError"></span>
                           
                          
                         </div>    -->
                         
                         <?php  //} ?>						
					
					  
					  <div class="form-group col-sm-3">
                  <label for="remarks">Rate Level <font color="#FF0000">*</font></label>
                  <?php $marketDropDown = '<select class="form-control  select2 input-sm" name="new_rate_level_id" id="new_rate_level_id" data-parsley-errors-container="#new_rate_level_idError" onchange="getGeneralTerms(this.value,1);rateLetterMasterFunction();'.$getSpecialRateLetterMaster.'"   data-parsley-required  '.$disabled.'>
												  <option value="">Select Rate Level</option>';
												 
												  $resCat = selectSql(TBL_RATE_LEVEL," where status='1' and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name`');										
												  if($db->num_rows2($resCat)){
													while($resultCat = $db->fetch_object2($resCat)){
														if($resultCat->id == $row->rate_level_id){
															$selected = 'selected="selected"';
														}else if($_REQUEST['market']== $resultCat->id){
															$selected = 'selected="selected"';
														}else{
															$selected = '';
														}	
														$marketDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
													}
												  }
													echo $marketDropDown .= '</select>';
												  ?>
					  <span id="new_rate_level_idError"><?php echo $err_new_rate_level_id;?></span>
                </div>
                
                
                
                
                <div class="form-group col-sm-3">
                  <label for="remarks">Market <font color="#FF0000">*</font></label>
                  <?php $marketDropDown = '<select class="form-control  select2 input-sm" name="market" id="market" data-parsley-errors-container="#marketError" onchange="rateLetterMasterFunction();'.$getSpecialRateLetterMaster.'"  data-parsley-required   >
												  <option value="">Select Market</option>';
												 
												  $resCat = selectSql(TBL_RATE_MARKET," where status='1' and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name`');										
												  if($db->num_rows2($resCat)){
													while($resultCat = $db->fetch_object2($resCat)){
														if($resultCat->id == $row->market){
															$selected = 'selected="selected"';
														}else if($_REQUEST['market']== $resultCat->id){
															$selected = 'selected="selected"';
														}else{
															$selected = '';
														}	
														$marketDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
													}
												  }
													echo $marketDropDown .= '</select>';
												  ?>
					  <span id="marketError"><?php echo $err_market;?></span>
                </div>
					
				<div class="form-group col-sm-3">
            <label for="id_guest" >Terms<font color="#FF0000">*</font></label>
            <div class="input-group" id="showGuest">
              <select class="form-control select2" name="generalterms" id="generalterms" required="required"  data-parsley-required data-parsley-errors-container="#generaltermsError" data-parsley-required>
                <option value="">Select  Terms</option>
                <?php 
		$resCat = selectSql(TBL_GENERAL_TERMS,"where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ");
				if(num_rows($resCat)){
					while($resultCat = $db->fetch_object2($resCat)){
						if($row->generalterms == $resultCat->id){
							$selected = 'selected="selected"';
						}
						elseif($rowDoc->id_general_terms==$resultCat->id){
							$selected = 'selected="selected"';
						}
						else{
							$selected = '';
						}
						$guestDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.$resultCat->title.'</option>';
					}
				}
				  echo $guestDropDown;
									
			?>
              </select>
              
            </div>
            
             
            <span id="generaltermsError"></span> </div>	

                   </div>  
                     <script src="https://code.jquery.com/jquery-latest.js"></script>
			 <script>
		function Toggle(id) {
			if (document.getElementById(id).style.display == "none" || document.getElementById(id).style.display == "") {
				document.getElementById(id).style.display = "block";
			} else if (document.getElementById(id).style.display == "block") {
				document.getElementById(id).style.display = "none";
			} else {
				document.getElementById(id).style.display = "none";
			}
		}
		
		function  SelectHotelsList(HotelDuplicateInsert){
			var test = HotelDuplicateInsert;
		        $("div.desc").hide();
		        $("#cars" + test).show();
			}
		
</script>
<style>
#test01 { width:500px; padding:15px;  display:none; }
</style>
<?php if(empty($_REQUEST['id']) && $_REQUEST['action']!='edit'){?> 
<div id="checkboxDisable" style="display:none; border:1px solid #CCC; margin-bottom: 15px; ">
<table id="example2" class="table table-bordered table-striped" style="margin-bottom: 0px !important;">
                <thead>
                  <tr style="background-color:#3C8DBC; color:#fff;">
                                   
                    <th> <input type="checkbox" name="" onclick="Toggle('test01')"   /> &nbsp;Auto Fill Rate Letter</th>					
                     
					
                    
                  </tr>
                </thead>
                
                </table>
    
   <div id="test01">
		
<form id="duplicateform" name="duplicateform" method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off"  action="" >


<div id="myRadioGroup">
    <input type="radio" name="HotelDuplicateInsert"  id="HotelDuplicateInsert" onClick="SelectHotelsList(1);" checked="checked"  value="1"  />All Hotels

<input type="radio" name="HotelDuplicateInsert" id="HotelDuplicateInsert"onClick="SelectHotelsList(2);"  value="2" />Select Hotels
 
   
   <div id="cars2" class="desc" style="display: none;" >
       <div class="form-group col-sm-5 col-md-6 col-lg-6">
					  <label for="DuphotelId">Hotel<font color="#FF0000">*</font></label>
					  <?php $hotelDropDown1 = '<select class="form-control" name="DuphotelId[]" id="DuphotelId" style="width:500px;" multiple="multiple" data-parsley-errors-container="#DuphotelError">
												  <option value="">Select Hotel</option>';
												  
												  if(empty($_SESSION['hotel_access'])){
													$resCat = selectSql(TBL_HOTELS," where status='1' and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name`');		
												  }else{
												  $resCat = selectSql(TBL_HOTELS," where status='1' and find_in_set(id,'".$_SESSION['hotel_access']."') and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name`');												}
												  if($db->num_rows2($resCat)){
													while($resultCat = $db->fetch_object2($resCat)){
													/*	if($resultCat->id == $row->hotelId){
															$selected = 'selected="selected"';
														}else if(encryptor('decrypt',$_REQUEST['hotelId'])== $resultCat->id){
															$selected = 'selected="selected"';
														}else{
															$selected = '';
														}	*/
				$hotelDropDown1 .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'-'.strtoupper($resultCat->city).'</option>';
													}
												  }
													echo $hotelDropDown1 .= '</select>';
												  ?>
					  <span id="DuphotelError"><?php echo $err_Duphotel;?></span> </div>
    

 </div>
</div>
<br /><br /><br /><br /><br /><br />

        <input type="hidden" id="RateEditId" name="RateEditId" value="<?php echo encryptor('decrypt',$_REQUEST['id']); ?>" >
        <input type="hidden" id="dupId" name="dupId" value="<?php echo $DuplicateID['0']; ?>" >
        <input type="hidden" id="rate_level_id1111" name="rate_level_id1111" value="<?php echo $_REQUEST['new_rate_level_id']; ?>" >
        <input type="hidden" id="Generatestart_date" name="Generatestart_date" value="<?php echo $_REQUEST['start_date']; ?>">
        <input type="hidden" id="Generateend_date" name="Generateend_date" value="<?php echo $_REQUEST['end_date']; ?>">
        
        
        <input type="hidden" id="Generatemarket" name="Generatemarket" value="<?php echo $_REQUEST['market']; ?>" >
        <input type="hidden" id="Generaterate_category_id" name="Generaterate_category_id" value="<?php echo $DuplicateID['0']; ?>" >
        <input type="hidden" id="GenerateseasonId" name="GenerateseasonId" value="<?php echo $_REQUEST['seasonId']; ?>" >
        
        <input type="hidden" id="Generatecompany_id" name="Generatecompany_id" value="<?php echo $_REQUEST['company_id']; ?>" >
        <input type="hidden" id="Generatecompany_id" name="Generatecompany_id" value="<?php echo $_REQUEST['company_id']; ?>" >
        <input type="hidden" id="Generateid_contacts" name="Generateid_contacts" value="<?php echo $_REQUEST['id_contacts']; ?>" >
        <button class="btn btn-primary" id="generateButton" onclick="SaveDuplicateRate();" type="button">Generate</button>      
      

 
    </form>
</div>
</div>
           <?php }  ?>        
                   
                   
                   
                   
              <div class="row">
                <div class="form-group col-sm-4">
					  <label for="hotelId">Hotel <font color="#FF0000">*</font></label>
					  <?php $hotelDropDown = '<select class="form-control select2" name="hotelId" id="hotelId" data-parsley-errors-container="#hotelError" onchange="getRoom(this.value,1); rateLetterMasterFunction(); getInclusionDetail(this.value);'.$getSpecialRateLetterMaster.'" data-parsley-required >
												  <option value="">Select Hotel</option>';
												  
												  if(empty($_SESSION['hotel_access'])){
													$resCat = selectSql(TBL_HOTELS," where status='1' and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name`');		
												  }else{
												  $resCat = selectSql(TBL_HOTELS," where status='1' and find_in_set(id,'".$_SESSION['hotel_access']."') and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name`');												
												      
												  }
												  if($db->num_rows2($resCat)){
													while($resultCat = $db->fetch_object2($resCat)){
														if($resultCat->id == $row->hotelId){
															$selected = 'selected="selected"';
														}else if(encryptor('decrypt',$_REQUEST['hotelId'])== $resultCat->id){
															$selected = 'selected="selected"';
														}else{
															$selected = '';
														}	
														$hotelDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).' - '.strtoupper($resultCat->city).'</option>';
													}
												  }
													echo $hotelDropDown .= '</select>';
												  ?>
					  <span id="hotelError"><?php echo $err_hotel;?></span> </div>
                      
                      
				  <!--<div class="form-group col-sm-4">
                  <label for="remarks">Remarks </label>
                  <textarea class="form-control " name="remarks" id="remarks" rows="1" placeholder="Enter Remarks" automcomplete="off" ><?php echo $row->remarks; ?></textarea>
                </div>-->
				<div class="clearfix"></div>	
            

 <?php 
					
					/*$inclusionDetail= json_decode($rowInclusion->inclusion_detail,true);
					
					$resRateInclusion = executeSql("SELECT * from `".TBL_RATE_INCLUSION."`  where status='1' and id_shop='".addslashes($_SESSION['shop'])."' and type='1' order by display_order"); 
				 
				 	while($rowRateInclusion = $db->fetch_object2($resRateInclusion)){
					
					if($inclusionDetail[$rowRateInclusion->id]!=''){
						$inclusionValue = $inclusionDetail[$rowRateInclusion->id];
					}else{
						$inclusionValue = 0;
					}
					
					echo ' <div class="form-group col-sm-2"> 
					  <label for="inclusion|'.$rowRateInclusion->id.'|'.$rowRateInclusion->type.'">'.$rowRateInclusion->name.'</label> 
					  <input type="hidden" name="inclusion_id[]" value="'.$rowRateInclusion->id.'" />
					  <input type="text" class="form-control inclusionFood" placeholder="Enter '.$rowRateInclusion->name.' Price" id="inclusion|'.$rowRateInclusion->id.'|'.$rowRateInclusion->type.'" name="inclusion_detail[]" value="'.$inclusionValue.'" data-parsley-required data-parsley-type="digits" onkeyup="rateCalAll(this.id,this.value);"> 
                  </div> ';
					
					}*/
				 
				 ?>
				<div class="clearfix"></div> 
				 <?php /*?><div class="form-group col-sm-2">
                  <label for="extra_bed">Extra Bed	</label>                 
                  <input type="text" class="form-control extra_bed" placeholder="Enter extra bed price" id="extra_bed" name="extra_bed" value="0" data-parsley-required data-parsley-type="digits" onkeyup="rateExtra(this.id,this.value);">
                </div><?php */?>
				
				
                <div class="form-group col-sm-1" style="display:none;">
                  <label for="extras">Extras</label>
                  <input type="hidden" name="inclusion_id[]" value="0" />
                  <input type="hidden" name="inclusion_extra" id="inclusion_extra" value="0" />
                  <input type="text" class="form-control inclusionExtra" placeholder="Enter extras price" id="extras" name="inclusion_detail[]" value="<?php if($inclusionDetail['0']!=''){echo $inclusionDetail['0'];}else{echo '0';} ?>" data-parsley-required data-parsley-type="digits" onkeyup="rateCalAll(this.id,this.value);">
                </div>
				
				
				<?php  $data= json_decode($row->rate_points,true); ?>
				
                
				
              </div>
            </div>
          </form>
          <form name="form1" id="rateUpdate"  method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off">
            <div class="box-body no-padding text-center loading" style="display:none;">
              <button type="button" class="btn btn-default btn-lrg ajax" title="Loading..."> <i class="fa fa-spin fa-refresh"></i>&nbsp; Loading... </button>
            </div>
			
            <div class="box-body box-primary" id="rateMasterDetail"> </div>
            
            	<?php 
                	//fetching default values
                /*$ratePoints=array();
                if($_REQUEST['id']==''){
                	$sqlDoc = "SELECT * FROM ".TBL_DOCUMENT_CONFIG." WHERE id_shop='".$_SESSION['shop']."' AND doc_type='1' ";
                	$resDoc=mysqli_query($connNew,$sqlDoc);
                	$rowDoc = mysqli_fetch_object($resDoc);
                	$ratePoints = explode(',', $rowDoc->ids_rate_points);

                }	*/

                ?>
                
                
            <!-- /.box-body -->
             <?php 
		   $allow_multiple_date_rates	=selectColumn(TBL_DOCUMENT_CONFIG,'allow_multiple_date_rates'," WHERE status='1' AND `id_shop` = '".addslashes($_SESSION['shop'])."' AND `doc_type`='1'");
		   if($allow_multiple_date_rates==1){
			   ?>     
              <div class="box-body box-primary" id="rateSpecialMasterDetail"> </div> 
            <?php } ?> 
            <div style="float: left;width: 1100px;">
            
                           
                           
                 <div class="form-group col-sm-4">
                  <label for="userlevelId">Rate Points</label>
				  <select class="form-control select2" name="rate_points[]"  id="rate_points" multiple="multiple" data-parsley-errors-container="#rate_pointsError">				  
                  <?php 
					$sqlUserActions = selectSql(TBL_RATE_POINTS," where id_shop='".$_SESSION['shop']."' ",'');
					$iCounterActions = 0;
					while($resUserActions = $db->fetch_object2($sqlUserActions)){
						$chkSql = "SELECT * FROM `".TBL_RATE."` WHERE FIND_IN_SET('".$resUserActions->id."',rate_points ) and id='".addslashes(encryptor('decrypt',$_REQUEST['id']))."' ";
						if($db->num_rows2(executeSql($chkSql)) > 0){
							$selected = 'selected="selected"';
						}else if($_POST[$selected]){
						$selected = 'selected="selected"';
						}													
						else{
							$selected = '';
						}
						echo '<option '.$selected.' value="'.$resUserActions->id.'">'.$resUserActions->title.'</option>';
						
						$iCounterActions++;
					}
					?>
					</select>
                    <?php echo $err_rate_points;?>
                </div>  
                           
          
            
            </div> 
              <div class="form-greoup" style="display:none;margin: 15px;">
                  <label for="additional_points" style="width:100%;">Additional Points</label>
                  
				   <textarea id="additional_points" name="additional_points" rows="10" cols="80"><?php if($_POST) echo $_POST['additional_points'];else echo stripslashes($row->additional_points);?></textarea>
                  
				<?php echo $err_additional_points;?>
                </div>


            <?php if($row->date_created){?>
             
           <div class="form-group col-sm-4">
                     <label for="date_created">Date Created</label>
                     <input type="text" disabled="disabled" class="form-control" id="date_created"  value="<?php echo stripslashes(dateformat($row->date_created));?>">        
                   </div> 
           
           <div class="form-group col-sm-4">
                     <label for="last_modified">Last Updated</label>
                     <input type="text" disabled="disabled" class="form-control" id="last_modified" value="<?php echo stripslashes(dateformat($row->last_modified));?>">       
                   </div> 
           
           <div class="form-group col-sm-4">
                     <label for="last_modified_by">Last Updated By</label>
              <?php $sqlUserDetail = $db->fetch_obj2(selectSql(TBL_USERS,"WHERE `id` = '".$row->last_modified_by."'",''));?>
                     <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail->username);?>">       
                   </div>  

                   <div class="form-group col-sm-6">
                     <label for="last_modified">Mail Sent By</label>
                     <input type="text" disabled="disabled" class="form-control" id="id_mail_sent_by" value="<?php echo selectColumn('fs_users','name',' WHERE id='.$row->id_mail_sent_by.' AND id_shop='.$_SESSION['shop'].'' );?>">       
                   </div> 

                   <div class="form-group col-sm-6">
                     <label for="last_modified">Mail Sent At</label>
                     <input type="text" disabled="disabled" class="form-control" id="mail_sent_at" value="<?php echo date('d-m-Y H:i:s',strtotime($row->mail_sent_at));?>">       
                   </div> 

             
             <?php } ?>   
         
               <div class="form-group col-sm-12">
                  <label for="booking_status">Allow For Booking</label>
                 <input type="radio"  class="flat-red" <?php if($_POST['allow_booking'] == '1'){echo "checked";}else{if($row->allow_booking == 1)echo "checked";}?> value="1" name="allow_booking"/> Yes
				 <input type="radio" class="flat-red" <?php if($_POST['allow_booking'] == '0'){echo "checked";}else{if($row->allow_booking == 0)echo "checked";}?> value="0" name="allow_booking"/> No
				 <?php echo $err_booking_status;?>
                </div> 
                
              <div class="form-group col-sm-12">
                  <label for="status">Status</label>
                  <?php

                   if($row->status=='0')
                   	$inactive='checked="checked"';
                   else
                   	$active='checked="checked"';

                  ?>
                 <input type="radio"  class="flat-red" <?php echo 	$active; ?> value="1" name="status"/> Active
				 <input type="radio" class="flat-red"  <?php echo 	$inactive; ?>  value="0" name="status"/> Inactive
				 <?php echo $err_status;?>
                </div>
                            <br/>
            <div class="box-footer ">
            	<input type='hidden' id='chkFlag' value='0' name='chkFlag'/>	
              <input type='hidden' value='<?=($_REQUEST['id']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
              <input type='button' value='<?=($_REQUEST['id']==''?'Add':'Edit')?>' class="btn btn-primary" onclick="submitRateLetterForm();"  id="saveButton">
              &nbsp;&nbsp;&nbsp;&nbsp;
              <input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("manageRateLetters.php?page=<?php echo $_GET['page']; ?>"); '>
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
<!--<span class="my_popup_open" style="display:none;"></span>
<div id="my_popup" class="well">
  <div id="RateLetterStatusOne"></div>
  <button class="my_popup_close btn btn-default pull-right">Close</button>
</div>-->
<!--save data in popup-->
<div id="fadeandscale" class="well">
  <form id="popupForm" >
    <input type="hidden" id="roomEid" >
    <input type="hidden" id="ratePlanId" >
    <input type="hidden" id="tarrifId" >
    <input type="hidden" id="tarrifName" >
	<input type="hidden" id="planType" >
    <div class="form-group">

      <label for="tarrif">Tarrif Price</label>
      <input type="text" class="form-control input-sm" placeholder="Enter tarrif price" id="tarrif" name="tarrif" value="0" data-parsley-required data-parsley-type="digits">
    </div>
    <div class="form-group">
      <label for="meal">Meal Price</label>
      <input type="text" class="form-control input-sm" placeholder="Enter meal price" id="meal" name="meal" value="0" data-parsley-required data-parsley-type="digits" readonly>
    </div>
    <button class="fadeandscale_close btn btn-default" onclick="SavePopup();">Save</button>
    <button class="fadeandscale_close btn btn-default">Close</button>
  </form>
</div>

<span class="my_popup_open" style="display:none;"></span>

	<div id="my_popup" class="well">

    <div id="rateUpdateData"></div>
    <button id="my_popup_yes" class="my_popup_close btn btn-default pull-left">Yes</button>
    <button id="my_popup_no" class="my_popup_close btn btn-default pull-left">No</button>

 </div>

<!--show msg in popup-->
<!--<span class="my_popup_open" style="display:none;"></span>
<div id="my_popup" class="well">
    <div id="rateUpdateData"></div>
    <button class="my_popup_close btn btn-default pull-right">Close</button>
</div>-->


<!--show msg in popup-->
<div id="ratePoint" class="well" style="display:none;">
	<form id="ratePointForm" autocomplete="off">
    <div id="ratePoinData"></div>
    
	</form>
	<button class="ratePoint_close btn btn-primary pull-right" onclick="SaveRatePointPopup();">Add</button>
</div>




<!--create pkg popup for ----->
<div id="pkgPopup" class="well" style="display:none; max-width:44em;">
  <form id="pkgpopupForm" autocomplete="off">
  	 <input type="hidden" id="pkgroomEid" >
    <input type="hidden" id="pkgratePlanId" >
	<input type="hidden" id="pkgplanType" >
  	<div class="form-group col-sm-6">
      <label for="pkg_title">Title</label>
      <input type="text" class="form-control input-sm" placeholder="Enter title" id="pkg_title" name="pkg_title"  data-parsley-required >
    </div>
	
	<div class="form-group col-sm-6">
      <label for="pkg_description">Description</label>
      <textarea type="text" class="form-control input-sm" rows="1" placeholder="Enter description" id="pkg_description" name="pkg_description"  data-parsley-required ></textarea>
    </div>
    <div class="form-group col-sm-6">
      <label for="pkg_min_pax">Min. Pax</label>
      <input type="text" class="form-control input-sm" placeholder="Enter min pax" id="pkg_min_pax" name="pkg_min_pax" value="2" data-parsley-required data-parsley-type="digits">
    </div>
	 <div class="form-group col-sm-6">
      <label for="pkg_min_nights">Min. Nights</label>
      <input type="text" class="form-control input-sm" placeholder="Enter min nights" id="pkg_min_nights" name="pkg_min_nights" value="2" data-parsley-required data-parsley-type="digits">
    </div>
	
	 <div class="form-group col-sm-6">
      <label for="rack_rate">Rack Rate</label>
      <input type="text" class="form-control input-sm" placeholder="Enter double pax price" id="rack_rate" name="rack_rate" value="0" data-parsley-required data-parsley-type="digits" readonly>
    </div>
	
	 <div class="form-group col-sm-6">
      <label for="pkg_discount">Discount</label>
      <div class="input-group"><input type="text" class="form-control input-sm" id="pkg_discount" name="pkg_discount" value="0" data-parsley-required automcomplete="off" data-parsley-type="digits" data-parsley-maxlength="2"><span class="input-group-addon" ><i class="fa fa-percent"></i></span></div>
    </div>
	
	 <div class="form-group col-sm-6">
      <label for="pkg_extra_price">Extra Price</label>
      <input type="text" class="form-control input-sm" placeholder="Enter package pax price" id="pkg_extra_price" name="pkg_extra_price" value="0" data-parsley-required data-parsley-type="digits">
    </div>
	
	<div class="form-group col-sm-6" style="margin-top:30px;">
        <label for="pkg_status">Status &nbsp;</label>
                 <input type="radio" value="1" name="pkg_status" id="pkg_status1"/> Active
				 <input type="radio" value="0" name="pkg_status" id="pkg_status2"/> Inactive
     </div>
	
	<div class="form-group col-sm-12" align="center">
		 
		<button class="btn btn-primary" onclick="savePkgPopupData();" type="button">Save</button>
		<button class="pkgPopup_close btn btn-default" onclick="this.form.reset();">Close</button>
	</div>
  </form>
</div>	


<div id="bookedby" class="well" style="width:50%;">
  <form id="bookedbypopupform" data-parsley-validate autocomplete="off" method="post"  >
  <input type="hidden" id="EditCustomerID" name="EditCustomerID" value="<?php echo $row->id_contacts; ?>" >
  
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
      <label for="email" >Email Id <font color="#FF0000">*</font></label>
      <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email Id" data-parsley-type="email" automcomplete="off" data-parsley-required>
    </div>
   <div class="form-group col-sm-4">
      <label for="mobile" >Mobile No. <font color="#FF0000">*</font></label>
      <input type="phone" name="mobile" id="mobile" class="form-control" placeholder="Enter mobile number"  data-parsley-type="digits" data-parsley-length="[10, 10]" automcomplete="off" data-parsley-required>
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

<!--create new pkg row popup  for add --- currently hidden -->
<div id="pkg" class="well" style="max-width:44em;"> 
  <form id="pkgForm" autocomplete="off">
  <input type="hidden" id="pkgId" value="">
  	<div class="form-group col-sm-6">
      <label for="title">Title</label>
      <input type="text" class="form-control input-sm" placeholder="Enter title" id="title" name="title" value="" data-parsley-required >
    </div>
	
	<div class="form-group col-sm-6">
      <label for="description">Description</label>
      <textarea type="text" class="form-control input-sm" rows="1" placeholder="Enter description" id="description" name="description" value="" data-parsley-required ></textarea>
    </div>
    <div class="form-group col-sm-6">
      <label for="min_pax">Min. Pax</label>
      <input type="text" class="form-control input-sm" placeholder="Enter min pax" id="min_pax" name="min_pax" value="2" data-parsley-required data-parsley-type="digits">
    </div>
	<div class="form-group col-sm-6" align="center">
		 <label for="btn">&nbsp;<br><br></label>
		<button class="btn btn-primary" onclick="savePkgPopup();" type="button">Save</button>
		<button class="pkg_close btn btn-default">Close</button>
	</div>
  </form>
</div>

	 <div id="FeedBack" class="well" style="display:none;">
    <form id="FeedBackpopupForm" data-parsley-validate autocomplete="off">
        <div class="form-group">
        <label for="room_name">Are you sure that you want to Revise this Rate Letter</label>
      </div>
        <button class="FeedBack_close btn btn-default">Close</button>
         <button class="FeedBack_close btn btn-default"  onclick="submitRateLetterForm();">Ok</button>
      </form>
  </div>
 <input type="hidden" id="rate_level_id111" name="rate_level_id111" value="<?php echo $_REQUEST['new_rate_level_id']; ?>" >
<?php /*?>
 <input type="hidden" id="dupId" name="dupId" value="<?php echo $DuplicateID['0']; ?>" >
       <input type="hidden" id="rate_level_id111" name="rate_level_id111" value="<?php echo $_REQUEST['new_rate_level_id']; ?>" >
	  <input type="hidden" id="start_date" name="start_date" value="<?php echo $_REQUEST['start_date']; ?>">
	  <input type="hidden" id="end_date" name="end_date" value="<?php echo $_REQUEST['end_date']; ?>">
      
<!--       <input type="hidden" id="hotelId" name="hotelId" value="<?php echo $_REQUEST['hotelId']; ?>" >-->
       <input type="hidden" id="market" name="market" value="<?php echo $_REQUEST['market']; ?>" >
              <input type="hidden" id="rate_category_id" name="rate_category_id" value="<?php echo $DuplicateID['0']; ?>" >
        <input type="hidden" id="seasonId" name="seasonId" value="<?php echo $_REQUEST['seasonId']; ?>" >

       <input type="hidden" id="company_id" name="company_id" value="<?php echo $_REQUEST['company_id']; ?>" >
        <input type="hidden" id="company_id" name="company_id" value="<?php echo $_REQUEST['company_id']; ?>" >
        <input type="hidden" id="id_contacts" name="id_contacts" value="<?php echo $_REQUEST['id_contacts']; ?>" >
<?php */?>
<script>
 

$("#addCon").click(function(){
	$("#EditCustomerID").val('');
		$('#Nametitle').val('');	
		$('#first_name').val('');	
		$('#last_name').val();	
		$('#email').val('');			
		$('#mobile').val('');
		$('#designation').val('');	
		$('#dateofBirthMonth').val('');			
		$('#dateofBirthday').val('');
		$('#dateofanniversaryMonth').val('');			
		$('#dateofanniversaryday').val('');
	$("#addCon").addClass("bookedby_open");
});

$("#EditcusterName").click(function(){
	var id_contacts			= $("#id_contacts").val();
	 $('#EditCustomerID').val(id_contacts);
	 $.ajax({
   type: "GET",
   url: 'ajax/ajaxGetCustomerValue.php',
   data: 'id_contacts='+id_contacts, 
   success: function (result) {	
  
	   	if(result !=''){
			
		var  resultArray = result.split('####');
		

		$('#Nametitle').val(resultArray['0']); 
	
		
		$('#first_name').val(resultArray['1']);	
		$('#last_name').val(resultArray['2']);	
		$('#email').val(resultArray['3']);			
		$('#mobile').val(resultArray['4']);
		$('#designation').val(resultArray['5']);
			
		if(resultArray['6']!=''){	
		$('#dateofBirthMonth').val(resultArray['6']);			
		}
		if(resultArray['7']!=''){	
		$('#dateofBirthday').val(resultArray['7']);
		}
		if(resultArray['8']!=''){	
		$('#dateofanniversaryMonth').val(resultArray['8']);
		}
		if(resultArray['9']!=''){				
		$('#dateofanniversaryday').val(resultArray['9']);		
		}
		
	}

	}

	})

	 
	
});

function areaExecutive(value){
    var cmp_id = value;
     $.ajax({
     type        : 'POST',
     url         : 'ajax/ajaxAreaExecutive.php', 
     data        : 'id_company='+cmp_id,
     success     : function(data){
      $("#companyError1").html(data);
      
     } 
    })
  }


function CustomerEditEnable(){	 
	var id_contacts			= $("#id_contacts").val();		
	if(id_contacts!=''){		
	$('#EditCustomerID').val(id_contacts);			 
	$("#EditcusterName").show();
	}else{
	$("#EditcusterName").hide();
		}
	}

function SaveDuplicateRate() {
          
	
var RateEditId			= $("#RateEditId").val();
var company_id 			= $("#company_id").val();
var chkFlag 			= $("#chkFlag").val();
var rate_points 			= $("#rate_points").val();
var generalTerm 			= $("#generalterms").val();
if(company_id=="" || company_id==undefined){
	alert("Please Select Company !");
	return;
}
else if(generalTerm =='' || generalTerm ==undefined){
	alert('Select Terms');
	return;
}


//below conditon put on 21/02/2019

if($("#new_rate_level_id_change").val() !="" || $("#new_rate_level_id_change").val() !=undefined){
	var new_rate_level_id 	= $("#new_rate_level_id_change").val();	
}
else{
	var new_rate_level_id 	= $("#new_rate_level_id").val();
}

///////////////////end////////////////////

var start_date 			= $("#start_date").val();
var end_date 			= $("#end_date").val();
var market 				= $("#market").val();
var id_contacts 		= $("#id_contacts").val();
var rate_category_id 	= $("#rate_category_id").val();
var dupId 				= $("#dupId").val();
var DuphotelId			= $("#DuphotelId").val();
var rate_level_id		= $("#rate_level_id").val();
var seasonId			= $("#seasonId").val();
var HotelDuplicateInsert	=	$("input[type=radio]:checked").val();
		  var form=$("#duplicateform");	
		  	  
		  //form.parsley().validate();		  
  		  $('.loading').show(); 
		  $.ajax({
			   type: "POST",
			   url: 'ajax/ajaxSaveDuplicateRateDemo.php',
			    data: form.serialize()+'&company_id='+company_id+'&new_rate_level_id='+new_rate_level_id+'&start_date='+start_date+'&end_date='+end_date+'&market='+market+'&id_contacts='+id_contacts+'&rate_category_id='+rate_category_id+'&dupId='+dupId+'&DuphotelId='+DuphotelId+'&rate_level_id='+rate_level_id+'&seasonId='+seasonId+'&HotelDuplicateInsert='+HotelDuplicateInsert+'&RateEditId='+RateEditId+'&rate_points='+rate_points+'&generalterms='+generalTerm+'&chkFlag='+chkFlag, 
			   success: function (result) {
					$( "#chkFlag" ).val("set");
					$( ".my_popup_open" ).click();
					$( "#my_popup_yes" ).hide();
					$( "#my_popup_no" ).hide();
					$("#rateUpdateData").html(result);
				}
		})
	return false;
 }
</script>
<?php if($_REQUEST['id']!=''){ ?>
<script>
window.onload = function() {rateLetterMasterFunction(); }

</script>

<?php } ?>
<?php if($_REQUEST['id']!=''){ ?>
<script>
window.onload = function() {rateLetterMasterFunction(); }
 function ReviseRateLetterAutoMail(hotelId,seasonId,company_id,revise_code,sub_code){
		 
	  	
	$.ajax({
		   type: "POST",
		   url: 'pdf-template/generateHotelwiseContractPdf.php',
		   data: 'id='+hotelId+'&session='+seasonId+'&company_id='+company_id+'&hotelwisetype=emailToHotel&revise_code='+revise_code+'&sub_code='+sub_code, 
		   success: function (result) {	
		   resultArray = result.split('###');
		   
		   	
			window.location.href = "editRateLetters.php?id="+resultArray[0]+"&hotelId="+resultArray[1]+"&action=edit&page=1&sub_code="+sub_code+'&revise_code='+revise_code;
			}
		})	
  


}
</script>

<?php } ?>
<?php include_once("includes/footer.php")?>

<script type="text/javascript">
	$('#my_popup_yes').click(function(){

		var id = $('#id').val();
		if(id==undefined || id==""){
			var id = $('#rateInsertId').val();
		}
		
    window.location.href='editRateLetters.php?id='+id+'&action=edit&page=1';
  });

  $('#my_popup_no').click(function(){
     window.location.href='manageRateLetters.php';
  });

  function companyInsideZone(){
  	var company_id=$('#company_id').val();
  	//console.log(company_id);
  	if(company_id !=""){
	  	$.ajax({
	  	 type        : 'POST',
	  	 url         : 'ajax/ajaxCompanyInsideZone.php', 
	  	 data        : 'id='+company_id,
	  	 success     : function(data){
	  	  //console.log(data);
	  	  if(data == 1){
	  	  	$("#companyError").html("");
	  	  	$("#saveButton").removeAttr("disabled");
	  	  	$("#generateButton").removeAttr("disabled");
	  	  }
	  	  else{
	  	  	$("#companyError").html("This company Not in your zone !");
	  	  	$("#saveButton").attr("disabled","disabled");
	  	  	$("#generateButton").attr("disabled","disabled");
	  	  }
	  	
	  	 } 
	  	})
	}  	

  }

  //COMPANY AUTO COMPLETE START==================================================================
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
	  //COMPANY AUTO COMPLETE END==================================================================
 

  	


</script>