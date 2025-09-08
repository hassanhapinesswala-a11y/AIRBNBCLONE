<?php
require 'db.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM properties WHERE id = :id LIMIT 1");
$stmt->execute([':id'=>$id]);
$prop = $stmt->fetch();
if(!$prop){
  echo "Property not found. <a href='index.php'>Go home</a>";
  exit;
}
?>
<!doctype html>
<html lang="ur">
<head>
  <meta charset="utf-8">
  <title><?=htmlspecialchars($prop['title'])?></title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <style>
    body{font-family:Inter,system-ui;background:#071025;color:#eaf6f6;margin:0}
    .wrap{max-width:900px;margin:28px auto;padding:14px}
    .hero{border-radius:12px;overflow:hidden}
    .hero img{width:100%;height:380px;object-fit:cover}
    .info{background:linear-gradient(180deg,#04101a, #071025);padding:14px;border-radius:12px;margin-top:12px;box-shadow:0 8px 30px rgba(0,0,0,0.6)}
    .book-btn{background:linear-gradient(90deg,#06b6d4,#0ea5a4);padding:10px 14px;border-radius:10px;border:0;cursor:pointer;font-weight:700}
  </style>
</head>
<body>
  <div class="wrap">
    <div style="display:flex;justify-content:space-between;align-items:center">
      <div style="font-weight:800"><?=htmlspecialchars($prop['title'])?></div>
      <div style="color:#9fc9c8"><?=htmlspecialchars($prop['rating'])?>★</div>
    </div>
 
    <div class="hero"><img src="<?=htmlspecialchars($prop['image'])?>" alt=""></div>
 
    <div class="info">
      <div style="display:flex;justify-content:space-between;align-items:center">
        <div>
          <div style="font-weight:700">₨<?=number_format($prop['price'])?> / night</div>
          <div style="color:#9aaeb0;margin-top:6px"><?=htmlspecialchars($prop['location'])?> • <?=htmlspecialchars($prop['type'])?></div>
        </div>
        <div>
          <button onclick="startBooking(<?= (int)$prop['id'] ?>)" class="book-btn">Book Now</button>
        </div>
      </div>
 
      <div style="margin-top:12px;color:#cfeff0">
        <strong>Amenities:</strong> <?=htmlspecialchars($prop['amenities'])?>
      </div>
 
      <div style="margin-top:10px;color:#cfeff0">
        <?=nl2br(htmlspecialchars($prop['description']))?>
      </div>
    </div>
  </div>
 
<script>
function startBooking(id){
  // Redirect to booking page with params
  window.location.href = 'book.php?property_id=' + encodeURIComponent(id);
}
</script>
</body>
</html>
 
