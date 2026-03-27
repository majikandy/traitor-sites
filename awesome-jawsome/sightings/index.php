<?php
$pageTitle = 'All Sightings';

// Load & sort all sightings
$dataDir  = dirname(__DIR__) . '/data/sightings/';
$sightings = [];
if (is_dir($dataDir)) {
    $files = glob($dataDir . '*.json');
    if ($files) {
        usort($files, fn($a, $b) => filemtime($b) - filemtime($a));
        foreach ($files as $file) {
            $data = json_decode(file_get_contents($file), true);
            if ($data) $sightings[] = $data;
        }
    }
}

// Filter by species if requested
$filterSpecies = $_GET['species'] ?? '';
if ($filterSpecies) {
    $sightings = array_filter($sightings, fn($s) => $s['species'] === $filterSpecies);
}

$speciesList = ['Great White', 'Hammerhead', 'Tiger Shark', 'Bull Shark', 'Whale Shark', 'Other'];
$speciesBadge = [
    'Great White'  => 'great-white',
    'Hammerhead'   => 'hammerhead',
    'Tiger Shark'  => 'tiger',
    'Bull Shark'   => 'bull',
    'Whale Shark'  => 'whale',
    'Other'        => 'other',
];

require '../includes/header.php';
?>

<div class="hero" style="padding:2.5rem 2rem;">
    <h1>Sighting <span>Reports</span></h1>
    <p>Community-submitted shark encounters from oceans around the world. Every entry is stored and preserved.</p>
</div>

<main>
    <!-- Filter bar -->
    <div style="display:flex; gap:0.5rem; flex-wrap:wrap; margin-bottom:2rem; align-items:center;">
        <span style="color:var(--muted); font-size:0.9rem; margin-right:0.25rem;">Filter by species:</span>
        <a href="index.php" class="btn <?= !$filterSpecies ? 'btn-primary' : 'btn-outline' ?>" style="padding:0.35rem 0.9rem; font-size:0.85rem;">All</a>
        <?php foreach ($speciesList as $sp): ?>
        <a href="?species=<?= urlencode($sp) ?>"
           class="btn <?= $filterSpecies === $sp ? 'btn-primary' : 'btn-outline' ?>"
           style="padding:0.35rem 0.9rem; font-size:0.85rem;"><?= $sp ?></a>
        <?php endforeach; ?>
    </div>

    <?php if (empty($sightings)): ?>
    <div class="card" style="text-align:center; padding:3rem;">
        <div style="font-size:4rem; margin-bottom:1rem;">🦈</div>
        <h3>No sightings found<?= $filterSpecies ? " for \"$filterSpecies\"" : '' ?>.</h3>
        <p style="margin:0.75rem 0 1.25rem; color:var(--muted);">Be the first to report an encounter!</p>
        <a href="submit.php" class="btn btn-primary">Report a Sighting</a>
    </div>
    <?php else: ?>
    <div style="color:var(--muted); font-size:0.9rem; margin-bottom:1.25rem;">
        Showing <strong style="color:var(--text)"><?= count($sightings) ?></strong> report<?= count($sightings) !== 1 ? 's' : '' ?>
        <?= $filterSpecies ? " — filtered to <strong style='color:var(--accent)'>$filterSpecies</strong>" : '' ?>
    </div>
    <div class="card-grid">
        <?php foreach ($sightings as $s):
            $slug = $speciesBadge[$s['species']] ?? 'other';
        ?>
        <div class="card">
            <span class="badge badge-<?= $slug ?>"><?= htmlspecialchars($s['species']) ?></span>
            <?php if (!empty($s['behavior'])): ?>
            <span class="badge" style="background:rgba(255,111,0,0.2); color:var(--accent); margin-left:4px;">
                <?= htmlspecialchars($s['behavior']) ?>
            </span>
            <?php endif; ?>
            <h3 style="margin-top:0.5rem;"><?= htmlspecialchars($s['location']) ?></h3>
            <div class="meta">
                🌊 <?= htmlspecialchars($s['ocean_zone'] ?? '—') ?> &nbsp;|&nbsp;
                📅 <?= htmlspecialchars($s['date']) ?> &nbsp;|&nbsp;
                👤 <?= htmlspecialchars($s['reporter']) ?>
            </div>
            <?php if (!empty($s['size_est'])): ?>
            <div style="color:var(--muted); font-size:0.82rem; margin-bottom:0.5rem;">
                📏 Size: <?= htmlspecialchars($s['size_est']) ?>
                <?php if (!empty($s['depth'])): ?>
                &nbsp;|&nbsp; 🤿 Depth: <?= htmlspecialchars($s['depth']) ?>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            <p><?= htmlspecialchars($s['description']) ?></p>
            <div style="margin-top:0.75rem; font-size:0.78rem; color:var(--muted);">
                Submitted <?= htmlspecialchars($s['submitted_at'] ?? '') ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <hr class="divider">
    <div style="text-align:center;">
        <a href="submit.php" class="btn btn-primary">+ Report a New Sighting</a>
    </div>
</main>

<?php require '../includes/footer.php'; ?>
