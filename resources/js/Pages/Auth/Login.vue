<template>
    <div>
        <div class="container">
            <div class="row">
                <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
                    <div class="card border-0 shadow rounded-3 my-5">
                        <div class="card-body p-4 p-sm-5">
                            <h5 class="card-title text-center mb-5 fw-light fs-5">Mailing.am</h5>
                            <form>
                                <div class="form-floating mb-3">
                                    <input
                                        v-model="formData.email"
                                        type="email"
                                        class="form-control"
                                        id="floatingInput"
                                        placeholder="name@example.com"
                                    >
                                    <label for="floatingInput">Էլ․ Փոստ</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input
                                        v-model="formData.password"
                                        type="password"
                                        class="form-control"
                                        id="floatingPassword"
                                        placeholder="Password"
                                    >
                                    <label for="floatingPassword">Գաղտնաբառ</label>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" value="" id="rememberPasswordCheck">
                                    <label class="form-check-label" for="rememberPasswordCheck">
                                        Հիշել գաղտնաբառը
                                    </label>
                                </div>
                                <div class="d-grid">
                                    <v-btn
                                        @click.prevent="login"
                                        class="btn btn-primary btn-login text-uppercase fw-bold"
                                        color="green accent-4"
                                        type="submit">Մուտք գործել</v-btn>

                                </div>
                            </form>
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
            }
        }
    },
    methods: {
        async login() {
            await axios.post('/api/check-login', this.formData)
                .then(response => {
                    location.href = '/'
                })
                .catch(e => {
                    this.errors.push(e)
                })
        }
    }
}
</script>

<style scoped lang="scss">
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
