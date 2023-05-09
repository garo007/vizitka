<template>
    <section class="tags-section default-section">
        <div class="section-title">
            <h1>{{ $t('Tag') }}</h1>
        </div>
        <div class="tags-content">
            <el-form :model="form" :rules="rules" ref="form" label-width="120px" class="demo-form">
                <el-form-item label="Name" prop="name">
                    <el-input v-model="form.name"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="submitForm('form')">Create</el-button>
                </el-form-item>
            </el-form>
        </div>
    </section>
</template>

<script>
import {$api} from "../../../api/api";
import {api_url} from "../../../constants/api";
import {Message} from "@element-plus/icons-vue";
import objectToFormData from "../../../mixins/objectToFormData";

export default {
    data() {
        return {
            form: {
                name: '',
            },
            rules: {
                name: [
                    { required: true, message: 'Please input name', trigger: 'blur' },
                    { min: 2, message: 'Length should be 3', trigger: 'blur' }
                ]
            }
        };
    },
    methods: {
        submitForm(formName) {
            this.$refs[formName].validate((valid) => {
                if (valid) {
                    this.$store.commit('setPreloader', true);
                    $api().post(api_url + 'tags/',this.form).then(({data}) => {
                        if (data.success) {
                            this.data = {
                                name: null,
                                icon: null,
                            };
                            document.getElementById('icon').value = null;
                            Message({
                                message: data.message,
                                showClose: true,
                                type: 'success'
                            });
                        } else {
                            Message({
                                message: Object.values(data.errors)[0],
                                showClose: true,
                                type: 'warning'
                            });
                        }
                        this.$store.commit('setPreloader', false);
                    }).catch(() => {
                        this.$store.commit('setPreloader', false);
                    });
                } else {
                    console.log('error submit!!');
                    return false;
                }
            });
        }
    }
}
</script>

<style scoped>

</style>
