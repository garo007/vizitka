<template>
    <section class="rubrics-section default-section">
        <div class="section-title">
            <h1>{{ $t('rubric') }}</h1>
        </div>
        <div class="rubrics-content">
            <el-form :model="form" :rules="rules" ref="form" label-width="120px" class="demo-form">
                <el-form-item label="Name" prop="name">
                    <el-input v-model="form.name"></el-input>
                </el-form-item>
                <el-form-item label="icon" prop="icon">
                    <input @change="handleIcon($event)" type="file" />
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="submitForm('form')">Update</el-button>
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
    beforeMount() {
      this.getRubric()
    },
    data() {
        return {
            form: {
                name: '',
                icon: '',
            },
            rules: {
                name: [
                    { required: true, message: 'Please input Activity name', trigger: 'blur' },
                    { min: 3, message: 'Length should be 3', trigger: 'blur' }
                ],
                icon: [
                    { required: true, message: 'Please select Activity zone', trigger: 'change' }
                ]
            }
        };
    },
    methods: {
        getRubric() {
            this.$store.commit('setPreloader', true);
            $api().get(api_url + 'rubrics/' + this.$route.params.prefix).then(({data}) => {
                this.form.name = data.rubric.name;
                this.$store.commit('setPreloader', false);
            }).catch(() => {
                this.$store.commit('setPreloader', false);
            });
        },
        submitForm(formName) {
            this.$refs[formName].validate((valid) => {
                if (valid) {
                    this.$store.commit('setPreloader', true);
                    this.form._method = 'put';

                    $api().post(api_url + 'rubrics/' + this.$route.params.prefix, objectToFormData(this.form)).then(({data}) => {
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
        },
        handleIcon(event) {
            this.form.icon = event.target.files[0];
        }
    }
}
</script>

<style scoped>

</style>
