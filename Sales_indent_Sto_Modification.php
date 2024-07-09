            <?php include('partials/head.php'); ?>
            <!-- <body data-layout="horizontal"> -->
                <?php 
                if(@$Requestor_Menu_For_Sto_Indent == 0) {
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
                    $emp_id = $_SESSION['EmpID'];
                    $Status=isset($_REQUEST['Status']) && !empty($_REQUEST['Status']) ? safe_decode($_REQUEST['Status']) : 0;

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
                                        <div class="col-md-12">
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
                                                                <div class="flex-grow-1 overflow-hidden">
                                                                    <h5 class="font-size-16 mb-1 text-primary">Filters</h5>
                                                                    <!-- <p class="text-muted text-truncate mb-0">Fill all information below</p> -->
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
                                                                        <div class="col-md-2 col-sm-3 col-xs-6 change_select_width">
                                                                            <div class="mb-2">
                                                                                <label class="form-label"> Division</label>
                                                                            </div>
                                                                            <select class="js-example-basic-single col-xs-12 cls_division div_select product_division" name="product_division"  onchange="">
                                                                            <option value="All">Select Division</option>

                                                                            <?php foreach($product_division_arr  as $value){?>
                                                                            <option value="<?=$value['code']?>"><?=$value['name']?></option>
                                                                            <?php } ?>

                                                                            </select>
                                                                        </div>


                                                                        <div class="col-md-2 col-sm-3 col-xs-6 change_select_width">
                                                                            <div class="mb-2">
                                                                                <label class="form-label" for="name">Zone</label>
                                                                            </div> 
                                                                            <select class="js-example-basic-single col-xs-12 zone_id required_for_valid" error-msg="Zone Field is Required"  name="ZoneId" >
                                                                            <option value="">Select </option>
                                                                            </select>
                                                                            <label class="error_msg text-danger"></label>
                                                                        </div>

                                                                        <div class="col-md-2 col-sm-3 col-xs-6 change_select_width">
                                                                            <div class="mb-2">
                                                                                <label class="form-label">Region</label>
                                                                            </div>
                                                                            <select class="js-example-basic-single col-xs-12 required_for_valid cls_region reg_select region_id" name="region_id" onchange="" >
                                                                            <option value="">Select </option>
                                                                            </select>

                                                                            <input type="hidden" name="region_id" class="reg_text" value="" disabled/>
                                                                            <input type="hidden" class="request_region_id" value="">
                                                                        </div>

                                                                        <div class="col-md-2 col-sm-3 col-xs-6 change_select_width">
                                                                              <div class="mb-2">
                                                                                    <label for="form-label">Plant Name<span class="required"></span> </label>
                                                                              </div>

                                                                               <select class="js-example-basic-single col-xs-12 plant_id required_for_valid" error-msg="Plant Field is Required" name="PlantId" >
                                                                                <option value="">Select </option>
                                                                                </select>
                                                                                <input type="hidden" class="request_teritory_id" value="">
                                                                                <input type="hidden" class="request_teritory_id" value="">
                                                                        </div>

                                                                        <div class="col-md-2 col-sm-3 col-xs-6 change_select_width">
                                                                            <div class="mb-2">
                                                                                <label for="control-label col-md-3 col-sm-4 col-xs-12" for="name">Status<span class="required"></span> </label>
                                                                            </div>

                                                                            <select class="js-example-basic-single col-xs-12 Status required_for_valid" error-msg="Plant Field is Required" name="Status" >
                                                                                <option value="">Select </option>
                                                                                <option value="1" <?=$Status== 1 ? "Selected" : ""?>>Pending </option>
                                                                                <option value="2" <?=$Status== 2 ? "Selected" : ""?>>Validate </option>
                                                                                <option value="3" <?=$Status== 3 ? "Selected" : ""?>>Approved </option>
                                                                                <option value="4" <?=$Status== 4 ? "Selected" : ""?>>Reject </option>
                                                                            </select>

                                                                            <input type="hidden" class="request_teritory_id" value="">
                                                                        </div>  

                                                                        <div class="col-md-2 col-sm-3 col-xs-6">
                                                                            <button type="Submit"  class="btn btn-success filterData" style='margin-top: 34px;' value="Submit"><i class="mdi mdi-filter"></i> Filter</button>
                                                                        </div>


                                                                    </div>

                                                                    <div class="row mt-4">



                                                                    </div>
                                                                </form> 
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> 
                                        </div>
                                        <table class="table table-bordered table-hover dataTable table-striped Sales_Table " id="Sales_Indent" >
                                                <thead>
                                                    <tr>                    
                                                        <th class="f-14">Sno</th>
                                                        <th class="f-14">ReqId</th>
                                                        <th class="f-14">Req Date</th>
                                                        <th class="f-14">RequestBy</th>
                                                        <th class="f-14">EMPLNAME</th>             
                                                        <th class="f-14">Product Division</th>
                                                        <th class="f-14">Crop</th>
                                                        <th class="f-14">Zone</th>
                                                        <th class="f-14">Region</th>
                                                        <th class="f-14">Plant Id</th>
                                                        <th class="f-14">Material Code</th>
                                                        <th class="f-14">Quantity InBag</th>
                                                        <th class="f-14">Quantity InPKt</th>
                                                        <th class="f-14">Quantity InKg</th>
                                                        <th class="f-14">Status</th>
                                                        <th class="f-14">Action</th>
                                                    </tr>
                                                </thead>
                                        </table>
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


        <?php //include('partials/right-sidebar.php') ?>
        <?php include('partials/bottom_scripts.php') ?>

        <script src="js/Sales_indent_Sto_Report.js"></script>
        <script src="../common/checkSession.js"></script>

        <script type="text/javascript">
            $(document).ready(function(){
                $('.page-title').text('STO Modification');
                $('.page-title').before("<i class='fas fa-bahai icon font-size-20 me-2 text-primary'></i>");

                // $('.dt-buttons a').addClass('btn-sm');
            });
        </script>

    </body>

    </html>