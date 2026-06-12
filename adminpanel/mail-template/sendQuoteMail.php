<?php include_once("../../config/auto_loader.php");
//error_reporting(0);
/*if($_SESSION['userLevel'] !=1){
  restrictRateForZone($connNew,addslashes(encryptor('decrypt',$_REQUEST['id'])));
}*/

//checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE_DETAILS,'view');



$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
$sqlQuery = "SELECT * FROM ".TBL_SALES_QUOTE." WHERE id='".encryptor('decrypt',$_REQUEST['eId'])."' ";
	
$res = mysqli_query($conn,$sqlQuery);
if($res){
  $logo = selectColumn(TBL_SHOP,'image','WHERE id="'.$_SESSION['shop'].' "');	
  $row = mysqli_fetch_object($res);
   $custEmail = selectColumn(TBL_CUSTOMER,'email','WHERE id_customer="'.$row->id_contact.'" ');
  $company = selectColumn(TBL_COMPANY,'name','WHERE id_company="'.$row->id_company.'" ');
  $shopName = selectColumn(TBL_SHOP,'name','WHERE id="'.$_SESSION['shop'].'" ');
  $hotelName=selectColumn(TBL_HOTELS,'CONCAT(`name`,", ",`city`)','WHERE id="'.$row->hotel_id.'" ');
  $hotalTagline = selectColumn(TBL_HOTELS,'hotel_tagline','WHERE id="'.$row->hotel_id.'" ');
  $hotelDescription = selectColumn(TBL_HOTELS,'brief_description','WHERE id="'.$row->hotel_id.'" ');

  $general = selectColumn(TBL_HOTELS,'general_services','WHERE id="'.$row->hotel_id.'" ');

  $outdoor = selectColumn(TBL_HOTELS,'outdoor_services','WHERE id="'.$row->hotel_id.'" ');

  $dining = selectColumn(TBL_HOTELS,'dining_services','WHERE id="'.$row->hotel_id.'" ');

  $kids_related= selectColumn(TBL_HOTELS,'kids_services','WHERE id="'.$row->hotel_id.'" ');
  $conference= selectColumn(TBL_HOTELS,'conference_services','WHERE id="'.$row->hotel_id.'" ');

  $excursions= selectColumn(TBL_HOTELS,'excursions','WHERE id="'.$row->hotel_id.'" ');

  $gMapUrl = selectColumn(TBL_HOTELS,'gmap_url','WHERE id="'.$row->hotel_id.'" ');

  $quotedBy = selectColumn(TBL_USERS,'name','WHERE id="'.$row->id_user.'" ');
  $quotedByDesignationId = selectColumn(TBL_USERS,'designation','WHERE id="'.$row->id_user.'" ');
  $quotedByDesignation = selectColumn(TBL_DESIGNATION_MASTER,'name','WHERE id="'.$quotedByDesignationId.'" ');

  $quotedByMobile = selectColumn(TBL_USERS,'mobile','WHERE id="'.$row->id_user.'" ');

  $quotedByEmail = selectColumn(TBL_USERS,'email','WHERE id="'.$row->id_user.'" ');
  
  //// FETCHING SERVICES ////
  /*$sqlGen="SELECT * FROM ".TBL_GENERAL_SERVICES." WHERE id IN (".$ids_general.") ORDER BY name ";
  $resGen=mysqli_query($connNew,$sqlGen);
  $GenNum = mysqli_num_rows($resGen);

  $sqlOut="SELECT * FROM ".TBL_OUTDOOR_ACTIVITIES." WHERE id IN (".$ids_outdoor.") ORDER BY name ";
  $resOut=mysqli_query($connNew,$sqlOut);
  $OutNum = mysqli_num_rows($resOut);

  $sqlDin="SELECT * FROM ".TBL_DINING_SERVICES." WHERE id IN (".$ids_dining.") ORDER BY name ";
  $resDin=mysqli_query($connNew,$sqlDin);
  $DinNum = mysqli_num_rows($resDin);

  $sqlKid="SELECT * FROM ".TBL_KIDS_SERVICES." WHERE id IN (".$ids_kids_related.") ORDER BY name ";
  $resKid=mysqli_query($connNew,$sqlKid);
  $KidNum = mysqli_num_rows($resKid);

  $sqlConf="SELECT * FROM ".TBL_CONFERENCE_SERVICES." WHERE id IN (".$ids_conference.") ORDER BY name ";
  $resConf=mysqli_query($connNew,$sqlConf);
  $ConfNum = mysqli_num_rows($resConf);*/

  // SERVICES END ///

  //fetching user details
$address1 = selectColumn(TBL_USERS,'address','WHERE id="'.$row->id_user.'" AND id_shop="'.$_SESSION['shop'].'"');

$address2 = selectColumn(TBL_USERS,'address2','WHERE id="'.$row->id_user.'" AND id_shop="'.$_SESSION['shop'].'"');
$city = selectColumn(TBL_USERS,'city','WHERE id="'.$row->id_user.'" AND id_shop="'.$_SESSION['shop'].'"');
$zip = selectColumn(TBL_USERS,'zip','WHERE id="'.$row->id_user.'" AND id_shop="'.$_SESSION['shop'].'"');
$mobile = selectColumn(TBL_USERS,'mobile','WHERE id="'.$row->id_user.'" AND id_shop="'.$_SESSION['shop'].'"');
$phone = selectColumn(TBL_USERS,'phone','WHERE id="'.$row->id_user.'" AND id_shop="'.$_SESSION['shop'].'"');
$handeledbyEmail = selectColumn(TBL_USERS,'email','where id="'.$row->id_user.'" ');
$companyNameUser = selectColumn(TBL_USERS,'company','WHERE id="'.$row->id_user.'" ');
$formalCompanyName= selectColumn(TBL_SHOP,'formal_name','WHERE id="'.$_SESSION['shop'].'" ');

$personName = selectColumn(TBL_CUSTOMER,'CONCAT(title," ",first_name," ",last_name)','WHERE id_customer="'.$row->id_contact.'" ');
$personNameLast =selectColumn(TBL_CUSTOMER,'CONCAT(title," ",last_name)','WHERE id_customer="'.$row->id_contact.'" ');
$dateChecking = $row->checkin;
$dateCheckout = $row->checkout;

	$footer = selectColumn(TBL_RATE_MAIL_FORMAT,'description','WHERE id_shop="'.$_SESSION['shop'].'" AND type=3');
}
$content = "<style>
                div{
                  font-family:'Georgia' ;
                  font-size : 10pt;
                }
                
            </style>";
$content .="<div style='border-width:3px;border-style:double;width:100%;padding-left:10px;padding-right:5px;'>
                <div style='text-align:center;'><img style='margin-top:25px;' src='".$SITE_URL."/uploaded_files/shop/".$logo."' alt=".ucwords(strtolower(str_replace(':', '',$shopName)))."/></div>

                <div style='width:100%;'>
                    ".$personName."
                    
                    <br>
                    ".($company!=''?'<b>'.$company.'</b><br>':'')."
                    <br>
                    Dear ".$personNameLast.','."
                    <br>
                    <br>

                    Greetings From <b>".trim(ucwords(strtolower(str_replace(':', '',$shopName)))).'!'."</b>  
                    <br>
                    <br>
                    At the outset, we would like to thank you for showing interest in <b>".$hotelName."</b>. With reference to your query with us, we are pleased to share the availability of rooms and rates, along with a brief overview of the property :
                   
                    <br><br>
                    <div style='width:100%;'>
                    <table align='center' style='padding:5px 5px 5px 5px;border:1px solid black;margin:0 auto;width:50%;'>
                        <tr style='border:1px solid black;'>
                          <td style='border:1px solid black;' width='40%;'><span style='color:green;'><b>Accommodation</b></span><span style='float:right;color:green;'></span></td>
                          <td style='border:1px solid black;padding:5px 5px;'>As per the current status rooms are available from ".date('d',strtotime($row->checkin))." to ".date('d M, Y',strtotime($row->checkout))."</td>
                        <tr>
                        <tr style='border:1px solid black;'>
                          <td style='border:1px solid black;' width='40%;'><span style='color:green;'><b>Special Offer</b></span><span style='float:right;color:green;'></span></td>
                          <td style='border:1px solid black;padding:5px 5px;'>".$row->details."</td>
                        <tr>
                    </table>
                    <div>
                    <br>
                    
                    You are requested to kindly advise on the above so that we can do the needful and block rooms accordingly.

                    <div>
                        <p style='width:98%;font-weight:bold;padding:5px 5px;text-align:center;border:dashed 1px;'>
                          A brief overview of the hotel
                        </p>
                    </div>

                    <div style='text-align:center'>
                      <span style='color:green;font-size:12pt;'><u><b>".$hotelName."</u></b></span>
                      <br>
                      ".($hotalTagline!=''?'"'.$hotalTagline.'"':'')."
                    <div>

                    <div style='padding:2px 10px;text-align:justify;'>  
                      ".$hotelDescription."
                    </div>";

                   $content.="<div style='padding:0px 10px;text-align:justify;'>"; 
                  if($general!=''){
                  $content.= "<div style='margin-bottom:-40px !important;'><span style='margin-bottom:-50px !important;color:green;font-weight:bold;'>General Services:</span> ".str_replace('<p>','',$general)."</div>";
                  }  
                  if($outdoor!=''){
                   $content.="<div style='margin-bottom:-40px !important;'><span style='color:green;font-weight:bold;'>Outdoor Services:</span> ".str_replace('<p>','',$outdoor)."</div>";
                  } 

                  if($dining!=''){
                   $content.="<div style='margin-bottom:-40px !important;'><span style='margin-bottom:-50px !important;color:green;font-weight:bold;'>Dining Services:</span> ".str_replace('<p>','',$dining)."</div>";
                  }  
                  if($conference!=''){
                   $content.="<div style='margin-bottom:-40px !important;'><span style='margin-bottom:-50px !important;color:green;font-weight:bold;'>Conference Services:</span> ".str_replace('<p>','',$conference)."</div>";
                  }

                  if($kids_related!=''){
                   $content.="<div style='margin-bottom:-40px !important;'><span style='margin-bottom:-50px !important;color:green;font-weight:bold;'>Kids Services:</span> ".str_replace('<p>','',$kids_related)."</div>";
                  }  

                  if($excursions!=''){
                   $content.="<div style='margin-bottom:-40px !important;'><span style='color:green;font-weight:bold;'>Excursions:</span> ".str_replace('<p>','',$excursions)."</div>";
                  }  
                
                $content.=" </div>
                <br>
                <div style='text-align:left;'".$alignLeft.">
                Hope the above meets with your requirements. In case of any further assistance please feel free to get in touch with the undersigned.<br><br>
                We look forward to welcoming you at <b>".$hotelName."</b>.
                  <br><br>
                  With Kind Regards,<br><br> 
                  <b>".$quotedBy."</b><br>
                  <i>".$quotedByDesignation."</i><br><br>
                  
                      <b>".$companyNameUser."</b><br>
                          ".($address1!=''?$address1.', ':'').($address2!=''?$address1.', ':'').$city.'-'.$zip."<br>
                          M: ".$mobile." | Email : ".$handeledbyEmail."               
                    <span style='font-family:Georgia !important;font-size:10pt !important;'>".strip_tags($footer,'<span>,<p>,<b>')."</span>
                  
                  <br>
                </div>
           </div>";





      





/*$signature = $SITE_URL.'/uploaded_files/shop/'.selectColumn(TBL_SHOP,'mail_signature','WHERE id="'.$_SESSION['shop'].'" ');
$content .= "<table style='width : 100%;'>
        </tr>
            <td>
                <img src='".$signature."' />
            </td>
        </tr>
      </table>"; */
   
   

//---------------------------------------------------------------------------------------------------------.
$file_name = $company.'-'.$season.'.pdf';

?>



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

//$file_name = $company.'-'.$season.'.pdf';
$sent =  $sendMail->sendMail('support@roomstatushub.com', $_POST['mailId'], $_POST['subject'],$_POST['sendcontent'],$_POST['ccId']);



//echo "<script type='text/javascript'>alert('$message');window.location.href='../manageRateLetters.php';</script>";

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




	$message = "Mail Sent Successfully";

	echo "<script type='text/javascript'>alert('$message');window.location.href='../manageQuote.php';</script>";





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
            <!--<img id="loadAni" src="loadingAni.gif">-->
			<form action="" method="post" enctype="multipart/form-data" autocomplete="off" data-parsley-validate>

            <div class="box-body">

              <div class="form-group">
                   <lable>To</lable>             
                <input class="form-control" placeholder="To:" name="mailId" value="<?php echo $custEmail; ?>" data-parsley-required data-parsley-type="email">

                <input type="hidden" id="rate_id_mail" name="rate_id_mail" value="<?=$_REQUEST['id'];?>"/>

              </div>

			  <div class="form-group">
                <lable>CC</lable>
                <input class="form-control" placeholder="CC:" name="ccId" value="<?php echo $quotedByEmail;?>" data-parsley-required data-parsley-type="cc email">

              </div>

              <div class="form-group">
            <lable>Subject</lable>
                <?php $sub= $hotelName.' from '.date('d',strtotime($dateChecking)).' to '.date('d M,Y',strtotime($dateCheckout)); ?>
                <input class="form-control" placeholder="Subject:" name="subject" value="<?php echo $sub;?>" data-parsley-required >

              </div>
              <!--<div class="form-group">
                <lable>Attachment : &nbsp</lable><span style="color: green;font-weight: bold;"><?php echo $file_name; ?></span>
              </div>-->

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
  /*$(document).ajaxStart(
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
     url         : '../pdf-template/generateRatePdf.php', 
     data        : 'id='+rate_id+'&location=set',
     success     : function(data){
     } ,
     complete : function(){
        
     }
    })
  });*/
</script>

