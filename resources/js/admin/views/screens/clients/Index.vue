<template>
    <section class="clients-section default-section">
        <div class="section-title">
            <h1>{{ $t('clients') }}</h1>
        </div>
        <div class="clients-filters">
            <div class="clients-filter">
                <router-link :to="{name: 'create-client'}">
                    <el-button type="primary" >Create Client</el-button>
                </router-link>
            </div>
        </div>
        <div class="clients-content">
            <div class="table-parent scroll-grey">
                <el-table border :empty-text="$t('no_clients')" :data="data">
                    <el-table-column :label="$t('actions')" class-name="table-actions">
                        <template #default="scope">
                            <router-link :to="{name: 'update-client', params: {prefix: scope.row.id}}">
                                <el-button type="warning" icon="El-Icon-Edit" circle></el-button>
                            </router-link>
                            <el-popconfirm
                                width="max-content"
                                :confirm-button-text="$t('yes')"
                                :cancel-button-text="$t('no')"
                                :hide-icon="true"
                                :title="$t('delete_client', { company: scope.row.company_name })"
                                @confirm="destroy(scope.row)"
                            >
                                <template #reference>
                                    <el-button type="danger" icon="El-Icon-Delete" circle></el-button>
                                </template>
                            </el-popconfirm>
                        </template>
                    </el-table-column>
                    <el-table-column :label="$t('name')">
                        <template #default="scope">
                            <span>{{ scope.row.name }}</span>
                        </template>
                    </el-table-column>
                    <el-table-column :label="$t('business_address')">
                        <template #default="scope">
                            <span>{{ scope.row.address }}</span>
                        </template>
                    </el-table-column>
                    <el-table-column :label="$t('phone_number')">
                        <template #default="scope">
                            <span>+3745526655</span>
                        </template>
                    </el-table-column>
                </el-table>
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

    <transition name="fade">
        <RolesPermissions
            v-if="roles_permissions.popup"
            :model="roles_permissions.model"
            @closePopup="toggleRolesPermissions()"
            @handleUpdateClient="updateClient"
        ></RolesPermissions>
    </transition>
</template>

<script>
    import helper from "../../../mixins/helper";
    import {$api} from "../../../api/api";
    import {api_url} from "../../../constants/api";
    import {ElMessage} from 'element-plus';
    import RolesPermissions from "./RolesPermissions.vue";

    export default {
        mixins: [helper],
        components: {RolesPermissions},
        beforeMount() {
            this.getClients();
        },
        data() {
            return {
                clients: [],
                filters: {
                    company_name: null,
                    email: null,
                    phone_number: null,
                    tin: null,
                    status: 'all'
                },
                roles_permissions: {
                    popup: false,
                    model: {}
                },
                pagination: {
                    page: 1,
                    limit: 10
                }
            }
        },
        methods: {
            getClients() {
                this.$store.commit('setPreloader', true);
                $api().get(api_url + 'client').then(({data}) => {
                    this.clients = data.clients;
                    this.$store.commit('setPreloader', false);
                }).catch(() => {
                    this.$store.commit('setPreloader', false);
                });
            },
            paginate(page) {
                this.pagination.page = page;
            },
            toggle(id, action){
                this.$store.commit('setPreloader', true);
                $api().post(api_url + 'client/' + action, {ids: [id]}).then(({data}) => {
                    data.clients.forEach(client => {
                        this.clients.splice(this.clients.findIndex(c => c.id === client.id), 1, client);
                    });
                    ElMessage({
                        message: data.message,
                        showClose: true,
                        type: 'success'
                    });
                    this.$store.commit('setPreloader', false);
                }).catch(() => {
                    this.$store.commit('setPreloader', false);
                });
            },
            toggleRolesPermissions(model = {}) {
                if (this.roles_permissions.popup) {
                    this.roles_permissions = {
                        popup: false,
                        model: {}
                    };
                } else {
                    this.roles_permissions = {
                        popup: true,
                        model
                    };
                }
            },
            updateClient (client) {
                let index = this.clients.findIndex(el => el.id === client.id);
                this.clients[index] = client;
            },
            destroy (client) {
                $api().delete(api_url + 'client/' + client.id).then(({data}) => {
                    ElMessage({
                        message: data.message,
                        showClose: true,
                        type: 'success'
                    });

                    let index = this.clients.findIndex(el => el.id === client.id);

                    this.clients.splice(index, 1);

                    this.$store.commit('setPreloader', false);
                }).catch(() => {
                    this.$store.commit('setPreloader', false);
                });
            }
        },
        computed: {
            filtered: function () {
                return this.clients.filter(client => {
                    let matched = true;

                    if (this.filters.company_name){
                        if (client.company_name.toLowerCase().search(this.filters.company_name.toLowerCase()) === -1){
                            matched = false;
                        }
                    }

                    if (this.filters.email){
                        if (client.email.toLowerCase().search(this.filters.email.toLowerCase()) === -1){
                            matched = false;
                        }
                    }

                    if (this.filters.phone_number){
                        if (client.phone_number.toLowerCase().search(this.filters.phone_number.toLowerCase()) === -1){
                            matched = false;
                        }
                    }

                    if (this.filters.tin){
                        if (client.tin.toLowerCase().search(this.filters.tin.toLowerCase()) === -1){
                            matched = false;
                        }
                    }

                    if (this.filters.status === 'active'){
                        if (client.accepted_at === null){
                            matched = false;
                        }
                    }

                    if (this.filters.status === 'awaits'){
                        if (!(client.accepted_at === null && client.rejected_at === null)){
                            matched = false;
                        }
                    }

                    if (this.filters.status === 'disabled'){
                        if (client.rejected_at === null){
                            matched = false;
                        }
                    }

                    return matched;
                });
            },
            data: function () {
                return this.filtered.slice(this.pagination.limit * this.pagination.page - this.pagination.limit, this.pagination.limit * this.pagination.page);
            }
        },
        watch: {
            data: {
                deep: true,
                immediate: true,
                handler: function (data) {
                    if (data.length === 0 && this.pagination.page > 1){
                        this.pagination.page--;
                    }
                }
            }
        }
    }
</script>
