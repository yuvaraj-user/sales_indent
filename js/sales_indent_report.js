$(document).on("submit",".adv_submit",function(){
	var user_input={};
  user_input.product_division=$(".product_division").val();
  user_input.crop_id=$(".crop_id").val();
  user_input.quot_type=$(".quot_type").val();
  user_input.sale_type=$(".sale_type").val();
  user_input.zone_id=$(".zone_id").val();
  user_input.region_id=$(".region_id").val();
  user_input.teritory_id=$(".territory_id").val();
  user_input.customer_id=$(".customer_id").val();
  user_input.supply_type=$(".supply_type").val();
  user_input.Status=$(".Status").val();

  Server_Side_Datatable("yes",user_input);
  return false;
})

$(document).ready(function(){
 $('.js-example-basic-single').select2(); 
 var From_Date = '';
 var  to_Date = '';
 var Division = '';
 var region_code = '';
 var teritory_code = '';
 var productdivision ='';
 var status = $('#filter_status').val();
 var user_input={};
 user_input.product_division=$(".product_division").val();
 user_input.crop_id=$(".crop_id").val();
 user_input.quot_type=$(".quot_type").val();
 user_input.sale_type=$(".sale_type").val();
 user_input.zone_id=$(".zone_id").val();
 user_input.region_id=$(".region_id").val();
 user_input.teritory_id=$(".territory_id").val();
 user_input.customer_id=$(".customer_id").val();
 user_input.supply_type=$(".supply_type").val();
 user_input.Status=$(".Status").val();

 Server_Side_Datatable("no",user_input);
});



$(document).ready(function(){
 $('.js-example-basic-single').select2();
 var product_division = $(".product_division").val();
 var supply_type = $(".supply_type").val();  

 role_based_filter(product_division,"Get_Zone_Details",0,0,0,0,0);

 get_plant_details(supply_type,0);
 var zone_id = $(".zone_id").val();
 var region_id = $(".region_id").val();
 var territory_id = $(".territory_id").val();
 var crop_code = $(".crop_id").val();
 get_customer_details(product_division,zone_id,region_id,territory_id);
 get_material_details(product_division,crop_code,zone_id,0);
});
$(document).on("change",".supply_type",function(){
 get_plant_details($(this).val(),0);

});

$(document).on("change",".supply_type",function(){

 get_plant_details($(this).val(),0);

});

$(document).on("change",".zone_id,.region_id,.territory_id,.product_division",function(){
  var product_division = $(".product_division").val();
  var zone_id = $(".zone_id").val();
  var region_id = $(".region_id").val();
  var territory_id = $(".territory_id").val();
  get_customer_details(product_division,zone_id,region_id,territory_id);

});

$(document).ready(function(){
  $('.plant_name').select2();
  $('.plant_id').change(function(){
    var value = $(this).val();
    $('.plant_name').val(value);
    $('.plant_name').select2().trigger('change');
  });
});

function get_material_details(product_division,crop_code,zone_id,status){
  var output="";
  $.ajax 
  ({
    type: "POST",
    url: "Common_Ajax.php",
    data:{"Action":"Get_Material_Details","product_division":product_division,"crop_code":crop_code},
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

function get_plant_details(supply_type,status){
  var output="";
  $.ajax 
  ({
    type: "POST",
    url: "Common_Ajax.php",
    data:{"Action":"Get_Plant_Details","supply_type":supply_type},
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
        variety_dets="1"; 
      }else if(action_type == "Get_Region_Details"){
        zone_dets="0";
        region_dets="1";
        territory_dets="1";
      }else if(action_type == "Get_Territory_Details"){
        zone_dets="0";
        region_dets="0";
        territory_dets="1";
      }else if(action_type == "Get_varirty_Details"){
       variety_dets="1"; 
     }


     if(crop_dets == "1"){
      $(".crop_id").html(result.crop_details);
    }

    if(variety_dets == "1"){
      $(".hybrid_id").html(result.variety_details);
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

$(document).on("change",".crop_id",function(){
  var zone_id=0;
  var region_id=0;
  var territory_id=0;
  var crop_code=$(this).val();
  var variety_code=0;
  var product_division = $(".product_division").val();
  role_based_filter(product_division,"Get_varirty_Details",zone_id,region_id,territory_id,crop_code,variety_code);
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


  get_material_details(product_division,crop_code,zone_id,0);

});



function Server_Side_Datatable(destroy_status,user_input)
{

 jQuery.fn.DataTable.Api.register( 'buttons.exportData()', function ( options ) {
   if ( this.context.length ) {
     var jsonResult = $.ajax({

       url: 'Ajax.php',
       type:'POST',
       dataType:'json',
       data: { 
        Action:'View_Sales_Indent_Details',ProductDivision:user_input.product_division,QuotationType:user_input.quot_type,SaleOrderType:user_input.sale_type,Zone_id:user_input.zone_id,Region_Id:user_input.region_id,Terrirory_Id:user_input.teritory_id,Supply_Type:user_input.supply_type,Customer:user_input.customer_id,Status:user_input.Status,length:'All'
      },
      async: false
    });

     let headers=['S.No','ReqId','Req Date','RequestBy','Emp Name','Product Division','Crop','Zone','Region','Territory','Customer Code','Customer Name','Quotation Type','SaleOrder Type','Material Code','Quantity InBag','Quantity InPKt','Quantity InKg','Status']


     return {
       body: jsonResult.responseJSON.data, 
       header: headers};
     }
   } );


 var data_table='Sales_Indent'
 if(destroy_status == "yes")
 {
  $('#'+data_table).DataTable().destroy();
}
$('#' + data_table).DataTable({

  "dom": 'Brtip',
  
  //"columnDefs": [{ "className":"y desine", "targets": [1,2,3,4,5] }],
  "scrollX": true,
    //"scrollY": true,
  "buttons": ['copy', 'csv', 'excel', 'pdf', 'print'],
  "bprocessing": true,
  "serverSide": true,
  "pageLength": 5,
   /* "fixedColumns":   {
            "leftColumns": 4
            
        },

"bScrollCollapse": true,
   
  "StripeClasses":[],*/
  "ajax": 
  {
    "url": "Ajax.php", 
    "type": "POST",
    "data": {Action:'View_Sales_Indent_Details',ProductDivision:user_input.product_division,QuotationType:user_input.quot_type,SaleOrderType:user_input.sale_type,Zone_id:user_input.zone_id,Region_Id:user_input.region_id,Terrirory_Id:user_input.teritory_id,Supply_Type:user_input.supply_type,Customer:user_input.customer_id,Status:user_input.Status}
  },
    fnDrawCallback: function (settings, json) {
        $('.ellipsis').addClass('paginate_button');
        if($('.paginate_button').hasClass('ellipsis')) {
          $('.paginate_button').removeClass('ellipsis');
        }
    }
});
}

$(".filterData").click(function(){

  var region_code = $('.js-example-basic-single.region_id option:selected').val();
  var teritory_code = $('.js-example-basic-single.teritory_id option:selected').val();
  var Zone = $('.div_select.zone_id option:selected').val();
  var status = $('#status option:selected').val();
  //alert(status);
  var From_Date = $('#fromdate').val();
  var  to_Date =$('#todate').val();
  var productdivision=$('.product_division option:selected').val();

  if(region_code==''){
    region_code='';
  }
  
  if(teritory_code==''){
    teritory_code='';
  }
  if(Zone==''){
    Zone='';
  }
  
  if(From_Date=='')
  {
    From_Date='';
  }
  if(to_Date=='')
  {
    to_Date='';
  }
  getData('yes',From_Date,to_Date,Zone,region_code,teritory_code,status,productdivision);

});	

function getData(destroy_status,From_Date,to_Date,Division,region_code,teritory_code,status,productdivision){
	
  if(destroy_status == "yes"){
   $('#Sales_Indent').DataTable().destroy();
		   //alert('test');
 }  

}