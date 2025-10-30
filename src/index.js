const config = require("./config");
const app = require("./website/app")

if (config.turnedOn) {
    app.run()
} else {
    console.log("Website is turned off!!, Please check the Configurations file")
}