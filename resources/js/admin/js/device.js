import store from "../store";

let device = () => {
    if (window.innerWidth > 900) {
        store.commit('setDevice', 'web');
    } else {
        store.commit('setDevice', 'mobile');
    }
};

device();

window.addEventListener('resize', () => {
    device();
});

export {device};