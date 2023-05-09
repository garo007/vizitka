<template>
    <section class="auth-section">
        <h3 class="auth-title">{{ $t('login') }}</h3>
        <div class="auth-form">
            <div class="form-field">
                <div class="field-label">{{ $t('email') }}</div>
                <el-input @keyup.enter.native="submit()" size="large" v-model="credentials.email" name="username"></el-input>
            </div>
            <div class="form-field">
                <div class="field-label">{{ $t('password') }}</div>
                <el-input
                        v-model="credentials.password"
                        @keyup.enter.native="submit()"
                        size="large"
                        show-password
                        name="password"
                        class="with-append"
                >
                    <template slot="append">
                        <el-icon><El-Icon-View></El-Icon-View></el-icon>
                    </template>
                </el-input>
            </div>
            <router-link to="forgot-password" class="auth-link">{{ $t('forgot_password') }}</router-link>
        </div>
        <el-button @click="submit()">{{ $t('submit') }}</el-button>
    </section>
</template>

<script>
    import {$api} from "../../api/api";
    import {api_url} from "../../constants/api";
    import {ElMessage} from 'element-plus';

    export default {
        data(){
            return {
                credentials: {
                    email: null,
                    password: null
                }
            }
        },
        methods: {
            submit(){
                this.$store.commit('setPreloader', true);
                $api().post(api_url + 'login', this.credentials).then(({data}) => {
                    if (data.success) {
                        this.$store.commit("setToken", data.token);
                        this.$store.commit("setExpiredAt", data.expired_at);
                        this.$store.commit("setIsLogged", true);
                        this.$store.commit("setClient", data.client);
                        this.$router.push({name: 'profile', params: {prefix: 'details'}});
                    } else {
                        ElMessage({
                            message: data.message,
                            showClose: true,
                            type: 'warning'
                        });
                    }
                    this.$store.commit('setPreloader', false);
                }).catch(() => {
                    this.$store.commit('setPreloader', false);
                });
            }
        }
    }
</script>
