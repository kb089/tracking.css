<?php
/* ==============================================
 🕵️‍♂️ STEALTH PHP TRACKING SCRIPT (WITH CACHE BUSTING)
 - Logs anonymous page visits via CSS.
 - Does NOT store personal data (fully anonymous).
 - Returns a transparent image to complete the request.
============================================= */

// 🔒 Prevent caching
header('Content-Type: image/gif');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// 📌 Collect visitor data
$ip = $_SERVER['REMOTE_ADDR'];
$anonIP = preg_replace('/\\..+$/', '.0', $ip); // Anonymized IP
$url = $_GET['url'] ?? 'Unknown';
$referrer = $_SERVER['HTTP_REFERER'] ?? 'Direct';
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
$timestamp = date('Y-m-d H:i:s');

// ✏️ Format log entry
$logEntry = "$timestamp | IP: $anonIP | URL: $url | Referrer: $referrer | UA: $userAgent" . PHP_EOL;

// ✍️ Write to log file
file_put_contents('tracking.log', $logEntry, FILE_APPEND | LOCK_EX);

// 🎨 Output transparent GIF (43 bytes, base64-encoded)
echo base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
exit;
?>
