<?php include_once("../../config/auto_loader.php");
//$_POST['mailId']='shashafeer@gmail.com';
//echo $_POST['ccId']='shashafeer@gmail.com';
//echo '--'.$sendMail->sendRateMail('support@roomstatushub.com',$_POST['mailId'], 'test New severr','tttttt',$_POST['ccId'],"../mailattach/-Financial Year 2020-21.pdf".$file_name,ucwords('shafeer'),'shafeersyed@yahoo.co.in');


//debugData($_SESSION);

//checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE_DETAILS,'view');



$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
$sqlQuery = executeSql("SELECT * FROM support WHERE id='".encryptor('decrypt',$_REQUEST['id'])."' ");
	
if($sqlQuery){

    $row = $db->fetch_object2($sqlQuery);
    
    $customer_sql = executeSql("select * from `fs_customer` WHERE id_customer = '".$row->id_contacts."'");
    $customer_row = $db->fetch_object2($customer_sql);

    $customer = $customer_row->title . " " . $customer_row->first_name . " " . $customer_row->last_name;

	$to = selectColumn(TBL_CUSTOMER,'email','where id_customer="'.$row->id_contacts.'" ');

	
	//$id_user = selectColumn(TBL_AREAS,'user_id','where id="'.$id_area.'" ');
	$id_user =  $_SESSION['userId'];
	//$handeledby = selectColumn(TBL_USERS,'name','where id="'.$id_user.'" ');
    $handeledby = 'Aadiyar Infotech';
	$id_designation = selectColumn(TBL_USERS,'designation','where id="'.$id_user.'" ');
	$designation = selectColumn(TBL_DESIGNATION_MASTER,'name','where id="'.$id_designation.'" ');
	$handeledbyEmail = selectColumn(TBL_USERS,'email','where id="'.$id_user.'" ');

	$season = selectColumn(TBL_RATE_SEASON,'name','where id="'.$row->seasonId.'" ');
	
}
$content.="
  <style>
    body,table,tbody,tr,td,span,div,p{
          font-family: Georgia !important; 
          font-size:10pt !important;  
          }
  </style>
";
$content.="<table>
              <tr>
                  <td class='forTd' style='font-family:Georgia !imporant;'><span style='font-size:10pt'><span style='font-family:Georgia,serif'>Date ".date('d/m/Y')."</span></span></td>
                   
              </tr>
              <tr>
                <td></td>
              </tr>

          </table>";
$content.="<table>
              <tr>
                <td></td>
              </tr>
              <tr>
				  
				  <td class='forTd' style='font-family:Georgia !imporant;' ><span style='font-size:10pt'><span style='font-family:Georgia,serif'><b>Dear ".$customer.",</b></span></span></td>
              </tr>
              
          </table>";

$mailContent=selectColumn(TBL_DOCUMENT_CONFIG,'id_mail_content','WHERE doc_type=1 AND id_shop="'.$_SESSION['shop'].'" ');
$mailFooter=selectColumn(TBL_DOCUMENT_CONFIG,'id_mail_footer','WHERE doc_type=1 AND id_shop="'.$_SESSION['shop'].'" ');
$setContent = selectColumn(TBL_RATE_MAIL_FORMAT,'description','WHERE id_shop="'.$_SESSION['shop'].'" AND id="37" AND type=2');
if($setContent!=''){
/*$content .= "<table>
				<tr>
				    <td class='forTd' style='font-family: Georgia !important; 
          font-size:10pt !important; '>
                ".selectColumn(TBL_RATE_MAIL_FORMAT,'description','WHERE id_shop="'.$_SESSION['shop'].'" AND id="30" AND type=2')."
            </td>
				</tr>
			</table>";*/
			
		$content .=selectColumn(TBL_RATE_MAIL_FORMAT,'description','WHERE id_shop="'.$_SESSION['shop'].'" AND id="37" AND type=2');	
}

//Table Content

					

$content .= "<table class='table table-striped text-center' style='border-spacing:0;width:40%;text-align:center;'>
  <tbody>
    <tr>
      <td style='border:1px solid #252525;'><b> Your Last Remark </b></td>

     
    </tr>";

									
		$remark	=	selectColumn('support_details', 'support_remark', "WHERE `id_support` = '".$row->id."' ORDER BY id desc LIMIT 0,1");
	$content .= "<tr>
        <td style='border:1px solid #252525;'>".$remark."</td>
    
      
    </tr>";
	
	
  $content .= "</tbody>
</table>";



$content .= " <table style='font-family:Georgia !imporant;font-size:8pt !imporant;'>
                <tr style='font-family:Georgia !imporant;font-size:8pt !imporant;' ><td style='font-family:Georgia !imporant;font-size:8pt !imporant;'><span style='font-family:Georgia !imporant;font-size:8pt !imporant;'>".selectColumn(TBL_RATE_MAIL_FORMAT,'description','WHERE id_shop="'.$_SESSION['shop'].'" AND type=3 AND id="38" ')."</span></td></tr></table>";

      



//---------------------------------------------------------------------------------------------------------.
//$file_name = $company.'-'.$season.'.pdf';

?>
<style type="text/css">
	.forTd{
		padding: 10px;
	}
</style>
<?php
	if($_POST['submit'] == 'submit'){

   
$attachPath	=	$_SERVER['DOCUMENT_ROOT']."/sync/adminpanel/";
//$file_name = $company.'-'.$season.'.pdf';
		
//$sent =  $sendMail->sendRateMail('support@roomstatushub.com', $_POST['mailId'], $_POST['subject'],$_POST['sendcontent'],$_POST['ccId'],$attachPath."mailattach/".$file_name,ucwords($handeledby),$handeledbyEmail);

 $to=$_POST['mailId']; $subject=$_POST['subject']; $body=$_POST['sendcontent'];
 $cc=$_POST['ccId'];

$fromName=ucwords($handeledby);
$addReplyTo=$handeledbyEmail;
//$to='roomstatushublogs@gmail.com';


$id_user =  $_SESSION['userId'];
$user_custom_smtp = selectColumn(TBL_USERS,'user_custom_smtp','where id="'.$id_user.'" ');
$smtp_email = selectColumn(TBL_USERS,'smtp_email','where id="'.$id_user.'" ');
$smtp_password = selectColumn(TBL_USERS,'smtp_password','where id="'.$id_user.'" ');
if($user_custom_smtp=='1' &&  $smtp_email!='' &&  $smtp_password!=''){
	$SMTPUsername=$smtp_email;
	$SMTPPassword=$smtp_password;
	$SMTPHost="smtp.gmail.com";
	$SMTPPort='465';
	
}else{
	
	$SMTPUsername='support@roomstatushub.com';;
	$SMTPPassword='kxfm xrpv znoi xmhx';
	$SMTPHost="smtp.gmail.com";
	$SMTPPort='465';
	}

$mail = new PHPMailer(); // create a new object	
$mail->IsSMTP(); // enable SMTP
//$mail->SMTPDebug = 2; // debugging: 1 = errors and messages, 2 = messages only
$mail->SMTPAuth = true; // authentication enabled
$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
$mail->Host = $SMTPHost;//"mail.fernhotels.com";
$mail->Port = $SMTPPort;//465; // or 587 //465
$mail->IsHTML(true);


$mail->Username = $SMTPUsername;//'rsodelhi@fernhotels.com';//'rsodelhi@fernhotels.com';
$mail->Password = $SMTPPassword;//'$Unread#1234';
$mail->WordWrap = 50;
      $mail->isHTML(true);
      $mail->setFrom($SMTPUsername,$fromName);
      $toArray = explode(',',$to);
      $ccArray = explode(',',$cc);
      for($i=0;$i<count($toArray);$i++){
        $mail->addAddress($toArray[$i]);
      }
     
      for($i=0;$i<count($ccArray);$i++){
        $mail->addCC($ccArray[$i]);
      }

      if($addReplyTo!="")
        $mail->AddReplyTo($addReplyTo,$fromName);

      

      $mail->Subject = $subject;
      $mail->Body = $body;
 
     $sent=  $mail->send();

if($sent){
	
	$updateDaily = "UPDATE support SET mail_sent=mail_sent+1 WHERE id='".encryptor('decrypt',$_REQUEST['id'])."' ";
	mysqli_query($conn,$updateDaily);
	
	
  $message = "Mail Sent Successfully";



}
else{
  $message = "Fail to send mail. Try later !";

}

echo "<script type='text/javascript'>alert('$message');window.location.href='../support.php';</script>";



	}
?>
<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>

<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper"> 
  
  <!-- Content Header (Page header) -->
  
  <section class="content-header">
    <h1> Mailbox </h1>
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
          <?php /*?> <img id="loadAni" src="loadingAni.gif"><?php */?>
          <form action="" method="post" enctype="multipart/form-data" autocomplete="off" data-parsley-validate>
            <div class="box-body">
              <div class="form-group">
                <lable>To</lable>
                <input class="form-control" placeholder="To:" name="mailId" value="<?php echo $to; ?>" data-parsley-required data-parsley-type="email">
                <input type="hidden" id="rate_id_mail" name="rate_id_mail" value="<?=$_REQUEST['id'];?>"/>
              </div>
              <div class="form-group">
                <lable>CC</lable>
                <?php $salesHeadEmail2 = $salesHeadEmail!=''?','.$salesHeadEmail:'';?>
                <input class="form-control" placeholder="CC:" name="ccId" value="<?php echo $handeledbyEmail;?>" data-parsley-required data-parsley-type="cc email" >
                <!-- <input class="form-control" placeholder="CC:" name="ccId" value="<?php echo $handeledbyEmail.$salesHeadEmail2;?>" data-parsley-required data-parsley-type="cc email">--> 
                
              </div>
              <div class="form-group">
                <lable>Subject</lable>
                <input class="form-control" placeholder="Subject:" name="subject" value="<?php echo " Your Support Summary & Feedback Request  ".strtoupper($season) ?> ||  <?php echo strtoupper(selectColumn(TBL_SHOP,'name','WHERE id="'.$_SESSION['shop'].'" '));?>" data-parsley-required >
              </div>
              <div class="form-group">
                <label>Content</label>
                <textarea id="description" class="ckeditor" name="sendcontent">

                     <?php echo $content; ?>

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
            <?php //$countSql SELECT COUNT(*)  FROM `fs_rate` WHERE `id_mail_sent_by` = $_SESSION['userId'] AND `mail_sent_at` date('d,m,y') ORDER BY `id`  DESC; 

              $countSql = selectColumn(TBL_RATE,'COUNT(*)','where `id_mail_sent_by` = '.$_SESSION['userId'].' AND `mail_sent_at` LIKE  "%'.date('Y-m-d').'%" ORDER BY `id`  DESC;');
             // echo $countSql;

            ?>
            <div class="box-footer">
              <div class="pull-right">
                <input type="hidden" name="submit" value="submit" />
                <?php if($countSql<60){ ?>
                <button type="submit" class="btn btn-primary" ><i class="fa fa-envelope-o"></i> Send</button>
                <?php  }  else{ ?>
                <span data-toggle="modal" data-target="#countModal" class="btn btn-primary" ><i class="fa fa-envelope-o"></i> Send</span>
                <?php }
                  ?>
              </div>
            </div>
            <!--end 0f footer-->
            
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

<div class="modal" id="countModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body text-center" style="font-size:18px;"> You have exceeded your Today's Email Limit </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal" aria-label="close">Close</button>
      </div>
    </div>
  </div>
</div>
<?php 
$rateletterurl	=	selectColumn('mst_document_config','rateletter_url','WHERE id_shop="'.$_SESSION['shop'].'" and doc_type=1 and status=1 ');
if($rateletterurl!=''){
}else{
$rateletterurl='generateRatePdf.php';
}

include_once("../includes/footer.php");

?>
<script type="text/javascript">
 
</script> 
