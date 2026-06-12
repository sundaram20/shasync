<?php session_start();
include("../config/data.config.php");
include("$LIB_DIR/functions.library.php");
include("$LIB_DIR/msgs.inc.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");
include("$LIB_DIR/class.pagingClass.php");
$db = new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
adminLoginCheck();


 $filename="CLICK__REPORT_".date("Y-m-d_h-i-s_".rand(11111,99999));
$SqlQuery=urldecode($_GET['download']);
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment;Filename=".$filename.".xls");


?>
<table width="100%" border="1">
<tr><th>Mobile/Sim Name</th><th>Network</th><th>Plan</th><th>Handset Price</th><th>Gift</th><th>Discount</th><th>Merchant Name</th><th>IpAddress</th><th>Website Name</th><th>Tracking Url</th><th>Date</th></tr>
<?php

$db->query($SqlQuery);
$numRows =$db->num_rows();
if($numRows > 0){$counter = 1;
		while($rowMobileSeo = $db->fetch_object()){?>	
			<tr>
            <?php if($rowMobileSeo->mobile_id != 0){ ?>   
                        <td><?=selectColumn(TBL_MOBILE_PHONES,'name'," WHERE `id` = '".addslashes($rowMobileSeo->mobile_id)."'");?></td>
            <?php }else{?>
                       <td><?=selectColumn(TBL_SIM_NAME,'name'," WHERE `id` = '".addslashes($rowMobileSeo->sim_id)."'");?></td>
           <?php }?>
            	
                <td><?=selectColumn(TBL_NETWORKS,'name'," WHERE `id` = '".addslashes($rowMobileSeo->network_id)."'");?> </td>
                <td><?=selectColumn(TBL_PLANS,'name'," WHERE `id` = '".addslashes($rowMobileSeo->plan_id)."'");?> </td>
                <td><?=addslashes($rowMobileSeo->handset_price) ;?> </td>
                <td><?=selectColumn(TBL_FREE_GIFTS,'name'," WHERE `id` = '".addslashes($rowMobileSeo->gift_id)."'");?> </td>
                <td><?=selectColumn(TBL_DISCOUNTS,'name'," WHERE `id` = '".addslashes($rowMobileSeo->discount_id)."'");?> </td>
                <td><?=selectColumn(TBL_MERCHANT,'name'," WHERE `id` = '".addslashes($rowMobileSeo->merchant_id)."'");?> </td>
                <td><?=$rowMobileSeo->ipAddress;?> </td>
                <td><?=selectColumn(TBL_WEBSITES,'name'," WHERE `id` = '".addslashes($rowMobileSeo->website_id)."'");?> </td>
                <td><?=$rowMobileSeo->trackingUrl;?> </td>
                <td><?=$rowMobileSeo->date_created;?> </td>
                
            </tr>
<?php } 
}?>
</table>