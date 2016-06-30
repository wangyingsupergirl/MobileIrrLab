<?php
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE & ~E_DEPRECATED);
require_once dirname(__FILE__).'/../../mil_init.php';
require_once dirname(__FILE__).'/../Node.php';

class Member extends Node{
     protected $attrArr = array(
         'username'=> ''
         ,'password'=> ''
     );
     protected $idName = 'mem_id';
    //Create Member Instance by role ID
    public static function createMember($roleID){
        if($roleID == EMPLOYEE_ROLE){
            $member = new Employee();
        }else if($roleID == CONTRACTOR_ROLE){
            $member = new Contractor();
        }else if($roleID == ADMIN_ROLE){
            $member = new Administrator();
        }else if($roleID == PARTNER_ROLE){
           $member = new Partner();
        }else if($roleID==GUEST_ROLE){
          $member = new Guest();
        }else{
            echo "invalid role id $roleID";
            exit;
        }
        return $member;
    }
    public static function createMemberFromDB($arr){
        if(array_key_exists('role',$arr)){
            $role = $arr['role'];
        }else{
            echo 'role missed';
            exit;
        }
        $member = self::createMember($role);
        $member->init($arr);
        return $member;

    }
    public static function createMemberFromUserInput($arr){
        if(array_key_exists('role',$arr)){
            $role = $arr['role'];
        }else{
            echo 'role missed';
            exit;
        }
        $member = self::createMember($role);
        $member->init($arr);
        return $member;

    }
    /*
     * Return false, if username password don't exists.
     */
    public function getDisplayName($name){
        if($name=='role'){
            $val = $this->getProperty($name);
            if($val==EMPLOYEE_ROLE){
                return 'Employee';
            }else if($val==CONTRACTOR_ROLE){
                return 'Contractor';
            }else if($val == PARTNER_ROLE){
                return 'Partner';
            }else if($val==GUEST_ROLE){
                return 'Guest';
            }else if($val==ADMIN_ROLE){
                return 'Admin';
            }else{
                echo "invalid role id $val";
                exit;
            }
        }
    }
    public function verifyUsernamePwd($username, $pwd){
        $member = false;
        $encodePwd = self::encodePwd($pwd);
        $username = addslashes($username);
        $sql = "select * from member where username='$username' and password='$encodePwd'";
        $memsArr = MIL::doQuery($sql, MYSQL_ASSOC);
        if ($memsArr!=false){
           $memArr = $memsArr[0];
           $role = $memArr['role'];
           $member = self::createMember($role);
           $member->init($memArr);
        }
        return $member;
    
    }
    //Encode password before/after initialize an object
     public static function encodePwd($pwd){
        return md5($pwd);
    }
    //Initialize Object
    public function init($arr){
        foreach($this->attrArr as $name => $val){
            if(array_key_exists($name, $arr)){
                $this->setProperty($name,  $arr[$name]);
            }
        }
        //When the member record does not exist. eg: member sign up
        if(!$this->attrArr['mem_id']){
          $this->attrArr['mem_id'] = $this->generateId();
          $this->attrArr['status'] = 'pending';
          if($this->attrArr['password']){
            $this->attrArr['password'] = Member::encodePwd($this->attrArr['password']);
          }
        }

        $this->tableName = 'member';
    }
   
    /*
     * Check whether username has been used in DB
     * or check member exists or not in DB by unique username
     * if exit return the member else return false;
     */
    public function exists(){
        $username = $this->getProperty('username');
        if($username=='NA'){
            return 'Username can not be blank.';
        }else{
             $sql = "select * from member where username = '$username'";
             $rtn = MIL::doQuery($sql, MYSQLI_ASSOC);
             if($rtn){
                 if(count($rtn)){
                     $arr = $rtn[0];
                     $member = self::createMemberFromDB($arr);
                     $rtn = $member;
                 }
             }
             return $rtn;
        }
      }

      /*
       * Used by /signup/form_component/mil_labs_checkbox.php
       */
      public function getPropertyByType($paraName, $type){
        $paraValue = $this->getProperty($paraName);
            if($paraName=='labs_id'
                    ||$paraName=='funded_labs_id'
                    ||$paraName=='inkind_labs_id'
                    ||$paraName=='reports_id'){
                if($type=='array'){
                    if(is_array($paraValue)){
                     return $paraValue;
                    }else if(is_string($paraValue)){
                      $paraValue = explode(',', $paraValue);
                    }else{
                     return null;
                    }
                   
                }
            }
        return $paraValue;
      }
      
     
      public function approve($arr){
        if(array_key_exists('admin_comments',$arr)){
            $this->updateProperty(array('admin_comments','status','approved_time'),array($arr['admin_comments'],'approved',date('Y-m-d H:i:s')));
            return "{$this->getProperty('first_name')} {$this->getProperty('last_name')}'s membership has been approved!";

        }else{
            echo "Input field named comment is missing in member review page role id = {$this->getProperty('role')}";
            exit;
        }
       }
      public function disapprove($arr){
        if(array_key_exists('admin_comments',$arr)){
            
            $this->updateProperty(array('admin_comments','status','approved_time'),array($arr['admin_comments'],'disapproved',date('Y-m-d H:i:s')));
            return "{$this->getProperty('first_name')} {$this->getProperty('last_name')}'s membership has been disapproved!";


        }else{
            echo "Input field named comment is missing in member review page role id = {$this->getProperty('role')}";
            exit;
        }
       }
       public function getID(){
           return $this->getProperty('mem_id');
       }
       //return msg, because this function response to ajax
       public function changeUsername($newUsername){
           if($newUsername==$this->getProperty('username')){
               return 'The username you entered is already your username';
           }else{
               $this->setProperty('username',$newUsername);
           }
           $msg = $this->verifyProperty('username');
           if($msg===true){
               
           }else{
               return $msg;
           }
           if($this->exists()){
               return'The username you entered is not available any more, try anther one';
           }

           $rtn = $this->updateProperty('username',$newUsername);
           return true;
        }
        public function changePwd($old, $new){
            $bool = $this->verifyUsernamePwd($this->getProperty('username'), $old);
            if($bool){
                $this->setProperty('password',$new);
                $msg = $this->verifyProperty('password');
                if($msg===true){
                    $this->updateProperty('password',self::encodePwd($new));
                    return true;
                }else{
                    return $msg;
                }
            }else{
                return 'Password entered did not match record';
            }
        }
       public function verifyProperty($paraName){
            $msg = '';
            $val = $this->getProperty($paraName);
            if($paraName=='username'){
                $is_valid = preg_match('/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/', $val);
                if(!$is_valid){
                    $msg .= "Username must be valid email address.";
                }
            }else if($paraName=='password'){
                $is_valid = preg_match('/^\w*(?=\w*\d)(?=\w*[a-z])(?=\w*[A-Z])\w*$/', $val,$match);
                if(!$is_valid){
                    $msg .= "Password must have at least one number, one letter lower case and one letter upper case";
                }
            }
            if($msg===''){
               return true;
           }else{
               return $msg;
           }
       }
       public function verifyProperties($paraNames){
           $msg ='';
           foreach($paraNames as $paraName){
                $rtn = $this->verifyProperty($paraName);
                if($rtn===true){
                    
                }else{
                    $msg .= $rtn;
                }
           }
           if($msg===''){
               return true;
           }else{
               return $msg;
           }
           

       }
  

      
}


