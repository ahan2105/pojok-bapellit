<script>
(function () {
    'use strict';

    // Data dan URL dari server
    var templates    = @json($templateData);
    var storeUrl     = @json(route('admin.settings.chatbot.store'));
    var updateUrlTpl = @json(route('admin.settings.chatbot.update', ['template' => '__ID__']));

    // Elemen form
    var form          = document.getElementById('form-template');
    var methodField   = document.getElementById('method-field');
    var labelInput    = document.getElementById('input-label');
    var keywordsInput = document.getElementById('input-keywords');
    var replyInput    = document.getElementById('input-reply');
    var formTitle     = document.getElementById('form-title');
    var formBadge     = document.getElementById('form-badge');
    var btnSubmit     = document.getElementById('btn-submit');
    var btnCancel     = document.getElementById('btn-cancel-edit');
    var preview       = document.getElementById('keywords-preview');

    var editingCard = null;
    var highlightClasses = ['ring-2', 'ring-amber-300', 'border-amber-300'];

    // Pratinjau chip kata kunci saat mengetik
    function renderChips() {
        preview.innerHTML = '';
        keywordsInput.value.split(',').forEach(function (raw) {
            var word = raw.trim();
            if (!word) return;
            var chip = document.createElement('span');
            chip.className = 'inline-flex items-center rounded border border-indigo-100 bg-indigo-50 px-2 py-0.5 text-[11px] font-medium text-indigo-700';
            chip.textContent = word;
            preview.appendChild(chip);
        });
    }
    keywordsInput.addEventListener('input', renderChips);
    renderChips();

    function clearHighlight() {
        if (editingCard) {
            highlightClasses.forEach(function (c) { editingCard.classList.remove(c); });
            editingCard = null;
        }
    }

    // Masuk mode edit
    function startEdit(id) {
        var t = templates[id];
        if (!t) return;

        labelInput.value    = t.label;
        keywordsInput.value = (t.keywords || []).join(', ');
        replyInput.value    = t.reply;
        renderChips();

        form.action = updateUrlTpl.replace('__ID__', encodeURIComponent(id));
        methodField.disabled = false;

        formTitle.textContent  = 'Edit Template';
        formBadge.classList.remove('hidden');
        btnSubmit.textContent  = 'Update Template';
        btnCancel.classList.remove('hidden');

        clearHighlight();
        editingCard = document.querySelector('[data-template-id="' + id + '"]');
        if (editingCard) {
            highlightClasses.forEach(function (c) { editingCard.classList.add(c); });
        }

        form.scrollIntoView({ behavior: 'smooth', block: 'center' });
        labelInput.focus({ preventScroll: true });
    }

    // Kembali ke mode tambah
    function resetForm() {
        form.reset();
        labelInput.value    = '';
        keywordsInput.value = '';
        replyInput.value    = '';
        renderChips();

        form.action = storeUrl;
        methodField.disabled = true;

        formTitle.textContent = 'Tambah Template Baru';
        formBadge.classList.add('hidden');
        btnSubmit.textContent = 'Simpan Template';
        btnCancel.classList.add('hidden');

        clearHighlight();
    }

    document.querySelectorAll('[data-edit]').forEach(function (btn) {
        btn.addEventListener('click', function () { startEdit(btn.dataset.edit); });
    });
    btnCancel.addEventListener('click', resetForm);

    // Filter daftar template
    var filterInput = document.getElementById('template-filter');
    var filterEmpty = document.getElementById('filter-empty');
    if (filterInput) {
        filterInput.addEventListener('input', function () {
            var q = filterInput.value.trim().toLowerCase();
            var visible = 0;
            document.querySelectorAll('.template-item').forEach(function (item) {
                var match = !q || item.dataset.search.indexOf(q) !== -1;
                item.classList.toggle('hidden', !match);
                if (match) visible++;
            });
            filterEmpty.classList.toggle('hidden', visible !== 0);
        });
    }
})();
</script>