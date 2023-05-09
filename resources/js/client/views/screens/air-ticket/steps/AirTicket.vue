<template>
    <div class="form-field">
        <div class="field-label">{{ $t('cabin_class_name') }}</div>
        <el-select
            v-model="data.cabin_class"
            size="large"
            :reserve-keyword="false"
            :placeholder="$t('choose_cabin_class')">
            <el-option class="select-option" :label="$t('cabin_class.first')" value="first"></el-option>
            <el-option class="select-option" :label="$t('cabin_class.business')" value="business"></el-option>
            <el-option class="select-option" :label="$t('cabin_class.premium_economy')" value="premium_economy"></el-option>
            <el-option class="select-option" :label="$t('cabin_class.economy')" value="economy"></el-option>
        </el-select>
    </div>
    <div class="form-field">
        <div>
            <el-checkbox v-model="data.live_mode" :label="$t('live_mode')" size="large" />
        </div>
    </div>
</template>

<script>
export default {
    name: "AirTicket",
    inject: ['data'],
    data() {
        return {
            data: this.data
        }
    }
}
</script>
