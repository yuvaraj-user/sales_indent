<?php 

class Sto_Indent_Summary_Details{
	public $conn;
	function __construct($conn){
		$this->conn=$conn;
	}

	Public function Get_Zone_Wise_Summary_Details($user_input=array())
	{

		$offset=@$_POST['start'];
		$length=@$_POST['length'];
		$product_division_id=@$_POST['product_division_id'];
		$Zone_Id=@$_POST['ZoneId'];
		$Region_Id=@$_POST['RegionId'];
		$Territory_Id=@$_POST['Terrirory_Id'];
		$Crop_Id=@$_POST['CropCode'];
		$Sql="EXEC Sales_Indent_STO_Zone_Wise_Summary @Product_Division='".$product_division_id."',@Zone_Id='".$Zone_Id."',@Region_Id='".$Region_Id."',@Territory_Id='".$Territory_Id."',@Crop_Id='".$Crop_Id."'";
		$Sql_Dets=sqlsrv_query($this->conn,$Sql);
		$resultarr=array();
		while($value=sqlsrv_fetch_array($Sql_Dets))
		{
			$resarr = array();
			$resarr[] =utf8_encode($value['Zone_Name'])."<input type='hidden' class='table_name' value='zone'><input type='hidden' class='table_zone_id' value='".utf8_encode($value['Zone_id'])."'><input type='hidden' class='summary_status' value='0'>";
			$resarr[] =number_format($value['Indent_Qty']);
			$resarr[] =number_format($value['Approved_Qty']);
			$resarr[] =number_format($value['Delivery_Qty']);
			$resarr[] =number_format($value['Pending_Qty']);
			$resultarr[] = $resarr; 
		}
		$res['data'] = @$resultarr;
		$res['sql'] = @$Sql;
		$result = $res;
		return $result;
	}

	Public function Get_Crop_Wise_Summary_Details($user_input=array())
	{

		$offset=@$_POST['start'];
		$length=@$_POST['length'];
		$product_division_id=@$_POST['product_division_id'];
		$Zone_Id=@$_POST['ZoneId'];
		$Region_Id=@$_POST['RegionId'];
		$Territory_Id=@$_POST['Terrirory_Id'];
		$Crop_Id=@$_POST['CropCode'];
		$Sql="EXEC Sales_Indent_STO_Crop_Wise_Summary @Product_Division='".$product_division_id."',@Zone_Id='".$Zone_Id."',@Region_Id='".$Region_Id."',@Territory_Id='".$Territory_Id."',@Crop_Id='".$Crop_Id."'";
		$Sql_Dets=sqlsrv_query($this->conn,$Sql);
		$resultarr=array();
		while($value=sqlsrv_fetch_array($Sql_Dets))
		{
			$resarr = array();
			$resarr[] =utf8_encode($value['CropName'])."<input type='hidden' class='table_name' value='crop'><input type='hidden' class='table_crop_id' value='".utf8_encode($value['CropCode'])."'><input type='hidden' class='summary_status' value='0'>";
			$resarr[] =number_format($value['Indent_Qty']);
			$resarr[] =number_format($value['Approved_Qty']);
			$resarr[] =number_format($value['Delivery_Qty']);
			$resarr[] =number_format($value['Pending_Qty']);
			$resultarr[] = $resarr; 
		}
		$res['data'] = @$resultarr;
		$res['sql'] = @$Sql;
		$result = $res;
		return $result;
	}

	Public function Get_Region_Wise_Summary_Details($user_input=array())
	{

		$offset=@$_POST['start'];
		$length=@$_POST['length'];
		$product_division_id=@$_POST['product_division_id'];
		$Zone_Id=@$_POST['ZoneId'];
		$Region_Id=@$_POST['RegionId'];
		$Territory_Id=@$_POST['Terrirory_Id'];
		$Crop_Id=@$_POST['CropCode'];
		$Sql="EXEC Sales_Indent_STO_Region_Wise_Summary @Product_Division='".$product_division_id."',@Zone_Id='".$Zone_Id."',@Region_Id='".$Region_Id."',@Territory_Id='".$Territory_Id."',@Crop_Id='".$Crop_Id."'";
		$Sql_Dets=sqlsrv_query($this->conn,$Sql);
		$resultarr=array();
		while($value=sqlsrv_fetch_array($Sql_Dets))
		{
			$resarr = array();
			$resarr[] =utf8_encode($value['Region_Name'])."<input type='hidden' class='table_name' value='region'><input type='hidden' class='table_zone_id' value='".utf8_encode($value['zone_id'])."'>
			<input type='hidden' class='table_region_id' value='".utf8_encode($value['Region_Id'])."'>

			
			<input type='hidden' class='summary_status' value='0'>";
			$resarr[] =number_format($value['Indent_Qty']);
			$resarr[] =number_format($value['Approved_Qty']);
			$resarr[] =number_format($value['Delivery_Qty']);
			$resarr[] =number_format($value['Pending_Qty']);
			$resultarr[] = $resarr; 
		}
		$res['data'] = @$resultarr;
		$res['sql'] = @$Sql;
		$result = $res;
		return $result;
	}

	Public function Get_Territory_Wise_Summary_Details($user_input=array())
	{

		$offset=@$_POST['start'];
		$length=@$_POST['length'];
		$product_division_id=@$_POST['product_division_id'];
		$Zone_Id=@$_POST['ZoneId'];
		$Region_Id=@$_POST['RegionId'];
		$Territory_Id=@$_POST['Terrirory_Id'];
		$Crop_Id=@$_POST['CropCode'];
		$Sql="EXEC Sales_Indent_STO_Territory_Wise_Summary @Product_Division='".$product_division_id."',@Zone_Id='".$Zone_Id."',@Region_Id='".$Region_Id."',@Territory_Id='".$Territory_Id."',@Crop_Id='".$Crop_Id."'";
		$Sql_Dets=sqlsrv_query($this->conn,$Sql);
		$resultarr=array();
		while($value=sqlsrv_fetch_array($Sql_Dets))
		{
			$resarr = array();

			$resarr[] =utf8_encode($value['Plant_Name'])."<input type='hidden' class='table_name' value='territory'><input type='hidden' class='table_zone_id' value='".utf8_encode($Zone_Id)."'><input type='hidden' class='table_region_id' value='".utf8_encode($Region_Id)."'><input type='hidden' class='table_territory_id' value='".utf8_encode($value['Plant_id'])."'><input type='hidden' class='summary_status' value='0'>";
			$resarr[] =number_format($value['Indent_Qty']);
			$resarr[] =number_format($value['Approved_Qty']);
			$resarr[] =number_format($value['Delivery_Qty']);
			$resarr[] =number_format($value['Pending_Qty']);
			$resultarr[] = $resarr; 
		}
		$res['data'] = @$resultarr;
		$res['sql'] = @$Sql;
		$result = $res;
		return $result;
	}
	Public function Get_Variety_Wise_Summary_Details($user_input=array())
	{

		$offset=@$_POST['start'];
		$length=@$_POST['length'];
		$product_division_id=@$_POST['product_division_id'];
		$Zone_Id=@$_POST['ZoneId'];
		$Region_Id=@$_POST['RegionId'];
		$Territory_Id=@$_POST['Terrirory_Id'];
		$Crop_Id=@$_POST['CropCode'];
		$Sql="EXEC Sales_Indent_STO_Variety_Wise_Summary @Product_Division='".$product_division_id."',@Zone_Id='".$Zone_Id."',@Region_Id='".$Region_Id."',@Territory_Id='".$Territory_Id."',@Crop_Id='".$Crop_Id."'";
		$Sql_Dets=sqlsrv_query($this->conn,$Sql);
		$resultarr=array();
		while($value=sqlsrv_fetch_array($Sql_Dets))
		{
			$resarr = array();
			
			$resarr[] =utf8_encode($value['Product_Code'])."<input type='hidden' class='table_name' value='variety'><input type='hidden' class='table_variety_id' value='".utf8_encode($value['Product_Name'])."'><input type='hidden' class='table_crop_id' value='".utf8_encode($Crop_Id)."'><input type='hidden' class='summary_status' value='0'>";
			$resarr[] =number_format($value['Indent_Qty']);
			$resarr[] =number_format($value['Approved_Qty']);
			$resarr[] =number_format($value['Delivery_Qty']);
			$resarr[] =number_format($value['Pending_Qty']);
			$resultarr[] = $resarr; 
		}
		$res['data'] = @$resultarr;
		$res['sql'] = @$Sql;
		$result = $res;
		return $result;
	}


	Public function Get_Approval_within_limit_Count($user_input=array())
	{

		$offset=@$_POST['start'];
		$length=@$_POST['length'];
		$Emp_Id=isset($_SESSION['EmpID']) && ($_SESSION['EmpID'] !='Admin' || $_SESSION['EmpID'] !='SuperAdmin') ? $_SESSION['EmpID'] : '';
		$Dcode=$_SESSION['Dcode'];
		$Sql="EXEC Sales_Indent_STO_Widget_Count_Details_For_PlantWise @Dcode='".$Dcode."',@Emp_Id='".$Emp_Id."'";
		$Sql_Dets=sqlsrv_query($this->conn,$Sql);
		$resultarr=array();
		while($value=sqlsrv_fetch_array($Sql_Dets))
		{
			$resarr = array();
			
			$resarr[] =$value['Plant_Name'];
			$resarr[] =$value['Approve_Qty_With_In_Limit'];

			$resultarr[] = $resarr; 
		}
		$res['data'] = @$resultarr;
		$res['sql'] = @$Sql;
		$result = $res;
		return $result;
	}



	Public function Get_Approval_within_limit_Count_Validate($user_input=array())
	{

		$offset=@$_POST['start'];
		$length=@$_POST['length'];
		$Emp_Id=isset($_SESSION['EmpID']) && ($_SESSION['EmpID'] !='Admin' || $_SESSION['EmpID'] !='SuperAdmin') ? $_SESSION['EmpID'] : '';
		$Dcode=$_SESSION['Dcode'];
		$Sql="EXEC Sales_Indent_STO_Widget_Count_Details_For_PlantWise_Validate @Dcode='".$Dcode."',@Emp_Id='".$Emp_Id."'";
		$Sql_Dets=sqlsrv_query($this->conn,$Sql);
		$resultarr=array();
		while($value=sqlsrv_fetch_array($Sql_Dets))
		{
			$resarr = array();
			
			$resarr[] =$value['Plant_Name'];
			$resarr[] =$value['Approve_Qty_With_In_Limit'];

			$resultarr[] = $resarr; 
		}
		$res['data'] = @$resultarr;
		$res['sql'] = @$Sql;
		$result = $res;
		return $result;
	}

	Public function Get_Approval_within_limit_Count_Approve_Exceed($user_input=array())
	{

		$offset=@$_POST['start'];
		$length=@$_POST['length'];
		$Emp_Id=isset($_SESSION['EmpID']) && ($_SESSION['EmpID'] !='Admin' || $_SESSION['EmpID'] !='SuperAdmin') ? $_SESSION['EmpID'] : '';
		$Dcode=$_SESSION['Dcode'];
		$Sql="EXEC Sales_Indent_STO_Widget_Count_Details_For_PlantWise_Approve @Dcode='".$Dcode."',@Emp_Id='".$Emp_Id."'";
		$Sql_Dets=sqlsrv_query($this->conn,$Sql);
		$resultarr=array();
		while($value=sqlsrv_fetch_array($Sql_Dets))
		{
			$resarr = array();
			
			$resarr[] =$value['Plant_Name'];
			$resarr[] =$value['Approve_Qty_With_In_Limit'];

			$resultarr[] = $resarr; 
		}
		$res['data'] = @$resultarr;
		$res['sql'] = @$Sql;
		$result = $res;
		return $result;
	}

	public function get_indent_sumary_details($request)
	{
		$product_division_id=$_POST['product_division_id'];
		$Emp_Id=isset($_SESSION['EmpID']) && ($_SESSION['EmpID'] !='Admin' || $_SESSION['EmpID'] !='SuperAdmin') ? $_SESSION['EmpID'] : '';
		$Dcode=$_SESSION['Dcode'];
		$Emp_Id = '';
		$from          = $_POST['from'];
		$zone_id       = isset($_POST['zone_id']) ? $_POST['zone_id'] : '';
		$region_id     = isset($_POST['region_id']) ? $_POST['region_id'] : '';
		$territory_id  = isset($_POST['territory_id']) ? $_POST['territory_id'] : '';
		$crop_id       = isset($_POST['crop_id']) ? $_POST['crop_id'] : '';
		$material_code = isset($_POST['material_code']) ? $_POST['material_code'] : '';

		// $code = isset($_POST['code']) ? $_POST['code'] : '';
		$sql = "SELECT Trn_Tbl.ReqId,Zone_Tbl.ZONENAME AS Zone_Name,Material_Tbl.QtyInPkt,Material_Tbl.QtyInBag,Region_Tbl.REGIONNAME,Plant_Tbl.PlantName,Material_Tbl.MaterialCode
		FROM Sales_Indent_STO AS Trn_Tbl
		INNER JOIN Sales_Indent_STO_Material_Details AS Material_Tbl ON Material_Tbl.SalesIndentId=Trn_Tbl.SalesIndentId			
		INNER JOIN (SELECT DISTINCT DATAAREAID,ZONENAME,SAPZONEID FROM RASI_ZONETABLE) AS Zone_Tbl ON Zone_Tbl.SAPZONEID=Trn_Tbl.ZoneId AND Zone_Tbl.DATAAREAID=Trn_Tbl.ProductDivision
		INNER JOIN (SELECT DISTINCT DATAAREAID,REGIONNAME,SAPREGIONID FROM RASI_REGIONTABLE) AS Region_Tbl ON Region_Tbl.SAPREGIONID=Trn_Tbl.RegionId AND Region_Tbl.DATAAREAID=Trn_Tbl.ProductDivision
		LEFT JOIN RASI_Role_Mapping AS Mapping ON Mapping.Requester_Emp_Id=Trn_Tbl.RequestBy AND Mapping.Product_Division=Trn_Tbl.ProductDivision
		LEFT JOIN Logistics_SaleIndent_SaleOrder AS Logistics_Tbl ON Logistics_Tbl.Indent_No=Trn_Tbl.ReqId
		INNER JOIN (SELECT DISTINCT CropCode,CropName FROM Master_Stock_Prod_2_Crop) AS Crop_Tbl ON Crop_Tbl.CropCode=Trn_Tbl.CropId
		LEFT JOIN EMPLTABLE AS EMPLTABLE ON EMPLTABLE.EMPLID=Trn_Tbl.RequestBy
		LEFT JOIN DIRPARTYTABLE AS DIRPARTYTABLE ON DIRPARTYTABLE.RECID=EMPLTABLE.RECID
		LEFT JOIN CNFPlants AS Plant_Tbl ON Plant_Tbl.PlantCode=Trn_Tbl.PlantId 
		WHERE 1=1 AND  Mapping.Type='STO_Indent'";

		if($product_division_id != '') {
			$sql .= " AND Trn_Tbl.ProductDivision = '".$product_division_id."'";
		}

		if(isset($Emp_Id) && $Emp_Id != '' && $Emp_Id != 'ADMIN') {
			$sql .= " AND (Mapping.Requester_Emp_Id='".$Emp_Id."' OR Mapping.Recommender_Emp_Id='".$Emp_Id."' OR Mapping.Recommender_Level_2_Emp_Id='".$Emp_Id."' OR Mapping.Approver_Emp_Id='".$Emp_Id."')";
		}

		if(isset($Dcode) && $Dcode != '' && $Dcode == 'TM') {
			$sql .= " AND Trn_Tbl.RequestBy='".$Emp_Id."'";
		}

		if($from == 'zone' && (isset($zone_id) && $zone_id != '')) {
			$sql .= " AND Trn_Tbl.ZoneId = '".$zone_id."'";
		} elseif($from == 'crop' && (isset($crop_id) && $crop_id != '')) {
			$sql .= " AND Trn_Tbl.CropId = '".$crop_id."'";
		} elseif($from == 'region' && (isset($zone_id) && $zone_id != '') && (isset($region_id) && $region_id != '')) {
			$sql .= " AND Trn_Tbl.ZoneId = '".$zone_id."' AND Trn_Tbl.RegionId = '".$region_id."'";
		} elseif($from == 'territory' && (isset($zone_id) && $zone_id != '') && (isset($region_id) && $region_id != '') && (isset($territory_id) && $territory_id != '')) {
			$sql .= " AND Trn_Tbl.ZoneId = '".$zone_id."' AND Trn_Tbl.RegionId = '".$region_id."' AND Trn_Tbl.PlantId = '".$territory_id."'";
		} elseif($from == 'variety' && (isset($crop_id) && $crop_id != '') && (isset($material_code) && $material_code != '')) {
			$sql .= " AND Trn_Tbl.CropId = '".$crop_id."' AND Material_Tbl.MaterialCode = '".$material_code."'";
		}

		// echo $sql;exit;

		$Sql_Dets=sqlsrv_query($this->conn,$sql);
		$resultarr=array();
		while($value=sqlsrv_fetch_array($Sql_Dets))
		{
			$resultarr[] = $value; 
		}	

		return $resultarr;
	}	

}

?>