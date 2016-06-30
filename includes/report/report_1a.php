<?php

require_once dirname(__FILE__) . '/../mil_init.php';
require_once dirname(__FILE__) . '/../utility.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/FollowIrrSys.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/InitIrrSys.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/FollowFirm.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/InitFirm.php';

Class Report1a extends Report {

    private $params = array(
        'mil_labs' => array()
        , 'cal_start_yr' => ''
        , 'cal_start_month' => ''
        , 'cal_end_yr' => ''
        , 'cal_end_month' => ''
        , 'eval_method' => ''
    );
    private $evals = array();
    private $waiting_list = array();
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
        //initialize $this->result
        if (array_key_exists('mil_labs', $array)) {
            $mil_labs = $array['mil_labs'];
            foreach ($mil_labs as $lab) {
                $cur = array();
                $cur['num_of_eval'] = 0;
                $cur['num_of_followup'] = 0;
                $cur['total_acres'] = 0;
                $cur['pws'] = 0;
                $cur['aws'] = 0;
                $cur['start_date'] = $this->params['cal_start_yr'] . '-' . $this->params['cal_start_month'];
                $cur['end_date'] = $this->params['cal_end_yr'] . '-' . $this->params['cal_end_month'];
                $cur['waiting_eval'] = 0;
                $cur['waiting_eval_acres'] = 0;

                $this->results[$lab] = $cur;
            }
        }
    }

    public function requestDBData($array) {
        $this->init($array);
        $this->getEvals();
        if ($this->evals == false)
            return false;
        $this->getWaitEvals();
        $this->calculateData();
        //No. Evaluation, total acre,  Start Date, End Date
        return $this->evals;
    }

    public function calculateData() {
        foreach ($this->evals as $mil_id => $evals) {
            if (!is_array($evals) || count($evals) == 0) {//no evaluation in evals
                continue;
            }
            $length = count($evals);
            $this->results[$mil_id]['num_of_eval'] = $length;
            $i = 0;
            foreach ($evals as $eval_id => $eval) {
                $this->results[$mil_id]['total_acres'] += $eval->getProperty('acre');
                $this->results[$mil_id]['aws'] += $eval->getAWS();
                $this->results[$mil_id]['pws'] += $eval->getPWS();
                if ($eval->getProperty('eval_type') == 2) {
                    $this->results[$mil_id]['num_of_followup'] += 1;
                }
                if (array_key_exists($mil_id, $this->waiting_list)) {
                    $this->results[$mil_id]['waiting_eval'] = $this->waiting_list[$mil_id]['waiting_eval'];
                    $this->results[$mil_id]['waiting_eval_acres'] = $this->waiting_list[$mil_id]['waiting_eval_acres'];
                }
                if ($i == 0)
                    $this->results[$mil_id]['start_date'] = $eval->getProperty('eval_yr') . '-' . $eval->getProperty('eval_month');
                if ($i == $length - 1)
                    $this->results[$mil_id]['end_date'] = $eval->getProperty('eval_yr') . '-' . $eval->getProperty('eval_month');
                $i++;
            }
        }
    }

    /*
     */

    public function getEvals() {
        $labs_id = $this->params['mil_labs'];
        $start_yr = $this->params['cal_start_yr'];
        $end_yr = $this->params['cal_end_yr'];
        $start_month = $this->params['cal_start_month'];
        $end_month = $this->params['cal_end_month'];
        $this->eval_method = $this->params['eval_method'];

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

        foreach ($labs_id as $mil_id) {
            if ($this->eval_method != 'both') {
                $sql = "
   select *
     from evaluation 
     where eval_yr_month >= $start_yr_month and eval_yr_month < $end_yr_month
           and mil_id  = $mil_id
           and status='approved'
           and eval_method = '" . "$this->eval_method" . "'
     order by eval_yr, eval_month";
            } else {
                $sql = "
   select *
     from evaluation 
     where eval_yr_month >= $start_yr_month and eval_yr_month < $end_yr_month
           and mil_id  = $mil_id
           and status='approved'
     order by eval_yr, eval_month";
            }
            $evals = MIL::doQuery($sql, MYSQL_ASSOC);
            if ($evals == false) {//no eval list
                $this->evals[$mil_id] = array();
            } else {
                foreach ($evals as $key => $arr) {
                    $eval = Evaluation::createEval('from_db', $arr);
                    $eval->setLastModifiedTime($arr['last_modified_time']);
                    $id = $eval->getProperty('id');
                    $this->evals[$mil_id][$id] = $eval;
                }
            }
        }
        return $this->evals;
    }

    public function getWaitEvals() {
        $labs_id = $this->params['mil_labs'];
        $start_yr = $this->params['cal_start_yr'];
        $end_yr = $this->params['cal_end_yr'];
        $start_month = $this->params['cal_start_month'];
        $end_month = $this->params['cal_end_month'];
        if ($labs_id != null && count($labs_id) > 0) {
            $labs_id_str = implode(',', $labs_id);
            if ($start_yr === $end_yr) {
                $sql = "select
        p.mil_id mil_id
        ,sum(waiting_eval.total_count) waiting_eval
        ,sum(waiting_eval.total_acres) waiting_eval_acres
       from
	(select package.id, package.mil_id
           from
            (select id, mil_id, eval_month 
             from package 
              where
               mil_id in ($labs_id_str)
               and eval_yr = $start_yr
               and eval_month >= $start_month
               and eval_month <= $end_month
               and status = 'approved') as package
             inner join 
             (select mil_id, max(eval_month) last_month 
              from package 
              where
               mil_id in ($labs_id_str)
               and eval_yr = $start_yr
               and eval_month >= $start_month
               and eval_month <= $end_month
               and status = 'approved'
              group by mil_id) as last_package
             on package.mil_id = last_package.mil_id
                   and package.eval_month = last_package.last_month) as p
        inner join waiting_eval
        on p.id = waiting_eval.package_id
        group by mil_id";
            } else {
                $sql = "select
        p.mil_id mil_id
        ,sum(waiting_eval.total_count) waiting_eval
        ,sum(waiting_eval.total_acres) waiting_eval_acres
       from
	(select package.id, package.mil_id
           from
            (select id, mil_id, eval_month 
             from package 
              where
               mil_id in ($labs_id_str)
               and (eval_yr > $start_yr
               and eval_yr < $end_yr
               or eval_yr=$start_yr
               and eval_month >= $start_month
               or eval_yr=$end_yr
               and eval_month <= $end_month)
               and status = 'approved') as package
             inner join 
             (select mil_id, max(eval_month) last_month 
              from package 
              where
               mil_id in ($labs_id_str)
               and (eval_yr > $start_yr
               and eval_yr < $end_yr
               or eval_yr=$start_yr
               and eval_month >= $start_month
               or eval_yr=$end_yr
               and eval_month <= $end_month)
               and status = 'approved'
              group by mil_id) as last_package
             on package.mil_id = last_package.mil_id
                   and package.eval_month = last_package.last_month) as p
        inner join waiting_eval
        on p.id = waiting_eval.package_id
        group by mil_id";
            }
            $waiting_list = MIL::doQuery($sql, MYSQL_ASSOC);
            if ($waiting_list == false) {
                $this->waiting_list = array();
            } else {
                foreach ($waiting_list as $key => $elem) {
                    $mil_id = $elem['mil_id'];
                    $this->waiting_list[$mil_id] = $elem;
                }
            }
        }
        return $this->waiting_list;
    }

    public function getReport() {
        $html = "
	<style>
		body{
			font-size: 120%;	
		}

		table {
			margin: 0;
			padding: 0;
			width: 100%;
			border-collapse: collapse;
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
        $html .= $this->getReportTitle();
        $html .= $this->getTableHeader();
        $html .= $this->getTableContent();
        return $html;
    }

    public function getTableHeader() {
        $this->tableHeader = '<table border="1" cellspacing="0" cellpadding="5">
  <tr  nobr="true" >
  <th  colspan="9" width="81%">&nbsp;</th>
  <th  colspan="2" width="18%">Waiting List Info - Last<br />Month of Available Period</th>
</tr>  
<tr  nobr="true" >
  <th width="9%">Ag Labs</th>
  <th width="9%">No. Evaluations</th>
  <th width="9%">No. Follow-ups</th>
  <th width="9%">Total Acres</th>
  <th width="9%">PWS(Million Gallons/Yr)</th>
  <th width="9%">AWS(Million Gallons/Yr)</th>
  <th width="9%">Available Start Date</th>
  <th width="9%">Available End Date</th>
  <th width="9%"></th>
  <th width="9%">No.</th>
  <th width="9%">Acres</th>
  </tr>';
        return $this->tableHeader;
    }

    public function getReportTitle() {
        $this->reportTitle = " <h2>
          Report 1A: MIL Water Savings, Acreages and Evaluations Summary<br />
          WITH {$this->eval_method_array[$this->eval_method]} <br />
         </h2>
         <h4>
        REQUESTED PERIOD From: {$this->params['cal_start_yr']}-{$this->params['cal_start_month']} To: {$this->params['cal_end_yr']}-{$this->params['cal_end_month']}
        </h4>";
        return $this->reportTitle;
    }

    public function getTableContent() {
        $this->tableContent = "";
        $total = array(
            'num_of_eval' => 0
            , 'total_acres' => 0
            , 'num_of_followup' => 0
            , 'pws' => 0
            , 'aws' => 0
            , 'waiting_eval' => 0
            , 'waiting_eval_acres' => 0
        );
        $labs_info = Utility::getLabsInfo($this->params['mil_labs']);
        foreach ($this->results as $mil_id => $cur) {
            $cur['pws'] = Utility::getMillionGallonNum($cur['pws']);
            $cur['aws'] = Utility::getMillionGallonNum($cur['aws']);

            $this->tableContent .= '<tr  nobr="true" >' .
                    "<td>{$labs_info[$mil_id]['mil_type']}-{$labs_info[$mil_id]['mil_name']}</td>
     <td>{$cur['num_of_eval']}</td>
     <td>{$cur['num_of_followup']}</td>
     <td>{$cur['total_acres']}</td>
     <td>{$cur['pws']}</td>
     <td>{$cur['aws']}</td>
     <td>{$cur['start_date']}</td>
     <td>{$cur['end_date']}</td>
     <td></td>
     <td>{$cur['waiting_eval']}</td>
     <td>{$cur['waiting_eval_acres']}</td>
     </tr>";
            foreach ($total as $key => $val) {
                if (array_key_exists($key, $cur)) {
                    $total[$key]+=$cur[$key];
                }
            }
        }
        $timeString = date('m/d/Y');
        $this->tableContent .= '<tr  nobr="true">' .
                "<td>Total Ag:</td>
     <td>{$total['num_of_eval']}</td>
     <td>{$total['num_of_followup']}</td>
     <td>{$total['total_acres']}</td>
     <td>{$total['pws']}</td>
     <td>{$total['aws']}</td>
     <td></td>
     <td></td>
     <td></td>
     <td>{$total['waiting_eval']}</td>
     <td>{$total['waiting_eval_acres']}</td>
     </tr>";
        $this->tableContent .=
                "</table>
             <div>IMPORTANT NOTES:<br />
            PWS: Potential Water Savings<br />
            AWS: Actual Water Savings<br /><br />
            Florida Department of Agriculture & Consumer Services</div><br/><br/><br/><br/><br/><br/>{$timeString}";
        return $this->tableContent;
    }

}

?>
