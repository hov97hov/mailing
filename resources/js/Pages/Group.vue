<template>
    <v-app>
        <Header/>
           <div class="users-wrapper">
               <div>
                   <Navbar/>
               </div>
               <div class="container">
                   <v-breadcrumbs class="breadcrumbs" :items="breadcrumbs"></v-breadcrumbs>
                   <div class="users-content">
                       <div class="mailing-right-list">
                           <div class="right-list-action">
                               <div class="action-btn-content" v-if="selected.length">
                                   <v-btn
                                       @click="openDeleteContactsDialog"
                                       color="deep-orange darken-2"
                                       dark
                                   >
                                       Ջնջել
                                   </v-btn>
                               </div>
                               <div class="action-btn-content" v-if="selected.length">
                                   <v-btn
                                       @click="openDialogSendGroupMessage"
                                       class="send-message"
                                       dark
                                   >
                                       <span>Ուղարկել նամակ</span>
                                       <v-icon>mdi-email-outline</v-icon>
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
                                   :items="contacts.contact"
                                   :search="search"
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
                                               <b>{{item.name}}</b>
                                           </td>
                                           <td>
                                               <button>{{item.email}}</button>
                                           </td>
                                           <td>
                                               <button>{{item.created_at}}</button>
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
           </div>
        <!--MESSAGE-->
        <v-dialog
            v-model="dialog"
            fullscreen
            hide-overlay
            transition="dialog-bottom-transition"
        >
            <v-card>
                <v-toolbar
                    color="green accent-4"
                >
                    <v-btn
                        icon
                        dark
                        @click="dialog = false"
                    >
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title class="text-white">Գրել Նոր նամակ</v-toolbar-title>
                </v-toolbar>
                <v-container>
                    <div class="message-dialog-content">
                        <div class="write-message-content">
                            <div class="mailing-field-content">
                                <v-text-field
                                    v-model="defaultMessage.subject"
                                    label="Թեմա"
                                    solo
                                    hide-details="auto"
                                    :error-messages="errors.defaultMessage.subject"
                                    @input="checkErrors('defaultMessage', 'subject')"
                                ></v-text-field>
                            </div>
                            <div class="mailing-field-content">
                                <v-radio-group
                                    v-model="defaultMessage.userEmail"
                                    mandatory
                                >
                                    <v-radio
                                        v-for="item in userEmails"
                                        :key="item.id"
                                        :label="item.email"
                                        :value="item.email"
                                    ></v-radio>
                                </v-radio-group>
                            </div>
                            <div class="mailing-field-content">
                                <v-file-input
                                    v-model="files"
                                    color="green accent-4"
                                    counter
                                    label="Ֆայլ"
                                    multiple
                                    placeholder="Ընտրել Ֆայլ"
                                    prepend-icon="mdi-paperclip"
                                    outlined
                                    show-size
                                >
                                    <template v-slot:selection="{ index, text }">
                                        <v-chip
                                            v-if="index < 2"
                                            color="green accent-4"
                                            dark
                                            label
                                            small
                                        >
                                            {{ text }}
                                        </v-chip>

                                        <span
                                            v-else-if="index === 2"
                                            class="text-overline mx-2"
                                        >
                                                +{{ files.length - 2 }} File(s)
                                            </span>
                                    </template>
                                </v-file-input>
                            </div>
                            <div class="mailing-field-content">
                                <vue-editor
                                    v-model="defaultMessage.text"
                                >
                                </vue-editor>
                            </div>
                            <div class="mailing-button-field-content">
                                <v-btn
                                    @click="sendMessageGroup"
                                    color="green accent-4"
                                    dark
                                >
                                    Ուղարկել
                                </v-btn>
                            </div>
                        </div>
                    </div>
                </v-container>
            </v-card>
        </v-dialog>
        <!--DELETE CONTACT-->
        <v-dialog
            v-model="deleteContactDialog"
            persistent
            max-width="290"
        >
            <v-card>
                <v-card-title class="text-h5">
                    Ջնջել օգտատիրոջը խմբից
                </v-card-title>
                <v-card-text>Համոզված եղեք, որ ուզում եք ջնջել օգտատիրոջը խմբից։</v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="green accent-4"
                        text
                        @click="deleteContactDialog = false"
                    >
                        Չեղարկել
                    </v-btn>
                    <v-btn
                        color="deep-orange darken-2"
                        text
                        @click="deleteContact"
                    >
                        Ջնջել
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!--DELETE CONTACTS-->
        <v-dialog
            v-model="deleteContactsDialog"
            persistent
            max-width="290"
        >
            <v-card>
                <v-card-title class="text-h5">
                    Ջնջել օգտատիրոջը խմբից
                </v-card-title>
                <v-card-text>Համոզված եղեք, որ ուզում եք ջնջել օգտատերերին խմբից։</v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="green accent-4"
                        text
                        @click="deleteContactsDialog = false"
                    >
                        Չեղարկել
                    </v-btn>
                    <v-btn
                        color="deep-orange darken-2"
                        text
                        @click="deleteSelectedContactGroup"
                    >
                        Ջնջել
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!--EDIT CONTACT-->
        <v-dialog
            v-model="editContactDialog"
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
                        @click="editContactDialog = false"
                    >
                        Չեղարկել
                    </v-btn>
                    <v-btn
                        color="green darken-1"
                        text
                        @click="updateContact"
                    >
                        Հաստատել
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
    name: "Group",
    components: {
        Header,
        Navbar,
        VueEditor
    },
    data: () => {
        return {
            files: [],
            editContactDialog: false,
            deleteContactDialog: false,
            deleteContactsDialog: false,
            dialog: false,
            notifications: false,
            sound: true,
            widgets: false,
            search: '',
            allSelected: false,
            selected: [],
            contactId: null,
            defaultMessage: {
                subject: '',
                text: '',
                userEmail: '',
            },
            breadcrumbs: [
                {
                    text: 'Գլխավոր',
                    disabled: false,
                    href: '/',
                },
                {
                    text: 'Խմբեր',
                    disabled: false,
                    href: '/groups',
                },
                {
                    text: 'Խումբ',
                    disabled: true,
                    href: '',
                },
            ],
            headers: [
                {
                    text: 'Անուն',
                    align: 'start',
                    sortable: true,
                    value: 'name',
                },
                { text: 'Էլ․ փոստ', sortable: false, value: 'email' },
                { text: 'Գրանցման ամսաթիվ', sortable: false, value: 'created_at' },
                { text: 'Գործողություն', sortable: false, value: 'iron' },
            ],
            contacts: [],
            userEmails: [],
            defaultEditData: {},
            errors: {
                defaultMessage: {
                    subject: '',
                    text: '',
                    userEmail: '',
                },
            }
        }
    },

    props: ['groupId'],

    async created() {
        await this.getGroupContacts()
        await this. getUserEmails()
    },
    watch: {
        selected: {
            async handler (val){
                if(val.length === this.contacts.contact.length){
                    this.allSelected = true
                }else{
                    this.allSelected = false
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
        async getUserEmails() {
            await axios.post(`/api/get-user-emails`)
                .then(response => {
                    this.userEmails = response.data.userEmails
                })
                .catch(e => {
                    console.log(e)
                })
        },
        selectAll(type){
            if(!type){
                this.contacts.contact.map((item, i) => {
                    if (!this.selected.includes(item.id)) {
                        this.selected.push(item.id);
                    }
                })
            }else{
                this.selected = []
            }

        },

        openEditDialog(data) {
            this.contactId = data.id
            this.defaultEditData = data
            this.editContactDialog = true
        },

        async getGroupContacts() {
            await axios.post(`/api/get-group-contacts`, {id: this.groupId})
                .then(response => {
                    this.contacts = response.data.contacts
                })
                .catch(e => {
                    console.log(e)
                })
        },

        async updateContact() {
            const formData = new FormData()
            formData.append('id', this.defaultEditData.id)
            formData.append('name', this.defaultEditData.name)
            formData.append('email', this.defaultEditData.email)

            await axios.post(`/api/update-contact`, formData)
                .then(response => {
                    this.editContactDialog = false
                    this.$notify({
                        group: 'auth',
                        type: 'success',
                        text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Օգտատերը թարմացվել է',
                        duration: 1000,
                        speed: 1000
                    })
                    this.getGroupContacts()
                })
                .catch(e => {
                    console.log(e)
                })
        },

        openDeleteDialog(id) {
            this.deleteContactDialog = true
            this.contactId = id
        },

        async deleteContact() {
            const data = {
                group_id: this.groupId,
                contact_id: this.contactId,
            }
            await axios.post(`/api/delete-contact-group`, data)
                .then(response => {
                    this.$notify({
                        group: 'auth',
                        type: 'success',
                        text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Օգտատերը Ջնջված է խմբից',
                        duration: 1000,
                        speed: 1000
                    })
                    this.getGroupContacts()
                    this.deleteContactDialog = false
                })
                .catch(e => {
                    console.log(e)
                })
        },

        openDialogSendGroupMessage() {
            this.dialog = true
        },

        async sendMessageGroup() {
            const formData = new FormData()
            if (this.files.length) {
                this.files.map((item, i) => {
                    formData.append('files[]', item)
                })
            }
            formData.append('subject', this.defaultMessage.subject)
            formData.append('text', this.defaultMessage.text)
            formData.append('contacts',  this.selected)
            formData.append('groupId',  this.groupId)
            formData.append('from',  this.defaultMessage.userEmail)

            await axios.post(`/api/send-group-message`, formData,
                {
                    headers:{
                        'Content-Type': 'multipart/form-data'
                    },
                }).then((res) => {
                this.$notify({
                    group: 'auth',
                    type: 'success',
                    text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Նամակը հաջողությամբ ուղարկված է',
                    duration: 1000,
                    speed: 1000
                })
                this.dialog = false
                this.defaultMessage.text = ''
                this.defaultMessage.subject = ''
                this.files = []
            }).catch((e) => {
                this.errors.defaultMessage = Object.assign(this.errors.defaultMessage, e.response.data.errors)
            })
        },

        openDeleteContactsDialog(id) {
            this.deleteContactsDialog = true
            this.contactId = id
        },

        async deleteSelectedContactGroup() {
            const data = {
                group_id: this.groupId,
                contact_ids: this.selected,
            }
            await axios.post(`/api/delete-selected-contact-group`, data)
                .then(response => {
                    this.$notify({
                        group: 'auth',
                        type: 'success',
                        text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Օգտատերերը Ջնջված է խմբից',
                        duration: 1000,
                        speed: 1000
                    })
                    this.getGroupContacts()
                    this.deleteContactsDialog = false
                })
                .catch(e => {
                    console.log(e)
                })
        },
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
                        transition: 0.25s ease;
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
                    .v-input--checkbox{
                        margin-top: 0 !important;
                    }
                    &:hover {
                        background: #43a0474d !important;
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
</style>
