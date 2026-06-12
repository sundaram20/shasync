<?php include_once("../../config/auto_loader.php");?>

<style>
table.dataTable{width:100%;margin:0 auto;clear:both;border-collapse:separate;border-spacing:0}table.dataTable thead th,table.dataTable tfoot th{font-weight:bold}table.dataTable thead th,table.dataTable thead td{padding:10px 18px;border-bottom:1px solid #111}table.dataTable thead th:active,table.dataTable thead td:active{outline:none}table.dataTable tfoot th,table.dataTable tfoot td{padding:10px 18px 6px 18px;border-top:1px solid #111}table.dataTable thead .sorting,table.dataTable thead .sorting_asc,table.dataTable thead .sorting_desc,table.dataTable thead .sorting_asc_disabled,table.dataTable thead .sorting_desc_disabled{cursor:pointer;*cursor:hand;background-repeat:no-repeat;background-position:center right}table.dataTable thead .sorting{background-image:url("../images/sort_both.png")}table.dataTable thead .sorting_asc{background-image:url("../images/sort_asc.png")}table.dataTable thead .sorting_desc{background-image:url("../images/sort_desc.png")}table.dataTable thead .sorting_asc_disabled{background-image:url("../images/sort_asc_disabled.png")}table.dataTable thead .sorting_desc_disabled{background-image:url("../images/sort_desc_disabled.png")}table.dataTable tbody tr{background-color:#ffffff}table.dataTable tbody tr.selected{background-color:#B0BED9}table.dataTable tbody th,table.dataTable tbody td{padding:8px 10px}table.dataTable.row-border tbody th,table.dataTable.row-border tbody td,table.dataTable.display tbody th,table.dataTable.display tbody td{border-top:1px solid #ddd}table.dataTable.row-border tbody tr:first-child th,table.dataTable.row-border tbody tr:first-child td,table.dataTable.display tbody tr:first-child th,table.dataTable.display tbody tr:first-child td{border-top:none}table.dataTable.cell-border tbody th,table.dataTable.cell-border tbody td{border-top:1px solid #ddd;border-right:1px solid #ddd}table.dataTable.cell-border tbody tr th:first-child,table.dataTable.cell-border tbody tr td:first-child{border-left:1px solid #ddd}table.dataTable.cell-border tbody tr:first-child th,table.dataTable.cell-border tbody tr:first-child td{border-top:none}table.dataTable.stripe tbody tr.odd,table.dataTable.display tbody tr.odd{background-color:#f9f9f9}table.dataTable.stripe tbody tr.odd.selected,table.dataTable.display tbody tr.odd.selected{background-color:#acbad4}table.dataTable.hover tbody tr:hover,table.dataTable.display tbody tr:hover{background-color:#f6f6f6}table.dataTable.hover tbody tr:hover.selected,table.dataTable.display tbody tr:hover.selected{background-color:#aab7d1}table.dataTable.order-column tbody tr>.sorting_1,table.dataTable.order-column tbody tr>.sorting_2,table.dataTable.order-column tbody tr>.sorting_3,table.dataTable.display tbody tr>.sorting_1,table.dataTable.display tbody tr>.sorting_2,table.dataTable.display tbody tr>.sorting_3{background-color:#fafafa}table.dataTable.order-column tbody tr.selected>.sorting_1,table.dataTable.order-column tbody tr.selected>.sorting_2,table.dataTable.order-column tbody tr.selected>.sorting_3,table.dataTable.display tbody tr.selected>.sorting_1,table.dataTable.display tbody tr.selected>.sorting_2,table.dataTable.display tbody tr.selected>.sorting_3{background-color:#acbad5}table.dataTable.display tbody tr.odd>.sorting_1,table.dataTable.order-column.stripe tbody tr.odd>.sorting_1{background-color:#f1f1f1}table.dataTable.display tbody tr.odd>.sorting_2,table.dataTable.order-column.stripe tbody tr.odd>.sorting_2{background-color:#f3f3f3}table.dataTable.display tbody tr.odd>.sorting_3,table.dataTable.order-column.stripe tbody tr.odd>.sorting_3{background-color:whitesmoke}table.dataTable.display tbody tr.odd.selected>.sorting_1,table.dataTable.order-column.stripe tbody tr.odd.selected>.sorting_1{background-color:#a6b4cd}table.dataTable.display tbody tr.odd.selected>.sorting_2,table.dataTable.order-column.stripe tbody tr.odd.selected>.sorting_2{background-color:#a8b5cf}table.dataTable.display tbody tr.odd.selected>.sorting_3,table.dataTable.order-column.stripe tbody tr.odd.selected>.sorting_3{background-color:#a9b7d1}table.dataTable.display tbody tr.even>.sorting_1,table.dataTable.order-column.stripe tbody tr.even>.sorting_1{background-color:#fafafa}table.dataTable.display tbody tr.even>.sorting_2,table.dataTable.order-column.stripe tbody tr.even>.sorting_2{background-color:#fcfcfc}table.dataTable.display tbody tr.even>.sorting_3,table.dataTable.order-column.stripe tbody tr.even>.sorting_3{background-color:#fefefe}table.dataTable.display tbody tr.even.selected>.sorting_1,table.dataTable.order-column.stripe tbody tr.even.selected>.sorting_1{background-color:#acbad5}table.dataTable.display tbody tr.even.selected>.sorting_2,table.dataTable.order-column.stripe tbody tr.even.selected>.sorting_2{background-color:#aebcd6}table.dataTable.display tbody tr.even.selected>.sorting_3,table.dataTable.order-column.stripe tbody tr.even.selected>.sorting_3{background-color:#afbdd8}table.dataTable.display tbody tr:hover>.sorting_1,table.dataTable.order-column.hover tbody tr:hover>.sorting_1{background-color:#eaeaea}table.dataTable.display tbody tr:hover>.sorting_2,table.dataTable.order-column.hover tbody tr:hover>.sorting_2{background-color:#ececec}table.dataTable.display tbody tr:hover>.sorting_3,table.dataTable.order-column.hover tbody tr:hover>.sorting_3{background-color:#efefef}table.dataTable.display tbody tr:hover.selected>.sorting_1,table.dataTable.order-column.hover tbody tr:hover.selected>.sorting_1{background-color:#a2aec7}table.dataTable.display tbody tr:hover.selected>.sorting_2,table.dataTable.order-column.hover tbody tr:hover.selected>.sorting_2{background-color:#a3b0c9}table.dataTable.display tbody tr:hover.selected>.sorting_3,table.dataTable.order-column.hover tbody tr:hover.selected>.sorting_3{background-color:#a5b2cb}table.dataTable.no-footer{border-bottom:1px solid #111}table.dataTable.nowrap th,table.dataTable.nowrap td{white-space:nowrap}table.dataTable.compact thead th,table.dataTable.compact thead td{padding:4px 17px 4px 4px}table.dataTable.compact tfoot th,table.dataTable.compact tfoot td{padding:4px}table.dataTable.compact tbody th,table.dataTable.compact tbody td{padding:4px}table.dataTable th.dt-left,table.dataTable td.dt-left{text-align:left}table.dataTable th.dt-center,table.dataTable td.dt-center,table.dataTable td.dataTables_empty{text-align:center}table.dataTable th.dt-right,table.dataTable td.dt-right{text-align:right}table.dataTable th.dt-justify,table.dataTable td.dt-justify{text-align:justify}table.dataTable th.dt-nowrap,table.dataTable td.dt-nowrap{white-space:nowrap}table.dataTable thead th.dt-head-left,table.dataTable thead td.dt-head-left,table.dataTable tfoot th.dt-head-left,table.dataTable tfoot td.dt-head-left{text-align:left}table.dataTable thead th.dt-head-center,table.dataTable thead td.dt-head-center,table.dataTable tfoot th.dt-head-center,table.dataTable tfoot td.dt-head-center{text-align:center}table.dataTable thead th.dt-head-right,table.dataTable thead td.dt-head-right,table.dataTable tfoot th.dt-head-right,table.dataTable tfoot td.dt-head-right{text-align:right}table.dataTable thead th.dt-head-justify,table.dataTable thead td.dt-head-justify,table.dataTable tfoot th.dt-head-justify,table.dataTable tfoot td.dt-head-justify{text-align:justify}table.dataTable thead th.dt-head-nowrap,table.dataTable thead td.dt-head-nowrap,table.dataTable tfoot th.dt-head-nowrap,table.dataTable tfoot td.dt-head-nowrap{white-space:nowrap}table.dataTable tbody th.dt-body-left,table.dataTable tbody td.dt-body-left{text-align:left}table.dataTable tbody th.dt-body-center,table.dataTable tbody td.dt-body-center{text-align:center}table.dataTable tbody th.dt-body-right,table.dataTable tbody td.dt-body-right{text-align:right}table.dataTable tbody th.dt-body-justify,table.dataTable tbody td.dt-body-justify{text-align:justify}table.dataTable tbody th.dt-body-nowrap,table.dataTable tbody td.dt-body-nowrap{white-space:nowrap}table.dataTable,table.dataTable th,table.dataTable td{box-sizing:content-box}.dataTables_wrapper{position:relative;clear:both;*zoom:1;zoom:1}.dataTables_wrapper .dataTables_length{float:left}.dataTables_wrapper .dataTables_filter{float:right;text-align:right}.dataTables_wrapper .dataTables_filter input{margin-left:0.5em}.dataTables_wrapper .dataTables_info{clear:both;float:left;padding-top:0.755em}.dataTables_wrapper .dataTables_paginate{float:right;text-align:right;padding-top:0.25em}.dataTables_wrapper .dataTables_paginate .paginate_button{box-sizing:border-box;display:inline-block;min-width:1.5em;padding:0.5em 1em;margin-left:2px;text-align:center;text-decoration:none !important;cursor:pointer;*cursor:hand;color:#333 !important;border:1px solid transparent;border-radius:2px}.dataTables_wrapper .dataTables_paginate .paginate_button.current,.dataTables_wrapper .dataTables_paginate .paginate_button.current:hover{color:#333 !important;border:1px solid #979797;background-color:white;background:-webkit-gradient(linear, left top, left bottom, color-stop(0%, #fff), color-stop(100%, #dcdcdc));background:-webkit-linear-gradient(top, #fff 0%, #dcdcdc 100%);background:-moz-linear-gradient(top, #fff 0%, #dcdcdc 100%);background:-ms-linear-gradient(top, #fff 0%, #dcdcdc 100%);background:-o-linear-gradient(top, #fff 0%, #dcdcdc 100%);background:linear-gradient(to bottom, #fff 0%, #dcdcdc 100%)}.dataTables_wrapper .dataTables_paginate .paginate_button.disabled,.dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover,.dataTables_wrapper .dataTables_paginate .paginate_button.disabled:active{cursor:default;color:#666 !important;border:1px solid transparent;background:transparent;box-shadow:none}.dataTables_wrapper .dataTables_paginate .paginate_button:hover{color:white !important;border:1px solid #111;background-color:#585858;background:-webkit-gradient(linear, left top, left bottom, color-stop(0%, #585858), color-stop(100%, #111));background:-webkit-linear-gradient(top, #585858 0%, #111 100%);background:-moz-linear-gradient(top, #585858 0%, #111 100%);background:-ms-linear-gradient(top, #585858 0%, #111 100%);background:-o-linear-gradient(top, #585858 0%, #111 100%);background:linear-gradient(to bottom, #585858 0%, #111 100%)}.dataTables_wrapper .dataTables_paginate .paginate_button:active{outline:none;background-color:#2b2b2b;background:-webkit-gradient(linear, left top, left bottom, color-stop(0%, #2b2b2b), color-stop(100%, #0c0c0c));background:-webkit-linear-gradient(top, #2b2b2b 0%, #0c0c0c 100%);background:-moz-linear-gradient(top, #2b2b2b 0%, #0c0c0c 100%);background:-ms-linear-gradient(top, #2b2b2b 0%, #0c0c0c 100%);background:-o-linear-gradient(top, #2b2b2b 0%, #0c0c0c 100%);background:linear-gradient(to bottom, #2b2b2b 0%, #0c0c0c 100%);box-shadow:inset 0 0 3px #111}.dataTables_wrapper .dataTables_paginate .ellipsis{padding:0 1em}.dataTables_wrapper .dataTables_processing{position:absolute;top:50%;left:50%;width:100%;height:40px;margin-left:-50%;margin-top:-25px;padding-top:20px;text-align:center;font-size:1.2em;background-color:white;background:-webkit-gradient(linear, left top, right top, color-stop(0%, rgba(255,255,255,0)), color-stop(25%, rgba(255,255,255,0.9)), color-stop(75%, rgba(255,255,255,0.9)), color-stop(100%, rgba(255,255,255,0)));background:-webkit-linear-gradient(left, rgba(255,255,255,0) 0%, rgba(255,255,255,0.9) 25%, rgba(255,255,255,0.9) 75%, rgba(255,255,255,0) 100%);background:-moz-linear-gradient(left, rgba(255,255,255,0) 0%, rgba(255,255,255,0.9) 25%, rgba(255,255,255,0.9) 75%, rgba(255,255,255,0) 100%);background:-ms-linear-gradient(left, rgba(255,255,255,0) 0%, rgba(255,255,255,0.9) 25%, rgba(255,255,255,0.9) 75%, rgba(255,255,255,0) 100%);background:-o-linear-gradient(left, rgba(255,255,255,0) 0%, rgba(255,255,255,0.9) 25%, rgba(255,255,255,0.9) 75%, rgba(255,255,255,0) 100%);background:linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.9) 25%, rgba(255,255,255,0.9) 75%, rgba(255,255,255,0) 100%)}.dataTables_wrapper .dataTables_length,.dataTables_wrapper .dataTables_filter,.dataTables_wrapper .dataTables_info,.dataTables_wrapper .dataTables_processing,.dataTables_wrapper .dataTables_paginate{color:#333}.dataTables_wrapper .dataTables_scroll{clear:both}.dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody{*margin-top:-1px;-webkit-overflow-scrolling:touch}.dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody>table>thead>tr>th,.dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody>table>thead>tr>td,.dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody>table>tbody>tr>th,.dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody>table>tbody>tr>td{vertical-align:middle}.dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody>table>thead>tr>th>div.dataTables_sizing,.dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody>table>thead>tr>td>div.dataTables_sizing,.dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody>table>tbody>tr>th>div.dataTables_sizing,.dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody>table>tbody>tr>td>div.dataTables_sizing{height:0;overflow:hidden;margin:0 !important;padding:0 !important}.dataTables_wrapper.no-footer .dataTables_scrollBody{border-bottom:1px solid #111}.dataTables_wrapper.no-footer div.dataTables_scrollHead table.dataTable,.dataTables_wrapper.no-footer div.dataTables_scrollBody>table{border-bottom:none}.dataTables_wrapper:after{visibility:hidden;display:block;content:"";clear:both;height:0}@media screen and (max-width: 767px){.dataTables_wrapper .dataTables_info,.dataTables_wrapper .dataTables_paginate{float:none;text-align:center}.dataTables_wrapper .dataTables_paginate{margin-top:0.5em}}@media screen and (max-width: 640px){.dataTables_wrapper .dataTables_length,.dataTables_wrapper .dataTables_filter{float:none;text-align:center}.dataTables_wrapper .dataTables_filter{margin-top:0.5em}}

</style>
<script>
$company = $('#search_name').select2();
  $(function () {
   // $('#example1').DataTable()
    $('#example2').DataTable({
		responsive: true, "dom": '<f<t>lip>',
				 "oLanguage": 
				 	{ "sSearch": "Search By Agent:"} ,
				
      'paging'      : true,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false,
	 
				    "searchable": false, "targets": [1,2,3,4,5]
				 	 	
	  
    })
	
  })
 
</script>
<?php
checkUserLevelPermission($_SESSION['userLevel'],TBL_AGENT_ACHIEVED,'update');
$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
        

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//print_r($_REQUEST);

$hotelId = $_REQUEST['hotelId'];

//$room_id = implode(',',$_REQUEST['roomId'])	;

$season = $_REQUEST['seasonId'];

//$id = encryptor('decrypt',$_REQUEST['hotelId']);



?>
<div class="box box-success  table-responsive no-padding">

<div class="box">
   <div class="box-body table-responsive">

              <table class="table table-bordered table-striped">
                <thead>
               <?php  $AddCompanylist .= '<tr> 
		<th>Agent</th>
		<th>Apr - '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>		
		<th>May- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Jun- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Jul- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>				  
		<th>Aug- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Sep- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Oct- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Nov- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Dec- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Jan- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Feb- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Mar- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Total</th>
		</tr>
                </thead>
                <tbody>
		';
$AddCompanylist .= '<tr>';
    $editstart_date =	$editrow->start_date;
	$editend_date 	=	$editrow->end_date;
	$totalqtyHori=0;

	
	//$availableData .= '<input type="hidden" id="bugetHotel" name="bugetHotel[]" value="'.$rowHotelResult->id.'" >';
	$AddCompanylist .= '<td style="width: 20%;">';
	$AddCompanylist .= '<input type="hidden"  name="selectuserid" id="selectuserid" value="'.$_REQUEST['hotelId'].'">
	<input type="hidden"  id="selectseasonId" name="selectseasonId" value="'.$_REQUEST['seasonId'].'">
	<input type="hidden"  id="id_hotel" name="id_hotel" value="'.$_REQUEST['id_hotel'].'">';
	
				 $AddCompanylist .= '<select class="form-control select2" name="search_name" id="search_name" onchange="updatecompanybudget(this.value);">
											    <option value="">Select Company </option>';
											 
	
	 $resCat12 = "SELECT A.name,A.id_company,A.city FROM `".TBL_COMPANY."` AS A 
		LEFT JOIN `".TBL_AREAS."` AS B ON A.area=B.id
		WHERE FIND_IN_SET('".$_REQUEST['hotelId']."',B.ids_unit_user) AND A.id_shop=".$_SESSION['shop']." AND A.name !='' ORDER BY A.name";
		$resCat_rooms21=mysqli_query($conn,$resCat12);
											  	while($rowHotelResult5 = mysqli_fetch_object($resCat_rooms21)){
													
													$AddCompanylist .= '<option  value="'.$rowHotelResult5->id_company.'">'.ucfirst($rowHotelResult5->name).'</option>';
												
											  }
											 	 $AddCompanylist .= '</select>';
												 
											 
	$AddCompanylist.'</td>'; 
	 
$AddCompanylist .= '<td colspan="13"><div id="Listbudgetvalue">';


	
	
echo $AddCompanylist .='</div></td></tr>';
?>

</table>
</div></div></div>
<?php
//USER TYPE CHECK===================>

$checkUserType = selectColumn(TBL_USERS,'user_type','WHERE id="'.$hotelId.'" '); 

$checkUserHotelAccess = selectColumn(TBL_USERS,'hotel_access','WHERE id="'.$hotelId.'" '); 

	if($checkUserType=='2'){
	$userTypeTable	= TBL_UNIT_AGENT_ACHIEVED;	
	$SqlConn		  = "and a.id_hotel = '".$_REQUEST['id_hotel']."' and FIND_IN_SET('".$_REQUEST['hotelId']."',C.ids_unit_user)";
	$SqlunitConn		  = "and ach.id_hotel = '".$_REQUEST['id_hotel']."' and ach.id_user = '".$_REQUEST['hotelId']."' ";
	$SqlConnUNit="and a.id_hotel = '".$_REQUEST['id_hotel']."'  ";
	//echo 'UNIT USER';
	}else{
			$userTypeTable	= TBL_AGENT_ACHIEVED;			
			//$SqlConn		  = "and C.`user_id`='".$_REQUEST['hotelId']."'";
			$SqlunitConn		  = "and ach.id_user = '".$_REQUEST['hotelId']."' ";
		//	$ConnTotalSql= "and a.id_user = '".$_REQUEST['hotelId']."' ";
			//echo 'RSO USER ';
		}
//USER TYPE CHECK===================>



$start_date	=	selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");		

$end_date	=selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");

if($_REQUEST['hotelId']!=''){
	
	  $editRowvalue = executeSql("SELECT * FROM `".$userTypeTable."` as a
			LEFT JOIN ".TBL_COMPANY." B ON a.id_company=B.id_company
			LEFT JOIN ".TBL_AREAS." C ON B.area=C.id
			where  a.`id_shop` = '".addslashes($_SESSION['shop'])."' and  a.`month`='".$start_date."' $SqlConn ");
			
	
}

//////////////////////////////getting rate data on edit//////////////////////////////////////////////////////

$CountNumber_row	=	num_rows($editRowvalue); 

if($_REQUEST['hotelId']!='' && $CountNumber_row > 0){

	 //EDIT
////////////////////////////show grid data////////////////////////////////////////////////////////

$rowRepeat ='<tr> 
		<th>Agent2</th>
		<th>Apr - '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>		
		<th>May- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Jun- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Jul- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>				  
		<th>Aug- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Sep- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Oct- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Nov- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Dec- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Jan- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Feb- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Mar- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Total</th>
		</tr>';?>

<div class="box box-success  table-responsive no-padding">

				  
		
<div class="box">
   <div class="box-body table-responsive">

              <table id="example2" class="table table-bordered table-striped">
                <thead>
               <?php  $availableData .= '<tr> 
		<th>Agent</th>
		<th>Apr - '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>		
		<th>May- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Jun- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Jul- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>				  
		<th>Aug- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Sep- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Oct- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Nov- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Dec- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Jan- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Feb- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Mar- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Total</th>
		</tr>
                </thead>
                <tbody>
		';
//jump

        $prysql = "SELECT  id  FROM `".TBL_AREAS."`  WHERE `id_shop` = '".addslashes($id_shop)."' 	 AND user_id = '".$_REQUEST['hotelId']."' ";
		$userPrimaryAreaArr	=	array();
		$rowpry	= mysqli_query($conn,$prysql);
		while($result	=	mysqli_fetch_object($rowpry)){
			 array_push($userPrimaryAreaArr,$result->id);
		}
		
		 $resCat_rooms2 ="select  
budget.name,
budget.id_company,
budget.ids_unit_user,
sum(budget.Apr) as Apr,sum(budget.May) as May,sum(budget.Jun) as Jun,
sum(budget.Jul) as Jul,sum(budget.Aug) as Aug,sum(budget.Sep) as 
'Sep',
sum(budget.Oct) as Oct,sum(budget.Nov) as Nov,sum(budget.Dec) as 'Dec',
sum(budget.Jan) as Jan,sum(budget.Feb) as Feb,sum(budget.Mar) as Mar,
sum(budget.Total) as Total
from 
(
select distinct
com.name, com.id_company, ar.ids_unit_user,
case when month(ach.month)=4 then ach.qty end as 'Apr',case when month(ach.month)=5 then ach.qty end as 'May',
case when month(ach.month)=6 then ach.qty end as 'Jun',case when month(ach.month)=7 then ach.qty end as 'Jul',
case when month(ach.month)=8 then ach.qty end as 'Aug',case when month(ach.month)=9 then ach.qty end as 'Sep',
case when month(ach.month)=10 then ach.qty end as 'Oct',case when month(ach.month)=11 then ach.qty end as 'Nov',
case when month(ach.month)=12 then ach.qty end as 'Dec',case when month(ach.month)=1 then ach.qty end as 'Jan',
case when month(ach.month)=2 then ach.qty end as 'Feb',case when month(ach.month)=3 then ach.qty end as 'Mar',
ach.qty as Total
from fs_areas_assign ar
inner join
fs_company com
on
com.area = ar.id
inner join
`".$userTypeTable."` ach
on
ach.id_company=com.id_company
where com.id_shop='".addslashes($_SESSION['shop'])."' $SqlunitConn and ach.seasonId='".$_REQUEST['seasonId']."'  and com.name<>'' and FIND_IN_SET('".$_REQUEST['hotelId']."',ar.ids_unit_user)
) as budget
group by budget.name,
budget.id_company,
budget.ids_unit_user
having sum(budget.Total)>0
order by budget.name";
echo $resCat_rooms2;

		$resCat_rooms1=mysqli_query($conn,$resCat_rooms2);	
		
				
		
		$gradTot=0;
		$totalId=1;
		$prevValue=1;
		$rowCount=0;
while($rowHotelResult = mysqli_fetch_object($resCat_rooms1)){
	//$availableData.='<tr><td><table>';
	//$availableData.=$rowRepeat;
	$Year	=	 date('Y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'")));
//	$DateValue	=date($Year.'-04-01');
			 
				 
	$availableData .= '<tr>';
    $editstart_date =	$editrow->start_date;
	$editend_date 	=	$editrow->end_date;
	$totalqtyHori=0;

	$availableData .= '<input type="hidden" id="data_id" name="data_id[]" value="'.$rowHotelResult->id_company.'" >';	
	//$availableData .= '<input type="hidden" id="bugetHotel" name="bugetHotel[]" value="'.$rowHotelResult->id.'" >';
	$availableData .= '<td style="width: 20%;">'.$rowHotelResult->name.'</td>'; 
			 if($checkUserType=='2'){
				 $OnKeyUpOne	   ='updateRowValueUnit(this.value,'.$hotelId.','.$rowHotelResult->id_company.','.$_REQUEST['seasonId'].','.strtotime(date($Year.'-04-01')).','.$_REQUEST['id_hotel'].')';
			 }else{
				 $OnKeyUpOne	   = 'updateRowValue(this.value,'.$hotelId.','.$rowHotelResult->id_company.','.$_REQUEST['seasonId'].','.strtotime(date($Year.'-04-01')).')';
				 }
	 
$availableData .= '<td>			
		  <input type="text" class="form-control  buget_qty" id="buget_qty|'.$DateValue.'|'.$hotelId.'|'.$rowHotelResult->id_company.'" name="buget_qty|'.$rowHotelResult->id_company.'[]" value="'.$rowHotelResult->Apr.'"  automcomplete="off" data-parsley-type="number" style="width:60px;" onchange="'.$OnKeyUpOne.'" >
		  </td>';
		  
		  	if($checkUserType=='2'){
				 $OnKeyUpOne	   ='updateRowValueUnit(this.value,'.$hotelId.','.$rowHotelResult->id_company.','.$_REQUEST['seasonId'].','.strtotime(date($Year.'-05-01')).','.$_REQUEST['id_hotel'].')';
			 }else{
				 $OnKeyUpOne	   = 'updateRowValue(this.value,'.$hotelId.','.$rowHotelResult->id_company.','.$_REQUEST['seasonId'].','.strtotime(date($Year.'-05-01')).')';
				 }

$availableData .= '<td>
		  <input type="text" class="form-control  buget_qty" id="buget_qty|'.$DateValue.'|'.$hotelId.'|'.$rowHotelResult->id_company.'" name="buget_qty|'.$rowHotelResult->id_company.'[]" value="'.$rowHotelResult->May.'"  automcomplete="off" data-parsley-type="number" style="width:60px;" onchange="'.$OnKeyUpOne.'" onKeyUp="">
		  </td>';
		  
			 if($checkUserType=='2'){
			 $OnKeyUpOne	   ='updateRowValueUnit(this.value,'.$hotelId.','.$rowHotelResult->id_company.','.$_REQUEST['seasonId'].','.strtotime(date($Year.'-06-01')).','.$_REQUEST['id_hotel'].')';
		 }else{
			 $OnKeyUpOne	   = 'updateRowValue(this.value,'.$hotelId.','.$rowHotelResult->id_company.','.$_REQUEST['seasonId'].','.strtotime(date($Year.'-06-01')).')';
			 }

$availableData .= '<td>
		  <input type="text" class="form-control  buget_qty" id="buget_qty|'.$DateValue.'|'.$hotelId.'|'.$rowHotelResult->id_company.'" name="buget_qty|'.$rowHotelResult->id_company.'[]" value="'.$rowHotelResult->Jun.'"  automcomplete="off" data-parsley-type="number" style="width:60px;" onchange="'.$OnKeyUpOne.'" onKeyUp="">
		  </td>';
		  
		 if($checkUserType=='2'){
		 $OnKeyUpOne	   ='updateRowValueUnit(this.value,'.$hotelId.','.$rowHotelResult->id_company.','.$_REQUEST['seasonId'].','.strtotime(date($Year.'-07-01')).','.$_REQUEST['id_hotel'].')';
	 }else{
		 $OnKeyUpOne	   = 'updateRowValue(this.value,'.$hotelId.','.$rowHotelResult->id_company.','.$_REQUEST['seasonId'].','.strtotime(date($Year.'-07-01')).')';
		 }

$availableData .= '<td>
		  <input type="text" class="form-control  buget_qty" id="buget_qty|'.$DateValue.'|'.$hotelId.'|'.$rowHotelResult->id_company.'" name="buget_qty|'.$rowHotelResult->id_company.'[]" value="'.$rowHotelResult->Jul.'"  automcomplete="off" data-parsley-type="number" style="width:60px;" onchange="'.$OnKeyUpOne.'" onKeyUp="">
		  </td>';


		 if($checkUserType=='2'){
			 $OnKeyUpOne	   ='updateRowValueUnit(this.value,'.$hotelId.','.$rowHotelResult->id_company.','.$_REQUEST['seasonId'].','.strtotime(date($Year.'-08-01')).','.$_REQUEST['id_hotel'].')';
		 }else{
			 $OnKeyUpOne	   = 'updateRowValue(this.value,'.$hotelId.','.$rowHotelResult->id_company.','.$_REQUEST['seasonId'].','.strtotime(date($Year.'-08-01')).')';
			 }
$availableData .= '<td>
		  <input type="text" class="form-control  buget_qty" id="buget_qty|'.$DateValue.'|'.$hotelId.'|'.$rowHotelResult->id_company.'" name="buget_qty|'.$rowHotelResult->id_company.'[]" value="'.$rowHotelResult->Aug.'"  automcomplete="off" data-parsley-type="number" style="width:60px;" onchange="'.$OnKeyUpOne.'" onKeyUp="">
		  </td>';

		if($checkUserType=='2'){
		$OnKeyUpOne	   ='updateRowValueUnit(this.value,'.$hotelId.','.$rowHotelResult->id_company.','.$_REQUEST['seasonId'].','.strtotime(date($Year.'-09-01')).','.$_REQUEST['id_hotel'].')';
		}else{
		$OnKeyUpOne	   = 'updateRowValue(this.value,'.$hotelId.','.$rowHotelResult->id_company.','.$_REQUEST['seasonId'].','.strtotime(date($Year.'-09-01')).')';
		}
		
		
$availableData .= '<td>
		  <input type="text" class="form-control  buget_qty" id="buget_qty|'.$DateValue.'|'.$hotelId.'|'.$rowHotelResult->id_company.'" name="buget_qty|'.$rowHotelResult->id_company.'[]" value="'.$rowHotelResult->Sep.'"  automcomplete="off" data-parsley-type="number" style="width:60px;" onchange="'.$OnKeyUpOne.'" onKeyUp="">
		  </td>';
		if($checkUserType=='2'){
		$OnKeyUpOne	   ='updateRowValueUnit(this.value,'.$hotelId.','.$rowHotelResult->id_company.','.$_REQUEST['seasonId'].','.strtotime(date($Year.'-10-01')).','.$_REQUEST['id_hotel'].')';
		}else{
		$OnKeyUpOne	   = 'updateRowValue(this.value,'.$hotelId.','.$rowHotelResult->id_company.','.$_REQUEST['seasonId'].','.strtotime(date($Year.'-10-01')).')';
		}
$availableData .= '<td>
		  <input type="text" class="form-control  buget_qty" id="buget_qty|'.$DateValue.'|'.$hotelId.'|'.$rowHotelResult->id_company.'" name="buget_qty|'.$rowHotelResult->id_company.'[]" value="'.$rowHotelResult->Oct.'"  automcomplete="off" data-parsley-type="number" style="width:60px;" onchange="'.$OnKeyUpOne.'" onKeyUp="">
		  </td>';

		if($checkUserType=='2'){
		$OnKeyUpOne	   ='updateRowValueUnit(this.value,'.$hotelId.','.$rowHotelResult->id_company.','.$_REQUEST['seasonId'].','.strtotime(date($Year.'-11-01')).','.$_REQUEST['id_hotel'].')';
		}else{
		$OnKeyUpOne	   = 'updateRowValue(this.value,'.$hotelId.','.$rowHotelResult->id_company.','.$_REQUEST['seasonId'].','.strtotime(date($Year.'-11-01')).')';
		}

$availableData .= '<td>
		  <input type="text" class="form-control  buget_qty" id="buget_qty|'.$DateValue.'|'.$hotelId.'|'.$rowHotelResult->id_company.'" name="buget_qty|'.$rowHotelResult->id_company.'[]" value="'.$rowHotelResult->Nov.'"  automcomplete="off" data-parsley-type="number" style="width:60px;" onchange="'.$OnKeyUpOne.'" onKeyUp="">
		  </td>';
		if($checkUserType=='2'){
		$OnKeyUpOne	   ='updateRowValueUnit(this.value,'.$hotelId.','.$rowHotelResult->id_company.','.$_REQUEST['seasonId'].','.strtotime(date($Year.'-12-01')).','.$_REQUEST['id_hotel'].')';
		}else{
		$OnKeyUpOne	   = 'updateRowValue(this.value,'.$hotelId.','.$rowHotelResult->id_company.','.$_REQUEST['seasonId'].','.strtotime(date($Year.'-12-01')).')';
		}
		
$availableData .= '<td>
		  <input type="text" class="form-control  buget_qty" id="buget_qty|'.$DateValue.'|'.$hotelId.'|'.$rowHotelResult->id_company.'" name="buget_qty|'.$rowHotelResult->id_company.'[]" value="'.$rowHotelResult->Dec.'"  automcomplete="off" data-parsley-type="number" style="width:60px;" onchange="'.$OnKeyUpOne.'" onKeyUp="">
		  </td>';
		$Year	=	 date('Y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'")));
		if($checkUserType=='2'){
		$OnKeyUpOne	   ='updateRowValueUnit(this.value,'.$hotelId.','.$rowHotelResult->id_company.','.$_REQUEST['seasonId'].','.strtotime(date($Year.'-01-01')).','.$_REQUEST['id_hotel'].')';
		}else{
		$OnKeyUpOne	   = 'updateRowValue(this.value,'.$hotelId.','.$rowHotelResult->id_company.','.$_REQUEST['seasonId'].','.strtotime(date($Year.'-01-01')).')';
		}
$availableData .= '<td>
		  <input type="text" class="form-control  buget_qty" id="buget_qty|'.$DateValue.'|'.$hotelId.'|'.$rowHotelResult->id_company.'" name="buget_qty|'.$rowHotelResult->id_company.'[]" value="'.$rowHotelResult->Jan.'"  automcomplete="off" data-parsley-type="number" style="width:60px;" onchange="'.$OnKeyUpOne.'" onKeyUp="">
		  </td>';
		if($checkUserType=='2'){
		$OnKeyUpOne	   ='updateRowValueUnit(this.value,'.$hotelId.','.$rowHotelResult->id_company.','.$_REQUEST['seasonId'].','.strtotime(date($Year.'-02-01')).','.$_REQUEST['id_hotel'].')';
		}else{
		$OnKeyUpOne	   = 'updateRowValue(this.value,'.$hotelId.','.$rowHotelResult->id_company.','.$_REQUEST['seasonId'].','.strtotime(date($Year.'-02-01')).')';
		}
$availableData .= '<td>
		  <input type="text" class="form-control  buget_qty" id="buget_qty|'.$DateValue.'|'.$hotelId.'|'.$rowHotelResult->id_company.'" name="buget_qty|'.$rowHotelResult->id_company.'[]" value="'.$rowHotelResult->Feb.'"  automcomplete="off" data-parsley-type="number" style="width:60px;" onchange="'.$OnKeyUpOne.'" onKeyUp="">
		  </td>';
		if($checkUserType=='2'){
		$OnKeyUpOne	   ='updateRowValueUnit(this.value,'.$hotelId.','.$rowHotelResult->id_company.','.$_REQUEST['seasonId'].','.strtotime(date($Year.'-03-01')).','.$_REQUEST['id_hotel'].')';
		}else{
		$OnKeyUpOne	   = 'updateRowValue(this.value,'.$hotelId.','.$rowHotelResult->id_company.','.$_REQUEST['seasonId'].','.strtotime(date($Year.'-03-01')).')';
		}
$availableData .= '<td>
		  <input type="text" class="form-control  buget_qty" id="buget_qty|'.$DateValue.'|'.$hotelId.'|'.$rowHotelResult->id_company.'" name="buget_qty|'.$rowHotelResult->id_company.'[]" value="'.$rowHotelResult->Mar.'"  automcomplete="off" data-parsley-type="number" style="width:60px;" onchange="'.$OnKeyUpOne.'" onKeyUp="">
		  </td>';
		
$availableData .= '<td>
		 
<input id="totalCol_'.$rowHotelResult->id_company.'" class="form-control" type="text"  disabled="disabled" value="'.$rowHotelResult->Total.'" style="width:60px;">	
 </td>';
	
	//$gradTot +=$rowHotelResult->Total;
$availableData .='</tr>';
$RecordType='Edit';
}// end while
	
// -------------------Bottom Total END----------------------------------------------------
}else{ //EDIT  NO DATA IN DATABASE
	
////////////////////////////show grid data////////////////////////////////////////////////////////
?>
<div class="box box-success  table-responsive no-padding">			  
		
<div class="box">
   <div class="box-body table-responsive">

              <table id="example2" class="table table-bordered table-striped">
                <thead>
               
                
               <?php  $availableData .= '<tr> 
		<th>Agent:</th>
		<th>Apr - '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>		
		<th>May- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Jun- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Jul- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>				  
		<th>Aug- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Sep- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Oct- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Nov- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Dec- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Jan- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Feb- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Mar- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Total</th>
		</tr>
                </thead>
                <tbody>
				<th>No Agent</th>
		<th>-</th>
		<th>-</th>
		<th>-</th>
		<th>-</th>
		<th>-</th>
		<th>-</th>
		<th>-</th>
		<th>-</th>
		<th>-</th>
		<th>-</th>
		<th>-</th>
		<th>-</th>
		<th>-</th>
		';
/*
 $resCat_rooms2 ="SELECT A.name,A.id_company FROM `".TBL_COMPANY."` AS A 
 				  LEFT JOIN `".TBL_AREAS."` AS B ON A.area=B.id
 				  WHERE  FIND_IN_SET('".$_REQUEST['hotelId']."',B.ids_unit_user) AND A.id_shop=".$_SESSION['shop']." AND A.name !='' ORDER BY A.name";
 	$resCat_rooms1=mysqli_query($conn,$resCat_rooms2);	
 	$totalId=1;
 	$prevValue=1;		  
	while($rowHotelResult = mysqli_fetch_object($resCat_rooms1)){
		 
		//$availableData .=$rowRepeat;
		$availableData .= '<tr id="rateMaster|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'">';
	    $editstart_date 		=	$editrow->start_date;
		$editend_date 			=	$editrow->end_date;

$availableData .= '<input type="hidden" id="data_id" name="data_id[]" value="'.$rowHotelResult->id_company.'" >';	



//$availableData .= '<input type="hidden" id="bugetHotel" name="bugetHotel[]" value="'.$rowHotelResult->id_company.'" >';
$availableData .= '<td>'.$rowHotelResult->name.'</td>'; 
$start_date	=	selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");		
$end_date	=selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");								 

$start = $month = strtotime($start_date);

$end = strtotime($end_date);
$id_achieved	=0;
$totalIdVer=1;
while($month < $end){
     $DateValue	=	date('Y-m-d', $month);
     $month = strtotime("+1 month", $month);
	 if($checkUserType=='2'){
				 $OnKeyUpThree	   ='updateRowValueUnit(this.value,'.$id_achieved.','.$hotelId.','.$rowHotelResult->id_company.','.$_REQUEST['seasonId'].','.strtotime($DateValue).','.$_REQUEST['id_hotel'].')';
			 }else{
			 	$OnKeyUpThree	   = 'updateRowValue(this.value,'.$id_achieved.','.$hotelId.','.$rowHotelResult->id_company.','.$_REQUEST['seasonId'].','.strtotime($DateValue).')';
				 }
     //$availableData .= '<input type="hidden" id="MonthDate" name="MonthDate|'.$rowHotelResult->id_company.'[]" value="'.$DateValue.'" >';
	 $availableData .= '<td>
	 				<input type="hidden" disabled="disabled" id="hiddenBox'.$prevValue.'" value="0">

				  <input type="text" class="form-control  buget_qty" id="buget_qty|'.$DateValue.'|'.$hotelId.'|'.$rowHotelResult->id_company.'" name="buget_qty|'.$rowHotelResult->id_company.'[]" value="0"  automcomplete="off" data-parsley-type="number" style="width:60px;" onchange="'.$OnKeyUpThree.'" onKeyUp="">

				  </td>';
				  $totalIdVer++;
				  $prevValue++;
}

$availableData .="<td>
		 					<input id='totalRow".$totalId."' class='form-control' type='text'  disabled='disabled' value='0' style='width:60px;'>	
		 				   </td>";
$totalId++;
$availableData .='</tr>';

	$RecordType	='ADD';
	}*/
	//ADD

	
	}

				 


	$availableData .='</tbody>';
	
    $availableData.='<tr>';
	if($_REQUEST['hotelId']!='' && $CountNumber_row > 0){
	 $fromVer = date('Y-m-01',strtotime($start_date));
	$tillVer = date('Y-m-01',strtotime($end_date));
	$monthQtyVer = '';
	$totalIdVer=1;
	while($fromVer <= $tillVer){
		echo  '<br><br><br>'.$sqlTotalVer = "SELECT sum(qty)AS qty FROM `".$userTypeTable."` as a
			LEFT JOIN ".TBL_COMPANY." B ON a.id_company=B.id_company
			LEFT JOIN ".TBL_AREAS." C ON B.area=C.id
		  where  a.`id_shop` = '".addslashes($_SESSION['shop'])."' and FIND_IN_SET('".$_REQUEST['hotelId']."',C.ids_unit_user) and FIND_IN_SET('".$_REQUEST['hotelId']."',a.id_user) $ConnTotalSql and a.`seasonId`='".$_REQUEST['seasonId']."' and month='".date('Y-m-d',strtotime($fromVer))."'  $SqlConn ";

   /*  $sqlTotalVer = "SELECT sum(qty)AS qty FROM `".$userTypeTable."` as a
		
		  where  a.`id_shop` = '".addslashes($_SESSION['shop'])."'  $ConnTotalSql and a.`seasonId`='".$_REQUEST['seasonId']."' and a.month='".date('Y-m-d',strtotime($fromVer))."'  $SqlConnUNit ";
*/


		$resVer= mysqli_query($connNew,$sqlTotalVer);
		$objTot = mysqli_fetch_object($resVer);
$fromVer1	=$fromVer;
		$fromVer = date('Y-m-d',strtotime('+1 months',strtotime($fromVer)));
		
		$gradTot +=$objTot->qty;
		$monthQtyVer.="<td>--==>".$objTot->qty."
		 				<input id='totalCol".strtotime(date($fromVer1))."' class='form-control' type='text'  disabled='disabled' value='".$objTot->qty."' style='width:60px;'>	
		 				</td>";
		$totalIdVer++;
	}
		}else{
		$monthQtyVer ='
		
		<td><input id="syb" class="form-control" type="text"  disabled="disabled" value="0" style="width:60px;"></td>
		<td><input id="syb" class="form-control" type="text"  disabled="disabled" value="0" style="width:60px;"></td>
		<td><input id="syb" class="form-control" type="text"  disabled="disabled" value="0" style="width:60px;"></td>
		<td><input id="syb" class="form-control" type="text"  disabled="disabled" value="0" style="width:60px;"></td>
		<td><input id="syb" class="form-control" type="text"  disabled="disabled" value="0" style="width:60px;"></td>
		<td><input id="syb" class="form-control" type="text"  disabled="disabled" value="0" style="width:60px;"></td>
		<td><input id="syb" class="form-control" type="text"  disabled="disabled" value="0" style="width:60px;"></td>
		<td><input id="syb" class="form-control" type="text"  disabled="disabled" value="0" style="width:60px;"></td>
		<td><input id="syb" class="form-control" type="text"  disabled="disabled" value="0" style="width:60px;"></td>
		<td><input id="syb" class="form-control" type="text"  disabled="disabled" value="0" style="width:60px;"></td>
		<td><input id="syb" class="form-control" type="text"  disabled="disabled" value="0" style="width:60px;"></td>
		<td><input id="syb" class="form-control" type="text"  disabled="disabled" value="0" style="width:60px;"></td>';
		}

$availableData .='
					<td style="font-weight:bold;width: 20%;">Total:</td>
					----'.$monthQtyVer.'
					<td>
		 				<input id="grandTotal" class="form-control" type="text"  disabled="disabled" value="'.$gradTot.'" style="width:60px;">	
		 			</td>
					
				   <tr>';
			  
			  
            $availableData .=' </table></div>
           
          </div>';										 

$availableData .='<div class="box-footer " style="border-top: 0px solid #f4f4f4;">

             

              

              &nbsp;&nbsp;&nbsp;&nbsp;

              <input type="button" value="Cancel" class="btn btn-default" onclick="location.replace(manageAgentAchieved.php)">
              <span style="color:red;" id="loaderAni"></span>
            </div>';
//}
$availableData .= '  
            </div>';
echo $availableData;

?>            
 <script type="text/javascript">

function Creditallow(id_company,rate_id){

 var form1=$("#availabiltyForm");	

 var dataString = $("#availabiltyForm").serialize();	

	if(form1.parsley().validate()){

		$.ajax({

		   type: "POST",

		   url: 'ajax/ajaxCreditallow.php',

		   data: dataString+'&id_company='+id_company+'&rate_id='+rate_id, 

		   success: function (result) {					

				$( "#Creditallow_value" ).html(result);								

			}

		})

	}

}





//////////////////////check availabilty -book-now.php///////////////////////////////////////////////// 



function ajaxCheckAvailability() {

          //alert('test');

  		  var form=$("#availabiltyForm");		  

		  form.parsley().validate();		  

  		  $('.loading').show(); 

		  $.ajax({

			   type: "POST",

			   url: 'ajax/ajaxcheckAvailability.php',

			   data: form.serialize(), 

			   success: function (result) {

					$('#availabilty').html(result)

				},

			  complete: function(){

				$('.loading').hide();

			  }

		})

	return false;

 }

/////////////////////////////////show events on date -book-now.php/////////////////////////////////////////////

function getEvents(dated){

//$('#eventsPopup').popup('show');

 $('#eventsPopup').popup({

            //pagecontainer: '.container',

        	transition: 'all 0.3s',

            autoopen: true,            

        });

}







/////////////////////////////////show plan Details on date -book-now.php/////////////////////////////////////////////





$("#view").click(function (){

 var form1=$("#availabiltyForm");	

 var form2=$("#addRoomForm");

 var dataString = $("#availabiltyForm, #addRoomForm").serialize();	

	if(form1.parsley().validate() && form2.parsley().validate()){

		$.ajax({

		   type: "POST",

		   url: 'ajax/ajaxGetPlanDetails.php',

		   data: dataString, 

		   success: function (result) {					

				$( "#ajaxPlanData" ).html(result);

				$('#planDetail').popup({

        			 transition: 'all 0.3s',

           			 autoopen: true,            

        		});

				 //$("#hotelId").val('1').attr('selected','selected');					

			}

		})

	}

})


</script>               

