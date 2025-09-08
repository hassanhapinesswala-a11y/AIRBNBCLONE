<?php
require 'db.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT b.*, p.title, p.location FROM bookings b JOIN properties p ON p.id = b.property_id WHERE b.id = :id LIMIT 1");
$stmt->execute([':id'=>$id]);
$book = $stmt->fetch();
if(!$book){
  echo "Booking not found. <a href='index.php'>Home</a>";
  exit;
}
?>
<!doctype html>
<html lang="ur">
<head>
  <meta charset="utf-8">
  <title>Booking Confirmed</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <style>
    body{font-family:Inter,system-ui;background:#071025;color:#eaf6f6;margin:0}
    .wrap{max-width:700px;margin:28px auto;padding:14px}
    .card{background:linear-gradient(180deg,#04101a,#071025);padding:20px;border-radius:12px}
    .cta{margin-top:12px;padding:10px;border-radius:8px;border:0;background:#0ea5a4;color:#032;cursor:pointer}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <h2>Booking Confirmed ✅</h2>
      <p><strong>Property:</strong> <?=htmlspecialchars($book['title'])?></p>
      <p><strong>Location:</strong> <?=htmlspecialchars($book['location'])?></p>
      <p><strong>Guest:</strong> <?=htmlspecialchars($book['guest_name'])?> (<?=htmlspecialchars($book['guest_email'])?>)</p>
      <p><strong>Check-in:</strong> <?=htmlspecialchars($book['checkin'])?> &nbsp; <strong>Check-out:</strong> <?=htmlspecialchars($book['checkout'])?></p>
      <p><strong>Total Paid:</strong> ₨<?=number_format($book['total'],2)?></p>
 
      <button class="cta" onclick="window.location.href='index.php'">Back to Home</button>
    </div>
  </div>
</body>
</html>
 
