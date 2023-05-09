<template>
    <section class="details-section default-section">
        <div class="section-title">
            <h1>{{ $t('details') }}</h1>
        </div>
        <div class="details-content">
            <div class="details-form">
                <div :class="`form-field ${$HasError(errors, 'company_name') ? 'field-invalid' : ''}`">
                    <div class="field-label">{{ $t('company_name') }}</div>
                    <el-input size="large" v-model="model.company_name"></el-input>
                    <div v-if="$HasError(errors, 'company_name')" class="field-error">
                        <span>{{ $GetError(errors, 'company_name') }}</span>
                    </div>
                </div>
                <div :class="`form-field ${$HasError(errors, 'description') ? 'field-invalid' : ''}`">
                    <div class="field-label">{{ $t('description') }}</div>
                    <el-input size="large" type="textarea" rows="4" v-model="model.description"></el-input>
                    <div v-if="$HasError(errors, 'description')" class="field-error">
                        <span>{{ $GetError(errors, 'description') }}</span>
                    </div>
                </div>
                <div :class="`form-field ${$HasError(errors, 'email') ? 'field-invalid' : ''}`">
                    <div class="field-label">{{ $t('email') }}</div>
                    <el-input size="large" v-model="model.email"></el-input>
                    <div v-if="$HasError(errors, 'email')" class="field-error">
                        <span>{{ $GetError(errors, 'email') }}</span>
                    </div>
                </div>
                <div :class="`form-field ${$HasError(errors, 'business_address') ? 'field-invalid' : ''}`">
                    <div class="field-label">{{ $t('business_address') }}</div>
                    <el-input size="large" v-model="model.business_address"></el-input>
                    <div v-if="$HasError(errors, 'business_address')" class="field-error">
                        <span>{{ $GetError(errors, 'business_address') }}</span>
                    </div>
                </div>
                <div :class="`form-field ${$HasError(errors, 'phone_number') ? 'field-invalid' : ''}`">
                    <div class="field-label">{{ $t('phone_number') }}</div>
                    <el-input size="large" v-model="model.phone_number"></el-input>
                    <div v-if="$HasError(errors, 'phone_number')" class="field-error">
                        <span>{{ $GetError(errors, 'phone_number') }}</span>
                    </div>
                </div>
                <div :class="`form-field ${$HasError(errors, 'tin') ? 'field-invalid' : ''}`">
                    <div class="field-label">{{ $t('tin') }}</div>
                    <el-input size="large" v-model="model.tin"></el-input>
                    <div v-if="$HasError(errors, 'tin')" class="field-error">
                        <span>{{ $GetError(errors, 'tin') }}</span>
                    </div>
                </div>
                <div :class="`form-field ${$HasError(errors, 'bank_account') ? 'field-invalid' : ''}`">
                    <div class="field-label">{{ $t('bank_account') }}</div>
                    <el-input size="large" v-model="model.bank_account"></el-input>
                    <div v-if="$HasError(errors, 'bank_account')" class="field-error">
                        <span>{{ $GetError(errors, 'bank_account') }}</span>
                    </div>
                </div>
                <div :class="`form-field ${$HasError(errors, 'bank_name') ? 'field-invalid' : ''}`">
                    <div class="field-label">{{ $t('bank_name') }}</div>
                    <el-input size="large" v-model="model.bank_name"></el-input>
                    <div v-if="$HasError(errors, 'bank_name')" class="field-error">
                        <span>{{ $GetError(errors, 'bank_name') }}</span>
                    </div>
                </div>
                <div :class="`form-field ${$HasError(errors, 'contact_person') ? 'field-invalid' : ''}`">
                    <div class="field-label">{{ $t('contact_person') }}</div>
                    <el-input size="large" v-model="model.contact_person"></el-input>
                    <div v-if="$HasError(errors, 'contact_person')" class="field-error">
                        <span>{{ $GetError(errors, 'contact_person') }}</span>
                    </div>
                </div>
                <div :class="`form-field ${$HasError(errors, 'position') ? 'field-invalid' : ''}`">
                    <div class="field-label">{{ $t('position') }}</div>
                    <el-input size="large" v-model="model.position"></el-input>
                    <div v-if="$HasError(errors, 'position')" class="field-error">
                        <span>{{ $GetError(errors, 'position') }}</span>
                    </div>
                </div>
                <div class="form-field">
                    <div class="field-label">{{ $t('state_certificate_of_company') }}</div>
                    <div class="form-file">
                        <div class="file-input">
                            <el-button :type="getFile('state_certificate_of_company').type" size="large">{{ getFile('state_certificate_of_company').label }}</el-button>
                            <input id="state_certificate_of_company" type="file" @change="changeFile($event, 'state_certificate_of_company')"/>
                        </div>
                        <div class="file-action" v-if="hasFile('state_certificate_of_company')">
                            <el-button @click="resetFile('state_certificate_of_company')" type="danger" icon="El-Icon-Close" circle></el-button>
                        </div>
                        <el-link
                            v-if="$Client()['state_certificate_of_company']"
                            :href="$Client()['state_certificate_of_company']"
                            :underline="false"
                            target="_blank">
                            <el-button type="primary" icon="El-Icon-View" circle></el-button>
                        </el-link>
                    </div>
                    <div v-if="$HasError(errors, 'state_certificate_of_company')" class="field-error">
                        <span>{{ $GetError(errors, 'state_certificate_of_company') }}</span>
                    </div>
                </div>
                <div class="form-field">
                    <div class="field-label">{{ $t('license') }}</div>
                    <div class="form-file">
                        <div class="file-input">
                            <el-button :type="getFile('license').type" size="large">{{ getFile('license').label }}</el-button>
                            <input id="license" type="file" @change="changeFile($event, 'license')"/>
                        </div>
                        <div class="file-action" v-if="hasFile('license')">
                            <el-button @click="resetFile('license')" type="danger" icon="El-Icon-Close" circle></el-button>
                        </div>
                        <el-link
                            v-if="$Client()['license']"
                            :href="$Client()['license']"
                            :underline="false"
                            target="_blank">
                            <el-button type="primary" icon="El-Icon-View" circle></el-button>
                        </el-link>
                    </div>
                    <div v-if="$HasError(errors, 'license')" class="field-error">
                        <span>{{ $GetError(errors, 'license') }}</span>
                    </div>
                </div>
                <div class="form-field">
                    <div class="field-label">{{ $t('logo') }}</div>
                    <div class="form-file">
                        <div class="file-input">
                            <el-button :type="getFile('logo').type" size="large">{{ getFile('logo').label }}</el-button>
                            <input id="logo" type="file" @change="changeFile($event, 'logo')"/>
                        </div>
                        <div class="file-action" v-if="hasFile('logo')">
                            <el-button @click="resetFile('logo')" type="danger" icon="El-Icon-Close" circle></el-button>
                        </div>
                        <el-link
                            v-if="$Client()['logo']"
                            :href="$Client()['logo']"
                            :underline="false"
                            target="_blank">
                            <el-button type="primary" icon="El-Icon-View" circle></el-button>
                        </el-link>
                    </div>
                    <div v-if="$HasError(errors, 'logo')" class="field-error">
                        <span>{{ $GetError(errors, 'logo') }}</span>
                    </div>
                </div>
                <div class="form-field">
                    <div class="field-label">{{ $t('privacy_policy') }}</div>
                    <div class="form-file">
                        <div class="file-input">
                            <el-button :type="getFile('privacy_policy').type" size="large">{{ getFile('privacy_policy').label }}</el-button>
                            <input id="privacy_policy" type="file" @change="changeFile($event, 'privacy_policy')"/>
                        </div>
                        <div class="file-action" v-if="hasFile('privacy_policy')">
                            <el-button @click="resetFile('privacy_policy')" type="danger" icon="El-Icon-Close" circle></el-button>
                        </div>
                        <el-link
                            v-if="$Client()['privacy_policy']"
                            :href="$Client()['privacy_policy']"
                            :underline="false"
                            target="_blank">
                            <el-button type="primary" icon="El-Icon-View" circle></el-button>
                        </el-link>
                    </div>
                    <div v-if="$HasError(errors, 'privacy_policy')" class="field-error">
                        <span>{{ $GetError(errors, 'privacy_policy') }}</span>
                    </div>
                </div>
            </div>
            <div class="details-actions">
                <el-button @click="save()" type="primary" round>{{ $t('save') }}</el-button>
                <el-button @click="send()" type="success" round>{{ $t('send') }}</el-button>
            </div>
        </div>
    </section>
</template>

<script>
    import helper from "../../../mixins/helper";
    import {$api} from "../../../api/api";
    import {api_url} from "../../../constants/api";
    import objectToFormData from "../../../mixins/objectToFormData";
    import {ElMessage} from 'element-plus';

    export default {
        mixins: [helper],
        data() {
            return {
                model: {...this.$Client()},
                errors: {}
            }
        },
        methods: {
            hasFile(name){
                return typeof this.model[name] === 'object' && this.model[name] !== null;
            },
            getFile(name) {
                if (this.model[name]){
                    if (typeof this.model[name] === 'string'){
                        return {
                            type: 'success',
                            label: this.$t('change')
                        };
                    }
                    return {
                        type: 'primary',
                        label: this.model[name].name
                    };
                }
                return {
                    type: 'default',
                    label: this.$t('choose')
                };
            },
            resetFile(name){
                this.model[name] = this.$Client()[name];
                document.getElementById(name).value = null;
            },
            changeFile(e, name){
                this.model[name] = e.target.files[0];
            },
            save(){
                let model = {...this.model};

                if (!this.hasFile('state_certificate_of_company')){
                    delete model.state_certificate_of_company;
                }

                if (!this.hasFile('license')){
                    delete model.license;
                }

                if (!this.hasFile('logo')){
                    delete model.logo;
                }

                if (!this.hasFile('privacy_policy')){
                    delete model.privacy_policy;
                }

                this.$store.commit('setPreloader', true);
                $api().post(api_url + 'update', objectToFormData(model)).then(({data}) => {
                    if (data.success) {
                        this.errors = {};
                        this.model = {...data.client};
                        this.$store.commit('setClient', data.client);

                        this.resetFile('state_certificate_of_company');
                        this.resetFile('license');
                        this.resetFile('logo');
                        this.resetFile('privacy_policy');

                        ElMessage({
                            message: data.message,
                            showClose: true,
                            type: 'success'
                        });
                    } else {
                        this.errors = data.errors;
                    }
                    this.$store.commit('setPreloader', false);
                }).catch(() => {
                    this.$store.commit('setPreloader', false);
                });
            },
            send(){
                this.$store.commit('setPreloader', true);
                $api().post(api_url + 'send-request').then(({data}) => {
                    ElMessage({
                        message: data.message,
                        showClose: true,
                        type: data.success ? 'success' : 'warning'
                    });
                    this.$store.commit('setPreloader', false);
                }).catch(() => {
                    this.$store.commit('setPreloader', false);
                });
            }
        }
    }
</script>
