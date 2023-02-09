<template>
    <v-app>
        <Header/>
        <div class="mailing-wrapper">
            <div class="mailing-content">
                <div class="mailing-left-menu">
                    <div class="menu-header">
                        <div class="write-message">
                            <v-btn
                                @click="openMessageBox"
                                block
                                elevation="2"
                                dark
                                class="rounded-lg"
                                color="#253266"
                            >
                                Գրել
                            </v-btn>
                        </div>
                    </div>
                    <div class="menu">
                        <ul>
                            <li>
                                <a href="/?messages">
                                    <img src="/images/send.png">
                                    <span>Նամակներ</span>
                                </a>
                            </li>
                            <li>
                                <a href="/groups">
                                    <img src="/images/category.png">
                                    <span>Կատեգորիաներ</span>
                                </a>
                            </li>
                            <li class="emails">
                                <a href="/emails">
                                    <img src="/images/mail.png">
                                    <span>Էլ․ փոստեր</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="mailing-right-list">
                    <div class="top-section">
                        <span>ԷԼ․ ՀԱՍՑԵՆԵՐԻ ՑՈՒՑԱԿ</span>
                    </div>
                    <div class="mailing-right-list-content">
                        <div @click="closePage" class="close-page">
                            <v-icon color="#253266">mdi-close</v-icon>
                        </div>
                        <div class="create-category">
                            <div class="create-category-content">
                                <div>
                                    <v-text-field
                                        v-model="defaultEmailData.email"
                                        placeholder="email"
                                        filled
                                        rounded
                                        dense
                                        :error-messages="errors.defaultEmailData.email"
                                        @input="checkErrors('defaultEmailData', 'email')"
                                    ></v-text-field>
                                </div>
                                <div>
                                    <v-text-field
                                        v-model="defaultEmailData.host"
                                        placeholder="host"
                                        filled
                                        rounded
                                        dense
                                        :error-messages="errors.defaultEmailData.host"
                                        @input="checkErrors('defaultEmailData', 'host')"
                                    ></v-text-field>
                                </div>
                                <div>
                                    <v-text-field
                                        v-model="defaultEmailData.port"
                                        placeholder="port"
                                        filled
                                        rounded
                                        dense
                                        :error-messages="errors.defaultEmailData.port"
                                        @input="checkErrors('defaultEmailData', 'port')"
                                    ></v-text-field>
                                </div>
                                <div>
                                    <v-text-field
                                        v-model="defaultEmailData.from"
                                        placeholder="from"
                                        filled
                                        rounded
                                        dense
                                        :error-messages="errors.defaultEmailData.from"
                                        @input="checkErrors('defaultEmailData', 'from')"
                                    ></v-text-field>
                                </div>
                                <div>
                                    <v-text-field
                                        v-model="defaultEmailData.encryption"
                                        placeholder="encryption"
                                        filled
                                        rounded
                                        dense
                                        :error-messages="errors.defaultEmailData.encryption"
                                        @input="checkErrors('defaultEmailData', 'encryption')"
                                    ></v-text-field>
                                </div>
                                <div>
                                    <v-text-field
                                        v-model="defaultEmailData.header"
                                        placeholder="header"
                                        filled
                                        rounded
                                        dense
                                        :error-messages="errors.defaultEmailData.header"
                                        @input="checkErrors('defaultEmailData', 'header')"
                                    ></v-text-field>
                                </div>
                                <div>
                                    <v-text-field
                                        v-model="defaultEmailData.username"
                                        placeholder="username"
                                        filled
                                        rounded
                                        dense
                                        :error-messages="errors.defaultEmailData.username"
                                        @input="checkErrors('defaultEmailData', 'username')"
                                    ></v-text-field>
                                </div>
                                <div>
                                    <v-text-field
                                        v-model="defaultEmailData.password"
                                        placeholder="password"
                                        filled
                                        rounded
                                        dense
                                        :error-messages="errors.defaultEmailData.password"
                                        @input="checkErrors('defaultEmailData', 'password')"
                                    ></v-text-field>
                                </div>
                                <div class="btn-content">
                                    <v-btn @click="createMail">Հաստատել</v-btn>
                                </div>
                            </div>
                        </div>
                        <div class="category-messages-list">
                            <div class="header">
                                <div>Էլ․ փոստ</div>
                                <div></div>
                            </div>
                            <div class="items">
                                <div class="item" v-for="item in emails">
                                    <div class="ids">{{item.id}}</div>
                                    <div class="last-block">
                                        <div class="email">{{item.email}}</div>
                                        <div class="actions">
                                            <div>
                                                <img @click="editEmailModal(item)" src="/images/pencil.png" alt="">
                                            </div>
                                            <div>
                                                <img @click="openDeleteEmailDialog(item.id)" src="/images/removeIocn.png" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <loader v-if="loading" object="#343a40" color1="#ffffff" color2="#253266" size="5" speed="2" bg="#343a40" objectbg="#999793" opacity="80" disableScrolling="false" name="dots"></loader>
        <!--NOTIFICATION-->
        <notifications group="auth"/>
        <!--Delete category-->
        <v-dialog
            v-model="deleteEmailDialog"
            max-width="290"
        >
            <v-card>
                <v-card-title class="text-h5">
                    <span style="font-size: 16px; color: #253266">Վստահ եք որ ուզում եք ջնջել?</span>
                </v-card-title>

                <v-card-actions>
                    <v-spacer></v-spacer>

                    <v-btn
                        color="red"
                        text
                        @click="deleteEmailDialog = false"
                    >
                        Չեղարկել
                    </v-btn>

                    <v-btn
                        color="#253266"
                        text
                        @click="deleteEmail"
                    >
                        Հաստատել
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!--UPDATE EMAIL-->
        <v-dialog
            v-model="editEmailDialog"
            width="500"
            persistent
        >
            <v-card>
                <v-card-title class="text-h5 grey lighten-2">
                    Ավելացնել նոր Էլ․ հասցե
                </v-card-title>

                <v-card-text>
                    <div class="text-filed-content">
                        <div>
                            <v-text-field
                                v-model="editEmailData.email"
                                label="email"
                                filled
                                rounded
                                dense
                                :error-messages="errors.editEmailData.email"
                                @input="checkErrors('editEmailData', 'email')"
                            ></v-text-field>
                        </div>
                    </div>
                    <div class="text-filed-content">
                        <div>
                            <v-text-field
                                v-model="editEmailData.host"
                                label="host"
                                filled
                                rounded
                                dense
                                :error-messages="errors.editEmailData.host"
                                @input="checkErrors('editEmailData', 'host')"
                            ></v-text-field>
                        </div>
                    </div>
                    <div class="text-filed-content">
                        <div>
                            <v-text-field
                                v-model="editEmailData.port"
                                label="port"
                                filled
                                rounded
                                dense
                                :error-messages="errors.editEmailData.port"
                                @input="checkErrors('editEmailData', 'port')"
                            ></v-text-field>
                        </div>
                    </div>
                    <div class="text-filed-content">
                        <div>
                            <v-text-field
                                v-model="editEmailData.from"
                                label="from"
                                filled
                                rounded
                                dense
                                :error-messages="errors.editEmailData.from"
                                @input="checkErrors('editEmailData', 'from')"
                            ></v-text-field>
                        </div>
                    </div>
                    <div class="text-filed-content">
                        <div>
                            <v-text-field
                                v-model="editEmailData.encryption"
                                label="encryption"
                                filled
                                rounded
                                dense
                                :error-messages="errors.editEmailData.encryption"
                                @input="checkErrors('editEmailData', 'encryption')"
                            ></v-text-field>
                        </div>
                    </div>
                    <div class="text-filed-content">
                        <div>
                            <v-text-field
                                v-model="editEmailData.header"
                                label="header"
                                filled
                                rounded
                                dense
                                :error-messages="errors.editEmailData.header"
                                @input="checkErrors('editEmailData', 'header')"
                            ></v-text-field>
                        </div>

                        <div class="text-filed-content">
                            <div>
                                <v-text-field
                                    v-model="editEmailData.username"
                                    label="username"
                                    filled
                                    rounded
                                    dense
                                    :error-messages="errors.editEmailData.username"
                                    @input="checkErrors('editEmailData', 'username')"
                                ></v-text-field>
                            </div>
                        </div>
                        <div class="text-filed-content">
                                <div>
                                    <v-text-field
                                        v-model="editEmailData.password"
                                        label="password"
                                        filled
                                        rounded
                                        dense
                                        :error-messages="errors.editEmailData.password"
                                        @input="checkErrors('editEmailData', 'password')"
                                    ></v-text-field>
                                </div>
                            </div>
                        </div>
                </v-card-text>

                <v-divider></v-divider>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="red"
                        text
                        @click="editEmailDialog = false"
                    >
                        Չեղարկել
                    </v-btn>
                    <v-btn
                        color="#253266"
                        text
                        @click="updateEmail"
                    >
                        Հաստատել
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-app>
</template>

<script>
import axios from "axios";
import Header from '../../../resources/js/Components/Header'
import { VueEditor } from "vue2-editor";

export default {
    name: "home",
    components: {
        Header,
        VueEditor
    },
    data: () => {
        return {
            loading: false,
            isActive: false,
            dialog: false,
            deleteEmailDialog: false,
            editEmailDialog: false,
            search: '',
            emailId: '',
            categories: [],
            emails: [],
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
            editEmailData: {
                email: '',
                host: '',
                from: '',
                header: '',
                port: '',
                encryption: '',
                username: '',
                password: '',
            },
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
                editEmailData: {
                    email: '',
                    host: '',
                    from: '',
                    header: '',
                    port: '',
                    encryption: '',
                    username: '',
                    password: '',
                },
            }
        }
    },
    async created() {
        await this.getEmails()
    },

    methods: {
        openMessageBox() {
          location.href = '/'
        },

        checkErrors(obj, field) {
            if (obj) {
                this.errors[obj][field] = ''
            } else {
                this.errors[field] = ''
            }
        },

        closePage() {
            location.href = '/'
        },

        async createMail() {
            this.loading = true

            await axios.post('/api/create-email-setting', this.defaultEmailData).then(response => {
                this.$notify({
                    group: 'auth',
                    type: 'success',
                    text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Էլ․ փոստը ավելացված է' ,
                    duration: 1000,
                    speed: 1000
                })

                this.defaultEmailData.email = ''
                this.defaultEmailData.host = ''
                this.defaultEmailData.port = ''
                this.defaultEmailData.from = ''
                this.defaultEmailData.encryption = ''
                this.defaultEmailData.header = ''
                this.defaultEmailData.username = ''
                this.defaultEmailData.password = ''
                this.loading = false
                this.getEmails()
            }).catch(error => {
                this.loading = false
                this.$notify({
                    group: 'auth',
                    type: 'Danger',
                    text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Գործողությունը չհաջողվեց' ,
                    duration: 1000,
                    speed: 1000
                })
                this.errors.defaultEmailData = Object.assign(this.errors.defaultEmailData, error.response.data.errors)
            })
        },

        openDeleteEmailDialog(id) {
            this.emailId = id
            this.deleteEmailDialog = true
        },

        async deleteEmail() {
            this.loading = true
            await axios.post('/api/delete-email-setting', {id:this.emailId}).then(response => {
                this.$notify({
                    group: 'auth',
                    type: 'success',
                    text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Էլ․ փոստը հաջողությամբ ջնջվել է' ,
                    duration: 1000,
                    speed: 1000
                })
                this.loading = false
                this.deleteEmailDialog = false
                this.getEmails()
            }).catch(error => {
                this.loading = false
                this.$notify({
                    group: 'auth',
                    type: 'Danger',
                    text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Գործողությունը չհաջողվեց' ,
                    duration: 1000,
                    speed: 1000
                })
                console.log(error)
            })
        },

        editEmailModal(data) {
            this.editEmailData.id = data.id
            this.editEmailData.email = data.email
            this.editEmailData.host = data.host
            this.editEmailData.from = data.from
            this.editEmailData.port = data.port
            this.editEmailData.encryption = data.encryption
            this.editEmailData.header = data.header
            this.editEmailData.username = data.username
            this.editEmailData.password = data.password

            this.editEmailDialog = true
        },

        async updateEmail() {
            this.loading = true

            await axios.post('/api/update-email-setting', this.editEmailData).then(response => {
                this.$notify({
                    group: 'auth',
                    type: 'success',
                    text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Էլ․ փոստը թարմացված է' ,
                    duration: 1000,
                    speed: 1000
                })

                this.loading = false
                this.editEmailDialog = false
                this.getEmails()
            }).catch(error => {
                this.loading = false
                this.$notify({
                    group: 'auth',
                    type: 'Danger',
                    text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Գործողությունը չհաջողվեց' ,
                    duration: 1000,
                    speed: 1000
                })
                this.errors.editEmailData = Object.assign(this.errors.editEmailData, error.response.data.errors)
            })
        },

        async getEmails() {
            await axios.post('/api/get-emails-setting').then(response => {
                this.emails = response.data.emails
            }).catch(error => {
                console.log(error)
            })
        },
    }
}
</script>

<style scoped lang="scss">

.mailing-wrapper {
    display: flex;
    .mailing-content {
        width: 100%;
        display: flex;
        .mailing-left-menu {
            height: 100vh;
            background: #E8E8E8;
            width: 350px;
            padding: 50px;
            border-radius: 15px 0 0 15px;
            .menu {
                margin-top: 20px;
                ul {
                    li {
                        font-family: 'Inter';
                        font-style: normal;
                        font-weight: 400;
                        font-size: 18px;
                        line-height: 22px;
                        color: #253266;
                        padding: 5px 15px;
                        border-radius: 5px;
                        cursor: pointer;
                        margin-bottom: 10px;
                        &.emails {
                            background: #D3D4DA;
                        }
                        &:hover {
                            background: #D3D4DA;
                        }
                        a {
                            font-family: 'Inter';
                            font-style: normal;
                            font-weight: 400;
                            font-size: 18px;
                            line-height: 22px;
                            color: #253266;
                        }
                    }
                }
            }
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
            }
        }
        .mailing-right-list {
            width: 70%;
            .top-section {
                margin-top: -22px;
                margin-bottom: 10px;
                margin-left: 10px;
                span {
                    font-family: 'Arial AMU';
                    font-style: normal;
                    font-weight: 400;
                    font-size: 18px;
                    line-height: 22px;
                    text-transform: uppercase;
                    color: #253266;
                    border-bottom: 1px solid #253266;
                }
            }
            .mailing-right-list-content {
                background: #ffffff;
                padding: 15px;
                border-radius: 20px 15px 0px 0px;
                position: relative;
                .close-page{
                    position: absolute;
                    right: 10px;
                    top: 10px;
                    cursor: pointer;
                }
                .search-content {
                    display: flex;
                    justify-content: flex-end;
                    width: 100%;
                    margin-bottom: 30px;
                    .search {
                        width: 300px;
                    }
                }
                .table-td {
                    display: flex;
                    .round {
                        margin-right: 20px;
                        margin-top: 3px;
                    }
                }
                .table-td-action {
                    display: flex;
                    align-items: center;
                    > div {
                        margin-left: 20px;
                    }
                    img {
                        width: 18px;
                    }
                }
                .create-category {
                    width: 500px;
                    margin: auto;
                    padding-top: 30px;
                    > .title {
                        font-family: 'Inter';
                        font-style: normal;
                        font-weight: 400;
                        font-size: 18px;
                        line-height: 22px;
                        color: #253266;
                        text-align: center;
                        margin-bottom: 10px;
                    }
                    .create-category-content {
                        margin-bottom: 100px;
                        > div {
                            display: flex;
                            justify-content: space-between;
                            align-items: flex-end;
                            font-style: normal;
                            font-weight: 400;
                            font-size: 18px;
                            line-height: 18px;
                            color: #253266;
                            .add-photo {
                                display: flex;
                            }
                            &.btn-content {
                                display: flex;
                                justify-content: flex-end;
                            }
                            button {
                                font-weight: 400;
                                font-size: 16px !important;
                                line-height: 20px !important;
                                color: #253266;
                                background: #DADADA;
                                border: 0.5px solid #DADADA;
                                border-radius: 5px;
                                padding: 3px 10px;
                                text-transform: capitalize;
                            }
                        }
                    }
                }
                .category-messages-list {
                    > .header {
                        display: flex;
                        padding: 10px 100px;
                        align-items: center;
                        justify-content: space-between;
                        background: #DEE1E4;
                        border-radius: 5px;
                        margin-bottom: 25px;
                        > div {
                            font-family: 'Inter';
                            font-style: normal;
                            font-weight: 400;
                            font-size: 18px;
                            line-height: 18px;
                            color: #253266;
                        }
                    }
                    .items {
                        .item {
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            margin-bottom: 5px;
                            color: #253266;
                            .checkbox {
                                width: 20px;
                                height: 20px;
                                border-radius: 50%;
                                border: 1px solid;
                                margin-right: 20px;
                            }
                            .ids {
                                padding: 10px 15px;
                                background: #F4F4F4;
                                border-radius: 15px 0 0 15px;
                                margin-right: 3px;
                            }
                            .last-block {
                                width: 100%;
                                display: flex;
                                align-items: center;
                                justify-content: space-between;
                                padding: 10px 20px;
                                background: #F4F4F4;
                                border-radius: 0px 15px 15px 0px;
                                .actions {
                                    display: flex;
                                    align-items: center;
                                    > div {
                                        margin-left: 20px;
                                        img {
                                            cursor: pointer;
                                        }
                                    }
                                    .view-user-info {
                                        position: relative;
                                        .user-info {
                                            z-index: 1;
                                            width: 250px;
                                            min-height: 77px;
                                            position: absolute;
                                            padding: 5px 10px;
                                            top: 50%;
                                            left: 50%;
                                            transform: translate(-72%, 25%);

                                            background: #F7F7F7;
                                            box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
                                            border-radius: 18px;
                                            &:after {
                                                content: "";
                                                position: absolute;
                                                top: -10px;
                                                left: 72%;
                                                margin-left: -15px;
                                                width: 0;
                                                height: 0;
                                                border-bottom: solid 15px #F6F6F6;
                                                border-left: solid 15px transparent;
                                                border-right: solid 15px transparent;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
.v-card__text {
    margin-top: 20px;
}
</style>
