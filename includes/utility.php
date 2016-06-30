<?php

require_once dirname(__FILE__) . '/mil_init.php';
require_once dirname(__FILE__) . '/constant.php';
require_once dirname(__FILE__) . '/input/lookuptable/CountyLab.php';
require_once dirname(__FILE__) . '/input/lookuptable/LookUpTable.php';
require_once dirname(__FILE__) . '/input/package/Package.php';
require_once dirname(__FILE__) . '/input/package/Contract.php';
require_once dirname(__FILE__) . '/input/lookuptable/Lab.php';
require_once dirname(__FILE__) . '/input/lookuptable/ContractorName.php';
require_once dirname(__FILE__) . '/input/lookuptable/PartnerName.php';
require_once dirname(__FILE__) . '/input/lookuptable/ReportName.php';
require_once dirname(__FILE__) . '/input/member/MemberPackage.php';

class Utility {

    public static function getMonthName($index) {
        $months = array('0', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
        if ($index <= 12 && $index >= 1)
            return $months[$index];
        else
            return false;
    }
    public static function addNewLabToAdmin($lab_id){
         $sql = "select labs_id from member where role = ". ADMIN_ROLE;
         $result = MIL::doQuery($sql, MYSQL_ASSOC);
         $lab_ids = "";
         if(count($result) > 0){
             $lab_ids .= $result[0]['labs_id'];
         }else{
             //fail siliently return;
         }
         $lab_ids .=",$lab_id";
         $sql = "update member set labs_id = '$lab_ids' where role = ". ADMIN_ROLE;
         $result = MIL::doQuery($sql,MIL_DB_INSERT);
         return true;
    }
    public static function getNamesByIDs($ids, $table_name) {
        $table = Utility::getLookupTable($table_name, null);
        $arr = explode(",", $ids);
        $n = count($arr);
        for ($i = 0; $i < $n; $i++) {
            $id = $arr[$i];
            if (array_key_exists($id, $table)) {
                $name = $table[$id]->getProperty('name');
                $arr[$i] = $name;
            }
        }
        $names = implode(',', $arr);
        return $names;
    }

    public static function getMILLabsByCycle($cycle) {
        if ($cycle != 1 && $cycle != 3) {
            throw new Exception('Valid cycle should be one month or three months.');
        }
        $sql = "select mil_id from mil_lab where billing_cycle= $cycle";
        $result = MIL::doQuery($sql, MYSQL_ASSOC);
        $mil_labs = array();
        $mil_labs_str = false;
        foreach ($result as $lab) {
            if ($mil_labs_str == false) {
                $mil_labs_str = '';
            }
            array_push($mil_labs, $lab['mil_id']);
            $mil_labs_str .= ',' . $lab['mil_id'];
        }
        $mil_labs_str = substr($mil_labs_str, 1);
        return $mil_labs_str;
    }

    public static function getNumEvalsInPackages() {
        $sql = "select package_id id,  count(*) c from evaluation group by package_id;";
        $numEvals = MIL::doQuery($sql, MYSQL_ASSOC);
        $rtns = array();
        if ($numEvals != false) {
            foreach ($numEvals as $i => $arr) {
                $rtns[$arr['id']] = $arr['c'];
            }
        }
        return $rtns;
    }

    public static function getAllPackage($member) {
        global $kLog;
        $time_start = microtime(true);
        //$log->logInfo('Profile: Enter get all package at '.$time_start);
        $rtn = false;
        if ($member->getProperty('role') == ADMIN_ROLE) {
            $sql = "select * 
                        from package 
                        order by mil_id 
                        , pack_created_time DESC";
        } else {
            $str = $member->getProperty('labs_id');
            if ($str == '')
                return array();
            $sql = "select * 
            from package 
            where mil_id in ($str) 
            order by mil_id 
            , pack_created_time DESC;";
            //admin all the package
        }
        $packages = MIL::doQuery($sql, MYSQL_ASSOC);
        $packages_arr = array();
        $countEvals = Utility::getNumEvalsInPackages();
        if ($packages != false) {
            foreach ($packages as $key => $arr) {
                $arr['num_of_eval'] = (array_key_exists($arr['id'], $countEvals) ? $countEvals[$arr['id']] : 0);
                $package = new Package($arr);
                $package->retrievePackage($arr);
                $pid = $package->getProperty('id');
                $packages_arr[$pid] = $package;
            }
            $rtn = $packages_arr;
        }
        $time_end = microtime(true);
        $time = $time_end - $time_start;
        $kLog->logInfo('Get Packages Exec Time: ' . $time);
        return $rtn;
    }

    ////display all the lab(sign up) or based on member profile
    public static function getAllLab($member = null) {
        //If admin add mil lab to db, system doesn't update admins labs record
        //to make sure admin can view the update, role = admin can view all the lab.
        if ($member == null || $member->getProperty('role') == ADMIN_ROLE) {
            $sql = "select * from mil_lab";
        } else {
            $str = $member->getProperty('labs_id');
            if ($str == '')
                return array();
            $sql = "select * from mil_lab where mil_id in ($str)";
        }
        $labs = MIL::doQuery($sql, MYSQL_ASSOC);
        $labs_arr = array();
        if ($labs != false) {
            foreach ($labs as $key => $arr) {
                $lab = new Lab($arr);
                $id = $lab->getProperty('mil_id');
                $labs_arr[$id] = $lab;
            }
            return $labs_arr;
        } else {
            return false;
        }
    }
    public static function getAllMethod($member = null) {
        if ($member != null || $member->getProperty('role') == ADMIN_ROLE) {  
            $sql = "select distinct(eval_method) from evaluation";
            $methods = MIL::doQuery($sql, MYSQL_ASSOC);
            return $methods;
        }
        // default array
        return array(array('eval_method' => 'irr'), array('eval_method' => 'firm'));
    }

    public static function getAllContractor($member = null) {
        if ($member && $member->getProperty('role') != ADMIN_ROLE) { 
            $str = $member->getProperty('labs_id');
            $sql = "select c.* 
                        from contractor as c
                         inner join
                         (select contractor_id from mil_lab where mil_id in ($str)) as mil_lab
                         on c.id = mil_lab.contractor_id
                         order by c.id";
        } else {
            $sql = 'select * from contractor order by id';
        }
        $contractors = MIL::doQuery($sql, MYSQL_ASSOC);
        $contractors_arr = array();
        if ($contractors != false) {
            foreach ($contractors as $key => $arr) {
                $contractor = new ContractorName($arr);
                $id = $contractor->getProperty('id');
                $contractors_arr[$id] = $contractor;
            }
            return $contractors_arr;
        } else {
            return false;
        }
    }

    public static function getAllPartner($member = null) {
        if ($member && $member->getProperty('role') != ADMIN_ROLE) {
            $str = $member->getProperty('labs_id');
            //to be changed
            $sql .= "select c.* 
                        from contractor as c
                         inner join
                         (select contractor_id from mil_lab where mil_id in ($str)) as mil_lab
                         on c.id = mil_lab.contractor_id
                         order by c.id";
        } else {
            $sql = 'select * from partner order by id';
        }
        $partners = MIL::doQuery($sql, MYSQL_ASSOC);
        $partners_arr = array();
        if ($partners != false) {
            foreach ($partners as $key => $arr) {
                $partner = new PartnerName($arr);
                $id = $partner->getProperty('id');
                $partners_arr[$id] = $partner;
            }
            return $partners_arr;
        } else {
            return false;
        }
    }

    public static function getAllContract($member) {
        if ($member->getProperty('role') == ADMIN_ROLE) {
            //admin always can see all the contracts of all the mils
            //code here need to be refactored
            $sql = "select * 
                        from contract";
        } else {
            $str = $member->getProperty('labs_id');
            $sql = "select * from contract where mil_id in ($str)";
        }
        $contracts = MIL::doQuery($sql, MYSQL_ASSOC);
        $contracts_arr = array();
        if ($contracts != false) {
            foreach ($contracts as $key => $arr) {
                $contract = new Contract($arr);
                $id = $contract->getProperty('id');
                $contracts_arr[$id] = $contract;
            }
            return $contracts_arr;
        } else {
            return false;
        }
    }

    public static function getAllMember() {
        $sql = 'select * from member';
        $members = MIL::doQuery($sql, MYSQL_ASSOC);
        $members_arr = array();
        if ($members != false) {
            foreach ($members as $key => $arr) {
                $member = Member::createMemberFromDB($arr);
                $member->init($arr);
                $id = $member->getProperty('mem_id');
                $members_arr[$id] = $member;
            }
            return $members_arr;
        } else {
            return false;
        }
    }

    public static function getLookupTable($table_name, $constrain=null) {//get Evaluation Type Water Saving List
        /* if (array_key_exists($table_name, $_SESSION)) {
          $list = $_SESSION[$table_name];
          } else { */
        $list = array();
        if ($table_name == 'fl_county') {
            if ($constrain != null) {
                $sql = " select c.id id, c.name name from fl_county as c join lab_county l where l.county_id = c.id and mil_id = $constrain order by name ";
            } else {
                $sql = "select id, name from fl_county;";
            }
            $table = MIL::doQuery($sql, MYSQL_ASSOC);
        } else {
            $sql = 'select * from ' . $table_name . ' ' . ($constrain != null ? $constrain : '');
            $table = MIL::doQuery($sql, MYSQL_ASSOC);
        }
        if ($table_name == 'mil_lab') {
            $key = 'mil_id';
        } else {
            $key = 'id';
        }
       
        foreach ($table as $index => $arr) {

            $tuple = LookUpTuple::createLookupTuple($table_name, $arr);
            $id = $tuple->getProperty($key);
            $list[$id] = $tuple;
        }
        $_SESSION[$table_name] = $list;
        //}
        
        return $list;
    }

    public static function setLookupTableToSession($table_name, $constrain) {
        $table = Utility::getLookupTable($table_name, $constrain);
    }

    public static function getMaxEuDuJsArray($table_name, $colname) {
        $js = "{";

        $table = Utility::getLookupTable($table_name, null);

        foreach ($table as $id => $obj) {
            $max = $obj->getProperty($colname);
            $js .= $id . ':' . $max . ',';
        }
        $js = substr($js, 0, strlen($js) - 1);
        $js .= "}";
        return $js;
    }

    /*
     * Function printSelectedOption 
     * Display the selected option of init evaluation for follow up evaluation
     */

    public static function printSelectedOption($table_name, $constrain, $selected_id) {
        $table = Utility::getLookupTable($table_name, $constrain);
        if (array_key_exists($selected_id, $table)) {
            $obj = $table[$selected_id];
            $col = $obj->getDisplayCol();
            echo $obj->getProperty($col);
        } else {
            echo 'Missing value';
        }
    }

    public static function printOptions($table_name, $constrain, $init_eval_selected_option_val = null) {
        echo Utility::getOptions($table_name, $constrain, $init_eval_selected_option_val);
    }

    public static function getOptions($table_name, $constrain, $init_eval_selected_option_val = null) {
        $table = Utility::getLookupTable($table_name, $constrain);
        //echo "#####";
        
        $html = "";
        foreach ($table as $id => $obj) {

            $col = $obj->getDisplayCol();
            $html .= '<option value="' . $id . '"';
            $nameInEval = $obj->getNameInEval();

            if ($table_name == 'contractor') {

                if ($init_eval_selected_option_val != null) {
                    //Case 1: Admin Edit/Add Contractor Name Table
                    //$init_eval_selected_option_val will be used to pass a Lab object
                    if (get_class($init_eval_selected_option_val) == 'Lab') {
                        $contractor_name_id = $init_eval_selected_option_val->getProperty('contractor_id');
                        if ($contractor_name_id == $id) {
                            $html .='selected';
                        }
                    }
                } else {
                    //Case 2: Contractor sign up
                    if (array_key_exists('contractor_name', $_SESSION)) {

                        if ($_SESSION['contractor_name'] == $id) {
                            $html .= 'selected';
                        }
                    } else if (array_key_exists('memberReviewed', $_SESSION)) {
                        $member = $_SESSION['memberReviewed'];
                        $contractor_name = $member->getProperty('contractor_name');
                        if ($contractor_name == $id) {
                            $html .= 'selected';
                        }
                    } else if (array_key_exists('MemberServed', $_SESSION)) {
                        //Case 3:Sign up
                        $member = $_SESSION['MemberServed'];
                        $contractor_name = $member->getProperty('contractor_name');
                        if ($contractor_name == $id) {
                            $html .= 'selected';
                        }
                    }
                }
            } else if ($table_name == 'partner') {
                if (array_key_exists('memberReviewed', $_SESSION)) {
                    $member = $_SESSION['memberReviewed'];
                    $partner_name = $member->getProperty('partner_name');
                    if ($partner_name == $id) {
                        $html .= 'selected';
                    }
                } else if (array_key_exists('MemberServed', $_SESSION)) {
                    //Case 3:Sign up
                    $member = $_SESSION['MemberServed'];
                    $partner_name = $member->getProperty('partner_name');
                    if ($partner_name == $id) {
                        $html .= 'selected';
                    }
                }
            } else {


                if (array_key_exists('eval_stack', $_SESSION) && $_SESSION['eval_stack']->peek() != false) {
                    if ($init_eval_selected_option_val != null) {
                        if ($init_eval_selected_option_val == $id) {
                            $html .= 'selected';
                        }
                    } else {
                        $idInSes = $_SESSION['eval_stack']->peek()->getProperty($nameInEval);
                        if ($idInSes == $id) {
                            $html .= 'selected';
                        }
                    }
                }
            }
            $display_content = $obj->getProperty($col);
            $display_content = str_replace("'", "\'", $display_content);
            $html .= '>' . $display_content . '</option>';
        }
        return $html;
    }

    public static function getMillionGallon($acft) {
        $mg = floatval($acft) * 325851 / 1000000;
        $mg = round($mg, 2);
        $mg .= ' Millions of Gallons';
        return $mg;
    }

    public static function getMillionGallonNum($acft) {
        $mg = floatval($acft) * 325851 / 1000000;
        $mg = round($mg, 2);
        $mg .= '';
        return $mg;
    }
    public static function getMillionGallonNumPerDay($acft) {
        $mg = floatval($acft) * 325851 / (1000000 * 365);
        $mg = round($mg, 3);
        $mg .= '';
        return $mg;
    }
    public static function numToFloat($acft){
        
        $arr = explode(',', $acft);
        return implode('', $arr);
        
    }
    public static function compareDateAndFindEarliest($date1, $date2){
        
        if ($date1 == "N/A"){
            
            return $date2;
        }
        if ($date2 == "N/A"){
            
            return $date1;
        }
        if (strtotime($date1) <= strtotime($date2)){
            
            return $date1;
        }else{
            
            return $date2;
        }
        
        
    }
    
    public static function compareDateAndFindLatest($date1, $date2){
        
        if ($date1 == "N/A"){
            
            return $date2;
        }
        if ($date2 == "N/A"){
            
            return $date1;
        }
        if (strtotime($date1) <= strtotime($date2)){
            
            return $date2;
        }else{
            
            return $date1;
        }
    }
    
    public static function getLabsInfo($lab_id_array) {
        $str = Utility::arrToStr($lab_id_array);
        $sql = 'select * from mil_lab where mil_id in ' . $str;
        $labs = MIL::doQuery($sql, MYSQL_ASSOC);
        $retlabs = array();
        foreach ($labs as $key => $arr) {
            $retlabs[$arr['mil_id']] = $arr;
        }
        return $retlabs;
    }

    public static function log($content, $flag) {
        if ($flag == 'err') {
            $path = utility::errLogPath;
        } else if ($flag == 'reg') {
            $path = utility::regLogPath;
        } else {
            $path = utility::logPath . '_' . $flag . '.txt';
        }
        if (!$handle = fopen($path, 'a+')) {
            echo "Cannot open($path)";
            exit;
        }
        $content = date("g:ia") . "," . $content . "\r\n";
        if (fwrite($handle, $content) === FALSE) {
            echo "Cannot write to file {$path} ";
            exit;
        }
        //echo "successful";
        fclose($handle);
    }

//array to ("a[0]","a[1]","a[2]")
    public static function arrToStr($array) {
        $str = "";
        foreach ($array as $key => $val) {
            $str.=',"' . $val . '"';
        }
        $str = '(' . substr($str, 1) . ')';
        return $str;
    }

    public static function buildLabName($type, $name) {
        return $type . ' - ' . $name;
    }

    public static function getStartEndMonth($start_year, $quarter, $prefer) {
        $rtn = array();
        if ($prefer == FED) {
            if ($quarter == 1) {
                $rtn['year'] = $start_year;
                $rtn['start_month'] = 10;
                $rtn['end_month'] = 12;
            } else {
                $start_year++;
                $rtn['year'] = $start_year;
                if ($quarter == 2) {
                    $rtn['start_month'] = 1;
                    $rtn['end_month'] = 3;
                } else if ($quarter == 3) {
                    $rtn['start_month'] = 4;
                    $rtn['end_month'] = 6;
                } else if ($quarter == 4) {
                    $rtn['start_month'] = 7;
                    $rtn['end_month'] = 9;
                }
            }
        } else if ($prefer == FDACS) {
            if ($quarter == 1) {
                $rtn['year'] = $start_year;
                $rtn['start_month'] = 7;
                $rtn['end_month'] = 9;
            } else if ($quarter == 2) {
                $rtn['year'] = $start_year;
                $rtn['start_month'] = 10;
                $rtn['end_month'] = 12;
            } else if ($quarter == 3) {
                $start_year++;
                $rtn['year'] = $start_year;
                $rtn['start_month'] = 1;
                $rtn['end_month'] = 3;
            } else if ($quarter == 4) {
                $start_year++;
                $rtn['year'] = $start_year;
                $rtn['start_month'] = 4;
                $rtn['end_month'] = 6;
            }
        }
        return $rtn;
    }
    public static function getStarMonthFromChoosedQuarter($startQuarter,$endQuarter){
        $rtn=array();
        if ($starQuarter == 1) {
            $rtn['start_month']=7;
        }
        else if($starQuarter == 2){
            $rtn['start_month']=10;
        }
        else if($starQuarter == 3){
            $rtn['start_month']=1;
        }      
        else{
            $rtn['start_month']=4;
        }
             if ($endQuarter == 1) {
            $rtn['end_month']=7;
        }
        else if($endQuarter == 2){
            $rtn['end_month']=10;
        }
        else if($endQuarter == 3){
            $rtn['end_month']=1;
        }      
        else{
            $rtn['end_month']=4;
        }
        return rtn;
        
        
    }

    public static function getDisplayYearQuarter($yr, $month, $prefer) {
        $a = array();
//require_once '../includes/constant.php';
        if ($prefer == 0) { //FDACS
            $a['standard'] = 'June';
            if ($month >= 1 && $month < 4) {
                $a['syr'] = $yr - 1;
                $a['eyr'] = $yr;
                $a['q'] = 3;
            } else if ($month >= 4 && $month < 7) {
                $a['syr'] = $yr - 1;
                $a['eyr'] = $yr;
                $a['q'] = 4;
            } else if ($month >= 7 && $month < 9) {
                $a['syr'] = $yr;
                $a['eyr'] = $yr + 1;
                $a['q'] = 1;
            } else if ($month >= 9 && $month <= 12) {
                $a['syr'] = $yr;
                $a['eyr'] = $yr + 1;
                $a['q'] = 2;
            } else {
                throw new Exception('illegal input of month and yr');
            }
        } else if ($prefer == 1) {//Federal
            $a['standard'] = 'September';
            if ($month >= 1 && $month < 4) {
                $a['syr'] = $yr - 1;
                $a['eyr'] = $yr;
                $a['q'] = 2;
            } else if ($month >= 4 && $month < 7) {
                $a['syr'] = $yr - 1;
                $a['eyr'] = $yr;
                $a['q'] = 3;
            } else if ($month >= 7 && $month < 9) {
                $a['syr'] = $yr - 1;
                $a['eyr'] = $yr;
                $a['q'] = 4;
            } else if ($month >= 9 && $month <= 12) {
                $a['syr'] = $yr;
                $a['eyr'] = $yr + 1;
                $a['q'] = 1;
            } else {
                throw new Exception('illegal input of month and yr');
            }
        } else {
            throw new Exception('illegal input of user perference');
        }
        return $a;
    }
    public static function getReportYearQuarter($yr, $month) {
        $a = array();
//require_once '../includes/constant.php';
        //FDACS
        $a['standard'] = 'June';
        if ($month >= 1 && $month < 4) {
            $a['fdacs_start_yr'] = $yr - 1;
            $a['fdacs_quarter'] = 3;
            $a['fed_start_yr'] = $yr - 1;
            $a['fed_quarter'] = 2;
            $a['end_month']=3;
        } else if ($month >= 4 && $month < 7) {
            $a['fdacs_start_yr'] = $yr - 1;
            $a['fdacs_quarter'] = 4;
            $a['fed_start_yr'] = $yr - 1;
            $a['fed_quarter'] = 3;
            $a['end_month']=6;
        } else if ($month >= 7 && $month < 10) {
            $a['fdacs_start_yr'] = $yr;
            $a['fdacs_quarter'] = 1;
            $a['fed_start_yr'] = $yr - 1;
            $a['fed_quarter'] = 4;
            $a['end_month']=9;
        } else if ($month >= 10 && $month <= 12) {
            $a['fdacs_start_yr'] = $yr;
            $a['fdacs_quarter'] = 2;
            $a['fed_start_yr'] = $yr;
            $a['fed_quarter'] = 1;
            $a['end_month']=12;
        } else {
            throw new Exception('illegal input of month and yr');
        }


        return $a;
    }

}

class Buttons {

    protected $node;
    protected $role;
    protected $buttons = array();

//decide button list, based on user's role and node( type: eval/pack; status: pending) 
    public function __construct($node, $role) {
        $this->node = $node;
        $this->role = $role;
        $status = $this->node->getProperty('status');
        $class_name = get_class($node);
        $btn_actions = array();
        if ($role == ADMIN_ROLE) {//admin
            if ($status == "pending") {
                $btn_actions = array("edit");
                //$btn_actions = array("edit", "delete");
            } else if ($status == 'submitted') {
                $btn_actions = array("review");
            } else if ($status == 'disapproved') {
                $btn_actions = array();
                //enteredByAdmin used by lookup tables entered by admin
            } else if ($status == 'approved') {
                if ($class_name == 'Package') {
                    $btn_actions = array("invoice","return");
                } else {
                    $btn_actions = array();
                }
            } else if ($status == 'enteredByAdmin') {
                $btn_actions = array("edit");
            }
        } else if ($role == EMPLOYEE_ROLE) { //contractor_employee
            if ($status == "pending") {
                if ($class_name == 'Package') {
                    //$btn_actions = array("edit", "delete", "submit");
                    $btn_actions = array("edit", "submit");
                } else {
                    $parent_class_name = get_parent_class($node);
                    if ($parent_class_name == 'Evaluation' || $parent_class_name == 'InitIrrSys') {

                        $btn_actions = array("edit", "delete");
                    } else {
                        $btn_actions = array();
                    }
                }
            } else if ($status == "submitted" || $status == 'approved' || $status == 'disapproved' || $status == 'enteredByAdmin') {
                $btn_actions = array();
            } else {
                echo 'invalid status in button class';
                exit;
            }
        } else if ($role == CONTRACTOR_ROLE) {
            if ($status == "pending") {
                if ($class_name == 'Package') {
                    //$btn_actions = array("edit", "delete", "submit");
                    $btn_actions = array("edit", "submit");
                } else {
                    $parent_class_name = get_parent_class($node);
                    if ($parent_class_name == 'Evaluation' || $parent_class_name == 'InitIrrSys') {

                        $btn_actions = array("edit", "delete");
                    } else {
                        $btn_actions = array();
                    }
                }
            } else if ($status == "submitted" || $status == 'disapproved' || $status == 'enteredByAdmin') {
                $btn_actions = array();
            } else if ($status == 'approved') {
                if ($class_name == 'Package') {
                    $btn_actions = array("invoice");
                } else {
                    $btn_actions = array();
                }
            } else {
                echo 'invalid status in button class';
                exit;
            }
        }else if ($role == PARTNER_ROLE) {
                $parent_class_name = get_parent_class($node);
                if ($parent_class_name == 'Member') {
                    $btn_actions = array("report");
                }
            } 

        foreach ($btn_actions as $action) {
            $parent_type = get_parent_class($node);
            if ($parent_type == 'Evaluation' || $parent_type == "InitIrrSys") {
                $type = 'Evaluation';
            } else {
                $type = get_class($node);
            }
            $type = strtolower($type);
            $btn = new Button($action, $type, $node->getID());
            array_push($this->buttons, $btn);
        }
    }

    public function getString() {
        $rtn = "";
        foreach ($this->buttons as $btn) {
            $rtn .= $btn->getString();
        }
        return $rtn;
    }

}

class Button {

    protected $name;
    protected $val;
    protected $operation;
    protected $type; //pack or eval
    protected $id;

    public function __construct($operation, $type, $id) {
        $this->operation = $operation;
        $this->type = $type;
        $this->id = $id;
        if ($this->operation == "edit") {
            $this->name = "edit_{$this->type}:" . $id;
            $this->val = 'Edit';
        } else if ($this->operation == "delete") {
            $this->name = "delete_{$this->type}:" . $id;
            $this->val = 'Delete';
        } else if ($this->operation == 'submit') {
            $this->name = "submit_{$this->type}:" . $id;
            $this->val = 'Submit';
        } else if ($this->operation == 'review') {
            $this->name = "review_{$this->type}:" . $id;
            $this->val = 'Review';
        } else if ($this->operation == 'invoice') {
            $this->name = "invoice_{$this->type}:" . $id;
            $this->val = 'Invoice';
        }else if($this->operation == 'report'){
            $this->name = "report_{$this->type}:" . $id;
            $this->val = 'Edit Report';
        }
        else if($this->operation == 'return'){
            $this->name = "return_{$this->type}:" . $id;
            $this->val = 'Return';
            
        }
    }

    public function getName() {
        return $this->name;
    }

    public function getVal() {
        return $this->val;
    }

    public function getType() {
        return $this->type;
    }

    public function getOperation() {
        return $this->operation;
    }

    public function getID() {
        return $this->id;
    }

    public function getString() {
        if ($this->operation == "delete") {
            if (strstr($this->name, 'evaluation')) {
                return "<input class='button' type='button' name=\"{$this->name}\" value=\"{$this->val}\"  
            onclick='canbeDeleted(\"{$this->type}\",\"{$this->id}\")'/>";
            } else {
                return "<input class=\"button\" type=\"button\" name=\"{$this->name}\" value=\"{$this->val}\"/>";
            }
        } 
            else {
            return "<input class=\"button\" type=\"submit\" name=\"{$this->name}\" value=\"{$this->val}\" />";
        }
    }

}

class FiscalQuarter {

    protected $fiscalSYr;
    protected $fiscalEYr;
    protected $fiscalQtr;
    protected $fiscalDQtr;
    protected $userPref;
    protected $displayPref;

    public static function getQuarterRange($month) {
        $result = array('min' => '', 'max' => '');
        if ($month >= 1 && $month < 4) {
            $result = array('min' => 1, 'max' => 3);
        } else if ($month >= 4 && $month < 7) {
            $result = array('min' => 4, 'max' => 6);
        } else if ($month >= 7 && $month < 10) {
            $result = array('min' => 7, 'max' => 9);
        } else if ($month >= 10 && $month <= 12) {
            $result = array('min' => 10, 'max' => 12);
        } else {
            throw new Exception('illegal input of month and yr');
        }
        return $result;
    }

    public static function getFiscalEndMonth($prefer) {
        $fiscal_end_month = 'Ends in ';
        if ($prefer == 0) {
            $fiscal_end_month .= "Jun";
        } else if ($prefer == 1) {
            $fiscal_end_month .= "Sept";
        } else if ($prefer == 2) {
            $fiscal_end_month .= "Dec";
        }
        return $fiscal_end_month;
    }

    public function __construct($yr=null, $month=null, $prefer) {
        $this->userPref = $prefer;
        if ($month >= 1 && $month < 4) {
            $this->fiscalDQtr = 'Jan to Mar';
        } else if ($month >= 4 && $month < 7) {
            $this->fiscalDQtr = 'Apr to Jun';
        } else if ($month >= 7 && $month < 10) {
            $this->fiscalDQtr = 'Jul to Sept';
        } else if ($month >= 10 && $month <= 12) {
            $this->fiscalDQtr = 'Oct to Dec';
        } else {
            throw new Exception('illegal input of month and yr');
        }
        if ($prefer == 0) { //FDACS
            $this->displayPref = 'Jun';
            if ($month >= 1 && $month < 4) {
                $this->fiscalSYr = $yr - 1;
                $this->fiscalEYr = $yr;
                $this->fiscalQtr = 3;
            } else if ($month >= 4 && $month < 7) {
                $this->fiscalSYr = $yr - 1;
                $this->fiscalEYr = $yr;
                $this->fiscalQtr = 4;
            } else if ($month >= 7 && $month < 10) {
                $this->fiscalSYr = $yr;
                $this->fiscalEYr = $yr + 1;
                $this->fiscalQtr = 1;
            } else if ($month >= 10 && $month <= 12) {
                $this->fiscalSYr = $yr;
                $this->fiscalEYr = $yr + 1;
                $this->fiscalQtr = 2;
            } else {
                throw new Exception('illegal input of month and yr');
            }
        } else if ($prefer == 1) {//Federal
            $this->displayPref = 'Sept';
            if ($month >= 1 && $month < 4) {
                $this->fiscalSYr = $yr - 1;
                $this->fiscalEYr = $yr;
                $this->fiscalQtr = 2;
            } else if ($month >= 4 && $month < 7) {
                $this->fiscalSYr = $yr - 1;
                $this->fiscalEYr = $yr;
                $this->fiscalQtr = 3;
            } else if ($month >= 7 && $month < 10) {
                $this->fiscalSYr = $yr - 1;
                $this->fiscalEYr = $yr;
                $this->fiscalQtr = 4;
            } else if ($month >= 10 && $month <= 12) {
                $this->fiscalSYr = $yr;
                $this->fiscalEYr = $yr + 1;
                $this->fiscalQtr = 1;
            } else {
                throw new Exception('illegal input of month and yr');
            }
        } else if ($prefer == 2) {
            $this->displayPref = 'Dec';
            if ($month >= 1 && $month < 4) {
                $this->fiscalSYr = $yr;
                $this->fiscalEYr = $yr + 1;
                $this->fiscalQtr = 1;
            } else if ($month >= 4 && $month < 7) {
                $this->fiscalSYr = $yr;
                $this->fiscalEYr = $yr + 1;
                $this->fiscalQtr = 2;
            } else if ($month >= 7 && $month < 10) {
                $this->fiscalSYr = $yr;
                $this->fiscalEYr = $yr + 1;
                $this->fiscalQtr = 3;
            } else if ($month >= 10 && $month <= 12) {
                $this->fiscalSYr = $yr;
                $this->fiscalEYr = $yr + 1;
                $this->fiscalQtr = 4;
            } else {
                throw new Exception('illegal input of month and yr');
            }
        } else {
            throw new Exception('illegal input of user perference');
        }
    }

    public function getFiscalSYr() {
        return $this->fiscalSYr;
    }

    public function getFiscalEYr() {
        return $this->fiscalEYr;
    }

    public function getFiscalQtr() {
        return $this->fiscalQtr;
    }

    public function getDisplayPref() {
        return $this->displayPref;
    }

    public function getFiscalYr() {
        return $this->fiscalSYr . ' - ' . $this->fiscalEYr;
    }

//D: detail month to month
    public function getFiscalDQtr() {
        return $this->fiscalDQtr;
    }

}

