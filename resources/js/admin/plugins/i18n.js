import {createI18n} from 'vue-i18n';
import {locale, fallbackLocale} from "../constants/localization";
import messages from "../locales";

export default createI18n({
    locale,
    fallbackLocale,
    messages
});