<?php 
include '../auto_load.php';

	$offset=@$_POST['start'];
	$length=@$_POST['length'];
	$ProductDivision=@$_POST['ProductDivision'];
	$Supply_Type=@$_POST['Supply_Type'];
	$Zone_id=@$_POST['Zone_id'];
	$Region_Id=@$_POST['Region_Id'];
	$Terrirory_Id=@$_POST['Terrirory_Id'];
	$Status=@$_POST['Status'];
	$Customer=@$_POST['Customer'];
	$QuotationType=@$_POST['QuotationType'];
	$SaleOrderType=@$_POST['SaleOrderType'];
	$length=$length == '-1' ? "All" : $length;
	$Emp_Id=isset($_SESSION['EmpID']) && ($_SESSION['EmpID'] !='Admin' || $_SESSION['EmpID'] !='SuperAdmin') ? $_SESSION['EmpID'] : '';
	$Dcode=$_SESSION['Dcode'];
	
	$sql="EXEC Sales_Indent_Report_Details @Length='$length',@Offset='$offset', @Emp_Id='".$Emp_Id."',@Product_Division='".$ProductDivision."',@Dcode='".$Dcode."',@Supply_Type='".$Supply_Type."',@Zone_id='".$Zone_id."',@Region_Id='".$Region_Id."',@Terrirory_Id='".$Terrirory_Id."',@Status='".$Status."',@QuotationType='".$QuotationType."',@Customer='".$Customer."',@SaleOrderType='".$SaleOrderType."'";
  	$stmt = sqlsrv_prepare($conn, $sql);
	sqlsrv_execute($stmt);
	$sno=$offset+1;
	$res['recordsTotal']=0;
	$resultarr=array();
	/* TOTALROW = COUNT(*) OVER(),ReqId
,(CASE WHEN ProductDivision='ras' THEN 'Cotton' WHEN ProductDivision='fcm' THEN 'Field Crop' WHEN ProductDivision='frg' THEN 'Forage Seeds' ELSE  ProductDivision END) AS ProductDivision
,(CASE WHEN SupplyType=1 THEN 'Direct Supply' WHEN SupplyType=2 THEN 'C&F Supply' ELSE  SupplyType END) AS SupplyType
,CONVERT (varchar(10),ReqDate, 105) AS ReqDate
,Tm_Tbl.EMPLNAME
,Trn_Tbl.RequestBy
,Trn_Tbl.ZoneId
,Zone_Tbl.ZONENAME
,Trn_Tbl.RegionId
,Region_Tbl.REGIONNAME
,Trn_Tbl.TerritoryId
,Tm_Tbl.TMNAME
,Trn_Tbl.CustomerCode
,Customer_Tbl.CustomerName
,Trn_Tbl.QuotationType
,Quotation_Tbl.Descrpition AS Quotation_Type_Name
,Trn_Tbl.SaleOrderType
,Sales_Order_Tbl.Descrpition AS SaleOrderType_Name */
	/*	,Material_Tbl.MaterialCode
,Material_Tbl.MaterialQtyInKg
,Material_Tbl.MaterialQtyInPkt*/
	while($prow = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC))
	{		
	 	$res['recordsTotal'] = @$prow['TOTALROW'];
	 	$resarr 	= array();
	 	$resarr[]	= $sno++;
		$resarr[] 	= @$prow['ReqId'];
		$resarr[] 	= @$prow['ReqDate'];	
		$resarr[] 	= @$prow['RequestBy'];		
		$resarr[] 	= @$prow['EMPLNAME'];		
		$resarr[] 	= @$prow['ProductDivision'];
		$resarr[] 	= @$prow['CropName'];
		$resarr[] 	= @$prow['ZONENAME'];
		$resarr[] 	= @$prow['REGIONNAME'];
		$resarr[] 	= @$prow['TMNAME'];
		$resarr[] 	= @$prow['CustomerCode'];
		$resarr[] 	= @$prow['CustomerName'];
		$resarr[] 	= @$prow['QuotationType'];
		$resarr[] 	= @$prow['SaleOrderType'];
		$resarr[] 	= @$prow['MaterialCode'];
		$resarr[] 	= @$prow['QtyInBag'];
		$resarr[] 	= @$prow['QtyInPkt'];
		$resarr[] 	= @$prow['QtyInKg'];

		$resarr[] 	= @$prow['Status_Text'];
		//$Poup_Details="<button class='btn btn-info popup_btn' >View</button>";
		// if(@$prow['CurrentStatus'] == 1 &&  @$prow['RejectionStatus'] == 1){ --> old status condition hide
		if(@$prow['CurrentStatus'] != 5 &&  @$prow['RejectionStatus'] != 7){
			$resarr[] 	= "<a href='edit_sales_indent_details?Id=".safe_encode(@$prow['SalesIndentId'])."'><button type='button' class='btn btn-success'><i class='fa fa-edit'></i></button></a>";
		}else{
			$resarr[] 	= "";
		}
		
		$resultarr[] = $resarr;
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