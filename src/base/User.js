const { model, Schema } = require("mongoose");
const config = require("../config");

module.exports = model("UserData", new Schema ({
    id: { type: String },
    username: {type: String },
    Password: { typr: String },
    verified: { type: Boolean, default: false },
    language: { type: String, default: config.defaults.language },
    userFullName: { type: String },
    mobile: { type: String },
    email: { type: String },
    address: { type: String },
}), "UserData");