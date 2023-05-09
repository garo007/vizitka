import createPersistedState from "vuex-persistedstate";
import { createStore } from 'vuex';
import Cookies from "js-cookie";

// Modules
import auth from "./auth";
import client from "./client";
import other from './other';

createPersistedState({storage: window.sessionStorage});

export default new createStore({
    modules: {
        auth,
        client,
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
            paths: ['auth', 'client']
        })
    ]
});






