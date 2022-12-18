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
                                <h5 class="card-title text-center mb-5 fw-light fs-5">Գրանցել նոր օգտատեր</h5>
                                <form>
                                    <div class="form-floating mb-3">
                                        <input
                                            v-model="formData.name"
                                            type="text"
                                            class="form-control"
                                            id="floatingInput"
                                            placeholder="name@example.com"
                                        >
                                        <label for="floatingInput">Անուն</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input
                                            v-model="formData.email"
                                            type="text"
                                            class="form-control"
                                            id="floatingInput"
                                            placeholder="email@example.com"
                                        >
                                        <label for="floatingInput">Էլ․ Փոստ</label>
                                    </div>
                                    <div class="d-grid">
                                        <v-btn
                                            @click.prevent="createContact"
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
                name: '',
                email: '',
            }
        }
    },
    methods: {
        async createContact() {
            const formData = new FormData()
            formData.append('name', this.formData.name)
            formData.append('email', this.formData.email)

            await axios.post('/api/create-contact', formData, {
                    headers: {
                        "Content-Type": "multipart/form-data"
                    }
                }
            ) .then(response => {
                console.log(response)
            }).catch(e => {
                console.log(e)
            })
        }
    }
}
</script>

<style scoped lang="scss">
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
