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
<title>Invoice</title>
<style>
body{
	font-size: 120%;	
}
table {
	margin: 0;
	padding: 0;
	width: 800px;
	border:1px solid #ccc;
	border-collapse: collapse;
	}
table th,
table td {
	padding: 10px 20px;
	text-align: left;
	border: 1px solid #ccc;
	
	}
table th {
	border-width: 2px;
	}
table td {
	color: #666;
	}
</style>

</head>

<body>

<table id="invoice_table" border="0" cellspacing="0" cellpadding="5" width="100%">
  <tr style="padding: 10px;">
    <td colspan="8" style="padding: 10px;"><h2>Invoice</h2></td>
    <td colspan="2">Invoice #:<?php echo ($invoice==null?"":$invoice->getProperty("id"));?></td>
  </tr>
  <tr>
    <td colspan="8"><?php echo ($invoice==null?"":$invoice->getProperty("contractor_name"));?></td>
    <td colspan="2">Period</td>
   
  </tr>
  <tr>
    <td colspan="8">Address: <?php echo ($invoice==null?"":$invoice->getProperty("contractor_remit_full_addr"));?></td>
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
    <td colspan="2">Date:<?php echo ($invoice==null?"":$invoice->getProperty("date"));?></td>
  </tr>
  <tr>
    <td colspan="8">Address: <?php echo ($invoice==null?"":$invoice->getProperty("customer_addr"));?></td>
    <td colspan="2">Order #:<?php echo ($invoice==null?"":$invoice->getProperty("order_id"));?></td>
  </tr>
  <tr>
    <td colspan="8">City: <?php echo ($invoice==null?"":$invoice->getProperty("customer_city"));?>, State: <?php echo ($invoice==null?"":$invoice->getProperty("customer_state"));?>, Zip: <?php echo ($invoice==null?"":$invoice->getProperty("customer_zip"));?></td>
    <td colspan="2">Rep:<?php echo ($invoice==null?"":$invoice->getProperty("rep"));?></td>
  </tr>
  <tr>
    <td colspan="8">Phone: <?php echo ($invoice==null?"":$invoice->getProperty("customer_phone"));?></td>
    <td colspan="2">FOB: <?php echo ($invoice==null?"":$invoice->getProperty("FOB"));?></td>
  </tr>
  <tr id="task_header">
    <td>Task #</td>
    <td colspan="7">Name of MIL</td>
   <td>Cost</td>
    <td>Total</td>
 
</tr>
<?php 
$total = 0;
if($invoice!=null){
$tasks = $invoice->getProperty("tasks");
$i = 0;
foreach($tasks as $key => $item){?>
 <tr style="<?php echo ($i%2==0?"background-color:#ddd":"")?>;">
    <td><?php echo $item["task_id"]?></td>
    <td colspan="7"><?php echo $invoice->getProperty("mil_name");?></td>
    <td><?php echo $item["cost"]?></td>
    <td><?php echo $item["sub_total"]?></td>
  </tr>
 <?php 
 $i++; 
 $total +=$item["sub_total"]; 
 } }?>
 	<tr>
    	<td colspan="8">&nbsp;</td>
   
    	<td style="background-color:#ddd;"> Total</td>
    	<td style="background-color:#ddd;" id="total"><?php echo $total;?></td>
    
	</tr>
 	<tr>
    	<td colspan="10"><h4><strong>Comment Box: </strong></h4>
     		<?php echo ($invoice==null?"":$invoice->getProperty("Comment"));?>
     	</td>
  	</tr>

  	<tr>
    	<td colspan="10"><h4><strong>Payment Details</strong></h4></td>
  	</tr>
  	<tr>
    	<td colspan="10">
     		<!--
    		-->
    		<?php 
    		 if( $invoice!=null&&$invoice->getProperty("payment")=="1"){?>
    		 <input type="radio" name="payment" value="Cash"/>
    		<?php 
    		}else{?>
     		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     		<?php
     		}?>
    		Cash
    	</td>
  	</tr>
  <tr>
    <td colspan="10">
      <?php 
       if( $invoice!=null&&$invoice->getProperty("payment")=="2"){?>
     <input type="radio" name="payment" value="Check"/>
    <?php 
     }else{?>
     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     <?php
     }?>Check</td>
  </tr>
  <tr>
    <td colspan="10">
    <?php
      if( $invoice!=null&&$invoice->getProperty("payment")=="3"){?>
     <input type="radio" name="payment" value="Credit"/>
    <?php 
     }else{?>
     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     <?php
     }?>Credit</td>
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
    <td colspan="10"><strong>Contractors FEID #: </strong><?php echo ($invoice==null?"":$invoice->getProperty("FEID"));?></td>
  </tr>

 
</table>


</body>
</html>