<template>
    <div class="popup air-tickets-popup">
        <div class="popup-inner">
            <div class="popup-header">
                <h1>{{ $t('add_air_ticket') }}</h1>
                <img src="../../../images/close.png" @click="$emit('closePopup')" alt>
            </div>
            <div class="popup-body scroll-green">
                <div class="air-tickets-form">
                    <AirTicket v-if="steps.air_ticket"></AirTicket>
                    <AirTicketSlices v-if="steps.air_ticket_slices"></AirTicketSlices>
                </div>
            </div>
            <div class="popup-footer">
                <el-button @click="prev(current_step)" size="large" type="danger" round>{{ $t('prev') }}</el-button>
                <div class="input-number">
                    <el-input-number size="large" v-model="quantity" :min="1" :max="50" />
                </div>
                <el-button v-if="!last_step" @click="next(current_step)" :disabled="disabled" size="large" type="success" round>{{ $t('next') }}</el-button>
                <el-button v-else @click="save" size="large" type="success" :disabled="disabled" round>{{ $t('save') }}</el-button>
            </div>
        </div>
    </div>
</template>

<script>
import AirTicket from "./steps/AirTicket.vue";
import AirTicketSlices from "./steps/AirTicketSlices.vue";
import {computed} from 'vue';
import {ElMessage} from "element-plus";
import {api_url} from "../../../constants/api";
import {$api} from "../../../api/api";

export default {
    name: "AddPopup",
    components: {
        AirTicket,
        AirTicketSlices
    },
    props: {
        air_tickets: Object
    },
    data() {
        return {
            current_step: 'air_ticket',
            last_step: false,
            steps: {
                air_ticket: true,
                air_ticket_slices: false
            },
            data: {
                type: 'one-way',
                cabin_class: null,
                live_mode: false,
                slices: []
            },
            quantity: 1,
            disabled: true
        }
    },
    provide() {
        return {
            data: computed(() => this.data),
        }
    },
    methods: {
        next(step) {
            let keys = Object.keys(this.steps);
            if (step === keys[keys.length - 1]) return;
            if (step === keys[keys.length - 2]) this.last_step = true;

            for (const property in this.steps) {
                if(property === step) {
                    let nextIndex = keys.indexOf(property) + 1;
                    let nextItem = keys[nextIndex];
                    this.steps[property] = false;
                    this.steps[nextItem] = true;
                    this.current_step = nextItem;
                    this.disabled = true;
                    break;
                }
            }

            if (this.current_step === 'air_ticket_slices' && this.data.slices[0]?.origin && this.data.slices[0]?.destination) {
                this.disabled = false;
            }
        },
        prev(step) {
            let keys = Object.keys(this.steps);
            if (step === keys[0]) return;

            for (const property in this.steps) {
                if(property === step) {
                    let prevIndex = keys.indexOf(property) - 1;
                    let prevItem = keys[prevIndex];
                    this.steps[property] = false;
                    this.steps[prevItem] = true;
                    this.current_step = prevItem;
                    this.disabled = false;
                    break;
                }
            }

            if (this.current_step !== keys[keys.length - 1]) this.last_step = false;
        },
        save() {
            this.$store.commit('setPreloader', true);
            this.data.quantity = this.quantity;
            $api().post(api_url + 'air-ticket', this.data).then(({data}) => {
                if (data.success) {
                    data.air_tickets.map(item => {
                        this.air_tickets.unshift(item);
                    });

                    this.$emit('closePopup');
                    ElMessage({
                        message: data.message,
                        showClose: true,
                        type: 'success'
                    });
                } else {
                    let errors = Object.keys(data.errors);
                    errors.map(error => {
                        ElMessage({
                            message: data.errors[error],
                            showClose: true,
                            type: 'warning'
                        });
                    });
                }
                this.$store.commit('setPreloader', false);
            }).catch(() => {
                this.$store.commit('setPreloader', false);
            })
        }
    },
    watch: {
        data: {
            deep: true,
            immediate: true,
            handler: function (data) {
                if (this.current_step === 'air_ticket' && data.cabin_class) {
                    this.disabled = false;
                } else this.disabled = !(this.current_step === 'air_ticket_slices' && data.slices[0].origin && data.slices[0].destination);
            }
        }
    }
}
</script>
