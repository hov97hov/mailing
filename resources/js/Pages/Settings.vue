<template>
    <div>
        <Header/>
        <div class="register-wrapper">
            <div>
                <Navbar/>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
                        <div class="card border-0 shadow rounded-3 my-5">
                            <div class="card-body p-4 p-sm-5">
                                <h5 class="card-title text-center mb-5 fw-light fs-5">SMTP Կարգավորումներ</h5>
                                <form>
                                    <div class="form-input">
                                        <v-text-field
                                            v-model="formData.host"
                                            type="text"
                                            label="Host"
                                            outlined
                                            dense
                                            hide-details="auto"
                                            :error-messages="errors.formData.host"
                                            @input="checkErrors('formData', 'host')"
                                        ></v-text-field>
                                    </div>
                                    <div class="form-input">
                                        <v-text-field
                                            v-model="formData.from"
                                            type="text"
                                            label="From"
                                            outlined
                                            dense
                                            hide-details="auto"
                                            :error-messages="errors.formData.from"
                                            @input="checkErrors('formData', 'from')"
                                        ></v-text-field>
                                    </div>
                                    <div class="form-input">
                                        <v-text-field
                                            v-model="formData.header"
                                            label="Header"
                                            outlined
                                            dense
                                            hide-details="auto"
                                            type="text"
                                            :error-messages="errors.formData.header"
                                            @input="checkErrors('formData', 'header')"
                                        ></v-text-field>
                                    </div>
                                    <div class="form-input">
                                        <v-text-field
                                            v-model="formData.port"
                                            label="Port"
                                            outlined
                                            dense
                                            hide-details="auto"
                                            type="text"
                                            :error-messages="errors.formData.port"
                                            @input="checkErrors('formData', 'port')"
                                        ></v-text-field>
                                    </div>
                                    <div class="form-input">
                                        <v-text-field
                                            v-model="formData.encryption"
                                            label="Encryption"
                                            outlined
                                            dense
                                            hide-details="auto"
                                            type="text"
                                            :error-messages="errors.formData.encryption"
                                            @input="checkErrors('formData', 'encryption')"
                                        ></v-text-field>
                                    </div>
                                    <div class="form-input">
                                        <v-text-field
                                            v-model="formData.username"
                                            label="Username"
                                            outlined
                                            dense
                                            hide-details="auto"
                                            type="text"
                                            :error-messages="errors.formData.username"
                                            @input="checkErrors('formData', 'username')"
                                        ></v-text-field>
                                    </div>
                                    <div class="form-input">
                                        <v-text-field
                                            v-model="formData.password"
                                            label="Password"
                                            outlined
                                            dense
                                            hide-details="auto"
                                            type="text"
                                            :error-messages="errors.formData.password"
                                            @input="checkErrors('formData', 'password')"
                                        ></v-text-field>
                                    </div>
                                    <div class="d-grid">
                                        <v-btn
                                            @click.prevent="submit"
                                            class="btn btn-primary btn-login text-uppercase fw-bold"
                                            color="green accent-4"
                                            type="submit">Հաստատել</v-btn>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--NOTIFICATION-->
        <notifications group="auth"/>
    </div>
</template>

<script>
import axios from "axios";
import Header from "../Components/Header";
import Navbar from "../Components/Navbar";

export default {
    name: "Login",
    components: {Header,Navbar},
    data: () => {
        return {
            formData: {
                host: '',
                port: '',
                from: '',
                header: '',
                encryption: '',
                username: '',
                password: '',
            },
            errors: {
                formData: {
                    host: '',
                    port: '',
                    from: '',
                    header: '',
                    encryption: '',
                    username: '',
                    password: '',
                }
            }
        }
    },
    async created() {
        await this.getSettings()
    },
    methods: {
        checkErrors(obj, field) {
            if (obj) {
                this.errors[obj][field] = ''
            } else {
                this.errors[field] = ''
            }
        },
        async getSettings() {
            await axios.post(`/api/get-settings`, this.formData)
                .then(response => {
                    this.formData.from = response.data.settings.from
                    this.formData.host = response.data.settings.host
                    this.formData.port = response.data.settings.port
                    this.formData.header = response.data.settings.header
                    this.formData.encryption = response.data.settings.encryption
                    this.formData.username = response.data.settings.username
                    this.formData.password = response.data.settings.password
                })
                .catch(e => {
                    console.log(e)
                })
        },

        async submit() {
            await axios.post(`/api/create-settings`, this.formData)
                .then(response => {
                    this.$notify({
                        group: 'auth',
                        type: 'success',
                        text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Կարգավորումները թարմացված են ',
                        duration: 1000,
                        speed: 1000
                    })
                    this.getSettings()
                })
                .catch(e => {
                    this.errors.formData = Object.assign(this.errors.formData, e.response.data.errors)
                })
        },
    }
}
</script>

<style scoped lang="scss">
.d-grid {
    margin-top: 30px;
}
.form-input {
    margin-bottom: 20px;
}
.error-message {
    color: red;
    font-size: 12px;
    display: block;
    margin-top: 5px
}
.register-wrapper {
    display: flex;
}
.btn-login {
    font-size: 0.9rem;
    letter-spacing: 0.05rem;
    padding: 0.75rem 1rem;
}

.btn-google {
    color: white !important;
    background-color: #ea4335;
}

.btn-facebook {
    color: white !important;
    background-color: #3b5998;
}

.btn-login {
    background: #00C853 !important;
    color: #ffffff !important;
    border: 1px solid #00C853;
    font-size: 0.9rem;
    letter-spacing: 0.05rem;
    padding: 0.75rem 1rem;
}
</style>
