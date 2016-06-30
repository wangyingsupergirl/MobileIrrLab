<?php

require_once dirname(__FILE__) . '/../mil_init.php';
require_once dirname(__FILE__) . '/../utility.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/FollowIrrSys.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/InitIrrSys.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/FollowFirm.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/InitFirm.php';
date_default_timezone_set('America/New_York');

Class Report6B extends Report {

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
                //if ($key == 'mil_labs')
                //    $array[$key] = explode(",", $array[$key]);
                $this->params[$key] = $array[$key];
            } else {
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
        if ($start_month <= 9) {
            $start_yr_month = $start_yr . '-0' . $start_month;
        } else {
            $start_yr_month = $start_yr . '-' . $start_month;
        }
        $start_date = "'" . date('Y-m-01', strtotime($start_yr_month)) . "'";
        if ($end_month <= 9) {
            $end_yr_month = $end_yr . '-0' . $end_month;
        } else {
            $end_yr_month = $end_yr . '-' . $end_month;
        }

        $end_date = "'" . date('Y-m-t', strtotime($end_yr_month)) . "'";
        $labs_str = "($labs_id)";
        /* Join farm_ids  before end_date has at least 2 follow up evaluations,
          on farm_ids in the selected time range at least has 1 evaluations */
//         $sql = "select 
//                         a.farm_id
//                     from
//                     /*
//                     *select farm_id which has at least 2 follow up evaluations before end_date
//                     */
//                         (select 
//                         farm_id
//                         from
//                         evaluation
//                         where
//                         status='approved'
//                         and
//                         eval_yr_month <= $end_date
//                         group by farm_id
//                     having count(case when eval_type = 2 then 1 end) >= 2) a
//                     join
//                     /*
//                     *select farm_id which has at least 1 evaluation in the selected time range
//                     */
//                         (select 
//                          farm_id
//                          from
//                          evaluation
//                     where
//                         status='approved'
//                         and eval_yr_month <= $end_date
//                         and eval_yr_month >= $start_date
//                     group by farm_id
//                     having count(case
//                         when eval_type = 2 then 1
//                     end) >= 1) b 
//                     ON a.farm_id = b.farm_id";
		if($this->eval_method == 'both'){
			$sql = "select farm_id from evaluation where status = 'approved' 
			group by farm_id having count(case when eval_type = 2 then 1 end) >=2;";}
		else{
			$sql = "select farm_id from evaluation where status = 'approved' and evaluation.eval_method = '"."$this->eval_method"."'
			group by farm_id having count(case when eval_type = 2 then 1 end) >=2;";}
        $query_rez = MIL::doQuery($sql, MYSQL_ASSOC);
        $farm_ids = "";
        foreach ($query_rez as $i => $value) {
            $farm_id = $value['farm_id'];
            $farm_ids.=",'$farm_id'";
        }

        $farm_ids = '(' . substr($farm_ids, 1) . ')';

        if ($query_rez == false) {
            return $this->results;
        };



        /*
         * query the evaluation whose farm_id is in farm_ids and eval_type=1 or eval_yr_month is 
         * in the selected timerange and has the newest eval_yr_month group by farm_id
         */

        $sql1 = "CREATE OR REPLACE VIEW result_table_6b AS
                 Select * from evaluation
                 where mil_id in $labs_str and status = 'approved' and eval_yr_month <= $end_date
                 and eval_yr_month >= $start_date and farm_id in $farm_ids order by eval_yr_month desc;"; // get the result table

        $sql2 = "create or replace view initial_eval_6b as
                 select evaluation.*
                 from result_table_6b, evaluation
                 where evaluation.eval_type = 1 and result_table_6b.farm_id = evaluation.farm_id
                 group by evaluation.farm_id;";
        $sql3 = "create or replace view start_date_6b as
                 select county_id, count(distinct(farm_id)) num_of_irr_sys, sum(acre) Initial_Evaluation,  format(sum(pws), 2) pws, date_format(min(eval_yr_month), '%Y-%m') start_date
                 from initial_eval_6b
                 group by county_id;";
        $sql4 = "create or replace view farm_max_end_date_6b as 
                 select farm_id, max(eval_yr_month) end_date
                 from result_table_6b
                 where eval_type = 2
                 group by farm_id;";
        $sql5 = "create or replace view latest_follow_up_eval_6b as
                 select county_id, sum(acre) Follow_Up_Evaluation, format(sum(aws), 2) aws, date_format(max(eval_yr_month), '%Y-%m') end_date
                 from result_table_6b, farm_max_end_date_6b
                 where result_table_6b.farm_id = farm_max_end_date_6b.farm_id and result_table_6b.eval_yr_month = farm_max_end_date_6b.end_date
                 group by county_id";

        $sql6 = "select start_date_6b.county_id, num_of_irr_sys, Initial_Evaluation, Follow_Up_Evaluation, pws, aws, start_date, end_date
                 from start_date_6b, latest_follow_up_eval_6b where start_date_6b.county_id = latest_follow_up_eval_6b.county_id;";
        $sql_array = array($sql1, $sql2, $sql3, $sql4, $sql5);

        //$this->results['farm_ids'] = $farm_ids;
        MIL::createView($sql_array);
        $query_results = MIL::doQuery($sql6, MYSQL_ASSOC);
        foreach ($query_results as $i => $value) {
            $this->results[$value['county_id']] = $value;
        }
        //$this->results=MIL::doQuery($sql, MYSQL_ASSOC);
        $drop_view_sql = "drop view if exists initial_eval_6b,start_date_6b,farm_max_end_date_6b,latest_follow_up_eval_6b,result_table_6b";
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
        //echo $this->results['farm_ids'];
        return $html;
    }

    public function getTableHeader() {
        $this->tableHeader = '<table border="1" cellspacing="0" cellpadding="5">
<tr>
  <th width="15%">County</th>
  <th width="9%">Number of Irrig Systems</th>
  <th width="9%">Initial Evaluation Acres</th>
  <th width="9%">Latest Follow Up Evaluation Acres</th>
  <th width="9%">Total PWS (Million Gallons/Day)</th>
  <th width="9%">Total AWS (Million Gallons/Day)</th>
  <th width="9%">Total AWS (% of Total PWS)</th>
  <th width="9%">Initial Evaluation Earliest Date</th>
  <th width="9%">Latest Follow Up Evaluation Date</th>
  </tr>';
        return $this->tableHeader;
    }

    public function getReportTitle() {
        $start_year = $this->params['cal_start_yr'];
        $end_year = $this->params['cal_end_yr'];
        $this->reportTitle = " <h2 style='text-align:left;font-family:helvetica, arial, sans-serif;margin: 25px 10px 0 10px; padding-bottom: 0;'>Report 6B: <br />
            Ag MIL Program Potential vs Actual Water Saved - by County<br />
            IRRIGATION SYSTEMS WITH INITIAL AND LATEST FOLLOW UP EVALUATIONS<br />
            WITH {$this->eval_method_array[$this->eval_method]} <br />
            LATEST FOLLOW UP EVALUATION DATE RANGE From: {$this->params['cal_start_yr']}-{$this->params['cal_start_month']} To: {$this->params['cal_end_yr']}-{$this->params['cal_end_month']}
        </h2>";
        return $this->reportTitle;
    }

    public function getTableContent() {
        $total = array("num_of_irr_sys" => 0,
            "Initial_Evaluation" => 0,
            "Follow_Up_Evaluation" => 0,
            "pws" => 0,
            "aws" => 0,
            "aws_percentage" => 0,
            "start_date" => 'N/A',
            "end_date" => 'N/A');
        $this->tableContent = "";
        foreach ($this->counties as $county_id => $obj) {
            if (!array_key_exists($county_id, $this->results)) {
                continue;
            }
            $county = $this->results[$county_id];
            $county['pws'] = Utility::numToFloat($county['pws']);
            $county['aws'] = Utility::numToFloat($county['aws']);
            $county['pws'] = Utility::getMillionGallonNumPerDay($county['pws']);
            $county['aws'] = Utility::getMillionGallonNumPerDay($county['aws']);
            $county['aws_percentage'] = ($county['pws'] == 0 ? 0 : round(($county['aws'] / $county['pws']) * 100));
            $total['num_of_irr_sys'] += $county['num_of_irr_sys'];
            $total['Initial_Evaluation'] += $county['Initial_Evaluation'];
            $total["Follow_Up_Evaluation"] += $county['Follow_Up_Evaluation'];
            $total['pws'] += $county['pws'];
            $total['aws'] += $county['aws'];
            $county['Initial_Evaluation'] = round($county['Initial_Evaluation'], 1);
            $county['Follow_Up_Evaluation'] = round($county['Follow_Up_Evaluation'], 1);
            $total['start_date'] = Utility::compareDateAndFindEarliest($total['start_date'], $county['start_date']);
            $total['end_date'] = Utility::compareDateAndFindLatest($total['end_date'], $county['end_date']);
            $this->tableContent .= '<tr  nobr="true">' .
                    "<td>{$this->counties[$county_id]->getProperty("name")}</td>
     <td>{$county['num_of_irr_sys']}</td>
     <td>{$county['Initial_Evaluation']}</td>
     <td>{$county['Follow_Up_Evaluation']}</td>
     <td>{$county['pws']}</td>
     <td>{$county['aws']}</td>
    <td>{$county['aws_percentage']}</td>
     <td>{$county['start_date']}</td>
     <td>{$county['end_date']}</td>
   </tr>";
        }
        $total['Initial_Evaluation'] = round($total['Initial_Evaluation'], 1);
        $total['Follow_Up_Evaluation'] = round($total['Follow_Up_Evaluation'], 1);
        $total['aws_percentage'] = ($total['pws'] == 0 ? 0 : round(($total['aws'] / $total['pws']) * 100));
        $total['aws'] = round($total['aws'], 3);
        $total['pws'] = round($total['pws'], 3);
        $timeString = date('m/d/Y');
        $this->tableContent .=
                "
            <tr nobr='true'>
                   <td>TOTALS</td>
                   <td>{$total['num_of_irr_sys']}</td>
                   <td>{$total['Initial_Evaluation']}</td>
                   <td>{$total['Follow_Up_Evaluation']}</td>
                   <td>{$total['pws']}</td>
                   <td>{$total['aws']}</td>
                   <td>{$total['aws_percentage']}</td>
                   <td>{$total['start_date']}</td>
                   <td>{$total['end_date']}</td>
                   </tr>        
            </table>
            <div><br /><p></p><p></p>
            IMPORTANT NOTES:<br />
            Total PWS: Potential Water Savings, due to companion initial evaluations that happened prior to or during the date range<br />
            Total AWS: Actual Water Savings, from latest follow up evaluation, which happened during the data range only<br />
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

/*

  require_once dirname(__FILE__) . '/../../includes/report/report.php';
  //$pieces = explode(':', $paraName);
  //$report_id = $pieces[1];
  $report = Report::createReport('6b');
  //$_POST['mil_labs'] = $_SESSION['MemberServed']->getProperty('labs_id');
  //$_POST['mil_labs']=array(1,3,5);
  //$_POST['cal_end_yr'] = (array_key_exists('cal_start_yr', $_POST) ? $_POST['cal_start_yr'] : '');
  $parameter['cal_end_yr']='2012';
  $parameter['cal_start_yr']='2012';
  $parameter['cal_start_month']='1';
  $parameter['cal_end_month']='12';

  $parameter['mil_labs'] = "";
  var_dump($parameter['mil_labs']);
  $labs = Utility::getAllLab();
  foreach($labs as $id => $lab){
  $parameter['mil_labs'] .= ",".$id;
  }
  //echo $parameter['mil_labs']."\r\n";
  $parameter['mil_labs'] = substr($parameter["mil_labs"], 1);
  echo $parameter['mil_labs']."\r\n";
  //exit;
  $report_start_time = microtime(true);
  $result = $report->requestDBData($parameter, 'approved');
  $report_end_time = microtime(true);
  $time = $report_end_time- $report_start_time;
  echo "total time :$time \r\n";
  //$kLog->logInfo("Report $report_id Request Time: $time");
  //echo $report->$time; */
?>
