<template>
    <section class="notfound-section">
        <div class="notfound">
            <div class="notfound-404">
                <h1>404</h1>
            </div>
            <h2>{{ $t('not_found.title') }}</h2>
            <p>
                <span>{{ $t('not_found.description') }}</span>
                <a @click="back()">{{ $t('not_found.back') }}</a>
            </p>
        </div>
    </section>
</template>

<script>
    export default {
        methods: {
            back(){
                this.$router.back();
            }
        }
    }
</script>
