<?php include_once("../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_INVENTORY,'view');
//if($_REQUEST['eId'] == ''){ header("location:editHotels.php"); }

/*unset($_SESSION);
mysqli_close($connNew);
mysqli_close($db);
mysqli_close($conn);*/
?>
<?php include_once("includes/header.php")?>
<style>.itemName {
  z-index: 9001;
}

</style><style>
	.fc-day-grid-event {
  margin: 3px 4px 4px;
  padding: 8px 2px;
  border-radius: 12px;
}
.fc-content{	
	border-radius: 5px;
  position: relative;
  padding: 5px 10px;
 
 
  margin: 5px 1px 3px 11px;
  color: #fff;white-space: none !important;
	}
</style>
<?php include_once("includes/left.php")?>
<?php $conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Weekly Planner 
        <small>Weekly</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Weekly</li>
      </ol>
    </section>
	
    <!-- Main content -->
    <section class="content">
    
      <div class="row">
        <!-- /.col -->
	
			
      
			
          <div class="box box-primary">
          <div class="box-header with-border">
              <div class="nav-tabs-custom col-md-12"> 
		  <div class="form-group col-md-3 ">
                                <label>User Name</label>
                                <!--<input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />-->
                               <?php 
                        
                         
                         $categoryDropDown = '<select class="form-control select2 " name="Admin_user_id" id="Admin_user_id" onChange="callingLoader();">
                                                        <option value="">Select User</option>';
                                       
                                        $resUserLevel = selectSql(TBL_USERS," WHERE `status` = '1' AND `sales_status_active` = '1' AND  `id_shop` = '".addslashes($_SESSION['shop'])."' ".$teamMembers."  $UserRestriction ",' ORDER BY `name`');
                                        
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
		  	</div></div>
            
            
            <div class="box-footer">
       
        <input name="Download" type="submit" class="btn btn-primary" value="Send Planner" onclick="SendPlannerMail();" style="float:right;">
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
              <div id="calendar"></div>
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
<div id="inventory" class="well">
  <form id="inventorypopupForm" data-parsley-validate autocomplete="off">
    <div class="form-group">
      <label for="room_name">Room Name</label>
	  <input type="hidden" name="hotelId" id="hotelId" value="" >
	  <input type="hidden" name="room_id" id="room_id" >
	  <input type="hidden" name="allocation_date" id="allocation_date" >
	  <input type="text" class="form-control input-sm" placeholder="Enter room name" id="room_name" name="room_name" data-parsley-required disabled="true">     
    </div>
	<div class="form-group">
      <label for="room_available">Room Status(CRS)</label>
      <input type="text" class="form-control input-sm" placeholder="Enter room available" id="room_available" name="room_available" value="5" data-parsley-required data-parsley-type="digits" readonly="true">
    </div>
     <div class="form-group">
      <label for="blocked_hotel">Room Blocked By Hotel </label>
      <input type="text" class="form-control input-sm" placeholder="Enter room blocked" id="blocked_hotel" name="blocked_hotel" value="5" data-parsley-required data-parsley-type="digits"  readonly="true">
    </div>
	 <div class="form-group">
      <label for="crs_available">Room Status(Hotel)</label>
      <input type="number" class="form-control input-sm" placeholder="Enter room available"  id="crs_available" name="crs_available"  data-parsley-required  onKeyUp="calculateRoomsCrsAvailable();checkMax(this.value);">
      <span id="maxError" style="color:red;"></span>
    </div>
     
	 <div class="form-group">
      <label for="online_allocation">Online Rooms</label>
      <input type="number" class="form-control input-sm" <?php echo $ReadOnly; ?> placeholder="Enter room allocated online" id="online_allocation" name="online_allocation" value="5" onchange="checkMax(this.value);" data-parsley-required >
      <span id="maxError"></span>
    </div>
  
    <div class="form-group">
      <label for="crs_available">Upadte OTAs also</label>
      <select name="OTA_req" id="OTA_req" class="form-control">
      <option value="1" <?php echo $selected;?>>Yes</option>
      <option <?php echo $selected2;?> value="0">No</option>
    </select>
    </div>
  <?php //} ?>
		<!--<div class="btn-group" style="width: 100%; margin-bottom: 10px;">
					<ul class="fc-color-picker" id="color-chooser">
					  <li><a class="text-aqua" href="#"><i class="fa fa-square"></i></a></li>
					  <li><a class="text-blue" href="#"><i class="fa fa-square"></i></a></li>
					  <li><a class="text-light-blue" href="#"><i class="fa fa-square"></i></a></li>
					  <li><a class="text-teal" href="#"><i class="fa fa-square"></i></a></li>
					  <li><a class="text-yellow" href="#"><i class="fa fa-square"></i></a></li>
					  <li><a class="text-orange" href="#"><i class="fa fa-square"></i></a></li>
					  <li><a class="text-green" href="#"><i class="fa fa-square"></i></a></li>
					  <li><a class="text-lime" href="#"><i class="fa fa-square"></i></a></li>
					  <li><a class="text-red" href="#"><i class="fa fa-square"></i></a></li>
					  <li><a class="text-purple" href="#"><i class="fa fa-square"></i></a></li>
					  <li><a class="text-fuchsia" href="#"><i class="fa fa-square"></i></a></li>
					  <li><a class="text-muted" href="#"><i class="fa fa-square"></i></a></li>
					  <li><a class="text-navy" href="#"><i class="fa fa-square"></i></a></li>
					</ul>
				  </div>-->
    <button class="btn btn-default" onclick="inventoryPopupSave();" type="button">Save</button>
    <button class="inventory_close btn btn-default">Close</button>
  </form>
</div>

<input type="hidden" name="reservation_date" id="reservation_date" value="<?php echo date('Y-m-d').' to '.date('Y-m-31') ?>">






<div class="modal" id="inventorySoldOut"  role="dialog" aria-labelledby="inventorySoldOutModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
           <div id="inventorySoldOutData">
           </div>
            
            
            
               
            
        </div>
    </div>
</div>
<!--- End Checkin modal -->



  <span class="my_popup_open" style="display:none;"></span>

  <div id="my_popup" class="well">

    <div id="FollowUpNextUpdate"></div>

    <button id='my_popup_ok'  class="my_popup_close btn btn-default pull-left">Ok</button>
    <!--<button id='alertForMail'  class="my_popup_close btn btn-default pull-left">No</button>

  -->

  </div>

<?php include_once("includes/footer.php")?>
<script src="
https://cdn.jsdelivr.net/npm/jquery.redirect@1.2.0/jquery.redirect.min.js
"></script>
<script>

function callingLoader(linkId){
	var linkId = $("#Admin_user_id").val();
	
 var eventSources  = 
 		{

        url: "ajax/ajaxWeeklyPlanner.php?exe_user_id="+linkId,
        type: 'POST',       
    }

  	$('#calendar').fullCalendar('removeEvents');
  	$('#calendar').fullCalendar('removeEventSource', eventSources);
    $('#calendar').fullCalendar('addEventSource', eventSources);
    	 
		
   
}
function calculateCrsAvailable(){

var form=$("#inventorypopupForm");
if(form.parsley().validate()){
	var room_available = parseFloat($("#room_available").val());
	var blocked_hotel = parseFloat($("#blocked_hotel").val());
	var crs_available = parseFloat(room_available-blocked_hotel);
	$("#crs_available").val(crs_available);
	$("#online_allocation").val(crs_available);
  document.getElementById('crs_available').setAttribute('max',crs_available);
	}
}

function calculateRoomsCrsAvailable(){

var form=$("#inventorypopupForm");
if(form.parsley().validate()){
	var room_available = parseFloat($("#room_available").val());
	var crs_available = parseFloat($("#crs_available").val());
	var blocked_hotel = parseFloat(room_available-crs_available);
	$("#blocked_hotel").val(blocked_hotel);
	//$("#online_allocation").val(blocked_hotel);
	$("#online_allocation").val(crs_available);
	}
}

function checkMax(val){
  let room_available = parseFloat($("#room_available").val());
  console.log(room_available+'----'+val);
  if(val >room_available ){
    $("#maxError").html('Value must be less than or equal to '+room_available);
  }
  else{
    $("#maxError").html('');
  }
}

function inventoryPopupSave(){

var form=$("#inventorypopupForm");
if(form.parsley().validate()){

  var hotelId = $("#hotelId").val();
	var roomId = $("#room_id").val();
	var blocked_hotel= $("#blocked_hotel").val();
	var crs_available= $("#crs_available").val();
	var online_allocation = $("#online_allocation").val();
	var allocation_date = $("#allocation_date").val();
  var OTA_req = $("#OTA_req").val();
$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxInventoryUpdate.php',
		   data: 'type=1&OTA_req='+OTA_req+'&hotelId='+hotelId+'&roomId='+roomId+'&blocked_hotel='+blocked_hotel+'&crs_available='+crs_available+'&online_allocation='+online_allocation+'&allocation_date='+allocation_date, 
		   success: function (result) {					
					$('#inventory').popup('hide');
					$('#calendar').fullCalendar( 'refetchEvents' );
			}
			})
			
			
}
}


function inventorySoldOut(){

  var hotelId ='';
  var start_date = $("#start_date").val();
	var end_date= $("#end_date").val();
  var OTA_req = $("#OTA_req1").val();
  
$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxInventoryUpdate.php',
		   data: 'type=2&OTA_req='+OTA_req+'&hotelId='+hotelId+'&start_date='+start_date+'&end_date='+end_date, 
		   success: function (result) {	
         $('#inventorySoldOut').popup('hide');
				 $('#calendar').fullCalendar( 'refetchEvents' );

			}
			})
}
<!-- Page specific script inventory.php -->



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
        left  : 'prev,next',
        center: 'title',
		    right: 'today,month,basicWeek'
        /* right : 'month,agendaWeek,agendaDay'*/
      },
		defaultView: 'basicWeek',
      buttonText: {
        today: 'today',
        month: 'month',
      },
      //Random default events
    //  events    : 'ajaxInventory.php',
	
	 eventSources: [
        // your event source
         { 
            url: 'ajax/ajaxWeeklyPlanner.php',
            type: 'POST',
			data: {
                'exe_user_id' : '<?php echo $_SESSION['userId'];?>',
            },			
            error: function() {
                alert('there was an error while fetching events!');
            }
        }
        // your event source
    ],
    

	eventClick: function(calEvent, jsEvent) { //EDIT
		var editId = calEvent.id;
		//$('#inventorySoldOut').popup('show');
		var start= calEvent.start.toISOString();
		 var b = $('#calendar').fullCalendar('getDate');
		$.ajax({
               type: "POST",
               url: 'ajax/ajaxWeeklyPlannerPopup.php',
              data: 'start='+moment(start).format('YYYY-MM-DD')+'&set=edit&editId='+editId, 
               success: function (result) {
				    //$(".select2").select2();
				   $('#inventorySoldOut').popup('show');
                   $('#inventorySoldOutData').html(result);
				  // $('.user_custom_smtp').select2();
				  // $(".select2").select2({});
				   $(".select3").select2({});
				  // $("#cars2").hide();
				  // $("#new_customer").hide();
				   // $("#user_custom_smtp").select2({});
                //alert(result);
                //console.log(result);
              },
              complete: function(){
              $('.loading').hide();
              }
          })
     /*$('#inventory').popup('show');
	 var roomId = calEvent.room_id;
	 $("#room_id").val(roomId);
	 $("#room_name").val(calEvent.roomName);
	 $("#room_available").val(calEvent.availableInventory);
	 $("#blocked_hotel").val(calEvent.blocked_hotel);
	 $("#crs_available").val(calEvent.crs_available);
	 $("#online_allocation").val(calEvent.online_allocation);
	 $("#allocation_date").val(calEvent.allocation_date);*/
       /* if (title!='') {
            calEvent.title = title;
            $('#calendar').fullCalendar('updateEvent', calEvent);
        }*/
    },
    viewRender: function (view, element) {
      var b = $('#calendar').fullCalendar('getDate');
      var dateStart = view.intervalStart.format('YYYY-MM-DD');
      var dateEnd = view.intervalEnd.subtract(1,'days').format('YYYY-MM-DD');
      var reservDate = dateStart+' to '+dateEnd;

      var hotelId = $("#hotelId").val();
      var reservation_date = reservDate;
     /* function ajaxCheckAvailability() {
            $('.loading').show(); 
            $.ajax({
               type: "POST",
               url: 'ajax/ajaxcheckAvailability.php',
               data: 'hotel_id='+hotelId+'&reservation_date='+reservation_date, 
               success: function (result) {
                //$('#availabilty').html(result)
                //alert(result);
                //console.log(result);
              },
              complete: function(){
              $('.loading').hide();
              }
          }) 
        return false;
       }
      ajaxCheckAvailability();*/

    },
	 selectHelper: true,
			select: function(start, end, allDay) {
				//alert(start);
				$.ajax({
               type: "POST",
               url: 'ajax/ajaxWeeklyPlannerPopup.php',
              data: 'start='+moment(start).format('YYYY-MM-DD')+'&set=Add', 
               success: function (result) {
				    //$(".select2").select2();
				   $('#inventorySoldOut').popup('show');
                   $('#inventorySoldOutData').html(result);
				  // $('.user_custom_smtp').select2();
				  // $(".select2").select2({});
				   $(".select3").select2({});
				   $("#cars2").hide();
				   $("#new_customer").hide();
				   // $("#user_custom_smtp").select2({});
                //alert(result);
                //console.log(result);
              },
              complete: function(){
              $('.loading').hide();
              }
          })
				
				
				 //$('#inventorySoldOut').popup('show');
				 //$("#start_date").val(moment(start).format('YYYY-MM-DD'));
				 //$("#end_date").val(moment(end).format('YYYY-MM-DD'));
         

    },
    
	  selectable: true,
     //editable  : true,
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
  
  //$('.select2').select2();


// $(".select2").select2(); 
function Selectweeklyplanner(datatext){
	
	$('#search_name').val('');	
	$('#id_contacts').val('');	
	$('#new_contact_name').val('');	
	$('#new_contact_mobile').val('');	
						
		if(datatext=='2'){
		$('#search_name').attr('data-parsley-required', 'false');
			$('#id_contacts').attr('data-parsley-required', 'false');
			$('#new_contact_mobile').attr('data-parsley-required', 'false');
			$('#new_contact_name').attr('data-parsley-required', 'false');
		}				
		var test = datatext;
        $("div.desc").hide();
        $("#cars" + test).show();
	
	}
	function SendPlannerMail(){
		
		var calendar = $('#calendar').fullCalendar('getCalendar');
		var view = calendar.getView();
		var startDate = view.start;
		var endDate = view.end;
		var user_id = $("#Admin_user_id").val();
		$( ".my_popup_open" ).click();
    $( "#FollowUpNextUpdate" ).html('Please Wait Weekly Planner Mailing In Progress...'); 
		$.ajax({
           type        : 'POST',
           url         : 'ajax/ajaxSendWeeklyPlannerMail.php', 
           data        : 'user_id='+user_id+'&startDate='+startDate.format()+'&endDate='+endDate.format(),
           success     : function(result){
			   $(".my_popup_close").click();			 
             alert (result);
             //window.location.href='editDailyReport.php';
           } 
          });
		//$.redirect('sendWeeklyPlannerMail.php',{'user_id':user_id,'startDate':startDate.format(),'endDate':endDate.format()});
		
	}
	
</script>