const router = require("express").Router();
const axios = require("axios");

const API = "http://localhost:8000/api";

router.get("/categories", async (req, res) => {
  try {
    const { data: categories } = await axios.get(`${API}/categories`);
    res.render("categories.ejs", { categories });
  } catch (e) {
    res.status(500).send("Failed to load categories");
  }
});

router.get("/category/:slug", async (req, res) => {
  const { slug } = req.params;
  const { q, order, ...filters } = req.query;
  try {
    // Drop empty filter values so we don't send name="" filters
    const params = { q, order, ...filters };
    Object.keys(params).forEach((k) => {
      if (params[k] === "" || params[k] == null) delete params[k];
    });
    const [{ data: cat }, { data: filterMap }, { data: products }] = await Promise.all([
      axios.get(`${API}/categories/${slug}`),
      axios.get(`${API}/categories/${slug}/filters`),
      axios.get(`${API}/categories/${slug}/products`, { params })
    ]);
    res.render("category.ejs", { cat, filters: filterMap, products });
  } catch (e) {
    res.status(500).send("Failed to load category");
  }
});

router.get("/search", async (req, res) => {
  const { q, order, ...filters } = req.query;
  try {
    const params = { q, order, ...filters };
    Object.keys(params).forEach((k) => {
      if (params[k] === "" || params[k] == null) delete params[k];
    });
    const { data } = await axios.get(`${API}/products/search`, { params });
    res.render("search.ejs", { q, order, products: data });
  } catch (e) {
    res.status(500).send("Failed to search");
  }
});

module.exports = router;
