<template>
    <section class="auth-section">
        <h3 class="auth-title">{{ $t('reset_password') }}</h3>
        <div class="auth-form">
            <div :class="`form-field ${$HasError(errors, 'password') ? 'field-invalid' : ''}`">
                <div class="field-label">{{ $t('password') }}</div>
                <el-input
                    @keyup.enter.native="submit()"
                    size="large"
                    v-model="data.password"
                    show-password
                    name="password">
                </el-input>
                <div v-if="$HasError(errors, 'password')" class="field-error">
                    <span>{{ $GetError(errors, 'password') }}</span>
                </div>
            </div>
            <div :class="`form-field ${$HasError(errors, 'confirm_password') ? 'field-invalid' : ''}`">
                <div class="field-label">{{ $t('confirm_password') }}</div>
                <el-input
                    @keyup.enter.native="submit()"
                    size="large"
                    v-model="data.confirm_password"
                    show-password
                    name="confirm_password">
                </el-input>
                <div v-if="$HasError(errors, 'confirm_password')" class="field-error">
                    <span>{{ $GetError(errors, 'confirm_password') }}</span>
                </div>
            </div>
        </div>
        <el-button @click="submit()">{{ $t('submit') }}</el-button>
    </section>
</template>

<script>
    import {$api} from "../../api/api";
    import helper from "../../mixins/helper";
    import {api_url} from "../../constants/api";

    export default {
        mixins: [helper],
        data(){
            return {
                data: {
                  password: null,
                  confirm_password: null
                },
                errors: {}
            }
        },
        methods: {
            submit(){
                this.$store.commit('setPreloader', true);
                let data = {
                    ...this.data,
                    token: this.$route.query.token
                };
                $api().post(api_url + 'reset-password', data).then(({data}) => {
                    if (data.success) {
                        this.errors = {};
                        this.$store.commit("setToken", data.token);
                        this.$store.commit("setExpiredAt", data.expired_at);
                        this.$store.commit("setIsLogged", true);
                        this.$store.commit("setClient", data.client);
                        this.$router.push({name: 'profile', params: {prefix: 'details'}});
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
