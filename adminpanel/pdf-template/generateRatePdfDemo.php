<?php 

include_once("../../config/auto_loader.php"); 
if($_SESSION['userLevel'] !=1){
	restrictRateForZone($connNew,addslashes(encryptor('decrypt',$_REQUEST['id'])));
}
$resShop  =  executeSQl("SELECT * FROM `".TBL_SHOP."` WHERE id= '".addslashes($_SESSION['shop'])."'");
$rowShop = $db->fetch_object2($resShop);
$logo	=	$rowShop->image;
$Newrate_id	= addslashes(encryptor('decrypt',$_REQUEST['id']));



$content = '<style>
body { 
	margin:0px; 
	padding:0px;
	font-size:13px !important;
 
 }
.table-bordered {
    	 border: 1px solid #000;
	 border-collapse: collapse;
}
.table {
	font-size:11px !important; 
    margin-bottom: 20px;	   
    width:100%;
} 
table {
	font-size:11px !important; 
    background-color: transparent;
    border-collapse: collapse;
    border-spacing: 0;
	}
.table-bordered > tbody > tr > td, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > td,  .table-bordered > thead > tr > td, .table-bordered > thead > tr > th {	
    border-collapse: collapse; border: 1px solid #000;
}
.table td, .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
    color: #000; border-collapse: collapse; border: 1px solid #000;
    
    
}
.fitwidth{
	
	}
.page_break { page-break-before: always;float:left;
 }
 
 .page_autobreak{ page-break-before: always;
 }
 .generalTermClass table{
 	width:100% !important;
 }
</style>';

$content .= '<table class="table" style=" margin-bottom: 0px;border: 0px;  ">
						<tr>					
						  <td>
						  <img src="../../uploaded_files/shop/'.$logo.'" class="img-responsive" alt="logo" title="logo"   />&nbsp;</td>
		  <td><img src="../../uploaded_files/shop/'.$rowShop->image_logo2.'" class="img-responsive" alt="logo" title="logo" /> &nbsp;</td>
 
	  
<td><img src="../../uploaded_files/shop/'.$rowShop->image_logo2.'" class="img-responsive" alt="logo" title="logo" /> &nbsp;</td>';

						  
$content .= '			 
				</tr>
			</table>
	    ';
		
 
//echo $content;die;
						
		   


 

$dompdf = new DOMPDF();


//$dompdf->set_option("isPhpEnabled", true);
$dompdf->set_paper('landscape', 'landscape');


$dompdf->load_html($content);
//debugData($dompdf);

$dompdf->render();


//debugData($dompdf);

$font = Font_Metrics::get_font("helvetica", "bold");
$dompdf->get_canvas()->page_text(720, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));



//echo $Filename;die;
if($_REQUEST['location']=='set'){
	$gen = $dompdf->output();
	$dompdf->stream($Filename.'.pdf', array("Attachment" => true));
	file_put_contents('../mailattach/'.$Filename.'.pdf', $gen);
	echo "ok";
}
else{
	
	$dompdf->output();
	$dompdf->stream($Filename.'.pdf', array("Attachment" => true));
}
//file_put_contents('../mailattach/'.$Filename.'.pdf', array("Attachment" => true	));
//$dompdf->stream();



/*$dompdf->load_html($availableData);
//$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

$dompdf->output();
$dompdf->stream();
  
//$dompdf->stream('test.pdf', array("Attachment" => false	));*/

 
?>

