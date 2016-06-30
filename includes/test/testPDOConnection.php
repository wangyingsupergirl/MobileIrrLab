<?php
require_once "../mil_init_pdo.php";
require_once "../db/PDOConnection.php";
$conn = new PDOConnection("mysql",MIL_SERVER,MIL_DB, MIL_DB_USERNAME, MIL_DB_PASSWORD);
$flag = $conn->newConnection(true);
echo "$conn->dsn<br />";
echo "The connection ".($flag===false?"fail":"success")
?>
