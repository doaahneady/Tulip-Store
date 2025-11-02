const express = require("express");
const config = require("../config");
const { join } = require("path");

module.exports.run = async () => {
  const app = express();
  require("../functions/database").connectData();

  /* Routers */
  const mainRouter = require("./routes/index");
  const signUpRouter = require("./routes/signUp");
  const contactUsRouter = require("./routes/contactUs");
  const businessRouter = require("./routes/business");

  app.config = config;
  app.usersData = require("../base/User");
  app.Info = require("../base/Info");
  app.systemData = require("../base/System");
  app.currencies = require("../base/Currencies");

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
  app.use("/signup", signUpRouter);
  app.use("/contact", contactUsRouter);
  app.use("/business", businessRouter);

  app.listen(app.get("port"), () => {
    console.log(
      `${config.defaults.name}'s Website is running on port: ${app.get(
        "port"
      )} !`
    );
  });
};
