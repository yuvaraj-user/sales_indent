<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">
<?php $activePage = basename($_SERVER['PHP_SELF'], ".php"); ?>
<!-- LOGO -->
<div class="navbar-brand-box">
    <a href="index.php" class="logo logo-dark mt-3">
        <span class="logo-sm mt-3">
            <img  src="../global/photos/VijayRasiSeedsLogo.png" alt="" height="35">
        </span>
        <span class="logo-lg ms-5">
            <img src="../global/photos/logo.png" alt="" height="40" width="100">
        </span>
    </a> 

<!--     <a href="index.html" class="logo logo-light">
        <span class="logo-lg">
            <img src="../global/photos/logo.png" alt="" height="40" width="100">
        </span>
        <span class="logo-sm mt-3">
            <img  src="../global/photos/VijayRasiSeedsLogo.png" alt="" height="35">
        </span>
    </a> -->
</div>

<!-- <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect vertical-menu-btn">
    <i class="bx bx-menu align-middle"></i>
</button> -->

<div data-simplebar class="sidebar-menu-scroll">

    <!--- Sidemenu -->
    <div id="sidebar-menu">
        <!-- Left Menu Start -->
        <ul class="metismenu list-unstyled" id="side-menu">
            <li class="menu-title text-white" data-key="t-menu">Dashboards</li>

            <li <?php if($activePage == 'index' || $activePage == '') { ?> class="mm-active" <?php }?>>
                <a href="index.php" >
                    <i class="bx bx-home-alt icon font-size-14"></i>
                    <span class="menu-item font-size-13" data-key="t-dashboard">Indent Dashboard</span>
                    <!-- <span class="badge rounded-pill bg-primary">2</span> -->
                </a>
               <!-- <ul class="sub-menu" aria-expanded="false">
                    <li><a href="index.html" data-key="t-ecommerce">Ecommerce</a></li>
                    <li><a href="dashboard-sales.html" data-key="t-sales">Sales</a></li>
                </ul> -->
            </li> 

            <li <?php if($activePage == 'Sto_Dashboard'){ ?> class="mm-active" <?php } ?>>
                <a href="Sto_Dashboard.php">
                    <i class="fas fa-tachometer-alt font-size-14"></i>
                    <span class="menu-item font-size-13" data-key="t-ecommerce">STO Dashboard</span>
                </a>
            </li>

            <li class="menu-title text-white" data-key="t-applications">Creations</li>
            <?php if(@$requeter_menu >0) {  ?>
            <!-- <li>
                <a href="Indent_creation.php">
                    <i class="bx bx-store icon nav-icon"></i>
                    <span class="menu-item font-size-13" data-key="t-email">New Indent Creation</span>
                </a>
            </li> -->


            <li>
                <a href="javascript: void(0);" class="has-arrow">
                    <i class="bx bx-store icon font-size-14"></i>
                    <span class="menu-item font-size-13" data-key="t-ecommerce">Sales Indent</span>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a href="Indent_creation.php">
                            <span class="menu-item font-size-13 font-size-13">Indent Creation</span>
                        </a>
                    </li>
                    <li>
                        <a href="Indent_update.php">
                            <span class="menu-item font-size-13 font-size-13">Indent Modification</span>
                        </a>
                    </li>
                    <li>
                        <a href="Sales_indent_report.php">
                            <span class="menu-item font-size-13 font-size-13">Indent Report</span>
                        </a>
                    </li>
                </ul>
            </li>
            <?php } ?>

        <?php if($Recommend_Menu >0 ){ ?>
        <li <?php if($activePage == 'Sales_Indent_Approver_Dashboard' || $activePage == 'Approve_Sales_Indent_Report'){ ?> class="mm-active" <?php } ?>>
            <?php if($indent_Approve_Count_wilimit > 0) { ?>
            <span class="badge rounded-pill bg-danger" style="position: absolute;z-index: 99999999;display: block !important;left: 38px;"><?php echo $indent_Approve_Count_wilimit; ?></span>
            <?php } ?>
          <a href="Sales_Indent_Approver_Dashboard.php">
            <i class="bx bx-check-shield font-size-14"></i>
            <span class="menu-item font-size-13 sm-module-sidebar" data-key="t-ecommerce" >Indent Approve</span><br>
            <span style="margin-left: 60px;" class="sm-module-sidebar">(With In Limit)</span>

            <span class="menu-item font-size-13 lg-module-sidebar dis-none" data-key="t-ecommerce">Indent Approve (With In Limit)</span>
          </a>
        </li>
        <li <?php if($activePage == 'Sales_Indent_Recommendation' || $activePage == 'Sales_Indent_Recommendation_Dashboard'){ ?> class="mm-active" <?php } ?>>
            <?php if($indent_Recommedation_Count_lexceed > 0) { ?>
            <span class="badge rounded-pill bg-danger" style="position: absolute;z-index: 99999999;display: block !important;left: 38px;"><?php echo $indent_Recommedation_Count_lexceed; ?></span>
            <?php } ?>
          <a href="Sales_Indent_Recommendation_Dashboard.php">
            <i class="bx bx-analyse font-size-14"></i>
            <span class="menu-item font-size-13 sm-module-sidebar ps-0" data-key="t-ecommerce" >Indent Recommendation</span><br>
            <span style="margin-left: 60px;" class="sm-module-sidebar">(Limit Exceeds)</span>

            <span class="menu-item font-size-13 lg-module-sidebar dis-none" data-key="t-ecommerce">Indent Recommendation (Limit Exceeds)</span>
          </a>
        </li>
        <?php }  ?>

        <?php if($Validate_Menu >0 ){ ?>
        <!-- <li>
            <?php if($Validation_With_In_Limit_Count > 0) { ?>
            <span class="badge rounded-pill bg-danger" style="position: absolute;z-index: 99999999;display: block !important;left: 38px;"><?php echo $Validation_With_In_Limit_Count; ?></span>
            <?php } ?>
          <a href="Sales_Indent_Validation_With_In_limit_Dashboard.php">
            <i class="fas fa-chart-pie font-size-22"></i>
            <span class="menu-item font-size-13 sm-module-sidebar" data-key="t-ecommerce" >Indent Validation</span><br>
            <span style="margin-left: 55px;" class="sm-module-sidebar">(With In Limit)</span>

            <span class="menu-item font-size-13 lg-module-sidebar dis-none" data-key="t-ecommerce">Indent Validation (With In Limit)</span>
          </a>
        </li> -->

        <li>
            <?php if($indent_approve_direct_withinlimit > 0) { ?>
            <span class="badge rounded-pill bg-danger" style="position: absolute;z-index: 99999999;display: block !important;left: 38px;"><?php echo $indent_approve_direct_withinlimit; ?></span>
            <?php } ?>
          <a href="Approve_Sales_Indent_Report_Direct.php">
            <i class="fas fa-chart-pie font-size-14"></i>
            <span class="menu-item font-size-13 sm-module-sidebar" data-key="t-ecommerce" >Indent Approve</span><br>
            <span style="margin-left: 55px;" class="sm-module-sidebar">(Direct) (With In Limit)</span>

            <span class="menu-item font-size-13 lg-module-sidebar dis-none" data-key="t-ecommerce">Indent Approve (Direct) (With In Limit)</span>
          </a>
        </li>

        <li <?php if($activePage == 'Sales_Indent_Validation_Limit_Exceed_Dashboard' || $activePage == 'Sales_Indent_Validation'){ ?> class="mm-active" <?php } ?>>
            <?php if($indent_Validation_Limit_Exceed_Count > 0) { ?>
            <span class="badge rounded-pill bg-danger" style="position: absolute;z-index: 99999999;display: block !important;left: 38px;"><?php echo $indent_Validation_Limit_Exceed_Count; ?></span>
            <?php } ?>
          <a href="Sales_Indent_Validation_Limit_Exceed_Dashboard.php">
            <i class="fas fa-shield-alt font-size-14"></i>
            <span class="menu-item font-size-13 sm-module-sidebar" data-key="t-ecommerce" >Indent Validation</span><br>
            <span style="margin-left: 55px;" class="sm-module-sidebar">(Limit Exceeds)</span>

            <span class="menu-item font-size-13 lg-module-sidebar dis-none" data-key="t-ecommerce">Indent Validation (Limit Exceeds)</span>
          </a>
        </li>
        <?php } ?>

        <?php if($Approve_limit_Exceed_Menu >0 ){ ?>
        <li <?php if($activePage == 'Sales_Indent_Approve_Limit_Exceed_Dashboard' || $activePage == 'Sales_Indent_Approve_for_Limit_Exceed'){ ?> class="mm-active" <?php } ?>>
            <?php if($indent_Approve_Count_Limit_Exceed_Count > 0) { ?>
            <span class="badge rounded-pill bg-danger" style="position: absolute;z-index: 99999999;display: block !important;left: 38px;"><?php echo $indent_Approve_Count_Limit_Exceed_Count; ?></span>
            <?php } ?>
          <a href="Sales_Indent_Approve_Limit_Exceed_Dashboard.php">
            <i class="fas fa-check-circle font-size-14"></i>
            <span class="menu-item font-size-13 sm-module-sidebar" data-key="t-ecommerce" >Indent Approve</span><br>
            <span style="margin-left: 55px;" class="sm-module-sidebar">(Limit Exceeds)</span>

            <span class="menu-item font-size-13 lg-module-sidebar dis-none" data-key="t-ecommerce">Indent Approve (Limit Exceeds)</span>
          </a>
        </li>
        <?php } ?>

        <?php if($Requestor_Menu_For_Sto_Indent >0 ){ ?>

        <li <?php if($activePage == 'STOindex' || $activePage == 'Sales_indent_Sto_Modification' || $activePage == 'Sales_indent_Sto_Report'){ ?> class="mm-active" <?php } ?>>
            <a href="javascript: void(0);" class="has-arrow">
                <i class="fas fa-bahai font-size-14"></i>
                <span class="menu-item font-size-13" data-key="t-ecommerce">STO</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li>
                    <a href="STOindex.php" data-key="t-products">
                        <span class="menu-item font-size-13 font-size-13" data-key="t-dashboard">STO Creation</span>
                    </a>
                </li>
                <li>
                    <a href="Sales_indent_Sto_Modification.php" data-key="t-product-detail">
                        <span class="menu-item font-size-13 font-size-13" data-key="t-dashboard">STO Modification</span>
                    </a>
                </li>
                <li>
                    <a href="Sales_indent_Sto_Report.php" data-key="t-product-detail">
                        <span class="menu-item font-size-13 font-size-13" data-key="t-dashboard">STO Report</span>
                    </a>
                </li>
            </ul>
        </li>
        <?php } ?>

        <?php if($Approver_For_Sto_Indent >0 ){ ?>

        <li <?php if($activePage == 'Sto_Dashboard_Validate_With_Limit' || $activePage == 'Sto_Validate_Sales_Indent_Report_With_Limit'){ ?> class="mm-active" <?php } ?>>
            <?php if($Sto_validate_Count_With_In_Limit > 0) { ?>
            <span class="badge rounded-pill bg-danger" style="position: absolute;z-index: 99999999;display: block !important;left: 38px;"><?php echo $Sto_validate_Count_With_In_Limit; ?></span>
            <?php } ?>
           <a href="Sto_Dashboard_Validate_With_Limit.php">
            <i class="fas fa-money-check font-size-14" aria-hidden="true" ></i>
            <span class="menu-item font-size-13 sm-module-sidebar" data-key="t-ecommerce" >STO Validate</span><br>
            <span style="margin-left: 55px;" class="sm-module-sidebar">(With In Limit)</span>

            <span class="menu-item font-size-13 lg-module-sidebar dis-none" data-key="t-ecommerce">STO Validate (With In Limit)</span>
           </a>
        </li>


        <li <?php if($activePage == 'Sto_Dashboard_Recommend_Limit_Exceed' || $activePage == 'Sto_Recommend_Sales_Indent_Report'){ ?> class="mm-active" <?php } ?>>
            <?php if($Sto_Recommendation_Count_Limit_Exceed > 0) { ?>
              <span class="badge rounded-pill bg-danger" style="position: absolute;z-index: 99999999;display: block !important;left: 38px;"><?php echo $Sto_Recommendation_Count_Limit_Exceed; ?></span>
            <?php } ?>
            <a href="Sto_Dashboard_Recommend_Limit_Exceed.php">
              <i class="fas fa-receipt font-size-14" aria-hidden="true" ></i>
              <span class="menu-item font-size-13 sm-module-sidebar ps-0" data-key="t-ecommerce" >STO Recommendation</span><br>
              <span style="margin-left: 55px;" class="sm-module-sidebar">(Limit Exceed)</span>

              <span class="menu-item font-size-13 lg-module-sidebar dis-none" data-key="t-ecommerce">STO Recommendation (Limit Exceed)</span>
            </a>
        </li>

       <?php } ?>

       <?php if($Validate_For_Sto_Indent >0 ){ ?>
          
        <li <?php if($activePage == 'Sto_Dashboard_Approve' || $activePage == 'Sto_Approve_Sales_Indent_Report'){ ?> class="mm-active" <?php } ?>>
          <?php if($Sto_Approve_Count_With_In_Limit > 0) { ?>
             <span class="badge rounded-pill bg-danger" style="position: absolute;z-index: 99999999;display: block !important;left: 38px;"><?php echo $Sto_Approve_Count_With_In_Limit; ?></span>
         <?php } ?>
          <a href="Sto_Dashboard_Approve.php">
               <i class="fas fa-ribbon font-size-14" aria-hidden="true" ></i>
               <span class="menu-item font-size-13 sm-module-sidebar" data-key="t-ecommerce" >STO Approve</span><br>
              <span style="margin-left: 55px;" class="sm-module-sidebar">(With In Limit)</span>

              <span class="menu-item font-size-13 lg-module-sidebar dis-none" data-key="t-ecommerce">STO Approve (With In Limit)</span>
          </a>
        </li>

          <li <?php if($activePage == 'Sto_Dashboard_Validate_Limit_Exceed' || $activePage == 'Sto_Validate_Sales_Indent_Report_limit_Exceed'){ ?> class="mm-active" <?php } ?>>
              <?php if($sto_validate_Count_Limit_Exceed > 0) { ?>
                 <span class="badge rounded-pill bg-danger" style="position: absolute;z-index: 99999999;display: block !important;left: 38px;"><?php echo $sto_validate_Count_Limit_Exceed; ?></span> 
             <?php } ?>
            <a href="Sto_Dashboard_Validate_Limit_Exceed.php">
              <i class="fas fa-search-minus font-size-14" aria-hidden="true" ></i>
              <span class="menu-item font-size-13 sm-module-sidebar" data-key="t-ecommerce" >STO Validation</span><br>
              <span style="margin-left: 55px;" class="sm-module-sidebar">(Limit Exceed)</span>

              <span class="menu-item font-size-13 lg-module-sidebar dis-none" data-key="t-ecommerce">STO Validation (Limit Exceed)</span>
            </a>
        </li>


       <?php } ?>

        <?php  if($Approver_Menu_Limit_Exceed_For_Sto_Indent >0 ){ ?>
          <li <?php if($activePage == 'Sto_Dashboard_Approve_Limit_Exceed' || $activePage == 'Sto_Approve_Limit_Exceed_Sales_Indent_Report'){ ?> class="mm-active" <?php } ?>>
            <?php if($sto_Approve_Count_Limit_Exceed > 0) { ?>
              <span class="badge rounded-pill bg-danger" style="position: absolute;z-index: 99999999;display: block !important;left: 38px;"><?php echo $sto_Approve_Count_Limit_Exceed; ?></span>
            <?php } ?> 
            <a href="Sto_Dashboard_Approve_Limit_Exceed.php">
              <i class="fas fa-tasks font-size-14" aria-hidden="true" ></i>
             <span class="menu-item font-size-13 sm-module-sidebar" data-key="t-ecommerce" >STO Approve</span><br>
              <span style="margin-left: 55px;" class="sm-module-sidebar">(Limit Exceed)</span>

              <span class="menu-item font-size-13 lg-module-sidebar dis-none" data-key="t-ecommerce">STO Approve (Limit Exceed)</span>
            </a>
          </li>

        <?php } ?>

        <?php if($_SESSION['Dcode'] =='TM'){ ?>
        <li class="menu-title text-white" data-key="t-menu">Reports</li>
      
        <li>
            <a href="SalesIntentSummaryReport.php">
                <i class="far fa-file-alt font-size-14" aria-hidden="true" ></i>
                <span class="menu-item font-size-13 sm-module-sidebar" data-key="t-ecommerce" >Indent Vs Actual</span><br>
                <span class="menu-item font-size-13 lg-module-sidebar dis-none" data-key="t-ecommerce">Indent Vs Actual</span>
            </a>
        </li>
       
          
        <?php } else if($_SESSION['Dcode'] =='RBM' || $_SESSION['Dcode'] =='DBM' || $_SESSION['Dcode'] =='ZM'){ ?>
        <li class="menu-title text-white" data-key="t-menu">Reports</li>

    
        <li>
            <a href="SalesIntentSummaryReport.php">
                <i class="far fa-file-alt font-size-14" aria-hidden="true" ></i>
                <span class="menu-item font-size-13 sm-module-sidebar" data-key="t-ecommerce" >Indent Vs Actual</span><br>
                <span class="menu-item font-size-13 lg-module-sidebar dis-none" data-key="t-ecommerce">Indent Vs Actual</span>

            </a>
        </li>

        <li>
            <a href="STOSummaryReport.php">
                <i class="far fa-file font-size-14" aria-hidden="true" ></i>
                <span class="menu-item sm-module-sidebar" data-key="t-ecommerce" >STO VS Actual</span><br>
                <span class="menu-item lg-module-sidebar dis-none" data-key="t-ecommerce">STO VS Actual</span>
            </a>
        </li>

        <?php } else if($_SESSION['Dcode'] =='GM'){ ?>
        <li class="menu-title text-white" data-key="t-menu">Reports</li>
         
        <li>
            <a href="SalesIntentSummaryReport.php">
                <i class="far fa-file-alt font-size-14" aria-hidden="true" ></i>
                <span class="menu-item sm-module-sidebar" data-key="t-ecommerce" >Indent Vs Actual</span><br>
                <span class="menu-item lg-module-sidebar dis-none" data-key="t-ecommerce">Indent Vs Actual</span>

                <!-- <span>Indent Vs Actual</span> -->
            </a>
        </li>

        <li>
            <a href="STOSummaryReport.php">
                <i class="far fa-file font-size-14" aria-hidden="true" ></i>
                <span class="menu-item sm-module-sidebar" data-key="t-ecommerce" >STO VS Actual</span><br>
                <span class="menu-item lg-module-sidebar dis-none" data-key="t-ecommerce">STO VS Actual</span>
            </a>
        </li>
        
        <?php } ?>

<!-- 
            <li>
                <a href="apps-calendar.html">
                    <i class="bx bx-calendar-event icon nav-icon"></i>
                    <span class="menu-item" data-key="t-calendar">Calendar</span>
                </a>
            </li>

            <li>
                <a href="apps-todo.html">
                    <i class="bx bx-check-square icon nav-icon"></i>
                    <span class="menu-item" data-key="t-todo">Todo</span>
                </a>
            </li>

            <li>
                <a href="apps-file-manager.html">
                    <i class="bx bx-file-find icon nav-icon"></i>
                    <span class="menu-item" data-key="t-filemanager">File Manager</span>
                </a>
            </li>

            <li>
                <a href="apps-chat.html">
                    <i class="bx bx-chat icon nav-icon"></i>
                    <span class="menu-item" data-key="t-chat">Chat</span>
                    <span class="badge rounded-pill bg-danger" data-key="t-hot">Hot</span>
                </a>
            </li>

            <li>
                <a href="javascript: void(0);" class="has-arrow">
                    <i class="bx bx-store icon nav-icon"></i>
                    <span class="menu-item" data-key="t-ecommerce">Ecommerce</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="ecommerce-products.html" data-key="t-products">Products</a></li>
                    <li><a href="ecommerce-product-detail.html" data-key="t-product-detail">Product Detail</a></li>
                    <li><a href="ecommerce-orders.html" data-key="t-orders">Orders</a></li>
                    <li><a href="ecommerce-customers.html" data-key="t-customers">Customers</a></li>
                    <li><a href="ecommerce-cart.html" data-key="t-cart">Cart</a></li>
                    <li><a href="ecommerce-checkout.html" data-key="t-checkout">Checkout</a></li>
                    <li><a href="ecommerce-shops.html" data-key="t-shops">Shops</a></li>
                    <li><a href="ecommerce-add-product.html" data-key="t-add-product">Add Product</a></li>
                </ul>
            </li>



            <li>
                <a href="javascript: void(0);" class="has-arrow">
                    <i class="bx bx-receipt icon nav-icon"></i>
                    <span class="menu-item" data-key="t-invoices">Invoices</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="invoices-list.html" data-key="t-invoice-list">Invoice List</a></li>
                    <li><a href="invoices-detail.html" data-key="t-invoice-detail">Invoice Detail</a></li>
                </ul>
            </li>

            <li>
                <a href="javascript: void(0);" class="has-arrow">
                    <i class="bx bx-user-circle icon nav-icon"></i>
                    <span class="menu-item" data-key="t-contacts">Contacts</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="contacts-grid.html" data-key="t-user-grid">User Grid</a></li>
                    <li><a href="contacts-list.html" data-key="t-user-list">User List</a></li>
                    <li><a href="contacts-profile.html" data-key="t-user-profile">Profile</a></li>
                </ul>
            </li>

            <li class="menu-title" data-key="t-layouts">Layouts</li>

            <li>
                <a href="layouts-horizontal.html">
                    <i class="bx bx-layout icon nav-icon"></i>
                    <span class="menu-item" data-key="t-horizontal">Horizontal</span>
                </a>
            </li>

            <li class="menu-title" data-key="t-components">Components</li>

            <li>
                <a href="javascript: void(0);" class="has-arrow">
                    <i class="bx bx-cube icon nav-icon"></i>
                    <span class="menu-item" data-key="t-ui-elements">UI Elements</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="ui-alerts.html" data-key="t-alerts">Alerts</a></li>
                    <li><a href="ui-buttons.html" data-key="t-buttons">Buttons</a></li>
                    <li><a href="ui-cards.html" data-key="t-cards">Cards</a></li>
                    <li><a href="ui-carousel.html" data-key="t-carousel">Carousel</a></li>
                    <li><a href="ui-dropdowns.html" data-key="t-dropdowns">Dropdowns</a></li>
                    <li><a href="ui-grid.html" data-key="t-grid">Grid</a></li>
                    <li><a href="ui-images.html" data-key="t-images">Images</a></li>
                    <li><a href="ui-lightbox.html" data-key="t-lightbox">Lightbox</a></li>
                    <li><a href="ui-modals.html" data-key="t-modals">Modals</a></li>
                    <li><a href="ui-offcanvas.html" data-key="t-offcanvas">Offcanvas</a></li>
                    <li><a href="ui-rangeslider.html" data-key="t-range-slider">Range Slider</a></li>
                    <li><a href="ui-progressbars.html" data-key="t-progress-bars">Progress Bars</a></li>
                    <li><a href="ui-sweet-alert.html" data-key="t-sweet-alert">Sweet-Alert</a></li>
                    <li><a href="ui-tabs-accordions.html" data-key="t-tabs-accordions">Tabs & Accordions</a></li>
                    <li><a href="ui-typography.html" data-key="t-typography">Typography</a></li>
                    <li><a href="ui-video.html" data-key="t-video">Video</a></li>
                    <li><a href="ui-general.html" data-key="t-general">General</a></li>
                    <li><a href="ui-colors.html" data-key="t-colors">Colors</a></li>
                    <li><a href="ui-rating.html" data-key="t-rating">Rating</a></li>
                    <li><a href="ui-notifications.html" data-key="t-notifications">Notifications</a></li>
                </ul>
            </li>

            <li>
                <a href="javascript: void(0);" class="has-arrow">
                    <i class="bx bx-layout icon nav-icon"></i>
                    <span class="menu-item" data-key="t-forms">Forms</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="form-elements.html" data-key="t-form-elements">Form Elements</a></li>
                    <li><a href="form-layouts.html" data-key="t-form-layouts">Form Layouts</a></li>
                    <li><a href="form-validation.html" data-key="t-form-validation">Form Validation</a></li>
                    <li><a href="form-advanced.html" data-key="t-form-advanced">Form Advanced</a></li>
                    <li><a href="form-editors.html" data-key="t-form-editors">Form Editors</a></li>
                    <li><a href="form-uploads.html" data-key="t-form-upload">Form File Upload</a></li>
                    <li><a href="form-wizard.html" data-key="t-form-wizard">Form Wizard</a></li>
                    <li><a href="form-mask.html" data-key="t-form-mask">Form Mask</a></li>
                </ul>
            </li>

            <li>
                <a href="javascript: void(0);" class="has-arrow">
                    <i class="bx bx-table icon nav-icon"></i>
                    <span class="menu-item" data-key="t-tables">Tables</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="tables-basic.html" data-key="t-basic-tables">Basic Tables</a></li>
                    <li><a href="tables-advanced.html" data-key="t-advanced-tables">Advance Tables</a></li>
                </ul>
            </li>

            <li>
                <a href="javascript: void(0);" class="has-arrow">
                    <i class="bx bx-pie-chart-alt-2 icon nav-icon"></i>
                    <span class="menu-item" data-key="t-charts">Charts</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="charts-apex.html" data-key="t-apex-charts">Apex Charts</a></li>
                    <li><a href="charts-chartjs.html" data-key="t-chartjs-charts">Chartjs Charts</a></li>
                    <li><a href="charts-tui.html" data-key="t-ui-charts">Toast UI Charts</a></li>
                </ul>
            </li>

            <li>
                <a href="javascript: void(0);" class="has-arrow">
                    <i class="bx bx-cuboid icon nav-icon"></i>
                    <span class="menu-item" data-key="t-icons">Icons</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="icons-evaicons.html" data-key="t-evaicons">Eva Icons</a></li>
                    <li><a href="icons-boxicons.html" data-key="t-boxicons">Boxicons</a></li>
                    <li><a href="icons-materialdesign.html" data-key="t-material-design">Material Design</a></li>
                    <li><a href="icons-fontawesome.html" data-key="t-font-awesome">Font Awesome 5</a></li>
                </ul>
            </li>

            <li>
                <a href="javascript: void(0);" class="has-arrow">
                    <i class="bx bx-map-alt icon nav-icon"></i>
                    <span class="menu-item" data-key="t-maps">Maps</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="maps-google.html" data-key="t-google">Google</a></li>
                    <li><a href="maps-vector.html" data-key="t-vector">Vector</a></li>
                    <li><a href="maps-leaflet.html" data-key="t-leaflet">Leaflet</a></li>
                </ul>
            </li>

            <li class="menu-title" data-key="t-pages">Pages</li>

            <li>
                <a href="javascript: void(0);">
                    <i class="bx bx-user-pin icon nav-icon"></i>
                    <span class="menu-item" data-key="t-authentication">Authentication</span>
                    <span class="badge rounded-pill bg-info">8</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="auth-login.html" data-key="t-login">Login</a></li>
                    <li><a href="auth-register.html" data-key="t-register">Register</a></li>
                    <li><a href="auth-recoverpw.html" data-key="t-recover-password">Recover Password</a></li>
                    <li><a href="auth-lock-screen.html" data-key="t-lock-screen">Lock Screen</a></li>
                    <li><a href="auth-logout.html" data-key="t-logout">Logout</a></li>
                    <li><a href="auth-confirm-mail.html" data-key="t-confirm-mail">Confirm Mail</a></li>
                    <li><a href="auth-email-verification.html" data-key="t-email-verification">Email Verification</a></li>
                    <li><a href="auth-two-step-verification.html" data-key="t-two-step-verification">Two Step Verification</a></li>
                </ul>
            </li>

            <li>
                <a href="javascript: void(0);" class="has-arrow">
                    <i class="bx bx-file icon nav-icon"></i>
                    <span class="menu-item" data-key="t-utility">Utility</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="pages-starter.html" data-key="t-starter-page">Starter Page</a></li>
                    <li><a href="pages-maintenance.html" data-key="t-maintenance">Maintenance</a></li>
                    <li><a href="pages-comingsoon.html" data-key="t-coming-soon">Coming Soon</a></li>
                    <li><a href="pages-timeline.html" data-key="t-timeline">Timeline</a></li>
                    <li><a href="pages-faqs.html" data-key="t-faqs">FAQs</a></li>
                    <li><a href="pages-pricing.html" data-key="t-pricing">Pricing</a></li>
                    <li><a href="pages-404.html" data-key="t-error-404">Error 404</a></li>
                    <li><a href="pages-500.html" data-key="t-error-500">Error 500</a></li>
                </ul>
            </li>

            <li>
                <a href="javascript: void(0);" class="has-arrow">
                    <i class="bx bx-share-alt icon nav-icon"></i>
                    <span class="menu-item" data-key="t-multi-level">Multi Level</span>
                </a>
                <ul class="sub-menu" aria-expanded="true">
                    <li class="disabled"><a href="#" data-key="t-disabled-item">Disabled Item</a></li>
                    <li><a href="javascript: void(0);" data-key="t-level-1.1">Level 1.1</a></li>
                    <li><a href="javascript: void(0);" class="has-arrow" data-key="t-level-1.2">Level 1.2</a>
                        <ul class="sub-menu" aria-expanded="true">
                            <li><a href="javascript: void(0);" data-key="t-level-2.1">Level 2.1</a></li>
                            <li><a href="javascript: void(0);" data-key="t-level-2.2">Level 2.2</a></li>
                        </ul>
                    </li>
                </ul>
            </li> -->

        </ul>
    </div>
    <!-- Sidebar -->
</div>
</div>
<!-- Left Sidebar End -->