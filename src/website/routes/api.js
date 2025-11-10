const router = require("express").Router();
const axios = require("axios");

const API_BASE = "http://localhost:8000"; // Laravel base (no trailing slash)

// Generic proxy: forwards all mounted /api/* to Laravel, preserving method, query, headers, and body
// Express 5: use a catch-all middleware (no path pattern) to avoid path-to-regexp issues
router.use(async (req, res) => {
  try {
    // Build target as API_BASE + baseUrl + url (keeps query string)
    const targetPath = (req.baseUrl || "") + (req.url || "");
    const url = API_BASE + targetPath;
    const method = req.method.toLowerCase();
    if (process.env.NODE_ENV !== 'production') {
      console.info(`[proxy] ${req.method} ${targetPath}`);
    }

    const headers = { ...req.headers };
    delete headers["host"]; delete headers["content-length"]; delete headers["connection"]; delete headers["accept-encoding"]; 

    const ax = await axios({ url, method, headers, validateStatus: () => true, data: req.body });

    if (process.env.NODE_ENV !== 'production' && ax.status >= 400) {
      console.warn(`[proxy] ${req.method} ${targetPath} -> ${ax.status}`);
    }

    res.status(ax.status);
    const ct = ax.headers && ax.headers["content-type"];
    if (ct) res.setHeader("content-type", ct);
    res.send(ax.data);
  } catch (err) {
    const status = err.response?.status || 500;
    res.status(status).send({ success: false, message: "Proxy error", detail: err.message });
  }
});

module.exports = router;
