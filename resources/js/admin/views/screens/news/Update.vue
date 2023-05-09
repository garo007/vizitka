<template>
    <div class="default-section">
        <el-form :model="form" label-width="120px">
            <el-form-item label="Title">
                <el-input v-model="form.title" />
            </el-form-item>
            <el-form-item label="Image">
                <input @change="handleImage($event)" type="file" />
            </el-form-item>
            <el-form-item label="Description">
                <TinyEditor v-if="form.description" :val="form.description"/>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="onSubmit">Create</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
import UploaderImages from "../../../components/UploaderImages.vue";
import {Message} from "@element-plus/icons-vue";
import {api_url} from "../../../constants/api";
import {$api} from "../../../api/api";
import TinyEditor from "../../../components/TinyEditor.vue";

export default {
    name: "Store",
    components: {TinyEditor, UploaderImages, Message},
    beforeMount() {
        this.getNews();
    },
    data() {
      return {
          form: {
              title: '',
              image: null
          },
          news: null
      }
    },
    methods: {
        getNews() {
            this.$store.commit('setPreloader', true);
            $api().get(api_url + 'news/' + this.$route.params.prefix).then(({data}) => {
                this.form = data.news;
                this.$store.commit('setPreloader', false);
            }).catch(() => {
                this.$store.commit('setPreloader', false);
            });
        },
        handleImage(event) {
            this.form.image = event.target.files[0];
        },
        onSubmit() {
            let formData = new FormData();
            this.form.description = tinymce.activeEditor.getContent()
            Object.keys(this.form).map(property => {
                if (this.form[property]){
                    formData.append(property, this.form[property]);
                }
            });
            formData.append('_method', 'put')
            this.$store.commit('setPreloader', true);
            $api().post(api_url + 'news/' + this.$route.params.prefix, formData).then(({data}) => {
                if (data.success) {
                    this.data = {
                        title: '',
                        description: '',
                        image: null,
                    };
                    document.getElementById('image').value = null;
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
        },
    },
    watch: {
        form: {
            deep: true,
            immediate: true,
            handler: function (data) {

            }
        }
    }
}
</script>

<style scoped>

</style>
