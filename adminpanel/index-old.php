<?php session_start(); 
unset($_SESSION['database']);
include("../config/data.config.php");
include("$LIB_DIR/functions.library.php");
include("$LIB_DIR/msgs.inc.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");


?>
<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin | Log in</title>
	 <link rel="icon" href="<?php echo $SITE_URL; ?>/adminpanel/images/fevicon.png" type="image/x-icon">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo $SITE_URL; ?>/plugins/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo $SITE_URL; ?>/plugins/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo $SITE_URL; ?>/plugins/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo $SITE_URL; ?>/plugins/dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo $SITE_URL; ?>/plugins/iCheck/square/blue.css">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <script language="javascript" type="text/javascript">
	/* this is just a simple reload; you can safely remove it; remember to remove it from the image too */
	function reloadCaptcha(){
		document.getElementById('captcha').src = document.getElementById('captcha').src+ '?' +new Date();
	}
</script>
</head>
<body class="hold-transition login-page" > 
<div class="login-box">
  <div class="login-logo">
    <a href="index.php"><img style="width: 100%;height:auto;" src="images/room.png">
    </a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to start your session</p>
	<div class="form-group has-error">
	<?php 
			if($_SESSION['errorMsg']){?>
                  <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
                  <?php unset($_SESSION['errorMsg']);
			}elseif($_SESSION['successMsg']){?>
              	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
               <?php unset($_SESSION['successMsg']);
			}?>
	</div>
			
	<form name="form1" action="process.php" method="post">
		<input type="hidden" value="secureLogin" name="process" />
        <input type="hidden" value="submit" name="submit" />
      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Enter Username" name="username" id="username">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Password" name="password" id="password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="text" required="" class="form-control" placeholder="Enter Corporate Code" name="shopCode" id="shopCode">
        <span class="glyphicon glyphicon-home form-control-feedback"></span>
      </div>
	   <div class="row">	   	 
	    	<div class="col-xs-6">          
        		<img src="../captcha/image.php" border="1" alt="Click to reload image" title="Click to reload image" id="captcha" onClick="javascript:reloadCaptcha()" />		
			</div>
			<div class="col-xs-6">
				<div class="form-group has-feedback">
			 		<input name="secure" type="text" class="form-control" id="secure"  placeholder="Enter Captcha"/>
       			 	<span class="glyphicon glyphicon-cog form-control-feedback"></span>
				</div>
			</div>      	
	  </div>
      <div class="row">        
        <!-- /.col -->
        <div class="col-xs-offset-5">
          <button type="submit" class="btn btn-primary  btn-flat">Sign In</button>  &nbsp;&nbsp; <a href="forgetPassword.php">Forgot Password !</a>
        </div>
        
        <div class="timeline-item">
                <span class="time"><br/>

                <?php /*?><h3 class="timeline-header"><a href="#" style="    font-size: 14px;"> For Support</a></h3><?php */?>

                 <div class="timeline-body">
                  <img src="icon/email.png" border="1"   style="height: 21px;
    width: 28px;" /><a class="btn btn-primary btn-xs" style="font-size: 11px;">support@roomstatushub.com</a>&nbsp;
                    <img src="icon/phone.png" border="1" style="height: 30px;"  /><a class="btn btn-primary btn-xs">8929432759,8929432758</a>
                </div>
                
              </div>
        <!-- /.col -->
      </div>
    </form>
		
    <!-- /.social-auth-links -->

  <?php /*?>  <a href="#">I forgot my password</a><br>
    <a href="register.html" class="text-center">Register a new membership</a><?php */?>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="<?php echo $SITE_URL; ?>/plugins/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo $SITE_URL; ?>/plugins/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="<?php echo $SITE_URL; ?>/plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>