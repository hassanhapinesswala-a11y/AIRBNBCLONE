<?php
require 'db.php';
 
$property_id = isset($_GET['property_id']) ? (int)$_GET['property_id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM properties WHERE id = :id LIMIT 1");
$stmt->execute([':id'=>$property_id]);
$prop = $stmt->fetch();
if(!$prop){
  echo "Invalid property. <a href='index.php'>Home</a>"; exit;
}
 
$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $checkin = $_POST['checkin'] ?? '';
  $checkout = $_POST['checkout'] ?? '';
 
  if(!$name) $errors[] = "Name required";
  if(!$email) $errors[] = "Email required";
  if(!$checkin || !$checkout) $errors[] = "Check-in and check-out dates required";
  if(strtotime($checkout) <= strtotime($checkin)) $errors[] = "Checkout must be after checkin";
 
  if(empty($errors)){
    $nights = (strtotime($checkout) - strtotime($checkin)) / (60*60*24);
    $total = $nights * floatval($prop['price']);
 
    $ins = $pdo->prepare("INSERT INTO bookings (property_id, guest_name, guest_email, checkin, checkout, total) VALUES (:pid,:name,:email,:ci,:co,:total)");
    $ins->execute([
      ':pid'=>$property_id,
      ':name'=>$name,
      ':email'=>$email,
      ':ci'=>$checkin,
      ':co'=>$checkout,
      ':total'=>$total
    ]);
    $booking_id = $pdo->lastInsertId();
    // JS redirect to confirmation
    echo "<script>window.location.href='confirm.php?id={$booking_id}';</script>";
    exit;
  }
}
?>
<!doctype html>
<html lang="ur">
<head>
  <meta charset="utf-8">
  <title>Book - <?=htmlspecialchars($prop['title'])?></title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <style>
    body{font-family:Inter,system-ui;background:#071025;color:#eaf6f6;margin:0}
    .wrap{max-width:700px;margin:28px auto;padding:14px}
    form{background:linear-gradient(180deg,#04101a,#071025);padding:16px;border-radius:12px}
    input{width:100%;padding:10px;margin-top:8px;border-radius:8px;border:0}
    .submit{margin-top:12px;padding:10px;border-radius:10px;border:0;background:linear-gradient(90deg,#06b6d4,#0ea5a4);cursor:pointer;font-weight:700}
    .errors{background:#3b0e0e;padding:8px;border-radius:8px;color:#ffdede}
  </style>
</head>
<body>
  <div class="wrap">
    <h2>Booking — <?=htmlspecialchars($prop['title'])?></h2>
 
    <?php if(!empty($errors)): ?>
      <div class="errors">
        <?php foreach($errors as $err) echo htmlspecialchars($err)."<br>"; ?>
      </div>
    <?php endif; ?>
 
    <form method="post">
      <label>Full name</label>
      <input name="name" value="<?=htmlspecialchars($_POST['name'] ?? '')?>" required>
 
      <label>Email</label>
      <input name="email" type="email" value="<?=htmlspecialchars($_POST['email'] ?? '')?>" required>
 
      <div style="display:flex;gap:8px;margin-top:8px">
        <div style="flex:1">
          <label>Check-in</label>
          <input name="checkin" type="date" value="<?=htmlspecialchars($_POST['checkin'] ?? '')?>" required>
        </div>
        <div style="flex:1">
          <label>Check-out</label>
          <input name="checkout" type="date" value="<?=htmlspecialchars($_POST['checkout'] ?? '')?>" required>
        </div>
      </div>
 
      <button class="submit" type="submit">Confirm Booking</button>
    </form>
  </div>
</body>
</html>
 
