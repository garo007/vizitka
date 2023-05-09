<template>
    <div class="form-field">
        <div class="field-label">{{ $t('departure_date') }}</div>
        <div class="demo-date-picker air-tickets-date-picker">
            <div class="block">
                <el-date-picker
                    v-model="departure_date"
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
            @change="selectSlice($event, 'origin')"
            v-model="value_origin"
            class="select-slice"
            size="large"
            filterable
            remote
            :placeholder="$t('origin')"
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
            @change="selectSlice($event, 'destination')"
            v-model="value_destination"
            size="large"
            filterable
            remote
            reserve-keyword
            :placeholder="$t('destination')"
            :remote-method="remoteMethod"
            :loading="loading">
            <el-option
                v-for="item in options"
                :key="item.icao"
                :label="formatLabel(item.city, item.name)"
                :value="item.icao">
                <span class="option-name"><span>{{ item.iata }}</span> {{ item.city }}</span>
                <span class="option-name">{{ item.name }}</span>
            </el-option>
        </el-select>
    </div>
</template>

<script>
import Airports from './../../../../airports.json';

export default {
    name: "AirTicketSlices",
    inject: ['data'],
    mounted() {
        this.list_origin = this.airports.map(item => {
            return { value_origin: `${item.city}`, label: `${item.city}` };
        });
        this.list_destination = this.airports.map(item => {
            return { value_destination: `${item.city}`, label: `${item.city}` };
        });
        if(this.form_data.slices.length !== 0) {
            let slices = this.form_data.slices[0];
            if (slices.departure_date) this.departure_date = slices.departure_date;
            if (!!slices.origin) {
                this.value_origin = this.formatLabel(slices.origin.city_name, slices.origin.name);
            }
            if (!!slices.destination) {
                this.value_destination = this.formatLabel(slices.destination.city_name, slices.destination.name);
            }
        }
    },
    data() {
        return {
            form_data: this.data,
            airports: Airports,
            options: [],
            loading: false,
            list_origin: [],
            list_destination: [],
            value_origin: null,
            value_destination: null,
            departure_date: null,
        }
    },
    methods: {
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
            } else {
                this.options = [];
            }
        },
        formatLabel(city_name, airport) {
            return  city_name + ' - ' + airport;
        },
        selectSlice($event, value) {
            let airport = this.airports.filter(item => {
                return item.icao.toLowerCase().indexOf($event.toLowerCase()) > -1;
            })[0];

            let airports_list = this.filterAirports(airport);

            this.form_data.slices[0][value] = {
                type: this.form_data.slices[0][value + '_type'],
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
                    iata_city_code: item.city,
                    city_name: item.city,
                    city: {
                        name: item.city,
                        iata_country_code: item.country,
                        iata_code: item.iata_city_code
                    }
                });
            });
            return array;
        },
        disabledDate(time) {
            let date = new Date();
            return time.getTime() <= date.setDate(date.getDate()-1);
        }
    },
    watch: {
        departure_date: {
            deep: true,
            immediate: false,
            handler: function (departure_date) {
                if (!this.form_data.slices.length) {
                    this.form_data.slices[0] = {departure_date};
                }
            }
        }
    }
}
</script>
