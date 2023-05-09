<template>
    <section class="air-tickets-section default-section">
        <div class="section-title">
            <h1>{{ $t('air_tickets') }}</h1>
        </div>
        <div class="air-tickets-action">
            <el-button @click="toggleAddPopup()" size="large" type="success" round>{{ $t('add') }}</el-button>
        </div>
        <div class="air-tickets-filters">
            <div class="air-tickets-filter form-field">
                <div class="field-label">{{ $t('country') }}</div>
                <el-input size="large" v-model="filters.country" :placeholder="$t('country')"></el-input>
            </div>
            <div class="air-tickets-filter form-field">
                <div class="field-label">{{ $t('city') }}</div>
                <el-input size="large" v-model="filters.city" :placeholder="$t('city')"></el-input>
            </div>
            <div class="air-tickets-filter form-field">
                <div class="field-label">{{ $t('airport') }}</div>
                <el-input size="large" v-model="filters.airport" :placeholder="$t('airport')"></el-input>
            </div>
            <div class="air-tickets-filter form-field">
                <div class="field-label">{{ $t('departure_date') }}</div>
                <el-input size="large" v-model="filters.departure_date" :placeholder="$t('departure_date')"></el-input>
            </div>
            <div class="air-tickets-filter form-field">
                <div class="field-label">{{ $t('cabin_class_name') }}</div>
                <el-select
                    v-model="filters.cabin_class"
                    size="large"
                    remote
                    reserve-keyword
                    :placeholder="$t('cabin_class_name')">
                    <el-option :label="$t('all')" value="all"></el-option>
                    <el-option :label="$t('cabin_class.first')" value="first"></el-option>
                    <el-option :label="$t('cabin_class.business')" value="business"></el-option>
                    <el-option :label="$t('cabin_class.premium_economy')" value="premium_economy"></el-option>
                    <el-option :label="$t('cabin_class.economy')" value="economy"></el-option>
                </el-select>
            </div>
            <div class="air-tickets-filter form-field">
                <div class="field-label">{{ $t('filter_by') }}</div>
                <el-select
                    v-model="filters.filter_by"
                    size="large"
                    remote
                    reserve-keyword
                    :placeholder="$t('filter_by')">
                    <el-option :label="$t('all')" value="all"></el-option>
                    <el-option :label="$t('origin')" value="origin"></el-option>
                    <el-option :label="$t('destination')" value="destination"></el-option>
                </el-select>
            </div>
        </div>
        <div class="air-tickets-content">
            <div class="table-parent scroll-grey">
                <div class="table-parent scroll-grey">
                    <el-table border :empty-text="$t('no_air_ticket')" :data="data">
                        <el-table-column type="expand">
                            <template #default="scopes">
                                <div class="air-tickets-details-table">
                                    <p>{{ $t('origin') }}</p>
                                    <el-table border :data="[scopes.row.slices[0].origin]">
                                        <el-table-column :label="$t('name')" prop="name"/>
                                        <el-table-column :label="$t('iata_code')" prop="iata_code"/>
                                        <el-table-column :label="$t('iata_country_code')" prop="iata_country_code"/>
                                        <el-table-column :label="$t('icao_code')" prop="icao_code"/>
                                        <el-table-column :label="$t('time_zone')" prop="time_zone"/>
                                    </el-table>
                                </div>
                                <div class="air-tickets-details-table">
                                    <p>{{ $t('destination') }}</p>
                                    <el-table border :data="[scopes.row.slices[0].destination]">
                                        <el-table-column :label="$t('name')" prop="name"/>
                                        <el-table-column :label="$t('iata_code')" prop="iata_code"/>
                                        <el-table-column :label="$t('iata_country_code')" prop="iata_country_code"/>
                                        <el-table-column :label="$t('icao_code')" prop="icao_code"/>
                                        <el-table-column :label="$t('time_zone')" prop="time_zone"/>
                                    </el-table>
                                </div>
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('origin')">
                            <template #default="scope">
                                {{ scope.row.slices[0].origin.city_name }}
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('destination')">
                            <template #default="scope">
                                {{ scope.row.slices[0].destination.city_name }}
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('departure_date')">
                            <template #default="scope">
                                {{ scope.row.slices[0].departure_date }}
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('cabin_class_name')">
                            <template #default="scope">
                                {{ scope.row.cabin_class ? $t('cabin_class.' + scope.row.cabin_class) : '' }}
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('created_at')">
                            <template #default="scope">
                                {{ $Date(scope.row.created_at, 'YYYY-MM-DD') }}
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('live_mode')">
                            <template #default="scope">
                                <el-button v-if="scope.row.live_mode" type="success" icon="El-Icon-SwitchButton"
                                           circle/>
                                <el-button v-else type="danger" icon="El-Icon-MuteNotification" circle/>
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('operations')" fixed="right">
                            <template #default="scope">
                                <el-button
                                    type="primary"
                                    icon="El-Icon-Edit"
                                    circle
                                    @click="toggleEditPopup(scope.row)"
                                />
                                <el-popconfirm
                                    width="max-content"
                                    :confirm-button-text="$t('yes')"
                                    :cancel-button-text="$t('no')"
                                    :hide-icon="true"
                                    :title="$t('delete_air_ticket')"
                                    @confirm="deleteAirTicket(scope.row.id)"
                                >
                                    <template #reference>
                                        <el-button type="danger" icon="El-Icon-Delete" circle></el-button>
                                    </template>
                                </el-popconfirm>
                            </template>
                        </el-table-column>
                    </el-table>
                </div>
            </div>
            <el-pagination
                v-if="filtered.length > pagination.limit"
                background
                layout="pager"
                @current-change="paginate"
                :pagerCount="5"
                :current-page="pagination.page"
                :page-size="pagination.limit"
                :total="filtered.length">
            </el-pagination>
        </div>
    </section>
    <TransitionGroup name="fade">
        <AddPopup
            v-if="show_add_popup"
            :air_tickets="air_tickets"
            @closePopup="toggleAddPopup"
        ></AddPopup>
        <EditPopup
            v-if="show_edit_popup"
            :air_ticket="air_ticket"
            :air_tickets="air_tickets"
            @closeEditPopup="toggleEditPopup"
        ></EditPopup>
    </TransitionGroup>
</template>

<script>
import AddPopup from "./AddPopup.vue";
import EditPopup from "./EditPopup.vue";
import helper from "../../../mixins/helper";
import {api_url} from "../../../constants/api";
import {ElMessage} from "element-plus";
import {$api} from "../../../api/api";

export default {
    mixins: [helper],
    components: {EditPopup, AddPopup},
    beforeMount() {
        this.getAirTickets();
    },
    data() {
        return {
            pagination: {
                page: 1,
                limit: 10
            },
            filters: {
                country: null,
                city: null,
                airport: null,
                departure_date: null,
                cabin_class: 'all',
                filter_by: 'all',
            },
            show_add_popup: false,
            show_edit_popup: false,
            air_tickets: [],
            air_ticket: {}
        }
    },
    methods: {
        paginate(page) {
            this.pagination.page = page;
        },
        toggleAddPopup() {
            this.show_add_popup = !this.show_add_popup;
        },
        toggleEditPopup(air_ticket) {
            this.air_ticket = air_ticket
            this.show_edit_popup = !this.show_edit_popup;
        },
        getAirTickets() {
            this.$store.commit('setPreloader', true);
            $api().get(api_url + 'air-ticket').then(({data}) => {
                this.air_tickets = data.air_tickets;
                this.$store.commit('setPreloader', false);
            }).catch(() => {
                this.air_tickets = [];
                this.$store.commit('setPreloader', false);
            });
        },
        deleteAirTicket(id) {
            this.$store.commit('setPreloader', true);
            $api().delete(api_url + 'air-ticket/' + id).then(({data}) => {
                ElMessage({
                    message: data.message,
                    showClose: true,
                    type: 'success'
                });

                let index = this.air_tickets.findIndex(el => el.id === id);

                this.air_tickets.splice(index, 1);

                this.$store.commit('setPreloader', false);
            }).catch(() => {
                this.$store.commit('setPreloader', false);
            });
        }
    },
    computed: {
        filtered: function () {
            return this.air_tickets.filter(air_ticket => {
                let matched = true;
                const condition = this.filters.filter_by === 'origin' || this.filters.filter_by === 'destination';

                if (this.filters.country) {
                    if (condition && air_ticket.slices[0][this.filters.filter_by].iata_country_code.toLowerCase().search(this.filters.country.toLowerCase()) === -1) {
                        matched = false;
                    } else {
                        if (air_ticket.slices[0].origin.iata_country_code.toLowerCase().search(this.filters.country.toLowerCase()) === -1
                            && (air_ticket.slices[0].destination.iata_country_code.toLowerCase().search(this.filters.country.toLowerCase()) === -1)
                        ) {
                            matched = false;
                        }
                    }
                }

                if (this.filters.city) {
                    if (condition && air_ticket.slices[0][this.filters.filter_by].city_name.toLowerCase().search(this.filters.city.toLowerCase()) === -1) {
                        matched = false;
                    } else {
                        if (air_ticket.slices[0].origin.city_name.toLowerCase().search(this.filters.city.toLowerCase()) === -1 &&
                            air_ticket.slices[0].destination.city_name.toLowerCase().search(this.filters.city.toLowerCase()) === -1
                        ) {
                            matched = false;
                        }
                    }
                }

                if (this.filters.airport) {
                    if (condition && air_ticket.slices[0][this.filters.filter_by].name.toLowerCase().search(this.filters.airport.toLowerCase()) === -1) {
                        matched = false;
                    } else {
                        if (air_ticket.slices[0].origin.name.toLowerCase().search(this.filters.airport.toLowerCase()) === -1 &&
                            air_ticket.slices[0].destination.name.toLowerCase().search(this.filters.airport.toLowerCase()) === -1
                        ) {
                            matched = false;
                        }
                    }
                }

                if (this.filters.departure_date) {
                    if (air_ticket.slices[0].departure_date.toLowerCase().search(this.filters.departure_date.toLowerCase()) === -1) {
                        matched = false;
                    }
                }

                if (this.filters.cabin_class) {
                    if (air_ticket.cabin_class.toLowerCase().search(this.filters.cabin_class.toLowerCase()) === -1 && this.filters.cabin_class !== 'all') {
                        matched = false;
                    }
                }

                return matched;
            });
        },
        data: function () {
            return this.filtered.slice(this.pagination.limit * this.pagination.page - this.pagination.limit, this.pagination.limit * this.pagination.page);
        }
    }
}
</script>
