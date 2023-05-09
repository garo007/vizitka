export default {
    state: {
        client: null
    },
    mutations: {
        setClient(state, payload) {
            state.client = payload;
        }
    },
    getters: {
        getClient(state) {
            return state.client;
        }
    }
};
