<?php
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE & ~E_DEPRECATED);
require_once 'resultset.php';

class Connection{
	public $db_connection = null;        // Database connection string
	public $db_server = null;            // Database server
	public $db_database = null;          // The database being connected to
	public $db_username = null;          // The database username
	public $db_password = null;          // The database password
	public $CONNECTED = false;           // Determines if connection is established
	

	public function __construct($db_server,$db_database,$db_username, $db_password){
		$this->db_server = $db_server;
		$this->db_database = $db_database;
		$this->db_username = $db_username;
		$this->db_password = $db_password;
		 
	}
	public function doQuery($sql, $fetch_mode){
		$result_id = mysql_query($sql);
		if (mysql_errno()) {
  		     // throw new Exception("MySQL error ".mysql_errno().": ".mysql_error()."\n<br>When executing:<br>\n$sql\n<br>") ;
                     echo "MySQL error ".mysql_errno().": ".mysql_error()."\n<br>When executing:<br>\n$sql\n<br>";
                     exit;
                }            
		if($fetch_mode == MIL_DB_INSERT){
                    $result = $result_id;
		}else{
                    $resultset = new ResultSet($result_id,  MYSQL_ASSOC);
                    $result = $resultset->getArray();
		}
		$this->close();
		return $result;
		
	}
        public function createView($sql1){
            
            mysql_query($sql1);
            if (mysql_errno()) {
  		     // throw new Exception("MySQL error ".mysql_errno().": ".mysql_error()."\n<br>When executing:<br>\n$sql\n<br>") ;

                     echo "MySQL error ".mysql_errno().": ".mysql_error()."\n<br>When executing:<br>\n$sql1\n<br>";
                     echo "MySQL error ".mysql_errno().": ".mysql_error()."\n<br>When executing:<br>\n$sql\n<br>";

                     exit;
            }     
        }
        public function dropView($sql){
            mysql_query($sql);
            if (mysql_errno()) {
  		     // throw new Exception("MySQL error ".mysql_errno().": ".mysql_error()."\n<br>When executing:<br>\n$sql\n<br>") ;

                     echo "MySQL error ".mysql_errno().": ".mysql_error()."\n<br>When executing:<br>\n$sql1\n<br>";
                     echo "MySQL error ".mysql_errno().": ".mysql_error()."\n<br>When executing:<br>\n$sql\n<br>";

                     exit;
            }  
            
        }
	/** NewConnection Method
	 * This method establishes a new connection to the database. */
	public function newConnection()
	{

		// Attempt connection
		try
		{
			// Create connection to MYSQL database
			// Fourth true parameter will allow for multiple connections to be made
			$this->db_connection = mysql_connect ($this->db_server, $this->db_username, $this->db_password, true);
			//echo "{$this->db_connection} = mysql_connect ({$this->db_server}, {$this->db_username}, {$this->db_password}, true);";
			//exit;
			if (!$this->db_connection)
			{
				throw new Exception('MySQL Connection Database Error: ' . mysql_error());
			}
			else
			{
				$this->CONNECTED = true;
			}
			mysql_select_db ($this->db_database);
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
		}
	}

	 

	/** Close Method
	 * This method closes the connection to the MySQL Database */
	public function close()
	{

		if ($this->CONNECTED)
		{
			mysql_close($this->db_connection);
			$this->CONNECTED = false;
		}
		else
		{
			return "Error: No connection has been established to the database. Cannot close connection.";
		}
	}

}
?>