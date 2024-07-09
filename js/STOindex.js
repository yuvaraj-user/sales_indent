   function Limit_Exceeds_Validation()
  {
    var Planned_Qty,Previous_Indent_Qty,current_Indnet_Qty,Total_Plan_Qty,Limit_Exceed,error_count,Exceed_Count=0;
    $(".Plan_qty").each(function(){
      Planned_Qty,Previous_Indent_Qty,current_Indnet_Qty,Total_Plan_Qty,Limit_Exceed,error_count=0;
      Planned_Qty=$(this).closest('tr').find(".Plan_qty").val();
      Previous_Indent_Qty=$(this).closest('tr').find(".Indent_Qty").val();
      current_Indnet_Qty=$(this).closest('tr').find(".QtyInPkt").val();
      if(Planned_Qty =='' || isNaN(Planned_Qty))
      {
        Planned_Qty=0;
      }

      if(Previous_Indent_Qty =='' || isNaN(Previous_Indent_Qty))
      {
        Previous_Indent_Qty=0;
      }

      if(current_Indnet_Qty =='' || isNaN(current_Indnet_Qty))
      {
        current_Indnet_Qty=0;
      }

      Total_Plan_Qty=parseInt(Previous_Indent_Qty)+parseInt(current_Indnet_Qty);
      Planned_Qty=parseInt(Planned_Qty);
      if(Total_Plan_Qty <= Planned_Qty ){
        error_count=0;
        Limit_Exceed=0;

      }else{
        error_count++;
          Limit_Exceed=1;
          Exceed_Count++;
      }
      $(this).closest('tr').find(".Limit_Exceed").val(Limit_Exceed);
      console.log({Planned_Qty,Previous_Indent_Qty,current_Indnet_Qty,Total_Plan_Qty,Limit_Exceed,error_count});
    });

    

    if(Exceed_Count >0){
     $(".Limit_Exceed_Status").val(1);
    }else{
      $(".Limit_Exceed_Status").val(0);
    }
     return Exceed_Count;
  }


  $(document).on("change",".quot_type,.sale_type",function(){
show_details();
  });

  function show_details(){
    var quotation_type =$(".quot_type").val();
    var Sales_Order_type  =$(".sale_type").val();
    if(Sales_Order_type !='' && quotation_type !=''){

$(".Sales_Indent_Div").css("display","flex")
    }else{
      $(".Sales_Indent_Div").css("display","none")
    }  

  }

   $(document).on("change",".plant_id",function(){
show_details_material();
  });

  function show_details_material(){
    //var customer_id =$(".customer_id").val();
    //var supply_type  =$(".supply_type").val();
    var plant_id  =$(".plant_id").val();
    if( plant_id !=''){

$(".Sales_Indent_Div_Material").css("display","flex")
    }else{
      $(".Sales_Indent_Div_Material").css("display","none")
    }

    

  }

   $(document).on("change",".crop_id",function(){
//show_details_crop();
  });



  function show_details_crop(){
    var crop_id =$(".product_division").val();
    
    if(crop_id !=''){

$(".Sales_Indent_Div_Crop").css("display","flex")
    }else{
      $(".Sales_Indent_Div_Crop").css("display","none")
      $(".Sales_Indent_Div_Crop").css("display","none")
    }

    

  }


   $(document).on("change",".product_division",function(){
    var product_division = $(".product_division").val();
     if ($(this).val()  == "ras") {
        $(".Purchase_group").val("001");
    } else if ($(this).val() == "fcm") {
        $(".Purchase_group").val("002");
    }else if ($(this).val() == "frg") {
        $(".Purchase_group").val("003");
    }



      var product_division = $(".product_division").val();
    var zone_id = $(".zone_id").val();
    var crop_code = $(".crop_id").val();

   var material_dets=get_material_details(product_division,crop_code,zone_id,0);
     $(this).closest('tr').find(".material_id").html(material_dets);


     
  });

   $(document).on("change",".product_division,.zone_id,.region_id",function(){
    var product_division = $(".product_division").val();
     var zone_id = $(".zone_id").val();
     var region_id = $(".region_id").val();
     Get_CNF_Plant_Details(product_division,region_id);
      Get_Direct_Plant_Details(product_division,region_id);
   })


  $(document).ready(function(){
    var product_division = $(".product_division").val();
     if (product_division  == "ras") {
        $(".Purchase_group").val("001");
    } else if (product_division == "fcm") {
        $(".Purchase_group").val("002");
    }
    
  
     $('.js-example-basic-single').select2();
     var product_division = $(".product_division").val();
     var supply_type = $(".supply_type").val();  

    role_based_filter(product_division,"Get_Zone_Details",0,0,0,0,0);
  show_details_crop();
 
    var zone_id = $(".zone_id").val();
     var region_id = $(".region_id").val();
     var territory_id = $(".territory_id").val();
     var crop_code = $(".crop_id").val();
      Get_CNF_Plant_Details(product_division,region_id);
      Get_Direct_Plant_Details(product_division,region_id);
    
         
  });
  $(document).on("change",".supply_type",function(){
   get_plant_details($(this).val(),0);
   
  });

  $(document).on("change",".supply_type",function(){

   get_plant_details($(this).val(),0);
   //var plant_name = $("#plant_id").text();
   //$("#plant_name").val(plant_name);
   
  });





 

$(document).ready(function(){
  $('.plant_name').select2();
  $('.plant_id').change(function(){
    var value = $(this).val();
    $('.plant_name').val(value);
    $('.plant_name').select2().trigger('change');
  });



  var product_division = $(".product_division").val();
    var zone_id = $(".zone_id").val();
    var crop_code = $(".crop_id").val();

   var material_dets=get_material_details(product_division,crop_code,zone_id,0);
     $(this).closest('tr').find(".material_id").html(material_dets);

     
});

 $(document).on("change",".crop_id",function(){
    var product_division = $(".product_division").val();
    var zone_id = $(".zone_id").val();
    var crop_code = $(this).val();
     
   
     var material_dets=get_material_details(product_division,crop_code,zone_id,1);
     $(this).closest('tr').find(".material_id").html(material_dets);
   
  });

 function get_material_details(product_division,crop_code,zone_id,status){
  var output="";
     $.ajax 
      ({
      type: "POST",
      url: "Common_Ajax.php",
       data:{"Action":"Get_Material_Details","product_division":product_division,"crop_code":crop_code,"zone_id":zone_id},
       async:false,
      success: function(data){
         result=JSON.parse(data);
         output=result.data;
        
        }
      });
      if(status == 0){
        $('.material_id').html(output);
        
        }else{
      return output;
        }



}


function Get_Crop_Details(product_division)
{

  var product_division=product_division;
  
  var option="<option value=''> Select Crop </option>";
   $.ajax 
      ({
      type: "POST",
      url: "Role_Based_Location_Dimensions_for_Sales_Mis.php",
      data:{"action_type":"Get_Crop_Details","product_division":product_division},
       async:false,
      success: function(result){
        var data=JSON.parse(result);
        option=data.crop_details;
      }
    });

      return option;

}

function Get_Season_Code_Details(user_input)
{
  var region_id=user_input.region_id;
  var material_id=user_input.material_id;
  var option="<option value=''> Select Season </option>";
   $.ajax 
      ({
      type: "POST",
      url: "Common_Ajax.php",
      data:{"Action":"Get_Season_Code_Details","region_id":region_id,"material_id":material_id},
       async:false,
      success: function(result){
        var data=JSON.parse(result);
        option=data.data;
      }
    });

      return option;

}

$(document).on("change",".material_id",function(){
  var material_id=$(this).val();
  var region_id=$(".region_id").val();
  var user_input={};
user_input.material_id=material_id;
user_input.region_id=region_id;
var result=Get_Season_Code_Details(user_input);
$(this).closest('tr').find(".season").html(result);

});



function Get_CNF_Plant_Details(Product_Division,Region_Code)
{
  $.ajax ({
      type: "POST",
      url: "Common_Ajax.php",
      data:{"Action":"Get_STO_Plant_Details","product_division":Product_Division,"Region_Code":Region_Code,"Type":'CNF'},
       async:false,
      success: function(data){
         var result=JSON.parse(data);
         $(".Receiving_plant_id").html(result.data);
        }
      });
}

function Get_Direct_Plant_Details(Product_Division,Region_Code)
{
  $.ajax ({
      type: "POST",
      url: "Common_Ajax.php",
      data:{"Action":"Get_STO_Plant_Details","product_division":Product_Division,"Region_Code":Region_Code,"Type":'Direct'},
       async:false,
      success: function(data){
         var result=JSON.parse(data);
         $(".plant_id").html(result.data);
        }
      });
}


  function get_plant_details(product_division,zone_id,region_id,plant_id,status){
var output="";
     $.ajax 
      ({
      type: "POST",
      url: "Common_Ajax.php",
      data:{"Action":"Get_STO_Plant_Details","product_division":product_division,"zone_id":zone_id,"region_id":region_id,"plant_id":plant_id},
       async:false,
      success: function(data){
         result=JSON.parse(data);
         output=result.data;
        
        }
      });
      if(status == 0){
        $('.plant_id').html(output);
        $('.plant_name').html(output);
        }else{
      return output;
        }
  }

   function get_customer_details(product_division,zone_code,region_code,territory_code){
    if(product_division !='' && product_division !='0' && zone_code !='' && zone_code !='0'&& region_code !='' && region_code !='0' && territory_code !='' && territory_code !='0'){
     $.ajax 
      ({
      type: "POST",
      url: "Common_Ajax.php",
      data:{"Action":"Get_Customer_Details","product_division":product_division,"zone_code":zone_code,"region_code":region_code,"territory_code":territory_code},
       async:false,
      success: function(data){
        
        var result=JSON.parse(data);
        $('.customer_id').html(result.data);

        }
      });
    }
  }




  function role_based_filter(product_division,action_type,zone_code,region_code,territory_code,crop_code,variety_code)
  {
      $.ajax 
      ({
      type: "POST",
      url: "Role_Based_Location_Dimensions_for_Sales_Mis.php",
      data:{"product_division":product_division,"action_type":action_type,"zone_code":zone_code,"region_code":region_code,"territory_code":territory_code,"crop_code":crop_code,"variety_code":variety_code},
       async:false,
      success: function(data){
        var zone_dets="0";
        var region_dets="0";
        var territory_dets="0";
        var crop_dets="0";
        var variety_dets="0";
        var result=JSON.parse(data);
        if(action_type == "Get_Zone_Details"){
          zone_dets="1";
          region_dets="1";
          territory_dets="1"; 
          crop_dets="1"; 
          quotation_dets="1"; 
          saleorder_dets="1"; 
        }else if(action_type == "Get_Region_Details"){
          zone_dets="0";
          region_dets="1";
          territory_dets="1";
        }else if(action_type == "Get_Territory_Details"){
          zone_dets="0";
          region_dets="0";
          territory_dets="1";
        }else if(action_type == "Get_Crop_Details"){
          crop_dets="1";
        }
        
        
        if(crop_dets == "1"){
          $(".crop_id").html(result.crop_details);
        }

         if(quotation_dets == "1"){
          $(".quot_type").html(result.quotation_details);
        }

        if(saleorder_dets == "1"){
          $(".sale_type").html(result.saleorder_details);
        }
         if(zone_dets == "1"){
          $(".zone_id").html(result.zone_details);
        }

        if(region_dets == "1"){
          $(".region_id").html(result.region_details);
        }
        if(territory_dets == "1"){
          $(".territory_id").html(result.territory_details);
        }

        }
      });    
  }



function check_duplication_row(){
    var duplicate_count=0;
    var error_count=0;
    $(".material_id").each(function(){
      duplicate_count=0;
      var material_id=$(this).val();
      $(".material_id").each(function(){
        
        var Current_material_id=$(this).val(); 
        if(material_id==Current_material_id){
          duplicate_count++;
        }
      });

      if(duplicate_count>1){
        error_count++;
        $(this).closest("tr").addClass('duplicate_row');
      }else{
        $(this).closest("tr").removeClass('duplicate_row');
      }

    });
if(error_count >0){
  //alert("Some Values Are Duplicate.Please Check It.");
$(".duplicate_error").html("Some Material Are Duplicate.Please Check It.");
}else{
  $(".duplicate_error").html("");
}
return error_count;
}



  $(document).on("change",".product_division",function(){
    var zone_id=0;
    var region_id=0;
    var territory_id=0;
    var crop_code=0;
    var variety_code=0;
    var product_division = $(".product_division").val();
    role_based_filter(product_division,"Get_Zone_Details",zone_id,region_id,territory_id,crop_code,variety_code);

  });
  $(document).on("change",".zone_id",function(){
    var zone_id=$(this).val();
    var region_id=0;
    var territory_id=0;
    var crop_code=0;
    var variety_code=0;
    var product_division = $(".product_division").val();
    role_based_filter(product_division,"Get_Region_Details",zone_id,region_id,territory_id,crop_code,variety_code);
  });


  $(document).on("change",".region_id",function(){
    var zone_id=$(".zone_id").val();
    var region_id=$(this).val();
    var territory_id=0;
    var crop_code=0;
    var variety_code=0;
    var product_division = $(".product_division").val();
    role_based_filter(product_division,"Get_Territory_Details",zone_id,region_id,territory_id,crop_code,variety_code);
  });
   $(document).on("change",".product_division,.crop_id,.zone_id",function(){
    var product_division = $(".product_division").val();
   var zone_id = $(".zone_id").val();
     var crop_code = $(".crop_id").val();
     
   
    // get_material_details(product_division,crop_code,zone_id,0);
   
  });

   


   function Get_Sales_Plan_qty(product_division,zone_id,region_id,material_id,season)
   {
    var qty=0;
    $.ajax 
      ({
      type: "POST",
      url: "Common_Ajax.php",
      data:{"Action":"Get_Sales_Plan_Details","material_id":material_id,"zone_id":zone_id,"region_id":region_id,"Product_Division":product_division,"season":season},
       async:false,
      success: function(result){
        var data=JSON.parse(result);
        qty=data.qty;
      }
    });

      return qty;
   }

   
   function Get_Indent_Qty(product_division,zone_id,region_id,material_id,crop_id,plant_id,season)
   {
    //alert("in");
    var qty=0;
    $.ajax 
      ({
      type: "POST",
      url: "Common_Ajax.php",
      data:{"Action":"Get_Indent_Qty","material_id":material_id,"zone_id":zone_id,"region_id":region_id,"Product_Division":product_division,"PlantId":plant_id,"CropId":crop_id,"CropId":crop_id,"season":season},
       async:false,
      success: function(result){
        var data=JSON.parse(result);
        qty=data.qty;
      }
    });

      return qty;
   }


   $(document).on("change",".material_id",function(){
    $(this).closest("tr").find(".QtyInBag").removeAttr("readonly");
    $(this).closest("tr").find(".QtyInBag").removeClass("textbox-grey");
    var material_id=$(this).val();
    var product_division=$(".product_division").val();
    var zone_id=$(".zone_id").val();
    var region_id=$(".region_id").val();
    var CropId=$(".crop_id").val();
    var plant_id=$(".plant_id").val();
    var season=$(".season").val();
    var qty=Get_Sales_Plan_qty(product_division,zone_id,region_id,material_id,season);
    console.log(qty);
    $(this).closest('tr').find(".Plan_qty").val(qty);

    var Indent_Qty=Get_Indent_Qty(product_division,zone_id,region_id,material_id,CropId,plant_id,season);
    $(this).closest('tr').find(".Indent_Qty").val(Indent_Qty);

    
    var curren_tr=$(this).closest("tr");
     $.ajax 
      ({
      type: "POST",
      url: "Common_Ajax.php",
      data:{"Action":"Get_Product_Based_Qty_Details","material_id":material_id,"zone_id":zone_id,"region_id":region_id,"CropId":CropId,"product_division":product_division},
       async:false,
      success: function(result){
        //QtyInPkt,QtyInKg
        var data=JSON.parse(result);
        curren_tr.find(".Product_QtyInPkt").val(data.QtyInPkt);
        curren_tr.find(".Product_QtyInKg").val(data.QtyInKg);
      }
    });
   });
/*
   $(document).ready(function() {
  $('#MaterialCode').change(function() {
    if( $(this).val() == 0) {
          $('#QtyInPkt').prop( "disabled", true );
    } else {       
      $('#QtyInPkt').prop( "disabled", false );
    }
  });
 
});
 */



 
function Get_Crop_Details_sub(product_division,crop_id)
{

  var product_division=product_division;
  var crop_code=crop_id;
  
  var option="<option value=''> Select Crop </option>";
   $.ajax 
      ({
      type: "POST",
      url: "Role_Based_Location_Dimensions_for_Sales_Mis.php",
      data:{"action_type":"Get_Crop_Details_sub","product_division":product_division,"crop_code":crop_code},
       async:false,
      success: function(result){
        var data=JSON.parse(result);
        option=data.crop_details;
      }
    });

      return option;

}


 


  function add_row(){
    var supply_type=$(".supply_type").val();
      var product_division=$(".product_division").val();
      var crop_code=$(".crop_id").val();
      var zone_id=$(".zone_id").val();
      var crop_details = Get_Crop_Details(product_division);


       $(".crop_id").attr("style", "pointer-events: none;");

     var crop_details = Get_Crop_Details(product_division);
     var crop_details_sub = Get_Crop_Details_sub(product_division,crop_code);


   // var plant_details=get_plant_details(supply_type,1);
    var material_details=get_material_details(product_division,crop_code,zone_id,1);

      var markup = "<tr><td class='srn_no'></td><td><div><select class='js-example-basic-singles col-xs-12 crop_id required_for_valid' error-msg='Crop Field is Required' name='CropId' style='width:100%;'>"+ crop_details_sub+"</select><small class='error_msg text-danger mt-1 lh-base'></small></div></td><td><div> <select class='js-example-basic-singles col-xs-12 material_id required_for_valid'  error-msg='Material Field is Required' name='MaterialCode[]' >"+ material_details+"</select><input type='hidden'  class='form-control right Product_QtyInPkt' name='MaterialQtyInPkt[]' value='0' readonly ><input type='hidden'  class='form-control right Product_QtyInKg' name='MaterialQtyInKg[]' value='0' readonly ><small class='error_msg text-danger mt-1 lh-base'></small></div></td> <td><div><select class='js-example-basic-singles season required_for_valid' error-msg='Season Field is Required' name='season[]' ><option value='' selected>Select Season </option></select></div></td><td><input type='text' class='form-control Plan_qty textbox-grey' name='PlanQty[]' readonly></td> <td><input type='text' class='form-control ' name='' value='0' readonly></td><td><input type='text' class='form-control Indent_Qty textbox-grey' name='Indent_Qty[]' readonly></td><td><div>  <input type='hidden'  class='form-control right only_numbers max_charater location' error-msg='Pkts Field is Required textbox-grey' value='SE01' name='StorageLocation[]' readonly ><input type='text' class='form-control right max_charater required_for_valid only_numbers QtyInBag required_for_valid textbox-grey' error-msg='Quantity_Bag Field is Required ' name='QtyInBag[]' readonly ><small class='error_msg text-danger mt-1 lh-base'></small></div></td> <td><div><input type='text' class='form-control right max_charater required_for_valid only_numbers QtyInPkt textbox-grey' error-msg='Pkts Field is Required' name='QtyInPkt[]' readonly></div></td> <td><div><input type='text' class='form-control right max_charater  only_numbers QtyInKg textbox-grey' name='QtyInKg[]' readonly ></div></td><td><div class='d-flex mt-2'><button type='button' onclick ='add_row()' class='btn btn-sm btn-success'><i class='fas fa-plus-circle'></i></button> <button class='delete btn btn-sm btn-danger ms-1' onclick ='delete_user($(this))'><i class='fas fa-trash'></i></button></div><input type='hidden' class='form-control Limit_Exceed' name='Limit_Exceed[]' value='0'></tr>";
      $("table tbody").append(markup);
       var result="";
      s_no();
    
      $('.js-example-basic-singles').select2();
    }

     function delete_user(row)
  {
    row.closest('tr').remove();
    s_no();
  }


  function remove_option(){
      var optionValues = [];
      $('.cls_adv_to option').each(function(){
        if($.inArray(this.value, optionValues) >-1){
          $(this).remove();
        }else{
          optionValues.push(this.value);
        }
    });
  }

  function s_no(){
    var sno = 1;
    $(".srn_no").each(function(key,index){
       $(this).html((sno));   
    sno++;
    });
  }
  
  $(document).on("change keyup",".required_for_valid" ,function(){
	  var current_val=$(this).val();
	  var error_msg=$(this).attr("error-msg");
	  if(current_val == ''){
		  $(this).closest("div").find(".error_msg").html(error_msg);
	  }else{
		  $(this).closest("div").find(".error_msg").html("");
	  }
  })
  
  function validation(){
	  var error_count=0;
	  $(".required_for_valid").each(function(){
		  var current_val=$(this).val();
	  var error_msg=$(this).attr("error-msg");
	  if(current_val == ''){
		  error_count++;
		  $(this).closest("div").find(".error_msg").html(error_msg);
	  }else{
		  $(this).closest("div").find(".error_msg").html("");
	  }
	  });
	  return error_count;
  }
  
  $(document).on("submit",".Sales_Indent_Submit",function(){
     var duplication_count= check_duplication_row();
	  var error_count=validation();
    var Limit_Exceeds_Validation_count=Limit_Exceeds_Validation();
	  if(error_count == 0 && duplication_count==0){
		  return true;
	  }else{
		  return false;
	  }
  })

  $(document).on("keyup", ".QtyInBag", function() {
            var bag = parseInt($(this).val());
            var Plan_qty=$(this).closest('tr').find(".Plan_qty").val();
            Plan_qty=parseFloat(Plan_qty);
            var pkts=$(this).closest("tr").find(".Product_QtyInPkt").val();
            console.log("pkts ==" + pkts);
            console.log("bag ==" + bag);
            var kg=$(this).closest("tr").find(".Product_QtyInKg").val();
            var QtyInPkt = bag * pkts;



           
            if(isNaN(QtyInPkt))
            {
              QtyInPkt=0;
            }
 var QtyInKg = QtyInPkt * kg;
 
            if(QtyInPkt<=Plan_qty){
            $(this).closest("tr").find(".QtyInPkt").val(QtyInPkt);
            $(this).closest("tr").find(".QtyInKg").val(QtyInKg);
            }else{
            $(this).val("");
            $(this).closest("tr").find(".QtyInPkt").val("");
            $(this).closest("tr").find(".QtyInKg").val("");
            }
        });