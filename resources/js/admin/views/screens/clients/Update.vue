<template>
    <div class="default-section">
        <el-form :model="form" label-width="120px">
            <el-form-item label="Rubric">
                <el-select v-model="form.tag_ids" filterable multiple>
                    <el-option
                        v-for="tag in tags"
                        :key="tag.id"
                        :label="tag.name"
                        :value="tag.id"
                    />
                </el-select>
            </el-form-item>
            <el-form-item label="Rubric">
                <el-select v-model="form.rubric_id" filterable>
                    <el-option
                        v-for="rubric in rubrics"
                        :key="rubric.id"
                        :label="rubric.name"
                        :value="rubric.id"
                    />
                </el-select>
            </el-form-item>
            <el-form-item label="Name">
                <el-input v-model="form.name" />
            </el-form-item>
            <el-form-item label="Description">
                <el-input v-model="form.description" type="textarea" />
            </el-form-item>
            <el-form-item label="Logo">
                <input type="file" id="logo" @change="changeFile($event)">
            </el-form-item>
            <el-form-item label="Images">
                <div v-for="image in form.images">
                    <img :src="image" width="100" alt=""/>
                </div>
                <UploaderImages @images="handleImages"/>
            </el-form-item>
            <el-form-item label="Phone">
                <el-input v-model="form.phone" />
            </el-form-item>
            <el-form-item label="Address">
                <el-input v-model="form.address" />
            </el-form-item>
            <el-form-item label="Latitude">
                <el-input v-model="form.latitude" />
            </el-form-item>
            <el-form-item label="Longitude">
                <el-input v-model="form.longitude" />
            </el-form-item>

            <el-form-item>
                <el-button type="primary" @click="onSubmit">Update</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
import UploaderImages from "../../../components/UploaderImages.vue";
import {Message} from "@element-plus/icons-vue";
import {api_url} from "../../../constants/api";
import {$api} from "../../../api/api";

export default {
    name: "Store",
    components: {UploaderImages, Message},
    beforeMount() {
        this.getClient();
        this.getRubrics();
        this.getTags();
    },
    data() {
      return {
          form: {
              rubric_id: 8,
              tag_ids: [],
              name: '',
              description: '',
              logo: null,
              phone: null,
              address: null,
              latitude: null,
              longitude: null,
          },
          images: [],
          rubrics: null,
          tags: null,
      }
    },
    methods: {
        getTags() {
            this.$store.commit('setPreloader', true);
            $api().get(api_url + 'tags').then(({data}) => {
                this.tags = data.tags;
                this.$store.commit('setPreloader', false);
            }).catch(() => {
                this.$store.commit('setPreloader', false);
            });
        },
        getRubrics() {
            this.$store.commit('setPreloader', true);
            $api().get(api_url + 'rubrics').then(({data}) => {
                this.rubrics = data.rubrics;
                this.$store.commit('setPreloader', false);
            }).catch(() => {
                this.$store.commit('setPreloader', false);
            });
        },
        getClient() {
            this.$store.commit('setPreloader', true);
            $api().get(api_url + 'client/' + this.$route.params.prefix).then(({data}) => {
                this.form = data.client;
                this.$store.commit('setPreloader', false);
            }).catch(() => {
                this.$store.commit('setPreloader', false);
            });
        },
        onSubmit() {
            let formData = new FormData();

            Object.keys(this.form).map(property => {
                if (this.form[property]){
                    formData.append(property, this.form[property]);
                }
            });

            Object.keys(this.images).map(i => {
                if (this.images.length > 0) {
                    formData.append('images[]', this.images[i]);
                }
            });
            this.$store.commit('setPreloader', true);
            formData.append('_method', 'put')
            $api().post(api_url + 'client/' + this.$route.params.prefix, formData).then(({data}) => {
                if (data.success) {
                    this.data = {
                        name: '',
                        description: '',
                        logo: null,
                        phone: null,
                        address: null,
                        images: [],
                    };
                    document.getElementById('logo').value = null;
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
        handleImages(images) {
            this.images = images;
        },
        changeFile(event) {
            this.form.logo = event.target.files[0];
        },
    }
}
</script>

<style scoped>

</style>
