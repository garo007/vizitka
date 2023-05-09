<template>
    <section class="contracts-section default-section">
        <div class="section-title">
            <h1>{{ $t('contracts') }}</h1>
        </div>
        <div class="contracts-content">
            <div class="table-parent scroll-grey">
                <el-table border :empty-text="$t('no_contracts')" :data="contracts">
                    <el-table-column :label="$t('contracts')">
                        <template #default="scope">
                            <el-link
                                target="_blank" :href="scope.row.fullPath">{{ scope.row.name }}
                            </el-link>
                        </template>
                    </el-table-column>
                    <el-table-column :label="$t('expires_at')">
                        <template #default="scope">
                            <span>{{ $Date(scope.row.expired_at, 'YYYY-MM-DD') }}</span>
                        </template>
                    </el-table-column>
                    <el-table-column :label="$t('status')">
                        <template #default="scope">
                            <el-tag :type="scope.row.status === 'active' ? 'success' : 'danger'">{{ $t(scope.row.status) }}</el-tag>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
        </div>
    </section>
</template>

<script>
    import helper from "../../../mixins/helper";
    import {$api} from "../../../api/api";
    import {api_url} from "../../../constants/api";

    export default {
        mixins: [helper],
        beforeMount() {
            this.getContracts();
        },
        data() {
            return {
                contracts: []
            }
        },
        methods: {
            getContracts() {
                this.$store.commit('setPreloader', true);
                $api().get(api_url + 'contract').then(({data}) => {
                    this.contracts = data.contracts;
                    this.$store.commit('setPreloader', false);
                }).catch(() => {
                    this.$store.commit('setPreloader', false);
                });
            }
        }
    }
</script>
