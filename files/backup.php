<?php

// Get arguments.
$defaults = [
	'--debug' => false
];
$arguments = [];
foreach ($argv as $a) {
	$arguments[$a] = true;
}

// Get configuration file.
$config = json_decode(file_get_contents(__DIR__ . '/backup.json'), true);

// Get credentials.
$credentialsCmd = "";
if (
	isset($config['AWS_ACCESS_KEY_ID']) &&
	isset($config['AWS_SECRET_ACCESS_KEY'])
) {
	$credentialsCmd .= "
		export AWS_ACCESS_KEY_ID='$config[AWS_ACCESS_KEY_ID]'
		export AWS_SECRET_ACCESS_KEY='$config[AWS_SECRET_ACCESS_KEY]'
	";
}

if (isset($config['signaturePassphrase'])) {
	$credentialsCmd .= "
		export SIGN_PASSPHRASE='$config[signaturePassphrase]'
	";
}

if (isset($config['passphrase'])) {
	$credentialsCmd .= "
		export PASSPHRASE='$config[passphrase]'
	";
}

$encryptionKey = $config['encryptionKey'];
$signatureKey = $config['signatureKey'];

$defaults = [
	'src' => '',
	'dest' => '',
	'removeOlderThan' => null
];

foreach ($config['backups'] as $backup) {	
	$backup = array_merge($defaults, $backup);

	echo "Backup for \"" . $backup['src'] . "\" has started.\n";
	
	// Partial or full backup
	$full = '';
	if (date('j') == 1) { // Full backup on the first day of the month.
		$full = 'full';
	}	

	// Get additional options
	$additionalOptions = [];
	if (isset($backup['sshOptions'])) {
		$additionalOptions[] = "--ssh-options=\"" . $backup['sshOptions'] . "\"";
	}
	$additionalOptions = implode(' ', $additionalOptions);;

	// Remove old backups.
	if ($backup['removeOlderThan']) {
		echo "- Removing old backups.\n";
		$cmd = "
			$credentialsCmd
			duplicity \
				remove-older-than " . escapeshellarg($backup['removeOlderThan']) . " \
				--force \
				$additionalOptions \
				" . escapeshellarg($backup['dest']) . " \
				2>&1
		";
		echo shell_exec($cmd);
	}
	
	// Set exclusions
	$exclude = [];
	if (array_key_exists('exclude', $backup)) {
		foreach ($backup['exclude'] as $e) {
			$exclude[] = '--exclude ' . escapeshellarg($e);
		}
	}
	$exclude = implode(' ', $exclude);
	
	// Backup files
	echo "- Backing up to \"" . $backup['dest'] . "\".\n";
	$cmd = "
		$credentialsCmd
		duplicity \
			$full \
			$exclude \
			--encrypt-key=$encryptionKey \
			--sign-key=$signatureKey \
			--volsize=250 \
			--s3-use-new-style \
			$additionalOptions \
			" . escapeshellarg($backup['src']) . " " . escapeshellarg($backup['dest']) . " \
			2>&1
	";
	if ($arguments['--debug']) {
		passthru($cmd);
	} else {
		shell_exec($cmd);
	}
}
