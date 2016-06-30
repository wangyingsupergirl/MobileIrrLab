<?php
require_once dirname(__FILE__) . '/../../../includes/input/Invoice.php';
session_start();
$invoice = $_SESSION['invoice'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.js"></script>
<script src="http://jzaefferer.github.com/jquery-validation/jquery.validate.js" type="text/javascript"></script>

<title>Invoice</title>
<style>

body{
	font-size: 120%;	
}
#invoice_table {
	margin: 0;
	padding: 0;
	width: 800px;
	border:1px solid #ccc;
	border-collapse: collapse;
}
#invoice_table th,
#invoice_table td {
	padding: 10px 20px;
	text-align: left;
	border: 1px solid #ccc;
}
#invoice_table th {
	border-width: 2px;
}
#invoice_table td {
	color: #666;
}
#invoice_table td .error {
    color: red;
}
#invoice_table tr:last-child th,
#invoice_table tr:last-child td {
	border-bottom: none;
}
#invoice_table tr:nth-child(even) {
	background: #eee;
}	
</style> </head>

<body>
 <form id="invoiceForm" action="../control_evaluation.php" method="post">
<table id="invoice_table" border="0" cellspacing="0" cellpadding="5">
  <tr style="padding: 10px;">
    <td colspan="8" style="padding: 10px;"><h2>Invoice</h2></td>
    <td colspan="2">Invoice #: <input type="text" name="id"/></td>
  </tr>
  <tr>
    <td colspan="8"><?php echo ($invoice==null?"":$invoice->getProperty("contractor_name"));?></td>
    <td colspan="2">Period</td>
   
  </tr>
  <tr>
    <td colspan="8">Address:
    <?php echo ($invoice==null?"":$invoice->getProperty("contractor_remit_full_addr"));?>
    </td>
    <td colspan="2"><?php echo ($invoice==null?"":$invoice->getProperty("period"));?></td>
  </tr>
  <tr>
    <td colspan="8">Phone: <?php echo ($invoice==null?"":$invoice->getProperty("contractor_phone"));?></td>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="10"><h3>Customer</h3></td>
  </tr>
  <tr>
    <td colspan="8">Name: <?php echo ($invoice==null?"":$invoice->getProperty("customer_name"));?></td>
    <td colspan="2">Date:<input type="text" name="date"/></td>
  </tr>
  <tr>
    <td colspan="8">Address: <?php echo ($invoice==null?"":$invoice->getProperty("customer_addr"));?></td>
    <td colspan="2">Order #:<input type="text" name="order_id"/></td>
  </tr>
  <tr>
    <td colspan="8">
        City: <?php echo ($invoice==null?"":$invoice->getProperty("customer_city"));?>, 
        State: <?php echo ($invoice==null?"":$invoice->getProperty("customer_state"));?>, 
        Zip: <?php echo ($invoice==null?"":$invoice->getProperty("customer_zip"));?></td>
    <td colspan="2">Rep: <input type="text" name="rep"/></td>
  </tr>
  <tr>
    <td colspan="8">Phone: <?php echo ($invoice==null?"":$invoice->getProperty("customer_phone"));?></td>
    <td colspan="2">FOB: <input type="text" name="FOB"/></td>
  </tr>

  <tr id="task_header">
    <td>Task #</td>
    <td colspan="7">Name of MIL</td>
   <td>Cost</td>
    <td>Total</td>
 
</tr>
<!--
 <tr style="background-color:#ddd;">
    <td><input type="text" name="task_id"/></td>
    <td colspan="6"><?php echo ($invoice==null?"":$invoice->getProperty("mil_name"));?></td>
    <td><input type="text" name="cost"/></td>
    <td><input type="text" name="sub_total"/></td>
    <td><input type="submit" value="Edit"></td>
  </tr>-->
 
 <tr>
    <td colspan="7"></td>
    <td><input type="button" value="Add Task" id="add_task"/></td>
    <td style="background-color:#ddd;"> Total</td>
    <td style="background-color:#ddd;" id="total"></td>
    
  </tr>
   <tr>
    <td colspan="10"  style="transform: translateY(-16px);height:100px;"><strong>Comment Box: </strong>
      <textarea type="text" name="Comment" maxlength = "2000" style="width: 700px; height:55px; transform: translateY(15px); resize: none;" > 
      </textarea>
    </td>
    
  </tr>
  <tr>
    <td colspan="10"><h4><strong>Payment Details</strong></h4><div id="payment_err"><!--Error display here--></div></td>
  </tr>
  <tr>
    <td colspan="10"><input type="radio" class="radioBtn" name="payment" value="1" />
      <label for="radio"></label>
    Cash</td>
  </tr>
  <tr>
    <td colspan="10"><input type="radio" class="radioBtn" name="payment"  value="2"/>
    <label for="radio2"></label>Check</td>
  </tr>
  <tr>
    <td colspan="10"><input type="radio" class="radioBtn" name="payment"  value="3"/>
    <label for="radio3"></label>Credit</td>
  </tr>
  <tr>
    <td colspan="10">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="10">Office Use Only: </td>
  </tr>
  <tr>
    <td colspan="10">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="10"><strong>FDACS Contract #</strong><?php echo ($invoice==null?"":$invoice->getProperty("contract_id"));?></td>
  </tr>
  <tr>
    <td colspan="10"><strong>Contractors FEID #: </strong><input type="text" name="FEID"/></td>
  </tr>
  <tr>
    <td colspan="10"><input class="button" type="submit" name="request_invoice_pdf" onclick="validateForm()" value="Submit" /></td>
  </tr>
</table>
  </form>
 <script type="text/javascript">
var task_count = 0;
 $("#add_task").click(
     function(){
        $("#task_header").after(function(){
            var tr = '<tr class="task_item">'+
            '<td><input type="text" name="task_id:'+ task_count +'"/></td>'+
            '<td colspan="7"><?php echo ($invoice==null?"":$invoice->getProperty("mil_name"));?></td>'+
            '<td><input type="text" name="cost:'+task_count+'"/></td>'+
            '<td><input type="text" class="sub_total" name="sub_total:'+task_count+'"/></td>'+
            '</tr>';
           task_count++;
           return tr;
       });
    }
)
$("#invoice_table").on("keyup",".task_item", function(event){
      var target = $(event.target);
      var btnType = target.attr("class");
      if(btnType=="sub_total"){
           var total = 0;
           $(".sub_total").each(function(){
                var item =$(this).attr("value");
                total += parseFloat(item);
           })
           $("#total").html(total);
      }
})

var validateForm = function(){
	$("#invoiceForm").validate({
               rules: {
                    id:{
                      required: true,
                      maxlength: 12
                    },
                    date:"required", 
                    rep:{
                      required: true,
                      maxlength: 12
                    },
                   FOB:{
                      required: true,
                      maxlength: 12
                    },
                    order_id:{
                      required: true,
                      maxlength: 12
                    },
                    payment:"required",
                    FEID:{
                      required: true,
                      maxlength: 12
                    }
                },
                 errorPlacement: function(error, element) {
                    if ($(element).hasClass("radioBtn")) {
                       $("#payment_err").html(error);
                    } else {
                        error.insertAfter(element);
                    }
                } 
        });
}
 </script>
</body>
</html>
