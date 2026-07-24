<?php 
session_start();	
include("../config/data.config.php");
include("$LIB_DIR/functions.library.php");
include("$LIB_DIR/msgs.inc.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");

/* ============================================================
   OTP HELPER FUNCTIONS  (super-admin login, user_level = 1)
   ============================================================ */

if (!function_exists('generateAndSendOtp')) {
	/**
	 * Creates a 6-digit OTP, stores its hash + expiry against the user,
	 * and emails the plain OTP to the user's registered email address.
	 */
	function generateAndSendOtp($connNew, $userId, $email, $name) {
		$otp      = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
		$otpHash  = hash('sha256', $otp);
		$expiry   = date('Y-m-d H:i:s', strtotime('+5 minutes'));

		mysqli_query(
			$connNew,
			"UPDATE `" . TBL_USERS . "` 
			 SET `login_otp` = '" . addslashes($otpHash) . "', `otp_expiry` = '" . $expiry . "' 
			 WHERE `id` = '" . (int) $userId . "'"
		);

		$subject = "Your RoomStatusHUB Admin Login OTP";
		$body    = "Hello " . $name . ",\r\n\r\n"
				 . "Your One-Time Password (OTP) for admin login is: " . $otp . "\r\n\r\n"
				 . "This code is valid for 5 minutes. If you did not request this, please ignore this email or contact support.\r\n\r\n"
				 . "Regards,\r\nRoomStatusHUB Team";
		$headers = "From: no-reply@roomstatushub.com\r\nContent-Type: text/plain; charset=UTF-8";

		// NOTE: PHP's mail() requires a working MTA on the server.
		// If OTP emails don't arrive reliably, swap this out for
		// PHPMailer / an SMTP-based sender - happy to wire that up.
		@mail($email, $subject, $body, $headers);
	}
}

if (!function_exists('verifyOtpCode')) {
	function verifyOtpCode($connNew, $userId, $enteredOtp) {
		$enteredHash = hash('sha256', trim((string) $enteredOtp));

		$res = mysqli_query(
			$connNew,
			"SELECT `login_otp`, `otp_expiry` FROM `" . TBL_USERS . "` WHERE `id` = '" . (int) $userId . "'"
		);

		if (!$res || mysqli_num_rows($res) == 0) {
			return false;
		}

		$row = mysqli_fetch_assoc($res);

		if (empty($row['login_otp']) || empty($row['otp_expiry'])) {
			return false;
		}
		if (strtotime($row['otp_expiry']) < time()) {
			return false; // expired
		}

		return hash_equals($row['login_otp'], $enteredHash);
	}
}

if (!function_exists('clearOtp')) {
	function clearOtp($connNew, $userId) {
		mysqli_query(
			$connNew,
			"UPDATE `" . TBL_USERS . "` SET `login_otp` = '', `otp_expiry` = NULL WHERE `id` = '" . (int) $userId . "'"
		);
	}
}

/* ============================================================
   Existing shop-code resolution (unchanged)
   ============================================================ */

if($_REQUEST['process'] !='secureLogout'){
	
	$conn = mysqli_connect($DB_HOST_APP, $DB_USERNAME, $DB_PASSWORD, $DB_NAME,$DB_PORT);


	 $sqlShopCodeChk = "SELECT * FROM app_shops WHERE shop_code= '".$_POST['shopCode']."' ";
	 
	
	$resShopChk = mysqli_query($conn,$sqlShopCodeChk);
	
	if($resShopChk && mysqli_num_rows($resShopChk) == 1){
		
		$dataShopChk = mysqli_fetch_object($resShopChk);
		$_SESSION['host_name'] = $DB_HOST	=	$dataShopChk->host_name;
		$_SESSION['database'] = $DB_NAME	=	$dataShopChk->database;
		$_SESSION['dbuser_name'] = $DB_USERNAME	=	$dataShopChk->user_name;
		$_SESSION['password'] = $DB_PASSWORD	=	$dataShopChk->password;
		$_SESSION['module_access']	=	$dataShopChk->module_access;
		$_SESSION['shop_code']	= $dataShopChk->shop_code;
		
		$process = $_REQUEST['process'];
		mysqli_close($conn);

		$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME ,$DB_PORT,$DB_REPORT_ERROR, $DB_PERSISTENT_CONN);

		$db->open() or die($db->error());

		$connNew=mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME,$DB_PORT);

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
	$connNew=mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);

	$db->open() or die($db->error());
}	

switch($process){
    case "login": 
       	$query = "	SELECT * FROM ".TBL_USERS." 
					WHERE `email` = '".addslashes($_POST['email'])."' 
					AND `password` = '".encrypt($_POST['password'])."'";
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
					WHERE  `password` = '".encrypt($_POST['oldPassword'])."'";
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
		$query = "SELECT * FROM ".TBL_USERS." WHERE `id` = '".$_SESSION['userId']."'"   ;
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
		if($err == 0){
			$connNew = mysqli_connect($DB_HOST_APP, $DB_USERNAME, $DB_PASSWORD, $DB_NAME,$DB_PORT);

			if(($_POST['process'] == 'secureLogin') && $_POST['submit'] ){
					 $sqlLogin = "SELECT * FROM `".TBL_USERS."` WHERE `username` = '".addslashes($_POST['username'])."' AND `password` = '".base64_encode($_POST['password'])."' AND `status` = '1' AND `sales_status_active` = '1'";
					
				$resLogin = mysqli_query($connNew, $sqlLogin);

				 $numLogin = mysqli_num_rows($resLogin);
				
				if($numLogin > 0){
					$resultLogin = mysqli_fetch_assoc($resLogin);

					/* ---------- Super-admin: require OTP before finishing login ---------- */
					if($resultLogin['user_level'] == 1){
						generateAndSendOtp($connNew, $resultLogin['id'], $resultLogin['email'], $resultLogin['name']);
						mysqli_close($connNew);

						$_SESSION['otpPendingUser']  = $resultLogin;
						$_SESSION['otpPendingStage'] = 'password';
						$_SESSION['otpShopCode']     = $_POST['shopCode'];
						$_SESSION['otpAttempts']     = 0;
						$_SESSION['successMsg']      = 'An OTP has been sent to your registered email. Please enter it below to continue.';
						header('location:index.php?tab=otp');
						exit;
					}
					/* ----------------------------------------------------------------------- */

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
					
					$_SESSION['incentive_module_approved'] = selectColumn(TBL_SHOP,'incentive_module_approved'," WHERE `id` = '".addslashes($resultLogin['id_shop'])."'");
					$_SESSION['quotation_module_approved'] = selectColumn(TBL_SHOP,'quotation_module_approved'," WHERE `id` = '".addslashes($resultLogin['id_shop'])."'");
					$_SESSION['apptracking_module_approved'] = selectColumn(TBL_SHOP,'apptracking_module_approved'," WHERE `id` = '".addslashes($resultLogin['id_shop'])."'");
					teamMembers($connNew);
					teamMemberAreas($connNew);
					whomToShow($connNew);
					mysqli_close($connNew);
					//end
					@mysqli_query($connNew,"UPDATE `".TBL_USERS."` SET `last_login` = '".currenDateTime()."', `session_id` = '".$_SESSION['sessionId']."', ip_address='".ipCheck()."', browser='".$_SERVER['HTTP_USER_AGENT']."' WHERE `id` = '".$_SESSION['userId']."' AND `username` = '".$_SESSION['userName']."'");
					
					$connNew = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
	
						 
						 $query_history= "INSERT INTO `mst_login_history` ( `id_shop`, `id_user`, `login_date`, `ip_address`, `browser`, `is_session`, `date_created`) VALUES ( '".$_SESSION['shop']."', '".$_SESSION['userId']."', '".date('Y-m-d')."', '".ipCheck()."', '".$_SERVER['HTTP_USER_AGENT']."' , '".$_SESSION['sessionId']."', '".currenDateTime()."')";
			mysqli_query($connNew,$query_history);
			
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

	/* ============================================================
	   Admin-only OTP login: username -> OTP -> login (no password)
	   ============================================================ */
	case "sendAdminOtp":
		if($_POST['username'] == ''){
			$_SESSION['errorMsg'] = 'Please enter username.';
			header("location:index.php?tab=admin");
			exit;
		}

		$connNew = mysqli_connect($DB_HOST_APP, $DB_USERNAME, $DB_PASSWORD, $DB_NAME,$DB_PORT);

		$sqlAdmin = "SELECT * FROM `".TBL_USERS."` WHERE `username` = '".addslashes($_POST['username'])."' AND `status` = '1' AND `sales_status_active` = '1' AND `user_level` = '1'";
		$resAdmin = mysqli_query($connNew, $sqlAdmin);

		if($resAdmin && mysqli_num_rows($resAdmin) == 1){
			$adminUser = mysqli_fetch_assoc($resAdmin);

			generateAndSendOtp($connNew, $adminUser['id'], $adminUser['email'], $adminUser['name']);
			mysqli_close($connNew);

			$_SESSION['otpPendingUser']  = $adminUser;
			$_SESSION['otpPendingStage'] = 'adminTab';
			$_SESSION['otpShopCode']     = $_POST['shopCode'];
			$_SESSION['otpAttempts']     = 0;
			$_SESSION['successMsg']      = 'An OTP has been sent to your registered email.';
			header('location:index.php?tab=otp');
			exit;
		}else{
			mysqli_close($connNew);
			$_SESSION['errorMsg'] = 'No super-admin account found for this username.';
			header('location:index.php?tab=admin');
			exit;
		}
	break;

	/* ============================================================
	   Shared OTP verification for both entry paths above
	   ============================================================ */
	case "verifyOtp":
		if(empty($_SESSION['otpPendingUser'])){
			$_SESSION['errorMsg'] = 'Your OTP session has expired. Please login again.';
			header('location:index.php');
			exit;
		}

		$resultLogin = $_SESSION['otpPendingUser'];
		$connNew = mysqli_connect($DB_HOST_APP, $DB_USERNAME, $DB_PASSWORD, $DB_NAME,$DB_PORT);

		if(verifyOtpCode($connNew, $resultLogin['id'], $_POST['otp'])){
			clearOtp($connNew, $resultLogin['id']);
			unset($_SESSION['otpPendingUser']);
			unset($_SESSION['otpPendingStage']);
			unset($_SESSION['otpShopCode']);
			unset($_SESSION['otpAttempts']);

			$_SESSION['shop'] = $resultLogin['id_shop'];
			$_SESSION['userName'] = $resultLogin['username'];
			$_SESSION['userId'] = $resultLogin['id'];
			$_SESSION['userEmail'] = $resultLogin['email'];
			$_SESSION['userLevel'] = $resultLogin['user_level'];
			$_SESSION['userLastLogin'] = $resultLogin['last_login'];
			$_SESSION['hotel_access'] = $resultLogin['hotel_access'];
			$_SESSION['unit_user'] = $resultLogin['user_type'];
			$_SESSION['sessionId'] = session_id();

			$_SESSION['incentive_module_approved'] = selectColumn(TBL_SHOP,'incentive_module_approved'," WHERE `id` = '".addslashes($resultLogin['id_shop'])."'");
			$_SESSION['quotation_module_approved'] = selectColumn(TBL_SHOP,'quotation_module_approved'," WHERE `id` = '".addslashes($resultLogin['id_shop'])."'");
			$_SESSION['apptracking_module_approved'] = selectColumn(TBL_SHOP,'apptracking_module_approved'," WHERE `id` = '".addslashes($resultLogin['id_shop'])."'");

			teamMembers($connNew);
			teamMemberAreas($connNew);
			whomToShow($connNew);

			@mysqli_query($connNew,"UPDATE `".TBL_USERS."` SET `last_login` = '".currenDateTime()."', `session_id` = '".$_SESSION['sessionId']."', ip_address='".ipCheck()."', browser='".$_SERVER['HTTP_USER_AGENT']."' WHERE `id` = '".$_SESSION['userId']."' AND `username` = '".$_SESSION['userName']."'");
			mysqli_close($connNew);

			$connNew = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
			$query_history= "INSERT INTO `mst_login_history` ( `id_shop`, `id_user`, `login_date`, `ip_address`, `browser`, `is_session`, `date_created`) VALUES ( '".$_SESSION['shop']."', '".$_SESSION['userId']."', '".date('Y-m-d')."', '".ipCheck()."', '".$_SERVER['HTTP_USER_AGENT']."' , '".$_SESSION['sessionId']."', '".currenDateTime()."')";
			mysqli_query($connNew,$query_history);

			$_SESSION['successMsg'] = 'You have been sucessfully logged in.';
			header('location:editDailyReport.php');
			exit;
		}else{
			mysqli_close($connNew);
			$_SESSION['otpAttempts'] = isset($_SESSION['otpAttempts']) ? $_SESSION['otpAttempts'] + 1 : 1;

			if($_SESSION['otpAttempts'] >= 5){
				unset($_SESSION['otpPendingUser']);
				unset($_SESSION['otpPendingStage']);
				unset($_SESSION['otpShopCode']);
				unset($_SESSION['otpAttempts']);
				$_SESSION['errorMsg'] = 'Too many incorrect attempts. Please login again.';
				header('location:index.php');
				exit;
			}

			$_SESSION['errorMsg'] = 'Invalid or expired OTP. Please try again.';
			header('location:index.php?tab=otp');
			exit;
		}
	break;

	case "resendOtp":
		if(empty($_SESSION['otpPendingUser'])){
			header('location:index.php');
			exit;
		}
		$resultLogin = $_SESSION['otpPendingUser'];
		$connNew = mysqli_connect($DB_HOST_APP, $DB_USERNAME, $DB_PASSWORD, $DB_NAME,$DB_PORT);
		generateAndSendOtp($connNew, $resultLogin['id'], $resultLogin['email'], $resultLogin['name']);
		mysqli_close($connNew);
		$_SESSION['otpAttempts'] = 0;
		$_SESSION['successMsg'] = 'A new OTP has been sent to your email.';
		header('location:index.php?tab=otp');
		exit;
	break;

	case "secureLogout":
	
		@mysqli_query($connNew, "UPDATE `".TBL_USERS."` SET `last_logout` = '".currenDateTime()."', `session_id` = '' WHERE `id` = '".$_SESSION['userId']."' AND `username` = '".$_SESSION['userName']."'");
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
		unset($_SESSION['host_name']);
		unset($_SESSION['shop_code']);
		unset($_SESSION['module_access']);
		unset($_SESSION['security_number']);
		unset($_SESSION['shop']);
		unset($_SESSION['otpPendingUser']);
		unset($_SESSION['otpPendingStage']);
		unset($_SESSION['otpShopCode']);
		unset($_SESSION['otpAttempts']);
		$_SESSION['successMsg'] = 'You have been sucessfully logged out.';
		header("location:index.php");
		exit;
	break;	
}?>