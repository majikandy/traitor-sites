<?php
/**
 * Gekisaka Photo Downloader
 * Fetches all photos at full 2560px resolution from a gekisaka.jp photonews gallery.
 */

declare(strict_types=1);

$error   = '';
$preview = null;

function fetch_url(string $url, string $referer = ''): string|false
{
    $ctx = stream_context_create(['http' => [
        'method'  => 'GET',
        'timeout' => 15,
        'header'  => implode("\r\n", array_filter([
            'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
            $referer ? "Referer: $referer" : '',
        ])),
        'ignore_errors' => true,
    ]]);
    return @file_get_contents($url, false, $ctx);
}

function parse_gallery(string $url): array
{
    if (!preg_match('/\?(\d+)-\d+-pn/', $url, $m)) {
        throw new RuntimeException('Could not find gallery ID in URL.');
    }
    $gallery_id = (int)$m[1];

    $html = fetch_url($url);
    if ($html === false || strlen($html) < 500) {
        throw new RuntimeException('Failed to fetch the gallery page.');
    }

    if (!preg_match('/var\s+photo_max_count\s*=\s*(\d+)/', $html, $cm)) {
        throw new RuntimeException('Could not find photo count on page.');
    }
    $total = (int)$cm[1];

    $folder = (int)(ceil($gallery_id / 1000) * 1000);

    return compact('gallery_id', 'folder', 'total', 'url');
}

// ── Download-as-ZIP action ────────────────────────────────────────────────────
if (isset($_GET['download']) && isset($_GET['gallery_url'])) {
    $gallery_url = trim($_GET['gallery_url']);
    try {
        ['gallery_id' => $gid, 'folder' => $folder, 'total' => $total, 'url' => $gurl]
            = parse_gallery($gallery_url);

        $base = "https://f.image.geki.jp/data/image/news/2560/{$folder}/{$gid}";

        header('Content-Type: application/zip');
        header("Content-Disposition: attachment; filename=\"gekisaka_{$gid}.zip\"");
        header('Cache-Control: no-cache');
        header('X-Accel-Buffering: no');
        ob_end_clean();

        // Stream zip without temp file using ZipStream-style manual write
        // We'll use a temp file for compatibility
        $tmp = tempnam(sys_get_temp_dir(), 'geki_');
        $zip = new ZipArchive();
        $zip->open($tmp, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        for ($n = 1; $n <= $total; $n++) {
            $img_url = "{$base}/news_{$gid}_{$n}.webp";
            $data    = fetch_url($img_url, $gurl);
            if ($data !== false && strlen($data) > 100) {
                $zip->addFromString(sprintf('%03d.webp', $n), $data);
            }
        }
        $zip->close();

        $size = filesize($tmp);
        header("Content-Length: $size");
        readfile($tmp);
        unlink($tmp);
        exit;

    } catch (RuntimeException $e) {
        // Fall through to show error on page (shouldn't normally reach here via download link)
        $error = $e->getMessage();
    }
}

// ── Preview action ────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['gallery_url'])) {
    $gallery_url = trim($_POST['gallery_url']);
    try {
        $preview = parse_gallery($gallery_url);
    } catch (RuntimeException $e) {
        $error = $e->getMessage();
    }
}

$input_url = htmlspecialchars($_POST['gallery_url'] ?? '');
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
    align-items: flex-start;
    justify-content: center;
    padding: 2rem 1rem;
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
  }
  .btn:hover { opacity: .85; }
  .btn-primary { background: #2563eb; color: #fff; }
  .btn-green   { background: #16a34a; color: #fff; text-decoration: none; }
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
  .result h2 { font-size: 1rem; color: #86efac; margin-bottom: .75rem; }
  .meta { display: grid; gap: .4rem; font-size: .875rem; }
  .meta span { color: #6b7280; }
  .meta strong { color: #d1fae5; }
  .notice {
    margin-top: .85rem;
    font-size: .8rem;
    color: #6b7280;
  }
  .preview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(70px, 1fr));
    gap: 4px;
    margin-top: 1rem;
  }
  .preview-grid img {
    width: 100%;
    aspect-ratio: 3/2;
    object-fit: cover;
    border-radius: 4px;
    background: #222;
  }
</style>
</head>
<body>
<div class="card">
  <h1>Gekisaka Photo Downloader</h1>
  <p class="sub">Downloads all photos at full 2560px resolution as a zip file.</p>

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
      $gurl   = $preview['url'];
      $base   = "https://f.image.geki.jp/data/image/news";
      $dl_url = '?download=1&gallery_url=' . urlencode($gurl);
    ?>
    <div class="result">
      <h2>Gallery found</h2>
      <div class="meta">
        <div><span>Gallery ID: </span><strong><?= $gid ?></strong></div>
        <div><span>Total photos: </span><strong><?= $total ?></strong></div>
        <div><span>Resolution: </span><strong>2560 px (max)</strong></div>
        <div><span>Format: </span><strong>WebP</strong></div>
      </div>

      <div style="margin-top:1rem;">
        <a href="<?= htmlspecialchars($dl_url) ?>" class="btn btn-green">
          Download all <?= $total ?> photos (ZIP)
        </a>
      </div>

      <p class="notice">The zip file will be built server-side and may take a moment for large galleries.</p>

      <!-- Thumbnail preview of first 12 -->
      <div class="preview-grid">
        <?php for ($n = 1; $n <= min(12, $total); $n++): ?>
          <img src="<?= $base ?>/800/<?= $folder ?>/<?= $gid ?>/news_<?= $gid ?>_<?= $n ?>.webp"
               alt="Photo <?= $n ?>" loading="lazy">
        <?php endfor; ?>
      </div>
    </div>
  <?php endif; ?>
</div>
</body>
</html>
