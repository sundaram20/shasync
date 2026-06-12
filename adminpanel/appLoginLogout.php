<?php 
include_once("../config/auto_loader.php");
include_once("includes/reportFunctions.php");

if($_SESSION['userLevel']!=1){
$perSql="SELECT * FROM `fs_user_levels` WHERE id=".$_SESSION['userLevel']." AND id_shop=".$_SESSION['shop']." ";
$resPer = mysqli_query($connNew,$perSql);

if($resPer){
    $perData  = mysqli_fetch_object($resPer);
    if($perData->calendar_user_list_approved == 0){
     $UserRestriction =" AND id='".$_SESSION['userId']."'"; 
    }

}
}
if($_SESSION['teamMembers'] !=""){
  $teamMembers = "AND id IN (".$_SESSION['teamMembers'].")";
}
else{
  $teamMembers ="";
}

if($_REQUEST['Search']=='Download'){
 
  $date = @explode(' to ',$_REQUEST['reservation_date']);

  loginLogoutReport($_SESSION['shop'], date('Y-m-d',strtotime($date[0])), date('Y-m-d',strtotime($date[1])), $_REQUEST['usernameid']);

}  

?>
<?php include_once("includes/header.php")?>
 
<?php include_once("includes/left.php")?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        App Tracking
        <small>Geo Location Tracking</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Geo Location Tracking</li>
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
        
        <!-- /.box-header -->
    <form name="geoForm" id="geoForm" action="" method="get">
            <input type="hidden" value="1" name="searchFormSubmit" />
        <div class="box-body">
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Executive</label>        
                  <?php $categoryDropDown = '<select class="form-control select2" name="usernameid" id="usernameid">
                          ';

                  $resUserLevel = selectSql(TBL_USERS," WHERE `status` = '1' ".$teamMembers." ".$UserRestriction." AND `id_shop` = '".addslashes($_SESSION['shop'])."' AND  user_type!=2 ",' ORDER BY `name`');

                  if($db->num_rows2($resUserLevel)){
                              while($resultUserLevel = $db->fetch_object2($resUserLevel)){

                                          if($_REQUEST['usernameid'] == $resultUserLevel->id){

                                            $selected = 'selected="selected"';

                                          }else{

                                            $selected = '';

                                          }

                                          $categoryDropDown .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'</option>';

                                        }

                                        }

                                        echo $categoryDropDown .= '</select>';

                                        ?>

                                                              

                                                              </div>

                              </div>
                  
              
            

            <div class="col-md-3">
              <div class="form-group">
                <label>From - To Date</label>
                <input type="text" class="form-control pull-right dateRangeEdit"  placeholder="Enter Checkin date" name="reservation_date" id="reservation_date" data-parsley-required value="<?php if(isset($_REQUEST['reservation_date'])) echo $_REQUEST['reservation_date'];?>" data-parsley-errors-container="#reservation_dateError"  automcomplete="off">
              </div>
            </div>
      
            
          </div>
          
        </div>
        
        <div class="box-footer">
        <input name="Search" type="submit" class="btn btn-primary" value="Download" />
        </div>
    </form>   
      </div>
      
      <!-- /.row -->
   
     
    </section>
    <!-- /.content -->
  </div>

                         
<?php include_once("includes/footer.php")?> 
