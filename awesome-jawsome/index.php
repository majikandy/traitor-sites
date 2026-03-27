<?php
$pageTitle = 'Home';
require 'includes/header.php';

// Load the 3 most recent sightings
$dataDir = __DIR__ . '/data/sightings/';
$sightings = [];
if (is_dir($dataDir)) {
    $files = glob($dataDir . '*.json');
    if ($files) {
        usort($files, fn($a, $b) => filemtime($b) - filemtime($a));
        foreach (array_slice($files, 0, 3) as $file) {
            $data = json_decode(file_get_contents($file), true);
            if ($data) $sightings[] = $data;
        }
    }
}

$speciesBadge = [
    'Great White'  => 'great-white',
    'Hammerhead'   => 'hammerhead',
    'Tiger Shark'  => 'tiger',
    'Bull Shark'   => 'bull',
    'Whale Shark'  => 'whale',
    'Other'        => 'other',
];
?>

<div class="hero">
    <h1>Awesome <span>Jawsome</span></h1>
    <p>The world's most dedicated community for shark encounter reports, specimen spotting, and ocean adventure stories.</p>
    <br>
    <a href="sightings/submit.php" class="btn btn-primary">Report a Sighting</a>
    &nbsp;
    <a href="sightings/index.php" class="btn btn-outline">Browse Reports</a>
</div>

<main>
    <!-- Stats bar -->
    <div style="display:flex; gap:2rem; margin-bottom:2.5rem; flex-wrap:wrap;">
        <?php
        $total = 0;
        if (is_dir($dataDir)) $total = count(glob($dataDir . '*.json') ?: []);
        $stats = [
            ['label' => 'Total Sightings', 'value' => $total],
            ['label' => 'Species Tracked', 'value' => 6],
            ['label' => 'Ocean Zones',     'value' => 12],
            ['label' => 'Active Spotters', 'value' => '2.4k'],
        ];
        foreach ($stats as $s): ?>
        <div class="card" style="flex:1; min-width:140px; text-align:center; padding:1rem;">
            <div style="font-size:2rem; font-weight:900; color:var(--accent)"><?= $s['value'] ?></div>
            <div style="color:var(--muted); font-size:0.85rem; margin-top:0.25rem"><?= $s['label'] ?></div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Recent sightings -->
    <div class="section-title">Recent <span>Sightings</span></div>
    <p class="section-sub">Hot off the ocean floor &mdash; the latest community reports.</p>

    <?php if (empty($sightings)): ?>
    <div class="card" style="text-align:center; padding:3rem;">
        <div style="font-size:4rem; margin-bottom:1rem;">🦈</div>
        <h3>The ocean is quiet... for now.</h3>
        <p style="margin:0.75rem 0 1.25rem;">No sightings have been reported yet. Be the first to spot something jaw-dropping!</p>
        <a href="sightings/submit.php" class="btn btn-primary">Report the First Sighting</a>
    </div>
    <?php else: ?>
    <div class="card-grid">
        <?php foreach ($sightings as $s):
            $slug = $speciesBadge[$s['species']] ?? 'other';
        ?>
        <div class="card">
            <span class="badge badge-<?= $slug ?>"><?= htmlspecialchars($s['species']) ?></span>
            <h3><?= htmlspecialchars($s['location']) ?></h3>
            <div class="meta">
                📍 <?= htmlspecialchars($s['ocean_zone']) ?> &nbsp;|&nbsp;
                📅 <?= htmlspecialchars($s['date']) ?> &nbsp;|&nbsp;
                👤 <?= htmlspecialchars($s['reporter']) ?>
            </div>
            <p><?= htmlspecialchars(mb_strimwidth($s['description'], 0, 120, '…')) ?></p>
        </div>
        <?php endforeach; ?>
    </div>
    <div style="margin-top:1.5rem; text-align:center;">
        <a href="sightings/index.php" class="btn btn-outline">View All Sightings →</a>
    </div>
    <?php endif; ?>

    <hr class="divider">

    <!-- Feature callouts -->
    <div class="card-grid">
        <div class="card">
            <div style="font-size:2.5rem; margin-bottom:0.75rem;">📡</div>
            <h3>Submit a Report</h3>
            <p>Witnessed something fin-tastic? Fill out our quick sighting form and join hundreds of ocean spotters logging encounters in real time.</p>
            <a href="sightings/submit.php" class="read-more">Report now →</a>
        </div>
        <div class="card">
            <div style="font-size:2.5rem; margin-bottom:0.75rem;">🖼️</div>
            <h3>Species Gallery</h3>
            <p>From the mighty Great White to the gentle Whale Shark, explore our illustrated guide to the apex predators of the deep.</p>
            <a href="gallery/index.php" class="read-more">Open gallery →</a>
        </div>
        <div class="card">
            <div style="font-size:2.5rem; margin-bottom:0.75rem;">🌊</div>
            <h3>About the Project</h3>
            <p>Awesome Jawsome started as a dive-boat chat log in 2019. Today it's a global network of divers, surfers, and ocean scientists.</p>
            <a href="about/index.php" class="read-more">Our story →</a>
        </div>
    </div>
</main>

<?php require 'includes/footer.php'; ?>
