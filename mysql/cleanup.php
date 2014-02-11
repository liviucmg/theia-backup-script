<?php

$file = __DIR__ . '/backup.json';
if (!file_exists($file)) {
	return;
}
$mysqlConfig = json_decode(file_get_contents($file), true);

$mysqlConfig['destination'] = __DIR__ . '/' . $mysqlConfig['destination'];
shell_exec('rm -rf "' . $mysqlConfig['destination'] . '"');