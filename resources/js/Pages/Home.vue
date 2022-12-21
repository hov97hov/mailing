<template>
    <v-app>
        <Header/>
        <div class="mailing-wrapper">
            <div>
                <Navbar/>
            </div>
            <div class="container">
                <div class="mailing-content">
                    <div class="nailing-left-menu">
                        <div class="menu-header">
                            <div class="write-message">
                                <v-btn
                                    @click="dialog = true"
                                    block
                                    elevation="2"
                                    dark
                                    color="green accent-4"
                                >
                                    <v-icon>mdi-email-edit-outline</v-icon>
                                    Գրել
                                </v-btn>
                            </div>
                            <div class="refresh">
                                <v-btn
                                    block
                                    color="green accent-4"
                                    elevation="2"
                                    @click="updateList"
                                    dark
                                >
                                    <v-icon>mdi-refresh</v-icon>
                                </v-btn>
                            </div>
                        </div>
                        <div class="menu">
                            <ul>
                                <li @click="openAllMessages">
                                    <span><i class="fa fa-envelope-o" aria-hidden="true"></i></span>
                                    <span>Նամակներ</span>
                                </li>
                                <li @click="openArchivedMessages">
                                      <span><i class="fa fa-file-archive-o" aria-hidden="true"></i></span>
                                      <span>Արխիվ</span>
                                </li>
                                <li @click="openNoRegisterContactMessages">
                                    <span><i class="fa fa-file-archive-o" aria-hidden="true"></i></span>
                                    <span>Չգրանցված օգտատերերի նամակներ</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="mailing-right-list">
                        <div class="right-list-action">
                           <div class="action-btn-content" v-if="selectedActive.length || selectedArchive.length">
                               <v-btn
                                   @click="openDeleteContactsDialog"
                                   color="deep-orange darken-2"
                                   dark
                               >
                                   Ջնջել
                               </v-btn>
                           </div>
                        </div>
                        <v-card v-if="allMessages">
                            <v-card-title>
                                <p class="table-title">Բոլոր նամակները</p>
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
                                :items="allMessagesData"
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
                                                v-model="selectedActive"
                                                :value="item.id"
                                                item-value="id"
                                                hide-details
                                                color="green accent-4"
                                            />
                                        </td>
                                        <td @click="openDialog(item.id)">
                                            <b>
                                                <div v-html="item.message"></div>
                                            </b>
                                        </td>
                                        <td>
                                            {{item.contact.name ? item.contact.name : ''}}
                                        </td>
                                        <td>
                                            {{item.contact.email}}
                                        </td>
                                        <td>
                                            <div style="margin-bottom: 5px" v-for="group in item.contact.group">
                                                <span :style="`color:${group.color}`">{{group.name}}</span>
                                            </div>
                                        </td>
                                        <td>
                                            {{item.created_at}}
                                        </td>
                                        <td>
                                            <div class="table-action">
                                                <div class="action-item">
                                                    <v-btn @click="addArchiveDialog(item.id)">
                                                        <v-icon>mdi-package-up</v-icon>
                                                    </v-btn>
                                                </div>
                                                <div class="action-item archive">
                                                    <v-btn @click="deleteMessageDialog(item.id)">
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
                        <v-card v-if="archive">
                            <v-card-title>
                                <p class="table-title">Արխիվ</p>
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
                                :items="archiveMessages"
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
                                                v-model="selectedArchive"
                                                :value="item.id"
                                                item-value="id"
                                                hide-details
                                                color="green accent-4"
                                            />
                                        </td>
                                        <td @click="openDialog(item.id)">
                                            <b>
                                                <div v-html="item.message"></div>
                                            </b>
                                        </td>
                                        <td>
                                            {{item.contact.name ? item.contact.name : ''}}
                                        </td>
                                        <td>
                                            {{item.contact.email}}
                                        </td>
                                        <td>
                                            <div style="margin-bottom: 5px" v-for="group in item.contact.group">
                                                <span :style="`color:${group.color}`">{{group.name}}</span>
                                            </div>
                                        </td>
                                        <td>
                                            {{item.created_at}}
                                        </td>
                                        <td>
                                            <div class="table-action">
                                                <div class="action-item">
                                                    <v-btn @click="addActiveDialog(item.id)">
                                                        <v-icon>mdi-arrow-up-bold-circle</v-icon>
                                                    </v-btn>
                                                </div>
                                                <div class="action-item archive">
                                                    <v-btn @click="deleteMessageDialog(item.id)">
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
                        <v-card v-if="noRegisterMessage">
                            <v-card-title>
                                <p class="table-title">Չգրանցված օգտատերերի Նամակներ</p>
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
                                :headers="headersNoRegisterData"
                                :items="noRegisterContactData"
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
                                                v-model="selectedNoRegisterMessage"
                                                :value="item.id"
                                                item-value="id"
                                                hide-details
                                                color="green accent-4"
                                            />
                                        </td>
                                        <td @click="openDialog(item.id)">
                                            <b>
                                                <div v-html="item.message"></div>
                                            </b>
                                        </td>
                                        <td>
                                            {{item.subject}}
                                        </td>
                                        <td>
                                            {{item.email}}
                                        </td>
                                        <td>
                                            {{item.created_at}}
                                        </td>
                                        <td>
                                            <div class="table-action">
                                                <div class="action-item archive">
                                                    <v-btn @click="deleteMessageDialog(item.id)">
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
                                    v-model="defaultMessage.to"
                                    label="ՈՒմ"
                                    hide-details="auto"
                                    :error-messages="errors.defaultMessage.to"
                                    @input="checkErrors('defaultMessage', 'to')"
                                ></v-text-field>
                            </div>
                            <div class="mailing-field-content">
                                <v-text-field
                                    v-model="defaultMessage.subject"
                                    label="Թեմա"
                                    hide-details="auto"
                                    :error-messages="errors.defaultMessage.subject"
                                    @input="checkErrors('defaultMessage', 'subject')"
                                ></v-text-field>
                            </div>
                            <div class="mailing-field-content">
                                <div class="mailing-field-content">
                                    <v-select
                                        v-model="defaultMessage.userEmail"
                                        :items="userEmails"
                                        item-value="email"
                                        item-text="email"
                                        label="Ընտրեք թե որ էլ․ փոստով եք ուզում ուղարկել"
                                        :error-messages="errors.defaultMessage.userEmail"
                                        @input="checkErrors('defaultMessage', 'userEmail')"
                                    ></v-select>
                                </div>
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
                                    hide-details="auto"
                                    :error-messages="errors.defaultMessage.text"
                                    @input="checkErrors('defaultMessage', 'text')"
                                >
                                </vue-editor>
                            </div>
                            <div class="mailing-button-field-content">
                                <v-btn
                                    @click="sendMessage"
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
        <!--DELETE DIALOG-->
        <v-dialog
            v-model="deleteDialog"
            persistent
            max-width="290"
        >
            <v-card>
                <v-card-title class="text-h5">
                    Ջնջել նամակները
                </v-card-title>
                <v-card-text>Համոզված եղեք, որ ուզում եք ջնջել նամակները։</v-card-text>
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
                        @click="deleteDialog = false"
                    >
                        Ջնջել
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!--DELETE MESSAGE-->
        <v-dialog
            v-model="deleteMessage"
            persistent
            max-width="290"
        >
            <v-card>
                <v-card-title class="text-h5">
                    Ջնջել նամակը
                </v-card-title>
                <v-card-text>Համոզված եղեք, որ ուզում եք ջնջել նամակը։</v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="green accent-4"
                        text
                        @click="deleteMessage = false"
                    >
                        Չեղարկել
                    </v-btn>
                    <v-btn
                        color="deep-orange darken-2"
                        text
                        @click="removeMessage"
                    >
                        Ջնջել
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!--ADD ARCHIVE-->
        <v-dialog
            v-model="addMessageArchive"
            persistent
            max-width="290"
        >
            <v-card>
                <v-card-title class="text-h5">
                    Ավելացնել արխիվ
                </v-card-title>
                <v-card-text>Համոզված եղեք, որ ուզում եք ավելացնել արխիվում։</v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="red accent-4"
                        text
                        @click="addMessageArchive = false"
                    >
                        Չեղարկել
                    </v-btn>
                    <v-btn
                        color="green darken-2"
                        text
                        @click="addArchive"
                    >
                        Ավելացնել
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!--ADD ACTIVE-->
        <v-dialog
            v-model="addActiveMessage"
            persistent
            max-width="290"
        >
            <v-card>
                <v-card-title class="text-h5">
                   Հանել արխիվից
                </v-card-title>
                <v-card-text>Համոզված եղեք, որ ուզում եք հանել արխիվից։</v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="red accent-4"
                        text
                        @click="addActiveMessage = false"
                    >
                        Չեղարկել
                    </v-btn>
                    <v-btn
                        color="green darken-2"
                        text
                        @click="addActive"
                    >
                        Ավելացնել
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!--MESSAGE-->
        <v-dialog
            v-model="messageDialog"
            width="600px"
        >
            <v-card>
                <v-card-title>
                    <span class="text-h5">Ուղարկված նամակը</span>
                </v-card-title>
                <v-card-text>
                    <div class="modal-top">
                        Թեմա - {{oneMessage.subject}}
                    </div>
                    <div class="modal-messages" v-html="oneMessage.message"></div>
                    <div class="modal-files">
                        <div
                            v-if="oneMessage.file.length"
                            v-for="item in oneMessage.file"
                        >
                            <a :href="item.file_path" download="">{{item.file_name}}</a>
                        </div>
                    </div>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="green darken-1"
                        text
                        @click="messageDialog = false"
                    >
                        Փակել
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!--DELETE MESSAGES-->
        <v-dialog
            v-model="deleteContactsDialog"
            persistent
            max-width="290"
        >
            <v-card>
                <v-card-title class="text-h5">
                    Ջնջել օգտատիրոջը խմբից
                </v-card-title>
                <v-card-text>Համոզված եղեք, որ ուզում եք ջնջել նամակը։</v-card-text>
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
                        @click="deleteSelectedMessage"
                    >
                        Ջնջել
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!--ADD ARCHIVED-->
        <v-dialog
            v-model="selectedArchiveDialog"
            persistent
            max-width="290"
        >
            <v-card>
                <v-card-title class="text-h5">
                    Ավելացնել արխիվ
                </v-card-title>
                <v-card-text>Համոզված եղեք, որ ուզում եք ավելացնել արխիվում։</v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="red accent-4"
                        text
                        @click="selectedArchiveDialog = false"
                    >
                        Չեղարկել
                    </v-btn>
                    <v-btn
                        color="green darken-2"
                        text
                        @click="addSelectedArchived"
                    >
                        Ավելացնել
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
            userEmails: [],
            selectedArchiveDialog: false,
            deleteContactsDialog: false,
            addActiveMessage: false,
            messageDialog: false,
            deleteMessage: false,
            addMessageArchive: false,
            allMessages: true,
            allSelectedActive: false,
            noRegisterContactDataSelected: false,
            archive: false,
            dialog: false,
            deleteDialog: false,
            notifications: false,
            sound: true,
            widgets: false,
            userData: [],
            search: '',
            singleSelect: false,
            allSelectedarchiveMessages: false,
            noRegisterMessage: false,
            selectedActive: [],
            selectedNoRegisterMessage: [],
            emails: [],
            files: [],
            selectedArchive: [],
            allMessagesData: [],
            archiveMessages: [],
            noRegisterContactData: [],
            oneMessage: [],
            defaultMessage: {
                to: '',
                userEmail: '',
                subject: '',
                text: '',
            },
            headers: [
                { text: 'Նշել' },
                {
                    text: 'Նամակ',
                    align: 'start',
                    sortable: true,
                    value: 'message',
                },
                { text: 'Անուն Ազգանուն', value: 'contact.name' },
                { text: 'Էլ․ փոստ', value: 'contact.email' },
                { text: 'Խումբ', value: 'contact.group' },
                { text: 'Ամսաթիվ', value: 'created_at' },
                { text: 'Գործողություններ', value: 'iron' },
            ],
            headersNoRegisterData: [
                { text: 'Նշել' },
                {
                    text: 'Նամակ',
                    align: 'start',
                    sortable: true,
                    value: 'message',
                },
                { text: 'Թեմա', value: 'subject' },
                { text: 'Էլ․ փոստ', value: 'email' },
                { text: 'Ամսաթիվ', value: 'created_at' },
                { text: 'Գործողություններ', value: 'iron' },
            ],

            errors: {
                defaultMessage: {
                    to: '',
                    userEmail: '',
                    subject: '',
                    text: '',
                },
            }
        }
    },

    watch: {
        selectedActive: {
            async handler (val){
                if(val.length === this.allMessagesData.length){
                    this.allSelectedActive = true
                }else{
                    this.allSelectedActive = false
                }
            }
        },
        selectedArchive: {
            async handler (val){
                if(val.length === this.archiveMessages.length){
                    this.allSelectedarchiveMessages = true
                }else{
                    this.allSelectedarchiveMessages = false
                }
            }
        },
        selectedNoRegisterMessage: {
            async handler (val){
                if(val.length === this.noRegisterContactData.length) {
                    this.noRegisterContactDataSelected = true
                } else{
                    this.noRegisterContactDataSelected = false
                }
            }
        },
    },

    async created() {
        await this.getAllMessages()
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
        selectAllActive(type){
            if(!type){
                this.allMessagesData.map((item, i) => {
                    if (!this.selectedActive.includes(item.id)) {
                        this.selectedActive.push(item.id);
                    }
                })
            }else{
                this.selectedActive = []
            }

        },

        selectAllArchive(type){
            if(!type){
                this.archiveMessages.map((item, i) => {
                    if (!this.selectedArchive.includes(item.id)) {
                        this.selectedArchive.push(item.id);
                    }
                })
            }else{
                this.selectedArchive = []
            }

        },

        selectAllNoRegisterMessage(type){
            if(!type){
                this.noRegisterContactData.map((item, i) => {
                    if (!this.selectedNoRegisterMessage.includes(item.id)) {
                        this.selectedNoRegisterMessage.push(item.id);
                    }
                })
            }else{
                this.selectedNoRegisterMessage = []
            }

        },

        openDialog(id){
            this.messageDialog = true
            this.messageId = id
            this.getMessage()
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

        async getMessage() {
            await axios.post(`/api/get-message`, {id: this.messageId})
                .then(response => {
                    this.oneMessage = response.data.oneMessage
                })
                .catch(e => {
                    console.log(e)
                })
        },

        openAllMessages () {
            this.allMessages = true
            this.archive = false
            this.noRegisterMessage = false
        },

        openArchivedMessages () {
            this.getArchiveMessage()
            this.archive = true
            this.allMessages = false
            this.noRegisterMessage = false
        },

        openNoRegisterContactMessages() {
            this.getNoRegisterContactData()
            this.archive = false
            this.allMessages = false
            this.noRegisterMessage =true
        },

        async getNoRegisterContactData() {
            await axios.get(`/api/get-no-register-message-contact`)
                .then(response => {
                    this.noRegisterContactData = response.data.noRegisterContactData
                })
                .catch(e => {
                    console.log(e)
                })
        },

        async getAllMessages() {
           await axios.get(`/api/get-all-messages`)
                .then(response => {
                    this.allMessagesData = response.data.allMessages
                })
                .catch(e => {
                    console.log(e)
                })
        },

        updateList() {
            this.getAllMessages()
        },

        addArchiveDialog(id) {
            this.messageId = id
            this.addMessageArchive = true
        },

        async addArchive() {
            const formData = new FormData()
            formData.append('id', this.messageId)
            formData.append('archived', 1)
            await axios.post(`/api/add-archive-message`, formData)
                .then(response => {
                    this.$notify({
                        group: 'auth',
                        type: 'success',
                        text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Նամակը ավելացվել է արխիվում',
                        duration: 1000,
                        speed: 1000
                    })
                    this.addMessageArchive = false
                    this.getAllMessages()
                    this.getArchiveMessage()
                })
                .catch(e => {
                    console.log(e)
                })
        },

        async getArchiveMessage() {
            await axios.get(`/api/get-archive-message`)
                .then(response => {
                    this.archiveMessages = response.data.archiveMessages
                    this.getAllMessages()
                })
                .catch(e => {
                    console.log(e)
                })
        },

        addActiveDialog(id) {
            this.messageId = id
            this.addActiveMessage = true
        },

        async addActive() {
            const formData = new FormData()
            formData.append('id', this.messageId)
            formData.append('archived', 0)
            await axios.post(`/api/add-archive-message`, formData)
                .then(response => {
                    this.$notify({
                        group: 'auth',
                        type: 'success',
                        text: '<i class="fa fa-check-circle" aria-hidden="true"></i> նամակը հեռացվել է արխիվից',
                        duration: 1000,
                        speed: 1000
                    })
                    this.addActiveMessage = false
                    this.getAllMessages()
                    this.getArchiveMessage()
                })
                .catch(e => {
                    console.log(e)
                })
        },

        deleteMessageDialog(id) {
            this.deleteMessage = true
            this.messageId = id
        },

        async removeMessage() {
            await axios.post(`/api/delete-message`, {id:this.messageId})
                .then(response => {
                    this.$notify({
                        group: 'auth',
                        type: 'success',
                        text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Նամակը ջնջված է',
                        duration: 1000,
                        speed: 1000
                    })
                    this.deleteMessage = false
                    this.getNoRegisterContactData()
                    this.getAllMessages()
                    this.getArchiveMessage()
                })
                .catch(e => {
                    console.log(e)
                })
        },

        openDeleteContactsDialog() {
            this.deleteContactsDialog = true
        },

        async deleteSelectedMessage() {
            const data = {
                message_ids: this.selectedActive.length ? this.selectedActive : this.selectedArchive,
            }
            await axios.post(`/api/delete-selected-messages`, data)
                .then(response => {
                    this.$notify({
                        group: 'auth',
                        type: 'success',
                        text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Նամակը Ջնջված է',
                        duration: 1000,
                        speed: 1000
                    })
                    this.getAllMessages()
                    this.getArchiveMessage()
                    this.deleteContactsDialog = false
                })
                .catch(e => {
                    console.log(e)
                })
        },

        openSelectedArchivedDialog() {
            this.selectedArchiveDialog = true
        },

        async addSelectedArchived() {
            const data = {
                message_ids: this.selectedActive.length ? this.selectedActive : this.selectedArchive,
            }
            await axios.post(`/api/add-archive-selected-message`, data)
                .then(response => {
                    this.$notify({
                        group: 'auth',
                        type: 'success',
                        text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Նամակները ավելացված է արխիվում',
                        duration: 1000,
                        speed: 1000
                    })
                    this.getAllMessages()
                    this.selectedArchiveDialog = false
                })
                .catch(e => {
                    console.log(e)
                })
        },

        async sendMessage() {
            const formData = new FormData()

            if (this.files.length) {
                this.files.map((item, i) => {
                    formData.append('files[]', item)
                })
            }

            formData.append('to', this.defaultMessage.to)
            formData.append('subject', this.defaultMessage.subject)
            formData.append('from', this.defaultMessage.userEmail)
            formData.append('text', this.defaultMessage.text)

            await axios.post(`/api/send-message`, formData,
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
                this.defaultMessage.to = ''
                this.defaultMessage.text = ''
                this.defaultMessage.subject = ''
                this.files = []
            }).catch((e) => {
                this.errors.defaultMessage = Object.assign(this.errors.defaultMessage, e.response.data.errors)
            })
        },
    }
}
</script>

<style scoped lang="scss">
    .mailing-wrapper {
        display: flex;
        .mailing-content {
            display: flex;
            margin: 50px 0;
            .nailing-left-menu {
                margin-right: 20px;
                width: 350px;
                .menu-header {
                    margin: 0 0 15px;
                    button {
                        width: 100%;
                        height: 36px;
                        border: 1px solid #ffce0b;
                        border-radius: 3px;
                        font-size: 16px;
                    }
                    display: flex;
                    .write-message {
                        button {
                            text-transform: capitalize;
                        }
                       span {
                          i {
                                margin-right: 5px;
                           }
                       }
                        width: 80%;
                        margin-right: 10px;
                    }
                    .refresh {
                        width: 20%;
                    }
                }
                .menu {
                    ul {
                        li {
                            span {
                                &:first-child {
                                    margin-right: 5px;
                                }
                            }
                            padding: 5px 8px;
                            background: #E8EBEC;
                            margin: 0 0 5px;
                            border-radius: 3px;
                            cursor: pointer;
                            &:hover {
                                background: #D1D3D3FF;
                            }
                            span {
                                cursor: pointer;
                            }
                            a {
                                display: block;
                                color: #000000;
                                span {
                                    &:first-child {
                                        margin-right: 5px;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            .mailing-right-list {
                .right-list-action {
                    display: flex;
                    justify-content: flex-end;
                    margin: 0 0 20px;
                    .action-btn-content {
                        margin-left: 10px;
                        button {
                            text-transform: capitalize;
                        }
                    }
                }
                .table-title {
                    margin-right: 20px;
                    margin-bottom: 0;
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
                    border: 2px solid #26A69A;
                    color: #26A69A !important;
                    &:hover {
                        background: #26A69A !important;
                        color: #ffffff !important;
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

    //maodal message
    .modal-top {
        padding-bottom: 10px;
        margin-bottom: 20px;
        border-bottom: 1px solid #dddddd;
    }
    .modal-files {
        border-top: 1px solid #dddddd;
        padding-top: 10px;
    }
    .modal-files > div {
        margin-bottom: 10px;
        a {
            padding: 5px 10px;
            background: #00C853;
            color: #ffffff !important;
            border-radius: 5px;
        }
    }
</style>
