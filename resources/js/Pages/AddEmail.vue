<template>
    <div>
        <Header/>
        <div class="contact-wrapper">
            <div>
                <navbar/>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
                        <div class="card border-0 shadow rounded-3 my-5">
                            <div class="card-body p-4 p-sm-5">
                                <h5 class="card-title text-center mb-5 fw-light fs-5">Ավելացնել Էլ․ Փոստ</h5>
                                <form>
                                    <div class="form-input">
                                        <v-text-field
                                            v-model="formData.email"
                                            label="Էլ․ փոստ"
                                            outlined
                                            dense
                                            hide-details="auto"
                                            type="text"
                                            :error-messages="errors.formData.email"
                                            @input="checkErrors('formData', 'email')"
                                        ></v-text-field>
                                    </div>
                                    <div class="d-grid">
                                        <v-btn
                                            @click.prevent="createMail"
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
import Navbar from "../Components/Navbar.vue";
import Header from "../Components/Header.vue";

export default {
    name: "CreateContact",
    components: {Navbar,Header},
    data: () => {
        return {
            formData: {
                email: '',
            },
            errors: {
                formData: {
                    email: '',
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
        async createMail() {

            await axios.post(`/api/create-mail`, this.formData)
                .then(response => {
                    this.$notify({
                        group: 'auth',
                        type: 'success',
                        text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Էլ․ փոստը հաջողությամբ գրանցված է',
                        duration: 1000,
                        speed: 1000
                    })
                    this.formData.email = ''
                })
                .catch(e => {
                    this.errors.formData = Object.assign(this.errors.formData, e.response.data.errors)
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
.contact-wrapper {
    display: flex;
}
.btn-login {
    background: #00C853 !important;
    color: #ffffff !important;
    border: 1px solid #00C853;
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
</style>
