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
                            <li class="groups">
                                <img src="/images/category.png">
                                <span>Կատեգորիաներ</span>
                            </li>
                            <li>
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
                    <div class="top-title">
                        <span>Կատեգորիաներ</span>
                    </div>
                   <div class="right-content">
                       <div @click="closePage" class="close-page">
                           <v-icon color="#253266">mdi-close</v-icon>
                       </div>
                       <div class="create-category">
                           <div class="title">Ավելացնել կատեգորիա</div>
                           <div class="create-category-content">
                               <div>
                                   <v-text-field
                                       v-model="defaultCategoryData.name"
                                       placeholder="Անուն"
                                       filled
                                       rounded
                                       dense
                                       :error-messages="errors.defaultCategoryData.name"
                                       @input="checkErrors('defaultCategoryData', 'name')"
                                   ></v-text-field>
                               </div>
                               <div>
                                   <div class="add-photo">
                                       <div>
                                           <span>Ավելացնել նկար</span>
                                           <span>{{errors.defaultCategoryData.image[0]}}</span>
                                       </div>
                                       <div class="icon">
                                           <div>
                                               <v-btn
                                                   color="#253266"
                                                   class="text-none"
                                                   round
                                                   depressed
                                                   :loading="isSelecting"
                                                   @click="onButtonClick"
                                               >
                                                  <div>
                                                      <img src="/images/uploadImage.png" alt="">
                                                  </div>
                                               </v-btn>
                                               <input
                                                   ref="uploader"
                                                   class="d-none"
                                                   type="file"
                                                   accept="image/*"
                                                   @change="onFileChanged"
                                               >
                                           </div>
                                       </div>
                                   </div>
                                   <div>
                                       <button @click="createCategory">Ավելացնել</button>
                                   </div>
                               </div>
                               {{ buttonText }}
                           </div>
                       </div>
                       <div class="search-content">
                           <div>
                               <img v-if="selectedGroups.length" @click="deleteGroupDialog = true" src="/images/removeIcon.png" alt="">
                           </div>
                           <div class="search">
                               <v-text-field
                                   v-model="searchCategories"
                                   prepend-inner-icon="mdi-magnify"
                                   filled
                                   rounded
                                   dense
                                   hi
                                   hide-details
                                   color="#253266"
                                   @input="search"
                               ></v-text-field>
                           </div>
                       </div>
                       <div class="category-messages-list">
                           <div class="category-list">
                               <div class="items" v-click-outside="hide">
                                   <div class="item"
                                        v-for="item in categories"
                                        :key="item.id"
                                   >
                                       <label class="checkbox-content">
                                           <input v-model="selectedGroups" :value="item.id" type="checkbox">
                                           <span class="checkmark"></span>
                                       </label>
                                       <div class="last-block" >
                                           <div class="name">
                                               <img :src="item.image" alt="">
                                               <span @click="openGroupPage(item.id)">{{item.name}}</span>
                                           </div>
                                           <div class="actions">
                                               <div class="add-user-content">
                                                   <img @click="openPophup(item.id)" src="/images/plus.png" alt="">
                                                   <div class="add-user" v-if="isActive === item.id">
                                                       <div @click="openCreateEmailDialog(item.id)" class="btn-content">
                                                           <v-icon color="#253266">mdi-plus</v-icon>
                                                           <span>Ավելացնել Էլ․ հասցե</span>
                                                       </div>
                                                       <div  @click="openCreateNewEmailDialog(item.id)" class="btn-content">
                                                           <v-icon color="#253266">mdi-plus</v-icon>
                                                           <span>Ավելացնել նոր Էլ․ հասցե</span>
                                                       </div>
                                                       <div class="user-count"><span>Օգտատերերի քանակ {{item.contact.length}}</span></div>
                                                   </div>
                                               </div>
                                               <div>
                                                   <a href="/">
                                                       <img src="/images/mail.png" alt="">
                                                   </a>
                                               </div>
                                               <div>
                                                   <img @click="openGroupPage(item.id)" src="/images/pencil.png" alt="">
                                               </div>
                                               <div>
                                                   <img @click="openDialogDeleteCategory(item.id)" src="/images/removeIocn.png" alt="">
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
            </div>
        </div>
        <loader v-if="loading" object="#343a40" color1="#ffffff" color2="#253266" size="5" speed="2" bg="#343a40" objectbg="#999793" opacity="80" disableScrolling="false" name="dots"></loader>
        <!--NOTIFICATION-->
        <notifications group="auth"/>
        <!--CREATE EMAIL-->
        <v-dialog
            v-model="createEmailDialog"
            width="500"
            persistent
        >
            <v-card>
                <v-card-title class="text-h5 grey lighten-2">
                    Ավելացնել Էլ․ հասցե
                </v-card-title>

                <v-card-text>
                    <div class="text-filed-content">
                        <v-select
                            v-model="emailsData"
                            :items="emails"
                            chips
                            placeholder="Էլ․ Հասցե"
                            item-value="id"
                            item-text="email"
                            multiple
                            solo
                            :error-messages="errors.emailIds.contact_ids"
                            @input="checkErrors('emailIds', 'contact_ids')"
                        ></v-select>
                    </div>
                </v-card-text>

                <v-divider></v-divider>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="primary"
                        text
                        @click="closeCreateEmailDialog"
                    >
                        Չեղարկել
                    </v-btn>
                    <v-btn
                        color="primary"
                        text
                        @click="addEmailCategory"
                    >
                        Հաստատել
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!--CREATE NEW EMAIL-->
        <v-dialog
            v-model="createNewEmailDialog"
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
                                v-model="createNewEmailData.name"
                                placeholder="Անուն"
                                filled
                                rounded
                                dense
                                :error-messages="errors.createNewEmailData.name"
                                @input="checkErrors('createNewEmailData', 'name')"
                            ></v-text-field>
                        </div>
                    </div>
                    <div class="text-filed-content">
                        <div>
                            <v-text-field
                                v-model="createNewEmailData.email"
                                placeholder="Էլ․ հասցե"
                                filled
                                rounded
                                dense
                                :error-messages="errors.createNewEmailData.email"
                                @input="checkErrors('createNewEmailData', 'email')"
                            ></v-text-field>
                        </div>
                    </div>
                    <div class="text-filed-content">
                        <div>
                            <v-textarea
                                v-model="createNewEmailData.description"
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
                        @click="createNewEmailDialog = false"
                    >
                        Չեղարկել
                    </v-btn>
                    <v-btn
                        color="#253266"
                        text
                        @click="createMail"
                    >
                        Հաստատել
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!--Delete category-->
        <v-dialog
            v-model="deleteCategoryDialog"
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
                        @click="deleteCategoryDialog = false"
                    >
                        Չեղարկել
                    </v-btn>

                    <v-btn
                        color="#253266"
                        text
                        @click="deleteCategory"
                    >
                        Հաստատել
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!--Delete category-->
        <v-dialog
            v-model="deleteGroupDialog"
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
                        @click="deleteGroupDialog = false"
                    >
                        Չեղարկել
                    </v-btn>

                    <v-btn
                        color="#253266"
                        text
                        @click="deleteCategories"
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
import ClickOutside from 'vue-click-outside'


export default {
    name: "home",
    components: {
        Header,
        VueEditor,
    },
    data: () => {
        return {
            isActive: false,
            loading: false,
            isSelecting: false,
            createEmailDialog: false,
            createNewEmailDialog: false,
            deleteGroupDialog: false,
            deleteCategoryDialog: false,
            selectedFile: '',
            searchCategories: '',
            selectedCategory: [],
            emailsData: [],
            categories: [],
            emails: [],
            selectedGroups: [],
            pagination: {
                current: 1,
                total: 0
            },
            createNewEmailData: {
                name: '',
                categoryId: [],
                description: '',
            },
            defaultCategoryData: {
                name: '',
                image: '',
            },
            errors: {
                defaultCategoryData: {
                    name: '',
                    image: '',
                },
                createNewEmailData: {
                    name: '',
                    categoryId: [],
                    description: '',
                },
                emailIds: {
                    contact_ids: ''
                }
            }
        }
    },

    async created() {
        await this.getCategory()
        await this.getAllEmails()
    },

    computed: {
        buttonText() {
            return this.selectedFile ? this.selectedFile.name : this.defaultButtonText
        }
    },

    methods: {

        onPageChange() {
            this.getCategory();
        },

        toggle () {
            this.opened = true
        },

        hide () {
            this.isActive = false
        },

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

        onButtonClick() {
            this.isSelecting = true
            window.addEventListener('focus', () => {
                this.isSelecting = false
            }, { once: true })

            this.$refs.uploader.click()
        },
        onFileChanged(e) {
            this.selectedFile = e.target.files[0]
        },

        closePage() {
            location.href = '/'
        },

        openCreateEmailDialog(id) {
            this.createEmailDialog = true
            this.categoryId = id
            this.emailsData = []
        },
        closeCreateEmailDialog () {
            this.createEmailDialog = false
        },
        openCreateNewEmailDialog(id) {
            this.createNewEmailDialog = true
            this.categoryId = id
        },
        closeCreateNewEmailDialog () {
            this.createNewEmailDialog = false
            this.value = []
        },

        async createCategory() {
            this.loading = true
            const formData = new FormData()
            formData.append('name', this.defaultCategoryData.name)
            formData.append('image', this.selectedFile)

            await axios.post('/api/create-category', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(response => {
                this.$notify({
                    group: 'auth',
                    type: 'success',
                    text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Կատեգորիան ավելացված է' ,
                    duration: 1000,
                    speed: 1000
                })
                this.getCategory()
                this.defaultCategoryData.name = ''
                this.defaultCategoryData.image = ''
                this.errors.defaultCategoryData.image = ''
                this.loading = false
                this.selectedFile = []
            }).catch(error => {
                this.loading = false
                this.$notify({
                    group: 'auth',
                    type: 'Danger',
                    text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Գործողությունը չհաջողվեց' ,
                    duration: 1000,
                    speed: 1000
                })
                this.errors.defaultCategoryData = Object.assign(this.errors.defaultCategoryData, error.response.data.errors)
            })
        },

        async getCategory() {
            await axios.get('/api/get-category?page=' + this.pagination.current).then(response => {
                this.categories =  response.data.categories.data
                this.pagination.current = response.data.categories.current_page;
                this.pagination.total = response.data.categories.last_page;
            }).catch(error => {
                console.log(error)
            })
        },

        openDialogDeleteCategory(id) {
            this.categoryId = id
            this.deleteCategoryDialog = true
        },

        async deleteCategory() {
            this.loading = true
            await axios.post('/api/delete-category', {id:this.categoryId}).then(response => {
                this.$notify({
                    group: 'auth',
                    type: 'success',
                    text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Կատեգորիան հաջողությամբ ջնջվել է' ,
                    duration: 1000,
                    speed: 1000
                })
                this.loading = false
                this.deleteCategoryDialog = false
                this.getCategory()
            }).catch(error => {
                this.loading = false
                this.$notify({
                    group: 'auth',
                    type: 'Danger',
                    text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Գործողությունը չհաջողվեց' ,
                    duration: 1000,
                    speed: 1000
                })
                this.errors.defaultCategoryData = Object.assign(this.errors.defaultCategoryData, error.response.data.errors)
            })
        },

        async deleteCategories() {
            this.loading = true
            await axios.post('/api/delete-categories', {ids:this.selectedGroups}).then(response => {
                this.$notify({
                    group: 'auth',
                    type: 'success',
                    text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Կատեգորիաները հաջողությամբ ջնջվել է' ,
                    duration: 1000,
                    speed: 1000
                })
                this.loading = false
                this.deleteGroupDialog = false
                this.selectedGroups = ''
                this.getCategory()
            }).catch(error => {
                this.loading = false
                this.$notify({
                    group: 'auth',
                    type: 'Danger',
                    text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Գործողությունը չհաջողվեց' ,
                    duration: 1000,
                    speed: 1000
                })
                this.errors.defaultCategoryData = Object.assign(this.errors.defaultCategoryData, error.response.data.errors)
            })
        },

        openPophup(id) {
            this.isActive = id
        },

        openGroupPage(id) {
            location.href = '/group/' + id
        },

        async createMail() {
            this.loading = true
            const formData = new FormData()
            formData.append('name', this.createNewEmailData.name)
            formData.append('description', this.createNewEmailData.description)
            formData.append('email', this.createNewEmailData.email)
            formData.append('categoryId', this.categoryId)

            await axios.post('/api/create-email', formData).then(response => {
                this.$notify({
                    group: 'auth',
                    type: 'success',
                    text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Էլ․ փոստը ավելացված է' ,
                    duration: 1000,
                    speed: 1000
                })

                this.createNewEmailData.name = ''
                this.createNewEmailData.description = ''
                this.createNewEmailData.email = ''
                this.createNewEmailData.categoryId = ''
                this.createNewEmailDialog = false
                this.loading = false
                this.getCategory()
            }).catch(error => {
                this.loading = false
                this.$notify({
                    group: 'auth',
                    type: 'Danger',
                    text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Գործողությունը չհաջողվեց' ,
                    duration: 1000,
                    speed: 1000
                })
                this.errors.createNewEmailData = Object.assign(this.errors.createNewEmailData, error.response.data.errors)
            })
        },

        async getAllEmails() {
            await axios.get('/api/get-all-emails').then(response => {
                this.emails = response.data.emails
            }).catch(error => {
                console.log(error)
            })
        },

        async addEmailCategory() {
            this.loading = true
            const formData = new FormData()
            formData.append('contact_ids', this.emailsData)
            formData.append('categoryId', this.categoryId)

            await axios.post('/api/add-email-category', formData).then(response => {
                this.$notify({
                    group: 'auth',
                    type: 'success',
                    text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Էլ․ փոստը ավելացված է' ,
                    duration: 1000,
                    speed: 1000
                })
                this.emailsData = []
                this.createEmailDialog = false
                this.getCategory()
                this.loading = false
            }).catch(error => {
                this.loading = false
                this.$notify({
                    group: 'auth',
                    type: 'Danger',
                    text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Գործողությունը չհաջողվեց' ,
                    duration: 1000,
                    speed: 1000
                })
                this.errors.emailIds = Object.assign(this.errors.emailIds, error.response.data.errors)
            })
        },

        async search() {
            const formData =  new FormData()
            formData.append('query', this.searchCategories)

            await axios.post('/api/search-categories', formData).then(response => {
                this.categories = response.data.categories
            }).catch(error => {
                console.log(error)
            })
        },
    },

    directives: {
        ClickOutside
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
                        &.groups {
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
            width: 100%;
            .top-title {
                margin-top: -34px;
                margin-bottom: 10px;
                margin-left: 10px;
                span {
                    font-family: "Arial AMU";
                    font-style: normal;
                    font-weight: 400;
                    font-size: 18px;
                    line-height: 22px;
                    text-transform: uppercase;
                    color: #253266;
                    border-bottom: 1px solid #253266;
                }
            }
            .right-content {
                width: 95%;
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
                        margin-right: 20px;
                    }
                    .search {
                        width: 300px;
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
                                > .icon {
                                    button {
                                        width: 80px !important;
                                        height: 80px !important;
                                        border-radius: 50% !important;
                                        font-weight: 400;
                                        font-size: 16px;
                                        line-height: 20px;
                                        color: #253266;
                                        background: #DADADA !important;
                                        border: 0.5px solid #DADADA !important;
                                        padding: 3px 10px;
                                    }
                                }
                                > div {
                                    &:first-child {
                                        display: flex;
                                        flex-direction: column;
                                        span {
                                            &:last-child {
                                                margin-top: 65px;
                                                color: red !important;
                                                line-height: 12px;
                                                font-size: 12px;
                                                word-break: break-word;
                                            }
                                        }
                                    }
                                }
                            }
                            button {
                                font-weight: 400;
                                font-size: 16px;
                                line-height: 20px;
                                color: #253266;
                                background: #DADADA !important;
                                border: 0.5px solid #DADADA !important;
                                border-radius: 5px;
                                padding: 3px 10px;
                            }
                        }
                    }
                }
                .category-list {
                    .items {
                        .item {
                            width: 100%;
                            display: flex;
                            align-items: center;
                            margin-bottom: 20px;
                            > .checkbox {
                                width: 20px;
                                height: 20px;
                                border-radius: 20px;
                                border: 1px solid #000000;
                                margin-right: 15px;
                            }
                            .name {
                                font-family: 'Arial AMU';
                                font-style: normal;
                                font-weight: 400;
                                font-size: 16px;
                                line-height: 20px;
                                color: #253266;
                                cursor: pointer;
                                img {
                                    width: 25px;
                                    height: 25px;
                                    border-radius: 50%;
                                    object-fit: cover;
                                    margin-right: 10px;
                                }
                            }
                            .last-block {
                                width: 100%;
                                display: flex;
                                align-items: center;
                                justify-content: space-between;
                                padding: 10px 15px;
                                background: #EAEAEA;
                                border-radius: 15px;
                                > .actions {
                                    width: 33%;
                                    display: flex;
                                    align-items: center;
                                    > div {
                                        margin-left: 20px;
                                        img {
                                            cursor: pointer;
                                        }
                                    }
                                    .add-user-content {
                                        position: relative;
                                        .add-user {
                                            width: 307px;
                                            background: #F6F6F6;
                                            box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
                                            border-radius: 8px;
                                            position: absolute;
                                            padding: 5px 10px;
                                            top: 50%;
                                            left: 50%;
                                            z-index: 1;
                                            transform: translate(-50%, 25%);
                                            .btn-content {
                                                cursor: pointer;
                                                margin-bottom: 5px;
                                                font-family: 'Arial AMU';
                                                font-style: normal;
                                                font-weight: 400;
                                                font-size: 16px;
                                                line-height: 20px;
                                                color: #253266;
                                            }
                                            .user-count {
                                                display: flex;
                                                justify-content: flex-end;
                                                font-family: 'Arial AMU';
                                                font-style: normal;
                                                font-weight: 400;
                                                font-size: 14px;
                                                line-height: 18px;
                                                color: #253266;
                                            }
                                            &:after {
                                                content: "";
                                                position: absolute;
                                                top: -10px;
                                                left: 50%;
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

.text-filed-content {
    margin-top: 20px;
}

</style>
