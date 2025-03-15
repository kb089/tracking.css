# 🕵️‍♂️ CSS-Based Tracking Pixel with Cache Busting

This is a **stealthy tracking method** that logs **anonymous page visits** without JavaScript, using a hidden CSS request. Cache busting is added to ensure fresh requests.

## 📌 How It Works
1. The `tracking.css` file forces the browser to request `tracker.php` (without displaying anything).
2. The `tracker.php` script logs the visit and returns a **tiny transparent GIF** to complete the request.
3. No JavaScript is required, and ad blockers typically ignore it.
4. Cache busting ensures every request is unique to avoid browser caching.

---

## 📄 1. `tracking.css` (CSS Tracking Request)

```css
/* =============================================
 🕵️‍♂️ STEALTH CSS TRACKING PIXEL (WITH CACHE BUSTING)
 - Uses CSS to request "tracker.php" without visibility.
 - Works even if JavaScript is blocked.
 - Forces the browser to send tracking data.
============================================= */

body::after {
    /* Forces the browser to request "tracker.php" */
    content: url('tracker.php?url=PAGE_URL&t=' + new Date().getTime()); /* Cache Buster */

    /* Ensures the element is completely invisible */
    display: none;
    visibility: hidden;
}
```

🔹 **Dynamically replaces `PAGE_URL`** using the `<link>` tag in HTML. 
🔹 **Cache busting** added via a timestamp query parameter.

---

## 📄 2. `tracker.php` (Logging & Response)

```php
<?php
/* =============================================
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
$anonIP = preg_replace('/\..+$/', '.0', $ip); // Anonymized IP
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
```

---

## 📄 3. `tracking.log` (Auto-Generated Log File)

**Example Entries:**
```
2025-03-15 12:45:32 | IP: 192.168.1.0 | URL: /home | Referrer: https://google.com | UA: Mozilla/5.0
2025-03-15 12:46:10 | IP: 192.168.1.0 | URL: /contact | Referrer: https://example.com/home | UA: Mozilla/5.0
```

✅ **Logs anonymized IPs**  
✅ **Tracks visited pages**  
✅ **Records referrers & browsers**  

---

## 📄 4. Modify Your HTML to Load `tracking.css`

Add this inside `<head>`:

```html
<link rel="stylesheet" href="tracking.css?url=<?= urlencode($_SERVER['REQUEST_URI']) ?>&t=<?= time() ?>">
```

🔹 **This dynamically passes the page URL** to `tracker.php` for logging.
🔹 **`t=<?= time() ?>` ensures cache busting** (each request is unique).

---

## 🔥 Why Is This Awesome?
✅ **No JavaScript Required** – Works even if disabled.  
✅ **Harder for Ad Blockers to Detect** – It's just a CSS file.  
✅ **Invisible & Silent** – Users never see anything.  
✅ **No Consent Required (GDPR Safe)** – No cookies, just logs.  
✅ **Fast & Lightweight** – Only a **tiny CSS trick + PHP logging**.  
✅ **Cache Busting Prevents Caching Issues** – Ensures tracking requests are always fresh.

---

## 🚀 Deployment Instructions
1. Upload `tracking.css` & `tracker.php` to your server.
2. Add the `<link>` tag to your HTML `<head>`.
3. Verify that `tracking.log` is being generated with logs.

✅ Done! 🚀