<?php
class ResultSet{
	public $result_id;
	public $fetch_mode;

	public function ResultSet($result_id,$fetch_mode){
		$this->result_id = $result_id;
		$this->fetch_mode = $fetch_mode;
	}

	function close(){
		mysql_free_result( $this->result_id );
		$this->result_id = false;
	}
	function getArray(){
		//if query return nothing(No records satify query's conditions), return false;
		$results = false;
		$cnt = 0;
		while($arr = mysql_fetch_array($this->result_id, $this->fetch_mode)){
			$results[$cnt]=$arr;
			$cnt++;
		}
		//release resource
		$this->close();
		return $results; 
	}
}