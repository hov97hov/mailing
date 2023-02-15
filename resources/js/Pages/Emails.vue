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
                            <li>
                                <a href="/add-email-setting">
                                    <img src="/images/mail.png">
                                    <span>Ավելացնել Էլ․ հասցե</span>
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
                                        v-model="defaultEmailData.name"
                                        placeholder="Անուն"
                                        filled
                                        rounded
                                        dense
                                        :error-messages="errors.defaultEmailData.name"
                                        @input="checkErrors('defaultEmailData', 'name')"
                                    ></v-text-field>
                                </div>
                                <div>
                                    <v-text-field
                                        v-model="defaultEmailData.email"
                                        placeholder="Էլ․ փոստ"
                                        filled
                                        rounded
                                        dense
                                        :error-messages="errors.defaultEmailData.email"
                                        @input="checkErrors('defaultEmailData', 'email')"
                                    ></v-text-field>
                                </div>
                                <div>
                                    <v-select
                                        v-model="defaultEmailData.categoryId"
                                        :items="categories"
                                        item-text="name"
                                        item-value="id"
                                        chips
                                        label="Ընտրել Կատեգորիան"
                                        multiple
                                        solo
                                        :error-messages="errors.defaultEmailData.categoryId"
                                        @input="checkErrors('defaultEmailData', 'categoryId')"
                                    ></v-select>
                                </div>
                                <div>
                                    <v-textarea
                                        v-model="defaultEmailData.description"
                                        rows="1"
                                        placeholder="..."
                                        height="120"
                                        solo
                                    ></v-textarea>
                                </div>
                                <div class="btn-content">
                                    <v-btn @click="createMail">Հաստատել</v-btn>
                                </div>
                            </div>
                        </div>
                        <div class="search-content">
                            <div>
                                <img v-if="selectedEmails.length" @click="deleteEmailsDialog = true" src="/images/removeIcon.png" alt="">
                            </div>
                            <div class="search">
                                <v-text-field
                                    v-model="search"
                                    prepend-inner-icon="mdi-magnify"
                                    filled
                                    rounded
                                    dense
                                    hi
                                    hide-details
                                    color="#253266"
                                    @input="searchEmails"
                                ></v-text-field>
                            </div>
                        </div>
                        <div class="category-messages-list">
                            <div class="header">
                                <div>Անուն</div>
                                <div>Էլ․ փոստ</div>
                                <div>Կատեգորիա</div>
                                <div></div>
                            </div>
                            <div class="items" v-click-outside="hide">
                                <div class="item" v-for="item in emails">
                                    <label class="checkbox-content">
                                        <input v-model="selectedEmails" :value="item.id" type="checkbox">
                                        <span class="checkmark"></span>
                                    </label>
                                    <div class="ids">{{item.id}}</div>
                                    <div class="last-block">
                                        <div class="name">{{item.name}}</div>
                                        <div class="email">{{item.email}}</div>
                                        <div class="category">
                                            <span v-for="group in item.group">
                                                <span>{{group.name}}</span> <br>
                                            </span>
                                        </div>
                                        <div class="actions">
                                            <div>
                                                <img @click="editEmailModal(item)" src="/images/pencil.png" alt="">
                                            </div>
                                            <div class="view-user-info">
                                                <img @click="openModal(item.id)" src="/images/Subtract.png" alt="">
                                                <div class="user-info" v-if="isActive === item.id">
                                                   {{item.description}}
                                                </div>
                                            </div>
                                            <div>
                                                <img @click="openDeleteEmailDialog(item.id)" src="/images/removeIocn.png" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <v-pagination
                                class="paginate-content"
                                v-model="pagination.current"
                                :length="pagination.total"
                                :total-visible="7"
                                @input="onPageChange"
                                color="#253266"
                            ></v-pagination>
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
                                v-model="editEmailData.name"
                                placeholder="Անուն"
                                filled
                                rounded
                                dense
                                :error-messages="errors.editEmailData.name"
                                @input="checkErrors('editEmailData', 'name')"
                            ></v-text-field>
                        </div>
                    </div>
                    <div class="text-filed-content">
                        <div>
                            <v-text-field
                                v-model="editEmailData.email"
                                placeholder="Էլ․ հասցե"
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
                            <v-select
                                v-model="editEmailData.categoryId"
                                :items="categories"
                                item-text="name"
                                item-value="id"
                                chips
                                label="Ընտրել Կատեգորիան"
                                multiple
                                solo
                                :error-messages="errors.editEmailData.categoryId"
                                @input="checkErrors('editEmailData', 'categoryId')"
                            ></v-select>
                        </div>
                    </div>
                    <div class="text-filed-content">
                        <div>
                            <v-textarea
                                v-model="editEmailData.description"
                                rows="1"
                                placeholder="..."
                                height="120"
                                solo
                            ></v-textarea>
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
        <!--Delete category-->
        <v-dialog
            v-model="deleteEmailsDialog"
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
                        @click="deleteEmailsDialog = false"
                    >
                        Չեղարկել
                    </v-btn>

                    <v-btn
                        color="#253266"
                        text
                        @click="deleteSelectedEmails"
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
import ClickOutside from "vue-click-outside";

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
            deleteEmailsDialog: false,
            editEmailDialog: false,
            search: '',
            emailId: '',
            categories: [],
            emails: [],
            selectedEmails: [],
            pagination: {
                current: 1,
                total: 0
            },
            defaultEmailData: {
                name: '',
                categoryId: [],
                description: '',
            },
            editEmailData: {
                id: '',
                name: '',
                categoryId: [],
                description: '',
            },
            errors: {
                defaultEmailData: {
                    name: '',
                    categoryId: '',
                },
                editEmailData: {
                    name: '',
                    categoryId: '',
                    description: '',
                },
            }
        }
    },
    async created() {
        await this.getAllCategories()
        await this.getAllEmails()
    },

    methods: {
        openMessageBox() {
          location.href = '/'
        },

        onPageChange() {
            this.getAllEmails();
        },

        async searchEmails() {
            const formData =  new FormData()
            formData.append('query', this.search)

            await axios.post('/api/search-emails', formData).then(response => {
                this.emails = response.data.emails
            }).catch(error => {
                console.log(error)
            })
        },

        hide () {
            this.isActive = false
        },

        openModal(id) {
           this.isActive =  id
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

        async getAllCategories() {
            await axios.post('/api/get-all-category').then(response => {
                this.categories = response.data.categories
            }).catch(error => {
                console.log(error)
            })
        },

        async getAllEmails() {
            await axios.get('/api/get-emails?page=' + this.pagination.current).then(response => {
                this.emails =  response.data.emails.data
                this.pagination.current = response.data.emails.current_page;
                this.pagination.total = response.data.emails.last_page;
            }).catch(error => {
                console.log(error)
            })
        },

        async createMail() {
            this.loading = true

            await axios.post('/api/create-email', this.defaultEmailData).then(response => {
                this.$notify({
                    group: 'auth',
                    type: 'success',
                    text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Էլ․ փոստը ավելացված է' ,
                    duration: 1000,
                    speed: 1000
                })

                this.defaultEmailData.name = ''
                this.defaultEmailData.description = ''
                this.defaultEmailData.email = ''
                this.defaultEmailData.categoryId = ''
                this.loading = false
                this.getAllEmails()
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
            await axios.post('/api/delete-email', {id:this.emailId}).then(response => {
                this.$notify({
                    group: 'auth',
                    type: 'success',
                    text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Էլ․ փոստը հաջողությամբ ջնջվել է' ,
                    duration: 1000,
                    speed: 1000
                })
                this.loading = false
                this.deleteEmailDialog = false
                this.getAllEmails()
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

        async deleteSelectedEmails() {
            this.loading = true
            await axios.post('/api/delete-selected-emails', {ids:this.selectedEmails}).then(response => {
                this.$notify({
                    group: 'auth',
                    type: 'success',
                    text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Էլ․ փոստը հաջողությամբ ջնջվել է' ,
                    duration: 1000,
                    speed: 1000
                })
                this.loading = false
                this.deleteEmailsDialog = false
                this.getAllEmails()
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
            this.editEmailData.name = data.name
            this.editEmailData.email = data.email
            this.editEmailData.description = data.description
            this.editEmailData.categoryId = []
            data.group.map(item => {
                this.editEmailData.categoryId.push(item.id)
            })

            this.editEmailDialog = true
        },

        async updateEmail() {
            this.loading = true

            await axios.post('/api/update-email', this.editEmailData).then(response => {
                this.$notify({
                    group: 'auth',
                    type: 'success',
                    text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Էլ․ փոստը թարմացված է' ,
                    duration: 1000,
                    speed: 1000
                })

                this.loading = false
                this.editEmailDialog = false
                this.getAllEmails()
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

        directives: {
            ClickOutside
        }
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
            min-width: 350px;
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
                    img {
                        margin-right: 30px;
                    }
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
                                            padding: 10px;
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
