<?php

require_once dirname(__FILE__) . '/../mil_init.php';
require_once dirname(__FILE__) . '/../utility.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/FollowIrrSys.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/InitIrrSys.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/FollowFirm.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/InitFirm.php';
date_default_timezone_set('America/New_York');

Class Report14B extends Report {

    private $params = array(
        'mil_labs' => array()
        , 'cal_start_yr' => ''
        , 'cal_start_month' => ''
        , 'cal_end_yr' => ''
        , 'cal_end_month' => ''
        ,'eval_method' => ''
    );
    private $results = array();
    private $reportTitle;
    private $tableHeader;
    private $tableContent;
    private $IrriTypes = array();

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
        //$this->irrSysTypeCategory = Utility::getLookupTable('irr_sys_type_category');
        $this->irrSysTypes= Utility::getLookupTable('irr_sys_types');
        $this->counties = Utility::getLookupTable("fl_county");

        $sql = "select id, common_name from irr_sys_types";
        $query_rez = MIL::doQuery($sql, MYSQL_ASSOC);
        foreach ($query_rez as $i => $value) {
            $this->IrriTypes[$i] = $value;
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
       /* $sql = "select 
                        a.farm_id
                    from
                    /*
                    *select farm_id which has at least 2 follow up evaluations before end_date
                    **
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
                    **
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
                    ON a.farm_id = b.farm_id";*/
        $sql = "select farm_id from evaluation where status = 'approved' group by farm_id having count(case when eval_type = 2 then 1 end) = 0;";
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

        /*   $sql1 = "CREATE OR REPLACE VIEW result_table_12b AS
          Select * from evaluation
          where mil_id in $labs_str and status = 'approved' and eval_yr_month <= $end_date
          and eval_yr_month >= $start_date and farm_id in $farm_ids order by eval_yr_month desc;"; // get the result table

          $sql2 = "create or replace view initial_eval_12b as
          select evaluation.*
          from result_table_12b, evaluation
          where evaluation.eval_type = 1 and result_table_12b.farm_id = evaluation.farm_id
          group by evaluation.farm_id;"; */
        if($this->eval_method == 'both'){
        
        	$sql1 = "create or replace view initial_eval_14b as
                 select * from evaluation where farm_id in $farm_ids and 
                 mil_id in $labs_str and status = 'approved' and evaluation.eval_type = 1 and eval_yr_month <= $end_date and 
                eval_yr_month >= $start_date;";
            }
        else{
        	$sql1 = "create or replace view initial_eval_14b as
                 select * from evaluation where farm_id in $farm_ids and 
                 mil_id in $labs_str and status = 'approved' and evaluation.eval_type = 1 and eval_yr_month <= $end_date and 
                eval_yr_month >= $start_date and evaluation.eval_method = '"."$this->eval_method"."';";
            }
        	

        $sql_array = array($sql1);
        MIL::createView($sql_array);
       
        $sql2 = "select irr_sys_types.common_name,ag_urban_types_names.name from initial_eval_14b, irr_sys_types,
          	ag_urban_types_names where initial_eval_14b.irr_sys_type = irr_sys_types.id and 
          	initial_eval_14b.crop_category = ag_urban_types_names.id
          	group by irr_sys_types.common_name,ag_urban_types_names.name
		  	order by ag_urban_types_names.name , irr_sys_types.common_name";
		 
		//echo $sql2;

        $groupByCrop = MIL::doQuery($sql2, MYSQL_ASSOC);
        //print_r($groupByCrop);
        //$groupByCrop = array(array('crop_category' => '30'));
        foreach ($groupByCrop as $key => $array) {

            $sql3 = "CREATE OR REPLACE VIEW initial_eval_14b_by_irr_crop AS
                    SELECT initial_eval_14b.*,irr_sys_types.common_name from 
                    initial_eval_14b,irr_sys_types,ag_urban_types_names
                    WHERE initial_eval_14b.irr_sys_type = irr_sys_types.id and 
                    initial_eval_14b.crop_category = ag_urban_types_names.id and 
                    irr_sys_types.common_name = '$array[common_name]' and ag_urban_types_names.name = '$array[name]';";
            $sql_array = array($sql3);
            MIL::createView($sql_array);

            /* $sql3 = "create or replace view start_date_table_12b as
              select county_id, count(distinct(farm_id)) num_of_irr_sys, sum(acre) Initial_Evaluation,  format(sum(pws), 2) pws, date_format(min(eval_yr_month), '%Y-%m') start_date
              from initial_eval_12b_by_crop
              group by county_id;";
              $sql4 = "create or replace view farm_max_end_date_12b as
              select result_table_12b.farm_id, max(result_table_12b.eval_yr_month) end_date
              from result_table_12b, initial_eval_12b
              where result_table_12b.eval_type = 2 and initial_eval_12b.farm_id = result_table_12b.farm_id
              group by result_table_12b.farm_id;";

              $sql5 = "create or replace view latest_follow_up_eval_12b as
              select county_id, sum(acre) Follow_Up_Evaluation, format(sum(aws), 2) aws, date_format(max(eval_yr_month), '%Y-%m') end_date
              from result_table_12b, farm_max_end_date_12b
              where result_table_12b.farm_id = farm_max_end_date_12b.farm_id and result_table_12b.eval_yr_month = farm_max_end_date_12b.end_date
              group by county_id";

              $sql6 = "select start_date_table_12b.county_id, num_of_irr_sys, Initial_Evaluation, Follow_Up_Evaluation, pws, aws, start_date, end_date
              from start_date_table_12b, latest_follow_up_eval_12b where start_date_table_12b.county_id = latest_follow_up_eval_12b.county_id;"; */

            $sql4 = "select county_id, count(farm_id) num_of_irr_sys, sum(acre) Initial_Evaluation,  
                                    format(sum(pws), 2) pws, date_format(min(eval_yr_month), '%Y-%m') start_date, 
				 date_format(max(eval_yr_month), '%Y-%m') end_date
                                    from initial_eval_14b_by_irr_crop
                                    group by county_id;";

            //	$sql_array = array($sql3, $sql4, $sql5);         
            //$this->results['farm_ids'] = $farm_ids;
            //MIL::createView($sql_array);
            $query_results = MIL::doQuery($sql4, MYSQL_ASSOC);
            if (is_array($query_results)) {
                $temp = array();
                foreach ($query_results as $i => $value) {
                    $temp[$value['county_id']] = $value;
                }
                $this->results["Crop Type : {$array['name']} && Irrigation System Type: {$array['common_name']}"] = $temp;
            }
        }
        //$this->results=MIL::doQuery($sql, MYSQL_ASSOC);
        $drop_view_sql = "drop view if exists initial_eval_14b,initial_eval_14b_by_irr_crop";
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
        // $html .= $this->getTableHeader();
        $html .= $this->getTableContent();
        //echo $this->results['farm_ids'];
        return $html;
    }

    public function getTableHeader() {
        $this->tableHeader = '<table border="1" cellspacing="0" cellpadding="5">
<tr>
  <th width="15%">County</th>
  <th width="9%">Number of Irrig Systems</th>
  <th width="9%">Total Acres</th>
  <th width="9%">Total PWS (Million Gallons/Day)</th>
  <th width="9%">Initial Evaluation Earliest Date</th>
  <th width="9%">Initial Evaluation Latest Date</th>
  </tr>';
        return $this->tableHeader;
    }

    public function getReportTitle() {
        $start_year = $this->params['cal_start_yr'];
        $end_year = $this->params['cal_end_yr'];
        $this->reportTitle = " <h2 style='text-align:left;font-family:helvetica, arial, sans-serif;margin: 25px 10px 0 10px; padding-bottom: 0;'>Report 14B: <br />
            Ag MIL Program Potential Water Savings - by Crop and Irrigation System Type at Initial Evaluation and County<br />
            WITH {$this->eval_method_array[$this->eval_method]} <br />
           
            INITIAL EVALUATION DATE RANGE From: {$this->params['cal_start_yr']}-{$this->params['cal_start_month']} To: {$this->params['cal_end_yr']}-{$this->params['cal_end_month']}
        </h2>";
        return $this->reportTitle;
    }

    public function getTableContent() {

        $this->tableContent = "";
        $grand_total = array("num_of_irr_sys" => 0,
            "Initial_Evaluation" => 0,
            "pws" => 0,
            "start_date" => 'N/A',
            "end_date" => 'N/A');
        foreach ($this->results as $key => $content) {
            /*foreach ($this->IrriTypes as $k => $names) {
                if ($names['id'] == $irri) {
                    $name = $names['common_name'];
                    break;
                }
            }*/
            $total = array("num_of_irr_sys" => 0,
                "Initial_Evaluation" => 0,
                "pws" => 0,
                "start_date" => 'N/A',
                "end_date" => 'N/A');
            $this->tableContent .= "{$key}<br/><br/>";
            $this->tableContent .= '<table border="1" cellspacing="0" cellpadding="5">
			<tr>
			  <th width="15%">County</th>
              <th width="9%">Number of Irrig Systems</th>
              <th width="9%">Total Acres</th>
              <th width="9%">Total PWS (Million Gallons/Day)</th>
              <th width="9%">Initial Evaluation Earliest Date</th>
              <th width="9%">Initial Evaluation Latest Date</th>
			  </tr>';
            foreach ($this->counties as $county_id => $obj) {
                if (!array_key_exists($county_id, $content)) {
                    continue;
                }
                $county = $content[$county_id];
                $county['pws'] = Utility::numToFloat($county['pws']);
                $county['pws'] = Utility::getMillionGallonNumPerDay($county['pws']);
                $total['num_of_irr_sys'] += $county['num_of_irr_sys'];
                $total['Initial_Evaluation'] += $county['Initial_Evaluation'];
                $total['pws'] += $county['pws'];
                $county['Initial_Evaluation'] = round($county['Initial_Evaluation'], 1);
                $total['start_date'] = Utility::compareDateAndFindEarliest($total['start_date'], $county['start_date']);
                $total['end_date'] = Utility::compareDateAndFindLatest($total['end_date'], $county['end_date']);
                $this->tableContent .= '<tr  nobr="true">' .
                        "<td>{$this->counties[$county_id]->getProperty("name")}</td>
				 <td>{$county['num_of_irr_sys']}</td>
				 <td>{$county['Initial_Evaluation']}</td>
				 <td>{$county['pws']}</td>
				 <td>{$county['start_date']}</td>
				 <td>{$county['end_date']}</td>
			   </tr>";
            }
            $total['Initial_Evaluation'] = round($total['Initial_Evaluation'], 1);
            $total['pws'] = round($total['pws'], 3);
            $grand_total['num_of_irr_sys'] += $total['num_of_irr_sys'];
            $grand_total['Initial_Evaluation'] += $total['Initial_Evaluation'];
            $grand_total['pws'] += $total['pws'];
            $grand_total['start_date'] = Utility::compareDateAndFindEarliest($grand_total['start_date'], $total['start_date']);
            $grand_total['end_date'] = Utility::compareDateAndFindLatest($grand_total['end_date'], $total['end_date']);
            $timeString = date('m/d/Y');
            $this->tableContent .=
                    "
				<tr nobr='true'>
					   <td>TOTALS</td>
					   <td>{$total['num_of_irr_sys']}</td>
					   <td>{$total['Initial_Evaluation']}</td>
					   <td>{$total['pws']}</td>
					   <td>{$total['start_date']}</td>
					   <td>{$total['end_date']}</td>
					   </tr>        
				</table>
				<br /><p></p><p></p>";
        }
        $grand_total['Initial_Evaluation'] = round($grand_total['Initial_Evaluation'], 1);
        $grand_total['pws'] = round($grand_total['pws'], 3);
        $this->tableContent .= ' <table border="1" cellspacing="0" cellpadding="5">
			<tr>
			  <th width="15%"></th>
			  <th width="9%">Number of Irrig Systems</th>
			  <th width="9%">Total Acres</th>
			  <th width="9%">Total PWS (Million Gallons/Day)</th>
			  <th width="9%">Initial Evaluation Earliest Date</th>
			  <th width="9%">Initial Latest Date</th>
			  </tr>' . "
					   <tr nobr='true'>
					   <td>GRAND TOTALS</td>
					   <td>{$grand_total['num_of_irr_sys']}</td>
					   <td>{$grand_total['Initial_Evaluation']}</td>
					   <td>{$grand_total['pws']}</td>
					   <td>{$grand_total['start_date']}</td>
					   <td>{$grand_total['end_date']}</td>
					   </tr>
				</table>
				<div><br /><p></p><p></p> ";
        $this->tableContent .=
                "
		IMPORTANT NOTES:<br />
		Total PWS: Potential Water Savings, due to companion initial evaluations that happened prior to or during the date range<br />
		<br /><br />
		Florida Department of Agriculture & Consumer Services<br/>
		Office of Agricultural Water Policy</div><br/><br/><br/><br/><br/><br/>{$timeString}";
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
