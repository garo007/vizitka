import store from '../store';
import moment from "moment";

export default {
    methods: {
        $Client() {
            return store.getters.getClient;
        },
        $Device() {
            return store.getters.getDevice;
        },
        $Date(date, format){
            return moment(date).format(format);
        },
        $HasError(errors, field) {
            return errors[field] !== undefined;
        },
        $GetError(errors, field) {
            return errors[field];
        }
    }
};
