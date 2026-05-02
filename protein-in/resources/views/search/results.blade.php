@extends('layouts.app')

@section('title', 'Protein in "' . $query . '" — Protein-In')
@section('description', 'How much protein is in ' . $query . '? See protein content per 100g for all matching foods.')

@section('content')
<h1>Protein in "{{ $query }}"</h1>

<div id="import-status" style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1.25rem;color:#b45309;font-size:0.9rem;">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;animation:spin 1s linear infinite;">
        <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
    </svg>
    <span id="import-msg">{{ $needsImport ? 'Searching our database…' : 'Cross-referencing results…' }}</span>
</div>
<style>@keyframes spin { to { transform: rotate(360deg); } }</style>

<div id="result-meta" style="display:none;margin-bottom:0.75rem;">
    <span class="meta" id="result-count">{{ $foods->total() }} {{ Str::plural('result', $foods->total()) }}</span>
    <span id="filter-count" style="display:none;color:#b45309;font-size:0.875rem;margin-left:0.5rem;"></span>
</div>

<div style="margin-bottom:1rem;">
    <input
        type="text"
        id="filter-input"
        placeholder="Filter results… e.g. raw, grilled, canned"
        value="{{ $filter }}"
        style="width:100%;max-width:420px;padding:0.5rem 0.85rem;border:1px solid #d6d3d1;border-radius:6px;font-size:0.9rem;background:#fff;outline:none;"
        autocomplete="off"
    >
</div>

<div id="results-grid" class="food-grid">
    @include('search._results_grid', ['foods' => $foods])
</div>

<script>
(function () {
    const needsImport = {{ $needsImport ? 'true' : 'false' }};
    const baseUrl = '{{ route('search.show', rawurlencode($query)) }}';
    const freshMessages = [
        'Searching our database…',
        'Checking spelling variants…',
        'Finding matches…',
        'Fetching from USDA…',
        'Calculating macros…',
        'Extracting protein data…',
        'Cross-referencing nutrients…',
        'Almost there…',
    ];
    const repeatMessages = [
        'Cross-referencing results…',
        'Making sure we\'re up to date…',
        'Checking for new data…',
        'Verifying nutrient info…',
    ];

    const messages = needsImport ? freshMessages : repeatMessages;
    let i = 0;
    const el = document.getElementById('import-msg');
    const statusEl = document.getElementById('import-status');
    const metaEl = document.getElementById('result-meta');
    const countEl = document.getElementById('result-count');
    const filterCountEl = document.getElementById('filter-count');
    const gridEl = document.getElementById('results-grid');
    const filterInput = document.getElementById('filter-input');

    const timer = setInterval(() => {
        i = (i + 1) % messages.length;
        el.textContent = messages[i];
    }, 600);

    function done(imported) {
        clearInterval(timer);
        if (imported > 0) {
            window.location.reload();
        } else {
            statusEl.style.display = 'none';
            metaEl.style.display = '';
        }
    }

    fetch('{{ route('search.backfill', rawurlencode($query)) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        },
    })
    .then(r => r.json())
    .then(data => done(data.imported ?? 0))
    .catch(() => done(0));

    // --- Filter box ---
    let filterTimer = null;
    let currentFilter = '{{ addslashes($filter) }}';

    function applyFilter(value) {
        const params = new URLSearchParams({ filter: value });
        const url = baseUrl + '?' + params.toString();

        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            gridEl.innerHTML = data.html;
            const total = data.total;
            if (value) {
                countEl.textContent = total + ' ' + (total === 1 ? 'result' : 'results') + ' for "{{ addslashes($query) }}"';
                filterCountEl.textContent = '(' + total + ' match' + (total !== 1 ? 'es' : '') + ' "' + value + '")';
                filterCountEl.style.display = '';
            } else {
                countEl.textContent = total + ' ' + (total === 1 ? 'result' : 'results');
                filterCountEl.style.display = 'none';
            }
            // Update URL without reload
            const historyParams = value ? '?filter=' + encodeURIComponent(value) : '';
            history.replaceState(null, '', baseUrl + historyParams);
        })
        .catch(() => {});
    }

    filterInput.addEventListener('input', function () {
        clearTimeout(filterTimer);
        const val = this.value.trim();
        filterTimer = setTimeout(() => applyFilter(val), 280);
    });

    filterInput.addEventListener('focus', function () {
        this.style.borderColor = '#b45309';
        this.style.boxShadow = '0 0 0 2px #fef3c7';
    });
    filterInput.addEventListener('blur', function () {
        this.style.borderColor = '#d6d3d1';
        this.style.boxShadow = '';
    });
})();
</script>
@endsection
