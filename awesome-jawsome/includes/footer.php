<?php
$facts = [
    "Sharks are older than trees — they've existed for over 450 million years.",
    "A Great White's bite force is around 1.8 tonnes per square centimetre.",
    "Whale Sharks are the largest fish on Earth, reaching up to 18 metres.",
    "Bull Sharks can survive in fresh water and have been found in the Amazon.",
    "Sharks don't have bones — their skeletons are made entirely of cartilage.",
    "A shark can detect one part of blood per million parts of water.",
    "Hammerheads have 360° vertical vision thanks to their wide-set eyes.",
    "Some shark species must swim constantly or they will suffocate.",
    "Sharks pre-date dinosaurs by roughly 200 million years.",
    "The Greenland Shark can live for over 500 years.",
    "Great Whites can breach completely out of the water when hunting seals.",
    "Tiger Sharks have been found with licence plates, tyres, and suits of armour in their stomachs.",
    "Oceanic Whitetip populations have declined by over 95% due to finning.",
    "Sharks lose tens of thousands of teeth in a lifetime — they regrow in rows.",
    "A group of sharks is called a shiver.",
];
$fact = $facts[date('z') % count($facts)];
?>
</main>
<footer>
    <p>🦈 <span>Awesome Jawsome</span> &mdash; Community shark sighting reports since 2024. Stay safe, stay curious.</p>
    <p style="margin-top:0.5rem; font-size:0.8rem; color:#546e7a;">💡 Did you know? <?= $fact ?></p>
</footer>
</body>
</html>
