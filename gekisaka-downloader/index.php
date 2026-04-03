<?php
/**
 * Gekisaka Photo Downloader — index.php
 * Inspect a gallery, save it locally, and browse saved galleries.
 */
declare(strict_types=1);

// ── Helpers ───────────────────────────────────────────────────────────────────

function parse_gallery(string $url): array
{
    if (!preg_match('/\?(\d+)-\d+-pn/', $url, $m)) {
        throw new RuntimeException('Could not find gallery ID in URL.');
    }
    $gallery_id = (int)$m[1];

    $ctx = stream_context_create(['http' => [
        'method'  => 'GET',
        'timeout' => 15,
        'header'  => 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
        'ignore_errors' => true,
    ]]);
    $html = @file_get_contents($url, false, $ctx);
    if ($html === false || strlen($html) < 500) {
        throw new RuntimeException('Failed to fetch the gallery page.');
    }
    if (!preg_match('/var\s+photo_max_count\s*=\s*(\d+)/', $html, $cm)) {
        throw new RuntimeException('Could not find photo count on page.');
    }
    $total  = (int)$cm[1];
    $folder = (int)(ceil($gallery_id / 1000) * 1000);

    return compact('gallery_id', 'folder', 'total', 'url');
}

function galleries_dir(): string
{
    return __DIR__ . '/galleries';
}

function saved_galleries(): array
{
    $dir = galleries_dir();
    if (!is_dir($dir)) return [];
    $out = [];
    foreach (glob("$dir/*/meta.json") ?: [] as $f) {
        $meta = json_decode(file_get_contents($f), true);
        if (!$meta) continue;
        $gdir = dirname($f);
        $meta['saved_count'] = count(glob("$gdir/*.webp") ?: []);
        $out[] = $meta;
    }
    usort($out, fn($a, $b) => strcmp($b['saved_at'] ?? '', $a['saved_at'] ?? ''));
    return $out;
}

// ── Image proxy (thumbnails + individual image serving) ───────────────────────
if (isset($_GET['img'])) {
    $gid    = (int)$_GET['gid'];
    $folder = (int)$_GET['folder'];
    $n      = (int)$_GET['n'];
    $size   = in_array((int)($_GET['size'] ?? 800), [800, 2560]) ? (int)$_GET['size'] : 800;
    if ($gid && $folder && $n) {
        // Serve from local cache if available
        $local = galleries_dir() . "/{$gid}/" . sprintf('%03d', $n) . ".webp";
        if (file_exists($local)) {
            header('Content-Type: image/webp');
            header('Cache-Control: public, max-age=86400');
            readfile($local);
            exit;
        }
        // Otherwise proxy from source
        $img_url = "https://f.image.geki.jp/data/image/news/{$size}/{$folder}/{$gid}/news_{$gid}_{$n}.webp";
        $ctx = stream_context_create(['http' => [
            'method'  => 'GET',
            'timeout' => 15,
            'header'  => "User-Agent: Mozilla/5.0\r\nReferer: https://web.gekisaka.jp/",
            'ignore_errors' => true,
        ]]);
        $data = @file_get_contents($img_url, false, $ctx);
        if ($data !== false) {
            header('Content-Type: image/webp');
            header('Cache-Control: public, max-age=86400');
            echo $data;
        }
    }
    exit;
}

// ── SSE save endpoint ─────────────────────────────────────────────────────────
if (isset($_GET['action']) && $_GET['action'] === 'save') {
    $gid    = (int)($_GET['gid']    ?? 0);
    $folder = (int)($_GET['folder'] ?? 0);
    $total  = (int)($_GET['total']  ?? 0);
    $gurl   = $_GET['gurl'] ?? '';

    set_time_limit(0);
    ignore_user_abort(false);

    header('Content-Type: text/event-stream');
    header('Cache-Control: no-cache');
    header('X-Accel-Buffering: no');
    @ob_end_flush();

    function sse(array $data): void {
        echo "data: " . json_encode($data) . "\n\n";
        ob_flush(); flush();
    }

    if (!$gid || !$folder || !$total) { sse(['error' => 'Bad params']); exit; }

    $dir = galleries_dir() . "/{$gid}";
    if (!is_dir($dir)) mkdir($dir, 0755, true);

    $meta_file = "$dir/meta.json";
    if (!file_exists($meta_file)) {
        file_put_contents($meta_file, json_encode([
            'gid'      => $gid,
            'folder'   => $folder,
            'total'    => $total,
            'url'      => $gurl,
            'saved_at' => date('c'),
        ]));
    }

    $base        = "https://f.image.geki.jp/data/image/news/2560/{$folder}/{$gid}";
    $CONCURRENCY = 6;
    $done        = 0;
    $all         = range(1, $total);

    while (!empty($all)) {
        $batch = array_splice($all, 0, $CONCURRENCY);
        $mh    = curl_multi_init();
        $chs   = [];

        foreach ($batch as $n) {
            $dest = $dir . '/' . sprintf('%03d', $n) . '.webp';
            if (file_exists($dest)) {
                $done++;
                sse(['n' => $done, 'total' => $total, 'skipped' => true]);
                continue;
            }
            $ch = curl_init("$base/news_{$gid}_{$n}.webp");
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 20,
                CURLOPT_HTTPHEADER     => [
                    'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
                    'Referer: https://web.gekisaka.jp/',
                ],
            ]);
            $chs[$n] = $ch;
            curl_multi_add_handle($mh, $ch);
        }

        if ($chs) {
            do {
                curl_multi_exec($mh, $active);
                curl_multi_select($mh);
            } while ($active > 0);

            foreach ($chs as $n => $ch) {
                $data = curl_multi_getcontent($ch);
                if ($data && strlen($data) > 100) {
                    file_put_contents($dir . '/' . sprintf('%03d', $n) . '.webp', $data);
                }
                curl_multi_remove_handle($mh, $ch);
                curl_close($ch);
                $done++;
                sse(['n' => $done, 'total' => $total]);
            }
        }

        curl_multi_close($mh);
    }

    sse(['done' => true, 'gid' => $gid]);
    exit;
}

// ── Preview action ────────────────────────────────────────────────────────────
$error   = '';
$preview = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['gallery_url'])) {
    try {
        $preview = parse_gallery(trim($_POST['gallery_url']));
    } catch (RuntimeException $e) {
        $error = $e->getMessage();
    }
}

$input_url = htmlspecialchars($_POST['gallery_url'] ?? '');
$galleries  = saved_galleries();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Gekisaka Photo Downloader</title>
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  body {
    font-family: system-ui, -apple-system, sans-serif;
    background: #0f0f0f;
    color: #e0e0e0;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 2rem 1rem;
    gap: 1.5rem;
  }
  .card {
    background: #1a1a1a;
    border: 1px solid #2a2a2a;
    border-radius: 12px;
    padding: 2rem;
    width: 100%;
    max-width: 560px;
  }
  h1 { font-size: 1.25rem; font-weight: 600; margin-bottom: .25rem; color: #fff; }
  h2 { font-size: 1rem; color: #86efac; margin-bottom: .75rem; }
  .sub { font-size: .85rem; color: #666; margin-bottom: 1.5rem; }
  label { display: block; font-size: .85rem; color: #999; margin-bottom: .4rem; }
  input[type=text] {
    width: 100%;
    background: #111;
    border: 1px solid #333;
    border-radius: 8px;
    color: #e0e0e0;
    padding: .65rem .85rem;
    font-size: .95rem;
    outline: none;
    transition: border-color .2s;
  }
  input[type=text]:focus { border-color: #555; }
  .btn {
    display: inline-block;
    margin-top: 1rem;
    padding: .65rem 1.4rem;
    border-radius: 8px;
    font-size: .9rem;
    font-weight: 600;
    cursor: pointer;
    border: none;
    transition: opacity .15s;
    text-decoration: none;
  }
  .btn:hover:not(:disabled) { opacity: .85; }
  .btn:disabled { opacity: .45; cursor: not-allowed; }
  .btn-primary { background: #2563eb; color: #fff; }
  .btn-green   { background: #16a34a; color: #fff; }
  .error {
    background: #3b0a0a;
    border: 1px solid #7f1d1d;
    border-radius: 8px;
    padding: .75rem 1rem;
    margin-top: 1rem;
    font-size: .875rem;
    color: #fca5a5;
  }
  .result {
    background: #0d1f0d;
    border: 1px solid #166534;
    border-radius: 8px;
    padding: 1rem;
    margin-top: 1.25rem;
  }
  .meta { display: grid; gap: .4rem; font-size: .875rem; }
  .meta span { color: #6b7280; }
  .meta strong { color: #d1fae5; }
  .preview-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 6px;
    margin-top: 1rem;
  }
  .preview-grid img {
    width: 100%;
    aspect-ratio: 3/2;
    object-fit: cover;
    border-radius: 6px;
    background: #222;
  }
  /* Progress — fixed bar at bottom of viewport */
  .progress-wrap {
    display: none;
    position: fixed;
    bottom: 0; left: 0; right: 0;
    background: #111;
    border-top: 1px solid #2a2a2a;
    padding: .6rem 1.25rem;
    z-index: 50;
  }
  .progress-wrap.active { display: block; }
  .progress-label {
    font-size: .8rem;
    color: #9ca3af;
    margin-bottom: .35rem;
    display: flex;
    justify-content: space-between;
  }
  .progress-track {
    background: #222;
    border-radius: 99px;
    height: 6px;
    overflow: hidden;
  }
  .progress-bar {
    height: 100%;
    width: 0%;
    background: #16a34a;
    border-radius: 99px;
    transition: width .15s ease;
  }
  .progress-bar.done { background: #2563eb; }
  /* Saved galleries grid */
  .galleries-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 1rem;
    width: 100%;
    max-width: 560px;
  }
  .gallery-card {
    background: #1a1a1a;
    border: 1px solid #2a2a2a;
    border-radius: 10px;
    overflow: hidden;
    text-decoration: none;
    color: inherit;
    transition: border-color .2s;
  }
  .gallery-card:hover { border-color: #444; }
  .gallery-card img {
    width: 100%;
    aspect-ratio: 3/2;
    object-fit: cover;
    background: #222;
    display: block;
  }
  .gallery-card-info {
    padding: .65rem .75rem;
    font-size: .8rem;
    color: #6b7280;
  }
  .gallery-card-info strong { display: block; color: #e0e0e0; font-size: .9rem; }
  .section-title {
    font-size: .8rem;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: #4b5563;
    width: 100%;
    max-width: 560px;
    padding-bottom: .35rem;
    border-bottom: 1px solid #222;
  }
</style>
</head>
<body>

<div class="card">
  <h1>Gekisaka Photo Downloader</h1>
  <p class="sub">Saves all photos at 2560px locally and builds a browsable gallery.</p>

  <form method="post">
    <label for="gallery_url">Gallery URL</label>
    <input type="text" id="gallery_url" name="gallery_url"
           placeholder="https://web.gekisaka.jp/photonews/japan/detail/?448821-448821-pn"
           value="<?= $input_url ?>" required>
    <button type="submit" class="btn btn-primary">Inspect Gallery</button>
  </form>

  <?php if ($error): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <?php if ($preview): ?>
    <?php
      $gid    = $preview['gallery_id'];
      $folder = $preview['folder'];
      $total  = $preview['total'];
      $gurl   = urlencode($preview['url']);
      $dir           = galleries_dir() . "/{$gid}";
      $saved_count   = is_dir($dir) ? count(glob("$dir/*.webp") ?: []) : 0;
      $complete      = $saved_count >= $total;
      $partial       = $saved_count > 0 && !$complete;
    ?>
    <div class="result">
      <h2>Gallery found</h2>
      <div class="meta">
        <div><span>Gallery ID: </span><strong><?= $gid ?></strong></div>
        <div><span>Total photos: </span><strong><?= $total ?></strong></div>
        <div><span>Resolution: </span><strong>2560 px (max)</strong></div>
        <?php if ($saved_count > 0): ?>
          <div><span>Saved locally: </span><strong><?= $saved_count ?> / <?= $total ?></strong></div>
        <?php endif; ?>
      </div>

      <div style="margin-top:1rem; display:flex; gap:.5rem; flex-wrap:wrap;">
        <button class="btn btn-green"
          onclick="saveGallery(this, <?= $gid ?>, <?= $folder ?>, <?= $total ?>, '<?= $gurl ?>')">
          <?= $complete ? 'Re-download' : ($partial ? "Resume ({$saved_count}/{$total})" : 'Save Gallery') ?>
        </button>
        <?php if ($saved_count > 0): ?>
          <a href="gallery.php?gid=<?= $gid ?>" class="btn btn-primary" style="margin-top:1rem;">
            View Gallery
          </a>
        <?php endif; ?>
      </div>

      <div class="preview-grid">
        <?php for ($n = 1; $n <= min(3, $total); $n++): ?>
          <img src="?img=1&gid=<?= $gid ?>&folder=<?= $folder ?>&n=<?= $n ?>&size=800"
               alt="Photo <?= $n ?>" loading="lazy">
        <?php endfor; ?>
      </div>
    </div>
  <?php endif; ?>
</div>

<?php if ($galleries): ?>
  <p class="section-title">Saved Galleries</p>
  <div class="galleries-grid">
    <?php foreach ($galleries as $g):
      $g_saved  = (int)$g['saved_count'];
      $g_total  = (int)$g['total'];
      $g_partial = $g_saved > 0 && $g_saved < $g_total;
      $g_gurl   = urlencode($g['url'] ?? '');
    ?>
      <div class="gallery-card">
        <a href="gallery.php?gid=<?= $g['gid'] ?>">
          <img src="?img=1&gid=<?= $g['gid'] ?>&folder=<?= $g['folder'] ?>&n=1&size=800"
               alt="Gallery <?= $g['gid'] ?>" loading="lazy">
        </a>
        <div class="gallery-card-info">
          <strong>Gallery <?= $g['gid'] ?></strong>
          <?php if ($g_partial): ?>
            <span style="color:#f59e0b"><?= $g_saved ?>/<?= $g_total ?> saved</span>
          <?php else: ?>
            <?= $g_total ?> photos
          <?php endif; ?>
          &middot; <?= date('d M Y', strtotime($g['saved_at'])) ?>
          <?php if ($g_partial && $g_gurl): ?>
            <br><button class="btn btn-green" style="margin-top:.5rem; font-size:.75rem; padding:.35rem .8rem;"
              onclick="saveGallery(this, <?= $g['gid'] ?>, <?= $g['folder'] ?>, <?= $g_total ?>, '<?= $g_gurl ?>')">
              Resume (<?= $g_saved ?>/<?= $g_total ?>)
            </button>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<script>
function saveGallery(triggerBtn, gid, folder, total, gurl) {
  const wrap    = document.getElementById('progress-wrap');
  const bar     = document.getElementById('progress-bar');
  const text    = document.getElementById('progress-text');
  const countEl = document.getElementById('progress-count');

  triggerBtn.disabled = true;
  bar.classList.remove('done');
  bar.style.width = '0%';
  wrap.classList.add('active');

  let retries = 0;
  const MAX_RETRIES = 10;

  function connect() {
    const url = `?action=save&gid=${gid}&folder=${folder}&total=${total}&gurl=${gurl}`;
    const es  = new EventSource(url);

    es.onmessage = (e) => {
      retries = 0; // reset on successful message
      const d = JSON.parse(e.data);
      if (d.error) {
        text.textContent = 'Error: ' + d.error;
        es.close();
        triggerBtn.disabled = false;
        return;
      }
      if (d.done) {
        bar.style.width = '100%';
        bar.classList.add('done');
        text.textContent = 'Done! Redirecting…';
        es.close();
        setTimeout(() => { window.location = `gallery.php?gid=${gid}`; }, 800);
        return;
      }
      const pct = Math.round((d.n / d.total) * 100);
      bar.style.width = pct + '%';
      countEl.textContent = d.n + ' / ' + d.total;
    };

    es.onerror = () => {
      es.close();
      if (retries < MAX_RETRIES) {
        retries++;
        text.textContent = `Reconnecting… (attempt ${retries})`;
        setTimeout(connect, 1500);
      } else {
        text.textContent = 'Failed after ' + MAX_RETRIES + ' retries.';
        triggerBtn.disabled = false;
      }
    };
  }  // end connect()

  connect();
}  // end saveGallery()
</script>

<!-- Global progress bar (fixed bottom) -->
<div class="progress-wrap" id="progress-wrap">
  <div class="progress-label">
    <span id="progress-text">Saving images…</span>
    <span id="progress-count"></span>
  </div>
  <div class="progress-track">
    <div class="progress-bar" id="progress-bar"></div>
  </div>
</div>
</body>
</html>
