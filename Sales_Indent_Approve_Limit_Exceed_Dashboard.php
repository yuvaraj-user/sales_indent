            <?php include('partials/head.php'); ?>
            <!-- <body data-layout="horizontal"> -->
            <?php 
            if(@$Approve_limit_Exceed_Menu == 0) {
                header('location: index.php');  
            }

            $approve_counts = indent_limit_exceed_qry(4,'approve');

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

                    function Generate_Document_No($Emp_Id,$id='0'){
                      global $conn;
                      $Emp_Id=$Emp_Id !=''? strtoupper(trim($Emp_Id)) : "";
                      $Doc_No_Auto_Generation_Sql="Sales_Indent_Generate_Document_No @Emp_Id='".$Emp_Id."',@Id=".$id."";
                      $Doc_No_Auto_Generation_Dets=sqlsrv_query($conn,$Doc_No_Auto_Generation_Sql);
                      $Doc_No_Auto_Generation_Result = sqlsrv_fetch_array($Doc_No_Auto_Generation_Dets);
                      return $Anp_Doc_No_Generation_Id=$Doc_No_Auto_Generation_Result['PrimaryId'];
                    }
                    $Doc_No=Generate_Document_No($_SESSION['EmpID'],0);

                    // echo $dashboard_det="EXEC Sales_Indent_Widget_Count_Details @Emp_Id='".$_SESSION['EmpID']."',@Dcode='".$_SESSION['Dcode']."'";
                    // $salesadv       = sqlsrv_query($conn,$dashboard_det);
                    // $fetch_wait   = sqlsrv_fetch_array($salesadv);
                    // $Completed_Count        = $fetch_wait['Completed_Count'];
                    // $Validation_Count       = $fetch_wait['Validation_Count'];
                    // $Approve_Count          = $fetch_wait['Approve_Count'];
                    // $Total_Count          = $fetch_wait['Total_Count'];
                    // $Rejection_Count          = $fetch_wait['Rejection_Count'];
                    // $Approve_Count=@$fetch_wait['Approve_Count'];
                    // $Recommedation_Count=@$fetch_wait['Recommedation_Count'];
                    // $Validation_Limit_Exceed_Count=@$fetch_wait['Validation_Limit_Exceed_Count'];
                    // $Validation_With_In_Limit_Count=@$fetch_wait['Validation_With_In_Limit_Count'];
                    // $Approve_Count_Limit_Exceed_Count=@$fetch_wait['Approve_Count_Limit_Exceed_Count'];
                    // $Completed_Count=@$fetch_wait['Completed_Count'];
                    // $Approve_qty=@$fetch_wait['Approve_qty'];
                    // $Recommedation_Qty=@$fetch_wait['Recommedation_Qty'];
                    // $Validation_Limit_Exceed_Qty=@$fetch_wait['Validation_Limit_Exceed_Qty'];
                    // $Approve_Count_Limit_Exceed_Qty=@$fetch_wait['Approve_Count_Limit_Exceed_Qty'];
                    // $Completed_Qty=@$fetch_wait['Completed_Qty'];
                    // $Validation_With_In_Limit_Qty=@$fetch_wait['Validation_With_In_Limit_Qty'];
                ?>

                <!-- ============================================================== -->
                <!-- Start right Content here -->
                <!-- ============================================================== -->
                <div class="main-content">
                    <div class="page-content">
                        <div class="container-fluid">
                            <div class="row">
                               <!--  <div class="col-xl-3 col-md-4">
                                    <a href="Sales_Indent_Approve_for_Limit_Exceed.php">
                                    <div class="card widget-shade" >
                                        <div class="card-body widget-body">
                                            <div>
                                                <div class="d-flex align-items-center justify-content-around">
                                                    <div class="col-2">
                                                        <i class="fas fa-check-circle f-40 mb-0 text-success"></i>
                                                    </div>
                                                    <div class="col-7 pt-3 text-center">
                                                        <p class="mb-0 font-size-16 text-truncate fw-medium">Pending Apporval <br><span class="mt-2">(Limit Exceed)</span></p>
                                                        <div>
                                                            <input type="hidden" id="approve_le_value" value="<?= !is_null($indent_Approve_Count_Limit_Exceed_Count) ? $indent_Approve_Count_Limit_Exceed_Count : 0; ?>">

                                                            <p class="pt-3 mb-0 font-size-22 text-center fw-bold" id="approve_le">0
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    </a>
                                </div> -->

                                <div class="col-md-4 col-xl-3">
                                    <a href="Sales_Indent_Approve_for_Limit_Exceed.php">
                                        <div class="card widget-rounded-circle widget-shade">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="avatar-lg indent-avatar-bg">
                                                            <i class="fas fa-check-circle f-30 text-white avatar-title" style="border-radius: 50%;"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="text-end">
                                                            <input type="hidden" id="approve_le_value" value="<?= !is_null($indent_Approve_Count_Limit_Exceed_Count) ? $indent_Approve_Count_Limit_Exceed_Count : 0; ?>">

                                                            <h3 class="text-dark mt-1"><span data-plugin="counterup" id="approve_le">0</span></h3>
                                                            <p class="mb-1 text-truncate">Pending Approval <br> (limit exceed)</p>
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>
                                        </div> 
                                    </a>
                                </div>

                                <div class="col-xl-3 col-md-6 mt-1">
                                    <table class="table table-bordered table-hover table-striped datatable" style="height: 125px !important;">
                                        <thead class="tbl_head">
                                            <tr >
                                                <th>Supply Type</th>
                                                <th>Nos</th>
                                                <!-- <th>Qty</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="v-middle"><a href="Sales_Indent_Approve_for_Limit_Exceed.php?filter=2"><h5><span class="badge bg-success">C&F</span><i class="fas fa-angle-double-right text-success ms-2 blink_anime"></i></h5></a></td>
                                                <td class="v-middle text-end"><?php echo $approve_counts[0]['approve_cf_count'] ; ?></td>
                                                <!-- <td class="v-middle text-end"><?php echo $approve_counts[0]['approve_cf_qty'] ; ?></td> -->
                                            </tr>
                                            <tr>
                                                <td class="v-middle"><a href="Sales_Indent_Approve_for_Limit_Exceed.php?filter=1"><h5><span class="badge bg-success">DIRECT</span><i class="fas fa-angle-double-right text-success ms-2 blink_anime"></i></h5></a></td>
                                                <td class="v-middle text-end"><?php echo $approve_counts[0]['approve_direct_count'] ; ?></td>
                                                <!-- <td class="v-middle text-end"><?php echo $approve_counts[0]['approve_direct_qty'] ; ?></td> -->
                                            </tr>
                                        </tbody>

                                    </table>
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

            <script src="js/Indent_creation.js"></script>
            <script src="../common/checkSession.js"></script>

            <script type="text/javascript">
                $(document).ready(function(){
                    $('.page-title').text('Indent Approve (Limit Exceeds)');
                    $('.page-title').before("<i class='fas fa-check-circle icon font-size-20 me-2 text-primary'></i>");

                    $('.flatpickr-input').flatpickr();
                    // $('.datatable').DataTable({
                    //     "dom" : "rt"
                    // });
                    countto('approve_le',$('#approve_le_value').val());
                });
            </script>

            </body>

            </html>