<template>
    <section class="auth-section">
        <h3 class="auth-title">{{ $t('forgot_password') }}</h3>
        <div class="auth-form">
            <div :class="`form-field ${$HasError(errors, 'email') ? 'field-invalid' : ''}`">
                <div class="field-label">{{ $t('email') }}</div>
                <el-input @keyup.enter.native="submit()" size="large" v-model="email" name="username"></el-input>
                <div v-if="$HasError(errors, 'email')" class="field-error">
                    <span>{{ $GetError(errors, 'email') }}</span>
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
    import {ElMessage} from 'element-plus';

    export default {
        mixins: [helper],
        data(){
            return {
                email: null,
                errors: {}
            }
        },
        methods: {
            submit(){
                this.$store.commit('setPreloader', true);
                $api().post(api_url + 'forgot-password', {email: this.email}).then(({data}) => {
                    if (data.success) {
                        this.errors = {};
                        ElMessage({
                            message: data.message,
                            showClose: true,
                            type: 'success'
                        });
                        this.$router.push({name: 'login'});
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
