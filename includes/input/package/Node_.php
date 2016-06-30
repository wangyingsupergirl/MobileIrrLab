//<?php
//
//require_once dirname(__FILE__) . '/../../constant.php';
//require_once dirname(__FILE__) . '/../../mil_init.php';
//require_once dirname(__FILE__) . '/../../utility.php';
//
//class Node {
//
// protected $id;
// protected $parentId = null;
// protected $attrArr;
// protected $tableName;
//
// //protected $mode; // ADD?EDIT?VIEW?
//
// public function generateId() {
//  return uniqid(); //for only one server 
// }
//
// public static function createID() {
//  return uniqid();
// }
//
// public function exists($arr) {
//  $sql = "select * from $this->tableName where ";
//  if (array_key_exists('range', $arr)) {
//   $range = $arr['range'];
//   $sql .= "{$range['name']} between {$range['min']} and {$range['max']} and ";
//   unset($arr['range']);
//  }
//  foreach ($arr as $key => $val) {
//   $sql.= "$key = '$val' and ";
//  }
//  //to fix the last "and";
//  $sql.="1=1 ";
//  $bool = MIL::doQuery($sql, MYSQL_ASSOC);
//  return $bool;
// }
//
// public function buildInsertSql() {
//  $sql = "";
//  $attrName = "";
//  $attrVal = "";
//  foreach ($this->attrArr as $key => $val) {
//   $attrName.=',' . $key . '';
//   if ($val != null) {
//    $attrVal .= ',"' . $val . '"';
//   } else {
//    //if val = null, do not add ""
//    $attrVal .= ",null";
//   }
//  }
//  $attrName = '(' . substr($attrName, 1) . ')';
//  $attrVal = '(' . substr($attrVal, 1) . ')';
//  $sql = 'insert into ' . $this->tableName . ' ' . $attrName . ' values ' . $attrVal;
//  return $sql;
// }
//
// public function insertToDb() {
//  $sql = $this->buildInsertSql();
//  $result = MIL::doQuery($sql, MIL_DB_INSERT); // may throw db exception
//  return $result;
// }
//
// public function commitToDb() {
//  $sql = "delete from {$this->tableName} where id='{$this->getProperty('id')}'";
//  $result = MIL::doQuery($sql, MIL_DB_INSERT);
//  $sql = $this->buildInsertSql();
//  $result = MIL::doQuery($sql, MIL_DB_INSERT);
// }
//
// public function getId() {
//  return $this->id;
// }
//
// public function getParentId() {
//  return $this->parentId;
// }
//
// public function delete() {
//  if ($this->id == "" && array_key_exists('id', $this->attrArr)) {
//   $this->id = $this->attrArr['id'];
//  }
//  $sql = 'delete from ' . $this->tableName .
//          ' where id = "' . $this->id . '"';
//  $result = MIL::doQuery($sql, MIL_DB_INSERT);
//  return $result;
// }
//
// public function getStatus() {
//  return 'pending';
// }
//
// public function getProperty($paraName) {
//  if (array_key_exists($paraName, $this->attrArr)) {
//   return $this->attrArr[$paraName];
//  } else {
//   return 'NA';
//  }
// }
//
// public function updateProperty($paraName, $val) {
//  $this->attrArr[$paraName] = $val;
//  $sql = "update {$this->tableName} set $paraName = '$val' where id = '{$this->getProperty('id')}'";
//  $result = MIL::doQuery($sql, MIL_DB_INSERT);
//  return $result;
// }
//
//}
//
//class Stack {
//
// protected $stack = array();
//
// public function __construct($para_arr) {
//  $this->stack = $para_arr;
// }
//
// public function pop() {
//  $eval = array_pop($this->stack);
//  return $eval;
// }
//
// public function push($eval) {
//  array_push($this->stack, $eval);
// }
//
// public function peek() {
//  //return false if stack empty
//  return end($this->stack);
// }
//
// public function size() {
//  return count($this->stack);
// }
//
// public function switchTop($eval) {
//  $this->pop();
//  $this->push($eval);
// }
//
//}
//
//?>
