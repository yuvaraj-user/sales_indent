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
                    if(isset($_REQUEST['submit']))
                     {
                      $user_Input=$_POST;
                      $ModifiedAt=date('Y-m-d H:i:s');
                      $ModifiedBy=$_SESSION['EmpID'];
                      $Address=@$user_Input['Address'];
                      $sto_id = $user_Input['sto_id'];
                      $sql="update Sales_Indent_STO set Address='$Address' where salesIndentId='".$sto_id."'";
                        if(sqlsrv_query($conn,$sql)){
                           $count=0;
                        }

                      foreach(@$user_Input['QtyInBag'] as $key=>$value)
                      {
                        $MaterialCode=@$user_Input['MaterialCode'][$key];
                        $QtyInBag=@$user_Input['QtyInBag'][$key];
                        $QtyInPkt=@$user_Input['QtyInPkt'][$key];
                        $QtyInKg=@$user_Input['QtyInKg'][$key];
                        $SalesIndentId=@$user_Input['SalesIndentId'][$key];
                        $sql="update Sales_Indent_STO_Material_Details set QtyInBag='$QtyInBag',QtyInKg='$QtyInKg',QtyInPkt='$QtyInPkt',MaterialCode='$MaterialCode',ModifiedAt='".$ModifiedAt."',ModifiedBy='".$ModifiedBy."' where id='".$SalesIndentId."'";

                        if(sqlsrv_query($conn,$sql)){
                          $count++;
                        }
                      }
                      // echo "<script>window.location.href ='Sales_indent_Sto_Report.php'</script>";

                    echo "<script>
                      Swal.fire(
                      {
                      title:'Success!',
                      text:'STO Modifications updated succssfully.',
                      icon:'success',
                      // showCancelButton:!0,
                      confirmButtonColor:'#038edc',
                      // cancelButtonColor:'#f34e4e'
                         }
                      ).then(function(e){
                        if(e) {
                            window.location.href ='Sales_indent_Sto_Modification.php'
                        }
                      });
                      </script>";
                      
                      }
                      
                      //$Insert=new Stock_Indent();
                      //$Insert->Insert(array("data"=>1));
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


                    $id=isset($_REQUEST['Id']) && !empty($_REQUEST['Id']) ? safe_decode($_REQUEST['Id']) : 0;
                    $sql="EXEC Sales_Indent_STO_Header_Details @SalesIndentId='$id'";
                    $stmt = sqlsrv_prepare($conn, $sql);
                    sqlsrv_execute($stmt);
                    $Header_data = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC);

                    $Grid_sql="EXEC Sales_Indent_Get_STO_Material_DetS @Sales_Indent_Id='".@$Header_data['SalesIndentId']."'";
                    $Grid_stmt = sqlsrv_prepare($conn, $Grid_sql);
                    sqlsrv_execute($Grid_stmt);
                ?>

                <!-- ============================================================== -->
                <!-- Start right Content here -->
                <!-- ============================================================== -->
                <div class="main-content">
                    <div class="page-content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <form class="form-horizontal form-label-left Sales_Indent_Submit" name="form_name"  role="form" method="POST" enctype="multipart/form-data" >  

                                         <input type="hidden" class="form_submit_value" name="form_submit_value" value="0">
                                         <input type="hidden" class="session_user_zone_id" name="session_user_zone_id" value="<?=@$user_zone_id?>">
                                         <input type="hidden" class="session_user_region_id" name="session_user_region_id" value="<?=@$user_region_id?>">
                                         <input type="hidden" class="session_user_territory_id" name="session_user_territory_id" value="<?=@$user_territory_id?>">
                                
                                          <input type="hidden" name="sto_id"  class="sto_id" value="<?=@$Header_data['SalesIndentId'];?>">

                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title mb-4" style="color: rgb(31, 88, 199); display: flex;">STO Indent Details</h5>
                                                <!-- <p class="col-xs-12 Sales_Indent_Div fw-bold" style="color: #1f58c7">Sale Order Indent Details</p> -->
                                                <div class="row">
                                                    <div class="form-group" style="line-height: 5px;">
                                                      <div class="row">

                                                        <div class="col-md-2 col-sm-2 col-xs-6">
                                                          <label for="control-label col-md-3 col-sm-4 col-xs-12" for="name" >Request ID</label>
                                                          <input type="text" class="form-control col-md-12 col-xs-12 required_for_valid textbox-grey mt-2" error-msg="Request ID field is required" name="ReqId" value="<?=@$Header_data['ReqId']?>" readonly>
                                                          <label class="error_msg text-danger"></label>
                                                        </div>
                                                        
                                                        <div class="col-md-2 col-sm-2 col-xs-6">
                                                            <div class="form-group">
                                                              <label for="control-label col-md-2 col-sm-2 col-xs-12" for="name">Request Date</label>
                                                               
                                                                  <input type="text" class="form-control col-md-12 col-xs-12 required_for_valid textbox-grey mt-2" name="ReqDate" value="<?php echo date('d-m-Y');?>" readonly>
                                                                   <label class="error_msg text-danger"></label>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2 col-sm-2 col-xs-6">
                                                            <div class="form-group change_select_width">
                                                                <div class="mb-2">
                                                                    <label for="control-label col-md-3 col-sm-4 col-xs-12" for="name" >Division<span class="required"> *</span> </label>
                                                                </div>
                                                                <input type="hidden" class="product_division" name="ProductDivision"  value="<?=@$Header_data['ProductDivision']?>">
                                                                <select class="js-example-basic-single col-xs-12 product_division required_for_valid mt-2" error-msg="Division Field is Required" name="ProductDivision" disabled>
                                                               
                                                                    <?php foreach($product_division_arr  as $value){?>
                                                                  <option value="<?=$value['code']?>"><?=$value['name']?></option>
                                                                  <?php } ?>
                                                                </select>
                                                                <label class="error_msg text-danger"></label>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2 col-sm-2 col-xs-6">
                                                        <div class="form-group change_select_width">
                                                            <div class="mb-2">
                                                                <label for="control-label col-md-3 col-sm-4 col-xs-12" for="name" >Purchase Type<span class="required"> *</span> </label>
                                                            </div>

                                                            <input type="text" class="form-control required_for_valid textbox-grey"  name="purchase_type" value="ZSTO" readonly>
                
                                                            <label class="error_msg text-danger"></label>
                                                          
                                                            </div>
                                                        </div>


                                                        <div class="col-md-2 col-sm-2 col-xs-6">
                                                            <div class="form-group change_select_width">
                                                                <div class="mb-2">
                                                                   <label class="control-label col-md-4 col-sm-4 col-xs-12" for="name">Zone<span class="required"> *</span> </label>
                                                               </div>
                                                              <input type="text" class="form-control textbox-grey" value="<?=@$Header_data['ZONENAME']?>" readonly>
                                                              <input type="hidden" class="zone_id" name="ZoneId"  value="<?=@$Header_data['ZoneId']?>">
                                                              <input type="hidden" class="region_id" name="RegionId"  value="<?=@$Header_data['RegionId']?>">
                                                              <input type="hidden" class="territory_id" name="TerritoryId"  value="<?=@$Header_data['TerritoryId']?>">
                                                                <label class="error_msg text-danger"></label>
                                                            </div>
                                                        </div>


                                                        <div class="col-md-2 col-sm-2 col-xs-6">
                                                            <div class="form-group change_select_width">
                                                                <div class="mb-2">
                                                                    <label for="control-label col-md-3 col-sm-4 col-xs-12 " for="name">Region<span class="required"> *</span> </label>
                                                                </div>
                                                                <input type="text" class="form-control textbox-grey" value="<?=@$Header_data['REGIONNAME']?>" readonly>
                                                                <label class="error_msg text-danger"></label>
                                                                
                                                            </div>
                                                        </div>

                                                      </div>
                                                    </div>
                                                </div>


                                                <div class="row mt-3">

                                                   <div class="row Sales_Indent_Div">
                                                   
                                                        <div class="form-group row col-md-5">

                                                            <div class="col-md-2 col-sm-2 col-xs-6">
                                                                <div class="mb-2">
                                                                  <label for="control-label col-md-3 col-sm-4 col-xs-12" for="name">Plant<span class="required"> *</span> </label>
                                                                </div>

                                                                  <input type="hidden" class="form-control" name="PlantId" value="<?=@$Header_data['PlantId']?>" readonly>
                                                                  <select class="js-example-basic-single col-xs-12 plant_id" error-msg="Plant Field is Required" name="PlantId" disabled>
                                                                    <option value=" ">Select </option>
                                                                </select>
                                                            </div>

                                                        </div>

                                                   </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card sales_indent_material_card">
                                            <div class="card-body">
                                                <h5 class="card-title mb-4" style="color: rgb(31, 88, 199); display: flex;">STO Indent Material Details</h5>
                                                <div class="row">
                                                <div class="form-group col-md-12">
                                                    <small class="duplicate_error text-danger" style="font-weight:bold"></small>
                                                    <small class="limit_exceeds_error text-danger" style="font-weight:bold"></small>
                                                    <div class="Sales_Indent_Div_Crop">
                                                        <table class="table table-bordered table-hover table-striped table-responsive tbl-select p-0"     style="line-height: 10px;">
                                                            <thead>
                                                              <th class="f-14" style="max-width:10%">S.No.</th>
                                                              <th class="f-14" style="max-width:10%">Crop</th>
                                                              <!-- <th>Storage Location</th>-->
                                                              <th class="f-14" style="max-width:10%">Material</th>
                                                              <!-- <th class="text-center f-14" style="max-width:10%">Season</th> -->
                                                             <!--  <th class="text-center text-nowrap f-14">Planned Quantity</th>
                                                              <th class="text-center text-nowrap f-14">Despatched Quantity</th>
                                                              <th class="text-center text-nowrap f-14">Indent Quantity</th> -->
                                                              <th class="text-center text-nowrap f-14" style="max-width:10%">Quantity Bag</th>
                                                              <th class="text-center text-nowrap f-14" style="max-width:10%">Quantity Pkts</th>
                                                              <th class="text-center text-nowrap f-14" style="max-width:10%">Quantity Kg</th>
                                                              <th>Action</th>
                                                            </thead> 

                                                            <tbody>
                                                            <?php 
                                                                $i=1; 
                                                                while($Grid_data = sqlsrv_fetch_array($Grid_stmt,SQLSRV_FETCH_ASSOC)){;?>
                                                               <tr>
                                                                <td class='srn_no' ><?php echo $i++; ?> </td>
                                                                <td width="100px" >
                                                                     <input type="hidden" class="cropid" name="CropId"  value="<?=@$Header_data['CropId']?>">
                                                                      <select class="js-example-basic-single form-control Crop_Design crop_id required_for_valid" error-msg="Crop Field is Required" name="CropId" style="width: 150px;" disabled>
                                                                         <option value="">Select </option>
                                                                      </select>
                                                                     <small class="error_msg text-danger mt-1 lh-base"></small>
                                                                   <input type="hidden"  class="form-control right only_numbers max_charater location " error-msg="Pkts Field is Required" value="SE01" name="StorageLocation[]" readonly >
                                                                </td>
                                                                <td >
                                                                   <div>
                                                                      <input type="hidden" class='Material_Code' value='<?=@$Grid_data['MaterialCode']?>'>
                                                                      <select class="js-example-basic-single material_id required_for_valid" error-msg="Material Field is Required" name="MaterialCode[]" id="MaterialCode" style="width: 100%;">
                                                                         <option value="0" selected>Material </option>
                                                                         <option value=""></option>
                                                                      </select>
                                                                     <small class="error_msg text-danger mt-1 lh-base"></small>
                                                                   </div>
                                                                </td>
                                                                <!-- <td width="100px" >
                                                                   <div>
                                                                      <input type="hidden" class="form-control textbox-grey" value="<?php echo $Grid_data['season']; ?>"> 
                                                                      <select class="js-example-basic-single season required_for_valid" error-msg="Season Field is Required" name="season[]" >
                                                                         <option value="" selected>Select Season </option>
                                                                      </select>
                                                                     <small class="error_msg text-danger mt-1 lh-base"></small>
                                                                   </div>
                                                                </td> -->
                                                                <!-- <td><input type="text" class="form-control Plan_qty textbox-grey" name="PlanQty[]" readonly></td>
                                                                <td><input type="text" class="form-control  textbox-grey" name="" value="0" readonly></td>
                                                                <td><input type="text" class="form-control Indent_Qty textbox-grey" name="Indent_Qty[]" readonly></td> -->
                                                                <td >
                                                                   <div>
                                                                      <input type="text"  class="form-control text-end only_numbers max_charater QtyInBag required_for_valid" error-msg="Quantity_Bag Field is Required" name="QtyInBag[]"  value="<?=round(@$Grid_data['QtyInBag'],0)?>">
                                                                      <small class="error_msg text-danger mt-1 lh-base"></small>
                                                                   </div>
                                                                </td>
                                                                <td>
                                                                   <div>
                                                                      <input type="text"  class="form-control text-end only_numbers max_charater QtyInPkt textbox-grey" error-msg="Pkts Field is Required" name="QtyInPkt[]" value="<?=round(@$Grid_data['QtyInPkt'],0)?>" readonly >
                                                                   </div>
                                                                </td>
                                                                <td >
                                                                   <div>
                                                                      <input type="text"  class="form-control text-end only_numbers max_charater QtyInKg textbox-grey" error-msg="Kg Field is Required" name="QtyInKg[]" value="<?=round(@$Grid_data['QtyInKg'],2)?>" readonly >
                                                                     <input type="hidden"  class="form-control right only_numbers max_charater Product_QtyInPkt " error-msg="Kg Field is Required" name="MaterialQtyInPkt[]" value="<?=round(@$Grid_data['MaterialQtyInPkt'],2)?>" readonly >
                                                                    <input type="hidden"  class="form-control right only_numbers max_charater Product_QtyInKg " error-msg="Kg Field is Required" name="MaterialQtyInKg[]" value="<?=round(@$Grid_data['MaterialQtyInKg'],2)?>" readonly >

                                                                   </div>
                                                                </td>
                                                                <td>
                                                                   <div class="mt-2"> 
                                                                   <button type="button" class="btn btn-sm btn-danger remove_btn"><i class="fas fa-trash"></i></button>
                                                                    </div>
                                                                    <input type="hidden" name="SalesIndentId[]"  class="SalesIndentId" value="<?=@$Grid_data['Id']?>">

                                                                </td>
                                                               </tr>
                                                           <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                </div>


                                                <div class="col-md-12 Sales_Indent_Div_Material">
                                                    <div class="row mb-3" style="width:100%;">
                                                        <label class="control-label col-md-2" for="Address">Delivery Address </label>
                                                        <div class="col-md-12">
                                                            <textarea class="form-control " maxlength="60" error-msg="Delivery Address  Field is Required" name="Address" rows="2"><?=@$Header_data['Address']?></textarea>
                                                            <small class="text-danger mt-1"> ( Note: Only Allow 60 Characters Only ) </small>
                                                            <small class="error_msg text-danger mt-1 lh-base"></small>

                                                            <input type="hidden" class="form-control rasiCount_material mt-4" value="1" min="1" max='25'>
                                                        </div> 

                                                        <div class="col-md-12 Sales_Indent_Div d-flex justify-content-center mt-5">
                                                            <a href="Dashboard_validation.php" class="btn btn-danger"><i class="bx bx-x me-1"></i> Cancel</a>
                                                            <button type="submit" name="submit" class="btn btn-success cls_btn_sub ms-2"><i class="bx bx-check me-1"></i> Submit</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </form>
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

            <script src="js/Edit_Sales_indent_STO_Details.js"></script>
            <script src="../common/checkSession.js"></script>

            <script type="text/javascript">
                $(document).ready(function(){
                    $('.page-title').text('STO Creation');
                    $('.flatpickr-input').flatpickr();
                });
            </script>

            </body>

            </html>