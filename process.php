<?php session_start();	
include("../config/data.config.php");
include("$LIB_DIR/functions.library.php");
include("$LIB_DIR/msgs.inc.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");

if($_REQUEST['process'] !='secureLogout'){
	/*echo $DB_HOST;
	echo "<br>".$DB_USERNAME;
	echo "<br>".$DB_PASSWORD;
	echo "<br>".$DB_NAME;*/
	
	$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
	$sqlShopCodeChk = "SELECT * FROM app_shops WHERE shop_code= '".$_POST['shopCode']."' ";
	
	$resShopChk = mysqli_query($conn,$sqlShopCodeChk);
	
	if($resShopChk && mysql_num_rows($resShopChk) == 1){
		
		$dataShopChk = mysqli_fetch_object($resShopChk);
		
		echo "<br>".$_SESSION['database']= $DB_NAME	=	$dataShopChk->database;
		echo "<br>".$_SESSION['module_access']	=	$dataShopChk->module_access;
		echo "<br>".$_SESSION['shop_code']	= $dataShopChk->shop_code;
		//exit;
		$process = $_REQUEST['process'];
		mysqli_close($conn);
		
		$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);

		$db->open() or die($db->error());

	}
	else{
		$_SESSION['errorMsg']=$_POST['shopCode'].' '.' incorrect shop code !';
		mysqli_close($conn);
		header("location:index.php");
		exit;
	}
}
else{
	$process = $_REQUEST['process'];
	
	$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);

	$db->open() or die($db->error());
}	

//$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
//$db->open() or die($db->error());


//$process = $_REQUEST['process'];
switch($process){
    case "login": 
   		if($_SESSION['security_number'] != $_POST['secure']){
			 $_SESSION['sessMsg'] = 50;
			 header("location:index.php");
			 exit;
		}else if($_POST['secure'] == ''){
			 $_SESSION['sessMsg'] = 50;
			 header("location:index.php");
			 exit;
		}
       	$query = "	SELECT * FROM ".TBL_USERS." 
					WHERE `email` = '".addslashes($_POST['email'])."' 
					AND `password` = '".encrypt($_POST['password'])."' AND `status` = '1' AND `sales_status_active` = '1'";
		$db->query($query);
		if($db->num_rows()>0){			
			$row = $db->fetch_array(); 
			$_SESSION['userId'] = $row['id'];
			$_SESSION['sessAdminUsername'] = $row['email'];
			$_SESSION['sessAdminType'] = $row['email'];
			$query = "	UPDATE ".TBL_USERS." 
						SET `lastlogin` = '".currenDateTime()."' 
						WHERE `id` = '".$row['id']."'";
			$db->query($query);
			header("location:editDailyReport.php");
			exit;
		}else{
			$_SESSION['sessMsg'] = 50;
			header("location:index.php");
		}
	break;
	case "changePassword":
		$query = "	SELECT * FROM ".TBL_USERS." 
					WHERE  `password` = '".encrypt($_POST['oldPassword'])."' AND `status` = '1' AND `sales_status_active` = '1' ";
		$db->query($query);
		if($db->num_rows()>0){	
			$row=$db->fetch_array();
			$query = "	UPDATE ".TBL_USERS." SET 
						`password` = '".encrypt($_POST['newPassword'])."'  
						WHERE `id` = '".$row['id']."'";
			$db->query($query);
			$_SESSION['sessSucMsg'] = 52;
			header("location:changePassword.php");
			exit;
		}else{
			$_SESSION['sessErrorMsg'] = 53;
			header("location:changePassword.php");
			exit;
		}
	break;
	
	case "changeEmail":
		$query = "SELECT * FROM ".TBL_USERS." WHERE `id` = '".$_SESSION['userId']."' AND `status` = '1' AND `sales_status_active` = '1' "   ;
		$db->query($query);
		if($db->num_rows()>0){	
			$row = $db->fetch_array();
			if($row['email'] == $_POST['oldEmail']){
				$query = "UPDATE ".TBL_USERS." SET `email` = '".$_POST['newEmail']."' WHERE `id` = '".$row['id']."'";
				$db->query($query);
				$_SESSION['sessSucMsg'] = 54;
				header("location:changeEmail.php");
				exit;
			}else{
				$_SESSION['sessErrorMsg'] = 55;
				header("location:changeEmail.php");
				exit;
			}
		}else{
			header("location:index.php");
			exit;
		}
	break;

	case "logout":
				$_SESSION['userid']="";
				$_SESSION['username']="";
				session_destroy();
				header("location:index.php");
	break;
	
	case "secureLogin":
		$err = 0;
		if($_POST['username'] == ''){
			$err++;
			$_SESSION['errorMsg'] .= 'Please enter username.';
		}
		if($_POST['password'] == ''){
			$err++;
			$_SESSION['errorMsg'] .= ' Please enter password.';
		}
		if($_POST['secure'] == ''){
			$err++;
			$_SESSION['errorMsg'] .= ' Please enter security code.';
		}elseif($_SESSION['security_number'] != $_POST['secure']){
			$err++;
			$_SESSION['errorMsg'] .= ' Invalid security code. Please try again.';
		}
		if($err == 0){
			if(($_POST['process'] == 'secureLogin') && $_POST['submit']){
				$sqlLogin = "SELECT * FROM `".TBL_USERS."` WHERE `username` = '".addslashes($_POST['username'])."' AND `password` = '".base64_encode($_POST['password'])."' AND `status` = '1' AND `sales_status_active` = '1'";
				$resLogin = @mysql_query($sqlLogin);
				$numLogin = @mysql_num_rows($resLogin);
				if($numLogin > 0){
					$resultLogin = @mysql_fetch_assoc($resLogin);
					$_SESSION['shop'] = $resultLogin['id_shop'];
					$_SESSION['userName'] = $resultLogin['username'];
					$_SESSION['userId'] = $resultLogin['id'];
					$_SESSION['userEmail'] = $resultLogin['email'];
					$_SESSION['userLevel'] = $resultLogin['user_level'];
					$_SESSION['userLastLogin'] = $resultLogin['last_login'];
					$_SESSION['hotel_access'] = $resultLogin['hotel_access'];
					$_SESSION['unit_user'] = $resultLogin['user_type'];
					$_SESSION['sessionId'] = session_id(); 
					//setting session for team members below 
					//refer below functions
					teamMembers($connNewFun);
					teamMemberAreas($connNewFun);
					whomToShow($connNewFun);
					mysqli_close($connNewFun);
					//end
					@mysql_query("UPDATE `".TBL_USERS."` SET `last_login` = '".currenDateTime()."', `session_id` = '".$_SESSION['sessionId']."', ip_address='".ipCheck()."', browser='".$_SERVER['HTTP_USER_AGENT']."' WHERE `id` = '".$_SESSION['userId']."' AND `username` = '".$_SESSION['userName']."'");
					$_SESSION['successMsg'] = 'You have been sucessfully logged in.';
					header('location:editDailyReport.php');
					exit;
				}else{
					$_SESSION['errorMsg'] = 'Invalid login details. Please try again.';
					header("location:index.php");
					exit;
				}
			}else{
				$_SESSION['errorMsg'] = 'Invalid login details. Please try again.';
				header("location:index.php");
				exit;
			}
		}else{
			header("location:index.php");
			exit;
		}
	break;
	case "secureLogout":
		@mysql_query("UPDATE `".TBL_USERS."` SET `last_logout` = '".currenDateTime()."', `session_id` = '' WHERE `id` = '".$_SESSION['userId']."' AND `username` = '".$_SESSION['userName']."'");
		unset($_SESSION['userName']);
		unset($_SESSION['userId']);
		unset($_SESSION['userEmail']);
		unset($_SESSION['unit_user']);
		unset($_SESSION['userLevel']);
		unset($_SESSION['userLastLogin']);
		unset($_SESSION['sessionId']);
		unset($_SESSION['HotelUserPermission']);
		unset($_SESSION['HotelPerHotel']);
		unset($_SESSION['teamMembers']);
		unset($_SESSION['teamMemberAreas']);
		unset($_SESSION['teamMemberLevel']);
		unset($_SESSION['teamId']);
		unset($_SESSION['ActiveListHotelPerLogin']);
		unset($_SESSION['Ids_user_access_Company']);
		unset($_SESSION['ConveyanceUniqueCodeID']);
		unset($_SESSION['database']);
		unset($_SESSION['shop_code']);
		unset($_SESSION['module_access']);
		unset($_SESSION['security_number']);
		unset($_SESSION['shop']);
		$_SESSION['successMsg'] = 'You have been sucessfully logged out.';
		header("location:index.php");
		exit;
	break;	
}?>