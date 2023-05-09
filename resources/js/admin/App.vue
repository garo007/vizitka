<template>
    <main @click="onBody($event)" :class="`main ${classes}`">
        <Header v-if="header"></Header>

        <Sidebar v-if="sidebar"></Sidebar>

        <Preloader></Preloader>

        <router-view></router-view>
    </main>
</template>

<script>
    import $ from 'jquery';
    import Header from "./components/Header.vue";
    import Sidebar from "./components/Sidebar.vue";
    import Preloader from "./components/Preloader.vue";

    export default {
        components: {
            Header,
            Sidebar,
            Preloader
        },
        methods: {
            onBody(event){
                if ($(event.target).parents('.header-popups').length === 0 && $(event.target).parents('.header-actions').length === 0){
                    this.$store.commit('setPopups', {
                        profile: false
                    });
                }
            }
        },
        computed: {
            isAuth: function () {
                return this.$store.getters.getAdmin !== null;
            },
            header: function () {
                return this.isAuth && ['not-found'].indexOf(this.$route.name) === -1;
            },
            sidebar: function () {
                return this.isAuth && ['not-found'].indexOf(this.$route.name) === -1;
            },
            classes: function () {
                let device = this.$store.getters.getDevice;
                let content = ['login', 'not-found'].indexOf(this.$route.name) === -1 ? 'duplex' : 'full';
                let sidebar = this.$store.getters.getSidebar ? 'yes' : 'no';

                return device + ' ' + content + '-' + sidebar;
            }
        }
    }
</script>
