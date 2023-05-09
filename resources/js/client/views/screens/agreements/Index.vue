<template>
    <section class="agreements-section default-section">
        <Documents v-if="screen === 'documents'"></Documents>
        <Contracts v-if="screen === 'contracts'"></Contracts>
    </section>
</template>

<script>
    import Contracts from "./Contracts.vue";
    import Documents from "./Documents.vue";

    export default {
        components: {
            Contracts,
            Documents
        },
        beforeMount() {
            this.check();
        },
        methods: {
            check(){
                if (!this.screens.includes(this.screen)){
                    this.$router.push({name: 'agreements', params: {prefix: 'documents'}});
                }
            }
        },
        computed: {
            screen: function () {
                return this.$route.params.prefix;
            },
            screens: function () {
                let screens = {
                    contracts: true,
                    documents: true
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
                if (this.$route.name === 'agreements'){
                    this.check();
                }
            }
        }
    }
</script>
