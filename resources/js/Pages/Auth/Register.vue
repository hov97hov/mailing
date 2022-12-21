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
                                <h5 class="card-title text-center mb-5 fw-light fs-5">Գրանցվել</h5>
                                <form>
                                    <div class="form-input">
                                        <v-text-field
                                            v-model="formData.name"
                                            type="text"
                                            label="Անուն"
                                            outlined
                                            dense
                                            hide-details="auto"
                                            :error-messages="errors.formData.name"
                                            @input="checkErrors('formData', 'name')"
                                        ></v-text-field>
                                    </div>
                                    <div class="form-input">
                                        <v-text-field
                                            v-model="formData.email"
                                            type="text"
                                            label="Էլ․փոստ"
                                            outlined
                                            dense
                                            hide-details="auto"
                                            :error-messages="errors.formData.email"
                                            @input="checkErrors('formData', 'email')"
                                        ></v-text-field>
                                    </div>
                                    <div class="form-input">
                                        <v-text-field
                                            v-model="formData.password"
                                            label="Գաղտնաբառ"
                                            outlined
                                            dense
                                            hide-details="auto"
                                            type="password"
                                            :error-messages="errors.formData.password"
                                            @input="checkErrors('formData', 'password')"
                                        ></v-text-field>
                                    </div>
                                    <div class="form-input">
                                        <v-text-field
                                            v-model="formData.password_confirmation"
                                            label="Կրկնել գաղտնաբառը"
                                            type="password"
                                            outlined
                                            dense
                                            hide-details="auto"
                                            :error-messages="errors.formData.password"
                                            @input="checkErrors('formData', 'password')"
                                        ></v-text-field>
                                    </div>
                                    <div class="d-grid">
                                        <v-btn
                                            @click.prevent="submit"
                                            class="btn btn-primary btn-login text-uppercase fw-bold"
                                            color="green accent-4"
                                            type="submit">Գրանցել</v-btn>
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
import Header from "../../Components/Header";
import Navbar from "../../Components/Navbar";

export default {
    name: "Login",
    components: {Header,Navbar},
    data: () => {
        return {
            formData: {
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
            },
            errors: {
                formData: {
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                }
            }
        }
    },
    methods: {
        checkErrors(obj, field) {
            if (obj) {
                this.errors[obj][field] = ''
            } else {
                this.errors[field] = ''
            }
        },
        async submit() {
            axios.get('/sanctum/csrf-cookie').then(response => {
                axios.post('/api/register', {
                    name: this.formData.name,
                    email: this.formData.email,
                    password: this.formData.password,
                    password_confirmation: this.formData.password_confirmation,
                }).then(r => {
                    this.$notify({
                        group: 'auth',
                        type: 'success',
                        text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Օգտատերտ հաջողությամբ գրանցված է',
                        duration: 1000,
                        speed: 1000
                    })
                    this.formData.name = ''
                    this.formData.email = ''
                    this.formData.password = ''
                    this.formData.password_confirmation = ''
                }).catch(e => {
                    this.errors.formData = Object.assign(this.errors.formData, e.response.data.errors)
                })
            })
        }
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
