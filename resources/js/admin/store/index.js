import createPersistedState from "vuex-persistedstate";
import { createStore } from 'vuex';
import Cookies from "js-cookie";

// Modules
import auth from "./auth";
import admin from "./admin";
import other from './other';

createPersistedState({storage: window.sessionStorage});

export default new createStore({
    modules: {
        auth,
        admin,
        other
    },
    plugins: [
        createPersistedState({
            storage: {
                getItem: (key) => Cookies.get(key),
                setItem: (key, value) => Cookies.set(key, value, {
                    expires: 3,
                    secure: import.meta.env.PROD
                }),
                removeItem: (key) => Cookies.remove(key)
            },
            paths: ['auth', 'admin']
        })
    ]
});






