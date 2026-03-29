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
        <br><a href="/sightings/" style="color:inherit; font-weight:700;">← Browse all sightings</a>
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
    <div style="text-align:right; margin-bottom:1rem;">
        <button type="button" onclick="randomSighting()" class="btn btn-outline" style="font-size:0.9rem;">
            🎲 Generate Random Sighting
        </button>
    </div>
    <form method="POST" action="/sightings/submit">
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

<script>
function randomSighting() {
    const pick = arr => arr[Math.floor(Math.random() * arr.length)];

    const handles = [
        'DeepDiveKai', 'OceanPhil', 'FinWatcher99', 'SaltySurf_Lu', 'CoralQueen',
        'NautilusNate', 'BluewaterBex', 'TidalGhost', 'ReefRaider', 'WaveHunter_Oz',
        'SharkWhisperer', 'AbyssalAmy', 'PelagicPete', 'DriftDiver', 'NeptuneNick'
    ];

    const locations = [
        'Guadalupe Island, Mexico', 'False Bay, South Africa', 'Aliwal Shoal, South Africa',
        'North Shore, Oahu, Hawaii', 'Port Stephens, NSW, Australia', 'Beqa Lagoon, Fiji',
        'Ari Atoll, Maldives', 'Donsol Bay, Philippines', 'Crystal Bay, Nusa Penida, Indonesia',
        'Cocos Island, Costa Rica', 'Isla Mujeres, Mexico', 'Bimini, Bahamas',
        'Jupiter, Florida', 'Rangiroa, French Polynesia', 'Sodwana Bay, South Africa',
        'Farallon Islands, California', 'Bass Strait, Australia', 'Azores, Portugal'
    ];

    const species = ['Great White', 'Hammerhead', 'Tiger Shark', 'Bull Shark', 'Whale Shark', 'Other'];

    const zones = ['Coastal Shallows', 'Coral Reef', 'Open Ocean', 'Deep Water', 'Kelp Forest', 'Estuary', 'Other'];

    const behaviors = ['Curious', 'Aggressive', 'Feeding', 'Passing Through', 'Breaching', 'Unknown'];

    const sizes = [
        '~1.5m / 5ft', '~2m / 6.5ft', '~2.5m / 8ft', '~3m / 10ft', '~3.5m / 11.5ft',
        '~4m / 13ft', '~4.5m / 15ft', '~5m / 16ft', '~5.5m / 18ft', '~6m / 20ft', '~8m / 26ft'
    ];

    const depths = ['Surface', '2m', '5m', '8m', '10m', '15m', '20m', '25m', '30m', '40m', '60m', '100m+'];

    const descTemplates = [
        "Spotted while {activity} about {dist} from shore. The shark appeared {mood} and circled {times} before heading {dir}. Water visibility was {vis} and conditions were {cond}. {extra}",
        "Encountered during a {activity} at approximately {time} local time. A {size_adj} individual approached within {close} of our position. Behaviour was {mood} throughout. {extra}",
        "Observed from {platform} near {feature}. The shark spent around {duration} in the area, {doing}. Other divers {reaction}. Conditions: {cond}, visibility {vis}. {extra}",
        "While {activity}, a shark surfaced {dist} off the {dir} side. It {did} for roughly {duration} before disappearing into deeper water. {extra}"
    ];

    const fills = {
        activity:  ['freediving', 'cage diving', 'snorkelling', 'surfing', 'kayaking', 'spearfishing', 'drift diving', 'wreck diving'],
        dist:      ['5m', '10m', '20m', '30m', '50m', '100m'],
        mood:      ['calm', 'curious', 'agitated', 'completely unbothered', 'cautiously inquisitive', 'highly alert'],
        times:     ['twice', 'three times', 'four or five times', 'once slowly'],
        dir:       ['north', 'south', 'east', 'west', 'deeper', 'toward the reef', 'toward open water', 'port', 'starboard'],
        vis:       ['5m', '8m', '10m', '15m', '20m', '25m+', 'excellent'],
        cond:      ['calm seas', 'slight swell', 'choppy surface', 'glassy and flat', 'moderate current', 'strong thermocline at 15m'],
        extra:     [
            'No bait or chum was used.',
            'This is the second sighting at this location this month.',
            'A GoPro captured partial footage.',
            'The encounter lasted around 10 minutes in total.',
            'Several other marine species were present including rays and trevally.',
            'One of the most incredible wildlife moments of my life.',
            'We alerted the local dive operator on return to shore.',
        ],
        time:      ['06:45', '08:20', '11:00', '13:30', '15:15', '17:40', 'dawn', 'dusk'],
        size_adj:  ['juvenile', 'sub-adult', 'large adult', 'enormous', 'medium-sized', 'heavily scarred adult'],
        close:     ['2m', '5m', '8m', '10m', 'less than a metre'],
        platform:  ['the research vessel', 'a charter dive boat', 'a kayak', 'the beach', 'a cliff above the water'],
        feature:   ['the outer reef wall', 'a known seal colony', 'a shallow sand flat', 'a cleaning station', 'the wreck site'],
        duration:  ['5 minutes', '10 minutes', '20 minutes', 'nearly half an hour', 'around 45 minutes'],
        doing:     ['filter feeding at the surface', 'patrolling the drop-off', 'investigating the anchor chain', 'circling a bait ball', 'resting near the bottom'],
        reaction:  ['stayed calm and maintained position', 'slowly backed away to the boat', 'were completely transfixed'],
        did:       ['circled the boat', 'surfaced briefly', 'followed the kayak', 'investigated the hull', 'porpoised twice'],
    };

    function buildDesc() {
        const tmpl = pick(descTemplates);
        return tmpl.replace(/\{(\w+)\}/g, (_, key) => fills[key] ? pick(fills[key]) : '');
    }

    // Random recent date within last 60 days
    const d = new Date();
    d.setDate(d.getDate() - Math.floor(Math.random() * 60));
    const dateStr = d.toISOString().slice(0, 10);

    document.getElementById('reporter').value    = pick(handles);
    document.getElementById('date').value        = dateStr;
    document.getElementById('location').value    = pick(locations);
    document.getElementById('size_est').value    = pick(sizes);
    document.getElementById('depth').value       = pick(depths);
    document.getElementById('description').value = buildDesc();

    // Selects
    const setSelect = (id, val) => {
        const el = document.getElementById(id);
        for (let o of el.options) if (o.value === val) { el.value = val; break; }
    };
    setSelect('species',    pick(species));
    setSelect('ocean_zone', pick(zones));
    setSelect('behavior',   pick(behaviors));
}
</script>

<?php require '../includes/footer.php'; ?>
