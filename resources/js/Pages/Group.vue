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
                        </ul>
                    </div>
                </div>
                <div class="mailing-right-list">
                    <div @click="closePage" class="close-page">
                        <v-icon color="#253266">mdi-close</v-icon>
                    </div>
                    <div class="create-category">
                        <div class="create-category-content">
                            <div>
                                <div>
                                    <v-text-field
                                        v-model="category.name"
                                        placeholder="Անուն"
                                        filled
                                        rounded
                                        value="Կրթություն"
                                        @change="updateCategory"
                                        dense
                                    ></v-text-field>
                                </div>

                                <div class="img-content">
                                    <div>
                                        <img class="category-img" :src="category.image" alt="">
                                    </div>
                                    <div>
                                        <v-btn
                                            color="#253266"
                                            class="text-none"
                                            round
                                            depressed
                                            :loading="isSelecting"
                                            @click="onButtonClick"
                                        >
                                            <img src="/images/LargeReload.png" alt="">
                                            {{ buttonText }}
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
                            <div class="btn-content">
                               <v-btn @click="openCreateEmailDialog">
                                   <v-icon>mdi-plus</v-icon>
                                   Ավելացնել Էլ․ փոստ
                               </v-btn>
                                <v-btn @click="openCreateNewEmailDialog">
                                    <v-icon>mdi-plus</v-icon>
                                    Ավելացնել նոր Էլ․ փոստ
                                </v-btn>
                            </div>
                        </div>
                    </div>
                    <div class="search-content">
                        <div class="header">
                            <div>Օգտատերերի քանակ {{category.contact.length}}</div>
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
                                ></v-text-field>
                            </div>
                        </div>
                    </div>
                    <div class="category-messages-list">
                        <div class="items">
                            <div class="item" v-for="item in category.contact">
                                <div class="checkbox"></div>
                                <div class="ids">{{item.id}}</div>
                                <div class="last-block">
                                    <div class="name">{{item.email}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <loader v-if="loading" object="#03c200" color1="#ffffff" color2="#1fd13d" size="5" speed="2" bg="#343a40" objectbg="#999793" opacity="80" disableScrolling="false" name="dots"></loader>
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
                            v-model="value"
                            :items="items"
                            chips
                            placeholder="Էլ․ Հասցե"
                            multiple
                            solo
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
                        @click="createEmailDialog = false"
                    >
                        Հաստատել
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!--CREATE EMAIL-->
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
                                placeholder="Էլ․ հասցե"
                                filled
                                rounded
                                dense
                            ></v-text-field>
                        </div>
                    </div>
                </v-card-text>

                <v-divider></v-divider>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="primary"
                        text
                        @click="closeCreateNewEmailDialog"
                    >
                        Չեղարկել
                    </v-btn>
                    <v-btn
                        color="primary"
                        text
                        @click="createEmailDialog = false"
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
            value: [],
            createEmailDialog: false,
            createNewEmailDialog: false,
            items: ['foo', 'bar', 'fizz', 'buzz'],
            loading: false,
            dialog: false,
            search: '',
            defaultButtonText: '',
            selectedFile: null,
            isSelecting: false
        }
    },
    props: ['category'],
    async created() {

    },

    computed: {
        buttonText() {
            return this.selectedFile ? this.selectedFile.name : this.defaultButtonText
        }
    },

    methods: {
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

        openCreateEmailDialog() {
            this.createEmailDialog = true
        },
        closeCreateEmailDialog () {
            this.createEmailDialog = false
            this.value = []
        },
        openCreateNewEmailDialog() {
            this.createNewEmailDialog = true
        },
        closeCreateNewEmailDialog () {
            this.createNewEmailDialog = false
            this.value = []
        },

        async updateCategory(name) {
            this.loading = true

            await axios.post('/api/update-category', {
                name: name,
                id: this.category.id
            }).then(response => {
                this.$notify({
                    group: 'auth',
                    type: 'success',
                    text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Կատեգորիան թարմացվել է' ,
                    duration: 1000,
                    speed: 1000
                })
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
                this.errors.defaultCategoryData = Object.assign(this.errors.defaultCategoryData, error.response.data.errors)
            })
        },

        async changeImageCategory() {
            this.loading = true

            await axios.post('/api/update-category-image', {
                id: this.category.id,
                image: this.selectedFile
            }, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(response => {
                this.$notify({
                    group: 'auth',
                    type: 'success',
                    text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Նկարը թարմացվել է' ,
                    duration: 1000,
                    speed: 1000
                })
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
                this.errors.defaultCategoryData = Object.assign(this.errors.defaultCategoryData, error.response.data.errors)
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
            width: 70%;
            background: #ffffff;
            padding: 15px;
            border-radius: 20px 15px 0 0;
            position: relative;
            .close-page{
                position: absolute;
                right: 10px;
                top: 10px;
                cursor: pointer;
            }
            .search-content {
                width: 100%;
                margin-bottom: 30px;
                .header {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 5px 34px;
                    background: #DEE1E4;
                    border-radius: 5px;
                    font-family: 'Arial AMU';
                    font-style: normal;
                    font-weight: 400;
                    font-size: 16px;
                    line-height: 20px;
                    color: #253266;
                }
                .search {
                    width: 300px;
                }
            }
            .create-category {
                width: 700px;
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
                    display: flex;
                    justify-content: space-between;
                    > div {
                        width: calc(100% / 2 - 10px);
                        font-style: normal;
                        font-weight: 400;
                        font-size: 18px;
                        line-height: 18px;
                        color: #253266;
                        .img-content {
                            display: flex;
                            align-items: flex-end;
                            .category-img {
                                width: 80px;
                                height: 80px;
                                border-radius: 50%;
                                object-fit: cover;
                            }
                            button {
                                background: transparent!important;
                                img {
                                    margin-right: 10px;
                                }
                            }
                        }
                    }
                    .btn-content {
                        button {
                            font-weight: 400;
                            font-size: 14px;
                            line-height: 14px;
                            color: #253266;
                            background: #DADADA;
                            border: 0.5px solid #DADADA;
                            border-radius: 5px;
                            padding: 3px 10px;
                            text-transform: capitalize;
                            margin-bottom: 5px;
                        }
                    }
                }
            }
            .category-messages-list {
                .items {
                    display: flex;
                    flex-wrap: wrap;
                    justify-content: space-between;
                    .item {
                        width: calc(100% / 2 - 10px);
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        margin-bottom: 5px;
                        .checkbox {
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
