Changed Files:

1.sales_indent_details.php
	function Approve_Details_For_With_In_Limit changes 



stored Procedure changes:
	
1.	Sales_Indent_Report_Details_WITh_CROP_Limit
		
		previous: 
		
		 AND (Mapping.Requester_Emp_Id='''+ @Emp_Id +''' OR Mapping.Recommender_Emp_Id='''+ @Emp_Id +''' OR Mapping.Recommender_Level_2_Emp_Id='''+ @Emp_Id +''' OR Mapping.Approver_Emp_Id='''+ @Emp_Id +''')

		 after:
	    
	    AND (Mapping.Requester_Emp_Id='''+ @Emp_Id +''' OR Mapping.Recommender_Emp_Id='''+ @Emp_Id +''')




	    important:

	    Sales_Indent_STO_Details_WIth_Limit_STO procedure 

	    from:

	    LEFT join (select distinct Plant_Code,Plant_Name,Sales_org from Master_Plant) as Plant_Tbl ON Plant_Tbl.Plant_Code=Trn_Tbl.receiving_plant 
AND Plant_Tbl.Sales_org=Trn_Tbl.ProductDivision  


to:
LEFT join (select distinct Plant_Code,Plant_Name,Sales_org from Master_Plant) as Plant_Tbl ON Plant_Tbl.Plant_Code=Trn_Tbl.PlantId 
AND Plant_Tbl.Sales_org=Trn_Tbl.ProductDivision  