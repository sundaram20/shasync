<?php include_once("../../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_ORDERS,'view');
//---------------------------------------------------------------------------------------------------------
if(addslashes($_SESSION['shop'])==1){
	$ShopShortCode	=	'WH';
	}
  
if($_POST['submit'] == 'submit'){
$sendMail->sendMail('noreply@roomstatushub.com', $_POST['mailId'], $_POST['subject'], $_POST['sendcontent'],$_POST['ccId']);	
executeSQl("UPDATE `".TBL_ORDERS."` set reminder=reminder+1 where id_order=".addslashes(encryptor('decrypt',$_POST['eId']))."");

$message = "Mail Sent Successfully";
echo "<script type='text/javascript'>alert('$message');window.location.href='../manageHoldOrders.php';</script>";
}

$resShop  =  executeSQl("SELECT * FROM `".TBL_SHOP."` WHERE id= '".addslashes($_SESSION['shop'])."'");
 $rowShop = $db->fetch_object2($resShop);

		$sqlOrderDetail = executeSQl("SELECT * from `".TBL_ORDERS."` where `".TBL_ORDERS."`.booking_status='2' and `".TBL_ORDERS."`.id_order= '".addslashes(encryptor('decrypt',$_REQUEST['mailId']))."'"); 
		 $rowOrderDetail = fetch_object($sqlOrderDetail); 
		 
    $sqlGuestDetail = executeSQl("SELECT * FROM `".TBL_CUSTOMER."` WHERE type='1' and id_customer= '".addslashes($rowOrderDetail->id_customer)."'"); 
		 $rowGuestDetail = fetch_object($sqlGuestDetail); 
		
 	$resHotelDetail = selectSql(TBL_HOTELS,"where id='".addslashes($rowOrderDetail->id_hotel)."' ",' ORDER BY `name`'); 
		  $resultHotelDetail = fetch_object($resHotelDetail); 
if($rowOrderDetail->id_company_person>0){  
	$resContact = selectSql(TBL_CUSTOMER,"where type='2' and id_customer='".addslashes($rowOrderDetail->id_company_person)."'",''); 
		  $resultContact = fetch_object($resContact); }
		 
	$resCompany = selectSql(TBL_COMPANY,"where id_company='".addslashes($rowOrderDetail->id_company)."'",''); 
		  $resultCompany = fetch_object($resCompany); 
		  
	if($resultCompany->id_default_group != '0'){	  
	$content ='<p><strong>'.$resultContact->first_name.' '.$resultContact->last_name.'</strong><br /><strong>'.$resultCompany->name.'</strong><br />
	<strong>'.$resultCompany->mobile.'</strong><br />
   				 <br />';
				 $emailId= $resultContact->email;
	}else{
			$emailId= $rowGuestDetail->email;
	}
	$content .= '
    
  Dear '.$resultContact->first_name.' '.$resultContact->last_name.', &nbsp;<br />
  Greetings!!!&nbsp;<br />
  
  
  <br />  <br />
  This is in reference to the booking of the caption guest, '.$rowGuestDetail->first_name.' '.$rowGuestDetail->last_name.' from '.dateformat_date($rowOrderDetail->checkin).' to '.dateformat_date($rowOrderDetail->checkout).' at '.$resultHotelDetail->name.', '.$resultHotelDetail->city.' wherein the  voucher / Payments is due to be released by '.dateformat_date($rowOrderDetail->payment_date).'
  
    <br />  <br />
  You are requested to send us the  voucher / Payments  by the specified date to reconfirm the booking and avoid automatic system cancellation of booking.  
  <br />  <br />
  In case of any further assistance please feel free to get in touch with us.
  <br />  <br />
  Please ignore if already paid.
      <br />  <br />
	  With Kind Regards.
	  <br /> 
	  Team consortium 
<br /> 
  

  D-1/31, First Floor , Palam Extension ,Sector 07, Dwarka , New Delhi 110075.<br>

Phone  : 011 -45665201 ,45665202 ,45665203 ,45665204


</address></p>';
  	/*<address><strong> '.$ShopShortCode.' '.$resultHotelDetail->name.'</strong><br>'.
								
							$resultHotelDetail->city.' - '.$resultHotelDetail->pincode.'<br>'.
								selectColumn(TBL_STATE,'name'," WHERE `id_state` = '".addslashes($resultHotelDetail->state)."'").', India <br>
								<strong>Phone :</strong> '.$resultHotelDetail->phone1.'<br>
								<strong>Email :</strong> '.$resultHotelDetail->email.'<br>
								<strong>Website :</strong> '.$rowShop->website_url.'*/
  
  
?>
<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>




  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Mailbox
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Mailbox</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- /.col -->
		
		
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Compose New Message</h3>
            </div>
            <!-- /.box-header -->
			<form action="" method="post" enctype="multipart/form-data" autocomplete="off" data-parsley-validate>
			<input type="hidden" name="eId" value="<?php echo $_REQUEST['mailId'];?>" />
            <div class="box-body">
              <div class="form-group">
                <input class="form-control" placeholder="To:" name="mailId" value="<?php echo $emailId; ?>" data-parsley-required data-parsley-type="email">
              </div>

              <div class="form-group">

                <input class="form-control" placeholder="CC:" name="ccId" value="<?php echo $rowShop->email; ?>" data-parsley-required data-parsley-type="cc email">

              </div>

              <div class="form-group">
                <input class="form-control" placeholder="Subject:" name="subject" value="VOUCHER / PAYMENT REMINDER" data-parsley-required >
              </div>
              <div class="form-group">
                    <textarea id="description" class="ckeditor" name="sendcontent"><?php echo $content; ?>
                   
                    </textarea>
              </div>
              <!--<div class="form-group">
                <div class="btn btn-default btn-file">
                  <i class="fa fa-paperclip"></i> Attachment
                  <input type="file" name="sendAttachment">
                </div>
                <p class="help-block">Max. 1MB</p>
              </div>-->
            </div>
			<div class="box-footer">
              <div class="pull-right">   
			  	<input type="hidden" name="submit" value="submit" />             
                <button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Send</button>
              </div>
              
            </div>
			</form>	
            <!-- /.box-body -->
            
            <!-- /.box-footer -->
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php 
include_once("../includes/footer.php");
?>
