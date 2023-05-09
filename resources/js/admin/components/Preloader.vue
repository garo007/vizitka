<template>
    <div v-if="display" class="preloader">
        <img src="../images/preloader.gif" alt="">
    </div>
</template>

<script>
    export default {
        computed: {
            display: function () {
                return this.$store.getters.getPreloader;
            }
        }
    }
</script>
