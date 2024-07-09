<?php 
include '../auto_load.php';

	$offset=@$_POST['start'];
	$length=@$_POST['length'];
	$ProductDivision=@$_POST['ProductDivision'];
	$Plant_Id=@$_POST['Plant_Id'];
	$Zone_id=@$_POST['Zone_id'];
	$Region_Id=@$_POST['Region_Id'];
	$Terrirory_Id=@$_POST['Terrirory_Id'];
	$Status=@$_POST['Status'];
	//$QuotationType=@$_POST['QuotationType'];
	//$SaleOrderType=@$_POST['SaleOrderType'];
	//$CustomerCode=@$_POST['CustomerCode'];
	$length=$length == '-1' ? "All" : $length;
	$Emp_Id=isset($_SESSION['EmpID']) && ($_SESSION['EmpID'] !='Admin' || $_SESSION['EmpID'] !='SuperAdmin') ? $_SESSION['EmpID'] : '';
	$Dcode=$_SESSION['Dcode'];
	
	 $sql="EXEC Sales_Indent_STO_Details_WIth_Limit_STO_Final_Level @Emp_Id='".$Emp_Id."',@Product_Division='".$ProductDivision."',@Dcode='".$Dcode."',@plant_id='".$Plant_Id."',@Zone_id='".$Zone_id."',@Region_Id='".$Region_Id."',@Terrirory_Id='".$Terrirory_Id."',@Status='7',@length='".$length."',@offset='".$offset."'";
	 
  	$stmt = sqlsrv_prepare($conn, $sql);
	sqlsrv_execute($stmt);
	$sno=$offset+1;
	$res['recordsTotal']=0;
	$resultarr=array();
	while($prow = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC))
	{		
		if(@$prow['Approve_Permission_Limit_Exceed'] == 1){
		 	$res['recordsTotal'] = @$prow['TOTALROW'];
		 	$resarr 	= array();
		 	$resarr[]	= $sno++;
			$resarr[] 	= @$prow['ReqId'];
			$resarr[] 	= @$prow['RequestBy'];	
			$resarr[] 	= @$prow['ReqDate'];	
			$resarr[] 	= @$prow['PlantId'];
			$resarr[] 	= @$prow['Receiving_Plant'];
			$resarr[] 	= @$prow['Receiving_Plant_Name'];
			$resarr[] 	= @$prow['PlanQty'];
			$resarr[] 	= @$prow['MaterialCode'];
			$resarr[] 	= '<input type="text" class="form-control QtyInBag" name="QtyInBag['.@$prow['Id'].']" value="'.round(@$prow['QtyInBag'],2).'" >';
			$resarr[] 	= '<input type="text" class="form-control QtyInPkt" name="QtyInPkt['.@$prow['Id'].']" value="'.round(@$prow['QtyInPkt'],2).'"readonly><input type="hidden" class="MaterialQtyInPkt" value="'.round(@$prow['MaterialQtyInPkt'],2).'"readonly>';
			$resarr[] 	= '<input type="text" class="form-control QtyInKg" name="QtyInKg['.@$prow['Id'].']" value="'.round(@$prow['QtyInKg'],2).'"readonly><input type="hidden" class="MaterialQtyInKg" value="'.round(@$prow['MaterialQtyInKg'],2).'"readonly>';
			if(@$prow['Approve_Permission_Limit_Exceed'] == 1){
			$resarr[] 	= '<div class="form-check"> 
							<label class="form-check-label" for="">
							
						   </label>
						   <input class="form-check-input validate All_Validate m-auto" type="checkbox" name="validate['.@$prow['Id'].']" value="'.@$prow['Id'].'">
						    <input class="form-control" type="hidden" name="SalesIndentId['.@$prow['Id'].']" value="'.@$prow['SalesIndentId'].'">
						   <input class="form-control" type="hidden" name="Sales_Indent_line_No['.@$prow['Id'].']" value="'.@$prow['Id'].'">
						     <input class="form-control" type="hidden" name="EMPLOYEE_ID['.@$prow['Id'].']" value="'.@$prow['RequestBy'].'">
						     <input class="form-control" type="hidden" name="INDENT_NO['.@$prow['Id'].']" value="'.@$prow['ReqId'].'">
						   <input class="form-control" type="hidden" name="ACCOUNTNUM['.@$prow['Id'].']" value="'.@$prow['CustomerCode'].'">
						   <input class="form-control" type="hidden" name="SALES_ORG['.@$prow['Id'].']" value="'.@$prow['Sales_Org'].'">
						   <input class="form-control" type="hidden" name="DIST_CHANNEL['.@$prow['Id'].']" value="ST">
						   <input class="form-control" type="hidden" name="DIVISION['.@$prow['Id'].']" value="'.@$prow['Division'].'">
						   <input class="form-control" type="hidden" name="ZONE_ID['.@$prow['Id'].']" value="'.@$prow['ZoneId'].'">
						   <input class="form-control" type="hidden" name="REGION_ID['.@$prow['Id'].']" value="'.@$prow['RegionId'].'">
						   <input class="form-control" type="hidden" name="TM_ID['.@$prow['Id'].']" value="'.@$prow['TerritoryId'].'">
						   <input class="form-control" type="hidden" name="QUOTATION_TYPE['.@$prow['Id'].']" value="'.@$prow['QuotationType'].'">
						   <input class="form-control" type="hidden" name="SALEORDER_TYPE['.@$prow['Id'].']" value="'.@$prow['SaleOrderType'].'">
						   <input class="form-control" type="hidden" name="MATERIAL_NO['.@$prow['Id'].']" value="'.@$prow['MaterialCode'].'">
						   <input class="form-control" type="hidden" name="QUANTITY['.@$prow['Id'].']" value="'.round(@$prow['QtyInPkt'],2).'">
						   <input class="form-control" type="hidden" name="PLANT['.@$prow['Id'].']" value="'.@$prow['PlantId'].'">
						    <input class="form-control" type="hidden" name="Receiving_Plant['.@$prow['Id'].']" value="'.@$prow['Receiving_Plant'].'">
						   
						   <input class="form-control" type="hidden" name="LGORT['.@$prow['Id'].']" value="SE01">
						   <input class="form-control" type="hidden" name="VALID_TO_DATE['.@$prow['Id'].']" value="'.date("Y-m-d",strtotime(@$prow['ReqDate'])).'">
						   <input class="form-control" type="hidden" name="APPROVE_STATUS['.@$prow['Id'].']" value="Approved">
						   <input class="form-control" type="hidden" name="RBM_EMP_VENDOR['.@$prow['Id'].']" value="'.@$prow['Validate_by'].'">
						   <input class="form-control" type="hidden" name="DBM_EMP_VENDOR['.@$prow['Id'].']" value="'.$_SESSION['EmpID'].'">
						   <input class="form-control" type="hidden" name="RBM_MAIL_STATUS['.@$prow['Id'].']" value="No">
						   <input class="form-control" type="hidden" name="DBM_MAIL_STATUS['.@$prow['Id'].']" value="No">
						    <input class="form-control" type="hidden" name="Season_code['.@$prow['Id'].']" value="'.@$prow['Season_Code'].'">
						     <input type="hidden" class="form-control CropId" name="CropId['.@$prow['Id'].']" value="'.@$prow['CropId'].'">
						     <input type="hidden" class="form-control Expected_date" name="Expected_date['.@$prow['Id'].']" value="'.date("Ymd",strtotime(@$prow['Expected_date'])).'">

						      

						   </div>';
						}else{
							$resarr[]='';
						}
						
			
			$resultarr[] = $resarr;
		}
	}
	
	if(isset($_POST['draw']))
	{
		$res['draw'] = @$_POST['draw'];	
	}else
	{
		$res['draw'] = 1;	
	}
	$res['recordsFiltered'] = $res['recordsTotal'];
    $res['data'] = $resultarr;
    $res['sql'] = $sql;
    $result = $res;
    echo json_encode($result);

?>