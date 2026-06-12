<?php
session_start();
set_time_limit(600);
/**********
Author : Hitesh Aloney
Date : 10-01-2020
Description : Dashbord Graphs PDF automailer.
***********/



//CRON URL COMMAND
/*/usr/local/bin/php -q /home/admingcs/public_html/sync/adminpanel/cronjobs/dashboardReportPdfAutomailer.php /dev/null 2>&1 */

///////////////// LOCAL PATH  ///////////////////
/*
* Uncomment If working on local
*/
include_once("../../config/data.config.php");
include_once("../../phplib/data.constant.php");	
include_once("../../phplib/functions.library.php");
include("../../phplib/PHPMailer/PHPMailerAutoload.php");
include_once("../../phplib/class.mailer.php");
include_once("../../phplib/class.database.php");
include("../../phplib/dompdf/dompdf_config.inc.php");
include("../../phplib/phpgraphlib/phpgraphlib.php");
include('../../phplib/phpgraphlib/phpgraphlib_pie.php');
include('../../adminpanel/includes/dashboardGraphFunction.php');

///////////////// CRON JOB PATH ///////////////////
/*
* Uncomment If working on server
*/
// $path = getcwd().'/public_html/crs';
// include_once($path."/config/data.config.php");
// include_once(str_replace('crs','sales',$path)."/phplib/data.constant.php");	
// include_once($path."/phplib/functions.library.php");
// include_once($path."/phplib/PHPMailer/PHPMailerAutoload.php");
// include_once(str_replace('crs','sales',$path)."/phplib/class.mailer.php");
// include_once($path."/phplib/class.database.php");
// include($path."/phplib/dompdf/dompdf_config.inc.php");
// include_once(str_replace('crs','sales',$path)."/phplib/phpgraphlib/phpgraphlib.php");	

/*********** DATEA BASE CONNECTIONS *************/
$DB_NAME='inroomhu_fern';
$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$connNew = mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);

/************** Setting Variables **********************/

// local file save path
$attachPath='../mailattach/';

//cron job save path
// $attachPath=str_replace('crs','sales',$path).'/adminpanel/mailattach/';

$unitUser = $_SESSION['unit_user'];

$leadGraphData = leadGraphData(date('Y-m-d',strtotime($_REQUEST['report_date'])), $_REQUEST['id_team'], $_SESSION['teamMembers'], $_SESSION['shop'], $unitUser);

$performanceGraphData = performanceGraphData(date('Y-m-d',strtotime($_REQUEST['report_date'])), $_REQUEST['id_team'], $_SESSION['teamMembers'], $_SESSION['shop']);

$generateArr = array_combine($leadGraphData['reasons'],$leadGraphData['reasonval']);
$receivedArr = array_combine($leadGraphData['reasons'],$leadGraphData['reasonValRec']);

$mtdLastYear = array_combine($performanceGraphData['executives'],$performanceGraphData['mtdLastVal']);
$mtdThisYear = array_combine($performanceGraphData['executives'],$performanceGraphData['mtdThisVal']);

$ytdLastYear = array_combine($performanceGraphData['executives'],$performanceGraphData['ytdLastVal']);
$ytdThisYear = array_combine($performanceGraphData['executives'],$performanceGraphData['ytdThisVal']);

$dataSetMtd = array($mtdLastYear, $mtdThisYear);
$dataSetYtd = array($ytdLastYear, $ytdThisYear);



$files = array();

$fileMtdName='mtd'.date('d_m_Y_H_i_s ');
array_push($files, $fileMtdName.'.png');
$graphTitle='Month To Date';
$barColors=array('red','green');
$legendTitle=array('Last Yr','This Yr');
mtdYtdGraph($fileMtdName, $graphTitle, $dataSetMtd, $legendTitle);

$fileYtdName='ytd'.date('d_m_Y_H_i_s ');
array_push($files, $fileYtdName.'.png');
$graphTitle='Year To Date: Budget:'.array_sum($performanceGraphData['budgetVal']).'';
$barColors=array('red','green');
$legendTitle=array('Last Yr','This Yr');
mtdYtdGraph($fileYtdName, $graphTitle, $dataSetYtd, $legendTitle);

$content="
				<style>
					table{
						width:100%;
						height:auto;
						margin-bottom:10px;
					}
					tr{
						text-align:center;
						
					}
					td,th{
						border:1px solid black;
						
					}

				</style>
			";

$content.="<table  cellspacing='0' >";

$content.="	<tr>
			   <td colspan='2' style='font-size:20px;background-color:#3C8DBC;color:#fff;font-weight:bold;'>Team : ".selectColumn('mst_team','name','WHERE id="'.$_REQUEST['id_team'].'" ')."</td>
			</tr>
			<tr>
			   <td colspan='2' style='font-size:18px;background-color:#e3e3e3;color:#000;font-weight:bold;'>Performance Analysis For Period ".$performanceGraphData['reportPeriod']."</td>
			</tr>";	

$content.="<tr >
			   <td><img src='".$attachPath.$fileMtdName.".png'></td>
			   <td><img src='".$attachPath.$fileYtdName.".png'></td>
			</tr>";	

$content.="</table>";





$content.="<table cellspacing='0' style='page-break-after:always;'>";


$content.="<tr>
			   <td colspan='10' style='font-size:18px;background-color:#e3e3e3;color:#000;font-weight:bold;'>Sales Summary For Period ".$performanceGraphData['reportPeriod']."</td>
			</tr>";	

$content.="
			<tr style='background-color:#3C8DBC;color:#fff;font-weight:bold;'>
				<td rowspan='2'>Executive</td>
			    <td colspan='4'>Month To Date</td>
			   <td colspan='5'>Year To Date</td>
			</tr>";	

$content.="
			<tr style='background-color:#3C8DBC;color:#fff;'>
				<td >Visits</td>
			    <td>Rate Letters</td>
			    <td>Total Expense</td>
			    <td>Avg. Daily Call</td>
			    <td>Visits</td>
			    <td>Rate Letters</td>
			    <td>Total Expense</td>
			    <td>Avg. Daily Call</td>
			    <td >Yearly Budget</td>
			</tr>";	
$avgMtd = 0;
$avgYtd = 0;

for ($i=0; $i < count($performanceGraphData['executives']) ; $i++) { 

	$avgMtd += round($performanceGraphData['mtdVisits'][$i]/$performanceGraphData['totalDaysGoneMtd'],2);
	$avgYtd += round($performanceGraphData['ytdVisits'][$i]/$performanceGraphData['totalDaysGoneYtd'],2);

	$content.="
			<tr >
				<td >".$performanceGraphData['executives'][$i]."</td>
				<td >".$performanceGraphData['mtdVisits'][$i]."</td>
			    <td>".$performanceGraphData['mtdRateLetters'][$i]."</td>
			    <td>".$performanceGraphData['mtdTotalExpense'][$i]."</td>
			    <td>".round(($performanceGraphData['mtdVisits'][$i]/$performanceGraphData['totalDaysGoneMtd']),2)."</td>
			    <td>".$performanceGraphData['ytdVisits'][$i]."</td>
			    <td>".$performanceGraphData['ytdRateLetters'][$i]."</td>
			    <td>".$performanceGraphData['ytdTotalExpense'][$i]."</td>
			    <td>".round(($performanceGraphData['ytdVisits'][$i]/$performanceGraphData['totalDaysGoneYtd']),2)."</td>
			    <td >".$performanceGraphData['budgetVal'][$i]."</td>
			</tr>";	



}

$content.="
			<tr >
				<th >Total</td>
				<th >".array_sum($performanceGraphData['mtdVisits'])."</th>
			    <th>".array_sum($performanceGraphData['mtdRateLetters'])."</th>
			    <th>".array_sum($performanceGraphData['mtdTotalExpense'])."</th>
			    <th>".round(($avgMtd/count($performanceGraphData['executives'])),2)."</th>
			    <th>".array_sum($performanceGraphData['ytdVisits'])."</th>
			    <th>".array_sum($performanceGraphData['ytdRateLetters'])."</th>
			    <th>".array_sum($performanceGraphData['ytdTotalExpense'])."</th>
			    <th>".round(($avgYtd/count($performanceGraphData['executives'])),2)."</th>
			    <th>".array_sum($performanceGraphData['budgetVal'])."</th>
			</tr>";	

$content.="</table>";

$leadGen = 'gen'.date('d_m_Y_H_i_s ');
$graphTitle='Lead Generated';
leadGraph($leadGen, $graphTitle, $generateArr);
array_push($files, $leadGen.'.png');

$leadRec = 'rec'.date('d_m_Y_H_i_s ');
$graphTitle='Lead Received';
leadGraph($leadRec, $graphTitle, $receivedArr);
array_push($files, $leadRec.'.png');

$content.="<table cellspacing='0'>";

$content.="<tr>
			   <td colspan='2' style='font-size:18px;background-color:#e3e3e3;color:#000;font-weight:bold;'>Lead Summary For Period  ".$performanceGraphData['reportPeriod']."</td>
			</tr>";	

$content.="<tr>
			   <td><img src='".$attachPath.$leadGen.".png'></td>
			   <td><img src='".$attachPath.$leadRec.".png'></td>
			</tr>";	


$content.="</table>";			

$content.="</body>";
$fileName='dashboard'.date('H_i_s');
pdfGenerator($content, $fileName);
array_push($files, $fileName.'.pdf');

foreach ($files as $index => $name) {
	unlink($attachPath.$name);
}

?>