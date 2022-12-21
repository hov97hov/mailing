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
                            Ավելացնել խումբ
                        </v-btn>
                    </div>
                    <div class="group-items">
                        <div class="group-item" v-for="item in groupsData" :style="`border: 3px solid ${item.color}`">
                            <div class="group-item-header">
                                <div class="group-title">
                                    <div class="group-title-image">
                                        <img :src="item.image" alt="">
                                    </div>
                                    <a :href="`/group/${item.id}`">
                                        <h3><b>{{item.name}}</b></h3>
                                    </a>
                                </div>
                                <div class="send-group-message">
                                    <v-btn
                                        class="email-btn"
                                        @click="openDialogSendGroupMessage(item.id)"
                                        small
                                    >
                                        <v-icon>mdi-email-outline</v-icon>
                                    </v-btn>
                                    <v-btn
                                        @click="deleteGroupDialog(item.id)"
                                        dark
                                        class="ml-1 delete-btn"
                                        small
                                    >
                                        <v-icon>mdi-close</v-icon>
                                    </v-btn>
                                </div>
                            </div>

                            <div class="group-count">
                                <span>Օգտատերերի քանակ</span>
                                <span>10</span>
                            </div>
                            <div class="group-btn-content">
                                <v-btn
                                    @click="addContactDialog(item.id)"
                                    small
                                    dark
                                    color="green accent-4"
                                >
                                    Ավելացնել օգտատեր խմբում
                                    <v-icon>mdi-plus</v-icon>
                                </v-btn>
                            </div>
                        </div>
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
                                    <v-select
                                        v-model="defaultMessage.userEmail"
                                        :items="userEmails"
                                        item-value="email"
                                        item-text="email"
                                        hide-details="auto"
                                        label="Ընտրեք թե որ էլ․ փոստով եք ուզում ուղարկել"
                                        :error-messages="errors.defaultMessage.userEmail"
                                        @input="checkErrors('defaultMessage', 'userEmail')"
                                    ></v-select>
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
        <!--DELETE DIALOG-->
        <v-dialog
            v-model="deleteDialog"
            persistent
            max-width="290"
        >
            <v-card>
                <v-card-title class="text-h5">
                    Ջնջել Խումբը
                </v-card-title>
                <v-card-text>Համոզված եղեք, որ ուզում եք ջնջել խումբը։</v-card-text>
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
                        @click="deleteGroup"
                    >
                        Ջնջել
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!--ADD GROUPS-->
        <v-dialog
            v-model="addGroupsDialog"
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
                                    v-model="defaultDataGroup.name"
                                    label="Անուն"
                                    required
                                    hide-details="auto"
                                    :error-messages="errors.defaultDataGroup.name"
                                    @input="checkErrors('defaultDataGroup', 'name')"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="12" md="12">
                                <v-file-input
                                    v-model="groupImg"
                                    accept="image/png, image/jpeg, image/bmp"
                                    placeholder="Pick an avatar"
                                    prepend-icon="mdi-camera"
                                    label="Ավելացնել նկար"
                                ></v-file-input>
                            </v-col>
                            <v-col cols="12" sm="12" md="12">
                                <p>Ընտրել գույն</p>
                                <v-color-picker
                                    v-model="defaultDataGroup.color"
                                    dot-size="10"
                                    mode="hexa"
                                    swatches-max-height="50"
                                ></v-color-picker>
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
                        @click="createGroup"
                    >
                        Հաստատել
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!--ADD USER GROUP-->
        <v-dialog
            v-model="addUserGroupDialog"
            persistent
            max-width="600px"
        >
            <v-card>
                <v-card-title>
                    <span class="text-h5">Ավելացնել օգտատեր տվյալ խմբում</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-autocomplete
                            v-model="contacts"
                            :disabled="isUpdating"
                            :items="people"
                            filled
                            chips
                            color="blue-grey lighten-2"
                            label="Ընտրել"
                            item-text="name"
                            item-value="id"
                            multiple
                        >
                            <template v-slot:selection="data">
                                <v-chip
                                    v-bind="data.attrs"
                                    :input-value="data.selected"
                                    close
                                    @click="data.select"
                                    @click:close="remove(data.item)"
                                >
                                    <v-avatar left>
                                        <v-img src="/images/user/user.png"></v-img>
                                    </v-avatar>
                                    {{ data.item.name }}
                                </v-chip>
                            </template>
                            <template v-slot:item="data">
                                <template v-if="typeof data.item !== 'object'">
                                    <v-list-item-content v-text="data.item"></v-list-item-content>
                                </template>
                                <template v-else>
                                    <v-list-item-avatar>
                                        <img style="object-fit: cover" src="/images/user/user.png">
                                    </v-list-item-avatar>
                                    <v-list-item-content>
                                        <v-list-item-title v-html="data.item.name"></v-list-item-title>
                                    </v-list-item-content>
                                </template>
                            </template>
                        </v-autocomplete>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="red darken-1"
                        text
                        @click="addUserGroupDialog = false"
                    >
                        Չեղարկել
                    </v-btn>
                    <v-btn
                        color="green darken-1"
                        text
                        @click="addContactGroup"
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
            groupImg: null,
            defaultDataGroup: {
                color: '',
                name: '',
            },
            defaultMessage: {
                subject: '',
                userEmail: '',
                text: '',
            },
            autoUpdate: true,
            contacts: [],
            isUpdating: false,
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
            groupsData: [],
            userEmails: [],
            search: '',
            singleSelect: false,
            selected: [],
            contactsGroups: [],
            breadcrumbs: [
                {
                    text: 'Գլխավոր',
                    disabled: false,
                    href: '/',
                },
                {
                    text: 'Խմբեր',
                    disabled: true,
                    href: '',
                },
            ],
            errors: {
                defaultMessage: {
                    subject: '',
                    text: '',
                    userEmail: '',
                },
                defaultDataGroup: {
                    color: '',
                    name: '',
                },
            }
        }
    },
    async created() {
        await this.getUserEmails()
        await this.getGroups()
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
        remove (item) {
            const index = this.contacts.indexOf(item.id)
            if (index >= 0) this.contacts.splice(index, 1)
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

        async createGroup() {
            const formData = new FormData()
            formData.append('name', this.defaultDataGroup.name)
            formData.append('color', this.defaultDataGroup.color)
            formData.append('image', this.groupImg)
            await axios.post(`/api/create-group`, formData)
                .then(response => {
                    this.$notify({
                        group: 'auth',
                        type: 'success',
                        text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Խումբը ավելացվել է',
                        duration: 1000,
                        speed: 1000
                    })
                    this.getGroups()
                    this.addGroupsDialog = false
                })
                .catch(e => {
                    this.errors.defaultDataGroup = Object.assign(this.errors.defaultDataGroup, e.response.data.errors)
                })
        },

        deleteGroupDialog(id) {
            this.groupId = id
            this.deleteDialog = true
        },

        async deleteGroup() {
            await axios.post(`/api/delete-group`, {id:this.groupId})
                .then(response => {
                    this.$notify({
                        group: 'auth',
                        type: 'success',
                        text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Խումբը ջնջված է',
                        duration: 1000,
                        speed: 1000
                    })
                    this.getGroups()
                    this.deleteDialog = false
                })
                .catch(e => {
                    console.log(e)
                })
        },

        async getAllContacts() {
            await axios.get(`/api/get-contacts`)
                .then(response => {
                    this.people = response.data.contacts
                })
                .catch(e => {
                    console.log(e)
                })
        },

        async addContactGroup() {
            const formData = new FormData()
            formData.append('contacts', this.contacts)
            formData.append('group_id', this.groupId)

            await axios.post(`/api/add-contact-group`, formData)
                .then(response => {
                    this.$notify({
                        group: 'auth',
                        type: 'success',
                        text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Օգտատերերը ավելացվել են Խմբում',
                        duration: 1000,
                        speed: 1000
                    })
                    this.addUserGroupDialog = false
                })
                .catch(e => {
                    console.log(e)
                })
        },

        addContactDialog(id) {
            this.getAllContacts()
            this.groupId = id
            this.addUserGroupDialog = true
        },

        openDialogSendGroupMessage(id) {
            this.dialog = true
            this.groupId = id
            this.getGroupContacts(id)
        },

        async getGroupContacts(id) {
            await axios.post(`/api/get-group-contacts`, {id:id})
                .then(response => {
                    this.contactsGroups = []
                    response.data.contacts.contact.map(item => {
                        this.contactsGroups.push(item.id)
                    })
                })
                .catch(e => {
                    console.log(e)
                })
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
            formData.append('contacts',  this.contactsGroups)
            formData.append('groupId',  this.groupId)
            formData.append('from',  this.userEmail)

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
