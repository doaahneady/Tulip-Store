const router = require("express").Router();
const axios = require("axios");

const API = "/api";
const DIRECT_API = "http://localhost:8000/api";

router.get("/", async (req, res) => {
  const language = new (require(`../../languages/english`))();
  res.render("index.ejs", {
    app: req.app,
    language: language,
  });
});

// Fallback product details route (in case other routers fail to register)
router.get("/product/:idOrSlug", async (req, res) => {
  const { idOrSlug } = req.params;
  const tryGetDirect = async () => {
    try { return (await axios.get(`${API}/products/${encodeURIComponent(idOrSlug)}`)).data; } catch (e1) {
      try { return (await axios.get(`${DIRECT_API}/products/${encodeURIComponent(idOrSlug)}`)).data; } catch (e2) { return null; }
    }
  };
  const trySearch = async () => {
    const params = { q: idOrSlug };
    try { return (await axios.get(`${API}/products/search`, { params })).data; } catch (e1) {
      try { return (await axios.get(`${DIRECT_API}/products/search`, { params })).data; } catch (e2) { return null; }
    }
  };
  try {
    let payload = await tryGetDirect();
    let product = null;
    if (payload) {
      if (Array.isArray(payload?.data)) product = payload.data[0] || null; else if (payload?.data) product = payload.data; else product = payload;
    }
    if (!product) {
      const search = await trySearch();
      const list = Array.isArray(search?.data) ? search.data : [];
      if (list.length) {
        const lower = String(idOrSlug).toLowerCase();
        product = list.find(p => String(p.id) === idOrSlug) || list.find(p => String(p.slug||'').toLowerCase() === lower) || list[0];
      }
    }
    if (!product) return res.status(404).send("Product not found");

    const images = Array.isArray(product.images) ? product.images : (product.gallery || product.photos || []);
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
    return res.status(500).send("Failed to load product");
  }
});

module.exports = router;
