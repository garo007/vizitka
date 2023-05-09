<template>
    <div v-if="show" class="profile-popup scroll-grey">
        <div @click="logout()">
            <i class="fa fa-sign-out-alt"></i>
            <span>{{ $t('logout') }}</span>
        </div>
    </div>
</template>

<script>
    import {$api} from "../api/api";
    import {api_url} from "../constants/api";

    export default {
        props: {
            show: {
                type: Boolean
            }
        },
        methods: {
            reset() {
                this.$store.commit("setToken", null);
                this.$store.commit("setExpiredAt", null);
                this.$store.commit("setIsLogged", false);
                this.$store.commit("setClient", null);
                this.$router.push({name: "login"});
            },
            logout() {
                this.$store.commit('setPreloader', true);
                $api().post(api_url + "logout").then(() => {
                    this.reset();
                    this.$store.commit('setPreloader', false);
                }).catch(() => {
                    this.reset();
                    this.$store.commit('setPreloader', false);
                });
            }
        }
    }
</script>
