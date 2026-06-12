<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_INVENTORY,'view');
//if($_REQUEST['eId'] == ''){ header("location:editHotels.php"); }
?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>
<?php 
$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
$perSql="SELECT * FROM `fs_user_levels` WHERE id=".$_SESSION['userLevel']." AND id_shop=".$_SESSION['shop']." ";
$resPer = mysqli_query($conn,$perSql);

if($resPer){
  	$perData	=	mysqli_fetch_object($resPer);
if($perData->calendar_user_list_approved == 0){
	$UserRestriction	=" AND id='".$_SESSION['userId']."'";	
}
}

?>
 <style>
.tooltiptext {
	-webkit-box-shadow: -10px 0px 13px -7px #000000, 10px 0px 13px -7px #000000, 0px 5px 15px 5px rgba(0,0,0,0);
	box-shadow: -10px 0px 13px -7px #000000, 10px 0px 13px -7px #000000, 0px 5px 15px 5px rgba(0,0,0,0);
}
 @media only screen and (min-device-width :320px) and (max-device-width :480px) {
.tooltiptext {
	width: 45%
}
}
@media only screen and (min-width :321px) {
.tooltiptext {
	width: 45%
}
.popover-content1 {
	padding: 9px 14px;
	background-color: #fff;
	color: #000;
}
.popover-footer {
	margin: 0;
	padding: 8px 14px;
	font-size: 14px;
	font-weight: 400;
	line-height: 18px;
	background-color: #3c8dbc;
	border-top: 1px solid #3c8dbc;
	color: #FFF;
	font-weight: bold;
}
}
 @media only screen and (max-width :320px) {
.tooltiptext {
	width: 45%
}
.popover-content1 {
	padding: 9px 14px;
	background-color: #fff;
	color: #000;
}
.popover-footer {
	margin: 0;
	padding: 8px 14px;
	font-size: 14px;
	font-weight: 400;
	line-height: 18px;
	background-color: #3c8dbc;
	border-top: 1px solid #3c8dbc;
	color: #FFF;
	font-weight: bold;
}
}
 @media only screen and (min-width : 1224px) {
.tooltiptext {
	width: 30%
}
.popover-content1 {
	padding: 9px 14px;
	background-color: #fff;
	color: #000;
}
.popover-footer {
	margin: 0;
	padding: 8px 14px;
	font-size: 14px;
	font-weight: 400;
	line-height: 18px;
	background-color: #3c8dbc;
	border-top: 1px solid #3c8dbc;
	color: #FFF;
	font-weight: bold;
}
}
.popover-content1 {
	padding: 9px 14px;
	background-color: #fff;
	color: #000;
}
.popover-footer {
	margin: 0;
	padding: 8px 14px;
	font-size: 14px;
	font-weight: 400;
	line-height: 18px;
	background-color: #3c8dbc;
	border-top: 1px solid #3c8dbc;
	color: #FFF;
	font-weight: bold;
}

.tooltip:hover .tooltiptext {
  visibility: visible;
  opacity: 1;
}
</style>
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> Daily Reports Manager <small>Daily Reports</small> </h1>
        <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Daily Reports</li>
      </ol>
      </section>
    
    <!-- Main content -->
    <section class="content">
        <div class="row"> 
        <!-- /.col -->
        
        <div class="col-md-12">
            <div class="nav-tabs-custom"> </div>
            <div class="box box-primary">
            <div class="box-header with-border">
            <div class="form-group">
                <label>User Name</label>
                <!--<input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />-->
               <?php 
			  
			   
			   $categoryDropDown = '<select class="form-control select2" name="Admin_user_id" id="Admin_user_id" onChange="callingLoader();">
											  								<option value="">All user Name</option>';
											  $resUserLevel = selectSql(TBL_USERS," WHERE `status` = '1' AND  `id_shop` = '".addslashes($_SESSION['shop'])."'  $UserRestriction ",' ORDER BY `name`');
											  if($db->num_rows2($resUserLevel)){
											  	while($resultUserLevel = $db->fetch_object2($resUserLevel)){
													if($_SESSION['userId'] == $resultUserLevel->id){
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
                <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> Daily Reports : <a><?php echo selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'"); ?></a></h3>

                <div class="btn-group  pull-right"> <a type="button" class="btn btn-success " href="#" ><i class="fa fa-tasks"></i> &nbsp;Add </a>
                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>
                <ul class="dropdown-menu" role="menu" style="background-color:#00a65a;">
                    <li><a title="Add Visite" class="btn btn-success" href="addreport.php" style="color:#fff;font-weight:bold;">&nbsp;Visit</a></li>
                    <li><a href="editEnquiry.php" class="btn btn-success"  style="color:#fff;font-weight:bold;">
                       &nbsp; Enquiry
                      </a></li>
                      <li><a title="Add Quote" class="btn btn-success" href="BookingQuote.php" style="color:#fff;font-weight:bold;">&nbsp;Quote</a></li>
                  </ul>
              </div>
             
             <!-- Approval List -->
              <div id="approvalNotify" style="color:green;font-size:1.5rem;" class="text-center">
                <span id="approvalNotifyTxt" ></span>
                <div id="tableData" style="margin:0px auto;">
                  
                </div>
              </div>

              
              

            <div class="box-body no-padding">
                <div class="form-group has-error">
                <?php if($_SESSION['errorMsg']){?>
                <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
                <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
                <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
                <?php unset($_SESSION['successMsg']);}?>
              </div>
                <!-- THE CALENDAR -->
                
                <div id="calendar"> </div>
                <div id="shafeer123"></div>
              </div>
            <!-- /.box-body --> 
          </div>
            <!-- /. box --> 
          </div>
        <!-- /.col --> 
      </div>
        <!-- /.row --> 
      </section>
    <!-- /.content --> 
  </div>
  <!-- /.content-wrapper -->

<div id="OpenListPopUpshow" class="well" style="display:none;"></div>
  
  

 <div id="FeedBack" class="well">
    <form id="FeedBackpopupForm" data-parsley-validate autocomplete="off">
        <div class="form-group">
        <label for="room_name">FeedBack</label>
      </div>
        <button class="FeedBack_close btn btn-default">Close</button>
      </form>
  </div>

<span class="my_popup_open" style="display:none;"></span>
<div id="my_popup" class="well">
    <div id="FollowUpNextUpdate"></div>
    <button class="my_popup_close btn btn-default pull-right">Close</button>
  </div>
<div id="EnquiryPopup" class="well">
    <form id="bookedbypopupform" data-parsley-validate autocomplete="off" method="post"  >
       <div class="form-group">
        <label class="title">Title</label>
        <select name="title"  class="form-control input-sm" data-parsley-required >
           <option value="">-Select-</option>
           <option value="Dr.">Dr.</option>
           <option value="Miss.">Miss.</option>
           <option value="Mr.">Mr.</option>
           <option value="Mrs.">Mrs.</option>
           <option value="Ms.">Ms.</option>
           <option value="Pr.">Pr.</option>
           <option value="Prof.">Prof.</option>
           <option value="Rev.">Rev.</option>
         </select>
      </div>
       <div class="form-group">
        <label for="first_name">First Name </label>
        <input type="text" class="form-control input-sm" placeholder="Enter first name" id="first_name" name="first_name" value="" data-parsley-required>
      </div>
       <div class="form-group">
        <label for="last_name">Last Name</label>
        <input type="text" class="form-control input-sm" placeholder="Enter last name" id="last_name" name="last_name" value="">
      </div>
       
       <div class="form-group">
        <label for="email" >Email Id</label>
        <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email Id" data-parsley-type="email" automcomplete="off">
      </div>
       <div class="form-group">
        <label for="mobile" >Mobile No.</label>
        <input type="phone" name="mobile" id="mobile" class="form-control" placeholder="Enter mobile number"  data-parsley-type="digits" data-parsley-length="[10, 10]" automcomplete="off">
      </div>
      <div class="form-group">
        <label for="email" >Details</label>
        <textarea class="form-control" name="discussion_summary" id="discussion_summary"    rows="2" placeholder="Enter Discussion Summary" automcomplete="off"><?php if($_POST) echo $_POST['discussion_summary'];else echo stripslashes($row->discussion_summary);?>
</textarea>
      </div>
       <input  type="button" class="btn btn-default" onClick="saveBookedbyPopupform();" value="Save">
       <button class="EnquiryPopup_close btn btn-default">Close</button>
     </form>
  </div>
<?php include_once("includes/footer.php")?>

<?php
  $flag = 0;
  $flag = selectColumn(TBL_USER_LEVELS,'conveyance_approved','where id="'.$_SESSION['userLevel'].'" ');
  
?>

<script type="text/javascript">
$('document').ready(function(){
  var flag = '<?php echo $flag ;?>';
  if(flag==1){
    $.ajax({
       type: "POST",
       url: 'ajax/ajaxPendingApprovalList.php',
       data: 'flag='+flag, 
       success: function (result) { 
        console.log(result);
       if(result !=""){
        $('#approvalNotifyTxt').html('Check Pending Approvals !');
       }else{
        $('#approvalNotifyTxt').html('');
       }    
      }
    });
    $('#approvalNotifyTxt').hover(
      function(){
        $('#fillData').show();
      },
      function(){
          $('#fillData').hide();
      }
    );   

  }
});
</script>

<script>


var linkId = $("#Admin_user_id").val();
function callingLoader(linkId){
	var linkId = $("#Admin_user_id").val();
	
 var eventSources  = 
 		{
        url: "ajax/ajaxDailyReport.php?Admin_user_id="+linkId,
        type: 'POST',       
    }

  	$('#calendar').fullCalendar('removeEvents');
  	$('#calendar').fullCalendar('removeEventSource', eventSources);
    $('#calendar').fullCalendar('addEventSource', eventSources);
	
		
   
}
	
	
	calenderLoader(linkId);

function calenderLoader(linkId){
 $(function () {


    /* initialize the external events
     -----------------------------------------------------------------*/
    function init_events(ele) {
      ele.each(function () {

        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
          title: $.trim($(this).text()) // use the element's text as the event title
        }
//eventRender: function(eventObject, $ele) {
      
    
        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject)

        // make the event draggable using jQuery UI
        $(this).draggable({
          zIndex        : 1070,
          revert        : true, // will cause the event to go back to its
          revertDuration: 0  //  original position after the drag
        })

      })
    }

    init_events($('#external-events div.external-event'));
	


    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date()
    var d    = date.getDate(),
        m    = date.getMonth(),
        y    = date.getFullYear()
	
		
    $('#calendar').fullCalendar({
      header    : {
        left  : 'prev,next today',
        center: 'title',
		    right: '',
       
      },
	  
	  navLinks: true, // can click day/week names to navigate views
      editable: true,
      eventLimit: true, // allow "more" link when too many events
		  
      buttonText: {
        today: 'today',
        month: 'month',
		
      },
	  
   
	 eventSources: [
        // your event source
		
        {
			
            url: 'ajax/ajaxDailyReport.php',
            type: 'POST',
			data: {
                'Admin_user_id' :  $("#Admin_user_id").val(),
            },			
            error: function() {
                alert('there was an error while fetching events!');
            }
        }
        // any other sources...
    ],
/*eventRender: function(event, element, view) {

			  element.find('.fc-title').append("<br/>" + event.description);   
 },
*/

/*eventRender: function(event, element, view) {
      element.popover({
        title: event.title,
        content: event.description,
        trigger: 'hover',
        placement: 'top',
        container: 'body'
      });
    },*/
	
	
eventMouseover: function(calEvent, jsEvent) {	
  
  
  var EventTypeId	= calEvent.id
 // if(EventTypeId	==1){		
		
  var ResultTitle = calEvent.title.split('|');
	    var tooltip = '<div class="tooltiptext"  id="shafeer" style="right:9999px;margin-top:2px;border-radius:6px; border:1px solid;color:#3c8dbc; background:#3c8dbc;position:absolute;z-index:1;" ><div class="popover-footer">'+ ResultTitle[0] +'</div><div class="popover-content1">' + calEvent.description +'</div><div class="popover-footer"><a title="Add Visite" href="addreport.php" style="color:#fff;font-weight:bold;">Add Visit</a> &nbsp;  | <a title="Add Enquiry" href="editEnquiry.php" style="color:#fff;font-weight:bold;"> Enquiry </a> | <a title="Add Quote" href="BookingQuote.php" style="color:#fff;font-weight:bold;">&nbsp;Quote</a> <a title="Download" href="#" style="color:#fff;font-weight:bold; float:right;">&nbsp;<i class="fa fa-cloud-download"></i></a> </div></div>';
    var $tooltip = $(tooltip).appendTo('body');

    $(this).mouseover(function(e) {
		
	var calEvent = this;
	var curClass = calEvent.tooltiptext // current class clicked.
    var windowHeight = $(window).height();
    var windowWidth = $(window).width();
	
	
	var left 	= calEvent.offsetLeft;
    var top 	= calEvent.offsetTop;
    var linkHeight 	= $(".tooltiptext").height();
    var linkWidth 	= $(".tooltiptext").width();
    var bottom = windowHeight - top - linkHeight;
    var right = windowWidth - left - linkWidth;
    var topbottom = (top < bottom) ? bottom : top;
    var leftright = (left < right) ? right : left;

    var tooltiph = $(".tooltiptext").height();
    var tooltipw = $(".tooltiptext").width();
	//alert(bottom);
	
       
    if (topbottom == bottom && leftright == right) //done
    {
		$(this).css('z-index', 9999);
        $tooltip.fadeIn('1');
        //$tooltip.fadeTo('10', 1.9);
		$tooltip.css('top', e.pageY -10+ "px");
        $tooltip.css('left', e.pageX -10+ "px");
		$tooltip.css('position',absolute);
	
    } else if (topbottom == bottom && leftright == left) //done
    {
		//alert("2=="+top+'=='+tooltiph+'--'+linkHeight);
       // var yPos = top;
        //var xPos = right + linkWidth + 10;
		//var xPos = left + linkWidth + 10;
        //var yPos = top - tooltiph - (linkHeight / 2);
		 //var yPos = top - tooltiph - (linkHeight / 2);
       //$("#" + curClass).css("top", yPos + "px");
        //$("#" + curClass).css("right", xPos + "px");
		//alert(yPos);
		$(this).css('z-index', 9999);
        $tooltip.fadeIn('1');
        //$tooltip.fadeTo('10', 1.9);
		$tooltip.css('top', e.pageY -280+ "px");
        $tooltip.css('left', e.pageX -280+ "px");
		$tooltip.css('position',absolute);
		
    } else if (topbottom == top && leftright == right) //done
    {
				
		///*alert("3=="+top);
       
    } else if (topbottom == top && leftright == left) {
		
				//
        var yPos = top - tooltiph - (linkHeight / 2);
        var xPos = left - tooltipw - linkWidth;
       // $("#" + curClass).css("top", yPos + "px");
       // $("#" + curClass).css("left", xPos + "px");
		
		
		
		$(this).css('z-index', 9999);
        $tooltip.fadeIn('1');
        //$tooltip.fadeTo('10', 1.9);
		$tooltip.css('top', yPos + "px");
        $tooltip.css('left', yPos + "px");
		$tooltip.css('position',absolute);
		
		
		
    } else {
		
		alert("5");
		$(this).css('z-index', 9999);
        $tooltip.fadeIn('1');
        //$tooltip.fadeTo('10', 1.9);
		$tooltip.css('top', e.pageY -10+ "px");
        $tooltip.css('left', e.pageX -10+ "px");
		$tooltip.css('position',absolute);
	}
		
	
	

    //$(".tooltip").fadeIn('fast');
	
	
	
	
        
		
   
         
    })
	$('.tooltiptext').mouseleave(function(e){
	   $(this).fadeOut();
	})
		
  //}
},


eventClick: function(calEvent, jsEvent) {
		   
	if(calEvent.id	==1){
		var reservation_date = calEvent.start.format('YYYY-MM-DD');		
		 $.ajax({
		   type: "POST",
		   url: 'ajax/ajaxDateFollowupList.php',
		   data: 'reservation_date='+reservation_date, 
		   success: function (result) {			   
					//$('#shafeer123').val(result);	
					$('#shafeer123').html(result);
					//$('#shafeer123').html(result);	
			}
		})
	}
	if(calEvent.id	==2){
		var reservation_date = calEvent.start.format('YYYY-MM-DD');		
		 $.ajax({
		   type: "POST",
		   url: 'ajax/ajaxDateVisitList.php',
		   data: 'reservation_date='+reservation_date, 
		   success: function (result) {			   
					//$('#shafeer123').val(result);	
				$('#shafeer123').html(result);
					//$('#shafeer123').html(result);	
			}
		})


	}
	if(calEvent.id	==3){
	 $('#FeedBack').popup('show');
	 
	 

    
	}
	$("#CompanyName").val(calEvent.CompanyName);
	 var roomId = calEvent.room_id;
	 $("#room_id").val(roomId);
	 
	 $("#room_available").val(calEvent.availableInventory);
	 $("#blocked_hotel").val(calEvent.blocked_hotel);
	 $("#crs_available").val(calEvent.crs_available);
	 $("#online_allocation").val(calEvent.online_allocation);
	 $("#allocation_date").val(calEvent.allocation_date);
       /* if (title!='') {
            calEvent.title = title;
            $('#calendar').fullCalendar('updateEvent', calEvent);
        }*/
    },
	 selectHelper: true,
			select: function(start, end, allDay) {
				

				 $("#start_date").val(moment(start).format('YYYY-MM-DD'));
				 $("#end_date").val(moment(end).format('YYYY-MM-DD'));
				 
				 //console.log(start);
				 //$('#calendar').fullCalendar('unselect');
			},
	  selectable: true,
      editable  : true,
      droppable : true, // this allows things to be dropped onto the calendar !!!
      drop      : function (date, allDay) { // this function is called when something is dropped
	  
        // retrieve the dropped element's stored Event Object
        var originalEventObject = $(this).data('eventObject')
        // we need to copy it, so that multiple events don't have a reference to the same object
        var copiedEventObject = $.extend({}, originalEventObject)
        // assign it the date that was reported
        copiedEventObject.start           = date
        copiedEventObject.allDay          = allDay
        copiedEventObject.backgroundColor = $(this).css('background-color')
        copiedEventObject.borderColor     = $(this).css('border-color')
		
        // render the event on the calendar
        // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
        $('#calendar').fullCalendar('renderEvent', copiedEventObject, true)
        // is the "remove after drop" checkbox checked?
        if ($('#drop-remove').is(':checked')) {
	 // if so, remove the element from the "Draggable Events" list
          $(this).remove()
        }
      }
    })

    /* ADDING EVENTS */
    var currColor = '#3c8dbc' //Red by default
    //Color chooser button
    var colorChooser = $('#color-chooser-btn')
    $('#color-chooser > li > a').click(function (e) {
      e.preventDefault()
      //Save color
      currColor = $(this).css('color')
      //Add color effect to button
      $('#add-new-event').css({ 'background-color': currColor, 'border-color': currColor })
    })
    $('#add-new-event').click(function (e) {
      e.preventDefault()
      //Get value and make sure it is not null
      var val = $('#new-event').val()
      if (val.length == 0) {
        return
      }
      //Create events
      var event = $('<div />')
      event.css({
        'background-color': currColor,
        'border-color'    : currColor,
        'color'           : '#fff'
      }).addClass('external-event')
      event.html(val)
      $('#external-events').prepend(event)
      //Add draggable funtionality
      init_events(event)
      //Remove event from text input
      $('#new-event').val('')
    })
  })
 }
</script>

<script>


 function ajaxAddothercharges(rate_id,rate_assign_id,room_id,rate_plan_id,type){
	var reservation_date = $("#reservation_date").val();
	var rate_id = $("#rate_id").val();
	var hotel_id = $("#hotel_id").val();
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxAddFollowUp.php',
		   data: 'reservation_date='+reservation_date+'&hotel_id='+hotel_id+'&rate_id='+rate_id+'&rate_assign_id='+rate_assign_id+'&room_id='+room_id+'&rate_plan_id='+rate_plan_id+'&type='+type, 
		   success: function (result) {					
				resultArray = result.split('|||');					
					$('#showOtherCharges').append(resultArray['1']);
					$('#pricingValue').html(resultArray['2']);
					$('#addRoommsg').css('display', 'none');
					$('#flatDiscount').val();
					$('#percentDiscount').val();
					$('#flatAdditionalCharges').val();
					$('#percentAdditionalCharges').val();				
			}
		})

}


function ajaxOtherChargesRemove(uniqueCode){					
				 $('#'+uniqueCode).remove();
}
function OpenListPopup(followup_status,followup_id,daily_Visit_id,hotel_id){
	
	$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxListPopUpshow.php',
		    data: 'followup_id='+followup_id+'&daily_Visit_id='+daily_Visit_id+'&hotel_id='+hotel_id, 
		   success: function (result) {			  				
				$('#OpenListPopUpshow').html(result);		
				$('#OpenListPopUpshow').popup('show');				
			}
		})
	}

function OpenPopup(followup_status,followup_id,daily_Visit_id,hotel_id,followup_type){

		if(followup_status	== '0'){		
			alert('Your Follow Up already Closed');
			exit;
		}else{	
		
		$('#followup_id').val(followup_id);
		$('#daily_Visit_id').val(daily_Visit_id);
		$('#hotel_id').val(hotel_id);
		$('#followup_status').val(followup_status);
		$('#followup_type').val(followup_type);
		
		$('#followup_id_hidden').val(followup_id);
		$('#daily_Visit_id_hidden').val(daily_Visit_id);
		$('#hotel_id_hidden').val(hotel_id);
		$('#followup_status_hidden').val(followup_status);
		$('#followup_hidden_type').val(followup_type);

			$('#ColseSummaryPopUp').popup('show');
		}

}

$(document).on('click','.status_checks',function(){
	
	  var current_element = $(this); 
	  var status = ($(this).hasClass("btn-success")) ? '0' : '1';
	  
	  if(status	== '1'){
		  
		  alert('Your Follow Up already Closed');
		  
	 }else{
		 
	  $('#status').val(status);
	  var fs_daily_visit_followup_new	=	$(current_element).attr('data');
	  
	  $('#fs_daily_visit_followup_new').val(fs_daily_visit_followup_new);
	  
	 
	 
		$('#ColseSummaryPopUp').popup('show');
	  }
	
     /* var status = ($(this).hasClass("btn-success")) ? '0' : '1';
	 
      var msg = (status=='0')? 'Close' : 'Open';
      if(confirm("Are you sure to "+ msg)){
        var current_element = $(this);
        url = "ajax/ajaxFollowupstatusChange.php";
        $.ajax({
          type:"POST",
          url: url,
          data: {id:$(current_element).attr('data'),status:status},
          success: function(data)
          {  
		  resultArray = data.split('&&&&');		 
		  $('#ChangeButton_'+resultArray[1]).html(resultArray['0']);

          }
        });*/
      //}      
    });
	
	
function saveColseSummaryPopUpform(){	

	var form=$("#ColseSummaryPopUpForm");
	if(form.parsley().validate()){
	$('.loading').show(); 
	$.ajax({
	   type: "POST",
	   url: 'ajax/ajaxFollowupstatusChange.php',
	   data: form.serialize(), 
	   success: function (result) {
		  if(result!=''){
			    $('#followup_close_summary').val('');
		  $('#close_type').val('');
		  $('#close_status').val('');
		$('#ColseSummaryPopUp').popup('hide');
		$( ".my_popup_open" ).click();	
		   $( "#FollowUpNextUpdate" ).html(result);
		  // resultArray = result.split('&&&&');		
		  
		   // resultArray2 = resultArray[0].split('|||');	
			
		  /*$('#ChangeButton_'+resultArray[1]).html(resultArray2[0]);
		  $('#ChangeFollowUpSummary_'+resultArray[1]).html(resultArray2[1]);
		   $('#ChangeFollowupButton_'+resultArray[1]).html(resultArray2[2]);*/
			
			//$("#ColseSummaryPopUpForm").reset();
		  }
		},
	  complete: function(){
		  $('#followup_close_summary').val('');
		  $('#close_type').val('');
		  $('#close_status').val('');
		$('#ColseSummaryPopUp').popup('hide');
		$( ".my_popup_open" ).click();	
		   $( "#FollowUpNextUpdate" ).html(result);
	  }
	});
	return false;
	}
}
function ajaxAddNextFollowup(followup_id,daily_Visit_id,hotel_id){
	
	var daily_Visit_id = $("#daily_Visit_id").val();
	var hotel_id = $("#hotel_id").val();


		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxAddNextFollowUp.php',
		   data: 'followup_id='+followup_id+'&daily_Visit_id='+daily_Visit_id+'&hotel_id='+hotel_id, 
		   success: function (result) {					
				resultArray = result.split('|||');		
											
					$('#AddNextFollowup'+resultArray['1']).html(resultArray['0']);
									
			}
		})

}

function savefollowupDate() {  
  var form=$("#nextFollowup");
  if(form.parsley().validate()){
	 $('#ColseSummaryPopUp').popup('hide'); 
  	
	var nextFollowup = form.serialize(); 
	 $.ajax({
	   type: "GET",
	   url: 'ajax/ajaxfollowupCalanderUpdate.php',
	   data: nextFollowup,  
	   success: function (result) {	
	     
		   $( ".my_popup_open" ).click();	
		   $( "#FollowUpNextUpdate" ).html(result);
		}
	})  	
	}
	return false;
}
  </script>
  <script>
		function Toggle(id) {
			alert(document.getElementById(id).style.display);
			if (document.getElementById(id).style.display == "none" || document.getElementById(id).style.display == "") {
				document.getElementById(id).style.display = "block";
			} else if (document.getElementById(id).style.display == "block") {
				document.getElementById(id).style.display = "none";
			} else {
				document.getElementById(id).style.display = "none";
			}
		}
		
		function SelectHotelsList123(HotelDuplicateInsert){
			var test = HotelDuplicateInsert;
			
		        $("div.desc").hide();
		        $("#cars" + test).show();
			}
		
</script>
<script>
  $( function() {	 
    $( ".datepickertest").datepicker();
	$( "#datepicker" ).datepicker();

  } );
  
  

  </script>
  <div id="ColseSummaryPopUp" class="well" style="display:none;">
    <div id="" class="ajaxAddRoom">
        <div class="btn btn-default tablenew1 tablenewmobile1">
        <div class="col-md-9">
            <div class="form-group" style="text-align:left;">
            <label>Follow Up Status </label>
            <br>
            <input type="radio" name="HotelDuplicateInsert"  id="HotelDuplicateInsert" onClick="SelectHotelsList123(1);"   value="1"  checked="checked" />
            1) Action Required
            <input type="radio" name="HotelDuplicateInsert" id="HotelDuplicateInsert" onClick="SelectHotelsList123(2);" value="2" />
            2) Close</div>
          </div>
        <div id="cars1" class="desc">
            <form name="nextFollowup" id="nextFollowup"  method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off">
            
<input type="hidden" name="followup_id" id="followup_id" value="">
<input type="hidden" name="daily_Visit_id" id="daily_Visit_id" value="">
<input type="hidden" name="hotel_id_hidden" id="hotel_id" value="">
<input type="hidden" name="followup_status" id="followup" value="">
<input type="hidden" name="followup_type" id="followup_type" value="">
<div class="form-group">
                
              <label style="float:left;">Follow Up Summary</label>
                                                   
                <textarea   name="followup_description" id="followup_description"  class="form-control" placeholder="Follow Up Summary"  data-parsley-required automcomplete="off"></textarea>
                
                
              </div>
              <div class="form-group">
                <input type="text" class="form-control datepickertest" placeholder="Enter date" id="followup_date" name="followup_date" value="<?php echo date('d-m-Y');?>"  data-parsley-required>
              </div>
           <?php   $availableData .='<div class="form-group"><label style="float:left;">Assign To</label>';
               
              
                 $availableData .= '<select class="form-control select2" name="assign_user_id" id="assign_user_id">
											  								<option value="">Select Assign UserName</option>';
				  $resUserLevel = selectSql(TBL_USERS," WHERE `status` = '1' AND  `id_shop` = '".addslashes($_SESSION['shop'])."'  ",' ORDER BY `name`');
											  if($db->num_rows2($resUserLevel)){
											  	while($resultUserLevel = $db->fetch_object2($resUserLevel)){
													if($_SESSION['userId'] == $resultUserLevel->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$availableData .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'</option>';
												}
											  }
											 	 $availableData .= '</select>';
                                              
                                              	 echo $availableData .='</div>';
												 
												 ?>
												
             <?php /*?> <div class="form-group">
             <label style="float:left;">Assign To</label>
                <select class="form-control select2" name="assign_user_id" id="assign_user_id">
				<option value="">Select Assign UserName</option>
<?php  $resUserLevel = selectSql(TBL_USERS," WHERE `status` = '1' AND  `id_shop` = '".addslashes($_SESSION['shop'])."'  ",' ORDER BY `name`');
						  if($db->num_rows2($resUserLevel)){
							while($resultUserLevel = $db->fetch_object2($resUserLevel)){
								if($_SESSION['userId'] == $resultUserLevel->id){
									$selected = 'selected="selected"';
								}else{
									$selected = '';
								}
								echo $availableData .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'</option>';
							}
						  }
						  ?>
							 </select>
						  
                                              	 </div><?php */?>
            
            <div class="form-group" style="float:left;">
                <button class="btn btn-primary" onclick="savefollowupDate();" type="button">Save</button>
              &nbsp;<button class="ColseSummaryPopUp_close btn btn-default">Close</button>
              </div>
            '
          </form>
          </div>
        <div id="cars2" class="desc" style="display: none;">
            <form id="ColseSummaryPopUpForm" class="ColseSummaryPopUpForm" data-parsley-validate autocomplete="off">
<input type="hidden" name="followup_id_hidden" id="followup_id_hidden" value="">
<input type="hidden" name="daily_Visit_id_hidden" id="daily_Visit_id_hidden" value="">
<input type="hidden" name="hotel_id_hidden" id="hotel_id_hidden" value="">
<input type="hidden" name="followup_status_hidden" id="followup_status_hidden" value="">
<input type="hidden" name="followup_hidden_type" id="followup_hidden_type" value="">
            <div class="form-group">
                <input type="hidden" name="fs_daily_visit_followup_new" id="fs_daily_visit_followup_new" value="">
               <div class="form-group"> 
               <select name="close_type"  id="close_type" class="form-control input-sm" data-parsley-required >
									 	 <option value="">Select Close Type</option>';
											<?php  $resCat2 = selectSql(TBL_SALES_FOLLOWUPCLOSE_MASTER,"where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `id`');
											  if(num_rows($resCat2)){
											  	while($resultCat2 = $db->fetch_object2($resCat2)){
												
													if($row->amendment_remarks_id	== $resultCat2->id){
														$selected2 = 'selected="selected"';
													}else{
														$selected2 = '';
													}
													echo $availableDatasales .= '<option   '.$selected2.' value="'.$resultCat2->id.'">'.ucfirst($resultCat2->name).'</option>';
												}
											  }
											 	 
												 
												 ?></select>
                
                
                </div>
                 <div class="form-group"> 
                <textarea   name="followup_close_summary" id="followup_close_summary" class="form-control" placeholder="Close Summary"  data-parsley-required automcomplete="off"></textarea>
                </div>
                <br/>
                <div class="form-group" style="float:left;">
                  <button class="btn btn-primary" onclick="saveColseSummaryPopUpform();" type="button">Save</button>
                 &nbsp;<button class="ColseSummaryPopUp_close btn btn-default">Close</button>
                 </div>
              </div>
          </form>
          </div>
      </div>
      </div>
  </div>
  

