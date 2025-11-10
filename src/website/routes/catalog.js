const router = require("express").Router();
const axios = require("axios");

const API = "/api";
const DIRECT_API = "http://localhost:8000/api"; // fallback if proxy has issues

router.get("/categories", async (req, res) => {
  try {
    const { data: categories } = await axios.get(`${API}/categories`);
    res.render("categories.ejs", { categories });
  } catch (e) {
    res.status(500).send("Failed to load categories");
  }
});

router.get("/category/:slug", async (req, res) => {
  let { slug } = req.params;
  const { q, order, ...filters } = req.query;
  try {
    slug = String(slug || '').trim().toLowerCase();
    // Drop empty filter values so we don't send name="" filters
    const params = { q, order, ...filters };
    Object.keys(params).forEach((k) => { if (params[k] === "" || params[k] == null) delete params[k]; });

    async function getCat(base, s){
      try { return (await axios.get(`${base}/categories/${encodeURIComponent(s)}`)).data; } catch(_) { return null; }
    }
    async function getFilters(base, s){
      try { return (await axios.get(`${base}/categories/${encodeURIComponent(s)}/filters`)).data; } catch(_) { return {}; }
    }
    async function getProducts(base, s){
      try { return (await axios.get(`${base}/categories/${encodeURIComponent(s)}/products`, { params })).data; } catch(_) { return { data: [] }; }
    }

    // Try proxy first, then direct, and normalize slug from backend if available
    let cat = await getCat(API, slug);
    if (!cat) cat = await getCat(DIRECT_API, slug);
    if (!cat) return res.status(404).send("Failed to load category");
    const realSlug = String(cat.slug || slug);

    const [filtersMapProxy, productsProxy] = await Promise.all([
      getFilters(API, realSlug),
      getProducts(API, realSlug)
    ]);
    const needDirect = (!productsProxy || typeof productsProxy !== 'object' || productsProxy.data == null);
    const filtersMap = filtersMapProxy || {};
    let products = productsProxy;
    if (needDirect) {
      products = await getProducts(DIRECT_API, realSlug);
    }

    // expose params and app for template (selected states + shared head)
    return res.render("category.ejs", { cat, filters: filtersMap, products, query: params, slug: realSlug, app: req.app });
  } catch (e) {
    return res.status(500).send("Failed to load category");
  }
});

router.get("/search", async (req, res) => {
  const { q, order, ...filters } = req.query;
  const params = { q, order, ...filters };
  Object.keys(params).forEach((k) => {
    if (params[k] === "" || params[k] == null) delete params[k];
  });
  try {
    // Try via proxy first
    const { data } = await axios.get(`${API}/products/search`, { params });
    return res.render("search.ejs", { q, order, products: data });
  } catch (err1) {
    try {
      // Fallback: call Laravel directly
      const { data } = await axios.get(`${DIRECT_API}/products/search`, { params });
      return res.render("search.ejs", { q, order, products: data });
    } catch (err2) {
      console.error("Search failed", {
        proxyStatus: err1?.response?.status,
        proxyData: err1?.response?.data,
        directStatus: err2?.response?.status,
        directData: err2?.response?.data,
      });
      return res.status(500).send("Failed to search");
    }
  }
});

// Product details page
router.get("/product/:idOrSlug", async (req, res) => {
  const { idOrSlug } = req.params;
  const tryGetDirect = async () => {
    try {
      const r = await axios.get(`${API}/products/${encodeURIComponent(idOrSlug)}`);
      return r.data;
    } catch (e) {
      try {
        const r2 = await axios.get(`${DIRECT_API}/products/${encodeURIComponent(idOrSlug)}`);
        return r2.data;
      } catch (_) {
        return null;
      }
    }
  };
  const trySearch = async () => {
    const params = { q: idOrSlug };
    try {
      const r = await axios.get(`${API}/products/search`, { params });
      return r.data;
    } catch (e1) {
      try {
        const r2 = await axios.get(`${DIRECT_API}/products/search`, { params });
        return r2.data;
      } catch (e2) {
        return null;
      }
    }
  };

  try {
    let payload = await tryGetDirect();
    let product = null;
    // Normalize possible shapes
    if (payload) {
      if (Array.isArray(payload?.data)) {
        product = payload.data[0] || null;
      } else if (payload?.data) {
        product = payload.data;
      } else if (payload && !payload.data) {
        product = payload; // already a product
      }
    }
    if (!product) {
      const search = await trySearch();
      const list = Array.isArray(search?.data) ? search.data : [];
      if (list.length) {
        const lower = String(idOrSlug).toLowerCase();
        product = list.find(p => String(p.id) === idOrSlug) ||
                  list.find(p => String(p.slug||'').toLowerCase() === lower) ||
                  list[0];
      }
    }
    if (!product) return res.status(404).send("Product not found");

    // Basic normalization for template
    const images = Array.isArray(product.images) ? product.images :
                   (product.gallery || product.photos || []);
    const mainImage = product.image || images?.[0] || '/images/cover_phone.jpg';
    const normalized = {
      id: product.id || product.slug || product.code || product.sku || idOrSlug,
      name: product.name || product.title || 'Product',
      price: Number(product.price || product.sale_price || product.amount || 0),
      description: product.description || product.details || '',
      image: mainImage,
      images: images && images.length ? images : [mainImage],
      attributes: product.attributes || product.attrs || {},
      category: product.category || null,
    };

    return res.render("product.ejs", { product: normalized, app: req.app });
  } catch (e) {
    console.error('Product page error', e?.response?.status, e?.message);
    return res.status(500).send("Failed to load product");
  }
});

module.exports = router;
