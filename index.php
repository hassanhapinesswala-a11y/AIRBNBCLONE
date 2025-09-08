<?php
// index.php
require 'db.php';
 
// fetch featured (top-rated) properties
$stmt = $pdo->query("SELECT * FROM properties ORDER BY rating DESC LIMIT 6");
$featured = $stmt->fetchAll();
?>
<!doctype html>
<html lang="ur">
<head>
  <meta charset="utf-8">
  <title>Airbnb Clone - Home</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <style>
    /* Internal CSS — sleek, modern, rounded cards, gradients */
    :root{--accent:#0ea5a4;--muted:#6b7280;--bg:#0f172a}
    body{margin:0;font-family:Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue";background:linear-gradient(180deg,#0b1220 0%, #071025 100%);color:#e6eef8}
    .container{max-width:1100px;margin:36px auto;padding:18px}
    .brand{font-weight:700;font-size:26px;letter-spacing:0.4px;color:var(--accent)}
    .search{background:rgba(255,255,255,0.04);padding:18px;border-radius:14px;display:flex;gap:10px;align-items:center;box-shadow:0 6px 30px rgba(2,6,23,0.6)}
    .search input, .search button{padding:12px 14px;border-radius:10px;border:0;outline:none}
    .search input{flex:1;background:transparent;color:#e6eef8}
    .search button{background:linear-gradient(90deg,var(--accent),#06b6d4);color:#032;cursor:pointer;font-weight:600}
    .filters{margin-top:12px;display:flex;gap:8px;flex-wrap:wrap}
    .chip{background:rgba(255,255,255,0.03);padding:8px 12px;border-radius:999px;color:var(--muted);font-size:13px}
    h2{margin-top:22px;color:#dff7f6}
    .grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:16px;margin-top:14px}
    .card{background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));border-radius:14px;padding:0;overflow:hidden;box-shadow:0 8px 30px rgba(2,6,23,0.6)}
    .card img{width:100%;height:200px;object-fit:cover;display:block}
    .card-body{padding:12px}
    .title{font-weight:700}
    .meta{font-size:13px;color:var(--muted);margin-top:6px;display:flex;justify-content:space-between;align-items:center}
    .rating{background:rgba(255,255,255,0.06);padding:6px 8px;border-radius:8px;font-weight:700}
    /* responsive */
    @media (max-width:600px){.search{flex-direction:column;align-items:stretch}.card img{height:160px}}
  </style>
</head>
<body>
  <div class="container">
    <div style="display:flex;justify-content:space-between;align-items:center">
      <div>
        <div class="brand">AIRBNB-CLONE</div>
        <div style="color:var(--muted);font-size:13px">Search • Book • Stay</div>
      </div>
      <div style="text-align:right;color:var(--muted);font-size:13px">Welcome — Book quickly</div>
    </div>
 
    <form class="search" onsubmit="searchHandler(event)">
      <input type="text" id="destination" placeholder="Destination (e.g., Karachi)" />
      <input type="date" id="checkin" />
      <input type="date" id="checkout" />
      <button type="submit">Search</button>
    </form>
 
    <div class="filters">
      <div class="chip" onclick="quickFilter('price_asc')">Price: Low → High</div>
      <div class="chip" onclick="quickFilter('price_desc')">Price: High → Low</div>
      <div class="chip" onclick="quickFilter('best')">Best Rated</div>
      <div class="chip" onclick="quickFilter('villa')">Villas</div>
    </div>
 
    <h2>Featured stays</h2>
    <div class="grid">
      <?php foreach($featured as $p): ?>
        <div class="card">
          <img src="<?=htmlspecialchars($p['image'])?>" alt="<?=htmlspecialchars($p['title'])?>">
          <div class="card-body">
            <div class="title"><?=htmlspecialchars($p['title'])?></div>
            <div class="meta">
              <div class="loc"><?=htmlspecialchars($p['location'])?> • ₨<?=number_format($p['price'])?>/night</div>
              <div class="rating"><?=htmlspecialchars($p['rating'])?>★</div>
            </div>
            <div style="margin-top:10px;text-align:right">
              <button onclick="goToListing(<?= (int)$p['id'] ?>)" style="padding:8px 12px;border-radius:8px;border:0;cursor:pointer">View</button>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
 
<script>
function searchHandler(e){
  e.preventDefault();
  const dest = document.getElementById('destination').value || '';
  const ci = document.getElementById('checkin').value || '';
  const co = document.getElementById('checkout').value || '';
  // Use JS redirect (not PHP) as requested
  const params = new URLSearchParams({q: dest, checkin:ci, checkout:co});
  window.location.href = 'search_results.php?' + params.toString();
}
function quickFilter(f){
  // simple quick filters via query param
  window.location.href = 'search_results.php?filter=' + encodeURIComponent(f);
}
function goToListing(id){
  window.location.href = 'listing.php?id=' + encodeURIComponent(id);
}
</script>
</body>
</html>
