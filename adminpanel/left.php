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
      
      
      
      <li class="treeview"> <a href="#"> <i class="fa fa-laptop"></i> <span>Sales</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
        <ul class="treeview-menu">
          <!-- sub menu-->
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/editDailyReport.php" rel="editCompany.php"><i class="fa fa-circle-o"></i> Dashboard</a></li>
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageCompany.php" rel="editCompany.php"><i class="fa fa-circle-o"></i>Company Master</a></li>

          <!------------->
        </ul>
      </li>

      <li class="treeview"> <a href="#"> <i class="fa fa-dollar"></i> <span>Rates</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
        <ul class="treeview-menu">
          <!-- sub menu-->
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/rateQuery.php" rel="editCompany.php"><i class="fa fa-circle-o"></i>View Rate</a></li>
          <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageRateLetters.php" rel="editRateLetters.php"><i class="fa fa-circle-o"></i> Rate Letters </a></li>
          
          <!------------->
        </ul>
      </li>

      <li class="treeview"> <a href="#"> <i class="fa fa-file-excel-o"></i> <span>Reports</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
        <ul class="treeview-menu">
          <li class="treeview"> <a href="#"> <i class="fa fa-file-excel-o"></i> <span>Sales Report</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
            <ul class="treeview-menu">
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/ManagervisitReport.php" rel="editCompany.php"><i class="fa fa-circle-o"></i>Daily Sales Report</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/ManagerFollowupReport.php" rel="ManagerFollowupReport.php"><i class="fa fa-circle-o"></i> Follow up</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/ManagerFeedBackReport.php" rel="ManagerFeedBackReport.php"><i class="fa fa-circle-o"></i> Feed Back</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageConveyance.php" rel="manageConveyance.php"><i class="fa fa-circle-o"></i>Conveyance Report</a></li>
            <li><a href="<?php echo $SITE_URL; ?>/adminpanel/companyPortfolio.php" rel="companyPortfolio.php.php"><i class="fa fa-circle-o"></i>Company Portfolio</a></li>
            <li><a href="<?php echo $SITE_URL; ?>/adminpanel/executiveMtdYtd.php" rel="executiveMtdYtd.php.php"><i class="fa fa-circle-o"></i>Executive Wise Report</a></li>
            </ul>
          </li>

          <li class="treeview"> <a href="#"> <i class="fa fa-file-excel-o"></i> <span>Rate Report</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
            <ul class="treeview-menu">
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageHotelwiseContractReport.php" rel="editHotelwiseContractReport.php"><i class="fa fa-circle-o"></i>Hotelwise Contract Report</a></li>
            </ul>
          </li>
        </ul>
      </li>
      




      <!--Masters-->
      <li class="treeview"> <a href="#"> <i class="fa fa-bars"></i> <span>Masters</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
        <ul class="treeview-menu">

          <li class="treeview"><a href="#" rel=""><i class="fa fa-circle-o"></i><span>Hotel Master</span><span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span></a>
            <ul class="treeview-menu">
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageHotels.php" rel="editHotels.php" ><i class="fa fa-circle-o"></i>Hotels Manager</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageHotelTypes.php" rel="editHotelTypes.php"><i class="fa fa-circle-o"></i>Hotel Category Master</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageRoomTypes.php" rel="editRoomTypes.php"><i class="fa fa-circle-o"></i>Rooms Type Master</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageGeneralServices.php" rel="editGeneralServices.php"><i class="fa fa-circle-o"></i>General Services Master</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageOutdoorActivities.php" rel="editOutdoorActivities.php"><i class="fa fa-circle-o"></i>Outdoor Activities Master</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageDiningServices.php" rel="editDiningServices.php" ><i class="fa fa-circle-o"></i>Dining Services Master</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageHotelServices.php" rel="editHotelServices.php"><i class="fa fa-circle-o"></i>Hotel Services Master</a></li>
                  
            </ul>
          </li>

          <li class="treeview"><a href="#" rel=""><i class="fa fa-circle-o"></i><span>Company Master</span><span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span></a>
            <ul class="treeview-menu">
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageCompanyCustomer.php" rel="editCustomer.php"><i class="fa fa-circle-o"></i>Contacts Master</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageCompanyGroups.php" rel="editCompanyGroups.php"><i class="fa fa-circle-o"></i>Company Groups Master</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageCompanyAreas.php" rel="editCompanyAreas.php"><i class="fa fa-circle-o"></i>Company Domains Master</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageAreas.php" rel="editAreas.php"><i class="fa fa-circle-o"></i>Area User Master</a></li>
           </ul>
          </li> 

          <li class="treeview"><a href="#" rel=""><i class="fa fa-circle-o"></i><span>Rate Master</span><span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span></a>
            <ul class="treeview-menu">
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageRateMaster.php" rel="editRateMaster.php"><i class="fa fa-circle-o"></i> Rate Master</a></li>          
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageRateLevel.php" rel="editRateLevel.php"><i class="fa fa-circle-o"></i> Rate Level Master</a></li>
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageRatePlan.php" rel="editRatePlan.php"><i class="fa fa-circle-o"></i>Rate Plan Master</a></li>
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageRateSeason.php" rel="editRateSeason.php"><i class="fa fa-circle-o"></i>Rate Season Master</a></li>
                  
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageRateMarket.php" rel="editRateMarket.php"><i class="fa fa-circle-o"></i>Rate Market Master</a></li>
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageRatePoints.php" rel="editRatePoints.php"><i class="fa fa-circle-o"></i>Rate Point Master</a></li>
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageGeneralTerms.php" rel="editGeneralTerms.php"><i class="fa fa-circle-o"></i>General Terms Master</a></li>
            </ul>
          </li>   

          <li class="treeview"><a href="#" rel=""><i class="fa fa-circle-o"></i><span>Productivity Master</span><span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span></a>
            <ul class="treeview-menu"> 
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageAgentBudget.php" rel="editAgentBudget.php"><i class="fa fa-circle-o"></i>Budget Master</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageAgentAchieved.php" rel="editAgentBudget.php"><i class="fa fa-circle-o"></i>Monthly Achievement</a></li>
              <li><a href="<?php echo $SITE_URL; ?>/adminpanel/managebudgetyear.php" rel="editbudgetyear.php"><i class="fa fa-circle-o"></i>Budget Year Master</a></li>
            </ul>
          </li>  
           <li class="treeview"> <a href="#"> <i class="fa fa-circle-o"></i> <span>Attributes</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
        <ul class="treeview-menu">
         
            <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageDesignation.php" rel="editDesignation.php"><i class="fa fa-circle-o"></i> Designation Master</a></li>
              

            <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageAutoEmail.php" rel="editAutoEmail.php"><i class="fa fa-circle-o"></i> Auto Email Master</a></li>
            <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageZonal.php" rel="editZonal.php"><i class="fa fa-circle-o"></i> Zone Master</a></li>
            <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageState.php" rel="editState.php"><i class="fa fa-circle-o"></i> State Master</a></li>
        </ul>
      </li> 
          <?php if( $_SESSION['userLevel'] =='1'){ ?>
          <li class="treeview"><a href="#" rel=""><i class="fa fa-circle-o"></i><span>User Master</span><span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span></a>
            <ul class="treeview-menu"> 
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageShop.php" rel="editShop.php"><i class="fa fa-circle-o"></i> Shop Master</a></li>
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageUserLevels.php" rel="editUserLevels.php"><i class="fa fa-circle-o"></i>User Levels Master</a></li>
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageUserPermissions.php" rel="editUserPermissions.php" ><i class="fa fa-circle-o"></i>User Permissions Master</a> </li>
                <li><a href="<?php echo $SITE_URL; ?>/adminpanel/manageUsers.php" rel="editUsers.php"><i class="fa fa-circle-o"></i>User Master</a> </li>
               
            </ul>
          </li>   
          <?php }?>
        </ul>
      </li>
      
      <!---------->
      
      
      
         
    </ul>
  </section>
  <!-- /.sidebar --> </aside>
<?php /*?> <h3 class="headerbar">Menus</h3>		<ul class="submenu">		<?php $sqlModules = "  SELECT * FROM `fs_user_permissions`						WHERE status=1 and user_level_id='".$_SESSION['userLevel']."' and FIND_IN_SET(1,user_actions)";									$resModules = executeSql($sqlModules);			if(@mysqli_num_rows($resModules)> 0){$counter = 1;			while($rowModules = @mysqli_fetch_object($resModules)){ 						echo '<li><a href="'.selectColumn(fs_modules,'page_name'," WHERE `id` = '".$rowModules->module_id."'").'" style="cursor:pointer;">? '.selectColumn(fs_modules,'name'," WHERE `id` = '".$rowModules->module_id."'").'</a>  </li>';									}} ?>		</ul><?php */ ?>
