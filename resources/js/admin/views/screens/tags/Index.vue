<template>
    <section class="tags-section default-section">
        <div class="section-title">
            <h1>{{ $t('tags') }}</h1>
        </div>
        <div class="clients-filters">
            <div class="clients-filter">
                <router-link :to="{name: 'create-tag'}">
                    <el-button type="primary" >Create Tag</el-button>
                </router-link>
            </div>
        </div>
        <div class="tags-content">
            <div class="table-parent scroll-grey">
                <el-table width="500" border :empty-text="$t('no_rubric')" :data="data">
                    <el-table-column width="100" :label="$t('actions')" class-name="table-actions">
                        <template #default="scope">
                            <el-popconfirm
                                width="max-content"
                                :confirm-button-text="$t('yes')"
                                :cancel-button-text="$t('no')"
                                :hide-icon="true"
                                :title="$t('delete_rubric', { company: scope.row.company_name })"
                                @confirm="destroy(scope.row)"
                            >
                                <template #reference>
                                    <el-button type="danger" icon="El-Icon-Delete" circle></el-button>
                                </template>
                            </el-popconfirm>
                            <router-link :to="{name: 'edit-tag', params: {prefix: scope.row.id}}">
                                <el-button type="warning" icon="El-Icon-Edit" circle></el-button>
                            </router-link>
                        </template>
                    </el-table-column>
                    <el-table-column width="300" :label="$t('name')" class-name="flex-direction">
                        <template #default="scope">
                            <span>{{ scope.row.name }}</span>
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
</template>

<script>
import helper from "../../../mixins/helper";
import {$api} from "../../../api/api";
import {api_url} from "../../../constants/api";
import {ElMessage} from "element-plus";

export default {
    mixins: [helper],
    beforeMount() {
        this.getTags();
    },
    data() {
        return {
            tags: [],
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
        getTags() {
            this.$store.commit('setPreloader', true);
            $api().get(api_url + 'tags').then(({data}) => {
                this.tags = data.tags;
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
            $api().post(api_url + 'tags/' + action, {ids: [id]}).then(({data}) => {
                data.tags.forEach(tag => {
                    this.tags.splice(this.tags.findIndex(c => c.id === tag.id), 1, tag);
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
        destroy (tag) {
            $api().delete(api_url + 'tags/' + tag.id).then(({data}) => {
                ElMessage({
                    message: data.message,
                    showClose: true,
                    type: 'success'
                });

                let index = this.tags.findIndex(el => el.id === tag.id);

                this.tags.splice(index, 1);

                this.$store.commit('setPreloader', false);
            }).catch(() => {
                this.$store.commit('setPreloader', false);
            });
        }
    },
    computed: {
        filtered: function () {
            return this.tags.filter(tag => {
                let matched = true;

                if (this.filters.name){
                    if (tag.name.toLowerCase().search(this.filters.company_name.toLowerCase()) === -1){
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

<style scoped>

</style>
