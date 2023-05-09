<template>
    <div class="sidebar scroll-grey">
        <div class="sidebar-items">
            <div :class="`sidebar-group sidebar-group-profile ${$route.name === 'profile' ? 'is-active opened' : ''}`">
                <div class="sidebar-group-item">
                    <div class="sidebar-group-item-left">
                        <div class="sidebar-group-item-left-images">
                            <img class="dark-img" src="../images/sidebar/dark/agreements.svg" alt="">
                            <img class="light-img" src="../images/sidebar/light/agreements.svg" alt="">
                        </div>
                        <div class="sidebar-group-item-left-name">{{ $t('profile') }}</div>
                    </div>
                    <div class="sidebar-group-item-right">
                        <div class="sidebar-group-item-right-action">
                            <i class="el-icon-arrow-right"></i>
                        </div>
                    </div>
                </div>
                <div class="sidebar-group-dropdown">
                    <router-link
                        :to="{name: 'profile', params: {prefix: 'details'}}"
                        :class="`sidebar-group-dropdown-item ${$route.params.prefix === 'details' ? 'current' : ''}`"
                        tag="div"
                    >
                        <hr>
                        <span>{{ $t('details') }}</span>
                    </router-link>
                    <router-link
                        :to="{name: 'profile', params: {prefix: 'change-password'}}"
                        :class="`sidebar-group-dropdown-item ${$route.params.prefix === 'change-password' ? 'current' : ''}`"
                        tag="div"
                    >
                        <hr>
                        <span>{{ $t('change_password') }}</span>
                    </router-link>
                </div>
            </div>
            <div :class="`sidebar-group sidebar-group-agreements ${$route.name === 'agreements' ? 'is-active opened' : ''}`">
                <div class="sidebar-group-item">
                    <div class="sidebar-group-item-left">
                        <div class="sidebar-group-item-left-images">
                            <img class="dark-img" src="../images/sidebar/dark/agreements.svg" alt="">
                            <img class="light-img" src="../images/sidebar/light/agreements.svg" alt="">
                        </div>
                        <div class="sidebar-group-item-left-name">{{ $t('agreements') }}</div>
                    </div>
                    <div class="sidebar-group-item-right">
                        <div class="sidebar-group-item-right-action">
                            <i class="el-icon-arrow-right"></i>
                        </div>
                    </div>
                </div>
                <div class="sidebar-group-dropdown">
                    <router-link
                        :to="{name: 'agreements', params: {prefix: 'contracts'}}"
                        :class="`sidebar-group-dropdown-item ${$route.params.prefix === 'contracts' ? 'current' : ''}`"
                        tag="div"
                    >
                        <hr>
                        <span>{{ $t('contracts') }}</span>
                    </router-link>
                    <router-link
                        :to="{name: 'agreements', params: {prefix: 'documents'}}"
                        :class="`sidebar-group-dropdown-item ${$route.params.prefix === 'documents' ? 'current' : ''}`"
                        tag="div"
                    >
                        <hr>
                        <span>{{ $t('documents') }}</span>
                    </router-link>
                </div>
            </div>
            <router-link :to="{name: 'air-ticket'}" class="sidebar-item" active-class="active" tag="div">
                <div class="sidebar-item-images">
                    <img class="dark-img" src="../images/sidebar/dark/agreements.svg" alt="">
                    <img class="light-img" src="../images/sidebar/light/agreements.svg" alt="">
                </div>
                <div class="sidebar-item-name">{{ $t('air_tickets') }}</div>
            </router-link>
        </div>
    </div>
</template>

<script>
    import $ from 'jquery';

    export default {
        beforeMount() {
            $(function () {
                $('.sidebar-group-item').click(function () {
                    $(this).parents('.sidebar-group').toggleClass('opened');
                    $(this).next().slideToggle();
                });
            });
        },
        methods: {
            openDropdown(){
                $(function () {
                    $('.sidebar-group.is-active .sidebar-group-dropdown').slideDown();
                });
            },
            closeDropDown(name){
                let sidebar_group = $('.sidebar-group-' + name);
                sidebar_group.removeClass('is-active');
                sidebar_group.removeClass('opened');
                sidebar_group.find('.sidebar-group-dropdown').slideUp();
                sidebar_group.find('.sidebar-group-dropdown-item').removeClass('current');
            }
        },
        watch: {
            $route(route){
                this.openDropdown();
                if (route.name !== 'agreements'){
                    this.closeDropDown('agreements');
                }
                if (route.name !== 'profile'){
                    this.closeDropDown('profile');
                }
            }
        }
    }
</script>
