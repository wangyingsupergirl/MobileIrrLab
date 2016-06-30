<?php

require_once dirname(__FILE__) . '/../mil_init.php';
require_once dirname(__FILE__) . '/../utility.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/FollowIrrSys.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/InitIrrSys.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/FollowFirm.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/InitFirm.php';

Class Report6D extends Report {

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
        $this->irrSysTypeCategory = Utility::getLookupTable('irr_sys_type_category');
        $this->counties = Utility::getLookupTable("fl_county");
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
        if ($this->eval_method != 'both'){
        $sql1 = "create or replace view result_table_6d as
                 select *
                 from evaluation
                 where eval_yr_month >= $start_yr_month and eval_yr_month < $end_yr_month and mil_id in $labs_str and status = 'approved' and eval_type = 3 
                 and eval_method = '"."$this->eval_method"."' ";
    	}
    	else{
    	$sql1 = "create or replace view result_table_6d as
        		select *
        		from evaluation
        		where eval_yr_month >= $start_yr_month and eval_yr_month < $end_yr_month and mil_id in $labs_str and status = 'approved' and eval_type = 3";
    	}  
        $sql2 = "create or replace view start_date_table_6d as
                SELECT county_id, date_format(min(eval_yr_month), '%Y-%m') start_date
                FROM result_table_6d
                group by county_id;";
        $sql3 = "CREATE OR REPLACE VIEW date_table_6d AS
                SELECT result_table_6d.county_id, start_date_table_6d.start_date,date_format(max(eval_yr_month), '%Y-%m') end_date
                FROM result_table_6d INNER JOIN start_date_table_6d 
                ON result_table_6d.county_id = start_date_table_6d.county_id
                group by result_table_6d.county_id";
        $sql_array = array($sql1, $sql2, $sql3);
        MIL::createView($sql_array);
        $sql4 = "select result_table_6d.county_id, count(distinct(farm_id)) num_of_irr_sys, sum(acre) total_acres, format(sum(aws), 2) aws,
                date_table_6d.start_date,
                date_table_6d.end_date
                from result_table_6d, date_table_6d
                where result_table_6d.county_id = date_table_6d.county_id
                group by result_table_6d.county_id";
        $query_rez = MIL::doQuery($sql4, MYSQL_ASSOC);
        foreach ($query_rez as $i => $value) {
            $this->results[$value['county_id']] = $value;
        }
        $drop_view_sql = "drop view if exists result_table_6d,date_table_6d,start_date_table_6d";
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
        $html .= $this->getTableHeader();
        $html .= $this->getTableContent();
        //echo $html;
        return $html;
    }

    public function getTableHeader() {
        $this->tableHeader = '<table border="1" cellspacing="0" cellpadding="5">
            <tr>
  <th width="15%">County</th>
  <th width="9%">Number of Irrig Systems</th>
  <th width="9%">Total Acres</th>
  <th width="9%">Total AWS (Million Gallons/Day)</th>
  <th width="9%">Replacement Evaluation Earliest Date</th>
  <th width="9%">Replacement Evaluation Latest Date</th>
  </tr>';
        return $this->tableHeader;
    }

    public function getReportTitle() {
        $start_year = $this->params['cal_start_yr'];
        $end_year = $this->params['cal_end_yr'];
        $this->reportTitle = " <h2 style='text-align:left;font-family:helvetica, arial, sans-serif;margin: 25px 10px 0 10px; padding-bottom: 0;'>Report 6D: <br />
            Ag MIL Program Actual Water Saved - by County<br />
            IRRIGATION SYSTEMS REPLACED<br />
            WITH {$this->eval_method_array[$this->eval_method]} <br />
            REPLACEMENT EVALUATION DATE RANGE From: {$this->params['cal_start_yr']}-{$this->params['cal_start_month']} To: {$this->params['cal_end_yr']}-{$this->params['cal_end_month']}
        </h2>";
        return $this->reportTitle;
    }

    public function getTableContent() {
        $total = array("num_of_irr_sys" => 0,
            "total_acres" => 0,
            "aws" => 0,
            "start_date" => 'N/A',
            "end_date" => 'N/A');
        $this->tableContent = "";
        foreach ($this->counties as $county_id => $obj) {
            if (!array_key_exists($county_id, $this->results)) {
                continue;
            }
            $county = $this->results[$county_id];
            //$county['pws'] = Utility::getMillionGallonNum($county['pws']);
            //$county['pws'] = Utility::numToFloat($county['pws']);
            $county['aws'] = Utility::numToFloat($county['aws']);
            $county['aws'] = Utility::getMillionGallonNumPerDay($county['aws']);
            $total['num_of_irr_sys'] += $county['num_of_irr_sys'];
            $total['total_acres'] += $county['total_acres'];
            $total['aws'] += $county['aws'];
            $county['total_acres'] = round($county['total_acres'], 1);
            //$county['aws_percentage'] = ($county['pws'] == 0 ? 0 : round(($county['aws'] / $county['pws']) * 100));
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
        $total['aws'] = round($total['aws'], 2);
        //$total['pws'] = round($total['pws'], 2);
        $total['total_acres'] = round($total['total_acres'], 1);
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
                    

                </table><p></p><p></p>
            <div><br /><br />
            IMPORTANT NOTES:<br />
            Total AWS: Actual Water Savings obtained by replacing the old irrigation system with a new irrigation system.<br /><br />
            Florida Department of Agriculture & Consumer Services</div><br/><br/><br/><br/><br/><br/>{$timeString}";
        return $this->tableContent;
    }

}

?>
