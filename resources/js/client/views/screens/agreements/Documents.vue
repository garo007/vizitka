<template>
    <section class="documents-section default-section">
        <div class="section-title">
            <h1>{{ $t('documents') }}</h1>
        </div>
        <div class="documents-content">
            <el-icon>
                <El-Icon-Document @click="open()"></El-Icon-Document>
            </el-icon>
        </div>
    </section>
</template>

<script>
    export default {
        methods: {
            open(){
                window.open('https://www.africau.edu/images/default/sample.pdf', '_blank');
            }
        }
    }
</script>
