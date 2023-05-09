<template>
    <section class="default-section">
        <Details v-if="screen === 'details'"></Details>
        <ChangePassword v-if="screen === 'change_password'"></ChangePassword>
    </section>
</template>

<script>
    import Details from "./Details.vue";
    import ChangePassword from "./ChangePassword.vue";

    export default {
        components: {
            Details,
            ChangePassword
        },
        beforeMount() {
            this.check();
        },
        methods: {
            check(){
                if (!this.screens.includes(this.screen)){
                    this.$router.push({name: 'profile', params: {prefix: 'details'}});
                }
            }
        },
        computed: {
            screen: function () {
                return this.$route.params.prefix.replace('-', '_');
            },
            screens: function () {
                let screens = {
                    details: true,
                    change_password: true
                };
                Object.keys(screens).map((screen) => {
                    if (!screens[screen]){
                        delete screens[screen];
                    }
                });
                return Object.keys(screens);
            }
        },
        watch: {
            $route(){
                if (this.$route.name === 'profile'){
                    this.check();
                }
            }
        }
    }
</script>
