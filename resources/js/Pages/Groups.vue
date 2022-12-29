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
                        <div class="action-btn-content" v-if="selectedActive.length">
                            <v-btn
                                @click="deleteSelectedGroupDialog = true"
                                color="deep-orange darken-2 mr-3"
                                dark
                                class="delete"
                            >
                                Ջնջել
                            </v-btn>
                        </div>
                        <v-btn
                            @click="addGroupsDialog = true"
                        >
                            Ավելացնել խումբ
                        </v-btn>
                    </div>
                    <div class="group-items">
                        <div
                            class="group-item" v-for="item in groupsData"
                            :style="`border: 3px solid ${item.color}`"
                        >
                            <div class="checked-group">
                                <v-checkbox
                                    v-model="selectedActive"
                                    :value="item.id"
                                    item-value="id"
                                    hide-details
                                    color="green accent-4"
                                />
                                <span>Նշել</span>
                            </div>
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
                                        class="edit-btn mr-1"
                                        @click="editGroupDialog(item)"
                                        small
                                    >
                                        <v-icon>mdi-pencil</v-icon>
                                    </v-btn>
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
                                <span>{{item.contact.length}}</span>
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
                                <v-btn
                                    @click="addNewContactGroup(item.id)"
                                    small
                                    dark
                                    color="green accent-4"
                                >
                                    Ավելացնել նոր օգտատեր խմբում
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
                                        <p>Ընտրել որ էլ․ փոստով ուղարկել նամակը</p>
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
        <!--DELETE SELECTED DIALOG-->
        <v-dialog
            v-model="deleteSelectedGroupDialog"
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
                        @click="deleteSelectedGroupDialog = false"
                    >
                        Չեղարկել
                    </v-btn>
                    <v-btn
                        color="deep-orange darken-2"
                        text
                        @click="deleteSelectedGroup"
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
                                <v-text-field
                                    v-model="defaultDataGroup.sort"
                                    label="Սորթավորել"
                                    required
                                    hide-details="auto"
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
        <!--EDIT GROUPS-->
        <v-dialog
            v-model="editGroupsDialog"
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
                                    v-model="editDefaultDataGroup.name"
                                    label="Անուն"
                                    required
                                    hide-details="auto"
                                    :error-messages="errors.editDefaultDataGroup.name"
                                    @input="checkErrors('editDefaultDataGroup', 'name')"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="12" md="12">
                                <v-text-field
                                    v-model="editDefaultDataGroup.sort"
                                    label="Սորթավորել"
                                    required
                                    type="number"
                                    hide-details="auto"
                                    :error-messages="errors.editDefaultDataGroup.sort"
                                    @input="checkErrors('editDefaultDataGroup', 'sort')"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="12" md="12">
                                <p>Ընտրել գույն</p>
                                <v-color-picker
                                    v-model="editDefaultDataGroup.color"
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
                        @click="editGroupsDialog = false"
                    >
                        Չեղարկել
                    </v-btn>
                    <v-btn
                        color="green darken-1"
                        text
                        @click="editGroup"
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
        <loader v-if="loading" object="#03c200" color1="#ffffff" color2="#1fd13d" size="5" speed="2" bg="#343a40" objectbg="#999793" opacity="80" disableScrolling="false" name="dots"></loader>

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
            selectDesign: 'template1',
            selectedGroups: [],
            selectedActive: [],
            groupImg: null,
            editGroupsDialog: false,
            loading: false,
            defaultDataGroup: {
                color: '',
                name: '',
            },
            editDefaultDataGroup: {
                color: '',
                name: '',
                sort: '',
            },
            defaultMessage: {
                to: '',
                subject: '',
                from: '',
                text: '',
                link: '',
            },
            defaultNewContactData: {
                name: '',
                email: '',
            },
            selectGroupDialog: false,
            autoUpdate: true,
            contacts: [],
            isUpdating: false,
            name: 'Midnight Crew',
            people: [
                { header: 'Օգտատերեր' },
            ],
            deleteSelectedGroupDialog: false,
            addUserGroupDialog: false,
            addNewConatctGroupDialog: false,
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
            selectedGroupsData: [],
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
                    from: '',
                },
                defaultDataGroup: {
                    color: '',
                    name: '',
                    sort: '',
                },
                defaultNewContactData: {
                    name: '',
                    email: '',
                },
                editDefaultDataGroup: {
                    color: '',
                    name: '',
                    sort: '',
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


        editGroupDialog(data) {
            this.editGroupsDialog = true
            this.editDefaultDataGroup = data
        },

        async editGroup() {
            const formData = new FormData()
            formData.append('id', this.editDefaultDataGroup.id)
            formData.append('name', this.editDefaultDataGroup.name)
            formData.append('color', this.editDefaultDataGroup.color)
            formData.append('sort', this.editDefaultDataGroup.sort)
            await axios.post(`/api/edit-group`, formData)
                .then(response => {
                    this.$notify({
                        group: 'auth',
                        type: 'success',
                        text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Խումբը թարմացվել է',
                        duration: 1000,
                        speed: 1000
                    })
                    this.getGroups()
                    this.editDefaultDataGroup.name = ''
                    this.editDefaultDataGroup.color = ''
                    this.editDefaultDataGroup.sort = ''
                    this.editDefaultDataGroup.image = ''
                    this.editGroupsDialog = false
                })
                .catch(e => {
                    this.errors.defaultDataGroup = Object.assign(this.errors.defaultDataGroup, e.response.data.errors)
                })
        },

        addNewContactGroup(id) {
            this.addNewConatctGroupDialog = true
            this.groupId = id
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
                    this.getGroups()
                    this.addNewConatctGroupDialog = false
                })
                .catch(e => {
                    this.errors.defaultNewContactData = Object.assign(this.errors.defaultNewContactData, e.response.data.errors)
                })
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

        async getSelectedGroups() {
            await axios.post(`/api/get-selected-groups`, {ids: this.selectedGroups})
                .then(response => {
                    this.selectedGroupsData = response.data.groups
                    this.selectGroupDialog = false
                })
                .catch(e => {
                    console.log(e)
                })
        },

        async getGroups() {
           await axios.post(`/api/get-groups`)
                .then(response => {
                    this.groupsData = response.data.groups
                    this.selectedActive = []
                })
                .catch(e => {
                    console.log(e)
                })
        },

        async createGroup() {
            const formData = new FormData()
            formData.append('name', this.defaultDataGroup.name)
            formData.append('color', this.defaultDataGroup.color)
            formData.append('sort', this.defaultDataGroup.sort)
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
                    this.defaultDataGroup.name = ''
                    this.defaultDataGroup.color = ''
                    this.groupImg = ''
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

        async deleteSelectedGroup() {
            await axios.post(`/api/delete-selected-group`, {ids:this.selectedActive})
                .then(response => {
                    this.$notify({
                        group: 'auth',
                        type: 'success',
                        text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Խումբը ջնջված է',
                        duration: 1000,
                        speed: 1000
                    })
                    this.selectedActive =  []
                    this.getGroups()
                    this.deleteSelectedGroupDialog = false
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
                    this.getGroups()
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
                button {
                    &.delete {
                        background: #ffffff !important;
                        border: 2px solid #F4511E;
                        color: #F4511E !important;
                        &:hover {
                            background: #F4511E !important;
                            color: #ffffff !important;
                        }
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
                        .edit-btn {
                            background: #ffffff !important;
                            border: 2px solid #FFBA00FF;
                            color: #FFBA00FF !important;
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
                            margin: 0 0 10px;
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
                                margin-bottom: 10px;
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

</style>
