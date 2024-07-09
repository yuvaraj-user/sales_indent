            <?php include('partials/head.php'); ?>
            <!-- <body data-layout="horizontal"> -->
                <style type="text/css">
                    thead tr th {
                        background: var(--app-color) !important;
                       color: white !important;
                   }
            </style>
            <!-- Begin page -->
            <div id="layout-wrapper">
                <?php include('partials/topbar.php'); ?>

                <!-- ========== Left Sidebar Start ========== -->
                <?php include('partials/sidebar.php'); ?>
                <!-- Left Sidebar End -->
                <?php include('partials/horizontal_header.php'); ?>

                <?php 
                    include('Current_User_Session_Details.php');
                    $user_zone_id=$session_zone_id;
                    $user_region_id=$session_region_id;
                    $user_territory_id=$session_territory_id;

                    $user_zone_id=isset($user_zone_id) && !empty($user_zone_id) ? $user_zone_id : "No Zone";
                    $user_region_id=isset($user_region_id) && !empty($user_region_id) ? $user_region_id : "No Region";
                    $user_territory_id=isset($user_territory_id) && !empty($user_territory_id) ? $user_territory_id : "No Territory";

                    $product_division_arr=[];
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
                    foreach($product_division_array as $value)
                    {
                        if($value == "ras"){
                          $sub_array['code']=$value;
                          $sub_array['name']="Cotton";
                      }else if($value == "fcm"){
                          $sub_array['code']=$value;
                          $sub_array['name']="Field Crop";
                      }else if($value == "frg"){
                          $sub_array['code']=$value;
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
                                                        <form class="row top-form submit_form" name="submit_form" id="test" enctype="multipart/form-data">
                                                            <input type="hidden" class="login_type" value="<?php echo $_SESSION['Dcode']?>" readonly>
                                                            <input type="hidden" class="Emp_id" value="<?php echo $_SESSION['EmpID']?>" readonly>



                                                            <div class="row">

                                                                <div class="col-md-2">
                                                                    <label class="form-control-label">Product Division</label>
                                                                    <select class="js-example-basic-single form-control product_division_id"  name="pd" style="width:100%;">
                                                                      <?php foreach($product_division_arr as $value){ ?>
                                                                          <option value="<?=$value['code']?>"><?=$value['name']?></option>
                                                                      <?php } ?>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-2">
                                                                     <label class="form-control-label">Crop</label>
                                                                     <select class="js-example-basic-single form-control crop_id"  name="crop_id" style="width:100%;">
                                                                     </select>
                                                                </div>

                                                                <div class="col-md-2">
                                                                       <label class="form-control-label">Zone</label>
                                                                       <select class="js-example-basic-single form-control zone_id zoneid"  name="zonecode" style="width:100%;">
                                                                       </select>
                                                                </div>
                                                                    <div class="col-md-2">
                                                                       <label class="form-control-label">Region</label>
                                                                       <select class="js-example-basic-single form-control region_id cls_region"  name="regioncode" style="width:100%;">
                                                                       </select>
                                                                    </div>

                                                                <div class="col-md-2 pr-0 mt-auto">
                                                                  <button type="Submit" class="btn btn-success search_btn filterData" value="Submit"><i class="mdi mdi-filter"></i> Filter</button>    
                                                                  <button type="reset" class="btn btn-danger fresetbtn"><i class="fas fa-undo" aria-hidden="true"></i> RESET</button>
                                                                </div>
                                                            </div>

                                                        </form> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade indent_summary_modal" tabindex="-1" aria-labelledby="myExtraLargeModalLabel" style="display: none;" aria-modal="true" role="dialog">
                                        <div class="modal-dialog modal-dialog-centered modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="myExtraLargeModalLabel">Indent Summary Details</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row p-3">
                                                        <div class="col-md-12">
                                                            <table class="table table-bordered table-hover table-striped" id="indent_summary_tbl">
                                                                <thead>
                                                                    <th>Request Id</th>
                                                                    <th>Zone</th>
                                                                    <th>Region</th>
                                                                    <th>Plant Name</th>
                                                                    <th>Material</th>
                                                                    <th>QtyInBag</th>
                                                                    <th>QtyInPkt</th>
                                                                </thead>
                                                                <tbody class="indent_summary_tbody">

                                                                </tbody>
                                                                <tfoot>
                                                                  <tr>
                                                                   <th colspan="5"></th>
                                                                   <th class="text-end">Total</th>
                                                                   <th class="text-end pkt_total"></th>
                                                                  </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6" >
                                           <div class="card">
                                              <div class="card-body" id="containers4">
                                                 <div class="row">
                                                    <h5 class="card-header text-center report-head-bg mb-2 text-white">Division Wise Summary </h5>
                                                    <table class="table table-bordered table-hover dataTable table-striped zone_wise_summary responsive">
                                                       <thead>
                                                          <th>Division</th>
                                                          <th class="rgt" style="text-align: right;">Indent</th>
                                                          <th class="rgt" style="text-align: right;">Approved Indent</th>
                                                          <th class="rgt" style="text-align: right;">Delivery</th>
                                                          <th class="rgt" style="text-align: right;">Pending</th>
                                                       </thead>
                                                       <tfoot>
                                                          <tr>
                                                             <th></th>
                                                             <th></th>
                                                             <th></th>
                                                             <th></th>
                                                             <th></th>
                                                          </tr>
                                                       </tfoot>
                                                    </table>
                                                 </div>
                                              </div>
                                           </div>
                                        </div>
                                        <div class="col-md-6" >
                                           <div class="card">
                                              <div class="card-body" id="containers4">
                                                 <div class="row">
                                                    <h5 class="card-header text-center report-head-bg mb-2 text-white">Crop Wise Summary </h5>
                                                    <table class="table table-bordered table-hover dataTable table-striped w-full crop_wise_summary responsive nowrap">
                                                       <thead>
                                                          <th>Crop</th>
                                                          <th class="rgt" style="text-align: right;">Indent</th>
                                                          <th class="rgt" style="text-align: right;">Approved Indent</th>
                                                          <th class="rgt" style="text-align: right;">Delivery</th>
                                                          <th class="rgt" style="text-align: right;">Pending</th>
                                                       </thead>
                                                       <tfoot>
                                                          <tr>
                                                             <th></th>
                                                             <th></th>
                                                             <th></th>
                                                             <th></th>
                                                             <th></th>
                                                          </tr>
                                                       </tfoot>
                                                    </table>
                                                 </div>
                                              </div>
                                           </div>
                                        </div>
                                        <div class="col-md-6 region_wise_summary_div" style="display: none">
                                           <div class="card">
                                              <div class="card-body" id="containers4">
                                                 <div class="row">
                                                    <h5 class="card-header text-center report-head-bg mb-2 text-white">Region Wise Summary  </h5>
                                                    <table class="table table-bordered table-hover dataTable table-striped w-full region_wise_summary responsive">
                                                       <thead>
                                                          <th>Region</th>
                                                          <th class="rgt" style="text-align: right;">Indent</th>
                                                          <th class="rgt" style="text-align: right;">Approved Indent</th>
                                                          <th class="rgt" style="text-align: right;">Delivery</th>
                                                          <th class="rgt" style="text-align: right;">Pending</th>
                                                       </thead>
                                                       <tfoot>
                                                          <tr>
                                                             <th></th>
                                                             <th></th>
                                                             <th></th>
                                                             <th></th>
                                                             <th></th>
                                                          </tr>
                                                       </tfoot>
                                                    </table>
                                                 </div>
                                              </div>
                                           </div>
                                        </div>
                                        <div class="col-md-6 vareity_wise_summary_div" style="display: none">
                                           <div class="card">
                                              <div class="card-body" id="containers4">
                                                 <div class="row">
                                                    <h5 class="card-header text-center report-head-bg mb-2 text-white">Variety Wise Summary  </h5>
                                                    <table class="table table-bordered table-hover dataTable table-striped w-full vareity_wise_summary responsive">
                                                       <thead>
                                                          <th>Variety</th>
                                                          <th class="rgt" style="text-align: right;">Indent</th>
                                                          <th class="rgt" style="text-align: right;">Approved Indent</th>
                                                          <th class="rgt" style="text-align: right;">Delivery</th>
                                                          <th class="rgt" style="text-align: right;">Pending</th>
                                                       </thead>
                                                       <tfoot>
                                                          <tr>
                                                             <th></th>
                                                             <th></th>
                                                             <th></th>
                                                             <th></th>
                                                             <th></th>
                                                          </tr>
                                                       </tfoot>
                                                    </table>
                                                 </div>
                                              </div>
                                           </div>
                                        </div>
                                        <div class="col-md-6 territory_wise_summary_div" style="display: none">
                                           <div class="card">
                                              <div class="card-body" id="containers4">
                                                 <div class="row">
                                                    <h5 class="card-header text-center report-head-bg mb-2 text-white">Plant Wise Summary  </h5>
                                                    <table class="table table-bordered table-hover dataTable table-striped w-full territory_wise_summary responsive">
                                                       <thead>
                                                          <th>Territory</th>
                                                          <th class="rgt" style="text-align: right;">Indent</th>
                                                          <th class="rgt" style="text-align: right;">Approved Indent</th>
                                                          <th class="rgt" style="text-align: right;">Delivery</th>
                                                          <th class="rgt" style="text-align: right;">Pending</th>
                                                       </thead>
                                                       <tfoot>
                                                          <tr>
                                                             <th></th>
                                                             <th></th>
                                                             <th></th>
                                                             <th></th>
                                                             <th></th>
                                                          </tr>
                                                       </tfoot>
                                                    </table>
                                                 </div>
                                              </div>
                                           </div>
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

            <script src="js/STOSummaryReport.js"></script>
            <script src="../common/checkSession.js"></script>

            <script type="text/javascript">
                $(document).ready(function(){
                    $('.page-title').text('STO VS Actual');
                    $('.page-title').before("<i class='far fa-file icon font-size-17 me-2 text-primary'></i>");
                    
                    $('.flatpickr-input').flatpickr();
                    $('#indent_summary_tbl').DataTable({
                        "dom": 'Brtp',
                        "buttons": ['copy', 'csv', 'excel', 'pdf', 'print']
                    });
                });
            </script>

            </body>

            </html>