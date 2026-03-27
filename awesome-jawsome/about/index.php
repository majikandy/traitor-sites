<?php
$pageTitle = 'About';
require '../includes/header.php';

$team = [
    [
        'name'   => 'Dr. Marina Crest',
        'role'   => 'Founder & Marine Biologist',
        'emoji'  => '🔬',
        'bio'    => 'Spent 14 years tagging Great Whites off the coast of South Africa. Marina founded Awesome Jawsome after noticing how much informal sighting data was being lost to pub conversations and soggy dive-boat logbooks.',
    ],
    [
        'name'   => 'Tomás "FinFinder" Okafor',
        'role'   => 'Lead Data Wrangler',
        'emoji'  => '💻',
        'bio'    => 'Full-stack developer and recreational freediver. Tomás built the original sightings database from a spreadsheet on his phone. He\'s been chasing Hammerheads in the Galápagos since 2021.',
    ],
    [
        'name'   => 'Priya Anand',
        'role'   => 'Community Manager',
        'emoji'  => '🌊',
        'bio'    => 'Former surf instructor turned ocean advocate. Priya moderates incoming sighting reports, runs our quarterly digest newsletter, and once accidentally snorkelled directly into a Nurse Shark.',
    ],
    [
        'name'   => 'Jacques "The Jaw" Beaumont',
        'role'   => 'Species Illustrator',
        'emoji'  => '🎨',
        'bio'    => 'Self-taught wildlife illustrator who has been obsessed with elasmobranchs since age 7. Jacques is responsible for all species artwork on the site and insists the Goblin Shark is secretly cute.',
    ],
];

$milestones = [
    ['year' => '2019', 'event' => 'Marina starts a WhatsApp group called "Jawsome Sightings" with 6 dive buddies.'],
    ['year' => '2020', 'event' => 'Tomás converts the WhatsApp thread into a Google Sheet. 400 rows by December.'],
    ['year' => '2021', 'event' => 'The first version of this website goes live. 1,000 sightings logged in year one.'],
    ['year' => '2022', 'event' => 'Partnership with two university marine biology departments for data sharing.'],
    ['year' => '2023', 'event' => 'Mobile-responsive redesign. Community grows past 10,000 registered spotters.'],
    ['year' => '2024', 'event' => 'Launch of the Species Gallery and real-time filter system. 50,000+ reports logged.'],
    ['year' => '2025', 'event' => 'Awesome Jawsome cited in three peer-reviewed marine biology publications.'],
];
?>

<div class="hero" style="padding:2.5rem 2rem;">
    <h1>About <span>Awesome Jawsome</span></h1>
    <p>A passion project turned global community. Here's the story of how it all started with six divers and a group chat.</p>
</div>

<main>

    <!-- Mission -->
    <div class="card" style="margin-bottom:2rem; padding:2rem;">
        <div style="font-size:2.5rem; margin-bottom:0.75rem;">🌊</div>
        <h2 style="font-size:1.5rem; margin-bottom:0.75rem;">Our Mission</h2>
        <p style="line-height:1.8; font-size:1.05rem; color:#b0bec5;">
            Sharks are disappearing from our oceans at an alarming rate — over <strong style="color:var(--text)">100 million sharks</strong> are killed every year, primarily for the fin trade.
            Yet the data on where and when sharks are seen remains fragmented, scattered across dive operators, surf schools, fisheries reports, and memory.
        </p>
        <p style="line-height:1.8; font-size:1.05rem; color:#b0bec5; margin-top:1rem;">
            Awesome Jawsome exists to change that. We aggregate community sighting reports from divers, snorkellers, surfers, boat crews, and coastal residents worldwide —
            building a free, open dataset that researchers can use to track populations, migration routes, and behavioural shifts before it's too late.
        </p>
    </div>

    <!-- Timeline -->
    <div class="section-title">Our <span>Timeline</span></div>
    <p class="section-sub">From WhatsApp group to peer-reviewed citation.</p>
    <div style="margin-bottom:2.5rem;">
        <?php foreach ($milestones as $m): ?>
        <div style="display:flex; gap:1.5rem; margin-bottom:1rem; align-items:flex-start;">
            <div style="background:var(--accent); color:var(--deep); font-weight:800; border-radius:6px; padding:4px 10px; font-size:0.85rem; white-space:nowrap; flex-shrink:0;">
                <?= $m['year'] ?>
            </div>
            <div style="color:#b0bec5; padding-top:3px;"><?= $m['event'] ?></div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Team -->
    <div class="section-title">Meet the <span>Team</span></div>
    <p class="section-sub">The humans behind the database.</p>
    <div class="card-grid">
        <?php foreach ($team as $t): ?>
        <div class="card">
            <div style="font-size:2.5rem; margin-bottom:0.5rem;"><?= $t['emoji'] ?></div>
            <h3><?= $t['name'] ?></h3>
            <div style="color:var(--accent); font-size:0.85rem; font-weight:600; margin:0.25rem 0 0.75rem;"><?= $t['role'] ?></div>
            <p style="font-size:0.9rem; color:#b0bec5;"><?= $t['bio'] ?></p>
        </div>
        <?php endforeach; ?>
    </div>

    <hr class="divider">

    <!-- CTA -->
    <div class="card" style="text-align:center; padding:2rem;">
        <h3 style="margin-bottom:0.5rem;">Ready to contribute?</h3>
        <p style="color:var(--muted); margin-bottom:1.25rem;">Every sighting report, however brief, adds to the scientific record.</p>
        <a href="../sightings/submit.php" class="btn btn-primary">Log a Sighting</a>
        &nbsp;
        <a href="../sightings/index.php" class="btn btn-outline">Browse Reports</a>
    </div>

</main>

<?php require '../includes/footer.php'; ?>
