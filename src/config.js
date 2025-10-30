const currentYear = new Date().getFullYear();

module.exports = {
    website: "https://tulip-os.com",
    turnedOn: true,
    mongoUrl: "mongodb://localhost:27017/TulipStore",
    port: 3000,
    defaults: {
        name: "Tulip Store",
        telephone: "123456798",
        mobile: "099123456789",
        email: "info@tulip-os.com",
        language: "english",
        copyrights: `© ${currentYear}. Tulip Store`,
        mainColor: "#0D464C",
        secondaryColor: "#F05928"
    },
}