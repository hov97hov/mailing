<template>
    <v-app>
        <Header/>
        <div class="group-wrapper">
            <div>
                <Navbar/>
            </div>
            <div class="container">
                <div class="group-content">
                    <v-breadcrumbs class="breadcrumbs" :items="breadcrumbs"></v-breadcrumbs>
                    <div class="add-group">
                        <v-btn
                            @click="addGroupsDialog = true"
                        >
                            Ավելացնել Էլ․ փոստ
                        </v-btn>
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
                            :items="userEmails"
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
                                        <b>{{ item.email }}</b>
                                    </td>
                                    <td>
                                        {{item.host}}
                                    </td>
                                    <td>
                                        {{ item.from }}
                                    </td>
                                    <td>
                                        {{ item.header }}
                                    </td>
                                    <td>
                                        {{ item.port }}
                                    </td>
                                    <td>
                                        {{ item.encryption }}
                                    </td>
                                    <td>
                                        {{ item.username }}
                                    </td>
                                    <td>
                                        {{ item.password }}
                                    </td>
                                    <td>
                                        {{ item.created_at }}
                                    </td>
                                    <td>
                                        <div class="table-action">
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
        <!--ADD EMAIL-->
        <v-dialog
            v-model="addGroupsDialog"
            persistent
            max-width="600px"
        >
            <v-card>
                <v-card-title>
                    <span class="text-h5">Ստեղծել Էլ․ փոստ</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12" sm="12" md="12">
                                <v-text-field
                                    v-model="defaultEmailData.email"
                                    label="էլ․ փոստ"
                                    required
                                    hide-details="auto"
                                    :error-messages="errors.defaultEmailData.email"
                                    @input="checkErrors('defaultEmailData', 'email')"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="12" md="12">
                                <v-text-field
                                    v-model="defaultEmailData.host"
                                    label="Host"
                                    required
                                    hide-details="auto"
                                    :error-messages="errors.defaultEmailData.host"
                                    @input="checkErrors('defaultEmailData', 'host')"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="12" md="12">
                                <v-text-field
                                    v-model="defaultEmailData.from"
                                    label="From"
                                    required
                                    hide-details="auto"
                                    :error-messages="errors.defaultEmailData.from"
                                    @input="checkErrors('defaultEmailData', 'from')"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="12" md="12">
                                <v-text-field
                                    v-model="defaultEmailData.header"
                                    label="Header"
                                    required
                                    hide-details="auto"
                                    :error-messages="errors.defaultEmailData.header"
                                    @input="checkErrors('defaultEmailData', 'header')"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="12" md="12">
                                <v-text-field
                                    v-model="defaultEmailData.port"
                                    label="Port"
                                    required
                                    hide-details="auto"
                                    :error-messages="errors.defaultEmailData.port"
                                    @input="checkErrors('defaultEmailData', 'port')"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="12" md="12">
                                <v-text-field
                                    v-model="defaultEmailData.encryption"
                                    label="Encryption"
                                    required
                                    hide-details="auto"
                                    :error-messages="errors.defaultEmailData.encryption"
                                    @input="checkErrors('defaultEmailData', 'encryption')"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="12" md="12">
                                <v-text-field
                                    v-model="defaultEmailData.username"
                                    label="Username"
                                    required
                                    hide-details="auto"
                                    :error-messages="errors.defaultEmailData.username"
                                    @input="checkErrors('defaultEmailData', 'username')"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="12" md="12">
                                <v-text-field
                                    v-model="defaultEmailData.password"
                                    label="Password"
                                    required
                                    hide-details="auto"
                                    :error-messages="errors.defaultEmailData.password"
                                    @input="checkErrors('defaultEmailData', 'password')"
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
                        @click="addGroupsDialog = false"
                    >
                        Չեղարկել
                    </v-btn>
                    <v-btn
                        color="green darken-1"
                        text
                        @click="createEmail"
                    >
                        Հաստատել
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!--EDIT EMAIL-->
        <v-dialog
            v-model="editEmailDialog"
            persistent
            max-width="600px"
        >
            <v-card>
                <v-card-title>
                    <span class="text-h5">Թարմացնել Էլ․ փոստը</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12" sm="12" md="12">
                                <v-text-field
                                    v-model="editDefaultDataEmail.email"
                                    label="էլ․ փոստ"
                                    required
                                    hide-details="auto"
                                    :error-messages="errors.editDefaultDataEmail.email"
                                    @input="checkErrors('editDefaultDataEmail', 'email')"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="12" md="12">
                                <v-text-field
                                    v-model="editDefaultDataEmail.host"
                                    label="Host"
                                    required
                                    hide-details="auto"
                                    :error-messages="errors.editDefaultDataEmail.host"
                                    @input="checkErrors('editDefaultDataEmail', 'host')"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="12" md="12">
                                <v-text-field
                                    v-model="editDefaultDataEmail.from"
                                    label="From"
                                    required
                                    hide-details="auto"
                                    :error-messages="errors.editDefaultDataEmail.from"
                                    @input="checkErrors('editDefaultDataEmail', 'from')"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="12" md="12">
                                <v-text-field
                                    v-model="editDefaultDataEmail.header"
                                    label="Header"
                                    required
                                    hide-details="auto"
                                    :error-messages="errors.editDefaultDataEmail.header"
                                    @input="checkErrors('editDefaultDataEmail', 'header')"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="12" md="12">
                                <v-text-field
                                    v-model="editDefaultDataEmail.port"
                                    label="Port"
                                    required
                                    hide-details="auto"
                                    :error-messages="errors.editDefaultDataEmail.port"
                                    @input="checkErrors('editDefaultDataEmail', 'port')"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="12" md="12">
                                <v-text-field
                                    v-model="editDefaultDataEmail.encryption"
                                    label="Encryption"
                                    required
                                    hide-details="auto"
                                    :error-messages="errors.editDefaultDataEmail.encryption"
                                    @input="checkErrors('editDefaultDataEmail', 'encryption')"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="12" md="12">
                                <v-text-field
                                    v-model="editDefaultDataEmail.username"
                                    label="Username"
                                    required
                                    hide-details="auto"
                                    :error-messages="errors.editDefaultDataEmail.username"
                                    @input="checkErrors('editDefaultDataEmail', 'username')"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="12" md="12">
                                <v-text-field
                                    v-model="editDefaultDataEmail.password"
                                    label="Password"
                                    required
                                    hide-details="auto"
                                    :error-messages="errors.editDefaultDataEmail.password"
                                    @input="checkErrors('editDefaultDataEmail', 'password')"
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
                        @click="editEmailDialog = false"
                    >
                        Չեղարկել
                    </v-btn>
                    <v-btn
                        color="green darken-1"
                        text
                        @click="updateEmail"
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
                    Ջնջել Էլ․ փոստը
                </v-card-title>
                <v-card-text>Համոզված եղեք, որ ուզում եք ջնջել Էլ․ փոստը։</v-card-text>
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
                        @click="deleteEmail"
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
import { VueEditor } from "vue2-editor";

export default {
    name: "home",
    components: {
        Header,
        Navbar,
        VueEditor
    },
    data: () => {
        return {
            groupId: '',
            files: [],
            editDefaultDataEmail: [],
            defaultEmailData: {
                email: '',
                host: '',
                from: '',
                header: '',
                port: '',
                encryption: '',
                username: '',
                password: '',
            },
            autoUpdate: true,
            isUpdating: false,
            editEmailDialog: false,
            name: 'Midnight Crew',
            people: [
                { header: 'Օգտատերեր' },
            ],
            addUserGroupDialog: false,
            dialog: false,
            deleteDialog: false,
            addGroupsDialog: false,
            notifications: false,
            sound: true,
            widgets: false,
            emailId: '',
            userEmails: [],
            search: '',
            singleSelect: false,
            selected: [],
            emails: [],
            contactsGroups: [],
            breadcrumbs: [
                {
                    text: 'Գլխավոր',
                    disabled: false,
                    href: '/',
                },
                {
                    text: 'Էլ․ փոստերի ցուցակ',
                    disabled: true,
                    href: '',
                },
            ],
            headers: [
                { text: 'Նշել' },
                {
                    text: 'Էլ,փոստ',
                    align: 'start',
                    sortable: true,
                    value: 'email',
                },
                {text: 'Host', sortable: false, value: 'host'},
                {text: 'From', sortable: false, value: 'from'},
                {text: 'Header', sortable: false, value: 'header'},
                {text: 'Port', sortable: false, value: 'port'},
                {text: 'Encryption', sortable: false, value: 'encryption'},
                {text: 'Username', sortable: false, value: 'username'},
                {text: 'Password', sortable: false, value: 'username'},
                {text: 'Ամսաթիվ', sortable: false, value: 'created_at'},
                {text: 'Գործողություն', sortable: false, value: 'iron'},
            ],
            errors: {
                defaultEmailData: {
                    email: '',
                    host: '',
                    from: '',
                    header: '',
                    port: '',
                    encryption: '',
                    username: '',
                    password: '',
                },
                editDefaultDataEmail: {
                    email: '',
                    host: '',
                    from: '',
                    header: '',
                    port: '',
                    encryption: '',
                    username: '',
                    password: '',
                }
            }
        }
    },
    async created() {
        await this.getUserEmails()
    },
    methods: {
        checkErrors(obj, field) {
            if (obj) {
                this.errors[obj][field] = ''
            } else {
                this.errors[field] = ''
            }
        },

        openEditDialog(data) {
            this.emailId = data.id
            this.editDefaultDataEmail = data
            this.editEmailDialog = true
        },

        async getUserEmails() {
            await axios.post(`/api/get-user-emails`)
                .then(response => {
                    this.userEmails = response.data.userEmails
                })
                .catch(e => {
                    console.log(e)
                })
        },
        remove (item) {
            const index = this.contacts.indexOf(item.id)
            if (index >= 0) this.contacts.splice(index, 1)
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

        async createEmail() {
            await axios.post(`/api/create-email`, this.defaultEmailData)
                .then(response => {
                    this.$notify({
                        group: 'auth',
                        type: 'success',
                        text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Էլ․ փոստը ավելացվել է',
                        duration: 1000,
                        speed: 1000
                    })
                    this.getEmails()
                    this.addGroupsDialog = false
                })
                .catch(e => {
                    this.errors.defaultEmailData = Object.assign(this.errors.defaultEmailData, e.response.data.errors)
                })
        },

        async updateEmail() {
            await axios.post(`/api/update-email`, this.editDefaultDataEmail)
                .then(response => {
                    this.$notify({
                        group: 'auth',
                        type: 'success',
                        text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Էլ․ փոստը թարմացվել է',
                        duration: 1000,
                        speed: 1000
                    })
                    this.getEmails()
                    this.editEmailDialog = false
                })
                .catch(e => {
                    this.errors.editDefaultDataEmail = Object.assign(this.errors.editDefaultDataEmail, e.response.data.errors)
                })
        },

        openDeleteDialog(id) {
            this.emailId = id
            this.deleteDialog = true
        },

        async deleteEmail() {
            await axios.post(`/api/delete-email`, {id: this.emailId})
                .then(response => {
                    this.$notify({
                        group: 'auth',
                        type: 'success',
                        text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Էլ․ փոստը Ջնջված է',
                        duration: 1000,
                        speed: 1000
                    })
                    this.getEmails()
                    this.deleteDialog = false
                })
                .catch(e => {
                    console.log(e)
                })
        },

    }
}
</script>

<style scoped lang="scss">
.v-application .deep-purple.accent-4 {
    background-color: #00C853 !important;
    border-color: #00C853 !important;
    color: #ffffff !important;
}

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
        &:first-child {
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

        &:last-child {
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
//groups
.group-wrapper {
    display:flex;
    .group-content {
        .add-group {
            margin: 0 0 20px;
            display: flex;
            justify-content: flex-end;
            button {
                background: #ffffff !important;
                border: 2px solid #00C853;
                color: #00C853 !important;
                text-transform: capitalize;
                &:hover {
                    background: #00C853 !important;
                    color: #ffffff !important;;
                }
            }
        }
        margin-top: 50px;
        .group-items {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            .group-item {
                display: block;
                cursor: pointer;
                margin-bottom: 20px;
                width: calc(34% - 20px);
                box-shadow: 0 3px 1px -2px
                rgb(0 0 0 / 20%), 0 2px 2px 0
                rgb(0 0 0 / 14%), 0 1px 5px 0
                rgb(0 0 0 / 12%);
                padding: 15px;
                background: #ffffff;
                .send-group-message {
                    .email-btn {
                        background: #ffffff !important;
                        border: 2px solid #00C853;
                        color: #00C853 !important;
                    }
                    .delete-btn {
                        background: #ffffff !important;
                        border: 2px solid #F4511E;
                        color: #F4511E !important;
                    }
                    button {
                        min-width: auto !important;
                        width: 35px !important;
                        height: 35px !important;
                        border-radius: 50%;
                        i {
                            font-size: 20px !important;
                        }
                    }
                }
                .group-btn-content {
                    button {
                        background: #ffffff !important;
                        border: 2px solid #00C853;
                        color: #00C853 !important;
                        &:hover {
                            background: #00C853 !important;
                            color: #ffffff !important;;
                        }
                    }
                }
                .group-item-header {
                    display: flex;
                    justify-content: space-between;
                    margin: 0 0 15px;
                    .group-title {
                        word-break: break-word;
                        .group-title-image {
                            margin-right: 10px;
                            margin-botom: 10px;
                            img {
                                width: 100px;
                                height: 50px;
                                object-fit: cover;
                            }
                        }
                    }
                }
                .group-count {
                    margin: 0 0 20px;
                    display: flex;
                    span {
                        margin-right: 5px;
                    }
                }
            }
        }
    }
}
//mailing dialog
.v-card {
    overflow: auto;
    overscroll-behavior-x: none;
}
.v-card::-webkit-scrollbar{
    display: none !important;
}
.message-dialog-content {
    display: flex;
    justify-content: center;
    .write-message-content {
        width: 70%;
        background: #ffffff;
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
