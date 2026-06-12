<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_INVENTORY,'view');
if($_REQUEST['eId'] == ''){ header("location:editHotels.php"); }
?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Hotel Manager
        <small>Inventory</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Inventory</li>
      </ol>
    </section>
	
    <!-- Main content -->
    <section class="content">
    
      <div class="row">
        <!-- /.col -->
	
			
        <div class="col-md-12">
			<div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
				   <li ><a href="editHotels.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" >Overview</a></li>   
				  <li><a href="editHotelGallery.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>">Photo Gallery</a></li> 
				  <li><a href="manageAssignHotelRoom.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>">Room Types</a></li>   
				 
				  <li class="active" ><a href="inventory.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" data-toggle="tab">Inventory</a></li>        
				   <li  ><a href="calendar.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" >Calendar</a></li>        
				</ul>
			</div>
			
          <div class="box box-primary">
		  
		  	<div class="box-header with-border">
              <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> Inventory : <a><?php echo selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'"); ?></a></h3>
			  
				  <a href="inventoryBulkUpdate.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" class="btn btn-success pull-right"><i class="fa fa-tasks"></i> Bulk Update</a>
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
	  <input type="hidden" name="hotelId" id="hotelId" value="<?php echo encryptor('decrypt',$_REQUEST['eId']); ?>" >
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
      <input type="text" class="form-control input-sm" placeholder="Enter room available" id="crs_available" name="crs_available" value="5" data-parsley-required  onKeyUp="calculateRoomsCrsAvailable();">
    </div>
	 <div class="form-group">
      <label for="online_allocation">Online Rooms</label>
      <input type="text" class="form-control input-sm" placeholder="Enter room allocated online" id="online_allocation" name="online_allocation" value="5" data-parsley-required >
    </div>
		<div class="btn-group" style="width: 100%; margin-bottom: 10px;">
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
				  </div>
    <button class="btn btn-default" onclick="inventoryPopupSave();" type="button">Save</button>
    <button class="inventory_close btn btn-default">Close</button>
  </form>
</div>

<div id="inventorySoldOut" class="well">
  <form id="inventorypopupForm" data-parsley-validate autocomplete="off">
	  <input type="hidden" name="start_date" id="start_date" value="" >
	  <input type="hidden" name="end_date" id="end_date" value="" >	
     <p> Are you sure you want to mark these dates as sold out ?</p>
    <button class="btn btn-danger" onclick="inventorySoldOut();" type="button">Yes</button>
    <button class="inventorySoldOut_close btn btn-primary">Cancel</button>
  </form>
</div>

<?php include_once("includes/footer.php")?>

<script>
function calculateCrsAvailable(){

var form=$("#inventorypopupForm");
if(form.parsley().validate()){
	var room_available = parseFloat($("#room_available").val());
	var blocked_hotel = parseFloat($("#blocked_hotel").val());
	var crs_available = parseFloat(room_available-blocked_hotel);
	$("#crs_available").val(crs_available);
	$("#online_allocation").val(crs_available);
	}
}

function calculateRoomsCrsAvailable(){

var form=$("#inventorypopupForm");
if(form.parsley().validate()){
	var room_available = parseFloat($("#room_available").val());
	var crs_available = parseFloat($("#crs_available").val());
	var blocked_hotel = parseFloat(room_available-crs_available);
	$("#blocked_hotel").val(blocked_hotel);
	$("#online_allocation").val(blocked_hotel);
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
$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxInventoryUpdate.php',
		   data: 'type=1&hotelId='+hotelId+'&roomId='+roomId+'&blocked_hotel='+blocked_hotel+'&crs_available='+crs_available+'&online_allocation='+online_allocation+'&allocation_date='+allocation_date, 
		   success: function (result) {					
					$('#inventory').popup('hide');
					$('#calendar').fullCalendar( 'refetchEvents' );
					 
			}
			})
			
			
}
}


function inventorySoldOut(){

    var hotelId = <?php echo encryptor('decrypt',$_REQUEST['eId']); ?>;
	var start_date = $("#start_date").val();
	var end_date= $("#end_date").val();
$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxInventoryUpdate.php',
		   data: 'type=2&hotelId='+hotelId+'&start_date='+start_date+'&end_date='+end_date, 
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
        left  : 'prev,next today',
        center: 'title',
		right : 'month'
       /* right : 'month,agendaWeek,agendaDay'*/
      },
      buttonText: {
        today: 'today',
        month: 'month',
      },
      //Random default events
    //  events    : 'ajaxInventory.php',
	
	 eventSources: [
        // your event source
        {
            url: 'ajax/ajaxInventory.php',
            type: 'POST',
			data: {
                'hotelId' :  <?php echo encryptor('decrypt',$_REQUEST['eId']); ?>,
            },			
            error: function() {
                alert('there was an error while fetching events!');
            }
        }
        // any other sources...
    ],

	eventClick: function(calEvent, jsEvent) {
	 $('#inventory').popup('show');
	 var roomId = calEvent.room_id;
	 $("#room_id").val(roomId);
	 $("#room_name").val(calEvent.roomName);
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
				 $('#inventorySoldOut').popup('show');
				 $("#start_date").val(moment(start).format('YYYY-MM-DD'));
				 $("#end_date").val(moment(end).format('YYYY-MM-DD'));
				 
				 //console.log(start);
				 //$('#calendar').fullCalendar('unselect');
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

</script>