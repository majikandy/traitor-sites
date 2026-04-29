<?php $config = require __DIR__ . '/includes/config.php'; ?>
<?php $pageTitle = 'Ideas Vol. 2 — The Interesting Ones'; ?>
<?php $pageDescription = 'Eight less obvious ways to make money from a toast website. These are the ones worth getting excited about.'; ?>

<style>
/* ============================================
   IDEAS VOL 2 — DARKER, BOLDER, MORE CONFIDENT
   ============================================ */

@keyframes fadeSlide {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}

.ideas2-hero {
    background: var(--color-primary);
    color: #fff;
    padding: 4.5rem 0 4rem;
    position: relative;
    overflow: hidden;
}

.ideas2-hero::after {
    content: '🧠';
    position: absolute;
    right: -2rem;
    top: -3rem;
    font-size: 22rem;
    opacity: 0.07;
    line-height: 1;
    pointer-events: none;
}

.vol-tag {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.72rem;
    font-weight: 800;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.6);
    margin-bottom: 1.25rem;
    border: 1px solid rgba(255,255,255,0.2);
    padding: 0.3rem 0.85rem;
    border-radius: 2rem;
    width: fit-content;
}

.ideas2-hero h1 {
    font-family: var(--font-serif);
    font-size: 3.75rem;
    line-height: 1.05;
    margin-bottom: 1.25rem;
    color: #fff;
}

.ideas2-hero h1 em {
    font-style: italic;
    color: var(--color-accent);
}

.ideas2-hero p {
    font-size: 1.05rem;
    color: rgba(255,255,255,0.65);
    max-width: 520px;
    line-height: 1.7;
}

.prev-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 2rem;
    font-size: 0.85rem;
    font-weight: 600;
    color: rgba(255,255,255,0.5);
    text-decoration: none;
    transition: color 0.2s;
}
.prev-link:hover { color: #fff; }

/* ============================================
   GRID
   ============================================ */
.ideas2-section {
    padding: 4rem 0 5rem;
    background: var(--color-bg);
}

.ideas2-grid {
    display: grid;
    grid-template-columns: repeat(12, 1fr);
    gap: 1.25rem;
}

/* Asymmetric layout — different from vol 1 */
.i2card:nth-child(1) { grid-column: span 7; }
.i2card:nth-child(2) { grid-column: span 5; }
.i2card:nth-child(3) { grid-column: span 4; }
.i2card:nth-child(4) { grid-column: span 4; }
.i2card:nth-child(5) { grid-column: span 4; }
.i2card:nth-child(6) { grid-column: span 5; }
.i2card:nth-child(7) { grid-column: span 7; }
.i2card:nth-child(8) { grid-column: span 12; }

.i2card {
    background: var(--color-bg-white);
    border: 2px solid var(--color-border-light);
    border-radius: var(--radius);
    padding: 2rem 2rem 1.75rem;
    position: relative;
    overflow: hidden;
    animation: fadeSlide 0.4s ease both;
    transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
    display: flex;
    flex-direction: column;
}

.i2card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 36px rgba(45,32,23,0.09);
    border-color: var(--color-border);
}

.i2card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 4px;
}

/* card accent colours */
.i2card.c-flip::before   { background: #6366f1; }  /* indigo — exit play */
.i2card.c-press::before  { background: #0ea5e9; }  /* sky — media angle */
.i2card.c-viral::before  { background: var(--color-accent); }
.i2card.c-deal::before   { background: var(--color-primary); }
.i2card.c-season::before { background: #f59e0b; }
.i2card.c-license::before{ background: #10b981; }
.i2card.c-sync::before   { background: #ec4899; }
.i2card.c-tool::before   { background: #8b5cf6; }

.i2bg-num {
    position: absolute;
    bottom: -1.75rem;
    right: 0.25rem;
    font-family: var(--font-serif);
    font-size: 7rem;
    line-height: 1;
    color: var(--color-border-light);
    user-select: none;
    pointer-events: none;
}

.i2card-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 0.9rem;
}

.i2num {
    font-size: 0.68rem;
    font-weight: 800;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--color-text-light);
    margin-bottom: 0.5rem;
}

.i2emoji { font-size: 2rem; line-height: 1; }

.spicy-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    background: var(--color-text);
    color: var(--color-accent);
    font-size: 0.62rem;
    font-weight: 800;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    padding: 0.22rem 0.6rem;
    border-radius: 2rem;
    white-space: nowrap;
}

.i2card h3 {
    font-family: var(--font-serif);
    font-size: 1.3rem;
    line-height: 1.25;
    margin-bottom: 0.6rem;
    color: var(--color-text);
}

.i2card p {
    font-size: 0.88rem;
    color: var(--color-text-light);
    line-height: 1.65;
    margin: 0 0 1.25rem;
    flex: 1;
}

/* why it works callout */
.why-it-works {
    margin-top: auto;
    padding: 0.75rem 1rem;
    background: var(--color-bg);
    border-left: 3px solid var(--color-primary);
    border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
    font-size: 0.8rem;
    color: var(--color-text);
    line-height: 1.5;
}

.why-it-works strong {
    display: block;
    font-size: 0.68rem;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: var(--color-primary);
    font-weight: 800;
    margin-bottom: 0.2rem;
}

/* wide card */
.i2card-wide-inner {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2.5rem;
    align-items: start;
}

.i2card-wide-right {
    border-left: 2px solid var(--color-border-light);
    padding-left: 2.5rem;
}

.i2card-wide-right h4 {
    font-family: var(--font-serif);
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
}

.i2card-wide-right p {
    font-size: 0.88rem;
    color: var(--color-text-light);
    line-height: 1.65;
    margin-bottom: 1rem;
}

.i2card-wide-right p:last-child { margin-bottom: 0; }

/* ============================================
   BOTTOM PROVOCATION
   ============================================ */
.provocation {
    background: var(--color-text);
    padding: 5rem 0;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.provocation::before {
    content: '?';
    font-family: var(--font-serif);
    font-size: 40rem;
    color: rgba(255,255,255,0.03);
    position: absolute;
    top: -8rem;
    left: 50%;
    transform: translateX(-50%);
    line-height: 1;
    pointer-events: none;
}

.provocation h2 {
    font-family: var(--font-serif);
    font-size: 2.75rem;
    color: #fff;
    max-width: 680px;
    margin: 0 auto 1.25rem;
    line-height: 1.2;
}

.provocation h2 em {
    font-style: normal;
    color: var(--color-accent);
}

.provocation p {
    font-size: 1.05rem;
    color: rgba(255,255,255,0.5);
    max-width: 500px;
    margin: 0 auto 2.5rem;
    line-height: 1.7;
}

.btn-ghost-white {
    display: inline-block;
    padding: 0.85rem 2rem;
    font-size: 1rem;
    font-weight: 600;
    text-decoration: none;
    border-radius: var(--radius-sm);
    border: 2px solid rgba(255,255,255,0.25);
    color: #fff;
    transition: all 0.2s;
    margin: 0 0.5rem;
}
.btn-ghost-white:hover {
    border-color: rgba(255,255,255,0.6);
    color: #fff;
    background: rgba(255,255,255,0.05);
}

/* ============================================
   RESPONSIVE
   ============================================ */
@media (max-width: 1024px) {
    .i2card:nth-child(n) { grid-column: span 6; }
    .i2card-wide-inner { grid-template-columns: 1fr; gap: 1.5rem; }
    .i2card-wide-right { border-left: none; border-top: 2px solid var(--color-border-light); padding-left: 0; padding-top: 1.5rem; }
    .provocation h2 { font-size: 2rem; }
}

@media (max-width: 640px) {
    .ideas2-hero h1 { font-size: 2.4rem; }
    .i2card:nth-child(n) { grid-column: span 12; }
    .provocation h2 { font-size: 1.75rem; }
}
</style>

<?php include __DIR__ . '/includes/header.php'; ?>

<!-- HERO -->
<section class="ideas2-hero">
    <div class="container">
        <div class="vol-tag">Vol. 2 &mdash; The Interesting Ones</div>
        <h1>Not the obvious stuff.<br><em>The good stuff.</em></h1>
        <p>Anyone can tell you to do affiliate links. These are the eight ideas that actually get interesting — the ones where the upside is genuinely disproportionate to the effort.</p>
        <a href="/ideas.php" class="prev-link">&#8592; Vol. 1 — The quick wins</a>
    </div>
</section>

<!-- GRID -->
<section class="ideas2-section">
    <div class="container">
        <div class="ideas2-grid">

            <!-- 01 Flip the site -->
            <div class="i2card c-flip">
                <div class="i2bg-num">01</div>
                <div class="i2card-top">
                    <div>
                        <div class="i2num">Idea 01</div>
                        <div class="i2emoji">🏷️</div>
                    </div>
                    <span class="spicy-badge">&#x1F525; Big upside</span>
                </div>
                <h3>Build it to flip it</h3>
                <p>The site itself is an asset. Food content sites sell on Flippa for 30–40× monthly revenue. Get to £500/month in ad + affiliate income, and the site is worth £15–20k to the right buyer — someone who wants traffic without doing the work. You don't have to sell. But knowing the exit exists changes how you think about every decision.</p>
                <div class="why-it-works">
                    <strong>Why it's underrated</strong>
                    Most people build websites as hobbies. Treating it as an asset from day one — clean analytics, documented income, growing traffic — costs nothing extra and multiplies the outcome.
                </div>
            </div>

            <!-- 02 Toast Price Index -->
            <div class="i2card c-press">
                <div class="i2bg-num">02</div>
                <div class="i2card-top">
                    <div>
                        <div class="i2num">Idea 02</div>
                        <div class="i2emoji">📰</div>
                    </div>
                </div>
                <h3>The Top Toast Price Index</h3>
                <p>Publish a monthly tracker: the price of avocado, sourdough, tahini, free-range eggs, fancy butter. One spreadsheet, one blog post, one chart. Journalists quote it. Food editors link to it. It becomes a legit press source — which means free backlinks, domain authority, and SEO you can't buy.</p>
                <div class="why-it-works">
                    <strong>Why it works</strong>
                    The ONS publishes the CPI. Nobody publishes the toast index. You could own this niche. Once established, press will come to you.
                </div>
            </div>

            <!-- 03 Personality Quiz -->
            <div class="i2card c-viral">
                <div class="i2bg-num">03</div>
                <div class="i2card-top">
                    <div>
                        <div class="i2num">Idea 03</div>
                        <div class="i2emoji">🎯</div>
                    </div>
                    <span class="spicy-badge">Goes viral</span>
                </div>
                <h3>"What toast are you?"</h3>
                <p>A personality quiz. 8 questions. "You're a ricotta honey fig on sourdough." People share their results obsessively. Free tools like Typeform or Interact build it in an hour. Capture email at the end. A BuzzFeed-style quiz about toast is genuinely shareable — and every share is free acquisition.</p>
                <div class="why-it-works">
                    <strong>Why it works</strong>
                    Quizzes have one of the highest share rates of any content format. And toast has enough personality variants to make the results feel weirdly accurate.
                </div>
            </div>

            <!-- 04 AI Toast Generator -->
            <div class="i2card c-tool">
                <div class="i2bg-num">04</div>
                <div class="i2card-top">
                    <div>
                        <div class="i2num">Idea 04</div>
                        <div class="i2emoji">🤖</div>
                    </div>
                    <span class="spicy-badge">Sticky</span>
                </div>
                <h3>The Toast Generator</h3>
                <p>"Tell me what's in your fridge." You get a personalised toast recipe. Simple API call, one page on the site, zero ongoing work. Tools drive return visits. People bookmark it. They send it to friends. It's the kind of thing that gets mentioned in food newsletters with a "this is silly and I love it" energy.</p>
                <div class="why-it-works">
                    <strong>Why it works</strong>
                    Most food sites are static. An interactive tool makes you memorable and creates a reason to come back that recipes alone don't.
                </div>
            </div>

            <!-- 05 Toast Advent Calendar -->
            <div class="i2card c-season">
                <div class="i2bg-num">05</div>
                <div class="i2card-top">
                    <div>
                        <div class="i2num">Idea 05</div>
                        <div class="i2emoji">🎄</div>
                    </div>
                </div>
                <h3>The Toast Advent Calendar</h3>
                <p>25 days of toast recipes, beautifully designed PDF. Goes on sale every November for £5. You write it once and sell it every year. Seasonal urgency drives purchases. By year three it basically sells itself off the back catalogue. The Christmas Toast Collection is a gift people actually want to buy.</p>
                <div class="why-it-works">
                    <strong>Why it works</strong>
                    Seasonal digital products have built-in urgency. No discounting needed — December 1st does the marketing for you.
                </div>
            </div>

            <!-- 06 B&B Licensing -->
            <div class="i2card c-license">
                <div class="i2bg-num">06</div>
                <div class="i2card-top">
                    <div>
                        <div class="i2num">Idea 06</div>
                        <div class="i2emoji">🛏️</div>
                    </div>
                </div>
                <h3>Boutique Hotel &amp; B&amp;B Licensing</h3>
                <p>Sell a breakfast recipe licence to independent hotels and B&Bs. They get a printed "Recipes by Top Toast" card on every breakfast table. They get a differentiator. You get £49/year, repeated across however many places you can email in an afternoon. There are 30,000 B&Bs in the UK alone.</p>
                <div class="why-it-works">
                    <strong>Why it works</strong>
                    Independent accommodation owners are underserved and genuinely want this kind of thing. Low ticket, but 200 customers = £9,800/year for one cold outreach campaign.
                </div>
            </div>

            <!-- 07 Content Syndication -->
            <div class="i2card c-sync">
                <div class="i2bg-num">07</div>
                <div class="i2card-top">
                    <div>
                        <div class="i2num">Idea 07</div>
                        <div class="i2emoji">🔄</div>
                    </div>
                    <span class="spicy-badge">Underused</span>
                </div>
                <h3>Sell the Recipes Wholesale</h3>
                <p>Supermarkets, meal kit companies, and food magazines all need recipe content constantly and hate commissioning it from scratch. A pack of 10 tested, photographed toast recipes licensed for £500 is a bargain to a Waitrose Weekend editor. You've already written them. This is just distribution.</p>
                <div class="why-it-works">
                    <strong>Why it works</strong>
                    Recipe syndication is a real industry that small content creators never tap. Your content has already been produced — licensing it is pure margin.
                </div>
            </div>

            <!-- 08 Brand Licensing — WIDE -->
            <div class="i2card c-deal">
                <div class="i2bg-num">08</div>
                <div class="i2card-wide-inner">
                    <div>
                        <div class="i2card-top" style="margin-bottom:0.75rem">
                            <div>
                                <div class="i2num">Idea 08</div>
                                <div class="i2emoji">🏆</div>
                            </div>
                            <span class="spicy-badge">&#x1F525; Long game</span>
                        </div>
                        <h3>License "Top Toast Approved"<br>to a Toaster Brand</h3>
                        <p>Approach a mid-range toaster brand — Russell Hobbs, Dualit, Swan. Offer to co-brand: their product gets a "Top Toast Approved" badge and a recipe booklet in the box. You get a royalty per unit sold plus a lump fee. One deal. Passive for years. The brand gets credibility with toast enthusiasts. You get a royalty cheque every quarter for recommending a toaster.</p>
                    </div>
                    <div class="i2card-wide-right">
                        <h4>What makes this actually possible</h4>
                        <p>Toaster brands spend heavily on influencer partnerships already. A dedicated toast authority with a real audience is more credible than a generic lifestyle influencer. The pitch writes itself: "We are the only website in the UK entirely dedicated to toast. Your toaster should be in our kitchen."</p>
                        <p>You don't need a big audience to have this conversation. You need the right framing and one good email. The worst they say is no.</p>
                        <div class="why-it-works" style="margin-top:1rem">
                            <strong>The asymmetry</strong>
                            One yes from one brand could be worth more than everything else on this list combined. Low probability, but the effort to try is almost zero.
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- PROVOCATION -->
<section class="provocation">
    <div class="container">
        <h2>The real question is:<br><em>which one do you actually do?</em></h2>
        <p>Eight more ideas that go nowhere is just entertainment. Pick one. Do the first step. The site already exists — that's the hard part done.</p>
        <a href="/ideas.php" class="btn-ghost-white">&#8592; Vol. 1 ideas</a>
        <a href="/roadmap.php" class="btn btn-primary">See the full roadmap</a>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
