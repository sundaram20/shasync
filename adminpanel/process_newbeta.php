<?php 
session_start();	
include("../config/data.config.php");
include("$LIB_DIR/functions.library.php");
include("$LIB_DIR/msgs.inc.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");

/* ============================================================
   OTP HELPER FUNCTIONS  (required for every user's login now)
   ============================================================ */

if (!function_exists('sendOtpMail')) {
	/**
	 * Sends the OTP using PHPMailer/Gmail SMTP - same transport your
	 * app already uses successfully for rate-letter / payment-reminder mail.
	 * Returns true/false so the caller can log failures instead of
	 * silently swallowing them.
	 */
	function sendOtpMail($toEmail, $toName, $otp) {

		if (!class_exists('PHPMailer')) {
			@include_once("../config/auto_loader.php");
		}

		if (!class_exists('PHPMailer')) {
			error_log("OTP mail failed: PHPMailer class not found - check the auto_loader include path in sendOtpMail().");
			return false;
		}

		// Same fallback SMTP account used in your existing mail-sending code.
		$SMTPUsername = 'support@roomstatushub.com';
		$SMTPPassword = 'kxfm xrpv znoi xmhx';
		$SMTPHost     = 'smtp.gmail.com';
		$SMTPPort     = 465;

		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->SMTPAuth   = true;
		$mail->SMTPSecure = 'ssl';
		$mail->Host       = $SMTPHost;
		$mail->Port       = $SMTPPort;
		$mail->Username   = $SMTPUsername;
		$mail->Password   = $SMTPPassword;
		$mail->IsHTML(true);
		$mail->setFrom($SMTPUsername, 'RoomStatusHUB');
		$mail->addAddress($toEmail, $toName);

		$mail->Subject = 'Your RoomStatusHUB Login OTP';
		$mail->Body    = "Hello " . htmlspecialchars($toName) . ",<br><br>"
						. "Your One-Time Password (OTP) for login is: <b>" . $otp . "</b><br><br>"
						. "This code is valid for 5 minutes. If you did not request this, please ignore this email or contact support.<br><br>"
						. "Regards,<br>RoomStatusHUB Team";

		$sent = $mail->send();

		if (!$sent) {
			// Not suppressed on purpose - check this log if OTPs still don't arrive.
			error_log("OTP mail failed for {$toEmail}: " . $mail->ErrorInfo);
		}

		return $sent;
	}
}

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

		sendOtpMail($email, $name, $otp);
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
   Existing shop-code resolution (unchanged from live)
   ============================================================ */

if($_REQUEST['process'] !='secureLogout'){
	
	$conn = mysqli_connect($DB_HOST_APP,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
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

		$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
		$db->open() or die($db->error());
		
		$connNew = mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);
	}
	else{
		$_SESSION['errorMsg']=$_POST['shopCode'].' '.' incorrect shop code !';
		mysqli_close($conn);
		header("location:indexbeta.php");
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
			header("location:indexbeta.php");
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
			header("location:indexbeta.php");
			exit;
		}
	break;

	case "logout":
				$_SESSION['userid']="";
				$_SESSION['username']="";
				session_destroy();
				header("location:indexbeta.php");
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
		if($err == 0){$connNew = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
			if(($_POST['process'] == 'secureLogin') && $_POST['submit'] && $_POST['g-recaptcha-response']){
					 $sqlLogin = "SELECT * FROM `".TBL_USERS."` WHERE `username` = '".addslashes($_POST['username'])."' AND `password` = '".base64_encode($_POST['password'])."' AND `status` = '1' AND `sales_status_active` = '1'";
					
				$resLogin = mysqli_query($connNew, $sqlLogin);

				 $numLogin = mysqli_num_rows($resLogin);
				
				if($numLogin > 0){
					$resultLogin = mysqli_fetch_assoc($resLogin);

					/* ---------- Every user now needs an OTP before login completes ---------- */
					generateAndSendOtp($connNew, $resultLogin['id'], $resultLogin['email'], $resultLogin['name']);

					$_SESSION['otpPendingUser'] = $resultLogin;
					$_SESSION['otpShopCode']    = $_POST['shopCode'];
					$_SESSION['otpAttempts']    = 0;
					$_SESSION['successMsg']     = 'An OTP has been sent to your registered email. Please enter it below to continue.';
					header('location:indexbeta.php?otp=1');
					exit;
					/* --------------------------------------------------------------------------- */
				}else{
					$_SESSION['errorMsg'] = 'Invalid login details. Please try again.';
					header("location:indexbeta.php");
					exit;
				}
			}else{
				if(empty($_POST['g-recaptcha-response'])){
							 $_SESSION['errorMsg'] = 'Unable to verify. Please try again.';
								header("location:indexbeta.php");
									
						}else{
							$_SESSION['errorMsg'] = 'Invalid login details. Please try again.';
						header("location:indexbeta.php");
						exit;
						}
			}
		}else{
			
			header("location:indexbeta.php");
			exit;
		}
	break;

	/* ============================================================
	   OTP verification - completes the login started in secureLogin
	   ============================================================ */
	case "verifyOtp":
		if(empty($_SESSION['otpPendingUser'])){
			$_SESSION['errorMsg'] = 'Your OTP session has expired. Please login again.';
			header('location:indexbeta.php');
			exit;
		}

		$resultLogin = $_SESSION['otpPendingUser'];

		if(verifyOtpCode($connNew, $resultLogin['id'], $_POST['otp'])){
			clearOtp($connNew, $resultLogin['id']);
			unset($_SESSION['otpPendingUser']);
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
			//setting session for team members below 
			//refer below functions
			
			$_SESSION['incentive_module_approved'] = selectColumn(TBL_SHOP,'incentive_module_approved'," WHERE `id` = '".addslashes($resultLogin['id_shop'])."'");
			$_SESSION['quotation_module_approved'] = selectColumn(TBL_SHOP,'quotation_module_approved'," WHERE `id` = '".addslashes($resultLogin['id_shop'])."'");
			$_SESSION['apptracking_module_approved'] = selectColumn(TBL_SHOP,'apptracking_module_approved'," WHERE `id` = '".addslashes($resultLogin['id_shop'])."'");
			//echo 'selectColumn';die;
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
			$_SESSION['otpAttempts'] = isset($_SESSION['otpAttempts']) ? $_SESSION['otpAttempts'] + 1 : 1;

			if($_SESSION['otpAttempts'] >= 5){
				unset($_SESSION['otpPendingUser']);
				unset($_SESSION['otpShopCode']);
				unset($_SESSION['otpAttempts']);
				$_SESSION['errorMsg'] = 'Too many incorrect attempts. Please login again.';
				header('location:indexbeta.php');
				exit;
			}

			$_SESSION['errorMsg'] = 'Invalid or expired OTP. Please try again.';
			header('location:indexbeta.php?otp=1');
			exit;
		}
	break;

	case "resendOtp":
		if(empty($_SESSION['otpPendingUser'])){
			header('location:indexbeta.php');
			exit;
		}
		$resultLogin = $_SESSION['otpPendingUser'];
		generateAndSendOtp($connNew, $resultLogin['id'], $resultLogin['email'], $resultLogin['name']);
		$_SESSION['otpAttempts'] = 0;
		$_SESSION['successMsg'] = 'A new OTP has been sent to your email.';
		header('location:indexbeta.php?otp=1');
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
		unset($_SESSION['otpShopCode']);
		unset($_SESSION['otpAttempts']);
		$_SESSION['successMsg'] = 'You have been sucessfully logged out.';
		header("location:indexbeta.php");
		exit;
	break;	
}?>