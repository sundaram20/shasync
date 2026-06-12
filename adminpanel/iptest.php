<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_SHOP,'view');


function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
    $output = NULL;
    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    }
    $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
    $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
    $continents = array(
        "AF" => "Africa",
        "AN" => "Antarctica",
        "AS" => "Asia",
        "EU" => "Europe",
        "OC" => "Australia (Oceania)",
        "NA" => "North America",
        "SA" => "South America"
    );
    if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
        if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
            switch ($purpose) {
                case "location":
                    $output = array(
                        "city"           => @$ipdat->geoplugin_city,
                        "state"          => @$ipdat->geoplugin_regionName,
                        "country"        => @$ipdat->geoplugin_countryName,
                        "country_code"   => @$ipdat->geoplugin_countryCode,
                        "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                        "continent_code" => @$ipdat->geoplugin_continentCode
                    );
                    break;
                case "address":
                    $address = array($ipdat->geoplugin_countryName);
                    if (@strlen($ipdat->geoplugin_regionName) >= 1)
                        $address[] = $ipdat->geoplugin_regionName;
                    if (@strlen($ipdat->geoplugin_city) >= 1)
                        $address[] = $ipdat->geoplugin_city;
                    $output = implode(", ", array_reverse($address));
                    break;
                case "city":
                    $output = @$ipdat->geoplugin_city;
                    break;
                case "state":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "region":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "country":
                    $output = @$ipdat->geoplugin_countryName;
                    break;
                case "countrycode":
                    $output = @$ipdat->geoplugin_countryCode;
                    break;
            }
        }
    }
    return $output;
}

//USAGE OF FUNCTION
echo ip_info("160.238.70.23", "Country") . "<br/>"; // India
echo ip_info("160.238.70.23", "Country Code") . "<br/>"; // IN
echo ip_info("160.238.70.23", "State") . "<br/>"; // Haryāna
echo ip_info("160.238.70.23", "City") . "<br/>"; // Bhaskola
echo ip_info("160.238.70.23", "Address") . "<br/>"; // Bhaskola, Haryāna, India

print_r(ip_info("160.238.70.23", "Location")); // Array ( [city] => [state] => [country] => India [country_code] => IN [continent] => Asia [continent_code] => AS ) 






echo $_SERVER['REMOTE_ADDR'];
$url = json_decode(file_get_contents("http://api.ipinfodb.com/v3/ip-city/?key=<your_api_key>&ip=".$_SERVER['REMOTE_ADDR']."&format=json"));
 
//print the array to see the fields if you wish.
print_r($url);
 
echo "<table border='1' width='50%' align='center'><tr><td>COUNTRY:</td><td>";
echo $url->countryName;
echo "</td></tr><tr><td>CITY:</td><td>";
echo $url->cityName;
echo "</td></tr><tr><td>STATE OR REGION:</td><td>";
echo $url->regionName;
echo "</td></tr><tr><td>IP ADDRESS:</td><td>";
echo $url->ipAddress;
echo "</td></tr><tr><td>COUNTRY CODE:</td><td>";
echo $url->countryCode;
echo "</td></tr><tr><td>LATITUTE:</td><td>";
echo $url->latitude;
echo "</td></tr><tr><td>LONGITUDE:</td><td>";
echo $url->longitude;
echo "</td></tr><tr><td>TIMEZONE:</td><td>";
echo $url->timeZone;
echo "</td></tr><tr></table>";
die;

$urlTemplate = 'http://api.ip2location.com/?' . 'ip=%s&key=demo' . '&package=WS24&format=json';
 $host= gethostname();
 $ipAddress = gethostbyname($host);
 
 // replace the "%s" with real IP address
 $urlToCall = sprintf( $urlTemplate, $ipAddress);
 
 $rawJson = file_get_contents( $urlToCall );
 
 $geoLocation = json_decode( $rawJson, true );
 
 if(isset($geoLocation['city_name'])){
 
    if($geoLocation['city_name']!="-"){
        echo '<script language="javascript">';
        echo 'alert("Welcome Visitors from '.$geoLocation['city_name'].'")';
        echo '</script>';
    }else
    {
        echo '<center>You are in local server!</center><br>';
        echo '<script language="javascript">';
        echo 'alert("You are in local server!")';
        echo '</script>';
    }
 }else{
     echo 'IP Address parsing error!';
 }
?>
<html>
<head>
<title>IP2Location Web Service</title>
    </head>
<body>
<div>
<center>Hello World!</center><br>
</div>
<div>
<center>Your IP address <?php echo $ipAddress; ?></center>
      <center>
      <?php
      if(isset($geoLocation['country_code'])&&isset($geoLocation['country_name'])&&isset($geoLocation['region_name'])&&isset($geoLocation['city_name'])&&isset($geoLocation['latitude'])&&isset($geoLocation['longitude'])&&isset($geoLocation['zip_code'])&&isset($geoLocation['time_zone'])){
        echo '<br>Country Code:'."\n". $geoLocation['country_code'] . "\n<br>";
        echo 'Country Name:'."\n". $geoLocation['country_name'] . "\n<br>";
        echo 'Region Name:'."\n". $geoLocation['region_name'] . "\n<br>";
        echo 'City Name:'."\n". $geoLocation['city_name'] . "\n<br>";
        echo 'Latitude:'."\n". $geoLocation['latitude'] . "\n<br>";
        echo 'Longitude:'."\n". $geoLocation['longitude'] . "\n<br>";
        echo 'Zip code:'."\n". $geoLocation['zip_code'] . "\n<br>";
        echo 'Time zone:'."\n". $geoLocation['time_zone'] . "\n<br>";
      }else{
          echo 'IP Address parsing error!';
      }
      ?>
      </center>
</div>
</body>
</html>


<!DOCTYPE html>

<html>

     <head>
     <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
      <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true"></script>
     </head>

     <body>
          <p>Address: <div id="address"></div></p>
     </body>

     <script type="text/javascript" charset="utf-8">

     $(document).ready(function() {
         var currgeocoder;

         //Set geo location lat and long

         navigator.geolocation.getCurrentPosition(function(position, html5Error) {

             geo_loc = processGeolocationResult(position);
             currLatLong = geo_loc.split(",");
             initializeCurrent(currLatLong[0], currLatLong[1]);

        });

        //Get geo location result

       function processGeolocationResult(position) {
             html5Lat = position.coords.latitude; //Get latitude
             html5Lon = position.coords.longitude; //Get longitude
             html5TimeStamp = position.timestamp; //Get timestamp
             html5Accuracy = position.coords.accuracy; //Get accuracy in meters
             return (html5Lat).toFixed(8) + ", " + (html5Lon).toFixed(8);
       }

        //Check value is present or not & call google api function

        function initializeCurrent(latcurr, longcurr) {
             currgeocoder = new google.maps.Geocoder();
             console.log(latcurr + "-- ######## --" + longcurr);

             if (latcurr != '' && longcurr != '') {
                 var myLatlng = new google.maps.LatLng(latcurr, longcurr);
                 return getCurrentAddress(myLatlng);
             }
       }

        //Get current address

         function getCurrentAddress(location) {
              currgeocoder.geocode({
                  'location': location

            }, function(results, status) {
           
                if (status == google.maps.GeocoderStatus.OK) {
                    console.log(results[0]);
                    $("#address").html(results[0].formatted_address);
                } else {
                    alert('Geocode was not successful for the following reason: ' + status);
                }
            });
         }
    });

    </script>

</html>
<?php
echo "<pre>";
$latitude	= '13.0833';
$longitude='80.2833';

$geolocation = $latitude.','.$longitude;
$request = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.$geolocation.'&sensor=false'; 
$file_contents = file_get_contents($request);
$json_decode = json_decode($file_contents);
if(isset($json_decode->results[0])) {
    $response = array();
    foreach($json_decode->results[0]->address_components as $addressComponet) {
        if(in_array('political', $addressComponet->types)) {
                $response[] = $addressComponet->long_name; 
        }
    }

    if(isset($response[0])){ $first  =  $response[0];  } else { $first  = 'null'; }
    if(isset($response[1])){ $second =  $response[1];  } else { $second = 'null'; } 
    if(isset($response[2])){ $third  =  $response[2];  } else { $third  = 'null'; }
    if(isset($response[3])){ $fourth =  $response[3];  } else { $fourth = 'null'; }
    if(isset($response[4])){ $fifth  =  $response[4];  } else { $fifth  = 'null'; }

    if( $first != 'null' && $second != 'null' && $third != 'null' && $fourth != 'null' && $fifth != 'null' ) {
        echo "<br/>Address:: ".$first;
        echo "<br/>City:: ".$second;
        echo "<br/>State:: ".$fourth;
        echo "<br/>Country:: ".$fifth;
    }
    else if ( $first != 'null' && $second != 'null' && $third != 'null' && $fourth != 'null' && $fifth == 'null'  ) {
        echo "<br/>Address:: ".$first;
        echo "<br/>City:: ".$second;
        echo "<br/>State:: ".$third;
        echo "<br/>Country:: ".$fourth;
    }
    else if ( $first != 'null' && $second != 'null' && $third != 'null' && $fourth == 'null' && $fifth == 'null' ) {
        echo "<br/>City:: ".$first;
        echo "<br/>State:: ".$second;
        echo "<br/>Country:: ".$third;
    }
    else if ( $first != 'null' && $second != 'null' && $third == 'null' && $fourth == 'null' && $fifth == 'null'  ) {
        echo "<br/>State:: ".$first;
        echo "<br/>Country:: ".$second;
    }
    else if ( $first != 'null' && $second == 'null' && $third == 'null' && $fourth == 'null' && $fifth == 'null'  ) {
        echo "<br/>Country:: ".$first;
    }
  }

function get_ip_address() {
    // check for shared internet/ISP IP
    if (!empty($_SERVER['HTTP_CLIENT_IP']) && validate_ip($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }

    // check for IPs passing through proxies
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // check if multiple ips exist in var
        if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') !== false) {
            $iplist = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach ($iplist as $ip) {
                if (validate_ip($ip))
                    return $ip;
            }
        } else {
            if (validate_ip($_SERVER['HTTP_X_FORWARDED_FOR']))
                return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
    }
    if (!empty($_SERVER['HTTP_X_FORWARDED']) && validate_ip($_SERVER['HTTP_X_FORWARDED']))
        return $_SERVER['HTTP_X_FORWARDED'];
    if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && validate_ip($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
        return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    if (!empty($_SERVER['HTTP_FORWARDED_FOR']) && validate_ip($_SERVER['HTTP_FORWARDED_FOR']))
        return $_SERVER['HTTP_FORWARDED_FOR'];
    if (!empty($_SERVER['HTTP_FORWARDED']) && validate_ip($_SERVER['HTTP_FORWARDED']))
        return $_SERVER['HTTP_FORWARDED'];

    // return unreliable ip since all else failed
    return $_SERVER['REMOTE_ADDR'];
}

/**
 * Ensures an ip address is both a valid IP and does not fall within
 * a private network range.
 */
function validate_ip($ip) {
    if (strtolower($ip) === 'unknown')
        return false;

    // generate ipv4 network address
    $ip = ip2long($ip);

    // if the ip is set and not equivalent to 255.255.255.255
    if ($ip !== false && $ip !== -1) {
        // make sure to get unsigned long representation of ip
        // due to discrepancies between 32 and 64 bit OSes and
        // signed numbers (ints default to signed in PHP)
        $ip = sprintf('%u', $ip);
        // do private network range checking
        if ($ip >= 0 && $ip <= 50331647) return false;
        if ($ip >= 167772160 && $ip <= 184549375) return false;
        if ($ip >= 2130706432 && $ip <= 2147483647) return false;
        if ($ip >= 2851995648 && $ip <= 2852061183) return false;
        if ($ip >= 2886729728 && $ip <= 2887778303) return false;
        if ($ip >= 3221225984 && $ip <= 3221226239) return false;
        if ($ip >= 3232235520 && $ip <= 3232301055) return false;
        if ($ip >= 4294967040) return false;
    }
    return true;
}
echo get_ip_address();
echo $ip = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);

print_r($_SERVER);

$geolocation = $latitude.','.$longitude;

$request = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.$geolocation.'&sensor=false'; 
$file_contents = file_get_contents($request);
$json_decode = json_decode($file_contents);

$new_arr[]= unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$_SERVER['REMOTE_ADDR']));
echo "Latitude:".$new_arr[0]['geoplugin_latitude']." and Longitude:".$new_arr[0]['geoplugin_longitude'];




if(isset($json_decode->results[0])) {
	echo "1";
    $response = array();
    foreach($json_decode->results[0]->address_components as $addressComponet) {
        if(in_array('political', $addressComponet->types)) {
                $response[] = $addressComponet->long_name; 
        }
    }

    if(isset($response[0])){ $first  =  $response[0];  } else { $first  = 'null'; }
    if(isset($response[1])){ $second =  $response[1];  } else { $second = 'null'; } 
    if(isset($response[2])){ $third  =  $response[2];  } else { $third  = 'null'; }
    if(isset($response[3])){ $fourth =  $response[3];  } else { $fourth = 'null'; }
    if(isset($response[4])){ $fifth  =  $response[4];  } else { $fifth  = 'null'; }

    if( $first != 'null' && $second != 'null' && $third != 'null' && $fourth != 'null' && $fifth != 'null' ) {
        echo "<br/>Address:: ".$first;
        echo "<br/>City:: ".$second;
        echo "<br/>State:: ".$fourth;
        echo "<br/>Country:: ".$fifth;
    }
    else if ( $first != 'null' && $second != 'null' && $third != 'null' && $fourth != 'null' && $fifth == 'null'  ) {
        echo "<br/>Address:: ".$first;
        echo "<br/>City:: ".$second;
        echo "<br/>State:: ".$third;
        echo "<br/>Country:: ".$fourth;
    }
    else if ( $first != 'null' && $second != 'null' && $third != 'null' && $fourth == 'null' && $fifth == 'null' ) {
        echo "<br/>City:: ".$first;
        echo "<br/>State:: ".$second;
        echo "<br/>Country:: ".$third;
    }
    else if ( $first != 'null' && $second != 'null' && $third == 'null' && $fourth == 'null' && $fifth == 'null'  ) {
        echo "<br/>State:: ".$first;
        echo "<br/>Country:: ".$second;
    }
    else if ( $first != 'null' && $second == 'null' && $third == 'null' && $fourth == 'null' && $fifth == 'null'  ) {
        echo "<br/>Country:: ".$first;
    }
  }
  ?>

<script language="JavaScript" src="http://www.geoplugin.net/javascript.gp" type="text/javascript"></script>
<script language="JavaScript" src="http://www.geoplugin.net/javascript.gp" type="text/javascript"></script>

    <?php
	echo $user_ip = $_SERVER['REMOTE_ADDR'];
$geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$user_ip"));
$country = $geo["geoplugin_countryName"];
$city = $geo["geoplugin_city"];

	echo "<pre>";
	echo var_export(unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$_SERVER['REMOTE_ADDR'])));
		echo "</pre>";
$image_path = $UPLOAD_FILES.'/shop/';
$image_display_path = $UPLOAD_FILES_PATH ."/shop/";
if($_REQUEST['action'] == 'change'){
	if($_REQUEST['activeId'] != ''){
		checkUserLevelPermission($_SESSION['userLevel'],TBL_SHOP,'activate');
		$statusId = addslashes($_REQUEST['activeId']);
		$statusSql = "	UPDATE `".TBL_SHOP."` 
						SET `status` = '1' 
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` = '".addslashes($_REQUEST['activeId'])."'";
	}elseif($_REQUEST['inactiveId'] != ''){
		checkUserLevelPermission($_SESSION['userLevel'],TBL_SHOP,'deactivate');
		$statusId = addslashes($_REQUEST['inactiveId']);
		$statusSql = "	UPDATE `".TBL_SHOP."` 
						SET `status` = '0' 
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."' 
						WHERE `id` = '".addslashes($_REQUEST['inactiveId'])."'";
	}
	if(executeSql($statusSql)){
		$err = 0;
		$_SESSION['successMsg'] = 'Shop '.selectColumn(TBL_SHOP,'name'," WHERE `id` = '".$statusId."'").' status has been changed sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Shop '.selectColumn(TBL_SHOP,'name'," WHERE `id` = '".$statusId."'").' status has not been changed sucessfully.';
	}
}else if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_SHOP,'delete');
	$delSql = "DELETE FROM `".TBL_SHOP."` WHERE `id` = '".$_REQUEST['delId']."'";
	$sqlDelUsers = selectRow(TBL_SHOP," WHERE `id` = '".$_REQUEST['delId']."'");
	if(executeSql($delSql)){
		$err = 0;
		if(file_exists($image_path.$sqlDelUsers['image_small'])){
			@unlink($image_path.$sqlDelUsers['image_small']);
			@unlink($image_path.$sqlDelUsers['image_medium']);
			@unlink($image_path.$sqlDelUsers['image_large']);
		}
		$_SESSION['successMsg'] = 'One Shop '.$sqlDelUsers["name"].' has been deleted sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Unable to delete Shop '.$sqlDelUsers["name"];
	}
}
if($_REQUEST["act"] == "activate" && !empty($_REQUEST['ids'])){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_SHOP,'activate');	
	$activateIds = implode(',',$_REQUEST['ids']);	
	$statusSql = "	UPDATE `".TBL_SHOP."`
						SET `status` = '1'
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` IN (".addslashes($activateIds).")";	
										
	if(executeSql($statusSql)){
		$err = 0;
		$_SESSION['successMsg'] = 'Selected records status has been activated sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Selected records status has not been activated sucessfully.';
	}	
}else if($_REQUEST["act"] == "inactivate" && !empty($_REQUEST['ids'])){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_SHOP,'deactivate');	
	$deactivateIds = implode(',',$_REQUEST['ids']);	
	$statusSql = "	UPDATE `".TBL_SHOP."`
						SET `status` = '0'
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` IN (".addslashes($deactivateIds).")";	
										
	if(executeSql($statusSql)){
		$err = 0;
		$_SESSION['successMsg'] = 'Selected records status has been inactivated sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Selected records status has not been inactivated sucessfully.';
	}	
}else if($_REQUEST["act"] == "delete" && !empty($_REQUEST['ids'])){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_SHOP,'delete');	
	$deleteIds = implode(',',$_REQUEST['ids']);	
	$delSql = "DELETE FROM `".TBL_SHOP."` WHERE `id` IN (".addslashes($deleteIds).")";
	if(executeSql($delSql)){		
		$err = 0;
		$_SESSION['successMsg'] = 'Selected records has been deleted sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Unable to delete selected records';
	}
}

// ----------cate---------
$sqlUser = " SELECT * FROM `".TBL_SHOP."` WHERE `id` = '".addslashes($_SESSION['shop'])."'";
if($_REQUEST['search_name'] != ''){
	$sqlUser .= " AND `name` LIKE '%".addslashes($_REQUEST['search_name'])."%'";
}
if($_REQUEST['status'] != ''){
	$sqlUser .= " AND `status` = '".addslashes($_REQUEST['status'])."%'";
}
if($_REQUEST['order'] != ''){
	$sqlUser .= " ORDER BY `name`";
}else{
	$sqlUser .= " ORDER BY `name`";
}
$db->query($sqlUser);
$numRows= $db->num_rows();
$pagging = new pagingClass($sqlUser,$setpage);
$db->query($pagging->getQuery());
$total = $db->num_rows();
?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        User Manager
        <small>Manage Shop</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="manageShop.php">User  Manager</a></li>
        <li class="active">Manage Shop</li>
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
          <h3 class="box-title">Search <small>Total Records: (<?=$numRows;?>) &nbsp;</small> </h3>
			<div class="btn-group  pull-right">
                  <a type="button" class="btn btn-success" href="editShop.php">Add Shop</a>
                  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                   <?php /*?> <li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_SHOP;?>"><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>
                    <li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_SHOP;?>"><img  src="images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li><?php */?>
                  
                  </ul>
                </div>
				
        </div>
        <!-- /.box-header -->
		
		
		<form name="searchForm" action="" method="get">
           <input type="hidden" value="1" name="searchFormSubmit" /> 
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              
              <!-- /.form-group -->
              <div class="form-group">
                <label>User Title</label>
                <input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />
              </div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
            <div class="col-md-6">
              <div class="form-group">
                <label>Status</label>
                <?php 
						if($_REQUEST['status'] == '1'){
								$selected1 = 'selected="selected"';
						}elseif($_REQUEST['status'] == '0'){
								$selected0 = 'selected="selected"';
						}
						 echo $statusDropDown	 = '<select class="form-control select2" name="status"> <option value="">Both</option>
											 		 	<option '.$selected1.' value="1">Active</option>
											 		 	<option '.$selected0.' value="0">Inactive</option>
											  		</select>';
								?>
              </div>              
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
        <input name="Search" type="submit" class="btn btn-primary" value="Search" />
        </div>
		</form>
		
		
      </div>
	  
	  
      <div class="row">
        <div class="col-xs-12">    
		     
          <!-- /.box -->
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Users List</h3>
            </div>
			 <form name="listingForm" action="" method="post">
                <input type="hidden" value="" name="act" />
				  <div id="listingDiv"></div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th width="10%"><!--<input type='checkbox' name='CheckAll' id="CheckAll" value='Check All' />--> S.No.&nbsp;</th>
                  <th>Name</th>
                  <th>Status</th>
				  <th>Action</th>
                </tr>
                </thead>
                <tbody>
				 <?php 
				if($total > 0){$counter = 1;
				  while($rowUser = $db->fetch_object()){?>
                <tr>
                  <td><!--<input type="checkbox" name="ids[]" id="ids" value="<?=$rowUser->id;?>"/>--> <?php echo (($_REQUEST['page']-1)*$setpage)+$counter++;?>.&nbsp;</td>
                  <td><?=ucfirst($rowUser->name);?></td>
                  <td><?=$rowUser->status=='1'?'<span onclick="location.href=\'manageShop.php?inactiveId='.$rowUser->id.'&action=change\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'manageShop.php?activeId='.$rowUser->id.'&action=change\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>
				 
				  <td><img src="images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editShop.php?eId=<?=$rowUser->id?>&action=edit';" />&nbsp;&nbsp;<img src="images/delete.gif" style="cursor:pointer;" title="Delete" name="<?php echo $rowUser->name; ?>" id="<?php echo $rowUser->id;?>" onClick="deleteMe(this.id,this.name);"/> </td>
                </tr>
               <?php }?>    
			   
			    <!--<tr>
                     <td align="left" colspan="5">
					 <input name="delete_sel" type="button" class="btn btn-warning" value="Delete" onClick="javascript:formSubmit('delete');"/>&nbsp;&nbsp;&nbsp;&nbsp; 
					 <input name="active_sel" type="button" class="btn btn-success" value="Active" onClick="javascript:formSubmit('activate');"/>&nbsp;&nbsp;&nbsp;&nbsp;
					  <input name="inactive_sel" type="button" class="btn btn-danger" value="Inactive" onClick="javascript:formSubmit('inactivate');"/> </td>
				</tr>-->
				<tr>	 
					  <td align="right" colspan="5"><?php  echo $pagging->getLinks();?> </td>
                 </tr>             
				<?php }else {?>
				
				 <tr>
                      <td height="200" align="center" colspan="6">---- No Record Found ---- </td>
                 </tr>                 
				<?php }?>
                </tbody>                
              </table>			  
            </div>
		  </form>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div> 

  <script type="text/javascript">
  	function deleteMe(id,name){
  		var xhttp = new XMLHttpRequest();
  		  xhttp.onreadystatechange = function() {
  		    if (this.readyState == 4 && this.status == 200) {
  		    	console.log(this.responseText);
  		      if(this.responseText == 1){
  		      	alert("Transaction Found In the Table");
  		      }
  		      else{
  		      	if(confirm('Are you sure that you want to delete this record '+name+'?')){
  		      		window.location.href='manageShop.php?delId='+id+'&action=delete&page=<?=$_REQUEST['page']?>';
  		      	}
  		      }
  		    }
  		  };
  		  xhttp.open("GET", "ajax/ajaxCheckCompanyDomain.php?id_shop="+id, true);
  		  xhttp.send();
  	}
  </script> 

<?php include_once("includes/footer.php")?>  
                                             
