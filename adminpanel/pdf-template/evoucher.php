<?php include_once("../../phplib/dompdf/dompdf_config.inc.php"); 
$dompdf = new DOMPDF();

	$title = base64_decode($_REQUEST['title']);
	$name = base64_decode($_REQUEST['employee']);
	$serial = base64_decode($_REQUEST['serial_no']);
	$promocode = base64_decode($_REQUEST['promocode']);
	$date_from = base64_decode($_REQUEST['date_from']);
	$date_to = base64_decode($_REQUEST['date_to']);
	$value = base64_decode($_REQUEST['value']);
	$issue = date("d-M-Y");
?>



<?php 
$content = '<style>
#wrapper{
	height : 100%;
	width  : 100%;
	page-break-after: always;
}

#heading_txt{
	width:100%;
	text-align:center;

}
#header_img{
	width:100%;
	height:250px;
	
}
img{
	width:100%;
	height:700px;
	position : absolute;
	z-index : 0;
}
table{
	width : 100%;
	height :100%;
}
p{
	z-index:1;
	margin-top : 250px;
	padding : 20px 50px 20px 50px; 
	position : fixed;
}
li{
	margin-top : 20px;
}
</style>';
?>


<?php
$content .=

'<div id="wrapper">
	<img  src="../images/evoucher.jpg">
	<p>Serial No.   .....<b>'.$serial.'</b>..... <span style="margin-left:550px;">Date of Issue : '.$issue.'</span><br><br>
	Voucher Code :  <b>'.$promocode.'</b><br><br>
	<span style="text-align:center;margin-left:150px;">This Voucher entitles : <b>'.$title." ".$name.'</b>  to One Double Room and taxes for Two nights at</span><br><br><span style="text-align:center;margin-left:150px;">WelcomHeritage ..............................................................................alongwith upto Rs. '.$value.'/-</span> <br><span style="text-align:center;margin-left:200px;">worth of Food & Beverage services and other facilities offered at hotel.<.span><br><br><span style="text-align:center;margin-left:300px;">The same is applicable once during one stay.</span><br><br>
	<span style="text-align:center;margin-left:200px;">The validity of this voucher is from <b>'.date("d-M-Y",strtotime($date_from)).' to '.date("d-M-Y",strtotime($date_to)).'</b></span><br><br><br><br><br>

	<span >
	Sanjeev K Nayar, MIH<br>
	(General Manager)
	</span>	

	<span style="margin-left:430px;">
	(please refer terms & conditions on reverse)
	</span>	
	
	</p>


</div>
<br>
	<br>
	<b>Terms & Conditions</b>
	<ul style="text-align:justify;">
	<li>The validity of the voucher is from <b>'.date("d-M-Y",strtotime($date_from)).' to '.date("d-M-Y",strtotime($date_to)).'</b>. </li>
	<li>Reservations to be done with us at our Central Reservations Office in New Delhi at
holidays@welcomheritagehotels.in or call 011-46035500, Fax No. 46035528 to ensure proper instructions to
The Hotel. This offer can only be availed after getting booked through Central Reservations Office.</li>
	<li>
	<li>The certicate holder is entitled to 10% discount on Food & Beverages during his/her stay.</li>
	<li>The offer is non-transferable, non-negotiable, non-encashable, non-extendable and is subject to room
availability at the time of booking.</li>
	<li>This voucher will not be applicable during the peak period (i.e. from 15th December, 2018 till 2nd January, 2019
& festive holidays.</li>
	<li>This voucher cannot be reissued, in the event if it is lost. Post expiry the voucher can not be
revalidated under any circumstances.</li>
	<li>If any additional room is required then special package rate of 2 nights on EPAI plan as given to Amdocs for
the offer would be extended but at direct payment and extension of stay, will be subject to availability.</li>
<li>Employees are required to present this original voucher at the time of Check-In.</li></li>
	</ul>
';

?>


<?php 
$dompdf->set_paper(DEFAULT_PDF_PAPER_SIZE, 'landscape');
$dompdf->load_html($content);
$dompdf->render();
$dompdf->stream('abcd.pdf', array("Attachment" => false	));
?>
