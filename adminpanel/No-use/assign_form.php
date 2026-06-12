<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<style>
/*
    Colorbox Core Style:
    The following CSS is consistent between example themes and should not be altered.
*/
#colorbox, #cboxOverlay, #cboxWrapper{position:absolute; top:0; left:0; z-index:9999; overflow:hidden;}
#cboxWrapper {max-width:none;}
#cboxOverlay{position:fixed; width:100%; height:100%;}
#cboxMiddleLeft, #cboxBottomLeft{clear:left;}
#cboxContent{position:relative;}
#cboxLoadedContent{overflow:auto; -webkit-overflow-scrolling: touch;}
#cboxTitle{margin:0;}
#cboxLoadingOverlay, #cboxLoadingGraphic{position:absolute; top:0; left:0; width:100%; height:100%;}
#cboxPrevious, #cboxNext, #cboxClose, #cboxSlideshow{cursor:pointer;}
.cboxPhoto{float:left; margin:auto; border:0; display:block; max-width:none; -ms-interpolation-mode:bicubic;}
.cboxIframe{width:100%; height:100%; display:block; border:0; padding:0; margin:0;}
#colorbox, #cboxContent, #cboxLoadedContent{box-sizing:content-box; -moz-box-sizing:content-box; -webkit-box-sizing:content-box;}

/* 
    User Style:
    Change the following styles to modify the appearance of Colorbox.  They are
    ordered & tabbed in a way that represents the nesting of the generated HTML.
*/
#cboxOverlay{background:#000; opacity: 0.9; filter: alpha(opacity = 90);}
#colorbox{outline:0;}
    #cboxTopLeft{width:14px; height:14px; background:url(images/controls.png) no-repeat 0 0;}
    #cboxTopCenter{height:14px; background:url(images/border.png) repeat-x top left;}
    #cboxTopRight{width:14px; height:14px; background:url(images/controls.png) no-repeat -36px 0;}
    #cboxBottomLeft{width:14px; height:43px; background:url(images/controls.png) no-repeat 0 -32px;}
    #cboxBottomCenter{height:43px; background:url(images/border.png) repeat-x bottom left;}
    #cboxBottomRight{width:14px; height:43px; background:url(images/controls.png) no-repeat -36px -32px;}
    #cboxMiddleLeft{width:14px; background:url(images/controls.png) repeat-y -175px 0;}
    #cboxMiddleRight{width:14px; background:url(images/controls.png) repeat-y -211px 0;}
    #cboxContent{background:#fff; overflow:visible;}
        .cboxIframe{background:#fff;}
        #cboxError{padding:50px; border:1px solid #ccc;}
        #cboxLoadedContent{margin-bottom:5px;}
        #cboxLoadingOverlay{background:url(images/loading_background.png) no-repeat center center;}
        #cboxLoadingGraphic{background:url(images/loading.gif) no-repeat center center;}
        #cboxTitle{position:absolute; bottom:-25px; left:0; text-align:center; width:100%; font-weight:bold; color:#7C7C7C;}
        #cboxCurrent{position:absolute; bottom:-25px; left:58px; font-weight:bold; color:#7C7C7C;}

        /* these elements are buttons, and may need to have additional styles reset to avoid unwanted base styles */
        #cboxPrevious, #cboxNext, #cboxSlideshow, #cboxClose {border:0; padding:0; margin:0; overflow:visible;  position:absolute; bottom:-29px; background:url(images/controls.png) no-repeat 0px 0px; width:23px; height:23px; text-indent:-9999px;}
        
        /* avoid outlines on :active (mouseclick), but preserve outlines on :focus (tabbed navigating) */
        #cboxPrevious:active, #cboxNext:active, #cboxSlideshow:active, #cboxClose:active {outline:0;}
		#cboxPrevious{left:0px; background-position: -51px -25px;}
        #cboxPrevious:hover{background-position:-51px 0px;}
        #cboxNext{left:27px; background-position:-75px -25px;}
        #cboxNext:hover{background-position:-75px 0px;}
        #cboxClose{right:0; background-position:-100px -25px;}
        #cboxClose:hover{background-position:-100px 0px;}

        .cboxSlideshow_on #cboxSlideshow{background-position:-125px 0px; right:27px;}
        .cboxSlideshow_on #cboxSlideshow:hover{background-position:-150px 0px;}
        .cboxSlideshow_off #cboxSlideshow{background-position:-150px -25px; right:27px;}
        .cboxSlideshow_off #cboxSlideshow:hover{background-position:-125px 0px;}
		
		body{font:12px/1.2 Verdana, sans-serif; padding:0 10px;}
			a:link, a:visited{text-decoration:none; color:#416CE5; border-bottom:1px solid #416CE5;}
			h2{font-size:13px; margin:15px 0 0 0;}
</style>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="js/jquery.colorbox.js"></script>
		<script>
			$(document).ready(function(){
				//Examples of how to assign the Colorbox event to elements
				$(".group1").colorbox({rel:'group1'});
				$(".group2").colorbox({rel:'group2', transition:"fade"});
				$(".group3").colorbox({rel:'group3', transition:"none", width:"85%", height:"85%"});
				$(".group4").colorbox({rel:'group4', slideshow:true});
				$(".ajax").colorbox();
				$(".youtube").colorbox({iframe:true, innerWidth:640, innerHeight:390});
				$(".vimeo").colorbox({iframe:true, innerWidth:500, innerHeight:409});
				$(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
				$(".inline").colorbox({inline:true, width:"50%"});
				$(".callbacks").colorbox({
					onOpen:function(){ alert('onOpen: colorbox is about to open'); },
					onLoad:function(){ alert('onLoad: colorbox has started to load the targeted content'); },
					onComplete:function(){ alert('onComplete: colorbox has displayed the loaded content'); },
					onCleanup:function(){ alert('onCleanup: colorbox has begun the close process'); },
					onClosed:function(){ alert('onClosed: colorbox has completely closed'); }
				});

				$('.non-retina').colorbox({rel:'group5', transition:'none'})
				$('.retina').colorbox({rel:'group5', transition:'none', retinaImage:true, retinaUrl:true});
				
				//Example of preserving a JavaScript event for inline calls.
				$("#click").click(function(){ 
					$('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("Open this window again and this message will still be here.");
					return false;
				});
			});
		</script></head>

<body>
</body>
</html>


		
		
		<?php session_start();
include("../config/data.config.php");
include("$LIB_DIR/functions.library.php");
include("$LIB_DIR/msgs.inc.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");
include("$LIB_DIR/class.pagingClass.php");
$db = new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
adminLoginCheck();
checkUserLevelPermission($_SESSION['userLevel'],TBL_CREATIVE,'view'); 
$image_path = $UPLOAD_FILES.'/colors/';
$image_display_path = $UPLOAD_FILES_PATH ."/colors/";



	 $query="select * from  ".TBL_CREATIVE." where id='".$_GET['id']."'" ;
	 //  echo"query_booking-$query_booking<br>";
	$res=mysql_query($query);
	$line=mysql_fetch_array($res);
	//echo"<pre>";print_r($obj_booking);echo"</pre>";
	$total= mysql_num_rows($res);
?>
 <?php
 if(!empty($_GET['id'])){
	$sqlUserDetail = "  SELECT * FROM `".TBL_CREATIVE."`
						WHERE `id` = '".$_GET['id']."'";
	$db->query($sqlUserDetail);
	if($db->num_rows() > 0){
		$rowUser = $db->fetch_object();
	}						
}	
?>   

            <?php  
													
													
													if(file_exists($image_path.'small-'.$rowUser->image1)&& $rowUser->image1!=''){
													echo '<br><a class="group3" href="'.$image_display_path.$rowUser->image1.'"> <img class="group3" src="'.$image_display_path.$rowUser->image1.'" height="42"/></a>';
												}
													 elseif($rowUser->image1!=''){
													 echo '<br><a class="group3" href="'.$image_display_path.$rowUser->image1.'"><img class="group3" src="../images/image_not_found_small.png" width="42" height="42" title="Image not found" /></a>';}?>
													&nbsp; 
													
													
													<?php 
													if(file_exists($image_path.'small-'.$rowUser->image2)&& $rowUser->image2!=''){
													echo '<br><a class="group3" href="'.$image_display_path.$rowUser->image2.'"><img class="group1" src="'.$image_display_path.$rowUser->image2.'" height="42"/></a>'; 
													}
													elseif($rowUser->image2!=''){
													echo '<br><a class="group3" href="'.$image_display_path.$rowUser->image2.'">img class="group1" src="../images/image_not_found_small.png" width="42" height="42" title="Image not found" /></a>';}?>
													&nbsp;
													
													
													
													<?php
													 if(file_exists($image_path.'small-'.$rowUser->image3)&& $rowUser->image3!=''){
													echo '<br><a class="group3" href="'.$image_display_path.$rowUser->image3.'"><img class="group1" src="'.$image_display_path.$rowUser->image3.'" height="42"/></a>'; 
												}
													elseif($rowUser->image3!=''){
													echo '<br><a class="group3" href="'.$image_display_path.$rowUser->image3.'"><img class="group1" src="../images/image_not_found_small.png" width="42" height="42" title="Image not found" /></a>';}?>
													&nbsp;

											
          
		
		
		
		
