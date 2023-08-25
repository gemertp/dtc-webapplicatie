<?php

$connection = "";

try {
	$dbserver = "10.20.0.12";
	$dbport = "3306";
	$dbname = "datacenter";
	$dbuser = "datacenter";
	$dbpassword = "Deltion123!";

	$connection = new PDO (
		"mysql:host=$dbserver; dbname=$dbname", 
		$dbuser, 
		$dbpassword,
		[
			PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
			PDO::MYSQL_ATTR_SSL_KEY => '/var/local/datacenter/client-key.pem',
			PDO::MYSQL_ATTR_SSL_CERT => '/var/local/datacenter/client-cert.pem',
			PDO::MYSQL_ATTR_SSL_CA => '/var/local/datacenter/ca.pem',
		]
	);
} catch(PDOExeption $e) {
	print 'Connection failed: ' . $e->getMessage();
}

$sql_query = "SELECT servernaam, osnaam, ipbeheer, ipdatabase, ipnfs, ipiscsi, ipweb, ipnat FROM servers s JOIN oses o on s.osid = o.osid;";
$statement = $connection->prepare($sql_query);
$statement->execute();

$servers = $statement->fetchAll();

$table_rows = "";
foreach ( $servers as $server ) {
	$table_rows .= '
		<tr>
		<td>' . $server['servernaam']. '</td>
		<td>' . $server['osnaam']    . '</td>
		<td>' . $server['ipbeheer']  . '</td>
		<td>' . $server['ipdatabase']. '</td>
		<td>' . $server['ipnfs']     . '</td>
		<td>' . $server['ipiscsi']   . '</td>
		<td>' . $server['ipweb']     . '</td>
		<td>' . $server['ipnat']     . '</td>
		</tr>';
}

echo '<!DOCTYPE html>

<html>
<head>
	<title>Datacenter webapplicatie</title>
	<link rel="stylesheet" href="/css/opmaak.css">
	<link rel="stylesheet" href="/css/datacenter.css">
</head>

<body>
<table>
<thead>
<tr>
	<td>Servernaam</td>
	<td>Osnaam</td>
	<td>Beheer</td>
	<td>Database</td>
	<td>NFS</td>
	<td>iSCSI</td>
	<td>Web</td>
	<td>NAT</td>
</tr>
</thead>
<tbody>
' . $table_rows . '
</tbody>
</table>
<body>
</html>
';
?>


