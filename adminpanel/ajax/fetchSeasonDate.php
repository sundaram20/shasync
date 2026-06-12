<?php
include_once("../../config/auto_loader.php");

$sql = "SELECT * FROM ".TBL_RATE_SEASON." WHERE id='".$_REQUEST['id_season']."' ";
$res =mysqli_query($connNew,$sql);
$row = mysqli_fetch_object($res);

echo date('d-m-Y',strtotime($row->start_date)).' to '.date('d-m-Y',strtotime($row->end_date));