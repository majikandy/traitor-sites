<?php
/**
 * Gekisaka Photo Downloader — gallery.php
 * Browsable photo grid for a saved gallery.
 */
declare(strict_types=1);

$gid = (int)($_GET['gid'] ?? 0);
if (!$gid) { header('Location: index.php'); exit; }

$dir       = __DIR__ . '/galleries/' . $gid;
$meta_file = $dir . '/meta.json';
if (!is_dir($dir) || !file_exists($meta_file)) { header('Location: index.php'); exit; }

$meta   = json_decode(file_get_contents($meta_file), true);
$total  = (int)$meta['total'];
$folder = (int)$meta['folder'];
$saved  = date('d M Y', strtotime($meta['saved_at']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Gallery <?= $gid ?></title>
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  body {
    font-family: system-ui, -apple-system, sans-serif;
    background: #0f0f0f;
    color: #e0e0e0;
    min-height: 100vh;
  }
  header {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #1f1f1f;
    display: flex;
    align-items: center;
    gap: 1rem;
  }
  header a {
    color: #6b7280;
    text-decoration: none;
    font-size: .85rem;
  }
  header a:hover { color: #e0e0e0; }
  header h1 { font-size: 1rem; font-weight: 600; color: #fff; flex: 1; }
  header .meta-pill {
    font-size: .75rem;
    color: #6b7280;
    background: #1a1a1a;
    border: 1px solid #2a2a2a;
    border-radius: 99px;
    padding: .2rem .65rem;
  }

  .grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 3px;
    padding: 3px;
  }
  .grid-item {
    position: relative;
    aspect-ratio: 3/2;
    overflow: hidden;
    cursor: pointer;
    background: #111;
  }
  .grid-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform .25s ease, opacity .2s;
  }
  .grid-item:hover img { transform: scale(1.04); }
  .grid-item .num {
    position: absolute;
    bottom: 4px;
    left: 6px;
    font-size: .65rem;
    color: rgba(255,255,255,.5);
    pointer-events: none;
  }

  /* Lightbox */
  #lb {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.92);
    z-index: 100;
    align-items: center;
    justify-content: center;
  }
  #lb.open { display: flex; }
  #lb img {
    max-width: 92vw;
    max-height: 92vh;
    object-fit: contain;
    border-radius: 4px;
  }
  #lb-prev, #lb-next {
    position: fixed;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255,255,255,.08);
    border: none;
    color: #fff;
    font-size: 1.5rem;
    padding: .75rem 1rem;
    cursor: pointer;
    border-radius: 6px;
    z-index: 101;
    transition: background .15s;
  }
  #lb-prev:hover, #lb-next:hover { background: rgba(255,255,255,.18); }
  #lb-prev { left: 1rem; }
  #lb-next { right: 1rem; }
  #lb-close {
    position: fixed;
    top: 1rem;
    right: 1rem;
    background: none;
    border: none;
    color: #fff;
    font-size: 1.5rem;
    cursor: pointer;
    z-index: 101;
    opacity: .6;
  }
  #lb-close:hover { opacity: 1; }
  #lb-counter {
    position: fixed;
    bottom: 1.25rem;
    left: 50%;
    transform: translateX(-50%);
    font-size: .8rem;
    color: rgba(255,255,255,.4);
  }
</style>
</head>
<body>

<header>
  <a href="index.php">← Back</a>
  <h1>Gallery <?= $gid ?></h1>
  <span class="meta-pill"><?= $total ?> photos</span>
  <span class="meta-pill"><?= $saved ?></span>
</header>

<div class="grid">
  <?php for ($n = 1; $n <= $total; $n++): ?>
    <div class="grid-item" onclick="openLb(<?= $n ?>)">
      <img src="index.php?img=1&gid=<?= $gid ?>&folder=<?= $folder ?>&n=<?= $n ?>&size=800"
           data-full="index.php?img=1&gid=<?= $gid ?>&folder=<?= $folder ?>&n=<?= $n ?>&size=2560"
           alt="Photo <?= $n ?>" loading="lazy">
      <span class="num"><?= $n ?></span>
    </div>
  <?php endfor; ?>
</div>

<!-- Lightbox -->
<div id="lb">
  <button id="lb-close" onclick="closeLb()">✕</button>
  <button id="lb-prev" onclick="stepLb(-1)">‹</button>
  <img id="lb-img" src="" alt="">
  <button id="lb-next" onclick="stepLb(1)">›</button>
  <div id="lb-counter"></div>
</div>

<script>
const total = <?= $total ?>;
let current = 1;

function openLb(n) {
  current = n;
  updateLb();
  document.getElementById('lb').classList.add('open');
}
function closeLb() {
  document.getElementById('lb').classList.remove('open');
}
function stepLb(dir) {
  current = ((current - 1 + dir + total) % total) + 1;
  updateLb();
}
function updateLb() {
  const items = document.querySelectorAll('.grid-item img');
  const src   = items[current - 1]?.dataset.full || '';
  document.getElementById('lb-img').src = src;
  document.getElementById('lb-counter').textContent = current + ' / ' + total;
}

document.addEventListener('keydown', (e) => {
  if (!document.getElementById('lb').classList.contains('open')) return;
  if (e.key === 'ArrowRight' || e.key === 'ArrowDown') stepLb(1);
  if (e.key === 'ArrowLeft'  || e.key === 'ArrowUp')   stepLb(-1);
  if (e.key === 'Escape') closeLb();
});
document.getElementById('lb').addEventListener('click', (e) => {
  if (e.target === document.getElementById('lb')) closeLb();
});
</script>
</body>
</html>
