<?php

require_once dirname(__FILE__) . '/../mil_init.php';
require_once dirname(__FILE__) . '/../utility.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/FollowIrrSys.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/InitIrrSys.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/FollowFirm.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/InitFirm.php';

Class Report14D extends Report {

    private $params = array(
        'mil_labs' => array()
        , 'cal_start_yr' => ''
        , 'cal_start_month' => ''
        , 'cal_end_yr' => ''
        , 'cal_end_month' => ''
        , 'eval_method' => ''
    );
    private $results = array();
    private $reportTitle;
    private $tableHeader;
    private $tableContent;
    private $cropTypes = array();
    private $irrTypes = array();

    public function init($array) {
        //initialize request params $this->params
        foreach ($this->params as $key => $val) {
            if (array_key_exists($key, $array)) {
                if ($key == 'mil_labs')
                    $array[$key] = explode(",", $array[$key]);
                $this->params[$key] = $array[$key];
            }else {
                throw new Exception("Parameter $key is required.");
            }
        }
        $this->cropTypes = Utility::getLookupTable('ag_urban_types_names');
        $this->counties = Utility::getLookupTable("fl_county");
        $this->irrTypes = Utility::getLookupTable('irr_sys_types');

        $sql00 = "select id, name from ag_urban_types_names";  // 新添加
        $query_rez = MIL::doQuery($sql00, MYSQL_ASSOC);
        //print_r($query_rez);
        foreach ($query_rez as $i => $value) {
            $this->cropTypes[$i] = $value;
        }


        $sql = "select id, common_name from irr_sys_types";
        $query_rez1 = MIL::doQuery($sql, MYSQL_ASSOC);
        //print_r($query_rez);
        foreach ($query_rez1 as $i => $value) {
            $this->irrTypes[$i] = $value;
        }
    }

    public function requestDBData($array) {
        $this->init($array);
        return $this->setResult();
    }

    public function setResult() {
        $labs_id = $this->params['mil_labs'];
        $start_yr = $this->params['cal_start_yr'];
        $end_yr = $this->params['cal_end_yr'];
        $start_month = $this->params['cal_start_month'];
        $end_month = $this->params['cal_end_month'];
        $this->eval_method = $this->params['eval_method'];
        if ($labs_id == null || count($labs_id) == 0) {
            return;
        }
        $labs_str = implode(",", $labs_id);
        //print_r($labs_id);
        $labs_str = "(" . $labs_str . ")";
        /* $sql = "
          select county_id
          , count(distinct(farm_id)) num_of_irr_sys
          , sum(acre) total_acres
          , format(sum(pws),2) pws
          , format(sum(aws),2) aws
          , date_format(min(eval_yr_month), '%Y-%m') start_date
          ,date_format(max(eval_yr_month), '%Y-%m') end_date
          from evaluation
          where eval_yr  = $start_yr
          and eval_month >= $start_month
          and eval_month <=  $end_month
          and mil_id  in ($labs_str)
          and status='approved'
          and eval_type = 3
          group by county_id";
         * */
        if ($start_month <= 9) {
            $start_yr_month = "'" . $start_yr . '-0' . $start_month . "-01'";
        } else {
            $start_yr_month = "'" . $start_yr . '-' . $start_month . "-01'";
        }
        if ($end_month <= 8) {
            $end_yr_month = "'" . $end_yr . '-0' . ($end_month + 1) . "-01'";
        } else if ($end_month <= 11) {
            $end_yr_month = "'" . $end_yr . '-' . ($end_month + 1) . "-01'";
        } else {

            $end_yr_month = "'" . ($end_yr + 1) . "-01-01'";
        }
        if ($this->eval_method != 'both') {
            $sql1 = "create or replace view replaced_table_14d as
                 select farm_id, county_id, acre, aws, eval_yr_month, irr_sys_type, crop_category
                 from evaluation
                 where eval_yr_month >= $start_yr_month and eval_yr_month < $end_yr_month and mil_id in $labs_str and status = 'approved' and eval_type = 3 and eval_method ='" . "{$this->eval_method}" . "'";

            $sql2 = "CREATE OR REPLACE VIEW init_table_14d as
                 SELECT evaluation.irr_sys_type,replaced_table_14d.farm_id, evaluation.crop_category
                 from evaluation, replaced_table_14d
                 WHERE evaluation.eval_type =1 and evaluation.farm_id = replaced_table_14d.farm_id and evaluation.eval_method ='" . "{$this->eval_method}" . "'"
                    . "group by replaced_table_14d.farm_id";

            MIL::createView(array($sql1, $sql2));
        } else {
            $sql1 = "create or replace view replaced_table_14d as
                 select farm_id, county_id, acre, aws, eval_yr_month, irr_sys_type, crop_category
                 from evaluation
                 where eval_yr_month >= $start_yr_month and eval_yr_month < $end_yr_month and mil_id in $labs_str and status = 'approved' and eval_type = 3";

            $sql2 = "CREATE OR REPLACE VIEW init_table_14d as
                 SELECT evaluation.irr_sys_type,replaced_table_14d.farm_id, evaluation.crop_category
                 from evaluation, replaced_table_14d
                 WHERE evaluation.eval_type =1 and evaluation.farm_id = replaced_table_14d.farm_id
                 group by replaced_table_14d.farm_id";

            MIL::createView(array($sql1, $sql2));
        }
        $sql3 = "select init_table_14d.irr_sys_type, irr_sys_types.common_name, init_table_14d.crop_category, ag_urban_types_names.name
        		 from init_table_14d, irr_sys_types, ag_urban_types_names
		         where init_table_14d.irr_sys_type = irr_sys_types.id and init_table_14d.crop_category = ag_urban_types_names.id
		         group by irr_sys_types.common_name,ag_urban_types_names.name 
				 order by ag_urban_types_names.name, irr_sys_types.common_name";
        $groupByirrType_and_croptype = MIL::doQuery($sql3, MYSQL_ASSOC);
        //print_r($groupByirrType_and_croptype);

        foreach ($groupByirrType_and_croptype as $key => $array) {
            //print_r($groupByirrType_and_croptype);
            $sql5 = "select replaced_table_14d.county_id, count(distinct(replaced_table_14d.farm_id)) num_of_irr_sys,sum(acre) total_acres, format(sum(aws), 2) aws,
                 date_format(min(eval_yr_month), '%Y-%m') start_date, date_format(max(eval_yr_month), '%Y-%m') end_date
     	       	 from replaced_table_14d, init_table_14d
     	       	 where init_table_14d.irr_sys_type= $array[irr_sys_type] and init_table_14d.crop_category = $array[crop_category] 
     	       	 and init_table_14d.farm_id = replaced_table_14d.farm_id 
                 group by replaced_table_14d.county_id";

            $query_rez = MIL::doQuery($sql5, MYSQL_ASSOC);
            //print_r($query_rez);
            if (is_array($query_rez)) {  // is_array 检查变量是否是数组
                $temp = array();   // 声明一个空的数组
                foreach ($query_rez as $i => $value) {
                    $temp[$value['county_id']] = $value;
                }
                $this->results["Crop Type: {$array['name']} && Irrigation System Type:{$array['common_name']} "] = $temp;
            }
        }
        // echo "<pre>";
//         print_r($this->results);		
//         echo "</pre>";
        $drop_view_sql = "drop view if exists init_crop_category_table_14d, replaced_table_14d";
        MIL::dropView($drop_view_sql);
        return $this->results;
    }

    public function getReport() {
        $html = "
			<style>
		body{
			font-size: 120%;
			font-family: helvetica, verdana, sans-serif;			
		}
		table {
			margin: 0;
			padding: 0;
			width: 100%;
			border-collapse: collapse;
			border-color:#ccc;
			}
		table th,
		table td {
			padding: 10px 20px;
			text-align: left;
			
			}
		table th {
			border-width: 2px;
			}
		table td {
			color: #666;
			}
		table tr:last-child th,
		table tr:last-child td {
			border-bottom: none;
			}
		table tr:nth-child(even) {
			background: #eee;
			}	
		</style>";

        $html = $this->getReportTitle();
        //    $html .= $this->getTableHeader();
        $html .= $this->getTableContent();
        // echo $this->results['start_yr_month'];
        // echo $this->results['end_yr_month'];
        return $html;
    }

    public function getTableHeader() {
        $this->tableHeader = '<table border="1" cellspacing="0" cellpadding="5">
<tr>
  <th width="15%">County</th>
  <th width="9%">Number of Irrig Systems</th>
  <th width="9%">Total Replacement Evaluations Acres</th>
  <th width="9%">Total AWS (Million Gallons/Day)</th>
  <th width="9%">Replacement Evaluation Earliest Date</th>
  <th width="9%">Replacement Evaluation Latest Date</th>
  </tr>';
        return $this->tableHeader;
    }

    public function getReportTitle() {
        $start_year = $this->params['cal_start_yr'];
        $end_year = $this->params['cal_end_yr'];
        //$username = MIL_DB_PASSWORD;
        $this->reportTitle = " <h2 style='text-align:left;font-family:helvetica, arial, sans-serif;margin: 25px 10px 0 10px; padding-bottom: 0;'>Report 14d: <br />
            Ag MIL Program Actual Water Savings, by Crop categpry, Irrigation System and County<br />
        	IRRIGATION SYSTEMS THAT HAVE BEEN REPLACED<br />
        	WITH {$this->eval_method_array[$this->eval_method]} <br />
            REPLACEMENT EVALUATION DATE RANGE From: {$this->params['cal_start_yr']}-{$this->params['cal_start_month']} To: {$this->params['cal_end_yr']}-{$this->params['cal_end_month']}
        </h2>";
        return $this->reportTitle;
    }

    public function getTableContent() {

        $grand_total = array("num_of_irr_sys" => 0,
            "Replaced_Evaluation" => 0,
            "aws" => 0,
            "start_date" => 'N/A',
            "end_date" => 'N/A');

        $this->tableContent = "";
        // print_r($this->result); 
        foreach ($this->results as $key => $content) {  // 修改
            // print_r($key);
            $total = array("num_of_irr_sys" => 0,
                "total_acres" => 0,
                "aws" => 0,
                "start_date" => 'N/A',
                "end_date" => 'N/A');
            $this->tableContent .= "{$key}<br/><br/>"; // 修改
            $this->tableContent .= '<table border="1" cellspacing="0" cellpadding="5">
			<tr>
			  <th width="15%">County</th>
			  <th width="9%">Number of Irrig Systems</th>
			  <th width="9%">Total Replacement Evaluations Acres</th>
			  <th width="9%">Total AWS (Million Gallons/Day)</th>
			  <th width="9%">Replacement Evaluation Earliest Date</th>
			  <th width="9%">Replacement Evaluation Latest Date</th>
			  </tr>';
            foreach ($this->counties as $county_id => $obj) {
                if (!array_key_exists($county_id, $content)) {
                    continue;
                }
                $county = $content[$county_id];
                //comma in num cause problems
                $county['aws'] = Utility::numToFloat($county['aws']);
                //echo $county['pws'] . " / " .$county['aws'] . "<br/>";

                $county['aws'] = floatval(Utility::getMillionGallonNumPerDay($county['aws']));
                $total['num_of_irr_sys'] += $county['num_of_irr_sys'];
                $total['total_acres'] += $county['total_acres'];
                $total['aws'] += $county['aws'];
                $county['total_acres'] = round($county['total_acres'], 1);
                $county['start_date'] = $county['start_date'] == "" ? "N/A" : $county['start_date'];
                $county['end_date'] = $county['end_date'] == "" ? "N/A" : $county['end_date'];
                $total['start_date'] = Utility::compareDateAndFindEarliest($total['start_date'], $county['start_date']);
                $total['end_date'] = Utility::compareDateAndFindLatest($total['end_date'], $county['end_date']);
                $this->tableContent .= '<tr  nobr="true">' .
                        "<td>{$this->counties[$county_id]->getProperty("name")}</td>
				 <td>{$county['num_of_irr_sys']}</td>
				 <td>{$county['total_acres']}</td>
				 <td>{$county['aws']}</td>
				 <td>{$county['start_date']}</td>
				 <td>{$county['end_date']}</td>
			   </tr>";
            }
            $total['total_acres'] = round($total['total_acres'], 1);
            $total['aws'] = round($total['aws'], 3);
            $grand_total['num_of_irr_sys'] += $total['num_of_irr_sys'];
            $grand_total['Replaced_Evaluation'] += $total['total_acres'];
            $grand_total['aws'] += $total['aws'];
            $grand_total['start_date'] = Utility::compareDateAndFindEarliest($grand_total['start_date'], $total['start_date']);
            $grand_total['end_date'] = Utility::compareDateAndFindLatest($grand_total['end_date'], $total['end_date']);
            $timeString = date('m/d/Y');
            $this->tableContent .=
                    "
					   <tr nobr='true'>
					   <td>TOTALS</td>
					   <td>{$total['num_of_irr_sys']}</td>
					   <td>{$total['total_acres']}</td>
					   <td>{$total['aws']}</td>
					   <td>{$total['start_date']}</td>
					   <td>{$total['end_date']}</td>
					   </tr>
					   
					   </table><br /><p></p><p></p> ";
        }
        $grand_total['Replaced_Evaluation'] = round($grand_total['Replaced_Evaluation'], 1);
        $grand_total['aws'] = round($grand_total['aws'], 3);
        $this->tableContent .= ' <table border="1" cellspacing="0" cellpadding="5">
			<tr>
			  <th width="15%"></th>
			  <th width="9%">Number of Irrig Systems</th>
			  <th width="9%">Initial Evaluations Acres</th>
			  <th width="9%">Total AWS (Million Gallons/Day)</th>
			  <th width="9%">Replacement Evaluation Earliest Date</th>
			  <th width="9%">Replacement Evaluation Latest Date</th>
			  </tr>' . "
					   <tr nobr='true'>
					   <td>GRAND TOTALS</td>
					   <td>{$grand_total['num_of_irr_sys']}</td>
					   <td>{$grand_total['Replaced_Evaluation']}</td>
					   <td>{$grand_total['aws']}</td>
					   <td>{$grand_total['start_date']}</td>
					   <td>{$grand_total['end_date']}</td>
					   </tr>
					   
				</table>
				<div><br /><p></p><p></p> ";

        $this->tableContent .=
                "
            IMPORTANT NOTES:<br />
            Total PWS: Potential Water Savings, due to companion initial evaluations that happened prior to or during the date range<br />
            Total AWS: Actual Water Savings, which happened during the data range only<br />
			When AWS is higher than PWS, it could be due to one or more of the following reasons, among others:<br />
			- The evaluation was for seasonal crops and the seasonal crops planted on the <br />
			  year of the follow up evaluation use less water then the seasonal crops <br />
			  planted on the year of the initial evaluation<br />
			- Additional water savings due to other activities outside of improving the <br />
			  efficiency of the irrigation system (such as irrigation management/scheduling <br />
			  activities) could have been realized on the year of the follow up evaluation<br />
                         - Irrigated acres have been reduced
			<br /><br />
            Florida Department of Agriculture & Consumer Services</div><br/><br/><br/><br/><br/><br/>{$timeString}";
        return $this->tableContent;
    }

}

?>
