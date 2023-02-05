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
                                       placeholder="Անուն"
                                       filled
                                       rounded
                                       dense
                                   ></v-text-field>
                               </div>
                               <div>
                                   <div class="add-photo">
                                       <div>Ավելացնել նկար</div>
                                       <div class="icon">
                                           <v-file-input
                                               hide-input
                                               show-size
                                               truncate-length="15"
                                               prepend-icon="mdi-plus"
                                           ></v-file-input>
                                       </div>
                                   </div>
                                   <div>
                                       <button>Ավելացնել</button>
                                   </div>
                               </div>
                           </div>
                       </div>
                       <div class="search-content">
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
                       <div class="category-messages-list">
                           <div class="category-list">
                               <div class="items">
                                   <div class="item">
                                       <div class="checkbox"></div>
                                       <div class="last-block">
                                           <div class="name">
                                               <img src="/images/company.png" alt="">
                                               <span>Առողջապահություն</span>
                                           </div>
                                           <div class="actions">
                                               <div class="add-user-content">
                                                   <img @click="isActive = !isActive" src="/images/plus.png" alt="">
                                                   <div class="add-user" v-if="isActive">
                                                       <div @click="openCreateEmailDialog" class="btn-content">
                                                           <v-icon color="#253266">mdi-plus</v-icon>
                                                           <span>Ավելացնել Էլ․ հասցե</span>
                                                       </div>
                                                       <div @click="openCreateNewEmailDialog" class="btn-content">
                                                           <v-icon color="#253266">mdi-plus</v-icon>
                                                           <span>Ավելացնել նոր Էլ․ հասցե</span>
                                                       </div>
                                                       <div class="user-count"><span>Օգտատերերի քանակ 7</span></div>
                                                   </div>
                                               </div>
                                               <div>
                                                   <img src="/images/mail.png" alt="">
                                               </div>
                                               <div>
                                                   <img src="/images/pencil.png" alt="">
                                               </div>
                                               <div>
                                                   <img src="/images/removeIocn.png" alt="">
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
            isActive: false,
            loading: false,
            createEmailDialog: false,
            createNewEmailDialog: false,
            search: '',
        }
    },
    async created() {

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
                            }
                            button {
                                font-weight: 400;
                                font-size: 16px;
                                line-height: 20px;
                                color: #253266;
                                background: #DADADA;
                                border: 0.5px solid #DADADA;
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
