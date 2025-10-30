const { model, Schema } = require("mongoose");
const config = require("../config");
const { version } = require("../../package.json");

module.exports = model("StoreInfo", new Schema ({
    id: { type: Number, default: 0 },
    name: { type: String, default: config.defaults.name },
    telephone: { type: String, default: config.defaults.telephone },
    email: { type: String, default: config.defaults.email },
    mobile: { type: String, default: config.defaults.mobile }
}), "StoreInfo");