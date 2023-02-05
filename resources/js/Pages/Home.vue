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
                    <div v-if="messageList" class="mailing-right-list-header">
                        <span>ՈՒՂԱՐԿՎԱԾ ՆԱՄԱԿ</span>
                    </div>
                    <div v-if="messageBox" class="mailing-box-content">
                        <div class="mailing-box">
                            <div class="field">
                                <v-select
                                    label="Ում կողմից"
                                    solo
                                    color="#253266"
                                    class="rounded-lg"
                                    hide-details
                                >
                                </v-select>
                            </div>
                            <div class="field">
                                <v-select
                                    label="Ում"
                                    solo
                                    color="#253266"
                                    class="rounded-lg"
                                    hide-details
                                >
                                </v-select>
                            </div>
                            <div class="field">
                                <v-text-field
                                    label="Թեմա"
                                    solo
                                    color="#253266"
                                    class="rounded-lg"
                                    hide-details
                                >
                                </v-text-field>
                            </div>
                            <div class="field">
                                <vue-editor

                                >
                                </vue-editor>
                            </div>
                            <div class="fields">
                                <div>
                                    <img src="/images/mailing.png" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="close-modal">
                            <v-icon @click="closeMessageBox">mdi-close</v-icon>
                        </div>
                    </div>
                    <div class="messages-list" v-if="messageList">
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
                           <div>
                               <img src="/images/removeIcon.png" alt="">
                           </div>
                       </div>
                        <div class="message-list">
                            <div class="header">
                                <div>Ումից</div>
                                <div>Ում</div>
                                <div>Թեմա</div>
                                <div>Ամսաթիվ</div>
                            </div>
                            <div class="list">
                                <div>
                                    <div class="checkbox"></div>
                                    <div class="img"><img src="/images/Group.png" alt=""></div>
                                    <div class="name">hovo1997mkrtchyan@gmail.com</div>
                                </div>
                                <div>
                                    <img src="/images/company.png" alt="">
                                    <div>Առողջապահություն</div>
                                </div>
                                <div>
                                   <div>Եթե Ձեր ամբողջ ...</div>
                                </div>
                                <div>
                                    <div>19.01.2023</div>
                                </div>
                            </div>
                            <div class="list">
                                <div>
                                    <div class="checkbox"></div>
                                    <div class="img"><img src="/images/Group.png" alt=""></div>
                                    <div class="name">hovo1997mkrtchyan@gmail.com</div>
                                </div>
                                <div>
                                    <img src="/images/company.png" alt="">
                                    <div>Առողջապահություն</div>
                                </div>
                                <div>
                                    <div>Եթե Ձեր ամբողջ ...</div>
                                </div>
                                <div>
                                    <div>19.01.2023</div>
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
            messageList: false,
            messageBox: true,
            dialog: false,
            search: '',
            headers: [
                {text: 'Ումից', value: 'from',},
                { text: 'Ում', value: 'to' },
                { text: 'Թեմա', value: 'subject' },
                { text: 'Ամսաթիվ', value: 'date' },
            ],
            desserts: [
                {
                    from: 'Frozen Yogurt',
                    to: 'Առողջապահություն',
                    subject: 'Եթե Ձեր ամբողջ ...',
                    date: '19.01.2023',
                },
                {
                    from: 'Frozen Yogurt',
                    to: 'Առողջապահություն',
                    subject: 'Եթե Ձեր ամբողջ ...',
                    date: '19.01.2023',
                },
            ],
        }
    },
    async created() {
        if (window.location.search === '?messages') {
            this.messageBox = false
            this.messageList = true
        }
    },

    methods: {
        checkErrors(obj, field) {
            if (obj) {
                this.errors[obj][field] = ''
            } else {
                this.errors[field] = ''
            }
        },

        closeMessageBox() {
            this.messageBox = false
            this.messageList = true
        },
        openMessageBox() {
            this.messageBox = true
            this.messageList = false
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
                            cursor: pointer;
                            margin-bottom: 10px;
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
                .mailing-right-list-header {
                    margin-top: -22px;
                    width: 50%;
                    border-bottom: 1px solid;
                    margin-bottom: 10px;
                    margin-left: 5px;
                    span {
                        font-family: 'Arial AMU';
                        font-style: normal;
                        font-weight: 400;
                        font-size: 18px;
                        line-height: 22px;
                        padding-left: 15px;
                        color: #253266
                    }
                }
                .mailing-box-content {
                    width: 100%;
                    background: #ffffff;
                    padding: 15px;
                    border-radius: 20px 15px 0px 0px;
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
                .messages-list {
                    width: 100%;
                    background: #ffffff;
                    padding: 15px;
                    border-radius: 20px 15px 0px 0px;
                    .search-content {
                        margin-bottom: 30px;
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        .search {
                            width: 300px;
                        }
                    }
                    .message-list {
                        overflow-x: auto;
                        > .header {
                            display: flex;
                            justify-content: space-between;
                            padding-top: 5px;
                            padding-bottom: 5px;
                            background: #DEE1E4;
                            border-radius: 5px;
                            margin-bottom: 10px;
                            > div {
                                font-family: 'Inter';
                                font-style: normal;
                                font-weight: 400;
                                font-size: 18px;
                                line-height: 22px;
                                color: #253266;
                                &:nth-child(1) {
                                     min-width: 300px;
                                    padding-left: 40px;
                                }
                                &:nth-child(2) {
                                     min-width: 300px;
                                }
                                &:nth-child(3) {
                                     min-width: 150px;
                                }
                                &:nth-child(4) {
                                      min-width: 110px;
                                    padding-right: 30px;
                                 }
                            }
                        }
                        .list {
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            padding: 10px 10px;
                            border-radius: 15px;
                            border: 1px solid #B9B9B9;
                            &:not(:last-child) {
                                margin-bottom: 5px;
                            }
                            > div {
                                display: flex;
                                align-items: center;

                                &:nth-child(1) {
                                    min-width: 300px;
                                    .checkbox {
                                        width: 20px;
                                        height: 20px;
                                        border: 1px solid;
                                        border-radius: 50%;
                                        margin-right: 10px
                                    }
                                    img {
                                        margin-right: 10px
                                    }
                                }
                                &:nth-child(2) {
                                    min-width: 300px;
                                    img {
                                        margin-right: 10px;
                                    }
                                }
                                &:nth-child(3) {
                                    min-width: 150px;
                                }
                                &:nth-child(4) {
                                    display: flex;
                                    justify-content: center;
                                    min-width: 110px;
                                }
                            }
                        }
                    }
                }
            }
        }
    }



</style>
