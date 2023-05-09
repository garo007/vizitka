<template>
    <section class="change-password-section default-section">
        <div class="section-title">
            <h1>{{ $t('change_password') }}</h1>
        </div>
        <div class="change-password-form">
            <div :class="`form-field ${$HasError(errors, 'current_password') ? 'field-invalid' : ''}`">
                <div class="field-label">{{ $t('current_password') }}</div>
                <el-input
                    v-model="form.current_password"
                    size="large"
                    show-password
                    name="password"
                    class="with-append not-fill"
                >
                    <template slot="append">
                        <el-icon><El-Icon-View></El-Icon-View></el-icon>
                    </template>
                </el-input>
                <div v-if="$HasError(errors, 'current_password')" class="field-error">
                    <span>{{ $GetError(errors, 'current_password') }}</span>
                </div>
            </div>
            <div :class="`form-field ${$HasError(errors, 'password') ? 'field-invalid' : ''}`">
                <div class="field-label">{{ $t('password') }}</div>
                <el-input
                    v-model="form.password"
                    size="large"
                    show-password
                    name="password"
                    class="with-append"
                >
                    <template slot="append">
                        <el-icon><El-Icon-View></El-Icon-View></el-icon>
                    </template>
                </el-input>
                <div v-if="$HasError(errors, 'password')" class="field-error">
                    <span>{{ $GetError(errors, 'password') }}</span>
                </div>
            </div>
            <div :class="`form-field ${$HasError(errors, 'confirm_password') ? 'field-invalid' : ''}`">
                <div class="field-label">{{ $t('confirm_password') }}</div>
                <el-input
                    v-model="form.confirm_password"
                    size="large"
                    show-password
                    name="password"
                    class="with-append"
                >
                    <template slot="append">
                        <el-icon><El-Icon-View></El-Icon-View></el-icon>
                    </template>
                </el-input>
                <div v-if="$HasError(errors, 'confirm_password')" class="field-error">
                    <span>{{ $GetError(errors, 'confirm_password') }}</span>
                </div>
            </div>
        </div>
        <div class="change-password-action">
            <el-button @click="change()" size="large" type="success" round>{{ $t('change') }}</el-button>
        </div>
    </section>
</template>

<script>
    import helper from "../../../mixins/helper";
    import {$api} from "../../../api/api";
    import {api_url} from "../../../constants/api";
    import {ElMessage} from 'element-plus';

    export default {
        mixins: [helper],
        data() {
            return {
                form: {
                    current_password: null,
                    password: null,
                    confirm_password: null
                },
                errors: {}
            }
        },
        methods: {
            change() {
                this.$store.commit('setPreloader', true);
                $api().post(api_url + 'change-password', this.form).then(({data}) => {
                    if (data.success) {
                        this.errors = {};

                        this.form = {
                            current_password: null,
                            password: null,
                            confirm_password: null
                        };

                        ElMessage({
                            message: data.message,
                            showClose: true,
                            type: 'success'
                        });
                    } else {
                        this.errors = data.errors;
                    }
                    this.$store.commit('setPreloader', false);
                }).catch(() => {
                    this.$store.commit('setPreloader', false);
                });
            }
        }
    }
</script>
