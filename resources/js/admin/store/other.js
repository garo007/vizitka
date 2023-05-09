export default {
    state: {
        device: 'web',
        sidebar: false,
        preloader: false,
        popups: {
            profile: false
        }
    },
    mutations: {
        setDevice(state, payload) {
            state.device = payload;
        },
        setSidebar(state, payload) {
            state.sidebar = payload;
        },
        setPreloader(state, payload) {
            state.preloader = payload;
        },
        setPopups(state, payload) {
            state.popups = payload;
        }
    },
    getters: {
        getDevice(state) {
            return state.device;
        },
        getSidebar(state) {
            return state.sidebar;
        },
        getPreloader(state) {
            return state.preloader;
        },
        getPopups(state) {
            return state.popups;
        }
    }
};
