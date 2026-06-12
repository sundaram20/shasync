<?php
include_once('../config/data.config.php');

if($_REQUEST['submit']=='update'){
	$id_user = base64_decode(base64_decode(base64_decode($_REQUEST['token'])));
	if($id_user!=''  && base64_decode(trim($_REQUEST['client_token']))!='' ){

		$connNew=mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD,base64_decode(trim($_REQUEST['client_token'])));

		$sql="UPDATE fs_users SET password='".base64_encode($_REQUEST['new'])."' WHERE  id=".$id_user." ";

		if(mysqli_query($connNew,$sql)){
			echo "<script>
				alert('Password Updated Successfully ! ');
				window.location.href='index.php';
			</script>";
		}
		else{
			echo "<script>
				alert('Failed to update ! ');
				window.location.href='index.php';
			</script>";
		}
	}
	else{
		header('LOCATION:index.php');
	}

}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Update Forget Password </title>
</head>
<body>
	<div style="width: 100%;background-color: #e3e3e3;height: 150px;">
		<div style="text-align: center;">
			<form action="" method="post" class="text-center">
				<input type="hidden" name="client_token" value="<?php echo $_GET['client_token']?>">
				<input type="hidden" name="token" value="<?php echo $_GET['token']?>">
				<label>New Password</label><br>
				<input name="new" required="required" type="text" placeholder="Enter New Password"><br>
				<input name="submit" value="update" type="submit" >
			</form>
		</div>
		
	</div>
</body>
</html>