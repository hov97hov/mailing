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
                               <div class="action-btn-content" v-if="selected.length">
                                   <v-btn
                                       @click="selectGroupDialog = true"
                                       class="send-message"
                                       dark
                                   >
                                       <span>Ավելացնել այլ խմբերում</span>
                                       <v-icon>mdi-plus</v-icon>
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
                                   <v-btn
                                       @click="addNewContactGroup(contactId)"
                                       class="send-message ml-16"
                                       color="green accent-4"
                                       dark
                                   >
                                       <span>Ավելացնել Կոնտակտ Խմբում</span>
                                       <v-icon>mdi-plus</v-icon>
                                   </v-btn>
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
                            <div class="left-section">
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
                                        v-model="defaultMessage.from"
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
                                <div class="mailing-field-content select-group">
                                    <v-btn @click="selectGroupDialog = true">
                                        Ընտրել խումբ
                                    </v-btn>
                                    <div class="groups">
                                            <span
                                                v-for="item in selectedGroupsData"
                                                :style="`border: 1px solid ${item.color}`"
                                            >
                                                {{item.name}}
                                            </span>
                                    </div>
                                </div>
                                <div class="mailing-field-content">
                                    <v-text-field
                                        v-model="defaultMessage.link"
                                        label="Ավելացնել հղում"
                                        solo
                                        hide-details="auto"
                                    ></v-text-field>
                                </div>
                                <div class="mailing-field-content select-design">
                                    <div>
                                        <v-radio-group
                                            v-model="selectDesign"
                                            column
                                        >
                                            <div class="mailing-template-content">
                                                <div class="mailing-template">
                                                    <v-radio
                                                        label="Ընտրել"
                                                        color="green"
                                                        value="template1"
                                                    ></v-radio>
                                                    <img style="width: 150px" src="/images/email/մաիլինգ-02.jpg" alt="">
                                                </div>
                                                <div class="mailing-template">
                                                    <v-radio
                                                        label="Ընտրել"
                                                        color="green"
                                                        value="template2"
                                                    ></v-radio>
                                                    <img style="width: 150px" src="/images/email/մաիլինգ-03.jpg" alt="">
                                                </div>
                                                <div class="mailing-template">
                                                    <v-radio
                                                        label="Ընտրել"
                                                        color="green"
                                                        value="template3"
                                                    ></v-radio>
                                                    <img style="width: 150px" src="/images/email/մաիլինգ-04.jpg" alt="">
                                                </div>
                                                <div class="mailing-template">
                                                    <v-radio
                                                        label="Ընտրել"
                                                        color="green"
                                                        value="template4"
                                                    ></v-radio>
                                                    <img style="width: 150px" src="/images/email/մաիլինգ-05.jpg" alt="">
                                                </div>
                                                <div class="mailing-template">
                                                    <v-radio
                                                        label="Ընտրել"
                                                        color="green"
                                                        value="template5"
                                                    ></v-radio>
                                                    <img style="width: 150px" src="/images/email/մաիլինգ-06.jpg" alt="">
                                                </div>
                                                <div class="mailing-template">
                                                    <v-radio
                                                        label="Ընտրել"
                                                        color="green"
                                                        value="template6"
                                                    ></v-radio>
                                                    <img style="width: 150px" src="/images/email/մաիլինգ-07.jpg" alt="">
                                                </div>
                                                <div class="mailing-template">
                                                    <v-radio
                                                        label="Ընտրել"
                                                        color="green"
                                                        value="template7"
                                                    ></v-radio>
                                                    <img style="width: 150px" src="/images/email/մաիլինգ-08.jpg" alt="">
                                                </div>
                                                <div class="mailing-template">
                                                    <v-radio
                                                        label="Ընտրել"
                                                        color="green"
                                                        value="template8"
                                                    ></v-radio>
                                                    <img style="width: 150px" src="/images/email/մաիլինգ-09.jpg" alt="">
                                                </div>
                                            </div>
                                        </v-radio-group>
                                    </div>
                                </div>
                            </div>
                            <div class="right-section">
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
        <!--ADD CONTACT GROUPS-->
        <v-dialog
            v-model="addNewConatctGroupDialog"
            persistent
            max-width="600px"
        >
            <v-card>
                <v-card-title>
                    <span class="text-h5">Ստեղծել խումբ</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12" sm="12" md="12">
                                <v-text-field
                                    v-model="defaultNewContactData.name"
                                    label="Անուն"
                                    required
                                    hide-details="auto"
                                    :error-messages="errors.defaultNewContactData.name"
                                    @input="checkErrors('defaultNewContactData', 'name')"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="12" md="12">
                                <v-text-field
                                    v-model="defaultNewContactData.email"
                                    label="Էլ․ փոստ"
                                    required
                                    hide-details="auto"
                                    :error-messages="errors.defaultNewContactData.email"
                                    @input="checkErrors('defaultNewContactData', 'email')"
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
                        @click="addNewConatctGroupDialog = false"
                    >
                        Չեղարկել
                    </v-btn>
                    <v-btn
                        color="green darken-1"
                        text
                        @click="createNewContactGroup"
                    >
                        Հաստատել
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!--SELECT GROUPS-->
        <v-dialog
            v-model="selectGroupDialog"
            scrollable
            max-width="300px"
        >
            <v-card>
                <v-card-title>Ընտրել խմբեր</v-card-title>
                <v-divider></v-divider>
                <v-card-text style="height: 300px;">
                    <div v-for="item in groupsData">
                        <v-checkbox
                            v-model="selectedGroups"
                            :value="item.id"
                            item-value="id"
                            :label="item.name"
                            hide-details
                            color="green accent-4"
                        />
                    </div>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-btn
                        color="blue darken-1"
                        text
                        @click="selectGroupDialog = false"
                    >
                        Չեղարկել
                    </v-btn>
                    <v-btn
                        color="blue darken-1"
                        text
                        @click="getSelectedGroups"
                    >
                        Հաստատել
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!--NOTIFICATION-->
        <loader v-if="loading" object="#03c200" color1="#ffffff" color2="#1fd13d" size="5" speed="2" bg="#343a40" objectbg="#999793" opacity="80" disableScrolling="false" name="dots"></loader>
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
            loading: false,
            selectGroupDialog: false,
            addNewConatctGroupDialog: false,
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
            selectDesign: 'template1',
            selectedGroups: [],
            defaultNewContactData: {
                name: '',
                email: '',
            },
            defaultMessage: {
                to: '',
                subject: '',
                from: '',
                text: '',
                link: '',
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
                    text: 'Նշել',
                    sortable: false,
                },
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
            selectedGroupsData: [],
            groupsData: [],
            userEmails: [],
            defaultEditData: {},
            errors: {
                defaultMessage: {
                    subject: '',
                    text: '',
                    from: '',
                },
                defaultNewContactData: {
                    name: '',
                    email: '',
                },
            }
        }
    },

    props: ['groupId'],

    async created() {
        await this.getGroupContacts()
        await this.getUserEmails()
        await this.getGroups()
    },
    methods: {
        addNewContactGroup() {
            this.addNewConatctGroupDialog = true
        },

        async getGroups() {
            await axios.post(`/api/get-groups`)
                .then(response => {
                    this.groupsData = response.data.groups
                })
                .catch(e => {
                    console.log(e)
                })
        },

        async getSelectedGroups() {
            await axios.post(`/api/get-selected-groups`, {ids: this.selectedGroups})
                .then(response => {
                    console.log(response.data.groups)
                    this.selectedGroupsData = response.data.groups
                    this.selectGroupDialog = false
                })
                .catch(e => {
                    console.log(e)
                })
        },

        async addContactGroups() {
            const data = {
                group_ids: this.selectedGroups,
                contacts: this.selected,
            }
            await axios.post(`/api/add-contacts-groups`, data)
                .then(response => {
                    this.$notify({
                        group: 'auth',
                        type: 'success',
                        text: '<i class="fa fa-check-circle" aria-hidden="true"></i>օգտատերը ավելացված է խմբում' ,
                        duration: 1000,
                        speed: 1000
                    })
                    this.getGroupContacts()
                    this.selectGroupDialog = false
                })
                .catch(e => {
                    this.errors.defaultNewContactData = Object.assign(this.errors.defaultNewContactData, e.response.data.errors)
                })
        },

        async createNewContactGroup() {
            const data = {
                group_id: this.groupId,
                name: this.defaultNewContactData.name,
                email: this.defaultNewContactData.email
            }
            await axios.post(`/api/create-new-contact-group`, data)
                .then(response => {
                    this.$notify({
                        group: 'auth',
                        type: 'success',
                        text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Նոր օգտատերը ավելացված է',
                        duration: 1000,
                        speed: 1000
                    })
                    this.selectGroupDialog = false
                    this.getGroupContacts()
                    console.log(555)
                })
                .catch(e => {
                    this.errors.defaultNewContactData = Object.assign(this.errors.defaultNewContactData, e.response.data.errors)
                })
        },

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
            this.loading = true
            const formData = new FormData()

            if (this.files.length) {
                this.files.map((item, i) => {
                    formData.append('files[]', item)
                })
            }
            this.contactsGroups = []
            if (this.selectedGroupsData.length) {
                this.selectedGroupsData.map(item => {
                    if (item.contact.length) {
                        item.contact.map(contact => {
                            this.contactsGroups.push(contact.id)
                        })
                    }
                })
            }

            formData.append('subject', this.defaultMessage.subject)
            formData.append('text', this.defaultMessage.text)
            formData.append('from',  this.defaultMessage.from)
            formData.append('groups',  this.contactsGroups)
            formData.append('groupId',  this.groupId)
            formData.append('design', this.selectDesign)
            formData.append('link', this.defaultMessage.link)
            formData.append('contacts', this.selected)

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
                this.defaultMessage.text = ''
                this.defaultMessage.subject = ''
                this.defaultMessage.from = ''
                this.defaultMessage.link = ''
                this.files = []
                this.loading = false
            }).catch((e) => {
                this.loading = false
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
    .mailing-template-content {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
    }
    .checked-group {
        display: flex;
        align-items: center;
        margin: 0 0 15px;
        .v-input--checkbox {
            margin: 0;
        }
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
        width: 100%;
        display: flex;
        background: #ffffff;
        .left-section {
            width: 50%;
            margin-right: 20px;
        }
        .right-section {
            width: 50%;
        }
    }
    .mailing-field-content {
        margin-top: 10px;
        &.select-group {
            display: flex;
            align-items: flex-start;
            .groups {
                display: flex;
                flex-wrap: wrap;
                margin-left: 10px;
                span {
                    padding: 5px;
                    border-radius: 5px;
                    margin-left: 5px;
                    margin-right: 5px;
                    margin-bottom: 5px;
                }
            }
        }
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
