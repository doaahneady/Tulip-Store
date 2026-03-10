(function () {
    function escapeHtml(str) {
        return String(str ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function uid() {
        return 'a' + Math.random().toString(36).slice(2, 10) + Date.now().toString(36);
    }

    function parseOptions(raw) {
        const s = String(raw ?? '');
        return s
            .split(/\r?\n|,/g)
            .map((x) => x.trim())
            .filter(Boolean)
            .slice(0, 200);
    }

    function normalizeType(t) {
        const v = String(t ?? 'text').toLowerCase();
        if (v === 'dropdown') return 'select';
        if (v === 'single_line') return 'text';
        if (v === 'multi_line') return 'textarea';
        if (v === 'checkboxgroup') return 'checkbox_group';
        if (v === 'radiogroup') return 'radio_group';
        if (v === 'file_upload') return 'file';
        return v;
    }

    const TYPE_OPTIONS = [
        { value: 'select', label: 'Dropdown' },
        { value: 'text', label: 'Textbox' },
        { value: 'textarea', label: 'Multi-line' },
        { value: 'number', label: 'Number' },
        { value: 'date', label: 'Date' },
        { value: 'checkbox_group', label: 'Checkbox group' },
        { value: 'radio_group', label: 'Radio group' },
        { value: 'file', label: 'File upload' },
    ];

    function renderTypeSelect(selected) {
        const s = String(selected ?? 'text');
        return TYPE_OPTIONS.map((t) => `<option value="${t.value}" ${t.value === s ? 'selected' : ''}>${t.label}</option>`).join('');
    }

    function templateCard(index, data) {
        const type = normalizeType(data.type);
        const isReq = data.required ? 'checked' : '';
        const u = data.uid || uid();
        const name = escapeHtml(data.name || '');
        const key = escapeHtml(data.key || '');
        const options = escapeHtml(data.options || '');
        const minLen = data.min_length ?? '';
        const maxLen = data.max_length ?? '';
        const minVal = data.min ?? '';
        const maxVal = data.max ?? '';
        const allowedTypes = escapeHtml(data.allowed_file_types || '');
        const maxFile = data.max_file_size_kb ?? '';
        const existingId = escapeHtml(data.id || '');
        const value = data.value ?? '';
        const valueJson = Array.isArray(value) ? value : [];
        const valueText = Array.isArray(value) ? '' : escapeHtml(value);

        const valueField = (() => {
            if (type === 'textarea') {
                return `<textarea class="textarea" rows="2" data-role="value" name="custom_attributes[${index}][value]">${valueText}</textarea>`;
            }
            if (type === 'date') {
                return `<input class="input" type="date" data-role="value" name="custom_attributes[${index}][value]" value="${valueText}">`;
            }
            if (type === 'number') {
                return `<input class="input" type="number" step="0.01" data-role="value" name="custom_attributes[${index}][value]" value="${valueText}">`;
            }
            if (type === 'checkbox_group') {
                const opts = parseOptions(data.options);
                const optionsHtml = opts.map((o) => `<option value="${escapeHtml(o)}" ${valueJson.includes(o) ? 'selected' : ''}>${escapeHtml(o)}</option>`).join('');
                return `<select class="select" multiple data-role="value-multi" name="custom_attributes[${index}][value][]">${optionsHtml}</select>`;
            }
            if (type === 'radio_group' || type === 'select') {
                const opts = parseOptions(data.options);
                const optionsHtml = ['<option value="">—</option>']
                    .concat(opts.map((o) => `<option value="${escapeHtml(o)}" ${String(value) === String(o) ? 'selected' : ''}>${escapeHtml(o)}</option>`))
                    .join('');
                return `<select class="select" data-role="value" name="custom_attributes[${index}][value]">${optionsHtml}</select>`;
            }
            if (type === 'file') {
                const accept = allowedTypes
                    ? allowedTypes
                        .split(',')
                        .map((x) => x.trim())
                        .filter(Boolean)
                        .map((x) => (x.startsWith('.') ? x : '.' + x))
                        .join(',')
                    : '';
                return `<div style="display:flex; flex-direction:column; gap:.35rem;">
                    <input class="input" type="file" data-role="file" name="custom_attribute_files[${escapeHtml(u)}]" ${accept ? `accept="${escapeHtml(accept)}"` : ''}>
                    <input type="hidden" data-role="file-existing" name="custom_attributes[${index}][value_existing]" value="${valueText}">
                </div>`;
            }
            return `<input class="input" type="text" data-role="value" name="custom_attributes[${index}][value]" value="${valueText}">`;
        })();

        return `<div class="card attr-card" draggable="true" data-uid="${escapeHtml(u)}" style="margin-bottom:.75rem; padding:1rem;">
            <div style="display:flex; align-items:center; justify-content:space-between; gap:.75rem; margin-bottom:.75rem;">
                <div style="display:flex; align-items:center; gap:.5rem;">
                    <span class="attr-drag" style="cursor:grab; user-select:none; font-weight:900;">⋮⋮</span>
                    <div style="font-weight:900;">Field</div>
                </div>
                <button type="button" class="btn gray" data-action="remove"><i class="fas fa-trash"></i></button>
            </div>

            <input type="hidden" data-role="id" name="custom_attributes[${index}][id]" value="${existingId}">
            <input type="hidden" data-role="uid" name="custom_attributes[${index}][uid]" value="${escapeHtml(u)}">
            <div class="grid grid-2">
                <div>
                    <label style="font-weight:800;display:block;margin-bottom:.35rem">Label</label>
                    <input class="input" data-role="name" name="custom_attributes[${index}][name]" value="${name}" maxlength="80" required>
                </div>
                <div>
                    <label style="font-weight:800;display:block;margin-bottom:.35rem">Type</label>
                    <select class="select" data-role="type" name="custom_attributes[${index}][type]">${renderTypeSelect(type)}</select>
                </div>
                <div>
                    <label style="font-weight:800;display:block;margin-bottom:.35rem">Filter key (optional)</label>
                    <input class="input" data-role="key" name="custom_attributes[${index}][key]" value="${key}" maxlength="80" placeholder="e.g. material">
                </div>
                <div style="display:flex; align-items:flex-end; gap:.75rem;">
                    <label style="display:flex; align-items:center; gap:.5rem; font-weight:800; margin-bottom:.35rem;">
                        <input type="checkbox" data-role="required" ${isReq}>
                        Required
                    </label>
                    <input type="hidden" data-role="required-hidden" name="custom_attributes[${index}][required]" value="${data.required ? '1' : '0'}">
                </div>
                <div style="grid-column:1 / -1;">
                    <label style="font-weight:800;display:block;margin-bottom:.35rem">Options (newline or comma separated)</label>
                    <textarea class="textarea" rows="2" data-role="options" name="custom_attributes[${index}][options]" maxlength="2000">${options}</textarea>
                </div>
                <div>
                    <label style="font-weight:800;display:block;margin-bottom:.35rem">Min length</label>
                    <input class="input" type="number" data-role="min_length" name="custom_attributes[${index}][rules][min_length]" value="${escapeHtml(minLen)}" min="0" max="10000">
                </div>
                <div>
                    <label style="font-weight:800;display:block;margin-bottom:.35rem">Max length</label>
                    <input class="input" type="number" data-role="max_length" name="custom_attributes[${index}][rules][max_length]" value="${escapeHtml(maxLen)}" min="0" max="10000">
                </div>
                <div>
                    <label style="font-weight:800;display:block;margin-bottom:.35rem">Min value</label>
                    <input class="input" type="number" step="0.01" data-role="min" name="custom_attributes[${index}][rules][min]" value="${escapeHtml(minVal)}">
                </div>
                <div>
                    <label style="font-weight:800;display:block;margin-bottom:.35rem">Max value</label>
                    <input class="input" type="number" step="0.01" data-role="max" name="custom_attributes[${index}][rules][max]" value="${escapeHtml(maxVal)}">
                </div>
                <div>
                    <label style="font-weight:800;display:block;margin-bottom:.35rem">Allowed file types</label>
                    <input class="input" data-role="allowed_file_types" name="custom_attributes[${index}][rules][allowed_file_types]" value="${allowedTypes}" maxlength="200" placeholder="jpg,png,pdf">
                </div>
                <div>
                    <label style="font-weight:800;display:block;margin-bottom:.35rem">Max file size (KB)</label>
                    <input class="input" type="number" data-role="max_file_size_kb" name="custom_attributes[${index}][rules][max_file_size_kb]" value="${escapeHtml(maxFile)}" min="1" max="51200">
                </div>
                <div style="grid-column:1 / -1;">
                    <label style="font-weight:800;display:block;margin-bottom:.35rem">Value</label>
                    ${valueField}
                </div>
            </div>
        </div>`;
    }

    function updateIndexes(container) {
        const cards = Array.from(container.querySelectorAll('.attr-card'));
        cards.forEach((card, idx) => {
            const setName = (el, newName) => {
                if (!el) return;
                el.setAttribute('name', newName);
            };

            setName(card.querySelector('[data-role="id"]'), `custom_attributes[${idx}][id]`);
            setName(card.querySelector('[data-role="uid"]'), `custom_attributes[${idx}][uid]`);
            setName(card.querySelector('[data-role="name"]'), `custom_attributes[${idx}][name]`);
            setName(card.querySelector('[data-role="type"]'), `custom_attributes[${idx}][type]`);
            setName(card.querySelector('[data-role="key"]'), `custom_attributes[${idx}][key]`);
            setName(card.querySelector('[data-role="required-hidden"]'), `custom_attributes[${idx}][required]`);
            setName(card.querySelector('[data-role="options"]'), `custom_attributes[${idx}][options]`);
            setName(card.querySelector('[data-role="min_length"]'), `custom_attributes[${idx}][rules][min_length]`);
            setName(card.querySelector('[data-role="max_length"]'), `custom_attributes[${idx}][rules][max_length]`);
            setName(card.querySelector('[data-role="min"]'), `custom_attributes[${idx}][rules][min]`);
            setName(card.querySelector('[data-role="max"]'), `custom_attributes[${idx}][rules][max]`);
            setName(card.querySelector('[data-role="allowed_file_types"]'), `custom_attributes[${idx}][rules][allowed_file_types]`);
            setName(card.querySelector('[data-role="max_file_size_kb"]'), `custom_attributes[${idx}][rules][max_file_size_kb]`);

            const type = normalizeType(card.querySelector('[data-role="type"]')?.value);
            const valueEl = card.querySelector('[data-role="value"]');
            const valueMulti = card.querySelector('[data-role="value-multi"]');
            const existing = card.querySelector('[data-role="file-existing"]');
            if (type === 'checkbox_group') {
                if (valueMulti) setName(valueMulti, `custom_attributes[${idx}][value][]`);
                if (valueEl) valueEl.removeAttribute('name');
            } else if (type === 'file') {
                if (existing) setName(existing, `custom_attributes[${idx}][value_existing]`);
                if (valueEl) valueEl.removeAttribute('name');
                if (valueMulti) valueMulti.removeAttribute('name');
            } else {
                if (valueEl) setName(valueEl, `custom_attributes[${idx}][value]`);
                if (valueMulti) valueMulti.removeAttribute('name');
            }
        });
    }

    function buildPreview(container, previewEl) {
        const cards = Array.from(container.querySelectorAll('.attr-card'));
        const items = cards.map((card) => {
            const type = normalizeType(card.querySelector('[data-role="type"]')?.value);
            const name = String(card.querySelector('[data-role="name"]')?.value ?? '').trim();
            const required = !!card.querySelector('[data-role="required"]')?.checked;
            const options = parseOptions(card.querySelector('[data-role="options"]')?.value);
            let value = '';
            if (type === 'checkbox_group') {
                const el = card.querySelector('[data-role="value-multi"]');
                value = Array.from(el?.selectedOptions ?? []).map((o) => o.value);
            } else if (type === 'file') {
                const ex = String(card.querySelector('[data-role="file-existing"]')?.value ?? '').trim();
                const file = card.querySelector('[data-role="file"]')?.files?.[0];
                value = file ? file.name : (ex ? ex.split('/').pop() : '');
            } else {
                value = String(card.querySelector('[data-role="value"]')?.value ?? '').trim();
            }
            return { type, name, required, options, value };
        }).filter((x) => x.name);

        const html = items.map((a) => {
            const req = a.required ? '<span style="color:#dc2626; font-weight:900;">*</span>' : '';
            if (a.type === 'textarea') {
                return `<div style="display:flex; flex-direction:column; gap:.35rem;">
                    <div style="font-weight:800;">${escapeHtml(a.name)} ${req}</div>
                    <textarea class="textarea" rows="2" disabled>${escapeHtml(a.value)}</textarea>
                </div>`;
            }
            if (a.type === 'date') {
                return `<div style="display:flex; flex-direction:column; gap:.35rem;">
                    <div style="font-weight:800;">${escapeHtml(a.name)} ${req}</div>
                    <input class="input" type="date" value="${escapeHtml(a.value)}" disabled>
                </div>`;
            }
            if (a.type === 'number') {
                return `<div style="display:flex; flex-direction:column; gap:.35rem;">
                    <div style="font-weight:800;">${escapeHtml(a.name)} ${req}</div>
                    <input class="input" type="number" value="${escapeHtml(a.value)}" disabled>
                </div>`;
            }
            if (a.type === 'checkbox_group') {
                const opts = a.options;
                const selected = Array.isArray(a.value) ? a.value : [];
                const boxes = opts.length ? opts : selected;
                return `<div style="display:flex; flex-direction:column; gap:.35rem;">
                    <div style="font-weight:800;">${escapeHtml(a.name)} ${req}</div>
                    <div style="display:flex; flex-wrap:wrap; gap:.5rem;">
                        ${boxes.map((o) => `<label style="display:flex; align-items:center; gap:.35rem; background:#f3f4f6; padding:.35rem .6rem; border-radius:999px;">
                            <input type="checkbox" disabled ${selected.includes(o) ? 'checked' : ''}>
                            <span>${escapeHtml(o)}</span>
                        </label>`).join('')}
                    </div>
                </div>`;
            }
            if (a.type === 'radio_group') {
                const opts = a.options;
                const selected = String(a.value || '');
                const radios = opts.length ? opts : (selected ? [selected] : []);
                return `<div style="display:flex; flex-direction:column; gap:.35rem;">
                    <div style="font-weight:800;">${escapeHtml(a.name)} ${req}</div>
                    <div style="display:flex; flex-wrap:wrap; gap:.5rem;">
                        ${radios.map((o) => `<label style="display:flex; align-items:center; gap:.35rem; background:#f3f4f6; padding:.35rem .6rem; border-radius:999px;">
                            <input type="radio" disabled ${selected === String(o) ? 'checked' : ''}>
                            <span>${escapeHtml(o)}</span>
                        </label>`).join('')}
                    </div>
                </div>`;
            }
            if (a.type === 'select') {
                const opts = a.options;
                const optionsHtml = ['<option value="">—</option>'].concat(opts.map((o) => `<option ${String(a.value) === String(o) ? 'selected' : ''}>${escapeHtml(o)}</option>`)).join('');
                return `<div style="display:flex; flex-direction:column; gap:.35rem;">
                    <div style="font-weight:800;">${escapeHtml(a.name)} ${req}</div>
                    <select class="select" disabled>${optionsHtml}</select>
                </div>`;
            }
            if (a.type === 'file') {
                return `<div style="display:flex; flex-direction:column; gap:.35rem;">
                    <div style="font-weight:800;">${escapeHtml(a.name)} ${req}</div>
                    <div style="background:#f3f4f6; padding:.75rem; border-radius:12px; font-weight:700; color:#374151;">
                        ${a.value ? escapeHtml(a.value) : 'No file selected'}
                    </div>
                </div>`;
            }
            return `<div style="display:flex; flex-direction:column; gap:.35rem;">
                <div style="font-weight:800;">${escapeHtml(a.name)} ${req}</div>
                <input class="input" type="text" value="${escapeHtml(a.value)}" disabled>
            </div>`;
        }).join('');

        previewEl.innerHTML = html || '<div style="color:#6b7280; font-weight:700;">Add fields to see a preview.</div>';
    }

    function validateBeforeSubmit(form, container) {
        const cards = Array.from(container.querySelectorAll('.attr-card'));
        for (const card of cards) {
            const type = normalizeType(card.querySelector('[data-role="type"]')?.value);
            const label = String(card.querySelector('[data-role="name"]')?.value ?? '').trim();
            if (!label) continue;
            const required = !!card.querySelector('[data-role="required"]')?.checked;
            const minLen = parseInt(card.querySelector('[data-role="min_length"]')?.value || '', 10);
            const maxLen = parseInt(card.querySelector('[data-role="max_length"]')?.value || '', 10);
            const min = card.querySelector('[data-role="min"]')?.value;
            const max = card.querySelector('[data-role="max"]')?.value;
            const maxFileKb = parseInt(card.querySelector('[data-role="max_file_size_kb"]')?.value || '', 10);
            const allowed = String(card.querySelector('[data-role="allowed_file_types"]')?.value || '')
                .split(',')
                .map((x) => x.trim().toLowerCase())
                .filter(Boolean);

            const fail = (msg, el) => {
                alert(msg);
                el?.focus?.();
                return false;
            };

            if (type === 'checkbox_group') {
                const el = card.querySelector('[data-role="value-multi"]');
                const selected = Array.from(el?.selectedOptions ?? []).map((o) => o.value).filter(Boolean);
                if (required && selected.length === 0) {
                    return fail(`"${label}" is required`, el);
                }
                continue;
            }

            if (type === 'file') {
                const fileEl = card.querySelector('[data-role="file"]');
                const file = fileEl?.files?.[0];
                const existing = String(card.querySelector('[data-role="file-existing"]')?.value || '').trim();
                if (required && !file && !existing) {
                    return fail(`"${label}" is required`, fileEl);
                }
                if (file) {
                    const sizeKb = Math.ceil(file.size / 1024);
                    if (maxFileKb && sizeKb > maxFileKb) {
                        return fail(`"${label}" exceeds max file size`, fileEl);
                    }
                    if (allowed.length) {
                        const ext = (file.name.split('.').pop() || '').toLowerCase();
                        if (ext && !allowed.includes(ext) && !allowed.includes('.' + ext)) {
                            return fail(`"${label}" has invalid file type`, fileEl);
                        }
                    }
                }
                continue;
            }

            const valueEl = card.querySelector('[data-role="value"]');
            const v = String(valueEl?.value ?? '').trim();
            if (required && !v) {
                return fail(`"${label}" is required`, valueEl);
            }
            if (!v) continue;
            if ((type === 'text' || type === 'textarea') && !Number.isNaN(minLen) && minLen > 0 && v.length < minLen) {
                return fail(`"${label}" is too short`, valueEl);
            }
            if ((type === 'text' || type === 'textarea') && !Number.isNaN(maxLen) && maxLen > 0 && v.length > maxLen) {
                return fail(`"${label}" is too long`, valueEl);
            }
            if (type === 'number') {
                const n = Number(v);
                if (Number.isNaN(n)) return fail(`"${label}" must be a number`, valueEl);
                if (min !== '' && min != null && n < Number(min)) return fail(`"${label}" is below minimum`, valueEl);
                if (max !== '' && max != null && n > Number(max)) return fail(`"${label}" exceeds maximum`, valueEl);
            }
        }
        return true;
    }

    function init(containerEl, initial) {
        if (!containerEl) return;
        const listEl = containerEl.querySelector('[data-role="attr-list"]');
        const previewEl = containerEl.querySelector('[data-role="attr-preview"]');
        const addBtn = containerEl.querySelector('[data-action="add"]');
        const form = containerEl.closest('form');
        if (!listEl || !previewEl) return;

        const start = Array.isArray(initial) ? initial : [];
        if (start.length) {
            start.forEach((a, idx) => {
                listEl.insertAdjacentHTML('beforeend', templateCard(idx, {
                    id: a.id || '',
                    uid: a.uid || '',
                    name: a.name || a.label || '',
                    key: a.key || a.attribute_key || '',
                    type: a.type || 'text',
                    required: !!(a.required || a.is_required),
                    options: Array.isArray(a.options) ? a.options.join('\n') : (a.options || ''),
                    value: a.value ?? '',
                    min_length: a.rules?.min_length ?? a.min_length,
                    max_length: a.rules?.max_length ?? a.max_length,
                    min: a.rules?.min ?? a.min,
                    max: a.rules?.max ?? a.max,
                    allowed_file_types: Array.isArray(a.rules?.allowed_file_types) ? a.rules.allowed_file_types.join(',') : (a.rules?.allowed_file_types ?? a.allowed_file_types),
                    max_file_size_kb: a.rules?.max_file_size_kb ?? a.max_file_size_kb,
                }));
            });
        } else {
            listEl.insertAdjacentHTML('beforeend', templateCard(0, { type: 'text' }));
        }

        updateIndexes(listEl);
        buildPreview(listEl, previewEl);

        addBtn?.addEventListener('click', function () {
            const count = listEl.querySelectorAll('.attr-card').length;
            listEl.insertAdjacentHTML('beforeend', templateCard(count, { type: 'text' }));
            updateIndexes(listEl);
            buildPreview(listEl, previewEl);
        });

        listEl.addEventListener('click', function (e) {
            const btn = e.target?.closest?.('[data-action="remove"]');
            if (!btn) return;
            const card = btn.closest('.attr-card');
            if (!card) return;
            card.remove();
            updateIndexes(listEl);
            buildPreview(listEl, previewEl);
        });

        listEl.addEventListener('change', function (e) {
            const card = e.target?.closest?.('.attr-card');
            if (!card) return;
            const req = card.querySelector('[data-role="required"]');
            const hidden = card.querySelector('[data-role="required-hidden"]');
            if (req && hidden) hidden.value = req.checked ? '1' : '0';

            const role = e.target?.getAttribute?.('data-role') || '';
            if (role === 'type' || role === 'options') {
                const idx = Array.from(listEl.querySelectorAll('.attr-card')).indexOf(card);
                const snapshot = {
                    id: card.querySelector('[data-role="id"]')?.value || '',
                    uid: card.querySelector('[data-role="uid"]')?.value || card.getAttribute('data-uid') || '',
                    name: card.querySelector('[data-role="name"]')?.value || '',
                    key: card.querySelector('[data-role="key"]')?.value || '',
                    type: card.querySelector('[data-role="type"]')?.value || 'text',
                    required: !!card.querySelector('[data-role="required"]')?.checked,
                    options: card.querySelector('[data-role="options"]')?.value || '',
                    value: (() => {
                        const t = normalizeType(snapshot.type);
                        if (t === 'checkbox_group') {
                            const el = card.querySelector('[data-role="value-multi"]');
                            return Array.from(el?.selectedOptions ?? []).map((o) => o.value);
                        }
                        if (t === 'file') {
                            return card.querySelector('[data-role="file-existing"]')?.value || '';
                        }
                        return card.querySelector('[data-role="value"]')?.value || '';
                    })(),
                    min_length: card.querySelector('[data-role="min_length"]')?.value || '',
                    max_length: card.querySelector('[data-role="max_length"]')?.value || '',
                    min: card.querySelector('[data-role="min"]')?.value || '',
                    max: card.querySelector('[data-role="max"]')?.value || '',
                    allowed_file_types: card.querySelector('[data-role="allowed_file_types"]')?.value || '',
                    max_file_size_kb: card.querySelector('[data-role="max_file_size_kb"]')?.value || '',
                };
                const html = templateCard(idx, snapshot);
                card.insertAdjacentHTML('afterend', html);
                card.remove();
                updateIndexes(listEl);
            }

            buildPreview(listEl, previewEl);
        });

        listEl.addEventListener('input', function () {
            buildPreview(listEl, previewEl);
        });

        let dragged = null;
        listEl.addEventListener('dragstart', function (e) {
            const card = e.target?.closest?.('.attr-card');
            if (!card) return;
            dragged = card;
            e.dataTransfer?.setData?.('text/plain', card.getAttribute('data-uid') || '');
            card.style.opacity = '0.5';
        });
        listEl.addEventListener('dragend', function () {
            if (dragged) dragged.style.opacity = '';
            dragged = null;
            updateIndexes(listEl);
            buildPreview(listEl, previewEl);
        });
        listEl.addEventListener('dragover', function (e) {
            e.preventDefault();
            const over = e.target?.closest?.('.attr-card');
            if (!over || !dragged || over === dragged) return;
            const rect = over.getBoundingClientRect();
            const before = e.clientY < rect.top + rect.height / 2;
            listEl.insertBefore(dragged, before ? over : over.nextSibling);
        });

        form?.addEventListener('submit', function (e) {
            updateIndexes(listEl);
            buildPreview(listEl, previewEl);
            if (!validateBeforeSubmit(form, listEl)) {
                e.preventDefault();
                return;
            }
        });
    }

    window.initTraderAttributeBuilder = init;
})();

