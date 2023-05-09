import {createWebHistory, createRouter} from "vue-router";
import {api_url} from "../constants/api";
import moment from "moment";
import store from "../store";
import {$api} from "../api/api";

const routes = [
    {
        path: "/admin/login",
        name: "login",
        component: () => import("@/admin/views/auth/Login.vue"),
        meta: {auth: 'guest'}
    },
    {
        path: "/admin/clients",
        name: "clients",
        component: () => import("@/admin/views/screens/clients/Index.vue"),
        meta: {auth: 'auth'}
    },
    {
        path: "/admin/clients/create",
        name: "create-client",
        component: () => import("@/admin/views/screens/clients/Store.vue"),
        meta: {auth: 'auth'}
    },
    {
        path: "/admin/clients/update/:prefix",
        name: "update-client",
        component: () => import("@/admin/views/screens/clients/Update.vue"),
        meta: {auth: 'auth'}
    },
    {
        path: "/admin/rubrics",
        name: "rubrics",
        component: () => import("@/admin/views/screens/rubrics/Index.vue"),
        meta: {auth: 'auth'}
    },
    {
        path: "/admin/rubrics/edit/:prefix",
        name: "edit-rubric",
        component: () => import("@/admin/views/screens/rubrics/Edit.vue"),
        meta: {auth: 'auth'}
    },
    {
        path: "/admin/news",
        name: "news",
        component: () => import("@/admin/views/screens/news/Index.vue"),
        meta: {auth: 'auth'}
    },
    {
        path: "/admin/news/create",
        name: "create-news",
        component: () => import("@/admin/views/screens/news/Create.vue"),
        meta: {auth: 'auth'}
    },
    {
        path: "/admin/news/edit/:prefix",
        name: "edit-news",
        component: () => import("@/admin/views/screens/news/Update.vue"),
        meta: {auth: 'auth'}
    },
    {
        path: "/admin/tags",
        name: "tags",
        component: () => import("@/admin/views/screens/tags/Index.vue"),
        meta: {auth: 'auth'}
    },
    {
        path: "/admin/tags/create",
        name: "create-tag",
        component: () => import("@/admin/views/screens/tags/Create.vue"),
        meta: {auth: 'auth'}
    },
    {
        path: "/admin/tags/edit/:prefix",
        name: "edit-tag",
        component: () => import("@/admin/views/screens/tags/Update.vue"),
        meta: {auth: 'auth'}
    },
    {
        name: 'not-found',
        path: "/:catchAll(.*)",
        component: import("@/admin/views/others/NotFound.vue"),
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

    let admin = null;

    let reset = () => {
        store.commit("setToken", null);
        store.commit("setExpiredAt", null);
        store.commit("setIsLogged", false);
        store.commit("setAdmin", null);
        next('/admin/login');
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
                    admin = data.admin;
                    store.commit('setAdmin', admin);
                } else {
                    reset();
                }
            }).catch(() => {
                reset();
            });
        }
    }

    if (to.meta.auth === 'guest') {
        if (admin) {
            next('/admin/clients');
        } else {
            next();
        }
    } else if (to.meta.auth === 'auth') {
        if (admin) {
            next();
        } else {
            next('/admin/login');
        }
    } else {
        next();
    }
});

export default router;
