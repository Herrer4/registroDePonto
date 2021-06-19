import Vue from "vue";

import Vuex from "vuex";
Vue.use(Vuex);
export default new Vuex.Store({
    state: {
        leftMenu: null,
        user: {},
        token: null,
        datahora: null,
        currentRoomSuffix: null,
        getRota: ''
    },
    mutations: {
        setDataHota(state, datahora) {
            state.datahora = datahora;
        },
        updateRotaStore(state, rota) {
            state.getRota = rota;
        },

        storeToken(state, token) {
            state.token = token;
        },

        storeUser(state, user) {
            state.user = user;
        },

        toggleMenu(state) {
            state.leftMenu = !state.leftMenu
        },

        storeRoom(state, roomSuffix) {
            state.currentRoomSuffix = roomSuffix;
        }

    },
    actions: {

    }
});
