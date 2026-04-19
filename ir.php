<?php
// 1. Whitelist of allowed remotes and commands
// Populate these from your lircd.conf remote names and keys
$allowed_remotes = ['TV', 'AnyTV', 'Receiver'];
$allowed_commands = ['KEY_POWER', 'KEY_VOLUMEUP', 'KEY_VOLUMEDOWN', 'KEY_MUTE'];

// 2. Reject missing parameters
if (!isset($_GET['remote']) || !isset($_GET['command'])) {
    http_response_code(400);
    die("Missing parameters");
}

$remote  = $_GET['remote'];
$command = $_GET['command'];

// 3. Whitelist check
if (!in_array($remote, $allowed_remotes, true)) {
    http_response_code(400);
    die("Invalid remote");
}

if (!in_array($command, $allowed_commands, true)) {
    http_response_code(400);
    die("Invalid command");
}

// 4. escapeshellarg as an extra layer even after whitelisting
$safe_remote  = escapeshellarg($remote);
$safe_command = escapeshellarg($command);

// 5. Full path to irsend, don't rely on $PATH
exec("/usr/bin/irsend SEND_ONCE $safe_remote $safe_command", $output, $return_code);

if ($return_code !== 0) {
    http_response_code(500);
    die("irsend failed");
}

echo "OK";
?>