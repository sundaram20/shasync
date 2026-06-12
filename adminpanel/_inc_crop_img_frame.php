<?

include_once("../config/auto_loader.php");

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<script src="<?php echo $SITE_URL; ?>/plugins/jQuery/jQuery-2.1.3.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $SITE_URL; ?>/plugins/cropimage/imgareaselect-default.css" />
<script type="text/javascript" src="<?php echo $SITE_URL; ?>/plugins/cropimage/jquery.imgareaselect.js"></script>
</head>
<body>
<?


$ratio = explode('x', $_GET['s']);


if ($_POST['corpCords'] != '')

{

	$targetImg = $_GET['t'];

	@unlink($targetImg);


	corpImageByCords($_GET['o'],$targetImg, $_POST['corpCords'], $_POST['display_width']);


	if ($ratio[1] > 0 and $ratio[0] > 0)


	resizeToSpecificSize($targetImg, $ratio[0],$ratio[1]);	


	//@mysqli_query($connNew,"UPDATE witn_photos SET lastCropCords ='".$_POST['corpCords']."' WHERE orginalurl='".str_replace('../', '', $_GET['o'])."'");

	$_SESSION['successMsg'] = 'Gallery details has been updated sucessfully.';
	echo '<script>parent.window.location.href=parent.window.location.href</script>';

}


?>
<form method="post">
  <input name="corpCords" id="corpCords" type="hidden" value="">
  <input name="display_width" id="display_width" type="hidden" value="">
</form>
<img id="ximageToBeCorped" src="<?=$_GET['o']?>" border="0" style="max-width:550px;max-height:300px;">
<script>


$(window).load(function () {


<?


if ($_GET['c'] == '') $_GET['c'] = '0|0|219|85';


$cords = explode('|', $_GET['c']);





if ($ratio[1] > 0 and $ratio[0] > 0)


$jsRatio = "aspectRatio : '".$ratio[0].":".$ratio[1]."',";


?>








		


		$('#ximageToBeCorped').imgAreaSelect({  x1: <?=$cords[0]?>, y1: <?=$cords[1]?>, x2: <?=$cords[2]?>, y2:<?=$cords[3]?>,  


			handles: true, 


			persistent:true, 


			<?=$jsRatio?> 


		 	onSelectEnd: function (img, selection) 


		  	{


		    	$('#corpCords').val(selection.x1+'|'+selection.y1+'|'+selection.x2+'|'+selection.y2);


		    	$('#display_width').val($('#ximageToBeCorped').width());


		 	}


		});	


});	





</script>
</body>
</html>
