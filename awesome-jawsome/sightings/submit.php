<?php
$pageTitle = 'Report a Sighting';

$errors  = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reporter    = trim($_POST['reporter']    ?? '');
    $species     = trim($_POST['species']     ?? '');
    $location    = trim($_POST['location']    ?? '');
    $ocean_zone  = trim($_POST['ocean_zone']  ?? '');
    $date        = trim($_POST['date']        ?? '');
    $size_est    = trim($_POST['size_est']    ?? '');
    $description = trim($_POST['description'] ?? '');
    $depth       = trim($_POST['depth']       ?? '');
    $behavior    = trim($_POST['behavior']    ?? '');

    $allowed_species = ['Great White', 'Hammerhead', 'Tiger Shark', 'Bull Shark', 'Whale Shark', 'Other'];
    $allowed_zones   = ['Coastal Shallows', 'Coral Reef', 'Open Ocean', 'Deep Water', 'Kelp Forest', 'Estuary', 'Other'];
    $allowed_behavior = ['Curious', 'Aggressive', 'Feeding', 'Passing Through', 'Breaching', 'Unknown'];

    if (!$reporter)                               $errors[] = 'Reporter name is required.';
    if (!in_array($species, $allowed_species))    $errors[] = 'Please select a valid species.';
    if (!$location)                               $errors[] = 'Location is required.';
    if (!in_array($ocean_zone, $allowed_zones))   $errors[] = 'Please select a valid ocean zone.';
    if (!$date || !strtotime($date))              $errors[] = 'A valid date is required.';
    if (strlen($description) < 20)               $errors[] = 'Description must be at least 20 characters.';

    if (empty($errors)) {
        $dataDir = dirname(__DIR__) . '/data/sightings/';
        if (!is_dir($dataDir)) {
            mkdir($dataDir, 0755, true);
        }

        $sighting = [
            'id'          => uniqid('sight_', true),
            'reporter'    => htmlspecialchars($reporter,    ENT_QUOTES),
            'species'     => $species,
            'location'    => htmlspecialchars($location,    ENT_QUOTES),
            'ocean_zone'  => $ocean_zone,
            'date'        => $date,
            'size_est'    => htmlspecialchars($size_est,    ENT_QUOTES),
            'description' => htmlspecialchars($description, ENT_QUOTES),
            'depth'       => htmlspecialchars($depth,       ENT_QUOTES),
            'behavior'    => $behavior,
            'submitted_at'=> date('Y-m-d H:i:s'),
        ];

        $filename = $dataDir . time() . '_' . preg_replace('/[^a-z0-9]/', '', strtolower($reporter)) . '.json';
        file_put_contents($filename, json_encode($sighting, JSON_PRETTY_PRINT));
        $success = true;
    }
}

require '../includes/header.php';
?>

<div class="hero" style="padding:2.5rem 2rem;">
    <h1>Report a <span>Sighting</span></h1>
    <p>Witnessed a shark encounter? Log it here and help the community track fin activity worldwide.</p>
</div>

<main>
    <?php if ($success): ?>
    <div class="alert alert-success">
        ✅ Your sighting has been logged! Thank you for contributing to the Awesome Jawsome database.
        <br><a href="index.php" style="color:inherit; font-weight:700;">← Browse all sightings</a>
    </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <?php foreach ($errors as $e): ?>
        <div>⚠️ <?= htmlspecialchars($e) ?></div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (!$success): ?>
    <form method="POST" action="submit.php">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem;">
            <div class="form-group">
                <label for="reporter">Your Name / Handle</label>
                <input type="text" id="reporter" name="reporter" placeholder="e.g. DiveGuru_99" value="<?= htmlspecialchars($_POST['reporter'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="date">Date of Sighting</label>
                <input type="text" id="date" name="date" placeholder="YYYY-MM-DD" value="<?= htmlspecialchars($_POST['date'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="species">Species</label>
                <select id="species" name="species">
                    <option value="">— select species —</option>
                    <?php foreach (['Great White','Hammerhead','Tiger Shark','Bull Shark','Whale Shark','Other'] as $sp): ?>
                    <option value="<?= $sp ?>" <?= (($_POST['species'] ?? '') === $sp) ? 'selected' : '' ?>><?= $sp ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="behavior">Observed Behavior</label>
                <select id="behavior" name="behavior">
                    <option value="">— select behavior —</option>
                    <?php foreach (['Curious','Aggressive','Feeding','Passing Through','Breaching','Unknown'] as $bh): ?>
                    <option value="<?= $bh ?>" <?= (($_POST['behavior'] ?? '') === $bh) ? 'selected' : '' ?>><?= $bh ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="location">Location / Beach / Coordinates</label>
                <input type="text" id="location" name="location" placeholder="e.g. Seal Beach, CA or 33.7°N 118.1°W" value="<?= htmlspecialchars($_POST['location'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="ocean_zone">Ocean Zone</label>
                <select id="ocean_zone" name="ocean_zone">
                    <option value="">— select zone —</option>
                    <?php foreach (['Coastal Shallows','Coral Reef','Open Ocean','Deep Water','Kelp Forest','Estuary','Other'] as $oz): ?>
                    <option value="<?= $oz ?>" <?= (($_POST['ocean_zone'] ?? '') === $oz) ? 'selected' : '' ?>><?= $oz ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="size_est">Estimated Size</label>
                <input type="text" id="size_est" name="size_est" placeholder="e.g. ~4m / 13ft" value="<?= htmlspecialchars($_POST['size_est'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="depth">Approximate Depth</label>
                <input type="text" id="depth" name="depth" placeholder="e.g. 10m / surface" value="<?= htmlspecialchars($_POST['depth'] ?? '') ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="description">What happened? (min 20 characters)</label>
            <textarea id="description" name="description" rows="5" placeholder="Describe the encounter in detail — behaviour, weather, water conditions, other marine life nearby…"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%; padding:0.85rem; font-size:1rem;">
            🦈 Submit Sighting
        </button>
    </form>
    <?php endif; ?>
</main>

<?php require '../includes/footer.php'; ?>
