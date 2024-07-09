<?php
Class Sales_indent_zone_details 
{
	public $conn;
	function __construct($conn) {
    	$this->conn = $conn;
  	}

	public function Get_Sql_Data($sql)
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

  	public function zone_wise_sales_indent()
  	{
		$Emp_Id = isset($_SESSION['EmpID']) && ($_SESSION['EmpID'] !='Admin' || $_SESSION['EmpID'] !='SuperAdmin') ? $_SESSION['EmpID'] : '';
		$sql="EXEC sales_indent_zone_wise_report @Emp_Id='".$Emp_Id."'";
		$Result=$this->Get_Sql_Data($sql);
		return $Result;
  	}

  	public function region_wise_sales_indent($request)
  	{
  		$zone_code = $request['zone_id'];
		$Emp_Id = isset($_SESSION['EmpID']) && ($_SESSION['EmpID'] !='Admin' || $_SESSION['EmpID'] !='SuperAdmin') ? $_SESSION['EmpID'] : '';
		$sql="EXEC sales_indent_region_wise_report @Emp_Id='".$Emp_Id."',@Zone_id='".$zone_code."'";
		$Result=$this->Get_Sql_Data($sql);
		return $Result;
  	}

  	public function territory_wise_sales_indent($request)
  	{
  		$zone_code = $request['zone_id'];
		$Emp_Id = isset($_SESSION['EmpID']) && ($_SESSION['EmpID'] !='Admin' || $_SESSION['EmpID'] !='SuperAdmin') ? $_SESSION['EmpID'] : '';
		$sql="EXEC sales_indent_territory_wise_report @Emp_Id='".$Emp_Id."',@Zone_id='".$zone_code."'";
		$Result=$this->Get_Sql_Data($sql);
		return $Result;
  	}

  	public function crop_wise_sales_indent($request)
  	{
  		$zone_code = $request['zone_id'];
		$Emp_Id = isset($_SESSION['EmpID']) && ($_SESSION['EmpID'] !='Admin' || $_SESSION['EmpID'] !='SuperAdmin') ? $_SESSION['EmpID'] : '';
		$sql="EXEC sales_indent_crop_wise_report @Emp_Id='".$Emp_Id."',@Zone_id='".$zone_code."'";
		$Result=$this->Get_Sql_Data($sql);
		return $Result;
  	}

  	public function variety_wise_sales_indent($request)
  	{
  		$zone_code = $request['zone_id'];
  		$crop_code = $request['crop_id'];
		$Emp_Id = isset($_SESSION['EmpID']) && ($_SESSION['EmpID'] !='Admin' || $_SESSION['EmpID'] !='SuperAdmin') ? $_SESSION['EmpID'] : '';
		$sql="EXEC sales_indent_variety_wise_report @Emp_Id='".$Emp_Id."',@Zone_id='".$zone_code."',@Crop_id='".$crop_code."'";
		$Result=$this->Get_Sql_Data($sql);
		return $Result;
  	}
 }