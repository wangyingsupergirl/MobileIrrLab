<?php

require_once dirname(__FILE__) . '/../mil_init.php';
require_once dirname(__FILE__) . '/../utility.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/FollowIrrSys.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/InitIrrSys.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/FollowFirm.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/InitFirm.php';
date_default_timezone_set('America/New_York');

Class Report6C extends Report {

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
        $sql = "select 
                        a.farm_id
                    from
                    /*
                    *select farm_id which has at least 2 follow up evaluations before end_date
                    */
                        (select 
                        farm_id
                        from
                        evaluation
                        where
                        status='approved'
                        and
                        eval_yr_month <= $end_date
                        group by farm_id
                    having count(case when eval_type = 2 then 1 end) >= 2) a
                    join
                    /*
                    *select farm_id which has at least 1 evaluation in the selected time range
                    */
                        (select 
                         farm_id
                         from
                         evaluation
                    where
                        status='approved'
                        and eval_yr_month <= $end_date
                        and eval_yr_month >= $start_date
                    group by farm_id
                    having count(case
                        when eval_type = 2 then 1
                    end) >= 1) b 
                    ON a.farm_id = b.farm_id";
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
         * 
         */
        //sql statement for 6c(Latest Follow Up Evaluation)
        if ($this->eval_method != 'both') {
            $sql1 = "CREATE OR REPLACE VIEW result_table_6c AS
                 Select * from evaluation
                 where mil_id in $labs_str and status = 'approved' and eval_yr_month <= $end_date and eval_type =2
                 and eval_yr_month >= $start_date and eval_method = '" . "$this->eval_method" . "' and farm_id in $farm_ids order by eval_yr_month desc;"; // get the result table

            $sql2 = "create or replace view initial_eval_6c as
                 select evaluation.*
                 from result_table_6c, evaluation
                 where evaluation.eval_type = 1 and result_table_6c.farm_id = evaluation.farm_id and evaluation.eval_method = '" . "$this->eval_method" . "'
                 group by evaluation.farm_id;";
        } else {
            $sql1 = "CREATE OR REPLACE VIEW result_table_6c AS
                 Select * from evaluation
                 where mil_id in $labs_str and status = 'approved' and eval_yr_month <= $end_date and eval_type =2
                 and eval_yr_month >= $start_date and farm_id in $farm_ids order by eval_yr_month desc;"; // get the result table

            $sql2 = "create or replace view initial_eval_6c as
                 select evaluation.*
                 from result_table_6c, evaluation
                 where evaluation.eval_type = 1 and result_table_6c.farm_id = evaluation.farm_id
                 group by evaluation.farm_id;";
        }
        $sql3 = "create or replace view start_date_6c as
                 select county_id, count(distinct(farm_id)) num_of_irr_sys, sum(acre) Initial_Evaluation,  format(sum(pws), 2) pws, date_format(min(eval_yr_month), '%Y-%m') start_date
                 from initial_eval_6c
                 group by county_id;";
        $sql4 = "create or replace view farm_max_end_date_6c as 
                 select result_table_6c.farm_id, max(result_table_6c.eval_yr_month) end_date
                 from result_table_6c, initial_eval_6c
                 where result_table_6c.farm_id = initial_eval_6c.farm_id
                 group by farm_id;";
        $sql5 = "create or replace view latest_follow_up_eval_6c as
                 select county_id, sum(acre) Latest_Follow_Up_Evaluation, format(sum(aws), 2) aws, date_format(max(eval_yr_month), '%Y-%m') end_date
                 from result_table_6c, farm_max_end_date_6c
                 where result_table_6c.farm_id = farm_max_end_date_6c.farm_id and result_table_6c.eval_yr_month = farm_max_end_date_6c.end_date
                 group by county_id";

        //sql statement for first follow up evaluation
        if ($this->eval_method != 'both') {
            $sql6 = "create or replace view first_followup_ordered_view as
                select evaluation.farm_id, min(evaluation.eval_yr_month) eval_yr_month
                from evaluation, initial_eval_6c
                where evaluation.eval_type = 2 and evaluation.farm_id = initial_eval_6c.farm_id and evaluation.eval_method = '" . "$this->eval_method" . "'
                group by farm_id";   //farm id with more than one follow up evaluation

            $sql7 = "CREATE OR REPLACE VIEW first_followup_result_table_6c as
                 select county_id, sum(acre) First_Follow_Up_Evaluation, format(sum(aws), 2) aws, date_format(max(evaluation.eval_yr_month), '%Y-%m') end_date
                 from evaluation, first_followup_ordered_view
                 where evaluation.farm_id = first_followup_ordered_view.farm_id and evaluation.eval_yr_month = first_followup_ordered_view.eval_yr_month and evaluation.eval_type = 2
                 and evaluation.eval_method = '" . "$this->eval_method" . "'
                 group by county_id;";
        } else {
            $sql6 = "create or replace view first_followup_ordered_view as
                select evaluation.farm_id, min(evaluation.eval_yr_month) eval_yr_month
                from evaluation, initial_eval_6c
                where evaluation.eval_type = 2 and evaluation.farm_id = initial_eval_6c.farm_id
                group by farm_id";   //farm id with more than one follow up evaluation

            $sql7 = "CREATE OR REPLACE VIEW first_followup_result_table_6c as
                 select county_id, sum(acre) First_Follow_Up_Evaluation, format(sum(aws), 2) aws, date_format(max(evaluation.eval_yr_month), '%Y-%m') end_date
                 from evaluation, first_followup_ordered_view
                 where evaluation.farm_id = first_followup_ordered_view.farm_id and evaluation.eval_yr_month = first_followup_ordered_view.eval_yr_month and evaluation.eval_type = 2
                 group by county_id;";
        }
        $sql8 = "select start_date_6c.county_id, num_of_irr_sys, Initial_Evaluation, First_Follow_Up_Evaluation,
                 Latest_Follow_Up_Evaluation, pws, latest_follow_up_eval_6c.aws as latest_aws,
                 first_followup_result_table_6c.aws as first_aws,
                 start_date, latest_follow_up_eval_6c.end_date as latest_end_date, 
                 first_followup_result_table_6c.end_date as first_end_date
                 from ((start_date_6c inner join latest_follow_up_eval_6c on start_date_6c.county_id = latest_follow_up_eval_6c.county_id) left join first_followup_result_table_6c
                 on start_date_6c.county_id = first_followup_result_table_6c.county_id);";

        $sql_array_6c = array($sql1, $sql2, $sql3, $sql4, $sql5,$sql6, $sql7);
        //$this->results['farm_ids'] = $farm_ids;
        MIL::createView($sql_array_6c);
        //MIL::createView($sql_array_6c);
        $query_results = MIL::doQuery($sql8, MYSQL_ASSOC);
        foreach ($query_results as $i => $value) {
            $this->results[$value['county_id']] = $value;
        }
        $drop_view_sql = "drop view if exists result_table_6c,initial_eval_6c,start_date_6c,"
                . "farm_max_end_date_6c,latest_follow_up_eval_6c,first_followup_result_table_6c,first_followup_ordered_view";
        MIL::dropView($drop_view_sql);
        //$this->results=MIL::doQuery($sql, MYSQL_ASSOC);       
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
<thead><tr>
  <th rowspan="2">County</th>
  <th rowspan="2">Number of Irrig Systems</th>
  <th rowspan="2">Initial Evaluation Acres</th>
  <th rowspan="2">First Follow Up Evaluation Acres</th>
  <th rowspan="2">Latest Follow Up Evaluation Acres</th>
  <th rowspan="2">Total PWS (Million Gallons/Day)</th>
  <th colspan="2">First Follow Up Evaluation</th>
  <th colspan="2">Latest Follow Up Evaluation</th>
  <th rowspan="2">Initial Evaluation Earliest Date</th>
  <th rowspan="2">First Follow Up Evaluation Latest Date</th>
  <th rowspan="2">Latest Follow Up Evaluation Latest Date</th>
  </tr>
  <tr>
  <th>Total AWS (Million Gallons/Day)</th>
  <th>Total AWS (% of Total PWS)</th>
  <th>Total AWS (Million Gallons/Day)</th>
  <th>Total AWS (% of Total PWS)</th>
   </tr>
  </thead>';
        return $this->tableHeader;
    }

    public function getReportTitle() {
        $start_year = $this->params['cal_start_yr'];
        $end_year = $this->params['cal_end_yr'];
        $this->reportTitle = " <h2 style='text-align:left;font-family:helvetica, arial, sans-serif;margin: 25px 10px 0 10px; padding-bottom: 0;'>Report 6C: <br />
            Ag MIL Program Potential vs Actual Water Saved - by County<br />
            IRRIGATION SYSTEMS WITH INITIAL EVALUATION, AND FIRST AND LATEST FOLLOW UP EVALUATIONS<br />
            WITH {$this->eval_method_array[$this->eval_method]} <br />
            LATEST FOLLOW UP EVALUATION DATE RANGE From: {$this->params['cal_start_yr']}-{$this->params['cal_start_month']} To: {$this->params['cal_end_yr']}-{$this->params['cal_end_month']}
        </h2>";
        return $this->reportTitle;
    }

    public function getTableContent() {
        $total = array("num_of_irr_sys" => 0,
            "Initial_Evaluation" => 0,
            "First_Follow_Up_Evaluation" => 0,
            "Latest_Follow_Up_Evaluation" => 0,
            "pws" => 0,
            "latest_aws" => 0,
            "first_aws" => 0,
            "aws_percentage" => 0,
            "start_date" => 'N/A',
            "latest_end_date" => 'N/A',
            "first_end_date" => 'N/A');
        $this->tableContent = "";
        foreach ($this->counties as $county_id => $obj) {
            if (!array_key_exists($county_id, $this->results)) {
                continue;
            }
            $county = $this->results[$county_id];
            $county['pws'] = Utility::numToFloat($county['pws']);
            $county['latest_aws'] = Utility::numToFloat($county['latest_aws']);
            $county['first_aws'] = Utility::numToFloat($county['first_aws']);
            $county['pws'] = Utility::getMillionGallonNumPerDay($county['pws']);
            $county['latest_aws'] = Utility::getMillionGallonNumPerDay($county['latest_aws']);
            $county['first_aws'] = Utility::getMillionGallonNumPerDay($county['first_aws']);
            $county['latest_aws_percentage'] = ($county['pws'] == 0 ? 0 : round(($county['latest_aws'] / $county['pws']) * 100));
            $county['first_aws_percentage'] = ($county['pws'] == 0 ? 0 : round(($county['first_aws'] / $county['pws']) * 100));
            $total['num_of_irr_sys'] += $county['num_of_irr_sys'];
            $total['Initial_Evaluation'] += $county['Initial_Evaluation'];
            $total["First_Follow_Up_Evaluation"] += $county['First_Follow_Up_Evaluation'];
            $total["Latest_Follow_Up_Evaluation"] += $county['Latest_Follow_Up_Evaluation'];
            $total['pws'] += $county['pws'];
            $total['latest_aws'] += $county['latest_aws'];
            $total['first_aws'] += $county['first_aws'];
            $county['Initial_Evaluation'] = round($county['Initial_Evaluation'], 1);
            $county['Latest_Follow_Up_Evaluation'] = round($county['Latest_Follow_Up_Evaluation'], 1);
            $total['start_date'] = Utility::compareDateAndFindEarliest($total['start_date'], $county['start_date']);
            $total['first_end_date'] = Utility::compareDateAndFindLatest($total['first_end_date'], $county['first_end_date']);
            $total['latest_end_date'] = Utility::compareDateAndFindLatest($total['latest_end_date'], $county['latest_end_date']); // $county[first_end_date] 到底在哪啊？？
            $county_name = $this->counties[$county_id]->getProperty("name");
            if ($county_name == 'MIAMI-DADE') {

                $county_name = 'MIAMI DADE';
            }
            $this->tableContent .= '<tr  nobr="true">' .
                    "<td>{$county_name}</td>
     <td>{$county['num_of_irr_sys']}</td>
     <td>{$county['Initial_Evaluation']}</td>
      <td>{$county['First_Follow_Up_Evaluation']}</td>
     <td>{$county['Latest_Follow_Up_Evaluation']}</td>
     <td>{$county['pws']}</td>
     <td>{$county['first_aws']}</td>
    <td>{$county['first_aws_percentage']}</td>
    <td>{$county['latest_aws']}</td>
    <td>{$county['latest_aws_percentage']}</td>
     <td>{$county['start_date']}</td>
      <td>{$county['first_end_date']}</td>
     <td>{$county['latest_end_date']}</td>
   </tr>";
        }
        $total['Initial_Evaluation'] = round($total['Initial_Evaluation'], 3);
        $total['First_Follow_Up_Evaluation'] = round($total['First_Follow_Up_Evaluation'], 3);
        $total['Latest_Follow_Up_Evaluation'] = round($total['Latest_Follow_Up_Evaluation'], 3);
        $total['latest_aws_percentage'] = ($total['pws'] == 0 ? 0 : round(($total['latest_aws'] / $total['pws']) * 100));
        $total['first_aws_percentage'] = ($total['pws'] == 0 ? 0 : round(($total['first_aws'] / $total['pws']) * 100));
        $total['latest_aws'] = round($total['latest_aws'], 3);
        $total['first_aws'] = round($total['first_aws'], 3);
        $total['pws'] = round($total['pws'], 3);
        $timeString = date('m/d/Y');
        $this->tableContent .=
                "
            <tr nobr='true'>
                   <td>TOTALS</td>
                   <td>{$total['num_of_irr_sys']}</td>
                   <td>{$total['Initial_Evaluation']}</td>
                   <td>{$total['First_Follow_Up_Evaluation']}</td>
                   <td>{$total['Latest_Follow_Up_Evaluation']}</td>
                   <td>{$total['pws']}</td>
                   <td>{$total['first_aws']}</td>
                   <td>{$total['first_aws_percentage']}</td>
                   <td>{$total['latest_aws']}</td>
                   <td>{$total['latest_aws_percentage']}</td>
                   <td>{$total['start_date']}</td>
                   <td>{$total['first_end_date']}</td>
                   <td>{$total['latest_end_date']}</td>
                   </tr>        
            </table>
            <div><br /><p></p><p></p>
            IMPORTANT NOTES:<br />
            Total PWS: Potential Water Saving, due to companion initial evaluations that happened prior to or during the oldest follow up evaluation date range<br />
            Total AWS: Actual Water Saving, from follow up evaluation, which happened during the oldest follow up evaluation data range only; and from first follow up evaluations which happened during and/or prior to the latest follow up evaluation date range.<br />
			When AWS is higher than PWS, it could be due to one or more of the following reasons, among others:<br />
			- The evaluation was for seasonal crops and the seasonal crops planted on the <br />
			  year of the follow up evaluation use less water than the seasonal crops <br />
			  planted on the year of the initial evaluation<br />
			- Additional water savings due to other activities outside of improving the <br />
			  efficiency of the irrigation system (such as irrigation management/scheduling <br />
			  activities) could have been realized on the year of the follow up evaluation<br />
                        - Irrigated acres have been reduced
			<br /><br />
            Florida Department of Agriculture & Consumer Services<br />
            Office of Agriculture Water Policy</div><br/><br/><br/><br/><br/><br/>{$timeString}";
        return $this->tableContent;
    }

}

/*

  require_once dirname(__FILE__) . '/../../includes/report/report.php';
  //$pieces = explode(':', $paraName);
  //$report_id = $pieces[1];
  $report = Report::createReport('6c');
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
