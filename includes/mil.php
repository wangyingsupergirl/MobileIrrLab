<?php
require_once 'db/connection.php';
class MIL{
  public static function doQuery($sql,$fetch_mode){
       $connection = new Connection(MIL_SERVER,MIL_DB, MIL_DB_USERNAME, MIL_DB_PASSWORD);
       $connection->newConnection();
       $result = $connection->doQuery($sql,$fetch_mode);
       return $result;

   }
   public static function createView($sql){
       
       $connection = new Connection(MIL_SERVER,MIL_DB, MIL_DB_USERNAME, MIL_DB_PASSWORD);
       $connection->newConnection();
       foreach($sql as $eachSql){
           
           $connection->createView($eachSql);
           
       }   
   }
   public static function dropView($sql){
       
       
       $connection = new Connection(MIL_SERVER,MIL_DB, MIL_DB_USERNAME, MIL_DB_PASSWORD);
       $connection->newConnection();
       //foreach($sql as $eachSql){
           
       $connection->dropView($sql);
           
       //}
       
   }
	
}