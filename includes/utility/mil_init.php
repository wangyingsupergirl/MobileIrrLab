<?php
if($_SERVER['SERVER_NAME'] == 'fdacsmilims.fawn.ifas.ufl.edu'){
  define('MIL_SERVER', 'mysql.osg.ufl.edu'); 
  define('MIL_DB','milDB');
  define('MIL_DB_USERNAME','milDBAdmin');
  define('MIL_DB_PASSWORD','q2fK7HRcYz');
  define('MIL_DB_INSERT', 'INSERT');
  define('MIL_SERVER_ROOT','https://fdacsmilims.fawn.ifas.ufl.edu/');
}else if($_SERVER['SERVER_NAME'] == 'test.fdacsmilims.fawn.ifas.ufl.edu'){
  define('MIL_SERVER', 'mysql.osg.ufl.edu:3307'); 
  define('MIL_DB','mildb');
  define('MIL_DB_USERNAME','mildb');
  define('MIL_DB_PASSWORD','MxJqimyHin');
  define('MIL_DB_INSERT', 'INSERT');
  define('MIL_SERVER_ROOT','http://test.fdacsmilims.fawn.ifas.ufl.edu/');
}else{
    /*
     define('MIL_SERVER', 'mysql.osg.ufl.edu:3307'); 
     define('MIL_DB','mildb');
     define('MIL_DB_USERNAME','mildb');
     define('MIL_DB_PASSWORD','MxJqimyHin');
     define('MIL_DB_INSERT', 'INSERT');
     define('MIL_SERVER_ROOT','http://localhost/');
     * 
     */
  
  define('MIL_SERVER', 'mysql.osg.ufl.edu'); 
  define('MIL_DB','milDB');
  define('MIL_DB_USERNAME','milDBAdmin');
  define('MIL_DB_PASSWORD','q2fK7HRcYz');
  define('MIL_DB_INSERT', 'INSERT');
  define('MIL_SERVER_ROOT','http://localhost/');
 
}
date_default_timezone_set('America/New_York');
require_once dirname(__FILE__) . '/KLogger.php';
$kLog = KLogger::instance(dirname(__FILE__).'/../log/', KLogger::DEBUG);
require_once('mil.php');
