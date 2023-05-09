<template>
    <div class="popup roles-permissions-popup">
        <div class="popup-inner">
            <div class="popup-header">
                <h1>{{ $t('set_roles_and_permissions') }} | {{ model.company_name }}</h1>
                <img src="../../../images/close.png" @click="$emit('closePopup')" alt>
            </div>
            <div class="popup-body scroll-green">
                <div class="roles-permissions-form">
                    <div :class="`form-field ${$HasError(errors, 'roles') ? 'field-invalid' : ''}`">
                        <el-select
                            v-model="roles"
                            size="large"
                            multiple
                            filterable
                            allow-create
                            :reserve-keyword="false"
                            :placeholder="$t('choose_roles')">
                            <el-option class="select-option" :disabled="disabledFullAccess"
                                       :label="$t('roles.full_access')" value="full-access"></el-option>
                            <el-option class="select-option" :disabled="disabled" :label="$t('roles.buyer')"
                                       value="buyer"></el-option>
                            <el-option class="select-option" :disabled="disabled" :label="$t('roles.seller')"
                                       value="seller"></el-option>
                        </el-select>
                        <div v-if="$HasError(errors, 'roles')" class="field-error">
                            <span>{{ $GetError(errors, 'roles') }}</span>
                        </div>
                    </div>
                    <div :class="`form-field ${$HasError(errors, 'permissions') ? 'field-invalid' : ''}`"
                         v-if="showPermissions">
                        <el-select
                            v-model="permissions"
                            size="large"
                            multiple
                            filterable
                            allow-create
                            :reserve-keyword="false"
                            :placeholder="$t('choose_permissions')"
                        >
                            <el-option class="select-option" :disabled="disabledPermissionFullAccess" :label="$t('permissions.full_access')"
                                       value="full-access"></el-option>
                            <el-option class="select-option" :disabled="disabled" :label="$t('permissions.air_ticket')"
                                       value="air-ticket"></el-option>
                            <el-option class="select-option" :disabled="disabled" :label="$t('permissions.hotels')"
                                       value="hotels"></el-option>
                            <el-option class="select-option" :disabled="disabled" :label="$t('permissions.packages')"
                                       value="packages"></el-option>
                            <el-option class="select-option" :disabled="disabled" :label="$t('permissions.transfers')"
                                       value="transfers"></el-option>
                            <el-option class="select-option" :disabled="disabled" :label="$t('permissions.car_rental')"
                                       value="car-rental"></el-option>
                            <el-option class="select-option" :disabled="disabled" :label="$t('permissions.excursion')"
                                       value="excursion"></el-option>
                        </el-select>
                        <div v-if="$HasError(errors, 'permissions')" class="field-error">
                            <span>{{ $GetError(errors, 'permissions') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="popup-footer">
                <el-button @click="$emit('closePopup')" size="large" type="danger" round>{{ $t('close') }}</el-button>
                <el-button @click="submit()" :disabled="disabledSend" size="large" type="success" round>{{ $t('save') }}</el-button>
            </div>
        </div>
    </div>
</template>

<script>
    import helper from "../../../mixins/helper";
    import {$api} from "../../../api/api";
    import {api_url} from "../../../constants/api";
    import {ElMessage} from 'element-plus';

    export default {
        mixins: [helper],
        props: {
            model: Object
        },
        data() {
            return {
                roles: [],
                permissions: [],
                currentRoles: [],
                rolesEqual: false,
                permissionsEqual: false,
                currentPermissions: [],
                showPermissions: false,
                errors: {},
                disabled: false,
                disabledFullAccess: false,
                disabledSend: false,
                disabledPermissionFullAccess: false
            }
        },
        methods: {
            submit() {
                let data = {
                    roles: this.roles,
                    permissions: this.permissions
                }
                this.$store.commit('setPreloader', true);
                $api().post(api_url + 'client/set-roles-permissions/' + this.model.id, data).then(({data}) => {
                    if (data.success) {
                        this.errors = {};
                        this.$emit('handleUpdateClient', data.client);
                        this.$emit('closePopup');
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
            arraysEqual(newArray, currentArray) {
                return Array.isArray(newArray) &&
                    Array.isArray(currentArray) &&
                    newArray.length === currentArray.length &&
                    newArray.every((val, index) => val === currentArray[index]);
            },
        },
        watch: {
            roles: {
                deep: true,
                immediate: true,
                handler: function (data) {
                    if (data.includes('seller')) {
                        this.showPermissions = true;
                        this.disabledFullAccess = true;
                    } else {
                        this.showPermissions = false;

                        if (data.includes('full-access')) {
                            this.disabled = true;
                        } else if (data.includes('buyer')) {
                            this.disabledFullAccess = true;
                        } else {
                            this.disabled = false;
                            this.disabledFullAccess = false;
                        }
                    }
                    this.rolesEqual = this.arraysEqual(this.roles, this.currentRoles);
                    this.disabledSend = this.rolesEqual;
                }
            },
            permissions: {
                deep: true,
                immediate: true,
                handler: function (data) {
                    this.disabled = data.includes('full-access');
                    if(data.includes('full-access')) {
                        this.disabledPermissionFullAccess = false
                    } else {
                        this.disabledPermissionFullAccess = !!data.length;
                    }
                    this.permissionsEqual = this.arraysEqual(this.permissions, this.currentPermissions);
                    this.disabledSend = this.permissionsEqual;
                }
            },
            model: {
                deep: true,
                immediate: true,
                handler: function (data) {
                    if (data.roles && data.roles.length > 0) {
                        const roles = data.roles.sort();
                        roles.forEach(e => {
                            this.roles.push(e.name);
                            this.currentRoles.push(e.name);
                        })
                        this.rolesEqual = true;
                    }
                    if (data.permissions && data.permissions.length > 0) {
                        const permissions = data.permissions.sort();
                        permissions.forEach(e => {
                            this.permissions.push(e.name);
                            this.currentPermissions.push(e.name);
                        })
                        this.permissionsEqual = true;
                    }
                }
            }
        }
    }
</script>
