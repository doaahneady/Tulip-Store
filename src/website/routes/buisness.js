const router = require("express").Router();

router.get("/business", async (req, res) => {
  const language = new (require(`../../languages/english`))();
  res.render("businessAccount.ejs", {
    app: req.app,
    language: language,
  });
});

module.exports = router;
