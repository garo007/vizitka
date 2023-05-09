<template>
    <div class="header">
        <router-link tag="div" :to="{name: 'clients'}" class="header-logo">
            <img src="../images/logo.png" alt="">
        </router-link>
        <div class="header-actions">
            <div class="notification-action">
                <i class="far fa-bell"></i>
                <span>0</span>
            </div>
            <div @click="togglePopup('profile')" :class="`profile-action ${activities.profile ? 'active' : ''}`">
                <span>{{ name }}</span>
                <i class="fas fa-sort-down"></i>
            </div>
            <div @click="toggleSidebar()" :class="`burger-action ${sidebar ? 'active' : ''}`">
                <div></div>
            </div>
        </div>
        <div class="header-popups">
            <profile :show="popups.profile"></profile>
        </div>
    </div>
</template>

<script>
    import helper from "../mixins/helper";
    import Profile from "./Profile.vue";

    export default {
        mixins: [helper],
        components: {Profile},
        methods: {
            togglePopup(name){
                this.$store.commit('setPopups', {
                    profile: name === 'profile' ? !this.popups.profile: false
                });
            },
            toggleSidebar() {
                this.$store.commit('setSidebar', !this.sidebar);
            }
        },
        computed: {
            name: function () {
                if (this.$Device() === 'web'){
                    return this.$Admin()['name'][0] + '. ' + this.$Admin()['surname'];
                }
                return this.$Admin()['name'][0] + '. ' + this.$Admin()['surname'][0] + '.';
            },
            popups: function() {
                return this.$store.getters.getPopups;
            },
            sidebar: function () {
                return this.$store.getters.getSidebar;
            },
            activities: function () {
                return {
                    profile: this.popups.profile
                };
            }
        }
    }
</script>
