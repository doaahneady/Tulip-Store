const router = require("express").Router();

router.get("/", async (req, res) => {
  const language = new (require(`../../languages/english`))();
  res.render("contactUs.ejs", {
    app: req.app,
    language: language,
  });
});

module.exports = router;
