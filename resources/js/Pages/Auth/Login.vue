<template>
    <div>
        <div class="container">
            <div class="row">
                <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
                    <div class="card border-0 shadow rounded-3 my-5">
                        <div class="card-body p-4 p-sm-5">
                            <h5 class="card-title text-center mb-5 fw-light fs-5">Mailing.am</h5>
                            <div class="form-input">
                                <v-text-field
                                    v-model="formData.email"
                                    outlined
                                    dense
                                    label="Էլ․ փոստ"
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
                            <div class="d-grid">
                                <v-btn
                                    @click.prevent="login"
                                    class="btn btn-primary btn-login text-uppercase fw-bold"
                                    color="#253266"
                                    type="submit">Մուտք գործել</v-btn>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "Login",
    data: () => {
        return {
            formData: {
                email: '',
                password: '',
            },
            errors: {
                formData: {
                    email: '',
                    password: '',
                },
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
       login () {
            axios.get('/sanctum/csrf-cookie').then(response => {
                axios.post('/api/check-login', {
                    email: this.formData.email,
                    password: this.formData.password,
                }).then(r => {
                    location.href = '/'
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
        background: #253266 !important;
        color: #ffffff !important;
        border: 1px solid #253266;
        font-size: 0.9rem;
        letter-spacing: 0.05rem;
        padding: 0.75rem 1rem;
    }
</style>
