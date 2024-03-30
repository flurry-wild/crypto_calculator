import { createStore } from 'vuex';

const store = createStore({
    state() {
        return {
            updater: 0
        }
    }
});

export default store;
