<template>
    <div class="sidebar scroll-grey">
        <div class="sidebar-items">
            <router-link :to="{name: 'clients'}" class="sidebar-item" active-class="active" tag="div">
                <div class="sidebar-item-images">
                    <img class="dark-img" src="../images/sidebar/dark/clients.svg" alt="">
                    <img class="light-img" src="../images/sidebar/light/clients.svg" alt="">
                </div>
                <div class="sidebar-item-name">{{ $t('clients') }}</div>
            </router-link>
            <router-link :to="{name: 'rubrics'}" class="sidebar-item" active-class="active" tag="div">
                <div class="sidebar-item-images">
                    <img class="dark-img" src="../images/sidebar/dark/clients.svg" alt="">
                    <img class="light-img" src="../images/sidebar/light/clients.svg" alt="">
                </div>
                <div class="sidebar-item-name">{{ $t('rubric') }}</div>
            </router-link>
            <router-link :to="{name: 'news'}" class="sidebar-item" active-class="active" tag="div">
                <div class="sidebar-item-images">
                    <img class="dark-img" src="../images/sidebar/dark/clients.svg" alt="">
                    <img class="light-img" src="../images/sidebar/light/clients.svg" alt="">
                </div>
                <div class="sidebar-item-name">{{ $t('news') }}</div>
            </router-link>
            <router-link :to="{name: 'tags'}" class="sidebar-item" active-class="active" tag="div">
                <div class="sidebar-item-images">
                    <img class="dark-img" src="../images/sidebar/dark/clients.svg" alt="">
                    <img class="light-img" src="../images/sidebar/light/clients.svg" alt="">
                </div>
                <div class="sidebar-item-name">{{ $t('tags') }}</div>
            </router-link>
        </div>
    </div>
</template>

<script>
    export default {}
</script>
