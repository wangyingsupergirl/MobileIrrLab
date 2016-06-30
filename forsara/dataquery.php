<?php
require_once dirname(__FILE__) . '/../includes/utility.php';
require_once dirname(__FILE__) . '/../includes/input/package/evaluation/FollowIrrSys.php';
require_once dirname(__FILE__) . '/../includes/input/package/evaluation/InitIrrSys.php';
require_once dirname(__FILE__) . '/../includes/input/package/evaluation/FollowFirm.php';
require_once dirname(__FILE__) . '/../includes/input/package/evaluation/InitFirm.php';

$sql = "select *
     from evaluation 
     where eval_yr  = 2013
           and eval_month >= 7
           and eval_month <=  9
           and status='approved' 
     order by eval_month";
/*Camilo's special requirement 6/7/2013*/
/*$sql = "select *
     from evaluation 
     where (eval_yr  = 2012 
           and eval_month >= 7) or (eval_yr = 2013 and eval_month < 7)
    order by eval_yr, eval_month";*/
$evals_arr = MIL::doQuery($sql, MYSQL_ASSOC);
if ($evals_arr == false) {//no eval list
 $evals[$mil_id] = array();
} else {
 foreach ($evals_arr as $key => $arr) {
  $eval = Evaluation::createEval('from_db', $arr);
  $eval->setLastModifiedTime($arr['last_modified_time']);
  $id = $eval->getProperty('id');
  $evals[$id] = $eval;
 }
}
foreach ($evals as $id => $eval) {
 $results[$id]['id'] = $eval->getProperty('id');
 $results[$id]['county_id'] = $eval->getProperty('county_id');
 $results[$id]['acres'] = $eval->getProperty('acre');
 $results[$id]['pws'] = Utility::getMillionGallonNum($eval->getPWS());
 $results[$id]['aws'] =  Utility::getMillionGallonNum($eval->getAWS());
 


}
$is1stRow = TRUE; 
$out = array2CSV($results, $is1stRow);
//save2File($out, "counties.csv");
save2File($out, "evaluations.csv");
/*
$years = array('2011');
$stns = Fawn::getStations();
$type = '15mins';
foreach($years as $year){
//free $out memory
$out = '';
/*if( $year < 2005 ){
$flag = 'mssql';
}else{
$flag = 'mysql';
}
if($flag=='mssql'){
$souceDb = getZendDb('newms');
}else{
$souceDb = getZendDb('test');
}*/
/*
$out = getYearlyCSV($year,$stns,$type);
save2File($out,"{$year}_{$type}.csv");
$result = create_zip("{$year}_{$type}.csv","{$year}_{$type}.csv.zip",true);
}

*/
/* creates a compressed zip file */
function create_zip($file ='' ,$destination = '',$overwrite = false) {
  //if the zip file already exists and overwrite is false, return false
  if(file_exists($destination) && !$overwrite) { return false; }
  //create the archive
   $zip = new ZipArchive;
   if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
      return false;
   }
    //add the files
    $bool = $zip->addFile($file);
    //close the zip -- done!
    $zip->close();
    //check to make sure the file exists
    return file_exists($destination);
}
 


function getYearlyCSV($year,$stns,$type){
$out = '';
//test//$stns= array(array('LocID'=>'110','active'=>'Y'));
$is1stRow = TRUE; 
foreach($stns as $stn_id => $stn){
	
	if($type == 'hourly'){
		
		//$sql = getHrMysql($year,$stn_id);
		
		$sql = getHrSql($year,$stn_id);
    }else if($type=='15mins'){
        $sql = get15MinsSql($year, $stn_id);
    }
    
	echo "$stn_id, ";
	//echo xdebug_memory_usage()."; ";
	$rs = Fawn::getDb('mssql')->Execute($sql);
if (!$rs) {
    exit;
}
$data = $rs->getArray();
	$out .= array2CSV($data,$is1stRow);
	
	
}
return $out;
}

function array2CSV($data,&$is1stRow){
	$csv = '';
	// Field name is set or not.
	$fields_names = '';
	foreach($data as $row){
	    //get field name if this is first row.
	    
		if($is1stRow == TRUE){
		   foreach($row as $key => $val){
		   		$fields_names .= ",$key";
		   }
		   $fields_names = substr($fields_names,1);
		   $csv = $fields_names."\r\n";
		   $is1stRow = FALSE;
		}
		//convert each item of array to a csv line.
		$csv_line = '';
		foreach($row as $key => $val){
			$csv_line .=  ",$val";
		}
		$csv_line = substr($csv_line,1);
		$csv.= $csv_line."\r\n";
	}
	return $csv;
}

function save2File($data,$filename){
    if (!$handle = fopen($filename, 'w')) {
         echo "Cannot open file ($filename)";
         exit;
    }
    // Write $somecontent to our opened file.
    if (fwrite($handle, $data) === FALSE) {
        echo "Cannot write to file ($filename)";
        exit;
    }

    echo "Success, wrote data to file ($filename)";

    fclose($handle);


}
function getHrSql($year, $stn_id, $start_month = 1, $end_month = 12){
$sql = "
SELECT wx.ID, cast(Year(wx.datetime) as varchar)+'-'+cast(Month(wx.datetime) as varchar)+'-'+cast(day(wx.datetime) as varchar)+' '+ cast(DATEPART(hh,wx.datetime) as varchar) +':00:00' as date,
round(avg(wx.SoilTempAvg),3) as avg_temp_soil_10cm_C,
round(min(wx.SoilTempAvg),3) as min_temp_soil_10cm_C,
round(max(wx.SoilTempAvg),3) as max_temp_soil_10cm_C,
round(avg(wx.AirTemp1),3) as avg_temp_air_60cm_C,
round(min(wx.AirTemp1),3) as min_temp_air_60cm_C,
round(max(wx.AirTemp1),3) as max_temp_air_60cm_C,
round(avg(wx.AirTemp9),3) as avg_temp_air_2m_C,
round(min(wx.AirTemp9),3) as min_temp_air_2m_C,
round(max(wx.AirTemp9),3) as max_temp_air_2m_C,
round(avg(wx.AirTemp15),3) as avg_temp_air_10m_C,
round(min(wx.AirTemp15),3) as min_temp_air_10m_C,
round(max(wx.AirTemp15),3) as max_temp_air_10m_C,
round(avg(wx.RelHumAvg),3) as avg_rh_2m_pct,
round(avg(wx.DewPoint),3) as avg_temp_dp_2m_C,
round(min(wx.DewPoint),3) as min_temp_dp_2m_C,
round(max(wx.DewPoint),3) as max_temp_dp_2m_C,
round(sum(wx.RainFall),3)*0.393 as sum_rain_2m_inches,
round(avg(wx.WindSpeed),3)*0.621 as avg_wind_speed_10m_mph,
round(max(wx.WindSpeed),3)*0.621 as wind_speed_max_10m_mph,
round(avg(wx.WindDir),3) as wind_direction_10m_deg,
round(avg(wx.TotalRad),3) as avg_rfd_2m_wm2,
count(*) as num_observations
FROM weather wx 
where  id = '$stn_id' and Year(wx.Datetime) = $year and Month(wx.Datetime) >= $start_month and Month(wx.Datetime) <= $end_month 
GROUP BY wx.ID, Year(wx.Datetime), Month(wx.Datetime), Day(wx.Datetime),  DATEPART(hh, wx.Datetime)
order by wx.ID asc, Year(wx.Datetime) asc, Month(wx.Datetime) asc, Day(wx.Datetime) asc,  DATEPART(hh, wx.Datetime) asc
";
//$sql = "select * from latest_weather";
return $sql;
}
function getHrMysql($year, $stn_id){
$sql = "
SELECT wx.`ID`, DATE_FORMAT(wx.UTC,'%m/%d/%Y  %h:00:00 %p') as date, 
round(avg(wx.`temp_soil_10cm_C`),3) as avg_temp_soil_10cm_C,
round(min(wx.`temp_soil_10cm_C`),3) as min_temp_soil_10cm_C,
round(max(wx.`temp_soil_10cm_C`),3) as max_temp_soil_10cm_C,
round(avg(`temp_air_60cm_C`),3) as avg_temp_air_60cm_C,
round(min(wx.`temp_air_60cm_C`),3) as min_temp_air_60cm_C,
round(max(wx.`temp_air_60cm_C`),3) as max_temp_air_60cm_C,
round(avg(wx.`temp_air_2m_C`),3) as avg_temp_air_2m_C,
round(min(wx.`temp_air_2m_C`),3) as min_temp_air_2m_C,
round(max(wx.`temp_air_2m_C`),3) as max_temp_air_2m_C,
round(avg(wx.`temp_air_10m_C`),3) as avg_temp_air_10m_C,
round(min(wx.`temp_air_10m_C`),3) as min_temp_air_10m_C,
round(max(wx.`temp_air_10m_C`),3) as max_temp_air_10m_C,
round(avg(wx.`rh_2m_pct`),3) as avg_rh_2m_pct,
round(avg(wx.`temp_dp_2m_C`),3) as avg_temp_dp_2m_C,
round(min(wx.`temp_dp_2m_C`),3) as min_temp_dp_2m_C,
round(max(wx.`temp_dp_2m_C`),3) as max_temp_dp_2m_C,
round(sum(wx.`rain_2m_inches`),3)as sum_rain_2m_inches,
round(avg(wx.`wind_speed_10m_mph`),3) as avg_wind_speed_10m_mph,
round(max(wx.`wind_speed_10m_mph`),3) as wind_speed_max_10m_mph,
round(avg(wx.`wind_direction_10m_deg`),3) as wind_direction_10m_deg,
round(avg(wx.`rfd_2m_wm2`),3) as avg_rfd_2m_wm2,
count(*) as num_observations
FROM wx  where (wx.UTC between '{$year}-01-01 00:00:00' AND '{$year}-12-31 23:59:00') and id = '$stn_id'
GROUP BY wx.ID, Year(wx.UTC), Month(wx.UTC), Day(wx.UTC), Hour(wx.UTC)
order by wx.ID asc, DATE(wx.UTC) asc, Hour(wx.UTC) asc
";
return $sql;
}
function get15MinsSql($year, $stn_id, $start_month = 1, $end_month = 12){
$sql = "
SELECT wx.ID, wx.datetime as date,
round(wx.SoilTempAvg,3),
round(wx.AirTemp1,3),
round(wx.AirTemp9,3),
round(wx.AirTemp15,3),
round(wx.RelHumAvg,3) as rh_2m_pct,
round(wx.DewPoint,3) as temp_dp_2m_C,
round(wx.RainFall,3)*0.393 as rain_2m_inches,
round(wx.WindSpeed,3)*0.621 as wind_speed_10m_mph,
round(wx.WindDir,3) as wind_direction_10m_deg,
round(wx.TotalRad,3) as rfd_2m_wm2
FROM weather wx 
where  id = '$stn_id' and Year(wx.Datetime) = $year and Month(wx.Datetime) >= $start_month and Month(wx.Datetime) <= $end_month 
order by wx.ID asc, wx.Datetime asc
";
//$sql = "select * from latest_weather";
return $sql;
}
function get15MinsMysql($year, $stn_id){
$sql = "SELECT wx.ID, wx.UTC local_eastern_time, 
round(wx.temp_soil_10cm_C,3),
round(wx.temp_air_60cm_C,3),
round(wx.temp_air_2m_C,3),
round(wx.temp_air_10m_C,3),
round(wx.rh_2m_pct,3),
round(wx.temp_dp_2m_C,3),
round(wx.rain_2m_inches,3),
round(wx.wind_speed_10m_mph,3),
round(wx.wind_direction_10m_deg,3),
round(wx.rfd_2m_wm2,3)
FROM wx  where  (wx.UTC between '{$year}-07-01 00:00:00' AND '{$year}-12-31 23:59:00') and id = '260'
order by wx.ID asc, wx.UTC asc
";
return $sql;
}

function push2FTP($file){
$fp = fopen($file, 'r');
$ftp_server = 'agrofawn-prod01.osg.ufl.edu';
$ftp_user_name = 'cnswww-fawn';
$ftp_user_pass ='v6paCREw';
// set up basic connection
$remote_file = '/fawnpub/data/hourly_summaries/'.$file;
$connection = ssh2_connect($ftp_server, 22);
ssh2_auth_password($connection, $ftp_user_name, $ftp_user_pass);
$sftp = ssh2_sftp($connection);
// login with username and password
//$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
$stream = fopen("ssh2.sftp://$sftp{$remote_file}", 'r');
// try to upload $file
if (ftp_fput($conn_id, $remote_file, $fp, FTP_ASCII)) {
	echo "Successfully uploaded $file\n";
} else {
	echo "There was a problem while uploading $file\n";
}
// close the connection and the file handler
ftp_close($conn_id);
fclose($fp);
}

