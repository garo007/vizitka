export default {
    state: {
        admin: null
    },
    mutations: {
        setAdmin(state, payload) {
            state.admin = payload;
        }
    },
    getters: {
        getAdmin(state) {
            return state.admin;
        }
    }
};
