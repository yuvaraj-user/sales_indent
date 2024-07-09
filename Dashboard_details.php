<?php
 class Dashboard_details
 {
 	
	public $conn;
	function __construct($conn) {
    	$this->conn = $conn;
  	}

	private function Get_Sql_Data($sql)
	{
		$result_array = array();
		$Sql_Details  = sqlsrv_prepare($this->conn, $sql);
		sqlsrv_execute($Sql_Details);
		while($sql_result = sqlsrv_fetch_array($Sql_Details,SQLSRV_FETCH_ASSOC))
		{
			$result_array[] = $sql_result;
		}
		return $result_array;
	}

	public function get_businesss_year($business_year = '')
	{
		$sql = "SELECT FORMAT(From_Date,'yyyy-MM-dd') as From_Date,FORMAT(To_Date,'yyyy-MM-dd') as To_Date from CustomerOnboarding_Config_Business_Year";

		if($business_year == '') {
			 $sql .= " where Default_Value = '1'";
		} else {
			 $sql .= " where Business_Year = '".$business_year."'";
		}
		$result = $this->Get_Sql_Data($sql);
		return $result;
	}


	public function get_mapped_product_division($emp_id,$role)
	{
		$sql = "SELECT DISTINCT(Master_Division.Sales_Org),Master_Division.Division from RASI_Role_Mapping_With_Crop as role_mapping
		left join Master_Division ON Master_Division.Division = role_mapping.Product_Division where 1=1";

		if($role == "Recommender") {
			$sql .= " AND (role_mapping.Recommender_Emp_Id = '".$emp_id."' OR role_mapping.Recommender_Emp_Id = '".$emp_id."' OR role_mapping.Recommender_Level_2_Emp_Id = '".$emp_id."' OR role_mapping.Approver_Emp_Id = '".$emp_id."')";
		} elseif($role == 'Requester') {
			$sql .= " AND role_mapping.Requester_Emp_Id = '".$emp_id."'";
		}

		$result = $this->Get_Sql_Data($sql);
		return $result;
	}


	public function recommender_role_check($emp_id)
	{
		$sql = "SELECT COUNT(*) as recommender_count from RASI_Role_Mapping_With_Crop as role_mapping
		where (role_mapping.Recommender_Emp_Id = '".$emp_id."' OR role_mapping.Recommender_Emp_Id = '".$emp_id."' OR role_mapping.Recommender_Level_2_Emp_Id = '".$emp_id."' OR role_mapping.Approver_Emp_Id = '".$emp_id."')";

		$result = $this->Get_Sql_Data($sql);
		return $result;

	}

 	public function sales_indent_turnover_amount($request)
 	{
 		$emp_id  = $_SESSION['EmpID'];
 		if($request['sortby'] == 'current_season') {
 			$from_date =  $this->get_businesss_year()[0]['From_Date'];
 			$to_date   =  $this->get_businesss_year()[0]['To_Date'];


			$is_recommender = $this->recommender_role_check($emp_id)[0]['recommender_count'];
			
			$role = ($is_recommender > 0) ? "Recommender" : "Requester";

			// all product division list 
 			$mapped_all_division = $this->get_mapped_product_division($emp_id,$role);

 			// mapped product division top 1 division data select 
 			// echo "<pre>";print_r($this->get_mapped_product_division($emp_id,$role));exit;
 			$product_division = $this->get_mapped_product_division($emp_id,$role)[0]['Division']; 

 			// filter the data division based functionality
 			if(isset($request['product_division'])) {
				$product_division = $request['product_division'];  				
 			}


	 		$query = "SELECT MONTH(Sales_Indent.ReqDate) as month,SUM(CASE WHEN material_details.CurrentStatus = '5' AND  material_details.Limit_Exceed = '0' THEN material_details.Total_Price ELSE 0 END) as With_in_limit_Approved,
	 		SUM(CASE WHEN material_details.CurrentStatus = '7' AND  material_details.Limit_Exceed = '0' THEN material_details.Total_Price ELSE 0 END) as With_in_limit_rejected,
	 		SUM(CASE WHEN material_details.CurrentStatus != '5' AND  material_details.CurrentStatus != '7' AND  material_details.Limit_Exceed = '0' THEN material_details.Total_Price ELSE 0 END) as With_in_limit_pending,
	 		SUM(CASE WHEN material_details.CurrentStatus = '5' AND  material_details.Limit_Exceed = '1' THEN material_details.Total_Price ELSE 0 END) as limit_exceed_Approved,
	 		SUM(CASE WHEN material_details.CurrentStatus = '7' AND  material_details.Limit_Exceed = '1' THEN material_details.Total_Price ELSE 0 END) as limit_exceed_rejected,
	 		SUM(CASE WHEN material_details.CurrentStatus != '5' AND  material_details.CurrentStatus != '7' AND  material_details.Limit_Exceed = '1' THEN material_details.Total_Price ELSE 0 END) as limit_exceed_pending,
	 		Sales_Indent.ProductDivision,MONTH(Sales_Indent.ReqDate) as month from RASI_Role_Mapping_With_Crop as role_mapping
	 		inner join Sales_Indent on Sales_Indent.RequestBy =  role_mapping.Requester_Emp_Id
	 		inner join Sales_Indent_Material_Details as material_details ON Sales_Indent.SalesIndentId = material_details.SalesIndentId 
	 		where 1=1";

			if($role == 'Recommender') {
	 			$query .= " AND (role_mapping.Recommender_Emp_Id = '".$emp_id."' OR role_mapping.Recommender_Emp_Id = '".$emp_id."' OR role_mapping.Recommender_Level_2_Emp_Id = '".$emp_id."' OR role_mapping.Approver_Emp_Id = '".$emp_id."')";
			} elseif($role == 'Requester') {
	 			$query .= " AND role_mapping.Requester_Emp_Id = '".$emp_id."'";
			}

	 		$query .= " AND Sales_Indent.ReqDate BETWEEN '".$from_date."' AND '".$to_date."' AND Sales_Indent.ProductDivision = '".$product_division."'
	 		group by Sales_Indent.ProductDivision,MONTH(Sales_Indent.ReqDate)";


 			$result = $this->Get_Sql_Data($query);

 			$category = array();
 			$With_in_limit_pending  = array();
 			$With_in_limit_Approved = array();
 			$With_in_limit_rejected = array();
 			$limit_exceed_pending   = array();
 			$limit_exceed_Approved  = array();
 			$limit_exceed_rejected  = array();
			

 			for($i = 0;$i < date('m',strtotime($to_date)); $i++) {
	 			$dateObj   = DateTime::createFromFormat('m', ($i+1));
				$monthName = $dateObj->format('M'); 
	 			$category[$i] = $monthName; 

	 			$With_in_limit_pending[$i]  = $With_in_limit_Approved[$i] = $With_in_limit_rejected[$i] =  $limit_exceed_pending[$i]  = $limit_exceed_Approved[$i] = $limit_exceed_rejected[$i] = 0;
	 			foreach($result as $key => $value) {
	 				if(($i+1) == $value['month']) {
	 					//With in limit  
	 					$With_in_limit_pending[$i]  = number_format($value['With_in_limit_pending']/100000,2,'.','');
	 					$With_in_limit_Approved[$i] = number_format($value['With_in_limit_Approved']/100000,2,'.','');
	 					$With_in_limit_rejected[$i] = number_format($value['With_in_limit_rejected']/100000,2,'.','');

	 					//limit exceed 
	 					$limit_exceed_pending[$i]  = number_format($value['limit_exceed_pending']/100000,2,'.','');
	 					$limit_exceed_Approved[$i] = number_format($value['limit_exceed_Approved']/100000,2,'.','');
	 					$limit_exceed_rejected[$i] = number_format($value['limit_exceed_rejected']/100000,2,'.','');
	 				}
	 			}
 			}

 			$final_result = [
 				'category' => $category,'With_in_limit_pending' => $With_in_limit_pending,'With_in_limit_Approved' => $With_in_limit_Approved,'With_in_limit_rejected' => $With_in_limit_rejected,'limit_exceed_pending' => $limit_exceed_pending,'limit_exceed_Approved' => $limit_exceed_Approved,'limit_exceed_rejected' => $limit_exceed_rejected,'mapped_all_division' => $mapped_all_division
 			];

 		}

 		return $final_result;
 	}

 	public function sales_indent_plant_percentage($request)
 	{
 		$emp_id  = $_SESSION['EmpID'];

 		$is_recommender = $this->recommender_role_check($emp_id)[0]['recommender_count'];

 		$role = ($is_recommender > 0) ? "Recommender" : "Requester";

 		$dashboard_det = "EXEC Sales_Indent_Widget_Count_Details @Emp_Id='".$_SESSION['EmpID']."',@Dcode='".$_SESSION['Dcode']."'";
 		$salesadv       = sqlsrv_query($this->conn,$dashboard_det);
 		$fetch_wait   = sqlsrv_fetch_array($salesadv);
        $Total_Count  =  $fetch_wait['Total_Count'];

 		$query =  "SELECT COUNT(*) as count,Sales_Indent.PlantId,CNF_Plant_Table.Plant_Name from Sales_Indent 
 		INNER JOIN Sales_Indent_Material_Details ON Sales_Indent_Material_Details.SalesIndentId=Sales_Indent.SalesIndentId
 		LEFT JOIN RASI_Role_Mapping_With_Crop AS Mapping ON Mapping.Requester_Emp_Id=Sales_Indent.RequestBy 
 		INNER JOIN (select distinct Season_Code,Default_Season from Master_Season) as Master_Season
 		ON Sales_Indent_Material_Details.season = Master_Season.Season_Code and Master_Season.Default_Season = 1
 		AND Mapping.Product_Division=Sales_Indent.ProductDivision AND Mapping.Sub_Type =
 		(CASE WHEN Sales_Indent.SupplyType='1' THEN 'Direct_Supply'  ELSE 'C&F_Supply' END) 
 		AND Mapping.Crop=Sales_Indent.CropId AND Mapping.Type='Sales_Indent'
 		left join Sales_Indnet_User_Plant_Master as CNF_Plant_Table on CNF_Plant_Table.Plant_Code=Sales_Indent.PlantId and CNF_Plant_Table.Emp_Code=Sales_Indent.RequestBy
 		WHERE 1=1 and Sales_Indent_Material_Details.CurrentStatus !='0'";

 		if($role == 'Requester') {
 		 $query .=  " AND RequestBy = '".$emp_id."'";
 		} else {
 		 $query .=  " AND RequestBy IN (
			SELECT DISTINCT Requester_Emp_Id from RASI_Role_Mapping_With_Crop where Recommender_Emp_Id = '".$emp_id."' or Recommender_Level_2_Emp_Id = '".$emp_id."' or Approver_Emp_Id = '".$emp_id."'
			and type = 'Sales_Indent'
			 )";
 		}
 		$query .= " group by Sales_Indent.PlantId,CNF_Plant_Table.Plant_Name";


		$result_array = array();
		$Sql_Details  = sqlsrv_prepare($this->conn, $query);
		sqlsrv_execute($Sql_Details);
		$i = 0;
		while($sql_result = sqlsrv_fetch_array($Sql_Details,SQLSRV_FETCH_ASSOC))
		{
			$plantname = !is_null($sql_result['Plant_Name']) ? $sql_result['Plant_Name'] : '';
			$result_array[$i]['name'] = ($plantname != '') ? $sql_result['PlantId'].'('.$plantname.')' : $sql_result['PlantId'];
			$result_array[$i]['data'] = floatval(number_format((($sql_result['count']/$Total_Count) * 100),2,'.',''));
			$i++;
		}

 		return $result_array;

 	}


 	public function get_employee_name($emp_code)
 	{
 		$qry = "SELECT Employee_Name from HR_Master_Table where Employee_Code = '".$emp_code."'";
 		$result = $this->Get_Sql_Data($qry);
 		return (COUNT($result) > 0) ? $result[0]['Employee_Name'] : '';
 	}

 	public function get_indent_status_details($request)
 	{
 		$status = ($request['multiple_status'] == 1) ? "'".implode("','",explode(',',$request['status']))."'" : $request['status'];

 		$query =  "SELECT Sales_Indent.ReqId,FORMAT(Sales_Indent.ReqDate,'dd-MM-yyyy') as ReqDate,Sales_Indent.ProductDivision,Sales_Indent_Material_Details.CurrentStatus,Sales_Indent_Material_Details.MaterialCode,Sales_Indent_Material_Details.QtyInBag,Sales_Indent_Material_Details.QtyInKg,Sales_Indent_Material_Details.QtyInPkt,Mapping.Recommender_Emp_Id,Mapping.Recommender_Level_2_Emp_Id,Mapping.Approver_Emp_Id,Sales_Indent_Material_Details.Limit_Exceed,SD_CUS_MASTER.Customer_Name from Sales_Indent 
 		INNER JOIN Sales_Indent_Material_Details ON Sales_Indent_Material_Details.SalesIndentId=Sales_Indent.SalesIndentId
 		LEFT JOIN RASI_Role_Mapping_With_Crop AS Mapping ON Mapping.Requester_Emp_Id=Sales_Indent.RequestBy 
 		INNER JOIN (select distinct Season_Code,Default_Season from Master_Season) as Master_Season
 		ON Sales_Indent_Material_Details.season = Master_Season.Season_Code and Master_Season.Default_Season = 1
 		AND Mapping.Product_Division=Sales_Indent.ProductDivision AND Mapping.Sub_Type =
 		(CASE WHEN Sales_Indent.SupplyType='1' THEN 'Direct_Supply'  ELSE 'C&F_Supply' END) 
 		AND Mapping.Crop=Sales_Indent.CropId AND Mapping.Type='Sales_Indent'
 		left join Sales_Indnet_User_Plant_Master as CNF_Plant_Table on CNF_Plant_Table.Plant_Code=Sales_Indent.PlantId and CNF_Plant_Table.Emp_Code=Sales_Indent.RequestBy and CNF_Plant_Table.Emp_Code=Sales_Indent.RequestBy
 		left join Logistics_TRN_Sales_Quotation_Creation as despatch_tbl on Sales_Indent.ReqId =  despatch_tbl.INDENT_NO 
 		left join SD_CUS_MASTER ON SD_CUS_MASTER.Customer = Sales_Indent.CustomerCode
 		WHERE 1=1 and Sales_Indent_Material_Details.CurrentStatus IN (".$status.") AND Sales_Indent_Material_Details.RejectionStatus = '1' AND (Mapping.Requester_Emp_Id='".$_SESSION['EmpID']."' OR Mapping.Recommender_Emp_Id='".$_SESSION['EmpID']."' OR Mapping.Recommender_Level_2_Emp_Id='".$_SESSION['EmpID']."' OR Mapping.Approver_Emp_Id='".$_SESSION['EmpID']."') AND despatch_tbl.INDENT_NO IS NULL";


		$result_array = array();
		$Sql_Details  = sqlsrv_query($this->conn, $query);
		$i = 0;
		while($sql_result = sqlsrv_fetch_array($Sql_Details,SQLSRV_FETCH_ASSOC))
		{
			//basic indent details 
			$result_array[$i]['ReqId']           = $sql_result['ReqId'];
			$result_array[$i]['Customer_name']   = $sql_result['Customer_Name'];
			$result_array[$i]['ReqDate'] = $sql_result['ReqDate'];
			$result_array[$i]['ProductDivision']   = ($sql_result['ProductDivision'] == 'ras') ? 'Cotton' : (($sql_result['ProductDivision'] == 'fcm') ? 'Field Crop' : 'Forage');
			$result_array[$i]['MaterialCode']   = $sql_result['MaterialCode'];
			$result_array[$i]['QtyInBag']   = $sql_result['QtyInBag'];
			$result_array[$i]['QtyInKg']   = $sql_result['QtyInKg'];
			$result_array[$i]['QtyInPkt']   = $sql_result['QtyInPkt'];
			$result_array[$i]['limit_exceed']   = $sql_result['Limit_Exceed'];
			
			// pending status approver,recommender details
			$emp_id = $emp_name = '';
			if($sql_result['CurrentStatus'] == '1' || $sql_result['CurrentStatus'] == '2') {
				$emp_id   =  $sql_result['Recommender_Emp_Id'];
				$emp_name =  $this->get_employee_name($sql_result['Recommender_Emp_Id']);
			} else if($sql_result['CurrentStatus'] == '3' || $sql_result['CurrentStatus'] == '6') {
				$emp_id   =  $sql_result['Recommender_Level_2_Emp_Id'];
				$emp_name =  $this->get_employee_name($sql_result['Recommender_Level_2_Emp_Id']);
			} else if($sql_result['CurrentStatus'] == '4') {
				$emp_id   =  $sql_result['Approver_Emp_Id'];
				$emp_name =  $this->get_employee_name($sql_result['Approver_Emp_Id']);
			}

			$result_array[$i]['emp_id']     = $emp_id;
			$result_array[$i]['emp_name']   = $emp_name;

			// $result_array[$i]['wl_approver_emp_id']   = $sql_result['Recommender_Emp_Id'];

			$i++;
		}
 		return $result_array;

 	}

 	public function crop_wise_indent_summary($request)
 	{
 		$emp_id  = $_SESSION['EmpID'];

 		$is_recommender = $this->recommender_role_check($emp_id)[0]['recommender_count'];

 		$role = ($is_recommender > 0) ? "Recommender" : "Requester";

 		$query = "SELECT SUM(Sales_Indent_Material_Details.QtyInPkt) as indent_qty,
 		SUM(CASE WHEN Sales_Indent_Material_Details.CurrentStatus != '5' AND Sales_Indent_Material_Details.CurrentStatus != '7' THEN Sales_Indent_Material_Details.QtyInPkt ELSE 0 END) as approval_pending_qty,
 		SUM(CASE WHEN despatch_tbl.INDENT_NO IS NOT NULL THEN convert(FLOAT,despatch_tbl.QUANTITY) ELSE 0 END) as despatch_qty,
 		Sales_Indent.CropId,crop_master.CropName,SUM(COALESCE(Sales_Plan_Summary.SalesPlan_Qty,0)) as plan_qty from Sales_Indent
 		INNER JOIN Sales_Indent_Material_Details ON Sales_Indent_Material_Details.SalesIndentId=Sales_Indent.SalesIndentId
 		LEFT JOIN RASI_Role_Mapping_With_Crop AS Mapping ON Mapping.Requester_Emp_Id=Sales_Indent.RequestBy 
 		INNER JOIN (select distinct Season_Code,Default_Season from Master_Season) as Master_Season
 		ON Sales_Indent_Material_Details.season = Master_Season.Season_Code and Master_Season.Default_Season = 1
 		AND Mapping.Product_Division=Sales_Indent.ProductDivision AND Mapping.Sub_Type =
 		(CASE WHEN Sales_Indent.SupplyType='1' THEN 'Direct_Supply'  ELSE 'C&F_Supply' END) 
 		AND Mapping.Crop=Sales_Indent.CropId AND Mapping.Type='Sales_Indent'
 		left join Sales_Indnet_User_Plant_Master as CNF_Plant_Table on CNF_Plant_Table.Plant_Code=Sales_Indent.PlantId and CNF_Plant_Table.Emp_Code=Sales_Indent.RequestBy and CNF_Plant_Table.Emp_Code=Sales_Indent.RequestBy
 		left join Master_Stock_Prod_2_Crop as crop_master on crop_master.CropCode = Sales_Indent.CropId
 		left join Logistics_TRN_Sales_Quotation_Creation as despatch_tbl on Sales_Indent.ReqId =  despatch_tbl.INDENT_NO 
 		left join Sales_Variety_Master on Sales_Variety_Master.Crop_Code = Sales_Indent.CropId AND Sales_Variety_Master.Material_Code = Sales_Indent_Material_Details.MaterialCode 
 		left join Sales_Plan_Summary on Sales_Plan_Summary.VarietyCode = Sales_Variety_Master.Variety_Code AND Sales_Plan_Summary.Zone_Code = Sales_Indent.ZoneId AND Sales_Plan_Summary.Region_Code = Sales_Indent.RegionId AND Sales_Plan_Summary.Territory_Code = Sales_Indent.TerritoryId
 		where 1 = 1 AND Sales_Indent_Material_Details.CurrentStatus != '7' AND Sales_Indent.ProductDivision = '".$request['product_division']."'";

 		if($role == 'Requester') {
 		 $query .=  " AND RequestBy = '".$emp_id."'";
 		} else {
 		 $query .=  " AND RequestBy IN (
			SELECT DISTINCT Requester_Emp_Id from RASI_Role_Mapping_With_Crop where Recommender_Emp_Id = '".$emp_id."' or Recommender_Level_2_Emp_Id = '".$emp_id."' or Approver_Emp_Id = '".$emp_id."'
			and type = 'Sales_Indent'
			 )";
 		} 
 		$query .=  " group by Sales_Indent.CropId,crop_master.CropName"; 

 		// echo $query;exit;
 		$result = $this->Get_Sql_Data($query);

 		return $result;

 	}


 	public function product_wise_indent_summary($request)
 	{
 		$emp_id  = $_SESSION['EmpID'];
 		
 		$is_recommender = $this->recommender_role_check($emp_id)[0]['recommender_count'];

 		$role = ($is_recommender > 0) ? "Recommender" : "Requester";

 		$query = "SELECT SUM(Sales_Indent_Material_Details.QtyInPkt) as indent_qty,
 		SUM(CASE WHEN Sales_Indent_Material_Details.CurrentStatus != '5' AND Sales_Indent_Material_Details.CurrentStatus != '7' THEN Sales_Indent_Material_Details.QtyInPkt ELSE 0 END) as approval_pending_qty,
 		SUM(CASE WHEN despatch_tbl.INDENT_NO IS NOT NULL THEN convert(FLOAT,despatch_tbl.QUANTITY) ELSE 0 END) as despatch_qty,
 		Sales_Indent_Material_Details.MaterialCode,SUM(COALESCE(Sales_Plan_Summary.SalesPlan_Qty,0)) as plan_qty from Sales_Indent
 		INNER JOIN Sales_Indent_Material_Details ON Sales_Indent_Material_Details.SalesIndentId=Sales_Indent.SalesIndentId
 		LEFT JOIN RASI_Role_Mapping_With_Crop AS Mapping ON Mapping.Requester_Emp_Id=Sales_Indent.RequestBy 
 		INNER JOIN (select distinct Season_Code,Default_Season from Master_Season) as Master_Season
 		ON Sales_Indent_Material_Details.season = Master_Season.Season_Code and Master_Season.Default_Season = 1
 		AND Mapping.Product_Division=Sales_Indent.ProductDivision AND Mapping.Sub_Type =
 		(CASE WHEN Sales_Indent.SupplyType='1' THEN 'Direct_Supply'  ELSE 'C&F_Supply' END) 
 		AND Mapping.Crop=Sales_Indent.CropId AND Mapping.Type='Sales_Indent'
 		left join Sales_Indnet_User_Plant_Master as CNF_Plant_Table on CNF_Plant_Table.Plant_Code=Sales_Indent.PlantId and CNF_Plant_Table.Emp_Code=Sales_Indent.RequestBy and CNF_Plant_Table.Emp_Code=Sales_Indent.RequestBy
 		left join Master_Stock_Prod_2_Crop as crop_master on crop_master.CropCode = Sales_Indent.CropId
 		left join Logistics_TRN_Sales_Quotation_Creation as despatch_tbl on Sales_Indent.ReqId =  despatch_tbl.INDENT_NO 
 		left join Sales_Variety_Master on Sales_Variety_Master.Crop_Code = Sales_Indent.CropId AND Sales_Variety_Master.Material_Code = Sales_Indent_Material_Details.MaterialCode 
 		left join Sales_Plan_Summary on Sales_Plan_Summary.VarietyCode = Sales_Variety_Master.Variety_Code AND Sales_Plan_Summary.Zone_Code = Sales_Indent.ZoneId AND Sales_Plan_Summary.Region_Code = Sales_Indent.RegionId AND Sales_Plan_Summary.Territory_Code = Sales_Indent.TerritoryId
 		where 1 = 1 AND Sales_Indent_Material_Details.CurrentStatus != '7' AND Sales_Indent.ProductDivision = '".$request['product_division']."'";

 		if($role == 'Requester') {
 		 $query .=  " AND RequestBy = '".$emp_id."'";
 		} else {
 		 $query .=  " AND RequestBy IN (
			SELECT DISTINCT Requester_Emp_Id from RASI_Role_Mapping_With_Crop where Recommender_Emp_Id = '".$emp_id."' or Recommender_Level_2_Emp_Id = '".$emp_id."' or Approver_Emp_Id = '".$emp_id."'
			and type = 'Sales_Indent'
			 )";
 		} 

 		$query .=  " AND Sales_Indent.CropId = '".$request['crop_id']."' group by Sales_Indent_Material_Details.MaterialCode"; 



 		$result = $this->Get_Sql_Data($query);

 		return $result;

 	}


 	public function zone_wise_indent_summary($request)
 	{
 		$emp_id  = $_SESSION['EmpID'];
 		
 		$is_recommender = $this->recommender_role_check($emp_id)[0]['recommender_count'];

 		$role = ($is_recommender > 0) ? "Recommender" : "Requester";

 		$query = "SELECT SUM(Sales_Indent_Material_Details.QtyInPkt) as indent_qty,
 		SUM(CASE WHEN Sales_Indent_Material_Details.CurrentStatus != '5' AND Sales_Indent_Material_Details.CurrentStatus != '7' THEN Sales_Indent_Material_Details.QtyInPkt ELSE 0 END) as approval_pending_qty,
 		SUM(CASE WHEN despatch_tbl.INDENT_NO IS NOT NULL THEN convert(FLOAT,despatch_tbl.QUANTITY) ELSE 0 END) as despatch_qty,
 		Sales_Indent.ZoneId,ZONE_MASTER.ZONE_NAME,SUM(COALESCE(Sales_Plan_Summary.SalesPlan_Qty,0)) as plan_qty from Sales_Indent
 		INNER JOIN Sales_Indent_Material_Details ON Sales_Indent_Material_Details.SalesIndentId=Sales_Indent.SalesIndentId
 		LEFT JOIN RASI_Role_Mapping_With_Crop AS Mapping ON Mapping.Requester_Emp_Id=Sales_Indent.RequestBy 
 		INNER JOIN (select distinct Season_Code,Default_Season from Master_Season) as Master_Season
 		ON Sales_Indent_Material_Details.season = Master_Season.Season_Code and Master_Season.Default_Season = 1
 		AND Mapping.Product_Division=Sales_Indent.ProductDivision AND Mapping.Sub_Type =
 		(CASE WHEN Sales_Indent.SupplyType='1' THEN 'Direct_Supply'  ELSE 'C&F_Supply' END) 
 		AND Mapping.Crop=Sales_Indent.CropId AND Mapping.Type='Sales_Indent'
 		left join Sales_Indnet_User_Plant_Master as CNF_Plant_Table on CNF_Plant_Table.Plant_Code=Sales_Indent.PlantId and CNF_Plant_Table.Emp_Code=Sales_Indent.RequestBy and CNF_Plant_Table.Emp_Code=Sales_Indent.RequestBy
 		left join Master_Stock_Prod_2_Crop as crop_master on crop_master.CropCode = Sales_Indent.CropId
 		left join Logistics_TRN_Sales_Quotation_Creation as despatch_tbl on Sales_Indent.ReqId =  despatch_tbl.INDENT_NO 
 		left join Sales_Variety_Master on Sales_Variety_Master.Crop_Code = Sales_Indent.CropId AND Sales_Variety_Master.Material_Code = Sales_Indent_Material_Details.MaterialCode 
 		left join Sales_Plan_Summary on Sales_Plan_Summary.VarietyCode = Sales_Variety_Master.Variety_Code AND Sales_Plan_Summary.Zone_Code = Sales_Indent.ZoneId AND Sales_Plan_Summary.Region_Code = Sales_Indent.RegionId AND Sales_Plan_Summary.Territory_Code = Sales_Indent.TerritoryId
 		left join Master_Division ON Master_Division.Division = Sales_Indent.ProductDivision
 		left join ZONE_MASTER on ZONE_MASTER.ZONE_CODE = Sales_Indent.ZoneId AND ZONE_MASTER.TYPE = Master_Division.Sales_Org where 1 = 1 AND Sales_Indent_Material_Details.CurrentStatus != '7' AND Sales_Indent.ProductDivision = '".$request['product_division']."'";

 		if($role == 'Requester') {
 		 $query .=  " AND RequestBy = '".$emp_id."'";
 		} else {
 		 $query .=  " AND RequestBy IN (
			SELECT DISTINCT Requester_Emp_Id from RASI_Role_Mapping_With_Crop where Recommender_Emp_Id = '".$emp_id."' or Recommender_Level_2_Emp_Id = '".$emp_id."' or Approver_Emp_Id = '".$emp_id."'
			and type = 'Sales_Indent'
			 )";
 		} 
 		
 		$query .=  " AND Sales_Indent.CropId = '".$request['crop_id']."' AND Sales_Indent_Material_Details.MaterialCode = '".$request['material_code']."' group by Sales_Indent.ZoneId,ZONE_MASTER.ZONE_NAME"; 
 		
 		$result = $this->Get_Sql_Data($query);

 		return $result;

 	}

 	 public function region_wise_indent_summary($request)
 	{
 		$emp_id  = $_SESSION['EmpID'];
 		
 		$is_recommender = $this->recommender_role_check($emp_id)[0]['recommender_count'];

 		$role = ($is_recommender > 0) ? "Recommender" : "Requester";

 		$query = "SELECT SUM(Sales_Indent_Material_Details.QtyInPkt) as indent_qty,
 		SUM(CASE WHEN Sales_Indent_Material_Details.CurrentStatus != '5' AND Sales_Indent_Material_Details.CurrentStatus != '7' THEN Sales_Indent_Material_Details.QtyInPkt ELSE 0 END) as approval_pending_qty,
 		SUM(CASE WHEN despatch_tbl.INDENT_NO IS NOT NULL THEN convert(FLOAT,despatch_tbl.QUANTITY) ELSE 0 END) as despatch_qty,
 		Sales_Indent.RegionId,REG_MASTER.REG_NAME,SUM(COALESCE(Sales_Plan_Summary.SalesPlan_Qty,0)) as plan_qty from Sales_Indent
 		INNER JOIN Sales_Indent_Material_Details ON Sales_Indent_Material_Details.SalesIndentId=Sales_Indent.SalesIndentId
 		LEFT JOIN RASI_Role_Mapping_With_Crop AS Mapping ON Mapping.Requester_Emp_Id=Sales_Indent.RequestBy 
 		INNER JOIN (select distinct Season_Code,Default_Season from Master_Season) as Master_Season
 		ON Sales_Indent_Material_Details.season = Master_Season.Season_Code and Master_Season.Default_Season = 1
 		AND Mapping.Product_Division=Sales_Indent.ProductDivision AND Mapping.Sub_Type =
 		(CASE WHEN Sales_Indent.SupplyType='1' THEN 'Direct_Supply'  ELSE 'C&F_Supply' END) 
 		AND Mapping.Crop=Sales_Indent.CropId AND Mapping.Type='Sales_Indent'
 		left join Sales_Indnet_User_Plant_Master as CNF_Plant_Table on CNF_Plant_Table.Plant_Code=Sales_Indent.PlantId and CNF_Plant_Table.Emp_Code=Sales_Indent.RequestBy and CNF_Plant_Table.Emp_Code=Sales_Indent.RequestBy
 		left join Master_Stock_Prod_2_Crop as crop_master on crop_master.CropCode = Sales_Indent.CropId
 		left join Logistics_TRN_Sales_Quotation_Creation as despatch_tbl on Sales_Indent.ReqId =  despatch_tbl.INDENT_NO 
 		left join Sales_Variety_Master on Sales_Variety_Master.Crop_Code = Sales_Indent.CropId AND Sales_Variety_Master.Material_Code = Sales_Indent_Material_Details.MaterialCode 
 		left join Sales_Plan_Summary on Sales_Plan_Summary.VarietyCode = Sales_Variety_Master.Variety_Code AND Sales_Plan_Summary.Zone_Code = Sales_Indent.ZoneId AND Sales_Plan_Summary.Region_Code = Sales_Indent.RegionId AND Sales_Plan_Summary.Territory_Code = Sales_Indent.TerritoryId
 		left join Master_Division ON Master_Division.Division = Sales_Indent.ProductDivision
 		left join REG_MASTER on REG_MASTER.REG_CODE = Sales_Indent.RegionId AND REG_MASTER.TYPE = Master_Division.Sales_Org where 1 = 1 AND Sales_Indent_Material_Details.CurrentStatus != '7' AND Sales_Indent.ProductDivision = '".$request['product_division']."'";

 		if($role == 'Requester') {
 		 $query .=  " AND RequestBy = '".$emp_id."'";
 		} else {
 		 $query .=  " AND RequestBy IN (
			SELECT DISTINCT Requester_Emp_Id from RASI_Role_Mapping_With_Crop where Recommender_Emp_Id = '".$emp_id."' or Recommender_Level_2_Emp_Id = '".$emp_id."' or Approver_Emp_Id = '".$emp_id."'
			and type = 'Sales_Indent'
			 )";
 		} 

 		$query .=  " AND Sales_Indent.CropId = '".$request['crop_id']."' AND Sales_Indent_Material_Details.MaterialCode = '".$request['material_code']."' 
 		AND Sales_Indent.ZoneId = '".$request['zone_id']."' 
 		group by Sales_Indent.RegionId,REG_MASTER.REG_NAME"; 

 		$result = $this->Get_Sql_Data($query);

 		return $result;

 	}


 	 public function territory_wise_indent_summary($request)
 	{
 		$emp_id  = $_SESSION['EmpID'];
 		
 		$is_recommender = $this->recommender_role_check($emp_id)[0]['recommender_count'];

 		$role = ($is_recommender > 0) ? "Recommender" : "Requester";

 		$query = "SELECT SUM(Sales_Indent_Material_Details.QtyInPkt) as indent_qty,
 		SUM(CASE WHEN Sales_Indent_Material_Details.CurrentStatus != '5' AND Sales_Indent_Material_Details.CurrentStatus != '7' THEN Sales_Indent_Material_Details.QtyInPkt ELSE 0 END) as approval_pending_qty,
 		SUM(CASE WHEN despatch_tbl.INDENT_NO IS NOT NULL THEN convert(FLOAT,despatch_tbl.QUANTITY) ELSE 0 END) as despatch_qty,
 		Sales_Indent.TerritoryId,TER_MASTER.TE_NAME,SUM(COALESCE(Sales_Plan_Summary.SalesPlan_Qty,0)) as plan_qty from Sales_Indent
 		INNER JOIN Sales_Indent_Material_Details ON Sales_Indent_Material_Details.SalesIndentId=Sales_Indent.SalesIndentId
 		LEFT JOIN RASI_Role_Mapping_With_Crop AS Mapping ON Mapping.Requester_Emp_Id=Sales_Indent.RequestBy 
 		INNER JOIN (select distinct Season_Code,Default_Season from Master_Season) as Master_Season
 		ON Sales_Indent_Material_Details.season = Master_Season.Season_Code and Master_Season.Default_Season = 1
 		AND Mapping.Product_Division=Sales_Indent.ProductDivision AND Mapping.Sub_Type =
 		(CASE WHEN Sales_Indent.SupplyType='1' THEN 'Direct_Supply'  ELSE 'C&F_Supply' END) 
 		AND Mapping.Crop=Sales_Indent.CropId AND Mapping.Type='Sales_Indent'
 		left join Sales_Indnet_User_Plant_Master as CNF_Plant_Table on CNF_Plant_Table.Plant_Code=Sales_Indent.PlantId and CNF_Plant_Table.Emp_Code=Sales_Indent.RequestBy and CNF_Plant_Table.Emp_Code=Sales_Indent.RequestBy
 		left join Master_Stock_Prod_2_Crop as crop_master on crop_master.CropCode = Sales_Indent.CropId
 		left join Logistics_TRN_Sales_Quotation_Creation as despatch_tbl on Sales_Indent.ReqId =  despatch_tbl.INDENT_NO 
 		left join Sales_Variety_Master on Sales_Variety_Master.Crop_Code = Sales_Indent.CropId AND Sales_Variety_Master.Material_Code = Sales_Indent_Material_Details.MaterialCode 
 		left join Sales_Plan_Summary on Sales_Plan_Summary.VarietyCode = Sales_Variety_Master.Variety_Code AND Sales_Plan_Summary.Zone_Code = Sales_Indent.ZoneId AND Sales_Plan_Summary.Region_Code = Sales_Indent.RegionId AND Sales_Plan_Summary.Territory_Code = Sales_Indent.TerritoryId
 		left join Master_Division ON Master_Division.Division = Sales_Indent.ProductDivision
 		left join TER_MASTER on TER_MASTER.TE_CODE = Sales_Indent.TerritoryId AND TER_MASTER.TYPE = Master_Division.Sales_Org where 1 = 1 AND Sales_Indent_Material_Details.CurrentStatus != '7' AND Sales_Indent.ProductDivision = '".$request['product_division']."'";
 		
 		if($role == 'Requester') {
 		 $query .=  " AND RequestBy = '".$emp_id."'";
 		} else {
 		 $query .=  " AND RequestBy IN (
			SELECT DISTINCT Requester_Emp_Id from RASI_Role_Mapping_With_Crop where Recommender_Emp_Id = '".$emp_id."' or Recommender_Level_2_Emp_Id = '".$emp_id."' or Approver_Emp_Id = '".$emp_id."'
			and type = 'Sales_Indent'
			 )";
 		} 

 		$query .=  " AND Sales_Indent.CropId = '".$request['crop_id']."' AND Sales_Indent_Material_Details.MaterialCode = '".$request['material_code']."' 
 		AND Sales_Indent.ZoneId = '".$request['zone_id']."' AND Sales_Indent.RegionId = '".$request['region_id']."' 
 		group by Sales_Indent.TerritoryId,TER_MASTER.TE_NAME"; 

 		$result = $this->Get_Sql_Data($query);

 		return $result;

 	}

 	
 } 
?>