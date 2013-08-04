<?
$mysqlConfig = json_decode(file_get_contents(__DIR__ . '/backup.json'), true);

$mysqlConfig['destination'] = __DIR__ . '/' . $mysqlConfig['destination'];
shell_exec('rm -rf "' . $mysqlConfig['destination'] . '"');
mkdir($mysqlConfig['destination']);

$excludeTables = '';
foreach ($mysqlConfig['excludeTables'] as $table) {
	$excludeTables .= ' --ignore-table=' . $table;
}

$mysqli = new mysqli("localhost", $mysqlConfig['username'], $mysqlConfig['password']);
$result = $mysqli->query("SHOW DATABASES");
while ($row = $result->fetch_assoc()) {
	$database = $row['Database'];
	if (in_array($database, $mysqlConfig['excludeDatabases'])) {
		continue;
	}
	
	echo "Backing up MySQL database \"$database\".\n";
	$cmd = "mysqldump -u$mysqlConfig[username] -p$mysqlConfig[password] $excludeTables $database > '$mysqlConfig[destination]/$database.sql'";
	shell_exec($cmd);
}
