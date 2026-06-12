<?php include_once("../../config/auto_loader.php");
//error_reporting(E_ALL);
/*if($_SESSION['userLevel'] !=1){
  restrictRateForZone($connNew,addslashes(encryptor('decrypt',$_REQUEST['id'])));
}*/

checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE_DETAILS_UNIT,'export');



$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
$sqlQuery = "SELECT * FROM ".TBL_RATE_UNIT." WHERE id='".encryptor('decrypt',$_REQUEST['id'])."' ";

$id_hotel=selectColumn(TBL_RATE_DETAILS_UNIT,'hotel_id','WHERE rate_id="'.encryptor('decrypt',$_REQUEST['id']).'" ');
$hotelName=selectColumn(TBL_HOTELS,'CONCAT(name,", ",city)','WHERE id="'.$id_hotel.'" ');


  
$res = mysqli_query($conn,$sqlQuery);
if($res){

  $row = mysqli_fetch_object($res);
  $market = selectColumn(TBL_RATE_MARKET,'name','WHERE id="'.$row->rate_level_id.'" ');
  
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
  
  $id_user = selectColumn(TBL_AREAS,'user_id','where id="'.$id_area.'" ');
  
  $handeledby = selectColumn(TBL_USERS,'name','where id="'.$id_user.'" ');
  $id_designation = selectColumn(TBL_USERS,'designation','where id="'.$id_user.'" ');
  $designation = selectColumn(TBL_DESIGNATION_MASTER,'name','where id="'.$id_designation.'" ');
  $handeledbyEmail = selectColumn(TBL_USERS,'email','where id="'.$id_user.'" ');

  $season = selectColumn(TBL_RATE_SEASON,'name','where id="'.$row->seasonId.'" ');
  
}
$content="
  <style>
    body,table,tbody,tr,td,span,div,p{
          font-family: Georgia !important; 
          font-size:10pt !important;  
          }
  </style>
";
$content.="<table>
              <tr>
                  <td class='forTd' >Date ".date('d/m/Y')."</td>
                   
              </tr>
              <tr>
                <td></td>
              </tr>
              <tr>
                <td>
                  <b>".ucwords($customer)."</b><br>".$customerDesignation."<b><br><b>".$company_cust."</b>
                </td>
              </tr>
          </table>";
$content.="<table>
              <tr>
                <td></td>
              </tr>
              <tr>
                  <td class='forTd' ><b>Dear ".($customerLastName==''?$customer:$customerTitle.' '.$customerLastName).",</b></td>
              </tr>
              <tr>
                <td></td>
              </tr>
          </table>";

$mailContent=selectColumn(TBL_DOCUMENT_CONFIG,'id_mail_content','WHERE doc_type=2 AND id_shop="'.$_SESSION['shop'].'" ');
$mailFooter=selectColumn(TBL_DOCUMENT_CONFIG,'id_mail_footer','WHERE doc_type=2 AND id_shop="'.$_SESSION['shop'].'" ');

$content .= "<table >
        </tr>
            <td class='forTd' style='font-family: Georgia !important; 
          font-size:10pt !important; '>
                ".selectColumn(TBL_RATE_MAIL_FORMAT,'description','WHERE id_shop="'.$_SESSION['shop'].'" AND id="'.$mailContent.'" AND type=2')."
            </td>
        </tr>
      </table>";
$sqlUserDetail = $db->fetch_obj2(selectSql(TBL_USERS,"WHERE `id` = '".$row->last_modified_by."'",''));
$handeledByDesignation =selectColumn(TBL_DESIGNATION_MASTER,'name','WHERE id="'.$sqlUserDetail->designation.'" ');
$address1   = $sqlUserDetail->address;
$address2   =   $sqlUserDetail->address2;
$city = selectColumn(TBL_USERS,'city','WHERE id="'.$row->last_modified_by.'" AND id_shop="'.$_SESSION['shop'].'"');
$zip = selectColumn(TBL_USERS,'zip','WHERE id="'.$row->last_modified_by.'" AND id_shop="'.$_SESSION['shop'].'"');
$content.="<table>
              <tr>
                  <td class='forTd' ><b>".ucwords($sqlUserDetail->name)."</b></td>
                  
              </tr>
              <tr>
                <td class='forTd' ><b>".ucwords($handeledByDesignation)."</b><br></td>
              </tr>
          </table>";


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
                M: ".$sqlUserDetail->mobile." | T: ".$phone." | Email : ".$sqlUserDetail->email."</span>               
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
$file_name = $company.'-'.$market.'-'.$season.'-'.$hotelName.'.pdf';

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

$file_name =$company.'-'.$market.'-'.$season.'-'.$hotelName.'.pdf';

$sent =  $sendMail->sendRateMail('support@roomstatushub.com', $_POST['mailId'], $_POST['subject'],$_POST['sendcontent'],$_POST['ccId'],"../mailattachunit/".$file_name,ucwords($handeledby),$handeledbyEmail);

if($sent){
  $message = "Mail Sent Successfully";
  unlink("../mailattachunit/".$file_name);
}
else{
  $message = "Fail to send mail. Try later !";
  unlink("../mailattachunit/".$file_name);
}

echo "<script type='text/javascript'>alert('$message');window.location.href='../manageRateLettersUnit.php';</script>";

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
                <lable>CC</lable>
                <input class="form-control" placeholder="CC:" name="ccId" value="<?php echo $handeledbyEmail.','.$salesHeadEmail;?>" data-parsley-required data-parsley-type="cc email">

              </div>

              <div class="form-group">
            <lable>Subject</lable>
                <input class="form-control" placeholder="Subject:" name="subject" value="<?php echo strtoupper($company)." || PREFERRED RATES CONTRACT FOR ".strtoupper($season) ?> || CONCEPT HOSPITALITY-THE FERN HOTELS & RESORTS" data-parsley-required >

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
     url         : '../pdf-template/generateRateUnitPdf.php', 
     data        : 'id='+rate_id+'&location=set',
     success     : function(data){
     } ,
     complete : function(){
        
     }
    })
  });
</script>

