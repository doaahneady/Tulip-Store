const { model, Schema } = require("mongoose");

module.exports = model("Currencies", new Schema ({
    id: { type: String },
    name: { type: String },
    price: { type: String },
    default: { type: Boolean, default: false }
}), "Currencies")