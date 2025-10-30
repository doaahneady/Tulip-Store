const mongoose = require("mongoose");
const { mongoUrl } = require("../config");

module.exports.connectData = () => {
    mongoose.connect(mongoUrl).then(() => {
    console.log("Connected to MongoDB")
})
}