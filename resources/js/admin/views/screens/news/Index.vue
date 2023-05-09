<template>
    <section class="news-section default-section">
        <div class="section-title">
            <h1>{{ $t('news') }}</h1>
        </div>
        <div class="clients-filters">
            <div class="clients-filter">
                <router-link :to="{name: 'create-news'}">
                    <el-button type="primary" >Create News</el-button>
                </router-link>
            </div>
        </div>
        <div class="news-content">
            <div class="table-parent scroll-grey">
                <el-table width="500" border :empty-text="$t('no_news')" :data="data">
                    <el-table-column width="100" :label="$t('actions')" class-name="table-actions">
                        <template #default="scope">
                            <el-popconfirm
                                width="max-content"
                                :confirm-button-text="$t('yes')"
                                :cancel-button-text="$t('no')"
                                :hide-icon="true"
                                :title="$t('delete_news', { company: scope.row.company_name })"
                                @confirm="destroy(scope.row)"
                            >
                                <template #reference>
                                    <el-button type="danger" icon="El-Icon-Delete" circle></el-button>
                                </template>
                            </el-popconfirm>
                            <router-link :to="{name: 'edit-news', params: {prefix: scope.row.id}}">
                                <el-button type="warning" icon="El-Icon-Edit" circle></el-button>
                            </router-link>
                        </template>
                    </el-table-column>
                    <el-table-column width="300" :label="$t('title')" class-name="flex-direction">
                        <template #default="scope">
                            <span>{{ scope.row.title }}</span>
                        </template>
                    </el-table-column>
                    <el-table-column width="200" :label="$t('info')">
                        <template #default="scope">
                            <div v-html="scope.row.description"></div>
                        </template>
                    </el-table-column>
                    <el-table-column width="200" :label="$t('icon')">
                        <template #default="scope">
                            <img v-if="scope.row.image" :src="scope.row.image"  alt="icon" width="50">
                            <div v-else>
                                no Image
                            </div>
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
        this.getNews();
    },
    data() {
        return {
            news: [],
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
        getNews() {
            this.$store.commit('setPreloader', true);
            $api().get(api_url + 'news').then(({data}) => {
                console.log(data.news)
                this.news = data.news;
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
            $api().post(api_url + 'news/' + action, {ids: [id]}).then(({data}) => {
                data.news.forEach(rubric => {
                    this.news.splice(this.news.findIndex(c => c.id === rubric.id), 1, rubric);
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
        destroy (rubric) {
            $api().delete(api_url + 'news/' + rubric.id).then(({data}) => {
                ElMessage({
                    message: data.message,
                    showClose: true,
                    type: 'success'
                });

                let index = this.news.findIndex(el => el.id === rubric.id);

                this.news.splice(index, 1);

                this.$store.commit('setPreloader', false);
            }).catch(() => {
                this.$store.commit('setPreloader', false);
            });
        }
    },
    computed: {
        filtered: function () {
            return this.news.filter(rubric => {
                let matched = true;

                if (this.filters.company_name){
                    if (rubric.company_name.toLowerCase().search(this.filters.company_name.toLowerCase()) === -1){
                        matched = false;
                    }
                }

                if (this.filters.email){
                    if (rubric.email.toLowerCase().search(this.filters.email.toLowerCase()) === -1){
                        matched = false;
                    }
                }

                if (this.filters.phone_number){
                    if (rubric.phone_number.toLowerCase().search(this.filters.phone_number.toLowerCase()) === -1){
                        matched = false;
                    }
                }

                if (this.filters.tin){
                    if (rubric.tin.toLowerCase().search(this.filters.tin.toLowerCase()) === -1){
                        matched = false;
                    }
                }

                if (this.filters.status === 'active'){
                    if (rubric.accepted_at === null){
                        matched = false;
                    }
                }

                if (this.filters.status === 'awaits'){
                    if (!(rubric.accepted_at === null && rubric.rejected_at === null)){
                        matched = false;
                    }
                }

                if (this.filters.status === 'disabled'){
                    if (rubric.rejected_at === null){
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
