# TopToast - LLM Operating Manual

## Ground Rules

### No Fallbacks. Ever.
Never use fallback values, default alternatives, or silent recovery. If something is missing or wrong, **break loudly**. A crash in development is a gift — it forces an immediate, permanent fix.

- No `?? 'default'` or `?:` fallbacks for config values that should exist
- No `try/catch` that silently swallows errors
- If a required value is missing, crash. Don't guess.

### SOLID, YAGNI, KISS, DRY
- Single responsibility. Classes do one thing.
- Don't build it until you need it. No speculative features.
- Simplest solution that works is the right one.
- Duplicate 2-3 times before extracting. Premature abstraction is worse than repetition.

### Never Swallow Errors
If you catch an exception, it must be to add context before re-throwing, or to handle a specific expected case. No empty catch blocks.

---

## Site File Conventions

### config.php (READ THIS FIRST)
Location: `includes/config.php`
Contains: site_name, domain, meta_description, nav structure, contact details.
This is the single source of truth for the site.

### Page structure
Every page follows this pattern:
```php
<?php $config = require __DIR__ . '/../includes/config.php'; ?>
<?php include __DIR__ . '/../includes/header.php'; ?>

<!-- Page content here -->

<?php include __DIR__ . '/../includes/footer.php'; ?>
```

### header.php
- Outputs `<!DOCTYPE html>`, `<head>` with meta tags from config
- Opens `<body>`, renders navigation from `$config['nav']`

### footer.php
- Closes `</body></html>`, includes footer content

### CSS
- Location: `public/css/style.css`
- Mobile-first, responsive. No CSS frameworks unless requested.

### JavaScript
- Location: `public/js/main.js`
- Vanilla JS preferred, minimal dependencies.

---

## Style Guidelines

- Modern, semantic HTML5
- Mobile-first responsive CSS (no frameworks unless requested)
- Minimal JavaScript — vanilla JS preferred
- Fast load times — optimise images, minimal dependencies
- Accessible: proper alt tags, ARIA labels, semantic elements
- Clean, professional design with good typography and spacing
