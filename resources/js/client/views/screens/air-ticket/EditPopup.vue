<template>
    <div class="popup air-tickets-popup">
        <div class="popup-inner">
            <div class="popup-header">
                <h1>{{ $t('edit_air_ticket') }}</h1>
                <img src="../../../images/close.png" @click="$emit('closeEditPopup')" alt>
            </div>
            <div class="popup-body scroll-green">
                <div class="air-tickets-form">
                    <div class="form-field">
                        <div class="field-label">{{ $t('cabin_class_name') }}</div>
                        <el-select
                            v-model="form.cabin_class"
                            size="large"
                            :placeholder="$t('choose_cabin_class')">
                            <el-option class="select-option" :label="$t('cabin_class.first')" value="first"></el-option>
                            <el-option class="select-option" :label="$t('cabin_class.business')"
                                       value="business"></el-option>
                            <el-option class="select-option" :label="$t('cabin_class.premium_economy')"
                                       value="premium_economy"></el-option>
                            <el-option class="select-option" :label="$t('cabin_class.economy')"
                                       value="economy"></el-option>
                        </el-select>
                    </div>
                    <div class="form-field">
                        <div>
                            <el-checkbox v-model="form.live_mode" :label="$t('live_mode')" size="large"/>
                        </div>
                    </div>
                    <el-divider />
                    <div class="form-field">
                        <div class="demo-date-picker air-tickets-date-picker">
                            <div class="block">
                                <div class="field-label">{{ $t('departure_date') }}</div>
                                <el-date-picker
                                    v-model="form.slices[0].departure_date"
                                    type="date"
                                    value-format="YYYY-MM-DD"
                                    :placeholder="$t('pick_a_day')"
                                    size="large"
                                    :disabled-date="disabledDate"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="form-field">
                        <div class="field-label">{{ $t('origin') }}</div>
                        <el-select
                            v-model="value_origin"
                            class="select-slice"
                            size="large"
                            filterable
                            remote
                            placeholder="Origin"
                            @change="selectSlice($event, 'origin')"
                            :remote-method="remoteMethod"
                            :loading="loading">
                            <el-option
                                v-for="item in options"
                                :key="item.icao"
                                :label="formatLabel(item.city, item.name)"
                                :value="item.icao">
                                <p class="option-name"><span>{{ item.iata }}</span> {{ item.city }}</p>
                                <p class="option-name">{{ item.name }}</p>
                            </el-option>
                        </el-select>
                    </div>
                    <div class="form-field">
                        <div class="field-label">{{ $t('destination') }}</div>
                        <el-select
                            v-model="value_destination"
                            size="large"
                            filterable
                            remote
                            reserve-keyword
                            placeholder="Destination"
                            @change="selectSlice($event, 'destination')"
                            :remote-method="remoteMethod"
                            :loading="loading">
                            <el-option
                                v-for="item in options"
                                :key="item.value"
                                :label="formatLabel(item.city, item.name)"
                                :value="item.icao">
                                <span class="option-name"><span>{{ item.iata }}</span> {{ item.city }}</span>
                                <span class="option-name">{{ item.name }}</span>
                            </el-option>
                        </el-select>
                    </div>
                </div>
            </div>
            <div class="popup-footer">
                <el-button @click="$emit('closeEditPopup');" size="large" type="danger" round>{{ $t('close') }}</el-button>
                <el-button @click="save" size="large" type="success" round>{{ $t('save') }}</el-button>
            </div>
        </div>
    </div>
</template>

<script>
import Airports from "../../../airports.json";
import {$api} from "../../../api/api";
import {api_url} from "../../../constants/api";
import {ElMessage} from "element-plus";

export default {
    name: "EditPopup",
    mounted() {
        this.list_origin = this.airports.map(item => {
            return {value_origin: `${item.city}`, label: `${item.city}`};
        });
        this.list_destination = this.airports.map(item => {
            return {value_destination: `${item.city}`, label: `${item.city}`};
        });
        if (this.form.slices.length !== 0) {
            let slices = this.form.slices[0];
            if (!!slices.origin) {
                this.value_origin = this.formatLabel(slices.origin.city_name, slices.origin.name);
            }
            if (!!slices.destination) {
                this.value_destination = this.formatLabel(slices.destination.city_name, slices.destination.name);
            }
        }
    },
    props: {
        air_ticket: Object,
        air_tickets: Object
    },
    data() {
        return {
            form: {...this.air_ticket},
            airports: Airports,
            options: [],
            loading: false,
            list_origin: [],
            list_destination: [],
            value_origin: null,
            value_destination: null,
        }
    },
    methods: {
        disabledDate(time) {
            let d = new Date();
            return time.getTime() <= d.setDate(d.getDate() - 1);
        },
        remoteMethod(query) {
            if (query !== '') {
                this.loading = true;
                setTimeout(() => {
                    this.loading = false;
                    this.options = this.airports.filter(item => {
                        return item.city.toLowerCase().indexOf(query.toLowerCase()) > -1
                            || item.iata && item.iata.toLowerCase().indexOf(query.toLowerCase()) > -1;
                    }).slice(0, 50);
                }, 200);
            }
        },
        formatLabel(city_name, airport) {
            return city_name + ' - ' + airport;
        },
        selectSlice($event, value) {
            let airport = this.airports.filter(item => {
                return item.icao.toLowerCase()
                    .indexOf($event.toLowerCase()) > -1;
            })[0];

            let airports_list = this.filterAirports(airport);

            this.form.slices[0][value] = {
                type: this.form.slices[0][value + '_type'],
                time_zone: airport.tz,
                name: airport.name,
                longitude: airport.lon,
                latitude: airport.lat,
                icao_code: airport.icao,
                iata_country_code: airport.country,
                iata_code: airport.iata,
                iata_city_code: airport.iata_city_code,
                city_name: airport.city,
                city: {
                    name: airport.city,
                    iata_country_code: airport.country,
                    iata_code: airport.iata_city_code
                },
                airports: airports_list
            };

            this['value_' + value] = this.formatLabel(airport.city, airport.name);
        },
        filterAirports(airport) {
            let airports_list = this.airports.filter(item => {
                return item.city.toLowerCase().indexOf(airport.city.toLowerCase()) > -1 &&
                    item.country.toLowerCase().indexOf(airport.country.toLowerCase()) > -1;
            });
            let array = [];
            airports_list.map(item => {
                array.push({
                    time_zone: item.tz,
                    name: item.name,
                    longitude: item.lon,
                    latitude: item.lat,
                    icao_code: item.icao,
                    iata_country_code: item.country,
                    iata_code: item.iata,
                    iata_city_code: item.iata_city_code,
                    city_name: item.city,
                    city: {
                        name: item.city,
                        iata_country_code: item.country,
                        iata_code: item.iata_city_code
                    }
                })
            });
            return array;
        },
        save() {
            this.$store.commit('setPreloader', true);

            $api().put(api_url + 'air-ticket/' + this.form.id, this.form).then(({data}) => {
                if (data.success) {
                    let index = this.air_tickets.findIndex(item => item.id === this.form.id);
                    this.air_tickets[index] = data.air_ticket;
                    this.$emit('closeEditPopup');

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
    }
}
</script>
