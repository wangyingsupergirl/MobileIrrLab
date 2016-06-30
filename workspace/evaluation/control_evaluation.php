<?php

require_once dirname(__FILE__) . '/../../includes/input/package/Contract.php';
require_once dirname(__FILE__) . '/../../includes/input/package/Package.php';
require_once dirname(__FILE__) . '/../../includes/input/package/evaluation/FollowIrrSys.php';
require_once dirname(__FILE__) . '/../../includes/input/package/evaluation/InitIrrSys.php';
require_once dirname(__FILE__) . '/../../includes/input/package/evaluation/FollowFirm.php';
require_once dirname(__FILE__) . '/../../includes/input/package/evaluation/InitFirm.php';
require_once dirname(__FILE__) . '/../../includes/input/package/evaluation/ReplacementFirm.php';
require_once dirname(__FILE__) . '/../../includes/input/package/evaluation/ReplacementIrrSys.php';
require_once dirname(__FILE__) . '/../../includes/constant.php';
require_once dirname(__FILE__) . '/../../includes/utility/forms_validation.php';
require_once dirname(__FILE__) . '/../../includes/input/package/WaitEval.php';
require_once dirname(__FILE__) . '/../../includes/input/package/EducationReport.php';
require_once dirname(__FILE__) . '/../../includes/input/package/SearchLastEval.php';
require_once dirname(__FILE__) .'/../../includes/report/report.php';
require_once dirname(__FILE__) .'/../../includes/report/report_11a.php';
require_once dirname(__FILE__) .'/../../includes/report/report_11b.php';
require_once dirname(__FILE__) .'/../../includes/report/report_11c.php';
require_once dirname(__FILE__) .'/../../includes/report/report_7.php';
require_once dirname(__FILE__) .'/../../includes/report/report_8.php';
require_once dirname(__FILE__) .'/../../includes/report/report_9.php';
require_once dirname(__FILE__) . '/../../includes/input/Invoice.php';

session_start();
/* if users use back button on the browser, tab = 1 will make sure they will be back to MIL Evaluation Tab */
$_SESSION['tab'] = EVALUATION_TAB;
if(array_key_exists('submit_new_contract',$_POST)){
  header('Location:./package_content_list.php');  
}else if(array_key_exists('choose_eval_contract',$_POST)){
    header('Location:./package_content_list.php');
    exit;
}else if(array_key_exists('edit_package_comments',$_POST)){
    header('Location:./package_comments.php');
    exit;
}else if(array_key_exists('select_package_contract',$_POST)){
    header('Location:contracts_list.php');
    exit;
}else if(array_key_exists('package_comments_submit',$_POST)){
   $package = $_SESSION['PackageObject'];
    $comments = $package->updateProperty("comments", $_POST["comments"]);
    header('Location:./package_content_list.php');
    exit;
}else if(array_key_exists('package_comments_cancel',$_POST)){
     header('Location:./package_content_list.php');
     exit;
}else if (array_key_exists('back_to_workspace', $_POST)) {
    header('Location: ../workspace.php');
    exit;
} else if (array_key_exists('back_to_db_enter', $_POST)) {
    header('Location: ./eval_db_or_enter.php');
    exit;
} else if (array_key_exists('add_new_eval_package', $_POST)) {
    header('Location: ./package_new.php');
    exit;
} else if (array_key_exists('new_package', $_POST)) {
    try {
        $package = new Package($_POST);
        $bool = $package->newPackage();
        $_SESSION['PackageObject'] = $package;
        if ($bool) {
            header('Location:./package_content_list.php');
        } else {
            header('Location:./package_content_list.php?before=1');
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    exit;
} else if (array_key_exists('edit_waiting_list', $_POST)) {

    header('Location: waiting_list.php');
    exit;
} else if (array_key_exists('edit_education_report', $_POST)) {
    header('Location: eval_education.php');
    exit;
} else if (array_key_exists('add_waiting_eval', $_POST)) {
    $package = $_SESSION['PackageObject'];
    $_POST['package_id'] = $package->getProperty('id');
    $waitingEval = new WaitEval($_POST);
    $waitingEval->insertToDb();
    header('Location: waiting_list.php');
    exit;
} else if (array_key_exists('add_eval_education', $_POST)) {
   $package = $_SESSION['PackageObject'];
    $_POST['package_id'] = $package->getProperty('id');
    $node = new EducationReport($_POST);
    $node->insertToDb();
    header('Location: ../../workspace/evaluation/eval_education.php');
    exit;
} else if (array_key_exists('back_to_package', $_POST)) {
    header('Location: package_content_list.php');
    exit;
}else if(array_key_exists('replacement_eval_id_submit', $_POST)){
    if(array_key_exists('display_id',$_POST)){
        $display_id = $_POST['display_id'];
        $eval = $_SESSION['eval_stack']->peek();
        $eval->setProperty('display_id',$display_id);
        $return = $eval->existsAsReplacement();
        if($return==false){
            //Two Cases:
            //1. The evaluation exists as initial evaluation(Enter before as initial evaluation)
            //2. The evaluation doesn't exist at all.
            header('Location:./eval_db_or_enter.php');
        }else{
            //3. The evaluation exists as replacement evaluaiton(indicate initial evaluation must exists too)
            header("Location: package_content_list.php?msg=$display_id has been entered already as Replacement Evaluation.");
        }
    }else{
        echo 'display_id should be in $_POST';
    }
    exit;
} else if (array_key_exists('eval_type_submit', $_POST)) {
    Form::validate('eval_type_submit', $_POST);
    $eval = $_SESSION['eval_stack']->peek();
    $eval = Evaluation::transformEval($_POST, $eval);
    $_SESSION['eval_stack']->switchTop($eval);
    $eval_type = $eval->getProperty('eval_type');
    if ($eval_type == 2) { //follow up eval
        header('Location:./eval_db_or_enter.php');
    }else if($eval_type == 3){
        //header('Location:./enter_replacement_eval_id.php');
      header('Location:./eval_db_or_enter.php');
    }else if ($eval_type == 1) {//initial eval
        header('Location:./eval_content.php');
    }
    exit;
} else if (array_key_exists('eval_type_change', $_POST)) {
    unset($_SESSION['eval']);
    header('Location:./eval_content.php');
    exit;
} else if (array_key_exists('eval_irr_sys_type_update', $_POST)) {
    require_once '../../includes/input/package.php';
    $eval_stack = $_SESSION['eval_stack'];
    $eval = $eval_stack->peek();
    $eval->setAttrArr($_POST);
    if (array_key_exists('irr_sys_type', $_POST)) {
        $irr_sys_type = $_POST['irr_sys_type'];
        $eval->setProperty('irr_sys_type', $irr_sys_type);
    }
    //save all the user's input into evaluation object.
    $eval->setAttrArr($_POST);
    $_SESSION['eval'] = $eval;
    header('Location:./eval_content.php');
    exit;
} else if (array_key_exists('eval_submit', $_POST)) {

    if (array_key_exists('eval_stack', $_SESSION)) {
        $eval_stack = $_SESSION['eval_stack'];
        $eval = $eval_stack->peek();
        //0 vs NULL
        if(!array_key_exists('nir_checkbox', $_POST)){
            $_POST['nir_water_use'] = NULL;     
        }
        if(!array_key_exists('awu_checkbox', $_POST)){
            $_POST['actual_water_use'] = NULL;
        }
        $eval->setAttrArr($_POST);
        try {
            $suc = $eval->validateInput();
        } catch (Exception $e) {
            $error = './eval_content.php?err=' . $e->getMessage();
            header('Location: ' . $error);
            exit;
        }
        
        $next_eval = $eval_stack->top(2);
        if($next_eval!=false){
            /*
             *Evaluation stack size is larger than 2.
             *Current $eval could be Follow up/ Replacement evaluation
             *1. If $eval is Follow up, $next_eval is initial
             *2. If $eval is Replace, $next_eval is follow up/initial(the 'initEval' of Replacement evaluation could be follow up/initial )
             */
            $next_eval->setProperty('init_eval_id', $eval->getProperty('id'));
            $next_eval->setProperty('farm_id', $eval->getProperty('farm_id'));
            $next_eval->setProperty('initEval', $eval);
            
        }
        
        //check the existense package and get package id
        $package = $_SESSION['PackageObject'];
        $mil_id = $package->getProperty('mil_id');
        $package = new Package(array('mil_id' => $mil_id, 'eval_yr' => $_POST['eval_yr'], 'eval_month' => $_POST['eval_month']));
        /* $bool==false initial evaluation has been added to exist package, 
         * if not create a new package */
        $bool = $package->newPackage();
        $eval->setProperty('package_id', $package->getProperty('id'));
        $eval->commitToDb();
        $eval_stack->pop();
        if ($next_eval == false) {
            if ($bool) {
                header('Location:./eval_suc.php?init=11');
            } else {
                header('Location:./eval_suc.php?init=10');
            }
        } else {
            header('Location:./eval_suc.php?init=0');
        }
    }
    exit;
} else if (array_key_exists('eval_suc', $_POST)) {
    if (array_key_exists('init', $_POST)) {
        if ($_POST['init'] == 10 || $_POST['init'] == 11) {
            header('Location: package_content_list.php');
        } else if ($_POST['init'] == 0) {
            header('Location: eval_content.php');
        }
        exit;
    }
    header('Location: package_content_list.php');
    exit;
} else if (array_key_exists('eval_cancel', $_POST)) {
    //require_once '../../includes/input/package.php';
    //session_start();

    header('Location: package_content_list.php');
    exit;
} else if (array_key_exists('add_new_eval', $_POST)) {
    /* Jie 9/22/2011
     * The request is from package content page(package_content_list.php)
     * Evaluation stack should be blank at this moment.
     * Then clear evaluation stack(_SESSION[eval_stack]) in the session
     
     * evaluation content page(eval_content.php) always displays the top eval in stack.
     */
    if (array_key_exists('eval_stack', $_SESSION)){
        unset($_SESSION['eval_stack']);
    }
    if (array_key_exists('PackageObject', $_SESSION)) {
        $package = $_SESSION['PackageObject'];
        $package_id = $package->getProperty('id');
        $mil_id = $package->getProperty('mil_id');
        $eval = Evaluation::createEval('new_eval', array('package_id' => $package_id, 'mil_id' => $mil_id));
        $eval_stack = new Stack(array($eval));
        $_SESSION['eval_stack'] = $eval_stack;
        header('Location:eval_content.php');
        exit;
    }else{
        echo 'Session package object is missing';
        exit;
    }
    
} else if (array_key_exists('eval_type_delete', $_POST)) {

    if (array_key_exists('eval_stack', $_SESSION)) {
        //array_push($_SESSION['eval_stack'],$eval); // doubt can be used?
        $eval_stack = $_SESSION['eval_stack'];
        $eval = $eval_stack->peek();
        $eval->setAttrArr($_POST);
        //set eval_type undetermined
        $eval->setProperty('eval_type',''); 
        header('Location:eval_content.php');
    }
    exit;
} else if (array_key_exists('eval_db_or_enter', $_POST)) {

    $flag = $_POST['init_input_method'];
    if ($flag == "db") {
        header('Location: search_criteria.php');
    } else if ($flag == 'enter') {
        if (array_key_exists('eval_stack', $_SESSION)) {
            //array_push($_SESSION['eval_stack'],$eval); // doubt can be used?
            $eval_stack = $_SESSION['eval_stack'];
            $mil_id = $eval_stack->peek()->getProperty('mil_id');
            $arr = array('mil_id' => $mil_id);
            $eval = Evaluation::createEval('new_init_eval', $arr);
            $eval_stack->push($eval); //reference
            header('Location: eval_content.php');
        } else {
            header('Location: error.php');
        }
    }
    exit;
} else if (array_key_exists('search_eval', $_POST)) {
    $eval  =  $_SESSION['eval_stack']->peek();
    try {
        $search = new SearchLastEval($_POST, $eval);
    } catch (Exception $e) {
        $error = './search_criteria.php?err=' . $e->getMessage();
        header('Location: ' . $error);
        exit;
    }
    $_SESSION['search'] = $search;
    header('Location: list_of_init_evals.php');
    exit;
} else if (array_key_exists('refine_search_criteria', $_POST)) {
    header('Location: ./search_criteria.php');
    exit;
} else if (array_key_exists('calculate_water_saving', $_POST)) {
    if (array_key_exists('eval_stack', $_SESSION)) {
        $eval_stack = $_SESSION['eval_stack'];
        $eval = $eval_stack->peek();
         if(!array_key_exists('nir_checkbox', $_POST)){
            $_POST['nir_water_use'] = NULL;
             
        }
        if(!array_key_exists('awu_checkbox', $_POST)){
            $_POST['actual_water_use'] = NULL;
            
        }
        $eval->setAttrArr($_POST);
        Utility::setLookupTableToSession('irr_sys_types', NULL);
        try {
            $suc = $eval->validateInput();
            if ($suc) {
                $irr_sys_types_table = $_SESSION['irr_sys_types'];
                $paws = $eval->calculateDuEuImprov($irr_sys_types_table);
            } else {
                $eval->setProperty('duEuImprov', false);
            }//false will throws exception, so don't need else case
        } catch (Exception $e) {
            $error = './eval_content.php?err=' . $e->getMessage();
            header('Location: ' . $error);
            exit;
        }
        header('Location: eval_content.php#irr_calculation');
        exit;
    }
}else if(array_key_exists('calculate_replacement_water_saving', $_POST)){
   if (array_key_exists('eval_stack', $_SESSION)) {
        $eval_stack = $_SESSION['eval_stack'];
        $eval = $eval_stack->peek();
         if(!array_key_exists('nir_checkbox', $_POST)){
            $_POST['nir_water_use'] = NULL;
             
        }
        if(!array_key_exists('awu_checkbox', $_POST)){
            $_POST['actual_water_use'] = NULL;
            
        }
        $eval->setAttrArr($_POST);
        Utility::setLookupTableToSession('irr_sys_types', NULL);
        try {
           $irr_sys_types_table = $_SESSION['irr_sys_types'];
           $actual_du= $eval->calculateDuEuImprov($irr_sys_types_table);
           $aws = $eval->getTotalWS();
           $potiential_du = $eval->calculateInitialCopyDuEuImprov($irr_sys_types_table);
           $pws = $eval->getTotalPWS();
           $lastEval = $eval->getProperty('initEval');
           $potiential_du_last_eval = ($lastEval!=null?$lastEval->calculateDuEuImprov($irr_sys_types_table):"");
           $pws_last_eval = $lastEval->getTotalWS();
           $acre_ft = array(
                'actual_du'=>$actual_du
                ,'aws'=>$aws
                ,'potiential_du'=>$potiential_du
                ,'pws'=>$pws
                ,'potiential_du_last_eval'=>$potiential_du_last_eval
                ,'pws_last_eval'=>$pws_last_eval);
           $data = array();
           foreach($acre_ft as $key => $val){
            $data[$key] = $val;
            $gallon = Utility::getMillionGallonNum($val);
            $key .= "_gallon";
            $data[$key] = $gallon;
            
           }
           $response = array(
               'data'=>$data);
          
           
            //false will throws exception, so don't need else case
        } catch (Exception $e) {
           $response = array('msg'=>$e->getMessage());
         
        }
         $json = json_encode($response);
         echo $json;
        exit;
    }
}else if(array_key_exists('approve_package', $_POST)){
     $package = $_SESSION['PackageObject'];
     $pack_id = $package->getProperty('id');
     $package->approve();
     header("Location:../workspace.php?msg=package $pack_id has been approved");
}else if(array_key_exists('disapprove_package', $_POST)){
     $package = $_SESSION['PackageObject'];
     $package->disapprove($_POST['admin_comments']);
     $pack_id = $package->getProperty('id');
      header("Location:../workspace.php?msg=package $pack_id has been disapproved");
}else if(array_key_exists('preview_package',$_POST)){
    $package = $_SESSION['PackageObject'];
    $preview = $package->preview();
    echo $preview;
    exit;
}else if(array_key_exists('request_invoice_pdf',$_POST)){
    $invoice = $_SESSION['invoice'];
    $invoice->setUserInput($_POST); 
    header("Location:./invoice/invoice_pdf_generator.php");
    exit;
}

foreach ($_POST as $paraName => $paraVal) {
    if (strstr($paraName, 'delete_waiting_eval')) {
        $pieces = explode(":", $paraName);
        $wait_eval_id = $pieces[1];

        session_start();
        $package = $_SESSION['PackageObject'];
        $list = $package->getWaitingEvalList();
        $wait_eval = $list[$wait_eval_id];
        $wait_eval->delete();
        header('Location: ../../workspace/evaluation/waiting_list.php');
        exit;
    }
    if (strstr($paraName, 'choose_inital_eval_id')) {
        $pieces = explode(":", $paraName);
        $last_eval_id = $pieces[1];
        if (array_key_exists('search', $_SESSION)) {
            $search = $_SESSION['search'];
            $evalList = $search->getProperty('evalList');
            $last_eval = $evalList[$last_eval_id];
            if (array_key_exists('eval_stack', $_SESSION)) {
                $follow_eval = $_SESSION['eval_stack']->peek();
                $follow_eval->setProperty('init_eval_id', $last_eval_id);
                $follow_eval->setProperty('initEval', $last_eval);
                $follow_eval->setProperty('farm_id', $last_eval->getProperty('farm_id'));
            }else{
                echo "eval_stack is missing in SESSION";
                exit;
            }
            header('Location: eval_content.php');
        }
        exit;
    }
    if (strstr($paraName, 'delete_eval_education')) {
        $pieces = explode(":", $paraName);
        $node_id = $pieces[1];
        $package = $_SESSION['PackageObject'];
        $nodes = $package->getEducationReportList();
        $nodes[$node_id]->delete();
        header('Location: ../../workspace/evaluation/eval_education.php');
        exit;
    } else if (strstr($paraName, 'edit_package')) {
        //Reviewed, no problem
        $pieces = explode(":", $paraName);
        $pack_id = $pieces[1];
        $package = $_SESSION['Packages'][$pack_id];
        $_SESSION['PackageObject'] = $package;
        header('Location: package_content_list.php');
        exit;
    } else if (strstr($paraName, 'review_package_details')) {
        $pieces = explode(':',$paraName);
        $report_id = $pieces[1];
        $package = $_SESSION['PackageObject'];
        $report = Report::createReport($report_id);
        $arr = $report->changeObj2Arr($package,$report_id);
        $result = $report->requestDBData($arr,'submitted');
        if(!$result){
            echo 'The data of this report have not been entered by MIL yet.';
        }
        $_SESSION['report'] = $report;
        header("Location: ../report/report_results/simple_report.php");
        exit;
    }  else if (strstr($paraName, 'review_package')) {
        $pieces = explode(":", $paraName);
        $pack_id = $pieces[1];
        $package = $_SESSION['Packages'][$pack_id];
        $_SESSION['PackageObject'] = $package;
        header('Location: review_package.php');
        exit;
    } else if (strstr($paraName, 'delete_package')) {
        $pieces = explode(":", $paraName);
        $pack_id = $pieces[1];
        $package = $_SESSION['Packages'][$pack_id];
        $result = $package->delete();
        if ($result) {
            header("Location:../workspace.php?msg=package $pack_id has been deleted.");
        } else {
            echo $result;
        }
        exit;
    }else if (strstr($paraName, 'submit_package')) {
        $pieces = explode(":", $paraName);
        $pack_id = $pieces[1];
        $package = $_SESSION['Packages'][$pack_id];
        $mem_id = $_SESSION['MemberServed']->getProperty('mem_id');
        $package->updateProperty('submitted_by_member_id',$mem_id);
        $result = $package->submit();
        //$log = KLogger::instance(dirname(__FILE__).'/../../log/', KLogger::DEBUG);
        $now = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'];
        $hostname = gethostbyaddr($ip);
        $kLog->logInfo("Package {$package->getProperty('id')} is submitted by member $mem_id($ip $hostname) at $now ");
        if ($result) {
            header("Location:../workspace.php?msg=package $pack_id has been submitted successfully.");
        } else {
            echo $result;
        }
        exit;
    } else if (strstr($paraName, 'edit_evaluation')) {

        $pieces = explode(":", $paraName);
        $eval_id = $pieces[1];
        $package = $_SESSION['PackageObject'];
        $eval_list = $package->getEvalList();
        if(array_key_exists($eval_id, $eval_list)){
           $eval = $eval_list[$eval_id];
        }else{
            foreach($eval_list as $eval){
                $init_eval = $eval->getProperty('initEval');
                if($init_eval){
                    if($init_eval->getProperty('id')==$eval_id){
                        $init_eval->setProperty('isSingleEval', true);
                        $eval = $init_eval;
                        break;
                    }
                }
            }
        }
        
        $eval_stack = new Stack(array($eval));
        $_SESSION['eval_stack'] = $eval_stack;
        header('Location: eval_content.php');
        exit;
    } else if (strstr($paraName, 'delete_evaluation')) {
        $pieces = explode(":", $paraName);
        $eval_id = $pieces[1];
        $package = $_SESSION['PackageObject'];
        $eval_list = $package->getEvalList();
        $eval = $eval_list[$eval_id];
        $result = $eval->delete();
        if ($result) {
            header("Location: package_content_list.php?msg=Evaluation $eval_id has been deleted.");
        } else {
            echo $result;
        }
        exit;
    }else if (strstr($paraName, 'add_contract_to_package')) {
            $pieces = explode(":", $paraName);
            $contract_id = $pieces[1];
            $package = $_SESSION['PackageObject'];
            $result = $package->updateProperty('contract_id',$contract_id);
            if($result){
                header('Location: package_content_list.php?msg=Contract can not be added.');
            }else{
                header('Location: package_content_list.php?msg=Contract has been added to package.');
            }
            exit;
   }else if(strstr($paraName, 'invoice_package')) {
            $pieces = explode(":", $paraName);
             $package_id = $pieces[1];
            $package = $_SESSION['Packages'][$package_id];
            $invoice  =  new Invoice($package);
            $invoice->setInitialValue();
            $_SESSION['invoice']= $invoice;
            header('Location:invoice/invoice.php');
            exit;
   }
   else if(strstr($paraName, 'return_package')) {
        $pieces = explode(":", $paraName);
        $pack_id = $pieces[1];
        $package = $_SESSION['Packages'][$pack_id];
        //header("Location:../workspace.php?msg=package $pack_id has been deleted.");
        $comment="";
        $result = $package->disapprove($comment);
        header("Location:../workspace.php?msg=package $pack_id has been return pending.");
        exit;
   }
}
