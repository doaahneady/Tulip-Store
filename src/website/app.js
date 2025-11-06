const express = require("express");
const config = require("../config");
const { join } = require("path");

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
  app.config = config;
  // Remove MongoDB-based models from the app context
  // app.usersData = require("../base/User");
  // app.Info = require("../base/Info");
  // app.systemData = require("../base/System");
  // app.currencies = require("../base/Currencies");

  app.use(express.json());
  app.use(express.urlencoded({ extended: true }));
  app.engine("html", require("ejs").renderFile);
  app.set("view engin", "ejs");

  app.use((req, res, next) => {
    req.app = app;
    next();
  });

  app.use(express.static(join(__dirname, "/public")));
  app.set("views", join(__dirname, "/views"));
  app.set("port", config.port);
  app.use("/", mainRouter);
  app.use("/signin", signInRouter);
  app.use("/contact", contactUsRouter);
  app.use("/business", businessRouter);
  app.use("/ReturnPolicy", ReturnPolicy);

  app.listen(app.get("port"), () => {
    console.log(
      `${config.defaults.name}'s Website is running on port: ${app.get(
        "port"
      )} !`
    );
  });
};
