const lang = "english";

module.exports = class {
  constructor() {
    this.language = {
      main: {
        title: "Tulip Store",
        add: "Add",
        delete: "Delete",
        close: "close",

        // home page
        serachPlaceHolder: "What are you searching for ?",
        all: "All",
        fashion: "Fashion",
        electronics: "Electronics",
        sport: "Sport",
        school: "School",
        jewelry: "Jewelry",
        home: "Kitchen & Home",
        toys: "Toys",
        makeup: "Beauty & Makeup",

        english: "English",
        arabic: "العربية",

        // sign in
        signIn: "Sign In",

        email: "Email",
        EmailPlaceholder: "Enter your Email",
        password: "Password",
        PassPlaceholder: "Enter your Password",
        forgetPass: "Forgot password?",
        signUp: "Sign Up",
        account: "Don't have an account?",

        // sign up
        namePlaceholder: "Full Name",
        PhonePlaceholder: "Phone Number",
        passPlaceholder: "Password",
        PreferLang: "Preferred language:",
        gender: " Your gender:",
        male: "Male",
        female: "Femal",
        invalis_name: "Please enter your full name.",
        invalid_email: "Please enter a valid email address.",
        invalid_phone: "Please enter a valid phone number.",
        invalid_pass: " Please enter a password (min 6 characters).",
        invalid_lang: "Please select a preferred language.",
        invalid_gender: "Please select your gender.",
        register: "Register",

        profile: "Profile",
        edit: "Edit",
        settings: "Settings",
        orders: "Orders",
        signOut: "Sign Out",

        letter1: "Get Or",
        letter2: "der Now",

        explore: "Explore",
        gift: "Make your GIFT",
        // footer
        business: "Start business with us",

        info: "Info",
        about: "About Tulip Store",
        contact: "Contact Us",
        shopping: "Shopping",

        services: "Customers Services",
        return: "Return Policy",
        shipping: "Shipping Info",
        help: "Help & FAQ",

        newsletter: "Newsletter",
        newsPlaceholder: "How can we help you?",

        follow: "Follow Us",

        // products
        products: "Products",
        productName: "Product Name",
        Price: "$",
        description: "description about this product...",

        //one product
      },
    };
  }

  /**
   * The method to get language strings
   * @param {string} term The string or function to look up
   * @param {...*} args Any arguments to pass to the lookup
   * @returns {string|Function}
   */
  get(term, ...args) {
    //if (!this.enabled && this !== this.store.default) return this.store.default.get(term, ...args);
    const value = this.language[term];
    /* eslint-disable new-cap */
    switch (typeof value) {
      case "function":
        return value(...args);
      default:
        return value;
    }
  }

  getLang() {
    return lang;
  }
};
