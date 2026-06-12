<?php /*?><table class="table table-striped text-center">
  <tbody>
    <tr style="color:white;">
      <th colspan="13" style="background-color:#3C8DBC;vertical-align: middle;">Sales Summary 01-04-2024 To 14-05-2024</th>
    </tr>
    <tr style="color:white;">
      <th rowspan="2" style="background-color:#3C8DBC;vertical-align: middle;">Executive</th>
      <th style="background-color:#3C8DBC;" colspan="3">Today</th>
       <th style="background-color:#3C8DBC;border-left:1px solid #252525;" colspan="4">Month To Date</th>
      <th style="background-color:#3C8DBC;border-left:1px solid #252525;" colspan="4">Year To Date</th>
    </tr>
    <tr style="background-color:#5cb4e8;">
      <th>Value</th>
      <th>Points</th>
      <th>Relized Points</th>
      
      <th style="border-left:1px solid #252525;">Value</th>
      <th>Points</th>
      <th>Relized Points</th>
      <th >Eligible Points</th>
      			

      <th style="border-left:1px solid #252525;">Value</th>
      <th>Points</th>
      <th>Relized Points</th>
      <th>Eligible Points</th>
    </tr>
    <tr>
      <td style="text-align:left;">Admin</td>
      <td>0</td>
      <td>0</td>
      <td>0</td>
      <td style="border-left:1px solid #252525;">0.00</td>
      <td>0</td>
      <td>0</td>
      <td>0</td>
      <td style="border-left:1px solid #252525;">0.00</td>
      <td>0</td>
      <td>0</td>
      <td>0</td>
    </tr>
    <tr>
      <td style="text-align:left;">Bhawna</td>
      <td>0</td>
      <td>0</td>
      <td>0</td>
      <td style="border-left:1px solid #252525;">0.00</td>
      <td>0</td>
      <td>0</td>
      <td>0</td>
      <td style="border-left:1px solid #252525;">0.00</td>
      <td>0</td>
      <td>0</td>
      <td>0</td>
    </tr>
    <tr>
      <td style="text-align:left;">Deepika Bhardwaj</td>
 <td>0</td>
      <td>0</td>
      <td>0</td>
      <td style="border-left:1px solid #252525;">0.00</td>
      <td>0</td>
      <td>0</td>
      <td>0</td>
      <td style="border-left:1px solid #252525;">0.00</td>
      <td>0</td>
      <td>0</td>
      <td>0</td>
    </tr>
    <tr>
      <td style="text-align:left;">Jaykishor</td>
     <td>0</td>
      <td>0</td>
      <td>0</td>
      <td style="border-left:1px solid #252525;">0.00</td>
      <td>0</td>
      <td>0</td>
      <td>0</td>
      <td style="border-left:1px solid #252525;">0.00</td>
      <td>0</td>
      <td>0</td>
      <td>0</td>
    </tr>
    <tr>
      <td style="text-align:left;">Rahul Kumar Paswan</td>
      <td>0</td>
      <td>0</td>
      <td>0</td>
      <td style="border-left:1px solid #252525;">0.00</td>
      <td>0</td>
      <td>0</td>
      <td>0</td>
      <td style="border-left:1px solid #252525;">0.00</td>
      <td>0</td>
      <td>0</td>
      <td>0</td>
    </tr>
    <tr>
      <td style="text-align:left;">Sangeeta</td>
     <td>0</td>
      <td>0</td>
      <td>0</td>
      <td style="border-left:1px solid #252525;">0.00</td>
      <td>0</td>
      <td>0</td>
      <td>0</td>
      <td style="border-left:1px solid #252525;">0.00</td>
      <td>0</td>
      <td>0</td>
      <td>0</td>
    </tr>
    <tr>
      <td style="text-align:left;">Sundaram</td>
    <td>0</td>
      <td>0</td>
      <td>0</td>
      <td style="border-left:1px solid #252525;">0.00</td>
      <td>0</td>
      <td>0</td>
      <td>0</td>
      <td style="border-left:1px solid #252525;">0.00</td>
      <td>0</td>
      <td>0</td>
      <td>0</td>
    </tr>
    <tr>
      <td style="text-align:left;">Talmeez Saifi</td>
    <td>0</td>
      <td>0</td>
      <td>0</td>
      <td style="border-left:1px solid #252525;">0.00</td>
      <td>0</td>
      <td>0</td>
      <td>0</td>
      <td style="border-left:1px solid #252525;">0.00</td>
      <td>0</td>
      <td>0</td>
      <td>0</td>
    </tr>
    <tr>
      <td style="text-align:left;">Vipin Pandey</td>
     <td>0</td>
      <td>0</td>
      <td>0</td>
      <td style="border-left:1px solid #252525;">0.00</td>
      <td>0</td>
      <td>0</td>
      <td>0</td>
      <td style="border-left:1px solid #252525;">0.00</td>
      <td>0</td>
      <td>0</td>
      <td>0</td>
    </tr>
    <tr style="font-weight:bold;">
      <td style="text-align:left;">Total</td>
      <td>0</td>
      <td>0</td>
      <td>0</td>
      <td style="border-left:1px solid #252525;">0.00</td>
      <td>0</td>
      <td>0</td>
      <td>0</td>
      <td style="border-left:1px solid #252525;">0.00</td>
      <td>0</td>
      <td>0</td>
      <td>0</td>
    </tr>
  </tbody>
</table><?php */?>
<?php 



include_once("../../config/auto_loader.php");


if(($_SESSION['errorMsg']!='') || ($_SESSION['userId']=='')){
    //echo $_SESSION['errorMsg'];
    ?>
    <script type="text/javascript">
    window.location.href='<?php echo $SITE_URL;?>/adminpanel/index.php';
   
   </script>
<?php	
}

include_once("../includes/functionDailyPickupReport.php");
$shop_id=$_SESSION['shop'];
$CronSet=0;
echo functionDailyReport($_REQUEST['period'],$connNew,$db,$shop_id,$CronSet,$_REQUEST['period']);

?>



