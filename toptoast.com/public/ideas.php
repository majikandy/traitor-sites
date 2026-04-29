<?php $config = require __DIR__ . '/includes/config.php'; ?>
<?php $pageTitle = 'Money Ideas'; ?>
<?php $pageDescription = 'Eight low-effort, high-reward ways to monetise Top Toast. No investment. No partners. Start today.'; ?>

<style>
/* ============================================
   IDEAS PAGE — CRAZY 8s BRAINSTORM
   ============================================ */

@keyframes fadeSlide {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}

.ideas-hero {
    background: var(--color-text);
    color: #fff;
    padding: 4rem 0 3.5rem;
    position: relative;
    overflow: hidden;
}

.ideas-hero::after {
    content: '💰';
    position: absolute;
    right: -1rem;
    top: -2rem;
    font-size: 20rem;
    opacity: 0.05;
    line-height: 1;
    pointer-events: none;
    user-select: none;
}

.ideas-kicker {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--color-accent);
    margin-bottom: 1.25rem;
}

.ideas-kicker::before {
    content: '';
    display: inline-block;
    width: 2rem;
    height: 2px;
    background: var(--color-accent);
}

.ideas-hero h1 {
    font-family: var(--font-serif);
    font-size: 3.75rem;
    line-height: 1.05;
    margin-bottom: 1.25rem;
    color: #fff;
}

.ideas-hero h1 em {
    font-style: normal;
    color: var(--color-accent);
}

.ideas-hero p {
    font-size: 1.1rem;
    color: rgba(255,255,255,0.55);
    max-width: 500px;
    line-height: 1.65;
    margin-bottom: 0;
}

.constraint-bar {
    display: flex;
    gap: 1.5rem;
    margin-top: 2rem;
    flex-wrap: wrap;
}

.constraint-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255,255,255,0.07);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 2rem;
    padding: 0.4rem 1rem;
    font-size: 0.82rem;
    font-weight: 600;
    color: rgba(255,255,255,0.7);
}

.constraint-pill span { font-size: 1rem; }

/* ============================================
   GRID
   ============================================ */
.ideas-grid-section {
    padding: 4rem 0;
    background: #f9f3ec;
}

.ideas-grid {
    display: grid;
    grid-template-columns: repeat(12, 1fr);
    gap: 1.25rem;
}

/* Card spans */
.idea-card:nth-child(1) { grid-column: span 5; }
.idea-card:nth-child(2) { grid-column: span 7; }
.idea-card:nth-child(3) { grid-column: span 4; }
.idea-card:nth-child(4) { grid-column: span 4; }
.idea-card:nth-child(5) { grid-column: span 4; }
.idea-card:nth-child(6) { grid-column: span 7; }
.idea-card:nth-child(7) { grid-column: span 5; }
.idea-card:nth-child(8) { grid-column: span 12; }

.idea-card {
    background: #fff;
    border: 2px solid var(--color-border-light);
    border-radius: var(--radius);
    padding: 2rem 2rem 1.75rem;
    position: relative;
    overflow: hidden;
    animation: fadeSlide 0.4s ease both;
    transition: transform 0.2s, box-shadow 0.2s;
}

.idea-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 36px rgba(45,32,23,0.1);
}

/* accent stripe on top */
.idea-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 4px;
    background: var(--color-border-light);
}

.idea-card.hot::before    { background: var(--color-primary); }
.idea-card.warm::before   { background: var(--color-accent); }
.idea-card.solid::before  { background: #4ade80; }

/* big background number */
.idea-bg-num {
    position: absolute;
    bottom: -1.5rem;
    right: 0.5rem;
    font-family: var(--font-serif);
    font-size: 7rem;
    line-height: 1;
    color: var(--color-border-light);
    user-select: none;
    pointer-events: none;
    font-weight: 400;
}

.idea-card-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 1rem;
    gap: 0.75rem;
}

.idea-num {
    font-size: 0.72rem;
    font-weight: 800;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--color-text-light);
    margin-bottom: 0.6rem;
}

.idea-emoji {
    font-size: 2rem;
    line-height: 1;
    flex-shrink: 0;
}

.start-today-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    background: var(--color-primary);
    color: #fff;
    font-size: 0.65rem;
    font-weight: 800;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    padding: 0.25rem 0.65rem;
    border-radius: 2rem;
    white-space: nowrap;
}

.idea-card h3 {
    font-family: var(--font-serif);
    font-size: 1.35rem;
    line-height: 1.25;
    margin-bottom: 0.6rem;
    color: var(--color-text);
}

.idea-card p {
    font-size: 0.88rem;
    color: var(--color-text-light);
    line-height: 1.65;
    margin: 0 0 1.25rem;
}

/* effort/reward bars */
.idea-meters {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
    margin-top: auto;
}

.meter-row {
    display: flex;
    align-items: center;
    gap: 0.6rem;
}

.meter-label {
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: var(--color-text-light);
    min-width: 3.5rem;
}

.meter-pips {
    display: flex;
    gap: 3px;
}

.pip {
    width: 10px;
    height: 10px;
    border-radius: 2px;
    background: var(--color-border-light);
}

.pip.on.effort  { background: #f87171; }
.pip.on.reward  { background: #4ade80; }

/* ============================================
   WIDE CARD (card 8) overrides
   ============================================ */
.idea-card-wide {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    align-items: center;
}

.idea-card-wide .idea-wide-left {}
.idea-card-wide .idea-wide-right {
    border-left: 2px solid var(--color-border-light);
    padding-left: 2rem;
}

.idea-card-wide .idea-wide-right h4 {
    font-family: var(--font-serif);
    font-size: 1.15rem;
    margin-bottom: 0.5rem;
}

.idea-card-wide .idea-wide-right p {
    margin-bottom: 0;
}

/* ============================================
   START TODAY SECTION
   ============================================ */
.start-section {
    background: var(--color-primary);
    padding: 4.5rem 0;
    position: relative;
    overflow: hidden;
}

.start-section::before {
    content: '→';
    position: absolute;
    font-size: 28rem;
    color: rgba(255,255,255,0.04);
    right: -4rem;
    top: -4rem;
    line-height: 1;
    font-family: var(--font-sans);
    pointer-events: none;
}

.start-section .section-label {
    color: rgba(255,255,255,0.6);
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    margin-bottom: 0.5rem;
    display: block;
}

.start-section h2 {
    font-family: var(--font-serif);
    font-size: 2.5rem;
    color: #fff;
    margin-bottom: 2.5rem;
    line-height: 1.2;
}

.start-steps {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
}

.start-step {
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: var(--radius);
    padding: 1.75rem;
    position: relative;
}

.start-step-num {
    font-family: var(--font-serif);
    font-size: 3.5rem;
    line-height: 1;
    color: rgba(255,255,255,0.2);
    margin-bottom: 0.75rem;
}

.start-step h4 {
    font-size: 1rem;
    font-weight: 700;
    color: #fff;
    margin-bottom: 0.4rem;
}

.start-step p {
    font-size: 0.88rem;
    color: rgba(255,255,255,0.65);
    line-height: 1.6;
    margin: 0;
}

.start-step .step-action {
    display: inline-block;
    margin-top: 1rem;
    font-size: 0.78rem;
    font-weight: 700;
    color: var(--color-accent);
    text-transform: uppercase;
    letter-spacing: 0.06em;
}

/* ============================================
   RESPONSIVE
   ============================================ */
@media (max-width: 1024px) {
    .idea-card:nth-child(1),
    .idea-card:nth-child(2),
    .idea-card:nth-child(3),
    .idea-card:nth-child(4),
    .idea-card:nth-child(5),
    .idea-card:nth-child(6),
    .idea-card:nth-child(7),
    .idea-card:nth-child(8) { grid-column: span 6; }

    .idea-card-wide { grid-template-columns: 1fr; }
    .idea-card-wide .idea-wide-right { border-left: none; border-top: 2px solid var(--color-border-light); padding-left: 0; padding-top: 1.5rem; }
    .start-steps { grid-template-columns: 1fr 1fr; }
}

@media (max-width: 640px) {
    .ideas-hero h1 { font-size: 2.5rem; }

    .idea-card:nth-child(n) { grid-column: span 12; }

    .start-steps { grid-template-columns: 1fr; }
    .start-section h2 { font-size: 1.75rem; }

    .constraint-bar { gap: 0.75rem; }
}
</style>

<?php include __DIR__ . '/includes/header.php'; ?>

<!-- HERO -->
<section class="ideas-hero">
    <div class="container">
        <div class="ideas-kicker">Crazy 8s Session</div>
        <h1>8 ways to make<br><em>actual money</em><br>from toast.</h1>
        <p>No investors. No partnerships. No warehouses. Just a website about bread and a few smart moves.</p>
        <div class="constraint-bar">
            <span class="constraint-pill"><span>⚡</span> Low effort only</span>
            <span class="constraint-pill"><span>💸</span> High reward only</span>
            <span class="constraint-pill"><span>🔧</span> No big setup</span>
            <span class="constraint-pill"><span>📦</span> No inventory</span>
        </div>
    </div>
</section>

<!-- IDEAS GRID -->
<section class="ideas-grid-section">
    <div class="container">
        <div class="ideas-grid">

            <!-- 01 Display Ads -->
            <div class="idea-card hot">
                <div class="idea-bg-num">01</div>
                <div class="idea-card-top">
                    <div>
                        <div class="idea-num">Idea 01</div>
                        <div class="idea-emoji">💤</div>
                    </div>
                    <span class="start-today-badge">Start Today</span>
                </div>
                <h3>Display Ads</h3>
                <p>Paste one code snippet. Never touch it again. AdSense works from day one. Mediavine kicks in properly once you hit 50k monthly sessions — and then it actually pays. Toast recipes attract long dwell time. That's money.</p>
                <div class="idea-meters">
                    <div class="meter-row">
                        <span class="meter-label">Effort</span>
                        <div class="meter-pips">
                            <div class="pip on effort"></div>
                            <div class="pip"></div><div class="pip"></div><div class="pip"></div><div class="pip"></div>
                        </div>
                    </div>
                    <div class="meter-row">
                        <span class="meter-label">Reward</span>
                        <div class="meter-pips">
                            <div class="pip on reward"></div>
                            <div class="pip on reward"></div>
                            <div class="pip on reward"></div>
                            <div class="pip on reward"></div>
                            <div class="pip"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 02 Amazon Associates -->
            <div class="idea-card hot">
                <div class="idea-bg-num">02</div>
                <div class="idea-card-top">
                    <div>
                        <div class="idea-num">Idea 02</div>
                        <div class="idea-emoji">🔗</div>
                    </div>
                    <span class="start-today-badge">Start Today</span>
                </div>
                <h3>Amazon Associates</h3>
                <p>Every recipe mentions a toaster, a bread knife, a jar of tahini, or a fancy butter. That's a link. Someone clicks, buys a £180 toaster on the way to the checkout — you get £7.20 for a hyperlink you wrote once. The programme takes 10 minutes to set up. You have dozens of recipes. Do the maths.</p>
                <div class="idea-meters">
                    <div class="meter-row">
                        <span class="meter-label">Effort</span>
                        <div class="meter-pips">
                            <div class="pip on effort"></div>
                            <div class="pip on effort"></div>
                            <div class="pip"></div><div class="pip"></div><div class="pip"></div>
                        </div>
                    </div>
                    <div class="meter-row">
                        <span class="meter-label">Reward</span>
                        <div class="meter-pips">
                            <div class="pip on reward"></div>
                            <div class="pip on reward"></div>
                            <div class="pip on reward"></div>
                            <div class="pip on reward"></div>
                            <div class="pip on reward"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 03 Etsy Printables -->
            <div class="idea-card warm">
                <div class="idea-bg-num">03</div>
                <div class="idea-card-top">
                    <div>
                        <div class="idea-num">Idea 03</div>
                        <div class="idea-emoji">🖨️</div>
                    </div>
                </div>
                <h3>Printable Recipe Cards on Etsy</h3>
                <p>10 beautiful recipe cards in Canva. Upload to Etsy. £2.99 each. Digital download — Etsy handles the delivery automatically. You handle nothing. People buy these constantly as gifts and kitchen prints.</p>
                <div class="idea-meters">
                    <div class="meter-row">
                        <span class="meter-label">Effort</span>
                        <div class="meter-pips">
                            <div class="pip on effort"></div>
                            <div class="pip on effort"></div>
                            <div class="pip"></div><div class="pip"></div><div class="pip"></div>
                        </div>
                    </div>
                    <div class="meter-row">
                        <span class="meter-label">Reward</span>
                        <div class="meter-pips">
                            <div class="pip on reward"></div>
                            <div class="pip on reward"></div>
                            <div class="pip on reward"></div>
                            <div class="pip"></div><div class="pip"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 04 Ebook -->
            <div class="idea-card warm">
                <div class="idea-bg-num">04</div>
                <div class="idea-card-top">
                    <div>
                        <div class="idea-num">Idea 04</div>
                        <div class="idea-emoji">📕</div>
                    </div>
                </div>
                <h3>The Top Toast Ebook</h3>
                <p>30 recipes, a beautiful PDF layout, £8 on Gumroad. One weekend of work. Gumroad takes a tiny cut, you keep the rest. Write once. Sell forever. No publisher, no printing, no shipping.</p>
                <div class="idea-meters">
                    <div class="meter-row">
                        <span class="meter-label">Effort</span>
                        <div class="meter-pips">
                            <div class="pip on effort"></div>
                            <div class="pip on effort"></div>
                            <div class="pip on effort"></div>
                            <div class="pip"></div><div class="pip"></div>
                        </div>
                    </div>
                    <div class="meter-row">
                        <span class="meter-label">Reward</span>
                        <div class="meter-pips">
                            <div class="pip on reward"></div>
                            <div class="pip on reward"></div>
                            <div class="pip on reward"></div>
                            <div class="pip on reward"></div>
                            <div class="pip"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 05 Newsletter Sponsorships -->
            <div class="idea-card solid">
                <div class="idea-bg-num">05</div>
                <div class="idea-card-top">
                    <div>
                        <div class="idea-num">Idea 05</div>
                        <div class="idea-emoji">✉️</div>
                    </div>
                </div>
                <h3>Newsletter Sponsorships</h3>
                <p>One sentence in a weekly email. An artisan jam brand pays £150–300 to be mentioned to your list. One email equals one invoice. The smaller the brand, the hungrier they are for exactly this.</p>
                <div class="idea-meters">
                    <div class="meter-row">
                        <span class="meter-label">Effort</span>
                        <div class="meter-pips">
                            <div class="pip on effort"></div>
                            <div class="pip"></div><div class="pip"></div><div class="pip"></div><div class="pip"></div>
                        </div>
                    </div>
                    <div class="meter-row">
                        <span class="meter-label">Reward</span>
                        <div class="meter-pips">
                            <div class="pip on reward"></div>
                            <div class="pip on reward"></div>
                            <div class="pip on reward"></div>
                            <div class="pip on reward"></div>
                            <div class="pip"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 06 YouTube / TikTok -->
            <div class="idea-card hot">
                <div class="idea-bg-num">06</div>
                <div class="idea-card-top">
                    <div>
                        <div class="idea-num">Idea 06</div>
                        <div class="idea-emoji">🎬</div>
                    </div>
                    <span class="start-today-badge">Start Today</span>
                </div>
                <h3>YouTube Shorts &amp; TikTok</h3>
                <p>30 seconds. Natural light. Your phone. Toast is insanely visual — golden, steaming, loaded with toppings. Film it, post it. YouTube Partner Program + affiliate link in bio. One viral toast video changes the whole equation. You already have the recipes — this is just pointing a camera at them.</p>
                <div class="idea-meters">
                    <div class="meter-row">
                        <span class="meter-label">Effort</span>
                        <div class="meter-pips">
                            <div class="pip on effort"></div>
                            <div class="pip on effort"></div>
                            <div class="pip"></div><div class="pip"></div><div class="pip"></div>
                        </div>
                    </div>
                    <div class="meter-row">
                        <span class="meter-label">Reward</span>
                        <div class="meter-pips">
                            <div class="pip on reward"></div>
                            <div class="pip on reward"></div>
                            <div class="pip on reward"></div>
                            <div class="pip on reward"></div>
                            <div class="pip on reward"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 07 Print-on-Demand Merch -->
            <div class="idea-card warm">
                <div class="idea-bg-num">07</div>
                <div class="idea-card-top">
                    <div>
                        <div class="idea-num">Idea 07</div>
                        <div class="idea-emoji">👜</div>
                    </div>
                </div>
                <h3>Print-on-Demand Merch</h3>
                <p>Upload a design to Printful or Redbubble. Tea towels. Tote bags. Mugs. They print it, pack it, ship it. You collect the margin and do absolutely nothing when an order comes in. Toast typography looks great on a tote.</p>
                <div class="idea-meters">
                    <div class="meter-row">
                        <span class="meter-label">Effort</span>
                        <div class="meter-pips">
                            <div class="pip on effort"></div>
                            <div class="pip on effort"></div>
                            <div class="pip"></div><div class="pip"></div><div class="pip"></div>
                        </div>
                    </div>
                    <div class="meter-row">
                        <span class="meter-label">Reward</span>
                        <div class="meter-pips">
                            <div class="pip on reward"></div>
                            <div class="pip on reward"></div>
                            <div class="pip on reward"></div>
                            <div class="pip"></div><div class="pip"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 08 Mini Course — WIDE -->
            <div class="idea-card hot">
                <div class="idea-bg-num">08</div>
                <div class="idea-card-wide">
                    <div class="idea-wide-left">
                        <div class="idea-card-top" style="margin-bottom:0.75rem">
                            <div>
                                <div class="idea-num">Idea 08</div>
                                <div class="idea-emoji">🎓</div>
                            </div>
                            <span class="start-today-badge">Highest margin</span>
                        </div>
                        <h3>"Toast Like a Pro"<br>Mini Course</h3>
                        <p>8 short videos. Filmed on your phone over a weekend. Bread selection, toasting technique, seasonal toppings, plating. Sell on Gumroad for £20. Record once. Sell to everyone who finds you, forever. This is the highest-margin thing on this list.</p>
                    </div>
                    <div class="idea-wide-right">
                        <div class="idea-meters" style="margin-bottom:1.25rem">
                            <div class="meter-row">
                                <span class="meter-label">Effort</span>
                                <div class="meter-pips">
                                    <div class="pip on effort"></div>
                                    <div class="pip on effort"></div>
                                    <div class="pip on effort"></div>
                                    <div class="pip"></div><div class="pip"></div>
                                </div>
                            </div>
                            <div class="meter-row">
                                <span class="meter-label">Reward</span>
                                <div class="meter-pips">
                                    <div class="pip on reward"></div>
                                    <div class="pip on reward"></div>
                                    <div class="pip on reward"></div>
                                    <div class="pip on reward"></div>
                                    <div class="pip on reward"></div>
                                </div>
                            </div>
                        </div>
                        <h4>The rough maths</h4>
                        <p style="font-size:0.88rem;color:var(--color-text-light);line-height:1.6;margin:0">100 sales × £20 = £2,000 from one weekend of filming. No ongoing work. No refunds to chase. Gumroad handles the payments and delivery at 10% cut. You keep £1,800.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- START TODAY -->
<section class="start-section">
    <div class="container">
        <span class="section-label">No excuses</span>
        <h2>Three things to do<br>before Friday.</h2>
        <div class="start-steps">
            <div class="start-step">
                <div class="start-step-num">1</div>
                <h4>Sign up to Amazon Associates</h4>
                <p>Takes 10 minutes. Then go through every recipe and add a link to the main ingredient or the toaster you'd recommend. Do the easy ones first.</p>
                <span class="step-action">→ affiliate-program.amazon.co.uk</span>
            </div>
            <div class="start-step">
                <div class="start-step-num">2</div>
                <h4>Put AdSense on the site</h4>
                <p>One code snippet in the header. It pays immediately. Low rates at first — but you're leaving money on the table every day you delay.</p>
                <span class="step-action">→ adsense.google.com</span>
            </div>
            <div class="start-step">
                <div class="start-step-num">3</div>
                <h4>Film one toast video</h4>
                <p>Pick your best recipe. Natural light by a window. One take is fine. Post it to TikTok and YouTube Shorts with a link to the recipe. See what happens.</p>
                <span class="step-action">→ your phone, right now</span>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
