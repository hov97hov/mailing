<template>
    <v-app>
        <Header/>
        <div class="users-wrapper">
            <div>
                <Navbar/>
            </div>
            <div class="container">
                <div class="users-content">
                    <v-breadcrumbs class="breadcrumbs" :items="breadcrumbs"></v-breadcrumbs>
                    <div class="mailing-right-list">
                        <div class="right-list-action">
                            <div class="action-btn-content" v-if="selected.length">
                                <v-btn
                                    @click="openDeleteSelectedContactsDialog"
                                    color="deep-orange darken-2"
                                    dark
                                >
                                    Ջնջել
                                </v-btn>
                            </div>
                        </div>
                        <v-card>
                            <v-card-title>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Որոնել"
                                    single-line
                                    hide-details
                                    color="green accent-4"
                                ></v-text-field>
                            </v-card-title>
                            <v-data-table
                                :headers="headers"
                                :items="users"
                                :search="search"
                                item-key="id"
                                solo
                                :footer-props="{
                                    showFirstLastPage: true,
                                    'items-per-page-text':'Գնալ',
                                 }"
                            >
                                <template v-slot:body="{ items, options }">
                                    <tbody>
                                    <tr
                                        v-for="item in items"
                                        :key="item.id"
                                        class="table-tr"
                                    >
                                        <td>
                                            <v-checkbox
                                                v-model="selected"
                                                :value="item.id"
                                                item-value="id"
                                                hide-details
                                                color="green accent-4"
                                            />
                                        </td>
                                        <td>
                                            <b>{{ item.name }}</b>
                                        </td>
                                        <td>
                                            <button>{{ item.email }}</button>
                                        </td>
                                        <td>
                                            <div v-for="email in item.emails">
                                                {{ email.email }}<br> <br>
                                            </div>
                                        </td>
                                        <td>
                                            <button>{{ item.created_at }}</button>
                                        </td>
                                        <td>
                                            <div class="table-action">
                                                <div class="action-item">
                                                    <v-btn @click="openDialogAddEmail(item)">
                                                        <v-icon>mdi-plus</v-icon>
                                                    </v-btn>
                                                </div>
                                                <div class="action-item">
                                                    <v-btn @click="openEditDialog(item)">
                                                        <v-icon>mdi-pencil</v-icon>
                                                    </v-btn>
                                                </div>
                                                <div class="action-item">
                                                    <v-btn @click="openDeleteDialog(item.id)">
                                                        <v-icon>mdi-delete</v-icon>
                                                    </v-btn>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </template>
                            </v-data-table>
                        </v-card>
                    </div>
                </div>
            </div>
        </div>
        <!--EDIT CONTACT-->
        <v-dialog
            v-model="editUserDialog"
            persistent
            max-width="600px"
        >
            <v-card>
                <v-card-title>
                    <span class="text-h5">Թարմացնել օգտատիրոջը</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12">
                                <v-text-field
                                    v-model="defaultEditData.name"
                                    label="Անուն"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field
                                    v-model="defaultEditData.email"
                                    label="Էլ․ Փոստ"
                                ></v-text-field>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="red darken-1"
                        text
                        @click="editUserDialog = false"
                    >
                        Չեղարկել
                    </v-btn>
                    <v-btn
                        color="green darken-1"
                        text
                        @click="updateUser"
                    >
                        Հաստատել
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!--ADD EMAIL-->
        <v-dialog
            v-model="addEmailDialog"
            persistent
            max-width="600px"
        >
            <v-card>
                <v-card-title>
                    <span class="text-h5">Ավելացնել Էլ․ փոստ</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12">
                                <div v-for="item in emails"
                                >
                                    <v-checkbox
                                        v-model="selectEmail"
                                        :label="item.email"
                                        :value="item.id"
                                    ></v-checkbox>
                                </div>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="red darken-1"
                        text
                        @click="addEmailDialog = false"
                    >
                        Չեղարկել
                    </v-btn>
                    <v-btn
                        color="green darken-1"
                        text
                        @click="createEmailUser"
                    >
                        Հաստատել
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!--DELETE CONTACT-->
        <v-dialog
            v-model="deleteDialog"
            persistent
            max-width="290"
        >
            <v-card>
                <v-card-title class="text-h5">
                    Ջնջել օգտատիրոջը
                </v-card-title>
                <v-card-text>Համոզված եղեք, որ ուզում եք ջնջել օգտատիրոջը։</v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="green accent-4"
                        text
                        @click="deleteDialog = false"
                    >
                        Չեղարկել
                    </v-btn>
                    <v-btn
                        color="deep-orange darken-2"
                        text
                        @click="deleteUser"
                    >
                        Ջնջել
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!--DELETE CONTACT-->
        <v-dialog
            v-model="deleteSelectedContactsDialog"
            persistent
            max-width="290"
        >
            <v-card>
                <v-card-title class="text-h5">
                    Ջնջել օգտատիրոջը
                </v-card-title>
                <v-card-text>Համոզված եղեք, որ ուզում եք ջնջել օգտատիրոջը։</v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="green accent-4"
                        text
                        @click="deleteSelectedContactsDialog = false"
                    >
                        Չեղարկել
                    </v-btn>
                    <v-btn
                        color="deep-orange darken-2"
                        text
                        @click="deleteSelectedContacts"
                    >
                        Ջնջել
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!--NOTIFICATION-->
        <notifications group="auth"/>
    </v-app>
</template>

<script>
import axios from "axios";
import Header from '../../../resources/js/Components/Header'
import Navbar from '../../../resources/js/Components/Navbar'
import {VueEditor} from "vue2-editor";

export default {
    name: "Contact",
    components: {
        Navbar,
        Header,
        VueEditor
    },
    data: () => {
        return {
            addEmailDialog: false,
            editUserDialog: false,
            deleteSelectedContactsDialog: false,
            deleteContactsModal: false,
            deleteDialog: false,
            editContactDialog: false,
            notifications: false,
            sound: true,
            widgets: false,
            files: [],
            users: [],
            selectEmail: [],
            emails: [],
            search: '',
            singleSelect: false,
            selected: [],
            breadcrumbs: [
                {
                    text: 'Գլխավոր',
                    disabled: false,
                    href: '/',
                },
                {
                    text: 'Օգտատերեր',
                    disabled: true,
                    href: '',
                },
            ],
            headers: [
                { text: 'Նշել' },
                {
                    text: 'Անուն',
                    align: 'start',
                    sortable: true,
                    value: 'name',
                },
                {text: 'Էլ․ փոստ', sortable: false, value: 'email'},
                {text: 'Կցված էլ․ փոստերը', sortable: false, value: 'emails'},
                {text: 'Գրանցման ամսաթիվ', sortable: false, value: 'created_at'},
                {text: 'Գործողություն', sortable: false, value: 'iron'},
            ],
            defaultEditData: {},
            errors: {

            }
        }
    },
    watch: {

    },
    async created() {
        await this.getUsers()
    },
    methods: {
        checkErrors(obj, field) {
            if (obj) {
                this.errors[obj][field] = ''
            } else {
                this.errors[field] = ''
            }
        },

        openDialogSendGroupMessage() {
            this.dialog = true
        },

        openDeleteSelectedContactsDialog() {
            this.deleteSelectedContactsDialog = true
        },

        openDeleteDialog(id) {
            this.userId = id
            this.deleteDialog = true
        },

        openEditDialog(data) {
            this.userId = data.id
            this.defaultEditData = data
            this.editUserDialog = true
        },

        openDialogAddEmail(data) {
            this.userId = data.id
            this.addEmailDialog = true
            this.getEmails()
        },

        async updateUser() {
            const formData = new FormData()
            formData.append('id', this.defaultEditData.id)
            formData.append('name', this.defaultEditData.name)
            formData.append('email', this.defaultEditData.email)

            await axios.post(`/api/update-user`, formData)
                .then(response => {
                    this.getUsers()
                    this.editUserDialog = false
                    this.$notify({
                        group: 'auth',
                        type: 'success',
                        text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Օգտատերը թարմացվել է',
                        duration: 1000,
                        speed: 1000
                    })
                })
                .catch(e => {
                    console.log(e)
                })
        },

        async getEmails() {
            await axios.post(`/api/get-emails`)
                .then(response => {
                    this.emails = response.data.emails
                })
                .catch(e => {
                    console.log(e)
                })
        },

        async deleteSelectedContacts() {
            await axios.post(`/api/delete-selected-users`, {ids: this.selected})
                .then(response => {
                    this.$notify({
                        group: 'auth',
                        type: 'success',
                        text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Օգտատերը Ջնջված է',
                        duration: 1000,
                        speed: 1000
                    })
                    this.getUsers()
                    this.deleteSelectedContactsDialog = false
                })
                .catch(e => {
                    console.log(e)
                })
        },

        async createEmailUser() {
            await axios.post(`/api/create-email-user`, {
                id: this.userId,
                email: this.selectEmail
            })
                .then(response => {
                    this.$notify({
                        group: 'auth',
                        type: 'success',
                        text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Օգտատիրոջ համար ավելացվել է էլ․ փոստ',
                        duration: 1000,
                        speed: 1000
                    })
                    this.getUsers()
                    this.addEmailDialog = false
                })
                .catch(e => {
                    console.log(e)
                })
        },

        async deleteUser() {
            await axios.post(`/api/delete-user`, {id: this.userId})
                .then(response => {
                    this.$notify({
                        group: 'auth',
                        type: 'success',
                        text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Օգտատերը ջնջված է',
                        duration: 1000,
                        speed: 1000
                    })
                    this.getUsers()
                    this.deleteDialog = false
                })
                .catch(e => {
                    console.log(e)
                })
        },

        selectAll(type){
            if(!type){
                this.contacts.map((item, i) => {
                    if (!this.selected.includes(item.id)) {
                        this.selected.push(item.id);
                    }
                })
            }else{
                this.selected = []
            }

        },

        async getUsers() {
            await axios.post(`/api/get-users`)
                .then(response => {
                    this.users = response.data.users
                })
                .catch(e => {
                    console.log(e)
                })
        }

    }
}
</script>

<style scoped lang="scss">
//groups
.users-wrapper {
    display: flex;
    .breadcrumbs {
        margin-top: 50px;
    }
    .users-content {
        .mailing-right-list {
            .right-list-action {
                display: flex;
                justify-content: flex-end;
                margin: 0 0 20px;

                .action-btn-content {
                    margin-left: 10px;

                    .send-message {
                        background: #ffffff !important;
                        border: 2px solid #00C853;
                        color: #00C853 !important;

                        &:hover {
                            background: #00C853 !important;
                            color: #ffffff !important;;
                        }
                    }

                    button {
                        text-transform: capitalize;

                        i {
                            margin-left: 5px;
                        }
                    }
                }
            }

            width: 100%;

            .table-tr {
                cursor: pointer;

                .v-input--checkbox {
                    margin-top: 0 !important;
                }

                &:hover {
                    background: #43a0474d !important;
                }
            }
        }
    }
}

//table action
.table-action {
    display: flex;

    .action-item {
        &:nth-child(1) {
            margin-right: 5px;

            button {
                background: #ffffff !important;
                border: 2px solid green;
                color: green !important;

                &:hover {
                    background: green !important;
                    color: #ffffff !important;
                }
            }
        }
        &:nth-child(2) {
            margin-right: 5px;

            button {
                background: #ffffff !important;
                border: 2px solid #ffeb00;
                color: #ffeb00 !important;

                &:hover {
                    background: #ffeb00 !important;
                    color: #000000 !important;
                }
            }
        }

        &:nth-child(3) {
            button {
                background: #ffffff !important;
                border: 2px solid #F4511E;
                color: #F4511E !important;

                &:hover {
                    background: #F4511E !important;
                    color: #ffffff !important;
                }
            }
        }

        button {
            min-width: auto !important;
            width: 35px !important;
            height: 35px !important;
            border-radius: 50% !important;

            i {
                font-size: 18px !important;
            }
        }
    }
}

//mailing dialog
.v-card {
    overflow: auto;
    overscroll-behavior-x: none;
}

.v-card::-webkit-scrollbar {
    display: none !important;
}

.message-dialog-content {
    display: flex;
    justify-content: center;

    .write-message-content {
        width: 70%;
    }

    .mailing-field-content {
        margin-top: 20px;
    }

    .mailing-button-field-content {
        margin-top: 20px;
        margin-bottom: 50px;
        display: flex;
        justify-content: flex-end;
    }
}
</style>
