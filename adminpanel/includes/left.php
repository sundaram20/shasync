<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar"> <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar"> <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image"> <img src="<?php echo $SITE_URL; ?>/uploaded_files/shop/<?php echo $resLogo; ?>" class="img-circle" alt="User Image"> </div>
      <div class="pull-left info">
        <p><?php echo $_SESSION['userName'];?> </p>
      </div>
    </div>
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">MAIN NAVIGATION</li>
      <li class="treeview"> <a href="#"> <i class="fa fa-dashboard" aria-hidden="true"></i> <span>Dashboard</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a> 
       <ul class="treeview-menu">
      <?php $crs_sales_both_active= selectColumn(TBL_SHOP,'crs_sales_both_active'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");
     // if($crs_sales_both_active==0){?>
	<?php //if($_SESSION['shop']=='6'){?>	
		   <li > <a href="<?php echo $SITE_URL; ?>/adminpanel/salesDashboard07.php" rel="salesDashboardReport02.php"> <i class="fa fa-circle-o"></i> <span>Dashboard 1.0 (Beta)</span> </a> </li> 
		    <!-- <li > <a href="<?php echo $SITE_URL; ?>/adminpanel/salesLeadAward.php" rel="salesLeadAwardSummary.php"> <i class="fa fa-circle-o"></i> <span>Sales Lead Award</span> </a> </li>-->
		   <li > <a href="<?php echo $SITE_URL; ?>/adminpanel/salesLeadAwardSummary.php" rel="salesLeadAwardSummary.php"> <i class="fa fa-circle-o"></i> <span>Sales Lead Summary</span> </a> </li>
      <!-- <li > <a href="<?php echo $SITE_URL; ?>/adminpanel/salesDashboard.php"> <i class="fa fa-circle-o"></i> <span>Dashboard Test</span> </a> </li>
      <li > <a href="<?php echo $SITE_URL; ?>/adminpanel/dashboard1/salesDashboardReport.php" rel="salesDashboardReport.php"> <i class="fa fa-circle-o"></i> <span>Dashboard 1.0 (Beta) test</span> </a> </li> 
          
       <!-- <li > <a href="<?php echo $SITE_URL; ?>/adminpanel/SalesDashboardDemo.php" rel="SalesDashboardDemo.php"> <i class="fa fa-circle-o"></i> <span>Dashboard 5.0 (Beta)</span> </a> </li>  -->
      <?php //}      ?>
    <?php //}else{?>
   <!-- <li > <a href="<?php echo $SITE_URL; ?>/adminpanel/Dashboard.php"> <i class="fa fa-circle-o"></i> <span>Dashboard</span> </a>
      </li>-->
    <?php //} ?>
    
    
    </ul>
       </li>
       
      <li > <a href="<?php echo $SITE_URL; ?>/adminpanel/editDailyReport.php"> <i class="fa fa-laptop"></i> <span>Activity Calendar</span> </a>
      </li>

 <li > <a href="<?php echo $SITE_URL; ?>/adminpanel/weeklyPlanner.php"> <i class="fa fa-laptop"></i> <span>Planner</span> </a>
      </li>
      <li > <a href="<?php echo $SITE_URL; ?>/adminpanel/manageCompany.php" rel="editCompany.php"> <i class="fa fa-laptop"></i> <span>Create Company</span> </a>
      </li>
		
		<?php 
	  //if($_SESSION['userLevel']!=1){
	 //$checkMasterMenuAccess= selectColumn(TBL_USER_LEVELS,'customer_access_approval'," WHERE `id` = '".addslashes($_SESSION['userLevel'])."'");
	//  }else{
	  //$checkMasterMenuAccess	=$_SESSION['userLevel'];
		//  }
	  //if($checkMasterMenuAccess==1){ ?>
		
		<!--<li > <a href="<?php echo $SITE_URL; ?>/adminpanel/customerList.php" rel="editCustomer.php"> <i class="fa fa-users"></i> <span>Manage Customers</span> </a>
      </li>-->
		<?php// } ?>
		
      <li class="treeview"> <a href="#"> <i class="fa fa-laptop"></i> <span>Sales Activities</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
      <ul class="treeview-menu">
          <!-- sub menu-->
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/ManagervisitReport.php?link=set" rel="ManagervisitReport.php"><i class="fa fa-circle-o"></i>Sales Visit Summary</a></li>
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageInHouse.php" rel="manageInHouse.php"><i class="fa fa-circle-o"></i>Sales Activity Summary</a></li>
          
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageEnquiry.php" rel="editEnquiry.php"><i class="fa fa-circle-o"></i>Lead Summary</a></li>
           <?php 

			if($_SESSION['incentive_module_approved']	==1){?>   
             <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageIncentive.php" rel="manageIncentive.php"><i class="fa fa-circle-o"></i>Sales Lead Award </a></li>
          <?php } ?>
		  <li><a href="<?php echo $SITE_URL; ?>/adminpanel/WeeklyPlannerDailyReport.php" rel="WeeklyPlannerDailyReport.php"><i class="fa fa-circle-o"></i>Weekly Planner Report </a></li>
         
          <!--<li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageQuote.php" rel="editEnquiry.php"><i class="fa fa-circle-o"></i>Quote</a></li>-->
          
          <!------------->
        </ul>

	<?php if($_SESSION['database']=='gcs'){?>	  
		  
		   <li class="treeview"> <a href="#"> <i class="fa fa-laptop"></i> <span>GCS</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
      <ul class="treeview-menu">
          <!-- sub menu-->
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/ManagerDailyPickup.php" rel="edit-daily-pickup.php"><i class="fa fa-circle-o"></i>Daily Pickup</a></li>
		  <li><a href="<?php echo $SITE_URL; ?>/adminpanel/ManagerDailyPickupItemwise.php" rel="edit-daily-pickup.php"><i class="fa fa-circle-o"></i>Daily Pickup(Item wise)</a></li>
		  <li><a href="<?php echo $SITE_URL; ?>/adminpanel/DailyPickupReport.php" rel="DailyPickupReport.php"><i class="fa fa-circle-o"></i>Daily Pickup Report</a></li>
         <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageDailyPickupMailFormat.php" rel="editMailFormat.php"><i class="fa fa-circle-o"></i>Mail Master</a></li>
		  <li><a href="<?php echo $SITE_URL; ?>/adminpanel/calls.php" rel="editCalls.php"><i class="fa fa-circle-o"></i>Manage Calls</a></li>
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/support.php" rel="addSupport.php"><i class="fa fa-circle-o"></i>Manage Support For non-exist users</a></li>
        </ul>
		  
		  
		  <?php } ?>
		  
		  
		  
		  
		  
      <li class="treeview"> <a href="#"> <i class="fa fa-dollar"></i> <span>Rates</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
        <ul class="treeview-menu">
          <!-- sub menu-->
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/rateQuery.php" rel="editCompany.php"><i class="fa fa-circle-o"></i>View Rates</a></li>
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageRateLetters.php" rel="editRateLetters.php"><i class="fa fa-circle-o"></i>Rate Letters - RSO</a></li>
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageRateLettersUnit.php" rel="editRateLettersUnit.php"><i class="fa fa-circle-o"></i>Rate Letters - Unit</a></li>
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageHotelwiseContractReport.php" rel="editHotelwiseContractReport.php"><i class="fa fa-circle-o"></i>Hotelwise Contract Report</a></li>
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageRatePoints.php" rel="editRatePoints.php"><i class="fa fa-circle-o"></i>Rate Point Master</a></li>
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageGeneralTerms.php" rel="editGeneralTerms.php"><i class="fa fa-circle-o"></i>Rate General Terms </a></li>
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageMailFormat.php" rel="editMailFormat.php"><i class="fa fa-circle-o"></i>Rate Mail Master</a></li>
          <!------------->
        </ul>
      </li>


      <li class="treeview"> <a href="#"> <i class="fa fa-file-excel-o"></i> <span>Sales Reports</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
        <ul class="treeview-menu">
          <!-- sub menu-->
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/ManagervisitReport.php" rel="editCompany.php"><i class="fa fa-circle-o"></i>Daily Sales Report</a></li>
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageConveyance.php" rel="manageConveyance.php"><i class="fa fa-circle-o"></i>Conveyance Report</a></li>
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/visitHistoryReport.php" rel="visitHistoryReport.php"><i class="fa fa-circle-o"></i>Company History Report</a></li>
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/visitCompanyConsolidatedReport.php" rel="visitCompanyConsolidatedReport.php"><i class="fa fa-circle-o"></i>Company Visited Summary Report</a></li>
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/companyVisitedDetailsReport.php" rel="companyVisitedDetailsReport.php"><i class="fa fa-circle-o"></i>Company Visited Detail Report</a></li>
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/ManagerFeedBackReport.php" rel="ManagerFeedBackReport.php"><i class="fa fa-circle-o"></i> Feedback Report</a></li>
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/ManagerFollowupReport.php" rel="ManagerFollowupReport.php"><i class="fa fa-circle-o"></i> Follow Up Report</a></li>
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/OccasionReport.php" rel="OccasionReport.php"><i class="fa fa-circle-o"></i> Occasion Report </a></li>
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/comparisonReport.php" rel="comparisonReport.php"><i class="fa fa-circle-o"></i>Rate Comparison Report</a></li>
          
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/executiveEmailLogReport.php" rel="executiveEmailLogReport.php"><i class="fa fa-circle-o"></i>Email Log Report</a></li>
          
          <!------------->
        </ul>
      </li>
    <?php //if(selectColumn(TBL_USERS,'user_type','WHERE id='.$_SESSION['userId'].'')!=2){ 
              
            ?>
      <li class="treeview"> <a href="#"> <i class="fa fa-file-excel-o"></i> <span>Performance </span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
        <ul class="treeview-menu">
          <!-- sub menu-->
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/executivePortfolioYearly.php" rel="executivePortfolioYearly.php"><i class="fa fa-circle-o"></i>Yearly Portfolio - RSO</a></li>
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/executivePortfolioMonthly.php" rel="executivePortfolioMonthly.php"><i class="fa fa-circle-o"></i>Monthly Portfolio - RSO</a></li>

          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/unitExecutivePortfolioYearly.php" rel="unitExecutivePortfolioYearly.php"><i class="fa fa-circle-o"></i>Yearly Portfolio - Unit</a></li>
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/unitExecutivePortfolioMonthly.php" rel="unitExecutivePortfolioMonthly.php"><i class="fa fa-circle-o"></i> Monthly Portfolio - Unit</a></li>
           
           <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageAgentBudget.php" rel="editAgentBudget.php"><i class="fa fa-circle-o"></i>Company Yearly Budget</a></li>
           <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageAgentAchieved.php" rel="editAgentBudget.php"><i class="fa fa-circle-o"></i>Company Monthly Achievement</a></li>
          
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/PerformanceAnalysisReport.php" rel="PerformanceAnalysisReport.php"><i class="fa fa-circle-o"></i> Performance Analysis Report </a></li> 
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/managebudget.php" rel="editbudget.php"><i class="fa fa-circle-o"></i> Hotelwise monthly Budget</a></li>
           <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageHotelBudgetAchieved.php" rel="editHotelBudgetAchieved.php"><i class="fa fa-circle-o"></i>Hotelwise Monthly Achieved</a></li>          
 <!------------->
        </ul>
      </li>
      <?php //} ?>
      <li class="treeview"> <a href="#"> <i class="fa fa-file-excel-o"></i> <span>Finance Reports</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
        <ul class="treeview-menu">
          <!-- sub menu-->
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/creditReport.php" rel="creditReport.php"><i class="fa fa-circle-o"></i>Credit Report</a></li>
          <!------------->
        </ul>
      </li>
<?php /* ?>
      <li class="treeview"> <a href="#"> <i class="fa fa-file-excel-o"></i> <span>Accounts</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
        <ul class="treeview-menu">
          <!-- sub menu-->
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/mappings.php" rel="creditReport.php"><i class="fa fa-circle-o"></i>Mapping</a></li>
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/invoices.php" rel="editInvoices.php"><i class="fa fa-circle-o"></i>Outstandings</a></li>
        

          <!------------->
        </ul>
      </li><?php */ ?>
<?php /* ?>
         <li class="treeview"> <a href="#"> <i class="fa fa-file-excel-o"></i> <span>Task </span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
        <ul class="treeview-menu">
          <!-- sub menu-->
          <!--<li > <a href="<?php echo $SITE_URL; ?>/adminpanel/editDailyReportTask.php"  rel="editDailyReportTask.php"> <i class="fa fa-laptop"></i> <span>Task Calendar</span> </a>
      </li> -->
         
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/calls.php" rel="editCalls.php"><i class="fa fa-circle-o"></i>Manage Calls</a></li>
        
<!--<li><a href="<?php echo $SITE_URL; ?>/adminpanel/taskSummary.php" rel="Task.php"><i class="fa fa-circle-o"></i>Task Summary</a></li>-->
        
          <!------------->
        </ul>
      </li>
<?php */ ?>
      <?php if($_SESSION['userLevel']==1){ 
      if($_SESSION['apptracking_module_approved']	==1){ ?>
       <li class="treeview"> <a href="#"> <i class="fa fa-file-excel-o"></i> <span>App Tracking</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
        <ul class="treeview-menu">
          <!-- sub menu-->
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/appLoginLogout.php" rel=""><i class="fa fa-circle-o"></i>App Login-Logout</a></li>
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/appRequest.php" rel=""><i class="fa fa-circle-o"></i>App request</a></li>
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/geoLocation.php" rel=""><i class="fa fa-circle-o"></i>Geo Location Tracking</a></li>   
          <!------------->
        </ul>
      </li>  

<?php } ?>
      <li class="treeview"> <a href="#"> <i class="fa fa-file-excel-o"></i> <span>Admin Reports</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
        <ul class="treeview-menu">
          <!-- sub menu-->
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/executiveLogReport.php" rel="executiveLogReport.php"><i class="fa fa-circle-o"></i>Activity Log Report</a></li>
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/companyAdditionReport.php" rel="companyAdditionReport.php"><i class="fa fa-circle-o"></i>Company Addition Report</a></li>
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/ManagerLoginHistoryReport.php" rel="ManagerLoginHistoryReport.php"><i class="fa fa-circle-o"></i> Login Report </a></li>
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/executiveMtdYtd.php" rel="executiveMtdYtd.php"><i class="fa fa-circle-o"></i>Sales Summary Report</a></li> 		
          <!------------->
        </ul>
      </li>
    <?php } ?>

      <!--<li class="treeview"> <a href="#"> <i class="fa fa-file-excel-o"></i> <span>Sales Reports</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
        <ul class="treeview-menu">
          <li class="treeview"> <a href="#"> <i class="fa fa-file-excel-o"></i> <span>Sales Report</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
            <ul class="treeview-menu">
              
              
              
              
              
              
              
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/visitHistoryReport.php" rel="visitHistoryReport.php"><i class="fa fa-circle-o"></i>Visit History Report</a></li>
            
            
            
            
            <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageNotifications.php" rel="addNotification.php.php"><i class="fa fa-circle-o"></i>Notifications</a></li>
            
                     
                
                  
                   
           
            </ul>
          </li>

          <li class="treeview"> <a href="#"> <i class="fa fa-file-excel-o"></i> <span>Rate Report</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
            <ul class="treeview-menu">
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageHotelwiseContractReport.php" rel="editHotelwiseContractReport.php"><i class="fa fa-circle-o"></i>Hotelwise Contract Report</a></li>
            </ul>
          </li>
        </ul>
      </li>-->
      



      <?php 
	  if($_SESSION['userLevel']!=1){
	 $checkMasterMenuAccess= selectColumn(TBL_USER_LEVELS,'master_menu_approval'," WHERE `id` = '".addslashes($_SESSION['userLevel'])."'");
	  }else{
	  $checkMasterMenuAccess	=$_SESSION['userLevel'];
		  }
	  if($checkMasterMenuAccess==1){ ?>
      <!--Masters-->
      <li class="treeview"> <a href="#"> <i class="fa fa-bars"></i> <span>Masters</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
        <ul class="treeview-menu">
           <li class="treeview"><a href="#" rel=""><i class="fa fa-circle-o"></i><span>Admin Master</span><span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span></a>
            <ul class="treeview-menu">
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageAutoEmail.php" rel="editAutoEmail.php"><i class="fa fa-circle-o"></i> Auto Email Master</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/managebudgetyear.php" rel="editbudgetyear.php"><i class="fa fa-circle-o"></i>Budget Year Master</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageCompanyAreas.php" rel="editCompanyAreas.php"><i class="fa fa-circle-o"></i>Company Domain Master</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageZonal.php" rel="editZonal.php"><i class="fa fa-circle-o"></i> Zone Master</a></li>
				<li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageVendors.php" rel="editVendor.php"><i class="fa fa-circle-o"></i>Party Master</a></li>
             
              
          </ul>
           </li>
          
          <li class="treeview"> <a href="#"> <i class="fa fa-circle-o"></i> <span>Attributes</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
            <ul class="treeview-menu">
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageInHouseActivity.php" rel="editInHouseActivity.php"><i class="fa fa-circle-o"></i> Activity Master</a></li>
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageDesignation.php" rel="editDesignation.php"><i class="fa fa-circle-o"></i> Designation Master</a></li>
 <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageGrade.php" rel="editGrade.php"><i class="fa fa-circle-o"></i> Grade Master</a></li>
				
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageCloseMaster.php" rel="editCloseMaster.php"><i class="fa fa-circle-o"></i> Lead Close Master</a></li>
				<li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageOpenMaster.php" rel="editOpenMaster.php"><i class="fa fa-circle-o"></i> Lead Open Master</a></li>
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageTravelMaster.php" rel="editTravelMaster.php"><i class="fa fa-circle-o"></i> Travel Mode Master</a></li>
                  <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageCharges.php" rel="editCharges.php"><i class="fa fa-circle-o"></i> Charges Master</a></li>
                
            </ul>
          </li> 
          
          <li class="treeview"><a href="#" rel=""><i class="fa fa-circle-o"></i><span>Company Master</span><span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span></a>
            <ul class="treeview-menu">
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageAreas.php" rel="editAreas.php"><i class="fa fa-circle-o"></i>Area Master</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageCompanyCustomer.php" rel="editCustomer.php"><i class="fa fa-circle-o"></i>Contact Master</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageCompanyGroups.php" rel="editCompanyGroups.php"><i class="fa fa-circle-o"></i>Company Group Master</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageState.php" rel="editState.php"><i class="fa fa-circle-o"></i> State Master</a></li>
              
           </ul>
          </li> 

          <li class="treeview"><a href="#" rel=""><i class="fa fa-circle-o"></i><span>Product Master</span><span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span></a>
            <ul class="treeview-menu">
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageHotels.php" rel="editHotels.php" ><i class="fa fa-circle-o"></i>Product Manager</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageHotelTypes.php" rel="editHotelTypes.php"><i class="fa fa-circle-o"></i>Product Category Master</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageRoomTypes.php" rel="editRoomTypes.php"><i class="fa fa-circle-o"></i>Rooms Category Master</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/calendar.php" rel="calendar.php"><i class="fa fa-circle-o"></i>Product Calendar</a></li>
              <!--<li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageGeneralServices.php" rel="GeneralServices.php"><i class="fa fa-circle-o"></i>Hotel General Services</a></li>

              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageOutdoorActivities.php" rel="OutdoorActivities.php"><i class="fa fa-circle-o"></i>Hotel Outdoor Activities</a></li>

              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageDiningServices.php" ><i class="fa fa-circle-o"></i>Hotel Dining Services</a></li>

              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageConferenceServices.php" ><i class="fa fa-circle-o"></i>Hotel Meetings Services</a></li>

              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/managekidsFacilities.php" ><i class="fa fa-circle-o"></i>Hotel Kids Related Facilities</a></li>

              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageRoomAmenities.php" ><i class="fa fa-circle-o"></i>Hotel Room Amenities</a></li>-->



              <!--<li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageGeneralServices.php" rel="editGeneralServices.php"><i class="fa fa-circle-o"></i>General Services Master</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageOutdoorActivities.php" rel="editOutdoorActivities.php"><i class="fa fa-circle-o"></i>Outdoor Activities Master</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageDiningServices.php" rel="editDiningServices.php" ><i class="fa fa-circle-o"></i>Dining Services Master</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageHotelServices.php" rel="editHotelServices.php"><i class="fa fa-circle-o"></i>Hotel Services Master</a></li>-->
                  
            </ul>
          </li>

          <!-- End of hotel master -->
          <!-- Start of Product master -->

           <?php /*?> <li class="treeview"><a href="#" rel=""><i class="fa fa-circle-o"></i><span>Product  Master</span><span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span></a>
            <ul class="treeview-menu">
 <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageItems.php" rel="editItems.php" ><i class="fa fa-circle-o"></i>Items</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageProductTypes.php" rel="editProductTypes.php" ><i class="fa fa-circle-o"></i>Manage Type</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageUnits.php" rel="editUnits.php" ><i class="fa fa-circle-o"></i>Manage Units</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageProductMainGroups.php" rel="editProductMainGroups.php" ><i class="fa fa-circle-o"></i>Manage Main Group</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageProductSubGroups.php" rel="editProductSubGroups.php" ><i class="fa fa-circle-o"></i>Manage Sub Group</a></li>
               <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageProductGrade.php" rel="editProductGrade.php" ><i class="fa fa-circle-o"></i>Manage Grade</a></li>

                  
            </ul>
          </li><?php */?>
                    <!-- End of Product master -->

                          <!-- Start of Lead Source Master-->
       <li class="treeview"><a href="#" rel=""><i class="fa fa-circle-o"></i><span>Lead  Master</span><span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span></a>
            <ul class="treeview-menu">
 <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageLeadSource.php" rel="editLeadSource.php" ><i class="fa fa-circle-o"></i>Lead Source</a></li>
             
                  
            </ul>
          </li> 
          <!-- End of Lead Source Master-->


          <li class="treeview"><a href="#" rel=""><i class="fa fa-circle-o"></i><span>Rate Master</span><span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span></a>
            <ul class="treeview-menu">
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageRateMaster.php" rel="editRateMaster.php"><i class="fa fa-circle-o"></i> Rate Template</a></li>          
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageRateLevel.php" rel="editRateLevel.php"><i class="fa fa-circle-o"></i> Rate Level Master</a></li>
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageRatePlan.php" rel="editRatePlan.php"><i class="fa fa-circle-o"></i>Rate Plan Master</a></li>
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageRateSeason.php" rel="editRateSeason.php"><i class="fa fa-circle-o"></i>Rate Season Master</a></li>
                  
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageRateMarket.php" rel="editRateMarket.php"><i class="fa fa-circle-o"></i>Rate Market Master</a></li>
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageRatePoints.php" rel="editRatePoints.php"><i class="fa fa-circle-o"></i>Rate Point Master</a></li>
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageGeneralTerms.php" rel="editGeneralTerms.php"><i class="fa fa-circle-o"></i>Rate General Terms </a></li>
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageMailFormat.php" rel="editMailFormat.php"><i class="fa fa-circle-o"></i>Rate Mail Master</a></li>
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageDocumentConfig.php" rel="editDocumentConfig.php"><i class="fa fa-circle-o"></i> Document Master</a></li>
            </ul>
          </li>   

          <!--<li class="treeview"><a href="#" rel=""><i class="fa fa-circle-o"></i><span>Productivity Master</span><span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span></a>
            <ul class="treeview-menu"> 
              
              
              
            </ul>
          </li> --> 
           <?php 

			if($_SESSION['incentive_module_approved']	==1){?>   
           <li class="treeview"><a href="#" rel=""><i class="fa fa-circle-o"></i><span>Incentive Master</span><span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span></a>
            <ul class="treeview-menu">
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/incentiveParticipateHotel.php" rel="incentiveParticipateHotel.php"><i class="fa fa-circle-o"></i> Participate Hotels</a></li>          
             </ul>
          </li> 
            <?php } ?>
          <li class="treeview"><a href="#" rel=""><i class="fa fa-circle-o"></i><span>User Master</span><span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span></a>
            <ul class="treeview-menu"> 
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageUsers.php" rel="editUsers.php"><i class="fa fa-circle-o"></i>User Addition Master</a> </li>
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageUserPermissions.php" rel="editUserPermissions.php" ><i class="fa fa-circle-o"></i>User Permission Master</a> </li>
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageUserLevels.php" rel="editUserLevels.php"><i class="fa fa-circle-o"></i>Level Master</a></li>
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageTeam.php" rel="editTeam.php"><i class="fa fa-circle-o"></i>Team Master</a></li>
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageGroup.php" rel="editGroup.php"><i class="fa fa-circle-o"></i> Team Group Master</a></li>
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageShop.php" rel="editShop.php"><i class="fa fa-circle-o"></i> Shop Master</a></li>
            </ul>
          </li>   
          
        </ul>
      </li>
		
		
		
		<!--master end-->
    <?php } ?>
      
      <!---------->
      
      
      
         
    </ul>
  </section>
  <!-- /.sidebar --> </aside>

