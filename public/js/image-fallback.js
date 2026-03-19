(function () {
    function nowIso() {
        try {
            return new Date().toISOString();
        } catch (e) {
            return '';
        }
    }

    function normalizeHttps(url) {
        const u = String(url || '').trim();
        if (!u) return '';
        if (u.startsWith('http://')) return 'https://' + u.slice('http://'.length);
        return u;
    }

    function toRelativeStorage(url) {
        const u = String(url || '').trim();
        if (!u) return '';
        if (u.startsWith('/storage/')) return u;
        const i = u.indexOf('/storage/');
        if (i >= 0) return u.slice(i);
        return u;
    }

    function uniqueList(arr) {
        const out = [];
        const seen = new Set();
        for (const x of arr) {
            const s = String(x || '').trim();
            if (!s) continue;
            if (seen.has(s)) continue;
            seen.add(s);
            out.push(s);
        }
        return out;
    }

    async function tryFetch(url, timeoutMs) {
        const controller = new AbortController();
        const t = setTimeout(() => controller.abort(), timeoutMs);
        try {
            const res = await fetch(url, {
                method: 'GET',
                signal: controller.signal,
                mode: 'cors',
                cache: 'no-store',
                credentials: 'omit',
            });
            return { ok: res.ok, status: res.status };
        } catch (e) {
            return { ok: false, status: 0, error: String(e && e.name ? e.name : e) };
        } finally {
            clearTimeout(t);
        }
    }

    async function logFailure(payload) {
        try {
            const url = String(window.IMAGE_FALLBACK_LOG_URL || '').trim();
            if (!url) return;
            await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                },
                body: JSON.stringify(payload),
                keepalive: true,
            });
        } catch (e) {
        }
    }

    async function setImageWithFallback(img, primary, variants, placeholder, timeoutMs) {
        if (!img) return;
        const base = String(primary || '').trim();
        const placeholderUrl = String(placeholder || '').trim() || '/images/tulip_store.jpg';
        const list = uniqueList([
            base,
            normalizeHttps(base),
            toRelativeStorage(base),
            ...(Array.isArray(variants) ? variants : []),
            placeholderUrl,
        ]);

        for (const u of list) {
            if (!u) continue;
            if (u === placeholderUrl) {
                img.src = placeholderUrl;
                return;
            }

            const res = await tryFetch(u, timeoutMs);
            if (res.ok) {
                img.src = u;
                return;
            }

            await logFailure({
                url: u,
                status: res.status,
                error: res.error || null,
                at: nowIso(),
                context: img.getAttribute('data-image-context') || null,
            });
        }

        img.src = placeholderUrl;
    }

    window.setImageWithFallback = setImageWithFallback;
})();

