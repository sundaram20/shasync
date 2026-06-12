<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');
//---------------------------------------------------------------------------------------------------------
?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Hotel Booking Manager <small>Book Now</small> </h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Book Now</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
  <div class="box box-default">
    <div class="form-group has-error" align="center">
      <?php if($_SESSION['errorMsg']){?>
      <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
      <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
      <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
      <?php unset($_SESSION['successMsg']);}?>
    </div>
    <div class="box-header with-border">
      <h3 class="box-title"> BOOKING INFORMATION </h3>
    </div>
    <!-- /.box-header -->
    <div class="panel-body padding-0">
      <div class="row">
        <div class="col-sm-12">
          <div class="row box-border margin-right-10"> 	
          <form method="post" action="" id="bookNowForm" data-parsley-validate>
            <div class="container">
			  <div class="form-group col-sm-3">
			  <label for="hotelId" >Hotel</label>
                <?php 
									 $categoryDropDown = '<select name="hotelId" id="hotelId" class="form-control select2" onChange="getRoom(this.value,1);" data-parsley-required data-parsley-errors-container="#hotelError">
									 					  <option value="">Select Hotel</option>';
											  $resCat = selectSql(TBL_HOTELS," ",' ORDER BY `name`');
											  if(mysqli_num_rows($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['hotelId'] == $resultCat->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $categoryDropDown .= '</select>';
									
									 ?>
									 <span id="hotelError"></span>
              </div>
			  <div class="form-group col-sm-2">
			   <label for="room_id">Rooms</label>
			   <?php $resRoom = executeSql("SELECT rt.name, ahr.hotel_id, ahr.room_id from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".$_REQUEST['hotelId']."' ");
				//echo $resRoom;
				 ?>
                <select class="form-control select2" name="room_id" id="room_id" data-parsley-required data-parsley-errors-container="#roomError">
				<option value="" selected="">Select Room</option>
				<?php 
					while($rowRoom = mysqli_fetch_object($resRoom)){
												if($_REQUEST['room_id'] == $rowRoom->room_id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}			
						$hotelRoomType .= '<option '.$selected.' value="'.$rowRoom->room_id.'" >'.$rowRoom->name.'</option>';
					}	
				echo $hotelRoomType;
				?>
                </select>
				<span id="roomError"></span>
              </div>
              <div class="form-group col-sm-3"> 
			  <label for="reservation_date">Checkin Date - Checkout Date </label>              
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right " id="reservation_date" name="reservation_date" data-parsley-required value="<?php if($_REQUEST['reservation_date']){echo $_REQUEST['reservation_date'];} ?>" data-parsley-errors-container="#reservation_dateError" >
                </div>
                <!-- /.input group -->
				<span id="reservation_dateError"></span>
              </div>                           
              <div class="form-group col-sm-1">			   
                <button id="booknow" name="booknow" type="submit" class="btn btn-primary" style="margin-top:25px;"> Next <i class="fa fa-angle-double-right"></i> </button>
              </div>
            </div>			
          </form>
        </div>
      </div>
      <section class="content">      
      <!-- /.row -->
      
    </section>
    </div>
  </div>
</div>
<!-- /.row -->
</section>
<!-- /.content -->
</div>
<?php include_once("includes/footer.php")?>
