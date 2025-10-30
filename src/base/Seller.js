const { model, Schema } = require("mongoose");
const config = require("../config");

module.exports = model("SellersData", new Schema ({
    id: { type: String },
    username: {type: String },
    Password: { typr: String },
    verified: { type: Boolean, default: false },
    language: { type: String, default: config.defaults.language },
    FullName: { type: String },
    mobile: { type: String },
    email: { type: String },
    address: { type: String },
    businessName: { type: String },
    businessTelephone: { type: String },
    budinessAddress: { type: String },
    businessEmail: { type: String },
    cardImage: { data: Buffer, contentType: String }
}), "SellersData");