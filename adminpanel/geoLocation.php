<?php include_once("../config/auto_loader.php");

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

                          $resUserLevel = selectSql(TBL_USERS," WHERE `status` = '1' ".$teamMembers." ".$UserRestriction." AND `id_shop` = '".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');

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
                  <label for="start_date">Date</label>
                    <input type="text" class="form-control pickerdate" placeholder="Enter start date" id="report_date" name="report_date" value="<?php echo date('d-m-Y');?>"  data-parsley-required>
                  </div>
            </div>
			
            
          </div>
          
        </div>
        
        <div class="box-footer">
        <input name="Search" type="submit" class="btn btn-primary" value="Search" />
        </div>
		</form>		
      </div>
      
      <!-- /.row -->
      
      <div class="box" id="mapBox">
        <div  style="height: 500px;border:2px solid #252525;" class="map" id="map">
          <h2 class="text-center">Search For The Current Location.</h2>
        </div> 
      </div> 

     
    </section>
    <!-- /.content -->
  </div>

                         
<?php include_once("includes/footer.php")?> 



<script type="text/javascript">
   
    $("#geoForm").submit(function (e){
        e.preventDefault();
        map.remove();
        $("#mapBox").html('<div  style="height: 500px;border:2px solid #252525;" class="map" id="map"></div> ');
        $.ajax({
                'url':'ajax/getGeoLocations.ajax.php',
                'dataType':'JSON',
                'Type':'GET',
                'data':$(this).serialize(),
                success: (data) => {
                  var exeIcon = L.icon({
                            iconUrl: 'images/executive.gif',
                            iconSize: [80, 50]
                      });

                  console.log(data);

                  var map = L.map('map').setView([data[0].latitude,data[0].longitude], 13);
                  mapLink = '<a href="http://openstreetmap.org">OpenStreetMap</a>';
                          L.tileLayer(
                              'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                              attribution: '&copy; ' + mapLink + ' Contributors',
                              maxZoom: 18,
                              }).addTo(map);
                  var j=data.length;

                      for (var i = 0; i < data.length; i++) { 
                        var text='Location No :'+j;
                        
                        if(i==0){ //alert(i+'000');
                         var circleStart = L.circle([data[i].latitude, data[i].longitude], {
                              color: 'red',
                              fillColor: '#f03',
                              fillOpacity: 0.5,
                              radius: 400
                          }).addTo(map);

                          marker = new L.marker([data[i].latitude, data[i].longitude],{icon : exeIcon})
                          .bindPopup('<b>'+text+'</b><br><b>At : '+data[i].time+'</b><br>'+data[i].location).addTo(map).openPopup();
                       }else if(Number(data[i].type)==1){ //alert(i+'2222');
                        
                             var circleEnd = L.circle([data[i].latitude, data[i].longitude], {
                              color: 'blue',
                              fillColor: '#367',
                              fillOpacity: 0.5,
                              radius: 400
                            }).addTo(map);         
                        
                        }else{ // //alert(i+'4444');
                         marker = new L.marker([data[i].latitude, data[i].longitude],{icon : exeIcon})
                          .bindPopup('<b>'+text+'</b><br><b>At : '+data[i].time+'</b><br>'+data[i].location)
                          .addTo(map);
                        }  

                        j--;
                      }

                }
        });        

    });

</script>