<?php

require_once dirname(__FILE__) . '/../constant.php';
require_once dirname(__FILE__) . '/../mil_init.php';

class Node {

    protected $attrArr;
    protected $tableName;
    protected $idName = 'id';

    //protected $mode; // ADD?EDIT?VIEW?

    public function generateId() {
        return uniqid(); //for only one server 
    }

    public function exists($arr) {
        $sql = "select * from $this->tableName where ";
        if (array_key_exists('range', $arr)) {
            $range = $arr['range'];
            $sql .= "{$range['name']} between {$range['min']} and {$range['max']} and ";
            unset($arr['range']);
        }
        foreach ($arr as $key => $val) {
            $sql.= "$key = '$val' and ";
        }
        //to fix the last "and";
        $sql.="1=1 ";
        $bool = MIL::doQuery($sql, MYSQL_ASSOC);
        return $bool;
    }

    public function buildInsertSql() {
        $sql = "";
        $attrName = "";
        $attrVal = "";
        foreach ($this->attrArr as $key => $val) {
            $attrName.=',' . $key . '';
            if ($val != null) {
                $val = addslashes($val);
                $attrVal .= ',"' . $val . '"';
            } else {
                //if val = null, do not add ""
                $attrVal .= ",null";
            }
        }
        $attrName = '(' . substr($attrName, 1) . ')';
        $attrVal = '(' . substr($attrVal, 1) . ')';
        $sql = 'insert into ' . $this->tableName . ' ' . $attrName . ' values ' . $attrVal;
        return $sql;
    }

    public function insertToDb() {
        $sql = $this->buildInsertSql();
        $result = MIL::doQuery($sql, MIL_DB_INSERT); // may throw db exception
        return $result;
    }

    public function commitToDb() {

        $sql = "select * from {$this->tableName} where id='{$this->getProperty($this->idName)}'";
        $result = MIL::doQuery($sql, MYSQL_ASSOC);
        if ($result) {
            return $this->updateAllProperties();
        } else {
            return $this->insertToDb();
        }
    }

    public function getID() {
        return $this->getProperty('id');
    }

    public function delete() {
        if ($this->id == "" && array_key_exists('id', $this->attrArr)) {
            $this->id = $this->attrArr['id'];
        }
        $sql = "delete from {$this->tableName}
              where {$this->idName} = '{$this->getProperty($this->idName)}'";
        $result = MIL::doQuery($sql, MIL_DB_INSERT);
        return $result;
    }

    public function getProperty($paraName) {
        if (array_key_exists($paraName, $this->attrArr)
                && !is_null($this->attrArr[$paraName])) {
            return $this->attrArr[$paraName];
        } else {
            return '';
        }
    }

    public function getProperties() {
        return $this->attrArr;
    }

    public function setProperty($paraName, $value) {
        if (array_key_exists($paraName, $this->attrArr)) {
            $this->attrArr[$paraName] = $value;
        } else {
            $class_name = get_class($this);
            echo "function Node->setProperty: paraName($paraName) is not in Class($class_name)->attrArr ";
            exit;
        }
        return true;
    }

    public function updateProperty($paraName, $paraVal) {
        $idName = $this->idName;

        if (is_array($paraName) && is_array($paraVal)) {
            $lengthOfParaName = count($paraName);
            if ($lengthOfParaName != count($paraVal)) {
                echo 'Node->updateProperty(): The size of array $paraName and $paraVal should be same';
            }
            $sql = "update {$this->tableName} set ";
            for ($i = 0; $i < $lengthOfParaName; $i++) {
                $name = $paraName[$i];
                $val = $paraVal[$i];
                $this->setProperty($name, $val);
                $val = addslashes($val);
                $sql .= "$name = '$val' ";
                if ($i != ($lengthOfParaName - 1)) {
                    $sql .= ',';
                }
            }

            $sql .= "where  $idName = '{$this->getProperty($idName)}'";
            $result = MIL::doQuery($sql, MIL_DB_INSERT);
        } else if (is_string($paraName) && is_string($paraVal)) {
            $this->setProperty($paraName, $paraVal);
            $sql = "update {$this->tableName} set $paraName = '$paraVal' where $idName = '{$this->getProperty($idName)}'";
            $result = MIL::doQuery($sql, MIL_DB_INSERT);
        } else {
            $result = false;
            echo 'Node->updateProperty():Invalid $paraName or $paraVal type';
            exit;
        }
        return $result;
    }

    public function updateAllProperties() {
        $idName = $this->idName;
        $length = count($this->attrArr);
        $sql = "update {$this->tableName} set ";
        $i = 0;
        foreach ($this->attrArr as $name => $val) {
   
            $this->setProperty($name, $val);
            $val = ($val == NULL ? 'NULL' : "'" . addslashes($val) . "'");
            $sql .= "$name = $val ";

            if ($i != ($length - 1)) {
                $sql .= ',';
            }
            $i++;
        }
        $sql .= "where  $idName = '{$this->getProperty($idName)}'";
        $result = MIL::doQuery($sql, MIL_DB_INSERT);
        return $result;
    }

    /*
     * Update  all the properties in $paraArr  DB table and $this object
     */

    public function updateProperties($paraArr) {
        $idName = $this->idName;

        $sql = "update {$this->tableName} set ";
        $i = 0;
        foreach ($paraArr as $name => $val) {
            //check whether is valid field
            if (array_key_exists($name, $this->attrArr)) {
                $this->setProperty($name, $val);
                //for database
                $val = addslashes($val);
                $sql .= "$name = '$val' ";
                $sql .= ',';
            }
            $i++;
        }
        //remove the last comma
        $sql = substr($sql, 0, -1);
        $sql .= "where  $idName = '{$this->getProperty($idName)}'";
        $result = MIL::doQuery($sql, MIL_DB_INSERT);
        return $result;
    }

}

class Stack {

    protected $stack = array();

    public function __construct($para_arr) {
        $this->stack = $para_arr;
    }

    public function pop() {
        $eval = array_pop($this->stack);
        return $eval;
    }

    public function push($eval) {
        array_push($this->stack, $eval);
    }

    public function peek() {
        //return false if stack empty
        return end($this->stack);
    }

    public function top($index) {
        $size = count($this->stack);
        if ($size < $index) {
            return false;
        } else {
            $item = $this->stack[$size - $index];
            return $item;
        }
    }

    public function size() {
        return count($this->stack);
    }

    public function switchTop($eval) {
        $this->pop();
        $this->push($eval);
    }

}

?>
