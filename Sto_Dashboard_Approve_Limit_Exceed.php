            <?php include('partials/head.php'); ?>
            <!-- <body data-layout="horizontal"> -->
            <?php 
            if(@$Approver_Menu_Limit_Exceed_For_Sto_Indent == 0) {
                header('location: index.php');  
            }

            $sto_approve_lie_count = sto_with_in_limit_qry('7','approve_limit_exceed');

            ?>
            <style type="text/css">
                .dataTables_paginate {
                    margin-right: 0px !important;
                    margin-top: 10px !important;
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
                  //  $dashboard_det="EXEC Sales_Indent_STO_Widget_Count_Details @Emp_Id='".$_SESSION['EmpID']."',@Dcode='".$_SESSION['Dcode']."'";
                  // $salesadv       = sqlsrv_query($conn,$dashboard_det);
                  // $fetch_wait   = sqlsrv_fetch_array($salesadv);
                  
                  // $Approve_Count_With_In_Limit=@$fetch_wait['Approve_Count_With_In_Limit'];
                  // $Recommendation_Count_Limit_Exceed=@$fetch_wait['Recommendation_Count_Limit_Exceed'];
                  // $Approve_Count_Limit_Exceed=@$fetch_wait['Approve_Count_Limit_Exceed'];
                  // $Approve_Count_Limit_Exceed_VP=@$fetch_wait['Approve_Count_Limit_Exceed_VP'];
                  // $Completed_Count=@$fetch_wait['Completed_Count'];
                  // $Approve_Qty_With_In_Limit=@$fetch_wait['Approve_Qty_With_In_Limit'];
                  // $Recommendation_Qty_Limit_Exceed=@$fetch_wait['Recommendation_Qty_Limit_Exceed'];
                  // $Approve_Qty_Limit_Exceed=@$fetch_wait['Approve_Qty_Limit_Exceed'];
                  // $Approve_Qty_Limit_Exceed_VP=@$fetch_wait['Approve_Qty_Limit_Exceed_VP'];


                ?>

                <!-- ============================================================== -->
                <!-- Start right Content here -->
                <!-- ============================================================== -->
                <div class="main-content">
                    <div class="page-content">
                        <div class="container-fluid">
                            <div class="row">
                              <!--   <div class="col-xl-3 col-md-4">
                                    <a  href="Sto_Approve_Limit_Exceed_Sales_Indent_Report.php">
                                    <div class="card widget-shade" >
                                        <div class="card-body widget-body"  >
                                            <div>
                                                <div class="d-flex align-items-center justify-content-around">
                                                    <div class="col-2">
                                                        <i class="fas fa-tasks f-30 mb-0 text-success"></i>
                                                    </div>
                                                    <div class="col-8 pt-3">
                                                        <p class="mb-0 font-size-16 text-center fw-medium text-nowrap">Pending Approve <br>(Limit Exceed)</p>
                                                        <div>
                                                            <input type="hidden" id="pending_sto_approve_le_value" value="<?= !is_null($sto_Approve_Count_Limit_Exceed) ? $sto_Approve_Count_Limit_Exceed : 0; ?>">
                                                            <p class="pt-3 mb-0 font-size-22 text-center fw-bold" id="pending_sto_approve_le">0 
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </a>
                                </div> -->

                                <div class="col-md-6 col-xl-3">
                                    <a  href="Sto_Approve_Limit_Exceed_Sales_Indent_Report.php" >
                                        <div class="card widget-rounded-circle widget-shade widget-card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="avatar-lg indent-avatar-bg d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-tasks f-30 text-white" style="border-radius: 50%;"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="text-end">
                                                            <input type="hidden" id="pending_sto_approve_le_value" value="<?= !is_null($sto_Approve_Count_Limit_Exceed) ? $sto_Approve_Count_Limit_Exceed : 0; ?>">

                                                            <h3 class="text-dark mt-1"><span data-plugin="counterup" id="pending_sto_approve_le">0</span></h3>
                                                            <p class="mb-1 text-truncate">Pending Approval <br>(Limit Exceed)</p>
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>
                                        </div> 
                                    </a>
                                </div>

                                <div class="col-xl-3 col-md-6">
                                    <table class="table table-bordered table-hover table-striped datatable widget-shade" style="height: 105px !important;">
                                        <thead class="tbl_head">
                                            <tr >
                                                <th>Plant Code</th>
                                                <th>Plant Name</th>
                                                <th>Nos</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($sto_approve_lie_count as $key => $value) { ?>
                                            <tr>
                                                <td class="v-middle"><a href="Sto_Approve_Limit_Exceed_Sales_Indent_Report.php?filter=<?php echo base64_encode($value['Plant_Code']); ?>"><h5><span class="badge bg-primary"><?php echo $value['Plant_Code']; ?></span><i class="fas fa-angle-double-right text-primary ms-2 blink_anime"></i></h5></a></td>
                                                <td class="v-middle"><?php echo $value['Plant_Name']; ?></td>
                                                <td class="v-middle text-end"><?php echo $value['recommendation_count']; ?></td>
                                            </tr>
                                            <?php } ?>
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
                    $('.page-title').text('STO Approve (Limit Exceeds)');
                    $('.page-title').before("<i class='fas fa-tasks icon font-size-17 me-2 text-primary'></i>");

                    $('.flatpickr-input').flatpickr();
                    $('.datatable').DataTable({
                        "dom" : "rtp",
                        "pageLength" : 2
                    });
                    countto('pending_sto_approve_le',$('#pending_sto_approve_le_value').val());
                });
            </script>

            </body>

            </html>