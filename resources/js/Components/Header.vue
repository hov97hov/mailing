<template>
    <div>
        <div class="header">
            <div class="container">
                <div class="header-content">
                    <div class="logo">
                        <a href="/">
                            <img src="/images/header/logo.png" alt="logo">
                        </a>
                    </div>
                    <div class="header-btn-content">
                        <div class="menu-btns">
                            <v-btn
                                @click="logout"
                                class="menu-btn"
                            >
                                Դուրս գալ
                            </v-btn>
                        </div>
                    </div>
                </div>
                <v-dialog />
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "home",
    data: () => {
        return {

        }
    },
    async created() {
    },
    methods: {
        checkErrors(obj, field) {
            if (obj) {
                this.errors[obj][field] = ''
            } else {
                this.errors[field] = ''
            }
        },
        async logout() {
            axios.get('/sanctum/csrf-cookie').then(response => {
                axios.post('/api/logout').then(r => {
                    location.href = '/'
                })
            })
        }
    }
}
</script>

<style scoped lang="scss">
    .header {
        background: #ffffff;
        box-shadow: 0 3px 1px -2px rgb(0 0 0 / 20%), 0 2px 2px 0 rgb(0 0 0 / 14%), 0 1px 5px 0 rgb(0 0 0 / 12%);
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            .logo {
                a {
                    img {
                        width: 50px;
                    }
                }
            }
            .header-btn-content {
                display: flex;
                .menu-btns {
                    .menu-btn {
                        margin-left: 10px;
                        background: #ffffff;
                        border: 2px solid #00C853;
                        box-shadow: none;
                        text-transform: capitalize;
                        border-radius: 5px;
                        &:hover {
                            background: #00C853;
                            color: #ffffff;
                        }
                    }
                }
            }
        }
    }
</style>
