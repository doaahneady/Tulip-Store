const { model, Schema } = require("mongoose");
const config = require("../config");

module.exports = model("SystemData", new Schema ({
    id: { type: String },
    domain: {type: String },
    callback: { typr: String },
    language: { type: String, default: config.defaults.language }
}), "SystemData")