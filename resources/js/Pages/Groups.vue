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
                                <span>Կատեգորյաներ</span>
                            </li>
                            <li>
                                <img src="/images/mail.png">
                                <span>Էլ․ փոստեր</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="mailing-right-list">
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
                        <v-card>
                            <v-data-table
                                :headers="headers"
                                :items="desserts"
                                :search="search"
                                hide-default-header
                            >
                                <template v-slot:item.name="{ item }">
                                    <div class="table-td">
                                        <div>
                                            <div class="round">
                                                <input type="checkbox" id="checkbox"/>
                                                <label for="checkbox"></label>
                                            </div>
                                        </div>
                                       <div>
                                           <img src="/images/Group.png" alt="">
                                           <span>{{item.name}}</span>
                                       </div>
                                    </div>
                                </template>
                                <template v-slot:item.action="{ item }">
                                    <div class="table-td-action">
                                        <div><img src="/images/plus.png" alt=""></div>
                                        <div><img src="/images/mailIcon.png" alt=""></div>
                                        <div><img src="/images/pencil.png" alt=""></div>
                                        <div><img src="/images/removeIcon.png" alt=""></div>
                                    </div>
                                </template>
                            </v-data-table>
                        </v-card>
                    </div>
                </div>
            </div>
        </div>
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
            loading: false,
            messageList: false,
            messageBox: true,
            dialog: false,
            search: '',
            headers: [
                {text: 'Ումից', value: 'name',},
                { text: 'Ում', value: 'action' },
            ],
            desserts: [
                {
                    action: '',
                    name: 'Առողջապահություն',
                },
                {
                    action: '',
                    name: 'Առողջապահություն',
                },
                {
                    action: '',
                    name: 'Առողջապահություն',
                },
                {
                    action: '',
                    name: 'Առողջապահություն',
                },
            ],
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
            border-radius: 20px 15px 0px 0px;

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

            .mailing-box-content {
                display: flex;
                align-items: flex-start;
                .mailing-box {
                    margin-top: 30px;
                }
                .field {
                    margin-bottom: 20px;
                }
                .close-modal {
                    width: 10%;
                    display: flex;
                    justify-content: flex-end;
                    i {
                        cursor: pointer;
                    }
                }
            }
        }
    }
}



</style>
