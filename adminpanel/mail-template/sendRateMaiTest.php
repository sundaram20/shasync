<?php include_once("../../config/auto_loader.php");
//$_POST['mailId']='shashafeer@gmail.com';
//echo $_POST['ccId']='shashafeer@gmail.com';
//echo '--'.$sendMail->sendRateMail('support@roomstatushub.com',$_POST['mailId'], 'test New severr','tttttt',$_POST['ccId'],"../mailattach/-Financial Year 2020-21.pdf".$file_name,ucwords('shafeer'),'shafeersyed@yahoo.co.in');
//die;
if($_SESSION['userLevel'] !=1){
  restrictRateForZone($connNew,addslashes(encryptor('decrypt',$_REQUEST['id'])));
}

//checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE_DETAILS,'view');



$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
$sqlQuery = "SELECT * FROM ".TBL_RATE." WHERE id='".encryptor('decrypt',$_REQUEST['id'])."' ";
	
$res = mysqli_query($conn,$sqlQuery);
if($res){

	$row = mysqli_fetch_object($res);
	
	$customer = selectColumn(TBL_CUSTOMER,'CONCAT(title," ",first_name," ",last_name)','where id_customer="'.$row->id_contacts.'" ');

  $customerTitle = selectColumn(TBL_CUSTOMER,'title','where id_customer="'.$row->id_contacts.'" ');

  $customerLastName = selectColumn(TBL_CUSTOMER,'last_name','where id_customer="'.$row->id_contacts.'" ');

  $id_designation = selectColumn(TBL_CUSTOMER,'designation','where id_customer="'.$row->id_contacts.'" ');
  $id_company_cust =selectColumn(TBL_CUSTOMER,'id_company','where id_customer="'.$row->id_contacts.'" ');
  $company_cust = selectColumn(TBL_COMPANY,'name','where id_company="'.$id_company_cust.'" ');

  $customerDesignation = $designation = selectColumn(TBL_DESIGNATION_MASTER,'name','where id="'.$id_designation.'" ');

	$to = selectColumn(TBL_CUSTOMER,'email','where id_customer="'.$row->id_contacts.'" ');

	$company = selectColumn(TBL_COMPANY,'name','where id_company="'.$row->company_id.'" ');
	
	$id_area = selectColumn(TBL_COMPANY,'area','where id_company="'.$row->company_id.'" ');
	
	//$id_user = selectColumn(TBL_AREAS,'user_id','where id="'.$id_area.'" ');
	$id_user =  $_SESSION['userId'];
	$handeledby = selectColumn(TBL_USERS,'name','where id="'.$id_user.'" ');
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
              <tr>
                <td style='font-family:Georgia !imporant;'>
                  <span style='font-size:10pt'><span style='font-family:Georgia,serif'><b>".ucwords($customer)."</b><br>".$customerDesignation."<b><br><b>".$company_cust."</b></span></span>
                </td>
              </tr>
          </table>";
$content.="<table>
              <tr>
                <td></td>
              </tr>
              <tr>
                  <td class='forTd' style='font-family:Georgia !imporant;' ><span style='font-size:10pt'><span style='font-family:Georgia,serif'><b>Dear ".($customerLastName==''?$customer:$customerTitle.' '.$customerLastName).",</b></span></span></td>
              </tr>
              <tr>
                <td></td>
              </tr>
          </table>";

$mailContent=selectColumn(TBL_DOCUMENT_CONFIG,'id_mail_content','WHERE doc_type=1 AND id_shop="'.$_SESSION['shop'].'" ');
$mailFooter=selectColumn(TBL_DOCUMENT_CONFIG,'id_mail_footer','WHERE doc_type=1 AND id_shop="'.$_SESSION['shop'].'" ');
$setContent = selectColumn(TBL_RATE_MAIL_FORMAT,'description','WHERE id_shop="'.$_SESSION['shop'].'" AND id="'.$mailContent.'" AND type=2');
if($setContent!=''){
$content .= "<table >
				</tr>
				    <td class='forTd' style='font-family: Georgia !important; 
          font-size:10pt !important; '>
                ".selectColumn(TBL_RATE_MAIL_FORMAT,'description','WHERE id_shop="'.$_SESSION['shop'].'" AND id="'.$mailContent.'" AND type=2')."
            </td>
				</tr>
			</table>";
}
$content.="<table>
              <tr>
                  <td class='forTd' ><span style='font-size:10pt'><span style='font-family:Georgia,serif'><b>".ucwords($handeledby)."</b></span></span></td>
                  
              </tr>
              <tr>
                <td class='forTd' ><span style='font-size:10pt'><span style='font-family:Georgia,serif'><b>".ucwords($designation)."</b></span></span><br></td>
              </tr>
          </table>";

$address1 = selectColumn(TBL_USERS,'address','WHERE id="'.$id_user.'" AND id_shop="'.$_SESSION['shop'].'"');

$address2 = selectColumn(TBL_USERS,'address2','WHERE id="'.$id_user.'" AND id_shop="'.$_SESSION['shop'].'"');
$city = selectColumn(TBL_USERS,'city','WHERE id="'.$id_user.'" AND id_shop="'.$_SESSION['shop'].'"');
$zip = selectColumn(TBL_USERS,'zip','WHERE id="'.$id_user.'" AND id_shop="'.$_SESSION['shop'].'"');
$mobile = selectColumn(TBL_USERS,'mobile','WHERE id="'.$id_user.'" AND id_shop="'.$_SESSION['shop'].'"');
$phone = selectColumn(TBL_USERS,'phone','WHERE id="'.$id_user.'" AND id_shop="'.$_SESSION['shop'].'"');
$teamId = selectColumn(TBL_USERS,'ids_team','WHERE id="'.$id_user.'" AND id_shop="'.$_SESSION['shop'].'"');

$salesHeadId = selectColumn(TBL_TEAM,'id_user_level_1','WHERE id="'.$teamId.'" ');
$salesHeadEmail = selectColumn(TBL_USERS,'email','WHERE id="'.$salesHeadId.'" ');
$website = selectColumn(TBL_SHOP,'website_url','WHERE id="'.$_SESSION['shop'].'" ');
$companyNameUser = selectColumn(TBL_USERS,'company','WHERE id="'.$id_user.'" ');

$formalCompanyName= selectColumn(TBL_SHOP,'formal_name','WHERE id="'.$_SESSION['shop'].'" ');

$content .= "<table style='width : 100%;'>
        <tr><td></td></tr>
        </tr>
            <td ><span style='color:green;font-weight:bold;'>".$companyNameUser."</span><span style='font-family:Georgia !imporant;font-size:9pt !imporant;'><br>".$formalCompanyName."<br>
                ".$address1.','.$address2.','.$city.'-'.$zip."<br>
                M: ".$mobile." | T: ".$phone." | Email : ".$handeledbyEmail."</span>               
            </td>
        </tr>
      </table>";

$content .= "<table style='font-family:Georgia !imporant;font-size:8pt !imporant;'>
                <tr style='font-family:Georgia !imporant;font-size:8pt !imporant;' ><td style='font-family:Georgia !imporant;font-size:8pt !imporant;'><span style='font-family:Georgia !imporant;font-size:8pt !imporant;'>".selectColumn(TBL_RATE_MAIL_FORMAT,'description','WHERE id_shop="'.$_SESSION['shop'].'" AND type=3 AND id="'.$mailFooter.'" ')."</span></td></tr></table>";

      





/*$signature = $SITE_URL.'/uploaded_files/shop/'.selectColumn(TBL_SHOP,'mail_signature','WHERE id="'.$_SESSION['shop'].'" ');
$content .= "<table style='width : 100%;'>
        </tr>
            <td>
                <img src='".$signature."' />
            </td>
        </tr>
      </table>"; */
   
   

//---------------------------------------------------------------------------------------------------------.
//$file_name = $company.'-'.$season.'.pdf';
$file_name =str_replace(array( '\'', '_', ' / ' , ';', '<', '>',' ' ), '-', urldecode($company)).'-'.$season.'.pdf';
?>

<style type="text/css">
	.forTd{
		padding: 10px;
	}
</style>

<?php
	if($_POST['submit'] == 'submit'){

    /*if(isset($_FILES['attachment'])){
          $errors= array();
          $file_name = $_FILES['attachment']['name'];
          $file_size = $_FILES['attachment']['size'];
          $file_tmp = $_FILES['attachment']['tmp_name'];
          $file_type = $_FILES['attachment']['type'];
          $file_ext=strtolower(end(explode('.',$_FILES['attachment']['name'])));
          
          $expensions= array("jpeg","jpg","png","pdf");
          
          if(in_array($file_ext,$expensions)=== false){
             $errors[]="extension not allowed, please choose a PDF, JPEG or PNG file.";
          }
          
          if($file_size > 2097152) {
             $errors[]='File size must be excately 2 MB';
          }
          
          if(empty($errors)==true) {
             move_uploaded_file($file_tmp,"../mailattach/".$file_name); //The folder where you would like your file to be saved
          }else{
             print_r($errors);
          }
       }*/
$attachPath	=	$_SERVER['DOCUMENT_ROOT']."/sync/adminpanel/";
//$file_name = $company.'-'.$season.'.pdf';
$file_name =str_replace(array( '\'', '_', ' / ' , ';', '<', '>',' ' ), '-', urldecode($company)).'-'.$season.'.pdf';
		
//$sent =  $sendMail->sendRateMail('support@roomstatushub.com', $_POST['mailId'], $_POST['subject'],$_POST['sendcontent'],$_POST['ccId'],$attachPath."mailattach/".$file_name,ucwords($handeledby),$handeledbyEmail);

 $to=$_POST['mailId']; $subject=$_POST['subject']; $body=$_POST['sendcontent'];
 $cc=$_POST['ccId'];
$attach=$attachPath."mailattach/".$file_name;
$fromName=$handeledbyEmail;
$addReplyTo=$handeledbyEmail;
//$to='roomstatushublogs@gmail.com';
$from='rsodelhi@fernhotels.com';
//$sub= 'Fern Email Testing ';
//$content='Testing';


$mail = new PHPMailer(); // create a new object	
$mail->IsSMTP(); // enable SMTP
$mail->SMTPDebug = 2; // debugging: 1 = errors and messages, 2 = messages only
$mail->SMTPAuth = true; // authentication enabled
$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
$mail->Host = "mail.fernhotels.com";
$mail->Port = 465; // or 587 //465
$mail->IsHTML(true);


$mail->Username = 'rsodelhi@fernhotels.com';//'rsodelhi@fernhotels.com';
$mail->Password = '$Unread#1234';
$mail->WordWrap = 50;
      $mail->isHTML(true);
      $mail->setFrom($from,$fromName);
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

      if($attach!='')
       	$mail->addAttachment($attach);

      $mail->Subject = $subject;
      $mail->Body = $body;
 
     $sent=  $mail->send();

if($sent){
  $message = "Mail Sent Successfully";
  unlink($attachPath."mailattach/".$file_name);

  $updateRec = "UPDATE ".TBL_RATE." SET id_mail_sent_by='".$_SESSION['userId']."',mail_sent_at='".date('Y-m-d H:i:s')."' WHERE id='".encryptor('decrypt',$_REQUEST['id'])."' ";
  mysqli_query($connNew,$updateRec);
}
else{
  $message = "Fail to send mail. Try later !";
  unlink($attachPath."mailattach/".$file_name);
}



	// To send HTML mail, the Content-type header must be set

	/*$headers[] = 'MIME-Version: 1.0';

	$headers[] = 'Content-type: text/html; charset=iso-8859-1';



	// Additional headers

	$headers[] = 'To: '.$_POST['mailId'];

	$headers[] = 'From: '.$handeledbyEmail;

	$headers[] = 'Cc: '.$_POST['ccId'];
	/*$headers[] = 'To: '.'support@roomstatushub.com';

	$headers[] = 'From: '.'hiteshaloney75@gmail.com';

	$headers[] = 'Cc: '.$_POST['ccId'];*/

	//$headers[] = 'Bcc: birthdaycheck@example.com'shafeersyed@yahoo.in,vijaymaharaja6@gmail.com;



	// Mail it

	//mail($_POST['mailId'], $_POST['subject'], $_POST['sendcontent'], implode("\r\n", $headers));




	/*$message = "Mail Sent Successfully";

	echo "<script type='text/javascript'>alert('$message');window.location.href='../manageRateLetters.php';</script>";*/





	}
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
            <img id="loadAni" src="loadingAni.gif">
			<form action="" method="post" enctype="multipart/form-data" autocomplete="off" data-parsley-validate>

            <div class="box-body">

              <div class="form-group">
                   <lable>To</lable>             
                <input class="form-control" placeholder="To:" name="mailId" value="<?php echo $to; ?>" data-parsley-required data-parsley-type="email">

                <input type="hidden" id="rate_id_mail" name="rate_id_mail" value="<?=$_REQUEST['id'];?>"/>

              </div>

			  <div class="form-group">
                <lable>CC</lable><?php $salesHeadEmail2 = $salesHeadEmail!=''?','.$salesHeadEmail:'';?>
                <input class="form-control" placeholder="CC:" name="ccId" value="<?php echo $handeledbyEmail.$salesHeadEmail2;?>" data-parsley-required data-parsley-type="cc email">

              </div>

              <div class="form-group">
            <lable>Subject</lable>
                <input class="form-control" placeholder="Subject:" name="subject" value="<?php echo strtoupper($company)." || PREFERRED RATES CONTRACT FOR ".strtoupper($season) ?> ||  <?php echo strtoupper(selectColumn(TBL_SHOP,'name','WHERE id="'.$_SESSION['shop'].'" '));?>" data-parsley-required >

              </div>
              <div class="form-group">
                <lable>Attachment : &nbsp</lable><span style="color: green;font-weight: bold;"><?php echo $file_name; ?></span>
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
$rateletterurl	=	selectColumn('mst_document_config','rateletter_url','WHERE id_shop="'.$_SESSION['shop'].'" and doc_type=1 and status=1 ');
if($rateletterurl!=''){
}else{
$rateletterurl='generateRatePdf.php';
}

include_once("../includes/footer.php");

?>
<script type="text/javascript">
  $(document).ajaxStart(
      function() {
          $( "#loadAni" ).show();
          $( "form" ).hide();
      }
  ); 
   $(document).ajaxComplete(
      function() {
          $( "#loadAni" ).hide();
          $( "form" ).show();
      }
  ); 
  $("document").ready(function(){
    $( "form" ).hide();  
    var rate_id = $("#rate_id_mail").val();
    $.ajax({
     type        : 'POST',
     url         : '../pdf-template/<?php echo $rateletterurl;?>', 
     data        : 'id='+rate_id+'&location=set',
     success     : function(data){
     } ,
     complete : function(){
        
     }
    })
  });
</script>

