<?php
	session_start();
	session_destroy();
	session_unset();
	error_reporting(E_ALL);
	include_once('../config/data.config.php');

	if(isset($_REQUEST['submit'])){
		
		$connNew=mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, 'inroomhu_app');
		

		$sql = "select app_shops.database from app_shops where shop_code='".trim($_POST['shop_code'])."' ";
		$resDb = mysqli_query($connNew,$sql);
		

		$db=mysqli_fetch_object($resDb)->database;

		$dbConnect = mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $db);
		
		
		$userSql = "SELECT id,email,username FROM fs_users WHERE UPPER(email)='".strtoupper(trim($_POST['email']))."' OR UPPER(username)='".strtoupper(trim($_POST['email']))."' ";
		
		$resUser = mysqli_query($dbConnect,$userSql);
			
			
		$row=mysqli_fetch_object($resUser);
		
		$rowUser = $row->id;
		$rowEmail = $row->email;
		
		$link="https://www.roomstatushub.in/sync/adminpanel/updateForgetPassword.php?token=".base64_encode(base64_encode(base64_encode($rowUser)))."&client_token=".base64_encode($db);

		if($rowUser>0 && $rowEmail!=''){
			$headers=array();

			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-type: text/html; charset=iso-8859-1';

			// Additional headers
			//$headers[] = 'To: '.$to;

			$headers[] = 'From: support@roomstatushub.com ';
			

			
			$sent = mail($rowEmail,'Password Reset Link','Dear User,<br><br>Kindly Use The Following link to reset your password<br><br>Link : '.$link.'<br><br>Regards,<br>RoomStatusHUB', implode("\r\n", $headers));
			if($sent){
				echo "<script>
				alert('mail sent to your registered mail id');
				window.location.href='index.php';
				</script>";
			}
			else{
				echo "<script>
				alert('mail failed ! you have not registered mail id with us');
				window.location.href='index.php';
				</script>";
			}	
		}
		else{
			echo "<script>
				alert('user not found ! ');
				window.location.href='index.php';
			</script>";
		}
	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Forget Password</title>
</head>
<body>
	<div style="width: 100%;background-color: #e3e3e3;height: 150px;">
		<div style="text-align: center;">
			<form action="" method="post" class="text-center">
				<label>Email Or User name</label><br>
				<input name="email" required="required" type="text" placeholder="Enter Email OR User Name"><br>
				<label>Corporate Code</label><br>
				<input required="required" name="shop_code" type="text" placeholder="Enter Corporate Code"><br>
				<input name="submit" type="submit" >
			</form>
		</div>
		
	</div>
</body>
</html>