<?php
require 'db.php';
 
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$checkin = isset($_GET['checkin']) ? $_GET['checkin'] : '';
$checkout = isset($_GET['checkout']) ? $_GET['checkout'] : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';
 
// build query
$sql = "SELECT * FROM properties WHERE 1=1 ";
$params = [];
 
if($q !== ''){
  $sql .= " AND (location LIKE :q OR title LIKE :q) ";
  $params[':q'] = "%$q%";
}
 
if($filter === 'villa'){
  $sql .= " AND type = 'Villa' ";
} elseif ($filter === 'best') {
  $sql .= " AND rating >= 4.6 ";
}
 
if($sort === 'price_asc'){
  $sql .= " ORDER BY price ASC ";
} elseif($sort === 'price_desc'){
  $sql .= " ORDER BY price DESC ";
} else {
  $sql .= " ORDER BY created_at DESC ";
}
 
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$properties = $stmt->fetchAll();
?>
<!doctype html>
<html lang="ur">
<head>
  <meta charset="utf-8">
  <title>Search results</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <style>
    body{font-family:Inter, system-ui; background:#071025; color:#eaf6f6; margin:0}
    .wrap{max-width:1100px;margin:28px auto;padding:14px}
    .back{color:#9ee7e6;cursor:pointer}
    .controls{display:flex;gap:8px;align-items:center;margin:12px 0}
    select,input{padding:8px;border-radius:8px;border:0}
    .list{display:grid;grid-template-columns:repeat(auto-fill,minmax(330px,1fr));gap:14px}
    .card{background:linear-gradient(180deg,#07182a, #04101a);border-radius:12px;overflow:hidden;box-shadow:0 8px 24px rgba(0,0,0,0.6)}
    .card img{width:100%;height:180px;object-fit:cover}
    .card-body{padding:10px}
    .price{font-weight:800}
  </style>
</head>
<body>
  <div class="wrap">
    <div style="display:flex;justify-content:space-between;align-items:center">
      <div><span class="back" onclick="window.location.href='index.php'">← Back</span></div>
      <div>Showing <?=count($properties)?> results</div>
    </div>
 
    <div class="controls">
      <label>Sort:
        <select onchange="applySort(this.value)">
          <option value="">Newest</option>
          <option value="price_asc">Price: Low → High</option>
          <option value="price_desc">Price: High → Low</option>
        </select>
      </label>
      <label>Filter:
        <select onchange="applyFilter(this.value)">
          <option value="">All</option>
          <option value="villa">Villas</option>
          <option value="best">Best Rated</option>
        </select>
      </label>
    </div>
 
    <div class="list">
      <?php foreach($properties as $p): ?>
        <div class="card">
          <img src="<?=htmlspecialchars($p['image'])?>" alt="">
          <div class="card-body">
            <div style="display:flex;justify-content:space-between;align-items:center">
              <div>
                <div style="font-weight:700"><?=htmlspecialchars($p['title'])?></div>
                <div style="color:#9fc9c8;font-size:13px"><?=htmlspecialchars($p['location'])?></div>
              </div>
              <div style="text-align:right">
                <div class="price">₨<?=number_format($p['price'])?></div>
                <div style="font-size:13px;color:#9aaeb0"><?=htmlspecialchars($p['rating'])?>★</div>
              </div>
            </div>
            <div style="margin-top:10px;text-align:right">
              <button onclick="viewListing(<?= (int)$p['id'] ?>)" style="padding:8px 12px;border-radius:8px;border:0;cursor:pointer">View</button>
            </div>
          </div>_
