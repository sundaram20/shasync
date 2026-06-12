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
      
      
      <li > <a href="<?php echo $SITE_URL; ?>/adminpanel/salesDashboard.php"> <i class="fa fa-laptop"></i> <span>Dashboard</span> </a>
      </li>

      <li > <a href="<?php echo $SITE_URL; ?>/adminpanel/editDailyReport.php"> <i class="fa fa-laptop"></i> <span>Activity Calendar</span> </a>
      </li>

      <li > <a href="<?php echo $SITE_URL; ?>/adminpanel/manageCompany.php" rel="editCompany.php"> <i class="fa fa-laptop"></i> <span>Create Company</span> </a>
      </li>
      <li class="treeview"> <a href="#"> <i class="fa fa-laptop"></i> <span>Sales Activities</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
      <ul class="treeview-menu">
          <!-- sub menu-->
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/ManagervisitReport.php?link=set" rel="ManagervisitReport.php"><i class="fa fa-circle-o"></i>Sales Visit Summary</a></li>
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageInHouse.php" rel="manageInHouse.php"><i class="fa fa-circle-o"></i>Sales Activity Summary</a></li>
          
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageEnquiry.php" rel="editEnquiry.php"><i class="fa fa-circle-o"></i>Lead Summary</a></li>
          <!--<li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageQuote.php" rel="editEnquiry.php"><i class="fa fa-circle-o"></i>Quote</a></li>-->
          
          <!------------->
        </ul>

      <li class="treeview"> <a href="#"> <i class="fa fa-dollar"></i> <span>Rates</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
        <ul class="treeview-menu">
          <!-- sub menu-->
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/rateQuery.php" rel="editCompany.php"><i class="fa fa-circle-o"></i>View Rates</a></li>
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageRateLetters.php" rel="editRateLetters.php"><i class="fa fa-circle-o"></i>Rate Letters - RSO</a></li>
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageRateLettersUnit.php" rel="editRateLettersUnit.php"><i class="fa fa-circle-o"></i>Rate Letters - Unit</a></li>
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageHotelwiseContractReport.php" rel="editHotelwiseContractReport.php"><i class="fa fa-circle-o"></i>Hotelwise Contract Report</a></li>
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
          <!------------->
        </ul>
      </li>
    <?php //if(selectColumn(TBL_USERS,'user_type','WHERE id='.$_SESSION['userId'].'')!=2){ 
              
            ?>
      <li class="treeview"> <a href="#"> <i class="fa fa-file-excel-o"></i> <span>Performance Reports</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
        <ul class="treeview-menu">
          <!-- sub menu-->
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/executivePortfolioYearly.php" rel="executivePortfolioYearly.php"><i class="fa fa-circle-o"></i>Yearly Portfolio - RSO</a></li>
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/executivePortfolioMonthly.php" rel="executivePortfolioMonthly.php"><i class="fa fa-circle-o"></i>Monthly Portfolio - RSO</a></li>

          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/unitExecutivePortfolioYearly.php" rel="unitExecutivePortfolioYearly.php"><i class="fa fa-circle-o"></i>Yearly Portfolio - Unit</a></li>
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/unitExecutivePortfolioMonthly.php" rel="unitExecutivePortfolioMonthly.php"><i class="fa fa-circle-o"></i> Monthly Portfolio - Unit</a></li>
           
           <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageAgentBudget.php" rel="editAgentBudget.php"><i class="fa fa-circle-o"></i>Company Yearly Budget</a></li>
           <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageAgentAchieved.php" rel="editAgentBudget.php"><i class="fa fa-circle-o"></i>Company Monthly Achievement</a></li>
          
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/PerformanceAnalysisReport.php" rel="PerformanceAnalysisReport.php"><i class="fa fa-circle-o"></i> Performance Analysis Report </a></li> 
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
      <?php if($_SESSION['userLevel']==1){ ?>
      <li class="treeview"> <a href="#"> <i class="fa fa-file-excel-o"></i> <span>Admin Reports</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
        <ul class="treeview-menu">
          <!-- sub menu-->
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/executiveLogReport.php" rel="executiveLogReport.php"><i class="fa fa-circle-o"></i>Activity Log Report</a></li>
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/companyAdditionReport.php" rel="companyAdditionReport.php"><i class="fa fa-circle-o"></i>Company Addition Report</a></li>
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/ManagerLoginHistoryReport.php" rel="ManagerLoginHistoryReport.php"><i class="fa fa-circle-o"></i> Login Report </a></li>
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/executiveMtdYtd.php" rel="executiveMtdYtd.php.php"><i class="fa fa-circle-o"></i>Sales Summary Report</a></li>
          
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
      



      <?php if($_SESSION['userLevel']==1){ ?>
      <!--Masters-->
      <li class="treeview"> <a href="#"> <i class="fa fa-bars"></i> <span>Masters</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
        <ul class="treeview-menu">
           <li class="treeview"><a href="#" rel=""><i class="fa fa-circle-o"></i><span>Admin Master</span><span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span></a>
            <ul class="treeview-menu">
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageAutoEmail.php" rel="editAutoEmail.php"><i class="fa fa-circle-o"></i> Auto Email Master</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/managebudgetyear.php" rel="editbudgetyear.php"><i class="fa fa-circle-o"></i>Budget Year Master</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageCompanyAreas.php" rel="editCompanyAreas.php"><i class="fa fa-circle-o"></i>Company Domain Master</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageZonal.php" rel="editZonal.php"><i class="fa fa-circle-o"></i> Zone Master</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageDocumentConfig.php" rel="editDocumentConfig.php"><i class="fa fa-circle-o"></i> Document Master</a></li>
              
          </ul>
           </li>
          
          <li class="treeview"> <a href="#"> <i class="fa fa-circle-o"></i> <span>Attributes</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
            <ul class="treeview-menu">
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageInHouseActivity.php" rel="editInHouseActivity.php"><i class="fa fa-circle-o"></i> Activity Master</a></li>
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageDesignation.php" rel="editDesignation.php"><i class="fa fa-circle-o"></i> Designation Master</a></li>
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageCloseMaster.php" rel="editCloseMaster.php"><i class="fa fa-circle-o"></i> Lead Close Master</a></li>
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageTravelMaster.php" rel="editTravelMaster.php"><i class="fa fa-circle-o"></i> Travel Mode Master</a></li>
                
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

          <li class="treeview"><a href="#" rel=""><i class="fa fa-circle-o"></i><span>Hotel Master</span><span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span></a>
            <ul class="treeview-menu">
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageHotels.php" rel="editHotels.php" ><i class="fa fa-circle-o"></i>Hotels Manager</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageHotelTypes.php" rel="editHotelTypes.php"><i class="fa fa-circle-o"></i>Hotel Category Master</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageRoomTypes.php" rel="editRoomTypes.php"><i class="fa fa-circle-o"></i>Rooms Category Master</a></li>
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
            </ul>
          </li>   

          <!--<li class="treeview"><a href="#" rel=""><i class="fa fa-circle-o"></i><span>Productivity Master</span><span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span></a>
            <ul class="treeview-menu"> 
              
              
              
            </ul>
          </li> --> 
           
          
          <li class="treeview"><a href="#" rel=""><i class="fa fa-circle-o"></i><span>User Master</span><span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span></a>
            <ul class="treeview-menu"> 
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageUsers.php" rel="editUsers.php"><i class="fa fa-circle-o"></i>User Addition Master</a> </li>
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageUserPermissions.php" rel="editUserPermissions.php" ><i class="fa fa-circle-o"></i>User Permission Master</a> </li>
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageUserLevels.php" rel="editUserLevels.php"><i class="fa fa-circle-o"></i>Level Master</a></li>
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageTeam.php" rel="editTeam.php"><i class="fa fa-circle-o"></i>Team Master</a></li>
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageShop.php" rel="editShop.php"><i class="fa fa-circle-o"></i> Shop Master</a></li>
            </ul>
          </li>   
          
        </ul>
      </li><!--master end-->
    <?php } ?>
      
      <!---------->
      
      
      
         
    </ul>
  </section>
  <!-- /.sidebar --> </aside>
<?php /*?> <h3 class="headerbar">Menus</h3>		<ul class="submenu">		<?php $sqlModules = "  SELECT * FROM `fs_user_permissions`						WHERE status=1 and user_level_id='".$_SESSION['userLevel']."' and FIND_IN_SET(1,user_actions)";									$resModules = executeSql($sqlModules);			if(@mysql_num_rows($resModules)> 0){$counter = 1;			while($rowModules = @mysql_fetch_object($resModules)){ 						echo '<li><a href="'.selectColumn(fs_modules,'page_name'," WHERE `id` = '".$rowModules->module_id."'").'" style="cursor:pointer;">¤ '.selectColumn(fs_modules,'name'," WHERE `id` = '".$rowModules->module_id."'").'</a>  </li>';									}} ?>		</ul><?php */ ?>
