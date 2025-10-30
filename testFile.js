app.get("*", function (req, res, next) {
  if (req.headers.host == "business.tulip-os.com")
    //if it's a sub-domain
    req.url = "/business" + req.url; //append some text yourself
  next();
});

// This will mean that all get requests that come from the subdomain will get
// /subdomain appended to them, so then you can have routes like this
app.get("/contact", function () {
  // for non-subdomain
});

app.get("/business/", function () {
  // for subdomain
});
