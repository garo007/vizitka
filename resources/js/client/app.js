import {createApp} from 'vue';
import App from './App.vue';
import i18n from "./plugins/i18n";
import store from './store';
import router from './router';
import {configs} from "./js/configs";
import ElementPlus from 'element-plus';
import moment from "moment";
import '@fortawesome/fontawesome-free/css/all.css';
import 'element-plus/dist/index.css';
import './js/main';
import * as ElementPlusIconsVue from '@element-plus/icons-vue';

moment.locale(configs.locale);

const app = createApp(App)
    .use(i18n)
    .use(store)
    .use(router)
    .use(ElementPlus, {locale: configs.element_plus_locale});

for (const [key, component] of Object.entries(ElementPlusIconsVue)) {
    app.component(`El-Icon-${key}`, component);
}

app.mount('#app');
