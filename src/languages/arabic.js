const lang = "arabic";

module.exports = class {
  constructor() {
    this.language = {
      main: {
        title: "Tulip Store",
        add: "إضافة",
        delete: "حذف",
        close: "إغلاق",
        serachPlaceHolder: "عن ماذا تبحث لدينا؟",

        all: "الأقسام",
        fashion: "ملابس",
        electronics: "إلكترونيات",
        sport: "معدات رياضية",
        school: "أدوات مدرسية",
        jewelry: "مجوهرات",
        home: "جميع ما يحتاجه المنزل",
        toys: "ألعاب",
        makeup: "مكياج & عناية بالبشرة",

        english: "English",
        arabic: "العربية",

        signIn: "تسجيل دخول",

        email: "البريد الإلكتروني",
        EmailPlaceholder: "أدخل بريدك الإلكتروني",
        password: "كلمة المرور",
        PassPlaceholder: "أدخل كلمة المرور الخاصة بك",
        forgetPass: "هل نسيت كلمة المرور؟",
        signUp: "إنشاء حساب",
        account: "ليس لديك حساب لدينا؟",

        namePlaceholder: "الاسم الكامل",
        PhonePlaceholder: "رقم الموبايل",
        passPlaceholder: "كلمة المرور",
        PreferLang: "لغتك المفضلة:",
        gender: " الجنس:",
        male: "ذكر",
        female: "أنثى",
        invalis_name: "أدخل اسمك الكامل",
        invalid_email: "أدخل بريدك الإلكتروني الصحيح",
        invalid_phone: "أدخل رقم الموبايل الصحيح",
        invalid_pass: " كلمة المرور يجب أن تكون على الأقل من 6 محارف",
        invalid_lang: "لا تنسى إختيار لغتك المفضلة",
        invalid_gender: "لا تنسى اختيار جنسك",
        register: "Register",

        profile: "الحساب",
        edit: "تعديل",
        settings: "الإعدادات",
        orders: "الطلبات",
        signOut: "تسجيل خروج",

        letter1: "أطلب من ",
        letter2: "متجرنا",

        explore: "أكتشف المنتجات",
        gift: "أصنع هديتك",

        business: "إبدأبالعمل معنا",

        info: "المعلومات",
        about: "من نحن",
        contact: "أتصل بنا",
        shopping: "تسوق",

        services: "خدمة العملاء",
        return: "سياسة الإرجاع",
        shipping: "معلومات التوصيل",
        help: "المساعدة",

        newsletter: "Newsletter",
        newsPlaceholder: "كيف يمكننا مساعدتك؟",

        follow: "تابعنا",
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
