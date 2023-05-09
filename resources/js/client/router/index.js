import {createWebHistory, createRouter} from "vue-router";
import {api_url} from "../constants/api";
import moment from "moment";
import store from "../store";
import {$api} from "../api/api";

const routes = [
    {
        path: "/client/login",
        name: "login",
        component: () => import("@/client/views/auth/Login.vue"),
        meta: {auth: 'guest'}
    },
    {
        path: "/client/forgot-password",
        name: "forgot-password",
        component: () => import("@/client/views/auth/ForgotPassword.vue"),
        meta: {auth: 'guest'}
    },
    {
        path: "/client/reset-password",
        name: "reset-password",
        component: () => import("@/client/views/auth/ResetPassword.vue"),
        meta: {auth: 'guest'}
    },
    {
        path: "/client/profile/:prefix",
        name: "profile",
        component: () => import("@/client/views/screens/profile/Index.vue"),
        meta: {auth: 'auth'}
    },
    {
        path: "/client/agreements/:prefix",
        name: "agreements",
        component: () => import("@/client/views/screens/agreements/Index.vue"),
        meta: {auth: 'auth'}
    },
    {
        path: "/client/air-ticket",
        name: "air-ticket",
        component: () => import("@/client/views/screens/air-ticket/Index.vue"),
        meta: {auth: 'auth'}
    },
    {
        path: "/:catchAll(.*)",
        name: 'not-found',
        component: import("@/client/views/others/NotFound.vue"),
        meta: {auth: 'each'}
    }
];

const router = createRouter({
    history: createWebHistory(),
    mode: 'hash',
    routes
});

router.beforeEach(async (to, from, next) => {
    store.commit("setSidebar", false);
    store.commit("setPopups", {profile: false});

    let client = null;

    let reset = () => {
        store.commit("setToken", null);
        store.commit("setExpiredAt", null);
        store.commit("setIsLogged", false);
        store.commit("setClient", null);
        next('/client/login');
    };

    if (store.getters.getIsLogged) {
        let now = moment().format('YYYY-MM-DD HH:mm:ss');
        let expired_at = moment(store.getters.getExpiredAt);
        let is_expired = expired_at.diff(now) <= 0;

        if (is_expired) {
            reset();
        } else {
            await $api().post(api_url + "me").then(({data}) => {
                if (data.success) {
                    client = data.client;
                    store.commit('setClient', client);
                } else {
                    reset();
                }
            }).catch(() => {
                reset();
            });
        }
    }

    if (to.meta.auth === 'guest') {
        if (client) {
            next('/client/profile/details');
        } else {
            next();
        }
    } else if (to.meta.auth === 'auth') {
        if (client) {
            next();
        } else {
            next('/client/login');
        }
    } else {
        next();
    }
});

export default router;
