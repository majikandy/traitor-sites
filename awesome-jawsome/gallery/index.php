<?php
$pageTitle = 'Species Gallery';
require '../includes/header.php';

$sharks = [
    [
        'name'        => 'Great White Shark',
        'latin'       => 'Carcharodon carcharias',
        'emoji'       => '🦈',
        'badge'       => 'great-white',
        'length'      => 'Up to 6.1m (20ft)',
        'weight'      => 'Up to 2,000kg',
        'habitat'     => 'Coastal & offshore temperate waters',
        'danger'      => 5,
        'rarity'      => 3,
        'diet'        => 'Seals, sea lions, fish, cetaceans',
        'description' => 'The ocean\'s most iconic apex predator. Great Whites use the surface ambush technique — accelerating vertically from depth to breach the surface at tremendous speed. Despite their fearsome reputation, attacks on humans are rare and often investigatory.',
        'fun_fact'    => 'Great Whites can detect a single drop of blood diluted in 25 gallons of water.',
        'sightings_count' => 128,
    ],
    [
        'name'        => 'Hammerhead Shark',
        'latin'       => 'Sphyrna mokarran',
        'emoji'       => '🔨',
        'badge'       => 'hammerhead',
        'length'      => 'Up to 6m (20ft)',
        'weight'      => 'Up to 580kg',
        'habitat'     => 'Warm tropical & subtropical oceans',
        'danger'      => 3,
        'rarity'      => 3,
        'diet'        => 'Stingrays, bony fish, octopus, squid',
        'description' => 'The bizarre cephalofoil (hammer-shaped head) gives the hammerhead 360° vertical vision and superior electroreception. They\'re often seen in large schools during migration, making them a bucket-list sighting for divers.',
        'fun_fact'    => 'Hammerheads can be found with stingray barbs in their mouths — seemingly immune to the venom.',
        'sightings_count' => 74,
    ],
    [
        'name'        => 'Tiger Shark',
        'latin'       => 'Galeocerdo cuvier',
        'emoji'       => '🐯',
        'badge'       => 'tiger',
        'length'      => 'Up to 5.5m (18ft)',
        'weight'      => 'Up to 900kg',
        'habitat'     => 'Coastal tropical waters, harbors, river mouths',
        'danger'      => 4,
        'rarity'      => 3,
        'diet'        => 'Almost anything — fish, turtles, birds, dolphins, licence plates',
        'description' => 'Second only to Great Whites in unprovoked attacks on humans, Tiger Sharks earn their reputation as indiscriminate feeders. The distinctive dark vertical stripes fade as the shark matures. Known to linger in shallow harbour waters after dark.',
        'fun_fact'    => 'A Tiger Shark\'s stomach has been found to contain a suit of armour, a bag of potatoes, and a car tyre.',
        'sightings_count' => 56,
    ],
    [
        'name'        => 'Bull Shark',
        'latin'       => 'Carcharhinus leucas',
        'emoji'       => '🐂',
        'badge'       => 'bull',
        'length'      => 'Up to 3.4m (11ft)',
        'weight'      => 'Up to 316kg',
        'habitat'     => 'Shallow coastal waters, rivers, lakes',
        'danger'      => 5,
        'rarity'      => 2,
        'diet'        => 'Fish, dolphins, turtles, birds, other sharks',
        'description' => 'Considered by many scientists to be the most dangerous shark due to its aggressive nature, powerful build, and unique ability to survive in fresh water. Bull Sharks have been found hundreds of kilometres up rivers including the Amazon and Mississippi.',
        'fun_fact'    => 'Bull Sharks have the highest testosterone levels of any animal on Earth.',
        'sightings_count' => 91,
    ],
    [
        'name'        => 'Whale Shark',
        'latin'       => 'Rhincodon typus',
        'emoji'       => '🐋',
        'badge'       => 'whale',
        'length'      => 'Up to 18m (59ft)',
        'weight'      => 'Up to 18,000kg',
        'habitat'     => 'Tropical open oceans',
        'danger'      => 1,
        'rarity'      => 4,
        'diet'        => 'Plankton, krill, fish eggs, small fish',
        'description' => 'The largest fish on Earth is, ironically, a gentle filter feeder that poses no threat to humans. Swimming alongside a Whale Shark is considered one of the most magical wildlife encounters on the planet. Each individual is identified by its unique spot pattern.',
        'fun_fact'    => 'A Whale Shark\'s mouth can be 1.5m wide and contains over 300 rows of tiny teeth, though they\'re filter feeders.',
        'sightings_count' => 43,
    ],
    [
        'name'        => 'Oceanic Whitetip',
        'latin'       => 'Carcharhinus longimanus',
        'emoji'       => '🌊',
        'badge'       => 'other',
        'length'      => 'Up to 3.5m (11.5ft)',
        'weight'      => 'Up to 168kg',
        'habitat'     => 'Tropical & warm temperate open ocean',
        'danger'      => 4,
        'rarity'      => 4,
        'diet'        => 'Bony fish, squid, stingrays, sea birds',
        'description' => 'Historically one of the most abundant large oceanic sharks, the Whitetip\'s population has plummeted over 95% due to finning. They are notoriously bold around humans, responsible for more cumulative attacks than other species due to historic maritime disasters.',
        'fun_fact'    => 'Jacques Cousteau called the Oceanic Whitetip "the most dangerous of all sharks."',
        'sightings_count' => 22,
    ],
];
?>

<div class="hero" style="padding:2.5rem 2rem;">
    <h1>Specimen <span>Gallery</span></h1>
    <p>Meet the sharks. Know your apex predators — from the colossal Whale Shark to the relentless Bull Shark.</p>
</div>

<main>
    <div class="section-title">Species <span>Guide</span></div>
    <p class="section-sub">Six species most commonly reported in the Awesome Jawsome sightings database.</p>

    <div class="card-grid">
        <?php foreach ($sharks as $shark): ?>
        <div class="card">
            <div style="font-size:3rem; margin-bottom:0.5rem; text-align:center;"><?= $shark['emoji'] ?></div>
            <span class="badge badge-<?= $shark['badge'] ?>"><?= $shark['name'] ?></span>
            <div style="color:var(--muted); font-size:0.78rem; font-style:italic; margin:0.3rem 0 0.75rem;">
                <?= $shark['latin'] ?>
            </div>

            <!-- Danger / Rarity bars -->
            <div style="display:flex; gap:1rem; margin-bottom:0.75rem; font-size:0.8rem;">
                <div>
                    <span style="color:var(--muted);">Danger </span>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <span style="color:<?= $i <= $shark['danger'] ? '#d32f2f' : 'rgba(255,255,255,0.15)' ?>">●</span>
                    <?php endfor; ?>
                </div>
                <div>
                    <span style="color:var(--muted);">Rarity </span>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <span style="color:<?= $i <= $shark['rarity'] ? 'var(--accent)' : 'rgba(255,255,255,0.15)' ?>">●</span>
                    <?php endfor; ?>
                </div>
            </div>

            <p style="margin-bottom:0.75rem;"><?= $shark['description'] ?></p>

            <div style="background:rgba(255,255,255,0.04); border-radius:8px; padding:0.75rem; font-size:0.83rem; margin-bottom:0.75rem;">
                <div style="margin-bottom:0.3rem;">📏 <strong>Length:</strong> <?= $shark['length'] ?></div>
                <div style="margin-bottom:0.3rem;">⚖️ <strong>Weight:</strong> <?= $shark['weight'] ?></div>
                <div style="margin-bottom:0.3rem;">🌍 <strong>Habitat:</strong> <?= $shark['habitat'] ?></div>
                <div>🍽️ <strong>Diet:</strong> <?= $shark['diet'] ?></div>
            </div>

            <div style="background:rgba(255,111,0,0.08); border-left:3px solid var(--accent); padding:0.6rem 0.75rem; border-radius:0 6px 6px 0; font-size:0.83rem; color:#ffcc80; margin-bottom:0.75rem;">
                💡 <?= $shark['fun_fact'] ?>
            </div>

            <div style="display:flex; justify-content:space-between; align-items:center; font-size:0.82rem; color:var(--muted);">
                <span>🦈 <?= $shark['sightings_count'] ?> community reports</span>
                <a href="/sightings/?species=<?= urlencode($shark['name']) ?>" class="read-more" style="font-size:0.82rem;">See reports →</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <hr class="divider">

    <div class="card" style="text-align:center; padding:2rem;">
        <h3 style="margin-bottom:0.5rem;">Spotted a species not in the gallery?</h3>
        <p style="color:var(--muted); margin-bottom:1.25rem;">Log your encounter and help us expand the database.</p>
        <a href="/sightings/submit" class="btn btn-primary">Report a Sighting</a>
    </div>
</main>

<?php require '../includes/footer.php'; ?>
