<template>
    <div>
        <div class="header">
            <div class="container">
                <div class="header-content">
                    <div class="logo">
                        <a href="/">
                            <img src="/images/header/logo.png" alt="logo">
                            <span>Mailing</span>
                        </a>
                    </div>
                    <div class="header-last">
                        <div class="menu-btns">
                            <v-btn
                                @click="createEmailsPage"
                                class="create-btn"
                            >
                                <v-icon>mdi-plus-circle-outline</v-icon>
                                <span>Ավելացնել էլ․ Փոստ</span>
                            </v-btn
                            >
                            <v-btn
                                @click="createCategoryPage"
                                class="create-btn"
                            >
                                <v-icon>mdi-plus-circle-outline</v-icon>
                                <span>Ավելացնել կատեգորիա</span>
                            </v-btn>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <v-dialog
            v-model="addCategoryDialog"
            persistent
            max-width="600px"
        >
            <v-card>
                <v-card-title>
                    <span class="text-h5">User Profile</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col
                                cols="12"
                                sm="6"
                                md="4"
                            >
                                <v-text-field
                                    label="Legal first name*"
                                    required
                                ></v-text-field>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="6"
                                md="4"
                            >
                                <v-text-field
                                    label="Legal middle name"
                                    hint="example of helper text only on focus"
                                ></v-text-field>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="6"
                                md="4"
                            >
                                <v-text-field
                                    label="Legal last name*"
                                    hint="example of persistent helper text"
                                    persistent-hint
                                    required
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field
                                    label="Email*"
                                    required
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field
                                    label="Password*"
                                    type="password"
                                    required
                                ></v-text-field>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="6"
                            >
                                <v-select
                                    :items="['0-17', '18-29', '30-54', '54+']"
                                    label="Age*"
                                    required
                                ></v-select>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="6"
                            >
                                <v-autocomplete
                                    :items="['Skiing', 'Ice hockey', 'Soccer', 'Basketball', 'Hockey', 'Reading', 'Writing', 'Coding', 'Basejump']"
                                    label="Interests"
                                    multiple
                                ></v-autocomplete>
                            </v-col>
                        </v-row>
                    </v-container>
                    <small>*indicates required field</small>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="blue darken-1"
                        text
                        @click="addCategoryDialog = false"
                    >
                        Close
                    </v-btn>
                    <v-btn
                        color="blue darken-1"
                        text
                    >
                        Save
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "home",
    data: () => {
        return {
            addCategoryDialog: false
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
        createCategoryPage() {
            location.href = '/groups'
        },
        createEmailsPage() {
            location.href = '/emails'
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
        height: 70px;
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            .logo {
                a {
                    display: flex;
                    color: #253266 !important;
                    text-decoration: none;
                    align-items: flex-start;
                    img {
                        width: 50px;
                    }
                    span {
                        font-style: normal;
                        font-weight: 700;
                        font-size: 26px;
                        line-height: 71px;
                        display: flex;
                        align-items: center;
                        margin-top: -7px;
                    }
                }
            }
            .header-last {
                display: flex;
                .create-btn {
                    background: transparent !important;
                    box-shadow: unset !important;
                    border: unset !important;
                    text-transform: capitalize !important;
                }
            }
        }
    }
</style>
