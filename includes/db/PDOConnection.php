<?php
class PDOConnection{
        public $connection  = null;
        public function __construct($dbType, $hostName, $dbName, $username, $password) {
            $this->dbType = $dbType;
            $this->hostName = $hostName;
            $this->dbName = $dbName;
            $this->username = $username;
            $this->password = $password;
            $this->dsn = "{$this->dbType}:host={$this->hostName};dbname={$this->dbName}";
            //$db = new PDO('mysql:host=localhost;dbname=<SOMEDB>', '<USERNAME>', 'PASSWORD');
         }
        /**
	 * Non-persistent database connection
	 *
	 * @param	bool
	 * @return	object
	 */
	public function newConnection($persistent = FALSE){
		$this->options[PDO::ATTR_PERSISTENT] = $persistent;
                    try{
			$this->connection = new PDO($this->dsn, $this->username, $this->password, $this->options);
                                return $this->connection;
		}catch (PDOException $e){
			echo "Exception: ".$e->getMessage()."<br />";
                               echo "dsn: '".$this->dsn."'<br />";
			return FALSE;
		}
	}
}
?>
