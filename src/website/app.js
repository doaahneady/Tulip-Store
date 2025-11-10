const express = require("express");
const config = require("../config");
const { join } = require("path");
const axios = require("axios");

module.exports.run = async () => {
  const app = express();
  // Disabled MongoDB connection: backend uses MySQL (Laravel APIs)
  // require("../functions/database").connectData();

  /* Routers */
  const mainRouter = require("./routes/index");
  const signInRouter = require("./routes/signIn");
  const contactUsRouter = require("./routes/contactUs");
  const businessRouter = require("./routes/business");
  const ReturnPolicy = require("./routes/ReturnPolicy");
  const catalogRouter = require("./routes/catalog");
  const apiProxyRouter = require("./routes/api");

  const API = "/api";
  const DIRECT_API = "http://localhost:8000/api";

  app.config = config;
  // Remove MongoDB-based models from the app context
  // app.usersData = require("../base/User");
  // app.Info = require("../base/Info");
  // app.systemData = require("../base/System");
  // app.currencies = require("../base/Currencies");

  app.use(express.json());
  app.use(express.urlencoded({ extended: true }));
  // Configure EJS correctly for both .ejs and .html templates
  const ejs = require("ejs");
  app.engine("ejs", ejs.renderFile);
  app.engine("html", ejs.renderFile);
  app.set("view engine", "ejs");

  app.use((req, res, next) => {
    req.app = app;
    next();
  });

  // Regex fallback route for product pages to avoid any path-to-regexp mismatches
  app.get(/^\/product\/(.+)$/, async (req, res) => {
    const idOrSlug = req.params[0];
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
          product = list.find(p => String(p.slug||'').toLowerCase() === lower) ||
                    list.find(p => String(p.id) === idOrSlug) ||
                    list[0];
        }
      }
      // Fallback: if path is numeric id, scan categories to locate the product by id (robust for older links)
      if (!product && /^\d+$/.test(String(idOrSlug))) {
        try {
          const catsRes = await axios.get(`${DIRECT_API}/categories`);
          const cats = Array.isArray(catsRes.data) ? catsRes.data : [];
          const targetId = String(idOrSlug);
          for (const c of cats) {
            let page = 1, last = 1, found = null;
            do {
              const pr = await axios.get(`${DIRECT_API}/categories/${encodeURIComponent(c.slug)}/products`, { params: { page } });
              const data = pr.data || {};
              const arr = Array.isArray(data.data) ? data.data : [];
              found = arr.find(p => String(p.id) === targetId) || null;
              last = data.last_page || 1;
              page += 1;
            } while (!found && page <= last);
            if (found) { product = found; break; }
          }
        } catch (_) {}
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

  app.use(express.static(join(__dirname, "/public")));
  app.set("views", join(__dirname, "/views"));
  app.set("port", config.port);
  app.use("/", mainRouter);
  app.use("/signin", signInRouter);
  app.use("/contact", contactUsRouter);
  app.use("/business", businessRouter);
  app.use("/ReturnPolicy", ReturnPolicy);
  // API proxy under /api to avoid route conflicts and ensure matching
  app.use("/api", apiProxyRouter);
  app.use("/", catalogRouter);

  // Ensure product details route is available regardless of router load order
  app.get("/product/:idOrSlug", async (req, res) => {
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
          product = list.find(p => String(p.id) === idOrSlug) ||
                    list.find(p => String(p.slug||'').toLowerCase() === lower) ||
                    list[0];
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

  app.use("/", catalogRouter);

  app.listen(app.get("port"), () => {
    console.log(
      `${config.defaults.name}'s Website is running on port: ${app.get(
        "port"
      )} !`
    );
  });
};
