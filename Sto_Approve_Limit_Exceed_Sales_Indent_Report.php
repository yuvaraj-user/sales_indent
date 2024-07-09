            <?php include('partials/head.php'); ?>
            <!-- <body data-layout="horizontal"> -->
                <?php 
                if(@$Approver_Menu_Limit_Exceed_For_Sto_Indent == 0) {
                    header('location: index.php');  
                }
                ?>

                <!-- Begin page -->
                <div id="layout-wrapper">
                    <?php include('partials/topbar.php'); ?>

                    <!-- ========== Left Sidebar Start ========== -->
                    <?php include('partials/sidebar.php'); ?>
                    <!-- Left Sidebar End -->
                    <?php include('partials/horizontal_header.php'); ?>

                    <?php 
                    include('Current_User_Session_Details.php');
                    $uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
                    $uri_segments = explode('/', $uri_path);
                    $SAP_Url=strtoupper(@$uri_segments[1])=="CORPORATE" ? "PRD" :"DEV";
                    $emp_id = $_SESSION['EmpID'];
                    $user_zone_id=$session_zone_id;
                      $user_region_id=$session_region_id;
                      $user_territory_id=$session_territory_id;
                      $user_product_division_array=$product_division_array;
                     $user_zone_array=explode(",",$user_zone_id);
                     $user_region_array=explode(",",$user_region_id);
                     $user_territory_array=explode(",",$user_territory_id);
                     if($_SESSION['Dcode'] == "ADMIN" || $_SESSION['Dcode'] == "SUPERADMIN")
                    {
                      $product_division_arr[0]['code']="ras";
                      $product_division_arr[0]['name']="Cotton";
                      $product_division_arr[1]['code']="fcm";
                      $product_division_arr[1]['name']="Field Crop";
                      $product_division_arr[2]['code']="frg";
                      $product_division_arr[2]['name']="Forage Seeds";
                    }else {
                      $sub_array=[];
                      foreach($product_division_array as $value){
                        if($value == "ras"){
                          $sub_array['code']="ras";
                          $sub_array['name']="Cotton";
                        }else if($value == "fcm"){
                          $sub_array['code']="fcm";
                          $sub_array['name']="Field Crop";
                        }else if($value == "frg"){
                          $sub_array['code']="frg";
                          $sub_array['name']="Forage Seeds";
                        }
                        $product_division_arr[]=$sub_array;
                      }
                    }
              ?>


            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                        <div class="row">
                                <div class="col-md-12">
                                        <div class="card list_module_card">
                                            <div class="card-body">
                                                <div id="addproduct-accordion" class="custom-accordion">
                                                    <div class="card border border-primary">
                                                        <a href="#addproduct-productinfo-collapse" class="text-body" data-bs-toggle="collapse" aria-expanded="true" aria-controls="addproduct-productinfo-collapse">
                                                            <div class="p-2">

                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-shrink-0 me-3 ms-1">
                                                                        <div class="avatar">
                                                                            <div class="avatar-title rounded-circle bg-primary-subtle  text-primary">
                                                                                <h5 class="text-primary font-size-17 mb-0"><i class="mdi mdi-filter-variant"></i></h5>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex-grow-1 overflow-hidden d-flex">
                                                                        <h5 class="font-size-16 mb-auto mt-auto text-primary">Filters</h5>
                                                                    </div>
                                                                    <div class="flex-shrink-0">
                                                                        <i class="mdi mdi-chevron-up accor-down-icon font-size-24"></i>
                                                                    </div>

                                                                </div>

                                                            </div>
                                                        </a>

                                                        <div id="addproduct-productinfo-collapse" class="collapse" data-bs-parent="#addproduct-accordion">
                                                            <div class="p-4 border-top">
                                                                    <form class="form-horizontal form-label-left  adv_submit"  method="POST" >
                                                                        <input type="hidden" class="login_type" value="<?php echo $_SESSION['Dcode']?>" readonly>
                                                                        <input type="hidden" class="Emp_id" value="<?php echo $_SESSION['EmpID']?>" readonly>



                                                                        <div class="row">

                                                                            <div class="col-md-2">
                                                                                 <label for="control-label col-md-3 col-sm-4 col-xs-12" for="name">C&F Plant<span class="required"></span> </label>
                                                                                       
                                                                                 <select class="js-example-basic-single col-xs-12 plant_id required_for_valid" error-msg="Plant Field is Required" name="PlantId" style="width:100%;">
                                                                                    <option value="0">Select </option>
                                                                                </select>                                                                  
                                                                            </div>

                                                                            <div class="col-md-2 pr-0 mt-auto">
                                                                              <button type="Submit" class="btn btn-success filterData" value="Submit"><i class="mdi mdi-filter"></i> Filter</button>                         
                                                                            </div>
                                                                        </div>

                                                                    </form> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> 
                                                <form class="sales_indent_validate" method="post">
                                                    <table  class="table table-bordered table-hover dataTable table-striped Sales_Table" id="Sales_Indent" >
                                                            <thead>
                                                                <tr>                
                                                                   <th class="b-border-none">SNo</th>
                                                                   <th class="b-border-none">Req Id</th>
                                                                   <th class="b-border-none">Req By</th>
                                                                   <th class="b-border-none">Req Date</th>
                                                                   <th class="b-border-none">Plant</th>
                                                                   <th class="b-border-none">Rec Plant</th>
                                                                   <th class="b-border-none">Rec Plant Name</th>
                                                                   <th class="b-border-none">Plan Qty</th>
                                                                   <th class="b-border-none">Material Code</th>
                                                                   <th class="b-border-none">Quantity In Bag</th>
                                                                   <th class="b-border-none">Quantity In Pkt</th>
                                                                   <th class="b-border-none">Quantity In Kg</th>
                                                                    <th class="b-border-none">
                                                                        <span>Action</span>
                                                                        <div class="form-check d-flex justify-content-center"> 
                                                                            <input class="form-check-input checkAll" type="checkbox" value="">
                                                                        </div>
                                                                    </th>  
                                                                </tr>
                                                            </thead>
                                                    </table>
                                                    <input type="hidden" name="Action" value="Approve_STO_Material_Details">

                                                    <div class="approvals text-end">
                                                        <button type="button" name="submit"class="btn btn-success submit_btn" disabled><i class="bx bx-check me-1"></i> Approve</button>
                                                        <button type="button" name="button" class="btn btn-danger Reject_btn" disabled><i class="bx bx-x me-1"></i> Reject</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                </div>
                        </div>
                    </div>
                    <!-- container-fluid -->
                </div>
                <!-- End Page-content -->
                <?php include('partials/footer.php') ?>
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

  <input type="hidden" value ="<?=isset($_REQUEST['status']) && $_REQUEST['status']!="" ? safe_decode($_REQUEST['status']): "1" ?>" id="filter_status"/>

        <?php //include('partials/right-sidebar.php') ?>
        <?php include('partials/bottom_scripts.php') ?>

        <script src="js/Sto_Approve_Limit_Exceed_Sales_Indent_Report.js"></script>
        <script src="../common/checkSession.js"></script>

        <script type="text/javascript">
            $(document).ready(function(){
                $('.page-title').text('STO Approve (Limit Exceeds)');
                $('.page-title').before("<i class='fas fa-tasks icon font-size-17 me-2 text-primary'></i>");

                // $('.dt-buttons a').addClass('btn-sm');
                $('.dt-buttons').addClass('mb-3');

            });
        </script>

    </body>

    </html>