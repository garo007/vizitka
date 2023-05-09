import {locale} from "../constants/localization";
import uiEn from 'element-plus/lib/locale/lang/en';

let element_plus_locale;

if (locale === 'en'){
    element_plus_locale = uiEn;
}

export const configs = {
    locale,
    element_plus_locale
};
