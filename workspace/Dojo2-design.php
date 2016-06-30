<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>State of Florida Mobile Irrigation Lab (MIL) Program - Administrator Workspace</title>
	
	<link rel="stylesheet" type="text/css" href="../styles/milStylesheet.css" />

</head>		
        <style type="text/css">
            @import "../dojodev/dijit/themes/soria/soria.css";
        </style>
		
      
        <style type="text/css">
            html, body, #main {
                /* make the body expand to fill the visible window */
                width: 100%; 
                height: 100%;
            }
            
            #main{
            	width: 960px;
            	margin: 0 auto;
            }

            h1, h2, h3 { margin: 0.5em 0 1em 0}
            p   { margin: 0 0 1em 0}

            .box     { border: 1px #bbb solid;}
            .content { padding: 0.5em; overflow: auto}

            #header   { width: 960px; margin: 0 auto 15px auto; }
            #sidebar  { width: 150px;}
            #content  { padding: 1em;}
            #footer   { width: 960px;height: 50px; margin: 10px auto;}

            #sidebar ul { margin-left: -1em;}

            #mainstack { width: 75%; height: 75%; border: 1px #888 solid}

			.dijitTabContent{font-size: .75em;}
        </style>
<script type="text/javascript">
            djConfig = {
                isDebug:      true,
                parseOnLoad:   true
            };
        </script>

        <script type="text/javascript"
            src="../dojodev/dojo/dojo.js"></script>

        <script type="text/javascript">
            dojo.require("dojo.parser");

            dojo.require("dijit.layout.ContentPane");
            dojo.require("dijit.layout.BorderContainer");
            dojo.require("dijit.layout.StackContainer");
            dojo.require("dijit.layout.AccordionContainer");
            dojo.require("dijit.layout.SplitContainer");
            dojo.require("dijit.layout.TabContainer");
            dojo.require("dijit.layout.LinkPane");
        </script>

		<meta name="robots" content="nofollow" />

</head>
<body class="soria">

<div id="header">
   	<h1>Camilo's Workspace</h1>
</div>

<div id="main" dojotype="dijit.layout.BorderContainer" 
    		design="headline"
    		persist="true"
    		livesplitters="false">
 
  
  <!--Main Content starts here-->
  <div id="maincontent"dojotype="dijit.layout.TabContainer" tabposition="top" region="center">
    
    <!--My Profile starts here-->
    <div class="content"  dojotype="dijit.layout.ContentPane" title="My Account" <?php if($pageid!=null&&$pageid==1){echo 'selected';}?>>
    


	
    <div id="sign-up">
		<fieldset>
   			<form action="./app_control.php" method="post" name="Login">
	    	<input type="hidden" name="role" value="0/1"/>
	    	<input type="hidden" name="user_request_type" value="1">
<table class="mainContactForm">
			<tr>
        		<td>*First Name:</td>
        		<td><input name="first" value="" type="text" size="32" maxlength="32"></td>
           	</tr>
		
            <tr>
       			<td>*Last Name:</td>
       		    <td><input name="" value="" type="text" size="32" maxlength="32"></td>
         	</tr>
        
         	<tr>
        		<td>*Title:</td>
        	    <td><input name="" value="" type="text" size="32" maxlength="32"></td>
         	</tr>
			
            <tr>
        	    <td> * Phone: </td>
        	    <td><input name="" type="text" value="" size="3" maxlength="3"> - <input name="" type="text" value="" size="10" maxlength="10">
                  extension<input name="" type="text" value="" size="6" maxlength="6"></td>
           	</tr>
           	
           	<tr>
 	           <td>Fax:</td>
 	           <td><input name="" type="text" value="" size="20" maxlength="20"></td>
          	</tr>
    		
    		<tr>
	  	        <td>*Address1:</td>
   		        <td><input name="" type="text" value="" size="32" maxlength="32" /></td>
          	</tr>
        
           	<tr>
        	    <td>Address2:</td>
        	    <td><input name="" type="text" value="" size="32" maxlength="32"></td>
           	</tr>
        
           	<tr>
        	    <td>*City:</td>
        	    <td><input name="" type="text" value="" size="32" maxlength="32"></td>
           	</tr>
        
           	<tr>
        	    <td>State:</td>
           		<td><input name="" type="text" value="" size="32" maxlength="32"></td>
           	</tr>
           	
            <tr>
            	<td>*Zip:</td>
            	<td><input name="" type="text" value="" size="5" maxlength="5"></td>
           </tr>
           
           <tr>
				<td>Fiscal Year Standard:</td>
				<td><input type="radio"> &nbsp;FDACS (ends in June of every year)<br /><input type="radio"> &nbsp;Federal (ends in September of every year)</td>
			</tr>
            
            <tr>
            	<td>*User Name:</td>
            	<td><input type="input" name="" value="" size="32"> </td>
          	</tr>
            
            <tr>
            	<td></td>
            	<td><div style="width:200px">e.g. myname@example.com. This email address will be used to sign-in to your account. </div></td>
          	</tr>
            
            <tr>
            	<td>*Password:</td>
            	<td><input type="input" name="" value="" size="32"></td>
          	</tr>
            
            <tr>
            	<td>*Re-enter Password:</td>
            	<td><input type="input" name="" value="" size="32"></td>
          	</tr>
            
            <tr>
            	<td></td>
            	<td><input type="submit" name="Submit" value="Submit"></td>
          	</tr>
		</table>
		</form>
	</fieldset>	
</div>
      
    </div>
    <!--Evaluations Start here-->
    <div class="content" dojotype="dijit.layout.ContentPane" title="MIL Evaluations" <?php if($pageid!=null&&($pageid==2||$pageid==21||$pageid==22)){echo 'selected';}?> >
      <div id="subcontent" dojotype="dijit.layout.TabContainer" tabposition="top" region="center">
        <div class="content"
                    dojotype="dijit.layout.ContentPane"
                    title="Pending">
                    <div id="eval_list">
            <table border="0" cellpadding=0 cellspacing=0 class="subForms">
              <thead>
                <th>&nbsp;</th>
                <th>MIL Name</th>
                <th>MIL ID</th>
                <th>FDACS (or Federal) Fiscal Yr</th>
                <th>FDACS (or Federal) Quater</th>
                <th>Package ID</th>
                <th>Current Num of Evals</th>
                <th>Date Created</th>
                <th>&nbsp;</th>
              </thead>
               <tr>
                <td>1</td>
                <td>Ag - Palm Beach Broward</td>
                <td>20</td>
                <td>2010</td>
                <td>2</td>
                <td>P10</td>
                <td>10</td>
                <td>2010/2/2</td>
                <td>View</td>
              </tr>
              <tr>
                <td>2</td>
                <td>Ag - Lower West Coast</td>
                <td>2</td>
                <td>2010</td>
                <td>2</td>
                <td>P11</td>
                <td>3</td>
                <td>2010/4/1</td>
                <td>View</td>
              </tr>
              <tr>
                <td>3</td>
                <td>Ag - Lake</td>
                <td>26</td>
                <td>2010</td>
                <td>2</td>
                <td>P12</td>
                <td>10</td>
                <td>2010/3/4</td>
                <td>View</td>
              </tr>
               <tr>
                <td>4</td>
                <td>Urban -    Floridan RC&amp;D</td>
                <td>35</td>
                <td>2010</td>
                <td>2</td>
                <td>P4</td>
                <td>4</td>
                <td>2010/5/1</td>
                <td><a href="./eval_view.php?id=p9">View</a></td>
              </tr>
            </table>
          </div>
       </div>


       <div class="content"
                    dojotype="dijit.layout.ContentPane"
                    title="Submitted" <?php if($pageid!=null&&$pageid==21){echo 'selected';}?>>


       <div id="eval_list">
           <table border="0" cellpadding="0" cellspacing="0" class="subForms">
              <thead>
                <th>&nbsp;</th>
                <th>Package ID</th>
                <th>MIL Name</th>
                <th>MIL ID</th>
                <th>FDACS Fiscal Year</th>
                <th>FDACS Quater</th>
                <th>Current Num of Evals</th>
                <th>Submitted Time</th>
                <th>Operations</th>
              </thead>
             
              <tr>
                <td>1</td>
                <td>P9</td>
                <td> Ag - NRCS Wauchula</td>
                <td>30</td>
                <td>2010</td>
                <td>2</td>
                <td>14</td>
                <td>2010/3/1</td>
                <td>Review</td>
              </tr>
              <tr>
                <td>2</td>
                <td>P5</td>
                <td>Ag - Floridan</td>
                <td>16</td>
                <td>2010</td>
                <td>2</td>
                <td>10</td>
                <td>2010/5/2</td>
                <td><a href="./eval_review.php?type=21">Review</a></td>
              </tr>
              <tr>
                <td>3</td>
                <td>P6</td>
                <td>Urban - Manatee County</td>
                <td>34</td>
                <td>2010</td>
                <td>2</td>
                <td>10</td>
                <td>2010/5/1</td>
                <td>Review</td>
              </tr>
              <tr>
                <td>4</td>
                <td>P7</td>
                <td>Ag - St. Lucie Area</td>
                <td>29</td>
                <td>2010</td>
                <td>2</td>
                <td>10</td>
                <td>2010/5/4</td>
                <td>Review</td>
              </tr>
            </table>
          </div>
          <p></p>
        
        </div>
        <div class="content"
                    dojotype="dijit.layout.ContentPane"
                    title="Approved"  <?php if($pageid!=null&&$pageid==22){echo 'selected';}?>>
          <div id="eval_list">
            <table border="0" cellpadding="0" cellspacing="0" class="subForms">
              <tr>
                <td>&nbsp;</td>
                <td>Package ID</td>
                <td>MIL Name</td>
                 <td>MIL ID </td>
                <td>FDACS Fiscal Year</td>
                <td>FDACS Quater</td>
                <td>Current Num of Evals</td>
                <td>Approved Time</td>
                <td>Operations</td>
              </tr>
              <tr>
                <td>1</td>
                <td><input type="image" value="P1" onClick=" document.getElementById('p1').style.display='block'"></td>
                <td>Ag - SWFWMD ProMIL</td>
                <td>31</td>
                <td>2010</td>
                <td>2</td>
                <td>4</td>
               <td>2010/5/1</td>
                 <td><a href="./eval_review.php?id=1&mil=31&p=9&type=22">View</a></td>
              </tr>
              <tr>
                <td>2</td>
                <td><input type="image" value="P2" onClick=" document.getElementById('p1').style.display='block'"></td>
                <td>Ag - Suwannee River</td>
                <td>10</td>
                <td>2010</td>
                <td>2</td>
                <td>32</td>
                <td>2010/5/2</td>
                 <td>View</td>
              </tr>
              <tr>
                <td>3</td>
                <td><input type="image" value="P3" onClick=" document.getElementById('p1').style.display='block'"></td>
                <td>Ag - Tampa Bay Estuary</td>
                <td>6</td>
                <td>2010</td>
                <td>2</td>
                <td>29</td>
                <td>2010/5/1</td>
                 <td>View</td>
              </tr>
              <tr>
                <td>4</td>
                <td><input type="image" value="P4" onClick=" document.getElementById('p1').style.display='block'"></td>
                <td>Urban - Broward</td>
                <td>17</td>
                <td>2010</td>
                <td>2</td>
                <td>33</td>
                <td>2010/5/4</td>
                <td>View</td>
              </tr>
            </table>
          </div>
        </div>
        <div class="content"
                    dojotype="dijit.layout.ContentPane"
                    title="All">
         <!-- <div id="eval_list">
            <table class="subForms">
              <tr>
                <td>&nbsp;</td>
                <td>Package ID</td>
                <td>MIL ID</td>
                <td>Year</td>
                <td>Quater</td>
                <td>Num of Evals</td>
                <td>Status</td>
              </tr>
              <tr>
                <td>1</td>
                <td><input type="image" value="P1" onClick=" document.getElementById('p1').style.display='block'"></td>
                <td>31</td>
                <td>2010</td>
                <td>2</td>
                <td>4</td>
                <td>Approved</td>
              </tr>
              <tr>
                <td>2</td>
                <td><input type="image" value="P2" onClick=" document.getElementById('p1').style.display='block'"></td>
                <td>10</td>
                <td>2010</td>
                <td>2</td>
                <td>32</td>
                <td>Approved</td>
              </tr>
              <tr>
                <td>3</td>
                <td><input type="image" value="P3" onClick=" document.getElementById('p1').style.display='block'"></td>
                <td>6</td>
                <td>2010</td>
                <td>2</td>
                <td>29</td>
                <td>Approved</td>
              </tr>
              <tr>
                <td>4</td>
                <td><input type="image" value="P4" onClick=" document.getElementById('p1').style.display='block'"></td>
                <td>7</td>
                <td>2010</td>
                <td>2</td>
                <td>33</td>
                <td>Approved</td>
              </tr>
              <tr>
                <td>5</td>
                <td><input type="image" value="P4" onClick=" document.getElementById('p1').style.display='block'"></td>
                <td>35</td>
                <td>2010</td>
                <td>2</td>
                <td>4</td>
                <td>Submitted</td>
              </tr>
              <tr>
                <td>6</td>
                <td><input type="image" value="P5" onClick=" document.getElementById('p1').style.display='block'"></td>
                <td>16</td>
                <td>2010</td>
                <td>2</td>
                <td>10</td>
                <td>Submitted</td>
              </tr>
              <tr>
                <td>7</td>
                <td><input type="image" value="P6" onClick=" document.getElementById('p1').style.display='block'"></td>
                <td>34</td>
                <td>2010</td>
                <td>2</td>
                <td>10</td>
                <td>Submitted</td>
              </tr>
              <tr>
                <td>8</td>
                <td><input type="image" value="P7" onClick=" document.getElementById('p1').style.display='block'"></td>
                <td>29</td>
                <td>2010</td>
                <td>2</td>
                <td>10</td>
                <td>Submitted</td>
              </tr>
              <tr>
                <td>9</td>
                <td><input type="image" value="P6" onClick=" document.getElementById('p1').style.display='block'"></td>
                <td>12</td>
                <td>2010</td>
                <td>2</td>
                <td>10</td>
                <td>Edited</td>
              </tr>
              <tr>
                <td>10</td>
                <td><input type="image" value="P7" onClick=" document.getElementById('p1').style.display='block'"></td>
                <td>17</td>
                <td>2010</td>
                <td>2</td>
                <td>10</td>
                <td>Edited</td>
              </tr>
            </table>
          </div>-->
        </div>
      </div>
    </div>
    
    <div class="content"
	        dojotype="dijit.layout.ContentPane" preventcache="true"  refreshonshow="true" title="Member Management" <?php if($pageid!=null&&$pageid==41){echo 'selected';}?>>
      <div id="subcontent2" dojotype="dijit.layout.TabContainer" tabposition="top" region="center">
        <div class="content"
                    dojotype="dijit.layout.ContentPane"
                    title="New Application">
           <div class="content"
                    dojotype="dijit.layout.ContentPane"
                    title="Approved">
          <div id="eval_list">
            <table border="0" cellpadding="0" cellspacing="0" class="subForms">
              <tr>
                <td>&nbsp;</td>
                <td>Last Name</td>
                <td>First Name</td>
                <td>Role</td>
                <td>Apply date</td>
                <td>operations</td>
                
              </tr>
              <tr>
                <td>1</td>
                <td>Peterkin</td>
                <td>Sharon</td>
                <td>Partner</td>
                <td>2010/5/7</td>
                <td><a href="./member_app.php">detail</a></td>
               
              </tr>
              <tr>
                <td>2</td>
                <td>Mike</td>
                <td>White</td>
                <td>Employee</td>
                <td>2010/5/9</td>
                <td>detail</td>
                
              </tr>
              <tr>
                <td>3</td>
                <td>Ben</td>
                <td>White</td>
                <td>Contractor</td>
                <td>2010/5/10</td>
                <td>detail</td>
                
                
              </tr>
            
            </table>
          </div>
        </div>
        </div>
        <div class="content"
                    dojotype="dijit.layout.ContentPane"
                    title="Approved">
          <h3></h3>
          <p></p>
        </div>
      </div>
    </div>
    <div class="content"
	        dojotype="dijit.layout.ContentPane" preventcache="true"  refreshonshow="true" title="Lookup Tables Management">
      <div id="subcontent3" dojotype="dijit.layout.TabContainer" tabposition="top" region="center">
        <div class="content"
                    dojotype="dijit.layout.ContentPane"
                    title="Irrigation System Type and Max DU or EU Efficiencies">
          <div style="margin-top:20px; margin-left:10px;">
            <table border="0" cellpadding="0" cellspacing="0" class="subForms">
              <tr valign="bottom">
                <th align="center"></th>
                <td colspan="2" valign="middle" align="center"><font face="Arial" size="2"><b>Irrigation 
                  System Type</b></font></td>
                <td rowspan="2" valign="middle" align="center"><font face="Arial" size="2"><b>Max 
                  DU or EU (%)</b></font></td>
                 <td rowspan="2" valign="middle" align="center"><font face="Arial" size="2"><b>Operations</b></font></td> 
              </tr>
              <tr valign="bottom">
                <th align="center"></th>
                <td colspan="1" valign="middle" align="center"><font face="Arial" size="2"><b>Common 
                  Name</b></font></td>
                <td colspan="1" valign="middle" align="center"><font face="Arial" size="2"><b>NRCS/Technical 
                  Name</b></font></td>
              </tr>
              <tr valign="bottom">
                <th align="center"  valign="middle"><b>1</b></th>
                <td>Center Pivot - Standard </td>
                <td>Standard Center Pivot</td>
                <td valign="middle" align="center">85</td>
                <td valign="middle" align="center"><input type="submit" value="Edit"><input type="submit" value="delete"></td>
              </tr>
              <tr valign="bottom">
                <th align="center" valign="middle"><b>2</b></th>
                <td><font face="Arial" size="2">Center Pivot-Low Pressure 
                  Drop Downs&nbsp; </font></td>
                <td><font face="Arial" size="2">Center Pivot&nbsp; -&nbsp; 
                  With Low Pressure Drop Downs&nbsp; </font></td>
                <td valign="middle" align="center"><font face="Arial" size="2">94</font></td>
                 <td valign="middle" align="center"><input type="submit" value="Edit"><input type="submit" value="delete"></td>
                
              </tr>
              <tr valign="bottom">
                <th align="center" valign="middle"><b>3</b></th>
                <td><font face="Arial" size="2">Center Pivot or Linear Move 
                  - LEPA</font></td>
                <td><font face="Arial" size="2">Low Energy Precision Application 
                  (LEPA) Center Pivot or Linear Move</font></td>
                <td valign="middle" align="center"><font face="Arial" size="2">95</font></td>
                 <td valign="middle" align="center"><input type="submit" value="Edit"><input type="submit" value="delete"></td>
              </tr>
              <tr valign="bottom">
                <th align="center"><b>4</b></th>
                <td><font face="Arial" size="2">Drip</font></td>
                <td><font face="Arial" size="2">Point Source Emitters</font></td>
                <td valign="middle" align="center"><font face="Arial" size="2">90</font></td>
                 <td valign="middle" align="center"><input type="submit" value="Edit"><input type="submit" value="delete"></td>
              </tr>
              <tr valign="bottom">
                <th align="center"><b>5</b></th>
                <td><font face="Arial" size="2">Gun or Boom&nbsp; - Periodic 
                  Move </font></td>
                <td><font face="Arial" size="2">Periodic Move Gun Type or 
                  Boom</font></td>
                <td valign="middle" align="center"><font face="Arial" size="2">60</font></td>
                 <td valign="middle" align="center"><input type="submit" value="Edit"><input type="submit" value="delete"></td>
              </tr>
              <tr valign="bottom">
                <th align="center"><b>6</b></th>
                <td><font face="Arial" size="2">Micro Spray</font></td>
                <td><font face="Arial" size="2">Spray Emitters</font></td>
                <td valign="middle" align="center"><font face="Arial" size="2">95</font></td>
                 <td valign="middle" align="center"><input type="submit" value="Edit"><input type="submit" value="delete"></td>
              </tr>
              <tr valign="bottom">
                <th align="center"><b>7</b></th>
                <td><font face="Arial" size="2">Open Ditch Irrigation - 
                  Back Water Up</font></td>
                <td><font face="Arial" size="2">Open Ditch Irrigation - 
                  BackUp</font></td>
                <td valign="middle" align="center"><font face="Arial" size="2">75</font></td>
                 <td valign="middle" align="center"><input type="submit" value="Edit"><input type="submit" value="delete"></td>
              </tr>
              <tr valign="bottom">
                <th align="center"><b>8</b></th>
                <td><font face="Arial" size="2">Open Ditch Irrigation - 
                  Crown Flood</font></td>
                <td><font face="Arial" size="2">Open Ditch Irrigation - 
                  Crown Flood</font></td>
                <td valign="middle" align="center"><font face="Arial" size="2">80</font></td>
                 <td valign="middle" align="center"><input type="submit" value="Edit"><input type="submit" value="delete"></td>
              </tr>
              <tr valign="bottom">
                <th align="center"><b>9</b></th>
                <td><font face="Arial" size="2">Open Ditch Irrigation - 
                  Flow Through</font></td>
                <td><font face="Arial" size="2">Open Ditch Irrigation - 
                  Flow Through</font></td>
                <td valign="middle" align="center"><font face="Arial" size="2">80</font></td>
                 <td valign="middle" align="center"><input type="submit" value="Edit"><input type="submit" value="delete"></td>
              </tr>
              <tr valign="bottom">
                <th align="center"><b>10</b></th>
                <td><font face="Arial" size="2">Seepage Irrigation with 
                  Graded Furrows</font></td>
                <td><font face="Arial" size="2">Surface Irrigation Graded 
                  Furrow</font></td>
                <td valign="middle" align="center"><font face="Arial" size="2">80</font></td>
                 <td valign="middle" align="center"><input type="submit" value="Edit"><input type="submit" value="delete"></td>
              </tr>
              <tr valign="bottom">
                <th align="center"><b>11</b></th>
                <td><font face="Arial" size="2">Seepage Irrigation with 
                  Level Furrows</font></td>
                <td><font face="Arial" size="2">Surface Irrigation Level 
                  Furrow</font></td>
                <td valign="middle" align="center"><font face="Arial" size="2">85</font></td>
                 <td valign="middle" align="center"><input type="submit" value="Edit"><input type="submit" value="delete"></td>
              </tr>
              <tr valign="bottom">
                <th align="center"><b>12</b></th>
                <td><font face="Arial" size="2">Spaguetti Tube</font></td>
                <td><font face="Arial" size="2">Line Source Emitters</font></td>
                <td valign="middle" align="center"><font face="Arial" size="2">90</font></td>
                 <td valign="middle" align="center"><input type="submit" value="Edit"><input type="submit" value="delete"></td>
              </tr>
              <tr valign="bottom">
                <th align="center"><b>13</b></th>
                <td><font face="Arial" size="2">Sprinkler - Fixed </font></td>
                <td><font face="Arial" size="2">Sprinkler - Fixed Lateral 
                  Solid Set </font></td>
                <td valign="middle" align="center"><font face="Arial" size="2">80</font></td>
                
                 <td valign="middle" align="center"><input type="submit" value="Edit"><input type="submit" value="delete"></td>
              </tr>
              <tr valign="bottom">
                <th align="center"><b>14</b></th>
                <td><font face="Arial" size="2">Sprinkler - Periodic Lateral 
                  Move</font></td>
                <td><font face="Arial" size="2">Sprinkler - Periodic Move 
                  Lateral</font></td>
                <td valign="middle" align="center"><font face="Arial" size="2">75</font></td>
                 <td valign="middle" align="center"><input type="submit" value="Edit"><input type="submit" value="delete"></td>
              </tr>
              <tr valign="bottom">
                <th align="center"><b>15</b></th>
                <td><font face="Arial" size="2">Sprinkler - Linear or Lateral 
                  Move</font></td>
                <td><font face="Arial" size="2">Sprinkler - Linear Lateral 
                  Move</font></td>
                <td valign="middle" align="center"><font face="Arial" size="2">87</font></td>
                 <td valign="middle" align="center"><input type="submit" value="Edit"><input type="submit" value="delete"></td>
              </tr>
              <tr valign="bottom">
                <th align="center"><b>16</b></th>
                <td><font face="Arial" size="2">Traveling Gun</font></td>
                <td><font face="Arial" size="2">Traveling Gun Gun type or 
                  Boom</font></td>
                <td valign="middle" align="center"><font face="Arial" size="2">65</font></td>
                 <td valign="middle" align="center"><input type="submit" value="Edit"><input type="submit" value="delete"></td>
              </tr>
            </table>
          <a href="#"><span style="font:Georgia, 'Times New Roman', Times, serif; font-size:14px; font-weight:600">Add New Irrigation System Type</span></a>
          </div>
          
        </div>
        <div class="content"
                    dojotype="dijit.layout.ContentPane"
                    title="Ag and Urban Types and Names">
          <h3></h3>
          <p></p>
        </div>
        <div class="content"
                    dojotype="dijit.layout.ContentPane"
                    title="Water Sources">
          <h3></h3>
          <p></p>
        </div>
        <div class="content"
                    dojotype="dijit.layout.ContentPane"
                    title="Pump Types">
          <h3></h3>
          <p></p>
        </div>
        <div class="content"
                    dojotype="dijit.layout.ContentPane"
                    title="Motor Types">
          <h3></h3>
          <p></p>
        </div>
        <div class="content"
                    dojotype="dijit.layout.ContentPane"
                    title="MIL Labs Name and No.">
          <h3></h3>
          <p></p>
        </div>
        <div class="content"
                    dojotype="dijit.layout.ContentPane"
                    title="MIL Evaluation Type and Associated Saving Type">
          <h3></h3>
          <p></p>
        </div>
      </div>
    </div>
    <div class="content"
	        dojotype="dijit.layout.ContentPane" preventcache="true"  refreshonshow="true" title="MIL Management" <?php if($pageid!=null&&($pageid==61)){echo 'selected';}?>>
      <div id="subcontent5" dojotype="dijit.layout.TabContainer" tabposition="top" region="center">
        <div class="content"
                    dojotype="dijit.layout.ContentPane"
                    title="Active MILs">
          <table border="0" cellpadding="0" cellspacing="0" class="subForms">
              <tr>
                <td>MIL Name</td>
                <td>MIL  ID</td>
                <td>MIL Type</td>
                <td>MIL Contractor</td>
                <td>Current FDACS Contract Number</td>
                <td>FDACS Funding Per Current Contract</td>
                <td>
                <table>
                <tr>
                <td colspan="3">
                Number of Evaluations per Current Contract
                </td>
                </tr>
                <tr>
                <td>Total</td><td>Initial</td><td>Follow up</td>
                </tr>
                </table>
                </td>
                
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>16</td>
                <td>Ag - Floridan</td>
                <td>Ag</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                
                <td>&nbsp;</td>            
                 <td>&nbsp;</td>

                <td> View more information/ <br />
                  Edit/ &nbsp;Inactive</td>
               
              </tr>
              <tr>
                <td>26</td>
                <td>Ag - Lake</td>
                <td>Ag</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
               <td>&nbsp;</td>
                <td>&nbsp;</td>
                 
                <td>View more information /<br />
                Edit/&nbsp;Inactive</td>
                               

              </tr>
              <tr>
                <td>2</td>
                <td>Ag - Lower West Coast</td>
                <td>Ag</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                
                <td>&nbsp;</td>
               <td>&nbsp;</td>
                <td>View more information/ <br />
                  Edit/ Inactive</td>
                                

                
              </tr>
            
          </table>
            <p style="clear:left"><a href="./new_lab.php?type=61">Add More Lab</a></p>
        </div>
         <div class="content"
                    dojotype="dijit.layout.ContentPane"
                    title="Inactive MILs">
          
        </div>
         <div class="content"
                    dojotype="dijit.layout.ContentPane"
                    title="All MILs">
          
        </div>
         <div class="content"
                    dojotype="dijit.layout.ContentPane"
                    title="Add New MILs">
  
          
        </div>
        
      </div>
    </div>
     <div class="content"
	        dojotype="dijit.layout.ContentPane" preventcache="true"  refreshonshow="true" title="Contracts Management"  <?php if($pageid!=null&&($pageid==71||$pageid=31)){echo 'selected';}?>>
      <div id="subcontent6" dojotype="dijit.layout.TabContainer" tabposition="top" region="center">
        
        <div class="content"  dojotype="dijit.layout.ContentPane" title="New">
        <table border="0" cellpadding="0" cellspacing="0" class="subForms">
            <tr>
              <td>Contractor</td>
              <td>MIL Lab Name</td>
              <td>Contracting Number</td>
              <td>Contract Amount</td>
              <td>Total Evaluations Required</td>
              <td><p>Total Initial Evaluation Required</p></td>
              <td><p>Total Follow up Evaluation Required</p></td>
              <td>Contract Period From</td>
              <td>Contract Period To</td>
              <td>Contract Length(month)</td>
              <td>Current Funding by Other Partners</td>
              <td>Operations</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>Ag - Floridan</td>
              <td>015834</td>
              <td>8930</td>
              <td>10</td>
              <td>3</td>
              <td>10</td>
              <td>2011/1/1</td>
              <td>2011/12/31</td>
              <td>12</td>
              <td>1000</td>
              <td><a href="./contract.php?type=71">Edit</a> Delete</td>
            </tr>
            <tr>
               
              <td>&nbsp;</td>
              <td>Ag - Lake</td>
              <td>014834</td>
              <td>8930</td>
              <td>10</td>
              <td>3</td>
              <td>10</td>
              <td>2011/1/1</td>
              <td>2011/12/31</td>
              <td>12</td>
              <td>1000</td>
              <td>Edit Delete</td>
            
            </tr>
            <tr>
             
              <td>&nbsp;</td>
              <td>Ag - Lower West Coast</td>
              <td>016834</td>
              <td>8930</td>
              <td>10</td>
              <td>3</td>
              <td>10</td>
              <td>2011/1/1</td>
              <td>2011/12/31</td>
              <td>12</td>
              <td>1000</td>
              <td>Edit Delete</td>
            
            </tr>
          </table>
          <p style="clear:left"><a href="./contract.php?type=71">Add More Contract</a></p>
        </div>
        <div class="content" dojotype="dijit.layout.ContentPane"title="Current">
           <table border="0" cellpadding="0" cellspacing="0" class="subForms">
            <tr>
              <td>Contractor</td>
              <td>MIL Lab Name</td>
              <td>Contracting Number</td>
              <td>Contract Amount</td>
              <td>Total Evaluations</td>
              <td>Total Initial Evaluation</td>
              <td>Total Follow up Evaluation</td>
              <td>Contract Period From</td>
              <td>Contract Period To</td>
              <td>Contract Length(month)</td>
              <td>Current Funding by Other Partners</td>
              <td>Operations</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>Ag - Floridan</td>
              <td>015834</td>
              <td>8930</td>
              <td>10</td>
              <td>3</td>
              <td>10</td>
              <td>2010/1/1</td>
              <td>2010/12/31</td>
              <td>12</td>
              <td>1000</td>
              <td>View</td>
            </tr>
            <tr>
              
              <td>&nbsp;</td>
              <td>Ag - Lake</td>
              <td>014834</td>
              <td>8930</td>
              <td>10</td>
              <td>3</td>
              <td>10</td>
              <td>2010/1/1</td>
              <td>2010/12/31</td>
              <td>12</td>
              <td>1000</td>
              <td>View</td>
            </tr>
          
            <tr>
            
              <td>&nbsp;</td>
              <td>Ag - Lower West Coast</td>
              <td>016834</td>
              <td>8930</td>
              <td>10</td>
              <td>3</td>
              <td>10</td>
              <td>2010/1/1</td>
              <td>2010/12/31</td>
              <td>12</td>
              <td>1000</td>
              <td>View</td>
            
            </tr>
          </table>
        </div>
        <div class="content"
                    dojotype="dijit.layout.ContentPane"
                    title="All">
          <h3></h3>
          <p></p>
        </div>
         <div class="content"
                    dojotype="dijit.layout.ContentPane"
                    title="Invoices" <?php if($pageid!=null&&($pageid==31)){echo 'selected';}?>>
         <p style="clear:both;">Submitted</p>
                   <table border="0" cellpadding="0" cellspacing="0" class="subForms">
                        <tr>
                          <td>Contractor</td>
                          <td>Contract Number</td>
                          <td>MIL  Name</td>
                          <td>
                          <table>
                          <tr>
                          <td colspan="2">Invoice Period Covered</td>
                          </tr>
                          <tr>
                          <td bgcolor="#FF0000">From Date</td>
                          <td>&nbsp;</td>
                          <td bgcolor="#FF0000">End Date</td>
                          </tr>
                          </table>
                          </td>
                          <td>Invoice Total Amount</td>
                          <td>Invoice Number</td>
                          
                          
                          <td>Operations</td>
                     <tr>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>Ag - Froridan</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>2</td>
                              <td><a href="./invoice_sub.php?type=31">Review</a> &nbsp;</td>
               		 </tr>
                           <tr>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>Ag - Lake</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>2</td>
                              
                              <td>Review</td>
               		 </tr>
                             
                            <tr>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>Ag - Lower West Coast</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                             <td>2</td>
                              <td>Review</td>
                     </tr>
                        
                        
          </table> 
          <p style="clear:both;">Approved</p>
         <table border="0" cellpadding="0" cellspacing="0" class="subForms">
                        <tr>
                          <td>Contractor</td>
                          <td>Contract Number</td>
                          <td>MIL  Name</td>
                          <td>
                          <table>
                          <tr>
                          <td colspan="2">Invoice Period Covered</td>
                          </tr>
                          <tr>
                          <td bgcolor="#FF0000">From Date</td>
                          <td>&nbsp;</td>
                          <td bgcolor="#FF0000">End Date</td>
                          </tr>
                          </table>
                          </td>
                          <td>Invoice Total Amount</td>
                          <td>Invoice Number</td>
                          
                          
                          <td>Operations</td>
                     <tr>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>Ag - Froridan</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>2</td>
                              <td><a href="./invoice_sub.php?type=31">Review</a> &nbsp;</td>
               		 </tr>
                           <tr>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>Ag - Lake</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>2</td>
                              
                              <td>Review</td>
               		 </tr>
                             
                            <tr>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>Ag - Lower West Coast</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                             <td>2</td>
                              <td>Review</td>
                     </tr>
                        
                        
          </table> 
        </div>
      </div>
    </div>
    <!--Beginning of Report Tab-->
     <div class="content"
	        dojotype="dijit.layout.ContentPane" preventcache="true"  refreshonshow="true" title="Reports">
      <div id="subcontent4" dojotype="dijit.layout.TabContainer" tabposition="top" region="center">
        <!--Beginning of Report 1-->
        <div class="content"
                    dojotype="dijit.layout.ContentPane"
                    title="Water Conservation Information">
          <h3>Water Conservation Information</h3>
          <h3>Sample of report:</h3>
          <img src="images/report1.JPG">
          <p></p>
          <!--Beginning of create your own report-->
          <h3> Create your own report:</h3>
          <div style="border:1px #CCC solid; width:590px;  padding:10px"> 
           <!--Beginning of Select Period-->
         	<h4 style="clear:left;">Select Period:</h4>
           	 FDACS Fiscal Year: 
           	 <select name="CELL_ID2">
           	   <option value="0" selected>Choose one</option>
           	   <option value="1">09/10</option>
           	   <option value="2">08/09</option>
       	    </select>
FDACS Qtr:

           	 <select name="CELL_ID">
              <option value="0" selected>Choose one</option>
              <option value="1">Qtr1</option>
              <option value="2">Qtr2</option>
              <option value="1">Qtr3</option>
            <option value="2">Qtr4</option></select> 
              <br />or<br />
               <strong>From</strong> FDACS Fiscal Year:
<select name="CELL_ID">
              <option value="0" selected>Choose one</option>
              <option value="1">09/10</option>
              <option value="2">08/09</option></select>
             FDACS Qtr: <select name="CELL_ID">
              <option value="0" selected>Choose one</option>
              <option value="1">Qtr1</option>
              <option value="2">Qtr2</option>
              <option value="1">Qtr3</option>
              <option value="2">Qtr4</option></select> <br />
              <strong>To</strong> FDACS Fiscal Year:&nbsp; &nbsp;&nbsp;&nbsp;
               <select name="CELL_ID">
              <option value="0" selected>Choose one</option>
              <option value="1">09/10</option>
            <option value="2">08/09</option></select>
             FDACS Qtr: <select name="CELL_ID">
              <option value="0" selected>Choose one</option>
              <option value="1">Qtr1</option>
              <option value="2">Qtr2</option>
              <option value="1">Qtr3</option>
              <option value="2">Qtr4</option></select> 
           <!--End of Select Period>
           <!--Beginning of Select MIL-->
           	<h4>Select MIL Labs: </h4> 
           	<div id="fund_mils">
                <div class="nextDoor first" >
                    <div><input type="checkbox"> <label>Ag - Floridan</label></div>
                    <div><input type="checkbox"> <label>Ag - Lake</label></div>
                    <div><input type="checkbox"><label>Ag - Lower West Coast</label></div>
                    <div><input type="checkbox"> <label>Ag - Northwest Florida</label></div>	
              </div>
                <div class="nextDoor">
                    <div><input type="checkbox"><label>Ag - NRCS Wauchula</label></div>
                    <div><input type="checkbox"><label>Ag - Palm Beach Broward </label></div>
                    <div><input type="checkbox"> <label>Ag - South Dade</label></div>
                    <div><input type="checkbox"><label> Ag - St. Lucie Area</label></div>
                </div>
                <div class="nextDoor">
                    <div><input type="checkbox"><label> Ag - Suwannee River</label></div>
                    <div><input type="checkbox"> <label>Ag - SWFWMD ProMIL</label></div>
                    <div><input type="checkbox"> <label>Ag - Tampa Bay Estuary</label></div>
                </div>
                <div style="clear:both"></div>
                <div class="nextDoor">
                    <div><input type="checkbox"><label> Urban - Big Cypress Basin</label></div>
                    <div><input type="checkbox"><label> Urban - Broward</label></div>
                    <div><input type="checkbox"><label> Urban - East Central FL </label></div>
                    <div><input type="checkbox"><label> Urban - Floridan RC&D </label></div>
                </div>
                <div class="nextDoor">
                    <div><input type="checkbox"><label> Urban - Lower West Coast</label></div>
                    <div><input type="checkbox"><label> Urban - Manatee County</label></div>
                    <div><input type="checkbox"><label> Urban - Martin</label></div>
                    <div><input type="checkbox"><label>Urban - Palm Beach</label></div>
                </div>
                <div class="nextDoor">
                    <div><input type="checkbox"><label> Urban - South Dade</label></div>
                    <div><input type="checkbox"> <label>Urban - St Lucie Area</label></div>
                    <div><input type="checkbox"> <label>Urban - Tampa Bay</label></div>
                </div>
            	<div style="clear:left;"> or &nbsp;<label><input type="checkbox"> Select All</label></div>
            </div>
            <!--End of Select MIL-->
           <!--Beginning of select items to view-->
           <h4>Select Items to View: </h4>  
           <div id="fund_mils">
                <div class="nextDoor" >
                  <div><input type="checkbox"><label>No. Evaluations</label></div>
                  <div><input type="checkbox"><label>No. Follow-ups<br></label></div>
                  <div><input type="checkbox"><label>Total Acres</label></div>
                  <div><input type="checkbox"><label>Potential Water Savings</label></div>	
              </div>
                 <div class="nextDoor" >
                  <div><input type="checkbox"><label>Actual Water Savings</label></div>
                  <div><input type="checkbox"><label>No. Waiting  Evaluation</label></div>
                  <div><input type="checkbox"><label> No. Waiting Acres</label></div>
                 </div>
            </div>
             <!--End of select items to view-->   
           <p style="width:550px; text-align:right; clear:left">
           <input type="submit" name="submit" value="Submit">
           </p>
          </div>
          <!--End of create your own report-->
        </div>
        <!--End of Report 1-->
         
        <!--Beginning of Report 2-->
        <div class="content"
                    dojotype="dijit.layout.ContentPane"
                    title="Water Savings">
          <h3>Water Savings</h3>
          <h3>Sample of report:</h3>
          <img src="images/report2.JPG">
          <p></p>
          <!--Beginning of create your own report-->
          <h3> Create your own report:</h3>
          <div style="border:1px #CCC solid; width:590px;  padding:10px"> 
           <!--Beginning of Select Period-->
         	<h4 >Select Period:</h4>
           	 FDACS Fiscal Year: 
           	 <select name="CELL_ID2">
           	   <option value="0" selected>Choose one</option>
           	   <option value="1">09/10</option>
           	   <option value="2">08/09</option>
       	    </select>
FDACS Qtr:

           	 <select name="CELL_ID">
              <option value="0" selected>Choose one</option>
              <option value="1">Qtr1</option>
              <option value="2">Qtr2</option>
              <option value="1">Qtr3</option>
            <option value="2">Qtr4</option></select> 
              <br />or<br />
               <strong>From</strong> FDACS Fiscal Year:
<select name="CELL_ID">
              <option value="0" selected>Choose one</option>
              <option value="1">09/10</option>
              <option value="2">08/09</option></select>
             FDACS Qtr: <select name="CELL_ID">
              <option value="0" selected>Choose one</option>
              <option value="1">Qtr1</option>
              <option value="2">Qtr2</option>
              <option value="1">Qtr3</option>
              <option value="2">Qtr4</option></select> <br />
              <strong>To</strong> FDACS Fiscal Year:&nbsp; &nbsp;&nbsp;&nbsp;
               <select name="CELL_ID">
              <option value="0" selected>Choose one</option>
              <option value="1">09/10</option>
            <option value="2">08/09</option></select>
             FDACS Qtr: <select name="CELL_ID">
              <option value="0" selected>Choose one</option>
              <option value="1">Qtr1</option>
              <option value="2">Qtr2</option>
              <option value="1">Qtr3</option>
              <option value="2">Qtr4</option></select> 
           <!--End of Select Period-->
           <!--Beginning of Select MIL-->
           	<h4>Select MIL Labs: </h4> 
           	<div id="fund_mils">
                <div class="nextDoor first" >
                    <div><input type="checkbox"> <label>Ag - Floridan</label></div>
                    <div><input type="checkbox"> <label>Ag - Lake</label></div>
                    <div><input type="checkbox"><label>Ag - Lower West Coast</label></div>
                    <div><input type="checkbox"> <label>Ag - Northwest Florida</label></div>	
              </div>
                <div class="nextDoor">
                    <div><input type="checkbox"><label>Ag - NRCS Wauchula</label></div>
                    <div><input type="checkbox"><label>Ag - Palm Beach Broward </label></div>
                    <div><input type="checkbox"> <label>Ag - South Dade</label></div>
                    <div><input type="checkbox"><label> Ag - St. Lucie Area</label></div>
                </div>
                <div class="nextDoor">
                    <div><input type="checkbox"><label> Ag - Suwannee River</label></div>
                    <div><input type="checkbox"> <label>Ag - SWFWMD ProMIL</label></div>
                    <div><input type="checkbox"> <label>Ag - Tampa Bay Estuary</label></div>
                </div>
                <div style="clear:both"></div>
                <div class="nextDoor">
                    <div><input type="checkbox"><label> Urban - Big Cypress Basin</label></div>
                    <div><input type="checkbox"><label> Urban - Broward</label></div>
                    <div><input type="checkbox"><label> Urban - East Central FL </label></div>
                    <div><input type="checkbox"><label> Urban - Floridan RC&D </label></div>
                </div>
                <div class="nextDoor">
                    <div><input type="checkbox"><label> Urban - Lower West Coast</label></div>
                    <div><input type="checkbox"><label> Urban - Manatee County</label></div>
                    <div><input type="checkbox"><label> Urban - Martin</label></div>
                    <div><input type="checkbox"><label>Urban - Palm Beach</label></div>
                </div>
                <div class="nextDoor">
                    <div><input type="checkbox"><label> Urban - South Dade</label></div>
                    <div><input type="checkbox"> <label>Urban - St Lucie Area</label></div>
                    <div><input type="checkbox"> <label>Urban - Tampa Bay</label></div>
                </div>
            	<div style="clear:left;"> or &nbsp;<label><input type="checkbox"> Select All</label></div>
            </div>
            <!--End of Select MIL-->
           <!--Beginning of select items to view-->
           <h4>Select Items to View: </h4>  
           <div id="fund_mils">
                <div class="nextDoor" >
                  <div><input type="checkbox">
                  <label> Millions of Gallon Saved per Year</label></div>
                  <div><input type="checkbox">
                  <label> Waiting List for Next Quarter<br></label></div>
                </div>
            </div>
            <!--End of select items to view-->   
           <p style="width:550px; text-align:right; clear:left">
           <input type="submit" name="submit" value="Submit">
           </p>
          </div>
          <!--End of create your own report-->
        </div>
        <!--End of Report 2-->
       
       
        <!--Beginning of Report 3A-->
        <div class="content"
                    dojotype="dijit.layout.ContentPane"
                    title="Ag and Urban Water Conservation Summary Information">
          <h3> Ag and Urban MIL Program Water Conservation Summary Information: by Fiscal Year and Quarter							
</h3>
          <h3>Sample of report:</h3>
          <img src="images/report3a.JPG">
          <p></p>
          <!--Beginning of create your own report-->
          <h3> Create your own report:</h3>
          <div style="border:1px #CCC solid; width:590px;  padding:10px"> 
           <!--Beginning of Select Period-->
         	<h4 style="clear:left;">Select Period:</h4>
           	 <p>Start FDACS Fiscal Year:
               <select name="CELL_ID">
                 <option value="0" selected>Choose one</option>
                 <option value="1">09/10</option>
                 <option value="2">08/09</option>
               </select>
             FDACS Qtr: 
             <select name="CELL_ID">
               <option value="0" selected>Choose one</option>
               <option value="1">Qtr1</option>
               <option value="2">Qtr2</option>
               <option value="1">Qtr3</option>
               <option value="2">Qtr4</option>
             </select> 
           	</p>
           	<p>End FDACS Fiscal Year:&nbsp;
               <select name="CELL_ID">
                 <option value="0" selected>Choose one</option>
                 <option value="1">09/10</option>
                 <option value="2">08/09</option>
               </select>
             FDACS Qtr: 
             <select name="CELL_ID">
               <option value="0" selected>Choose one</option>
               <option value="1">Qtr1</option>
               <option value="2">Qtr2</option>
               <option value="1">Qtr3</option>
               <option value="2">Qtr4</option>
             </select> 
           	</p>
           	   <!--End of Select Period>
           <!--Beginning of Select Type-->
       	  
       	    <h4>Select Type: </h4> 
           	<div id="fund_mils">
                <div class="nextDoor first" >
                    <div><input type="checkbox"> <label>Ag </label></div>
                    <div><input type="checkbox"> <label>Urban</label></div>
              </div>
                
           	  <div style="clear:left;"> or &nbsp;<label><input type="checkbox"> Select All</label></div>
            </div>
            <!--End of Select Type-->
           <!--Beginning of select items to view-->
           <h4>Select Items to View: </h4>  
           <div id="fund_mils">
                <div class="nextDoor" >
                  <div><input type="checkbox"><label>No. Evaluations</label></div>
                  <div><input type="checkbox"><label>No. Follow-ups<br></label></div>
                  <div><input type="checkbox"><label>Total Acres</label></div>
                  <div><input type="checkbox"><label>Potential Water Savings</label></div>	
                 </div>
                 <div class="nextDoor" >
                  <div><input type="checkbox"><label>Actual Water Savings</label></div>
                  <div><input type="checkbox"><label>No. Waiting  Evaluation</label></div>
                  <div><input type="checkbox"><label> No. Waiting Acres</label></div>
                 </div>
             </div>
             <!--End of select items to view-->   
           <p style="width:550px; text-align:right; clear:left">
           <input type="submit" name="submit" value="Submit">
           </p>
          </div>
          <!--End of create your own report-->
        </div>
        <!--End of Report 3A-->
        
        
        <!--Beginning of Report 6A-->
        <div class="content"
                    dojotype="dijit.layout.ContentPane"
                    title="Ag Irrigation System Water Conservation Performance History By MIL">
          <h3> Ag Irrigation System Water Conservation Performance History By MIL					
</h3>
          <h3>Sample of report:</h3>
          <img src="images/report6a.JPG">
          <p></p>
          <!--Beginning of create your own report-->
          <h3> Create your own report:</h3>
          <div style="border:1px #CCC solid; width:590px;  padding:10px"> 
           
            <!--Beginning of Select MIL-->
           	<h4>Select MIL Labs: </h4> 
           	<div id="fund_mils">
                <div class="nextDoor first" >
                    <div><input type="checkbox"> <label>Ag - Floridan</label></div>
                    <div><input type="checkbox"> <label>Ag - Lake</label></div>
                    <div><input type="checkbox"><label>Ag - Lower West Coast</label></div>
                    <div><input type="checkbox"> <label>Ag - Northwest Florida</label></div>	
                 </div>
                <div class="nextDoor">
                    <div><input type="checkbox"><label>Ag - NRCS Wauchula</label></div>
                    <div><input type="checkbox"><label>Ag - Palm Beach Broward </label></div>
                    <div><input type="checkbox"> <label>Ag - South Dade</label></div>
                    <div><input type="checkbox"><label> Ag - St. Lucie Area</label></div>
                </div>
                <div class="nextDoor">
                    <div><input type="checkbox"><label> Ag - Suwannee River</label></div>
                    <div><input type="checkbox"> <label>Ag - SWFWMD ProMIL</label></div>
                    <div><input type="checkbox"> <label>Ag - Tampa Bay Estuary</label></div>
                </div>
                <div style="clear:both"></div>
                <div class="nextDoor">
                    <div><input type="checkbox"><label> Urban - Big Cypress Basin</label></div>
                    <div><input type="checkbox"><label> Urban - Broward</label></div>
                    <div><input type="checkbox"><label> Urban - East Central FL </label></div>
                    <div><input type="checkbox"><label> Urban - Floridan RC&D </label></div>
                </div>
                <div class="nextDoor">
                    <div><input type="checkbox"><label> Urban - Lower West Coast</label></div>
                    <div><input type="checkbox"><label> Urban - Manatee County</label></div>
                    <div><input type="checkbox"><label> Urban - Martin</label></div>
                    <div><input type="checkbox"><label>Urban - Palm Beach</label></div>
                </div>
                <div class="nextDoor">
                    <div><input type="checkbox"><label> Urban - South Dade</label></div>
                    <div><input type="checkbox"> <label>Urban - St Lucie Area</label></div>
                    <div><input type="checkbox"> <label>Urban - Tampa Bay</label></div>
                </div>
            	<div style="clear:left;"> or &nbsp;<label><input type="checkbox"> Select All</label></div>
            </div>
            <!--End of Select MIL-->
           <!--Beginning of select items to view-->
           <h4>Select Items to View: </h4>  
           <div id="fund_mils">
                <div class="nextDoor" >
                  <div><input type="checkbox">
                  <label> Initial Evaluation</label></div>
                  <div><input type="checkbox"> 
                  <label>Follow Up Evaluation No.1<br></label></div>
                  <div><input type="checkbox">
                  <label> Follow Up Evaluation No.2</label></div>
                  <div><input type="checkbox"> 
                  <label>Follow Up Evaluation No.3</label></div>	
                 </div>
            </div>
             <!--End of select items to view-->   
           <p style="width:550px; text-align:right; clear:left">
           <input type="submit" name="submit" value="Submit">
           </p>
          </div>
          <!--End of create your own report-->
        </div>
        <!--End of Report 6A-->
        
         <!--Beginning of Report 6C-->
        <div class="content"
                    dojotype="dijit.layout.ContentPane"
                    title="Ag Irrigation System Water Conservation Performance History By Irrigation System Type">
          <h3> Ag Irrigation System Water Conservation Performance History By Irrigation System Type					
</h3>
          <h3>Sample of report:</h3>
          <img src="images/report6c.JPG">
          <p></p>
          <!--Beginning of create your own report-->
          <h3> Create your own report:</h3>
          <div style="border:1px #CCC solid; width:590px;  padding:10px"> 
           
            <!--Beginning of Select Irrigation Type-->
           	<h4>Select MIL Labs: </h4> 
           	<div id="fund_mils">
                <div class="nextDoor first" >
                    <div><input type="checkbox"> <label> Center Pivot  -  Standard </label></div>
                    <div><input type="checkbox"> <label> Center Pivot-Low Pressure  Nozzles </label></div>
                    <div><input type="checkbox"><label> Center Pivot or Linear Move - LEPA</label></div>
                    <div><input type="checkbox"> <label> Drip</label></div>	
              </div>
                <div class="nextDoor">
                    <div><input type="checkbox"><label> Gun or Boom  - Periodic Move </label></div>
                    <div><input type="checkbox"><label> Micro Spray </label></div>
                    <div><input type="checkbox"> <label>Open Ditch Irrigation - Back Water Up</label></div>
                    <div><input type="checkbox"><label>Open Ditch Irrigation - Crown Flood</label></div>
                </div>
              
                <div style="clear:both"></div>
                <div class="nextDoor">
                    <div><input type="checkbox"><label> Open Ditch Irrigation - Flow Through</label></div>
                    <div><input type="checkbox"><label> Seepage Irrigation with Graded Furrows</label></div>
                    <div><input type="checkbox"><label> Spaguetti Tube </label></div>
                    <div><input type="checkbox"><label> Sprinkler - Fixed  </label></div>
                </div>
                <div class="nextDoor">
                    <div><input type="checkbox"><label> Sprinkler - Periodic Lateral or Hand Move</label></div>
                    <div><input type="checkbox"><label> Sprinkler - Linear or Lateral Move</label></div>
                    <div><input type="checkbox"><label> Traveling Gun</label></div>
                </div>
            	<div style="clear:left;"> or &nbsp;<label><input type="checkbox"> Select All</label></div>
            </div>
            <!--End of Select MIL-->
           <!--Beginning of select items to view-->
           <h4>Select Items to View: </h4>  
           <div id="fund_mils">
                <div class="nextDoor" >
                  <div><input type="checkbox">
                  <label> Initial Evaluation</label></div>
                  <div><input type="checkbox"> 
                  <label>Follow Up Evaluation No.1<br></label></div>
                  <div><input type="checkbox">
                  <label> Follow Up Evaluation No.2</label></div>
                  <div><input type="checkbox"> 
                  <label>Follow Up Evaluation No.3</label></div>	
              </div>
            </div>
             <!--End of select items to view-->   
           <p style="width:550px; text-align:right; clear:left">
           <input type="submit" name="submit" value="Submit">
           </p>
          </div>
          <!--End of create your own report-->
        </div>
        <!--End of Report 6C-->
      </div>
    </div>
       <!--End of Report Tab-->
    <div class="content"
	        dojotype="dijit.layout.ContentPane" preventcache="true"  refreshonshow="true" title="Query Tools">
      <div id="subcontent8" dojotype="dijit.layout.TabContainer" tabposition="top" region="center">
      </div>
     </div>
     
     
  </div>
</div>
</div>
</div>
		<div id="footer">
			<div id="sponsorLogos">
				<p><?php session_start(); echo 'role'. $_SESSION['role'];?></p>
				<p>Sponsored by the State of FLorida, The St. Johns Water Management District, and The Florida Department of Agriculture</p>
			</div>
		</div>
  		

</body>

</html>
