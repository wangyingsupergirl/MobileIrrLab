<?php

require_once dirname(__FILE__) . '/../mil_init.php';
require_once dirname(__FILE__) . '/../utility.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/FollowIrrSys.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/InitIrrSys.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/FollowFirm.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/InitFirm.php';

Class Report6A extends Report {

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
                // $array[$key] = explode(",", $array[$key]);
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
        $labs_str = "($labs_id)";
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


        // $this->results['start_yr_month'] = $start_yr_month;
        //$this->results['end_yr_month'] = $end_yr_month;
        /*
         * query farm_id,id in first_followup_evaluation whose eval_yr_month is in the selected range as first_followup
         * then query the evaluations whose id is in fisrt_followup or farm_id is in the first_followup and eval_type=1
         */
        if($this->eval_method != 'both'){
        	$sql0 = "create or replace view first_followup_ordered_view as
                select id, farm_id, eval_type, county_id, acre, aws, pws, eval_yr_month
                from evaluation
                WHERE eval_yr_month >= $start_yr_month AND eval_yr_month < $end_yr_month
                and eval_type = 2 and evaluation.eval_method = '"."$this->eval_method"."'
                order by farm_id, eval_type, eval_yr_month";
            }
        else{
        	 $sql0 = "create or replace view first_followup_ordered_view as
                select id, farm_id, eval_type, county_id, acre, aws, pws, eval_yr_month
                from evaluation
                WHERE eval_yr_month >= $start_yr_month AND eval_yr_month < $end_yr_month
                and eval_type = 2 
                order by farm_id, eval_type, eval_yr_month" ;}

        $sql1 = "CREATE OR REPLACE VIEW first_followup_6a AS  
                SELECT farm_id, id, eval_yr_month
                FROM first_followup_ordered_view
                group by farm_id
                having count(*) = 1;"; //find farm_id with only one first_followup_evaluation
        $sql2 = "CREATE OR REPLACE VIEW result_table_6a AS
                SELECT evaluation.* FROM evaluation INNER JOIN first_followup_6a
                ON evaluation.farm_id = first_followup_6a.farm_id
                WHERE (mil_id in $labs_str and evaluation.eval_type=1) or evaluation.id=first_followup_6a.id;"; //get the result table. calcualte the acre sum for eval_type = 1 or eval_type = 2 seperately in this table.
        $sql3 = "CREATE OR REPLACE VIEW start_date_table_6a AS
                SELECT county_id, date_format(min(eval_yr_month), '%Y-%m') start_date
                FROM result_table_6a
                where eval_type = 1
                group by county_id;"; //get the earliest start date

        $sql4 = "    
                CREATE OR REPLACE VIEW date_table_6a AS
                SELECT result_table_6a.county_id, start_date_table_6a.start_date,date_format(max(eval_yr_month), '%Y-%m') end_date
                FROM result_table_6a INNER JOIN start_date_table_6a 
                ON result_table_6a.county_id = start_date_table_6a.county_id
                where eval_type = 2
                group by result_table_6a.county_id;
                "; // get the latest end date and merge to a whole date table

        $sql5 = "create or replace view initial_eval_6a AS
                 select county_id,
                sum(acre) Initial_Evaluation,
                format(sum(pws), 2) pws
                from result_table_6a
                where eval_type = 1
                group by county_id;"; // get the initial eval view

        $sql6 = "create or replace view follow_up_eval_6a as
                    select county_id, sum(acre) Follow_Up_Evaluation, format(sum(aws), 2) aws
                    from result_table_6a
                    where eval_type = 2
                    group by county_id;"; //get the follow up eval view

        $sql7 = "select result_table_6a.county_id,
                count(distinct(farm_id)) num_of_irr_sys,
                initial_eval_6a.Initial_Evaluation,
                follow_up_eval_6a.Follow_Up_Evaluation,
                initial_eval_6a.pws,
                follow_up_eval_6a.aws,
                date_table_6a.start_date,
                date_table_6a.end_date
                from result_table_6a, initial_eval_6a, follow_up_eval_6a,date_table_6a
                where result_table_6a.county_id = initial_eval_6a.county_id and follow_up_eval_6a.county_id = initial_eval_6a.county_id
                and follow_up_eval_6a.county_id = date_table_6a.county_id
                group by county_id;"; //merge table

        $sql_array = array($sql0, $sql1, $sql2, $sql3, $sql4, $sql5, $sql6);
        MIL::createView($sql_array);
        $query_rez = MIL::doQuery($sql7, MYSQL_ASSOC);
        foreach ($query_rez as $i => $value) {
            $this->results[$value['county_id']] = $value;
        }
        //$this->results=MIL::doQuery($sql, MYSQL_ASSOC); 
        //print_r($this->results);	
        $drop_view_sql = "drop view if exists first_followup_ordered_view, first_followup_6a,result_table_6a, initial_eval_6a,"
                . "follow_up_eval_6a,start_date_table_6a,date_table_6a";
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
        // echo $this->results['start_yr_month'];
        // echo $this->results['end_yr_month'];
        return $html;
    }

    public function getTableHeader() {
        $this->tableHeader = '<table border="1" cellspacing="0" cellpadding="5">
<tr>
  <th width="15%">County</th>
  <th width="9%">Number of Irrig Systems</th>
  <th width="9%">Initial Evaluations Acres</th>
  <th width="9%">Follow Up Evaluations Acres</th>
  <th width="9%">Total PWS (Million Gallons/Day)</th>
  <th width="9%">Total AWS (Million Gallons/Day)</th>
  <th width="9%">Total AWS (% of Total PWS)</th>
  <th width="9%">Initial Evaluation Earliest Date</th>
  <th width="9%">First Follow Up Evaluation Latest Date</th>
  </tr>';
        return $this->tableHeader;
    }

    public function getReportTitle() {
        $start_year = $this->params['cal_start_yr'];
        $end_year = $this->params['cal_end_yr'];
        //$username = MIL_DB_PASSWORD;
        $this->reportTitle = " <h2 style='text-align:left;font-family:helvetica, arial, sans-serif;margin: 25px 10px 0 10px; padding-bottom: 0;'>Report 6A: <br />
            Ag MIL Program Potential vs Actual Water Saved - by County<br />
            IRRIGATION SYSTEMS WITH INITIAL AND FIRST FOLLOW UP EVALUATIONS<br />
            WITH {$this->eval_method_array[$this->eval_method]} <br />
            FIRST FOLLOW UP EVALUATION DATE RANGE From: {$this->params['cal_start_yr']}-{$this->params['cal_start_month']} To: {$this->params['cal_end_yr']}-{$this->params['cal_end_month']}
            
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
            //comma in num cause problems
            $county['pws'] = Utility::numToFloat($county['pws']);
            $county['aws'] = Utility::numToFloat($county['aws']);
            //echo $county['pws'] . " / " .$county['aws'] . "<br/>";

            $county['pws'] = floatval(Utility::getMillionGallonNumPerDay($county['pws']));
            $county['aws'] = floatval(Utility::getMillionGallonNumPerDay($county['aws']));
            $county['aws_percentage'] = ($county['pws'] == 0 ? 0 : round(($county['aws'] / $county['pws']) * 100));
            //echo $county['aws_percentage'] . "<br/>";
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
