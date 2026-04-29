<?php $config = require __DIR__ . '/includes/config.php'; ?>
<?php $pageTitle = 'The Roadmap'; ?>
<?php $pageDescription = 'Where Top Toast came from, where it\'s going, and why toast is going to be a serious business.'; ?>

<style>
/* ============================================
   ROADMAP PAGE
   ============================================ */

.roadmap-hero {
    padding: 5rem 0 4rem;
    text-align: center;
    background: linear-gradient(160deg, var(--color-bg) 0%, var(--color-primary-light) 60%, var(--color-accent-light) 100%);
    position: relative;
    overflow: hidden;
}

.roadmap-hero::before {
    content: '🍞';
    position: absolute;
    font-size: 18rem;
    opacity: 0.06;
    top: -3rem;
    right: -3rem;
    line-height: 1;
    pointer-events: none;
    user-select: none;
}

.roadmap-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--color-accent);
    color: var(--color-text);
    font-weight: 700;
    font-size: 0.8rem;
    padding: 0.4rem 1rem;
    border-radius: 2rem;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 1.5rem;
}

.roadmap-hero h1 {
    font-family: var(--font-serif);
    font-size: 4rem;
    line-height: 1.1;
    margin-bottom: 1.25rem;
    color: var(--color-text);
}

.roadmap-hero h1 span {
    color: var(--color-primary);
}

.roadmap-hero .lead {
    font-size: 1.2rem;
    color: var(--color-text-light);
    max-width: 560px;
    margin: 0 auto 2rem;
    line-height: 1.7;
}

.roadmap-hero .meta-note {
    display: inline-block;
    font-size: 0.85rem;
    color: var(--color-text-light);
    background: rgba(255,255,255,0.7);
    border: 1px solid var(--color-border);
    border-radius: 2rem;
    padding: 0.35rem 1rem;
}

/* ============================================
   JOURNEY TIMELINE
   ============================================ */
.journey-section {
    padding: 5rem 0;
    background: var(--color-bg);
}

.section-label {
    display: inline-block;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--color-primary);
    margin-bottom: 0.5rem;
}

.section-title {
    font-family: var(--font-serif);
    font-size: 2.25rem;
    margin-bottom: 0.75rem;
    color: var(--color-text);
}

.section-sub {
    font-size: 1.05rem;
    color: var(--color-text-light);
    max-width: 540px;
    line-height: 1.6;
    margin-bottom: 3.5rem;
}

.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0.75rem;
    bottom: 1rem;
    width: 3px;
    background: linear-gradient(to bottom, var(--color-primary), var(--color-accent), var(--color-border-light));
    border-radius: 3px;
}

.timeline-item {
    position: relative;
    padding: 0 0 3rem 2.5rem;
}

.timeline-item:last-child {
    padding-bottom: 0;
}

.timeline-dot {
    position: absolute;
    left: -2rem;
    top: 0.6rem;
    width: 1.25rem;
    height: 1.25rem;
    border-radius: 50%;
    background: var(--color-primary);
    border: 3px solid var(--color-bg);
    box-shadow: 0 0 0 2px var(--color-primary);
    transform: translateX(-50%);
    left: -0.1rem;
}

.timeline-dot.done { background: var(--color-primary); box-shadow: 0 0 0 2px var(--color-primary); }
.timeline-dot.current { background: var(--color-accent); box-shadow: 0 0 0 2px var(--color-accent); }
.timeline-dot.future { background: var(--color-border); box-shadow: 0 0 0 2px var(--color-border); }

.timeline-year {
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--color-text-light);
    margin-bottom: 0.25rem;
}

.timeline-item h3 {
    font-family: var(--font-serif);
    font-size: 1.4rem;
    margin-bottom: 0.5rem;
    color: var(--color-text);
}

.timeline-item p {
    color: var(--color-text-light);
    font-size: 0.95rem;
    line-height: 1.65;
    max-width: 540px;
    margin: 0;
}

.timeline-tag {
    display: inline-block;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    padding: 0.2rem 0.65rem;
    border-radius: 2rem;
    margin-bottom: 0.6rem;
}

.tag-done { background: #d4edda; color: #1a6b34; }
.tag-live { background: var(--color-primary); color: #fff; }
.tag-coming { background: var(--color-accent-light); color: #8a6a00; border: 1px solid var(--color-accent); }
.tag-dream { background: var(--color-bg-alt); color: var(--color-text-light); border: 1px solid var(--color-border); }

/* ============================================
   PHASES
   ============================================ */
.phases-section {
    padding: 5rem 0;
    background: var(--color-bg-alt);
    border-top: 2px solid var(--color-border-light);
    border-bottom: 2px solid var(--color-border-light);
}

.phases-header {
    max-width: 600px;
    margin-bottom: 3.5rem;
}

.phase-card {
    background: var(--color-bg-white);
    border: 2px solid var(--color-border-light);
    border-radius: var(--radius);
    padding: 2.5rem;
    margin-bottom: 1.75rem;
    position: relative;
    overflow: hidden;
    transition: box-shadow 0.2s, border-color 0.2s;
}

.phase-card:hover {
    box-shadow: 0 8px 32px rgba(45,32,23,0.08);
    border-color: var(--color-border);
}

.phase-card.phase-active {
    border-color: var(--color-primary);
    border-width: 2px;
}

.phase-card.phase-active::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--color-primary);
}

.phase-card.phase-dream::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--color-accent), var(--color-primary));
}

.phase-number {
    position: absolute;
    top: 1.75rem;
    right: 2rem;
    font-family: var(--font-serif);
    font-size: 5rem;
    line-height: 1;
    color: var(--color-border-light);
    font-weight: 400;
    user-select: none;
    pointer-events: none;
}

.phase-top {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.phase-icon {
    font-size: 2.25rem;
    flex-shrink: 0;
    line-height: 1;
}

.phase-top-text {}

.phase-top-text h2 {
    font-family: var(--font-serif);
    font-size: 1.6rem;
    margin-bottom: 0.25rem;
    line-height: 1.2;
}

.phase-top-text p {
    color: var(--color-text-light);
    font-size: 0.95rem;
    margin: 0;
    line-height: 1.5;
}

.phase-items {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    margin-top: 1.5rem;
}

.phase-item {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 1rem 1.25rem;
    background: var(--color-bg);
    border: 1.5px solid var(--color-border-light);
    border-radius: var(--radius-sm);
}

.phase-item-icon {
    font-size: 1.4rem;
    flex-shrink: 0;
    line-height: 1;
    margin-top: 0.1rem;
}

.phase-item h4 {
    font-size: 0.95rem;
    font-weight: 600;
    margin-bottom: 0.2rem;
    line-height: 1.3;
}

.phase-item p {
    font-size: 0.82rem;
    color: var(--color-text-light);
    margin: 0;
    line-height: 1.5;
}

/* ============================================
   VISION / CLOSER
   ============================================ */
.vision-section {
    padding: 5rem 0 6rem;
    background: var(--color-text);
    color: #fff;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.vision-section::before {
    content: '🍞';
    position: absolute;
    font-size: 24rem;
    opacity: 0.04;
    bottom: -4rem;
    left: -4rem;
    line-height: 1;
    pointer-events: none;
    user-select: none;
}

.vision-section .section-label {
    color: var(--color-accent);
}

.vision-section h2 {
    font-family: var(--font-serif);
    font-size: 3rem;
    line-height: 1.2;
    max-width: 700px;
    margin: 0.75rem auto 1.5rem;
    color: #fff;
}

.vision-section h2 em {
    font-style: normal;
    color: var(--color-accent);
}

.vision-section p {
    font-size: 1.1rem;
    color: rgba(255,255,255,0.65);
    max-width: 520px;
    margin: 0 auto 2.5rem;
    line-height: 1.7;
}

.vision-section .btn-primary {
    background: var(--color-primary);
    color: #fff;
}

.vision-stat-row {
    display: flex;
    justify-content: center;
    gap: 4rem;
    margin-bottom: 3.5rem;
    flex-wrap: wrap;
}

.vision-stat {
    text-align: center;
}

.vision-stat-num {
    font-family: var(--font-serif);
    font-size: 3rem;
    color: var(--color-accent);
    line-height: 1;
    margin-bottom: 0.25rem;
    display: block;
}

.vision-stat-label {
    font-size: 0.85rem;
    color: rgba(255,255,255,0.5);
    text-transform: uppercase;
    letter-spacing: 0.06em;
    font-weight: 600;
}

/* ============================================
   RESPONSIVE
   ============================================ */
@media (max-width: 768px) {
    .roadmap-hero h1 { font-size: 2.5rem; }
    .section-title { font-size: 1.75rem; }
    .phase-items { grid-template-columns: 1fr; }
    .phase-number { font-size: 3.5rem; top: 1.5rem; right: 1.5rem; }
    .vision-section h2 { font-size: 2rem; }
    .vision-stat-row { gap: 2rem; }
    .timeline { padding-left: 1.5rem; }
    .timeline-item { padding-left: 2rem; }
}
</style>

<?php include __DIR__ . '/includes/header.php'; ?>

<!-- HERO -->
<section class="roadmap-hero">
    <div class="container">
        <div class="roadmap-eyebrow">&#x1F35E; The Toast Empire Plan</div>
        <h1>Where <span>Top Toast</span><br>is going.</h1>
        <p class="lead">A recipe site is just the beginning. Here's the full vision — from bread and butter to a proper toast-obsessed brand.</p>
        <span class="meta-note">Confidential-ish &mdash; for business partner eyes</span>
    </div>
</section>

<!-- JOURNEY -->
<section class="journey-section">
    <div class="container">
        <span class="section-label">Chapter One</span>
        <h2 class="section-title">The Journey So Far</h2>
        <p class="section-sub">We didn't start here. Three formats, one obsession: toast deserves to be taken seriously.</p>

        <div class="timeline">

            <div class="timeline-item">
                <div class="timeline-dot done"></div>
                <div class="timeline-year">The Beginning</div>
                <span class="timeline-tag tag-done">Done &amp; dusted</span>
                <h3>&#x1F4F1; The iPhone App</h3>
                <p>It started with an app. Swipeable toast recipes, beautiful photography, a community of people who thought toast was more than a Tuesday morning afterthought. We proved the appetite was real.</p>
            </div>

            <div class="timeline-item">
                <div class="timeline-dot done"></div>
                <div class="timeline-year">Chapter Two</div>
                <span class="timeline-tag tag-done">Done &amp; dusted</span>
                <h3>&#x1F4E6; The Subscription Box</h3>
                <p>Monthly boxes. Artisan breads, curated toppings, recipe cards. Real product, real customers, real feedback. We learned what people love, what they'll pay for, and that toast boxes make excellent gifts.</p>
            </div>

            <div class="timeline-item">
                <div class="timeline-dot current"></div>
                <div class="timeline-year">Right Now</div>
                <span class="timeline-tag tag-live">Live</span>
                <h3>&#x1F310; The Recipe Website</h3>
                <p>toptoast.com — a destination for genuinely great toast recipes. Building the audience, the content library, and the brand. This is the platform everything else launches from.</p>
            </div>

        </div>
    </div>
</section>

<!-- PHASES -->
<section class="phases-section">
    <div class="container">
        <div class="phases-header">
            <span class="section-label">Chapter Two</span>
            <h2 class="section-title">The Plan</h2>
            <p class="section-sub">Three phases. Each one builds on the last. The goal: toast as a lifestyle brand people actually buy into.</p>
        </div>

        <!-- Phase 1 -->
        <div class="phase-card phase-active">
            <div class="phase-number">1</div>
            <div class="phase-top">
                <div class="phase-icon">&#x1F331;</div>
                <div class="phase-top-text">
                    <span class="timeline-tag tag-live">Live Now</span>
                    <h2>Build the Community</h2>
                    <p>Grow an audience that genuinely loves toast. Recipes are the hook — trust is the product.</p>
                </div>
            </div>
            <div class="phase-items">
                <div class="phase-item">
                    <div class="phase-item-icon">&#x1F4D6;</div>
                    <div>
                        <h4>Recipe Library</h4>
                        <p>50+ tested, beautiful recipes across sweet, savoury, brunch and quick categories. Quality over quantity.</p>
                    </div>
                </div>
                <div class="phase-item">
                    <div class="phase-item-icon">&#x2709;&#xFE0F;</div>
                    <div>
                        <h4>Newsletter</h4>
                        <p>Weekly "Toast of the Week" email. One recipe, one tip, one product we love. Build the list.</p>
                    </div>
                </div>
                <div class="phase-item">
                    <div class="phase-item-icon">&#x1F4F7;</div>
                    <div>
                        <h4>Social Content</h4>
                        <p>Instagram and TikTok-first. Short recipe videos that are too good not to share. Toast is visual.</p>
                    </div>
                </div>
                <div class="phase-item">
                    <div class="phase-item-icon">&#x2B50;</div>
                    <div>
                        <h4>SEO Foundation</h4>
                        <p>Own "toast recipes" in search. Long-tail keywords, structured data, fast pages. Organic traffic = free audience.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Phase 2 -->
        <div class="phase-card">
            <div class="phase-number">2</div>
            <div class="phase-top">
                <div class="phase-icon">&#x1F4B0;</div>
                <div class="phase-top-text">
                    <span class="timeline-tag tag-coming">Coming Soon</span>
                    <h2>Monetise the Audience</h2>
                    <p>Turn the readership into revenue. Multiple streams, none of them annoying.</p>
                </div>
            </div>
            <div class="phase-items">
                <div class="phase-item">
                    <div class="phase-item-icon">&#x1F91D;</div>
                    <div>
                        <h4>Brand Partnerships</h4>
                        <p>Sponsored recipes with quality bread, butter, and topping brands. Honest, editorial-style integrations only.</p>
                    </div>
                </div>
                <div class="phase-item">
                    <div class="phase-item-icon">&#x1F4DA;</div>
                    <div>
                        <h4>Premium Recipe Collections</h4>
                        <p>£8–15 digital downloads. "The Weekend Toast", "Dinner Party Toasts", "Toast for One". Beautiful PDFs people actually print.</p>
                    </div>
                </div>
                <div class="phase-item">
                    <div class="phase-item-icon">&#x1F517;</div>
                    <div>
                        <h4>Affiliate Products</h4>
                        <p>Curated shop linking to artisan breads, toasters, spreads, and boards we genuinely use and recommend.</p>
                    </div>
                </div>
                <div class="phase-item">
                    <div class="phase-item-icon">&#x1F3AB;</div>
                    <div>
                        <h4>Toast Club Membership</h4>
                        <p>Monthly subscribers get early recipes, behind-the-scenes content, and member discounts. £4.99/mo.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Phase 3 -->
        <div class="phase-card phase-dream">
            <div class="phase-number">3</div>
            <div class="phase-top">
                <div class="phase-icon">&#x1F451;</div>
                <div class="phase-top-text">
                    <span class="timeline-tag tag-dream">The Dream</span>
                    <h2>The Toast Empire</h2>
                    <p>This is what we're building toward. A brand with real-world presence and serious revenue.</p>
                </div>
            </div>
            <div class="phase-items">
                <div class="phase-item">
                    <div class="phase-item-icon">&#x1F4E6;</div>
                    <div>
                        <h4>Subscription Box Revival</h4>
                        <p>Monthly topping kits: seasonal jams, infused oils, specialty butters, and a recipe card. £25/box, 2,000 subscribers = £50k/mo.</p>
                    </div>
                </div>
                <div class="phase-item">
                    <div class="phase-item-icon">&#x1F4D5;</div>
                    <div>
                        <h4>Top Toast Cookbook</h4>
                        <p>A proper, beautiful hardback. The toast book the world doesn't know it needs yet. Traditional publisher or self-published via crowdfunding.</p>
                    </div>
                </div>
                <div class="phase-item">
                    <div class="phase-item-icon">&#x1F942;</div>
                    <div>
                        <h4>Pop-Up Toast Bars</h4>
                        <p>Weekend events at food markets and festivals. A menu of 8 signature toasts. Experiential, Instagrammable, and profitable per event.</p>
                    </div>
                </div>
                <div class="phase-item">
                    <div class="phase-item-icon">&#x1FA9B;</div>
                    <div>
                        <h4>Branded Merchandise</h4>
                        <p>Quality kit: bread knives, olive wood boards, linen napkins, and the world's best toaster. Stuff people actually want in their kitchen.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- VISION -->
<section class="vision-section">
    <div class="container">
        <div class="vision-stat-row">
            <div class="vision-stat">
                <span class="vision-stat-num">3</span>
                <span class="vision-stat-label">Revenue Streams Active</span>
            </div>
            <div class="vision-stat">
                <span class="vision-stat-num">50+</span>
                <span class="vision-stat-label">Recipes &amp; Counting</span>
            </div>
            <div class="vision-stat">
                <span class="vision-stat-num">&#x221E;</span>
                <span class="vision-stat-label">Toast Combinations</span>
            </div>
        </div>
        <span class="section-label">The Vision</span>
        <h2>Toast is a <em>serious business.</em><br>We're the ones proving it.</h2>
        <p>Other food brands are built on complexity — expensive ingredients, fancy techniques, intimidating skills. We're building on something every single person already owns a toaster for. The opportunity is enormous, the competition is asleep, and the product is delicious.</p>
        <a href="/recipes.php" class="btn btn-primary">Start with the recipes</a>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
