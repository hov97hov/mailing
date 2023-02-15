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
                    <div v-if="messageList" class="mailing-right-list-header">
                        <span>ՈՒՂԱՐԿՎԱԾ ՆԱՄԱԿ</span>
                    </div>
                    <div v-if="messageBox" class="mailing-box-content">
                        <div class="mailing-box">
                            <div class="field-attachment">
                                <div class="btn-content">
                                    <button @click="openImgLink" :class="{active : imgLink}">Նկար որպես հղում</button>
                                    <button @click="openImgButton" :class="{active : imgButton}">Նկար / կոճակ</button>
                                    <button @click="openImgText" :class="{active : imgText}">Նկար / տեքստ</button>
                                    <button @click="openImgTextButton" :class="{active : imgTextButton}">Նկար / տեքստ /կոճակ</button>
                                    <button @click="openTextButton" :class="{active : textButton}">Տեքստ / կոճակ</button>
                                    <button @click="openImgTextButtonImg" :class="{active : imgTextButtonImg}">Նկար / տեքստ /կոճակ / նկար</button>
                                </div>
                            </div>
                            <div v-if="imgLink">
                                <div class="field select-field">
                                    <select v-model="imgLinkData.from" @input="checkErrors('imgLinkData', 'from')">
                                        <option v-for="item in emailSettings" :value="item.email">{{item.email}}</option>
                                    </select>
                                    <p class="error-message setting">{{errors.imgLinkData.from[0]}}</p>
                                    <img src="/images/icon.png" alt="">
                                </div>
                                <div class="field">
                                    <v-text-field
                                        v-model="imgLinkData.to"
                                        label="Ում"
                                        solo
                                        color="#253266"
                                        class="rounded-lg"
                                    >
                                    </v-text-field>
                                </div>
                                <div class="field">
                                    <v-select
                                        v-model="imgLinkData.categoryIds"
                                        label="Ընտրել կատեգորիա"
                                        solo
                                        :items="categories"
                                        color="#253266"
                                        item-value="id"
                                        item-text="name"
                                        class="rounded-lg"
                                        multiple
                                        :error-messages="errors.imgLinkData.categoryIds"
                                        @input="checkErrors('imgLinkData', 'categoryIds')"
                                    >
                                    </v-select>
                                </div>
                                <div class="field">
                                    <v-text-field
                                        v-model="imgLinkData.subject"
                                        label="Թեմա"
                                        solo
                                        color="#253266"
                                        class="rounded-lg"
                                        :error-messages="errors.imgLinkData.subject"
                                        @input="checkErrors('imgLinkData', 'subject')"
                                    >
                                    </v-text-field>
                                </div>
                                <div class="fields">
                                    <v-file-input
                                        v-model="imgLinkData.image"
                                        label="Ընտրել նկար"
                                        prepend-icon="mdi-camera"
                                        :error-messages="errors.imgLinkData.image"
                                        @change="checkErrors('imgLinkData', 'image')"
                                    ></v-file-input>
                                </div>
                                <div class="field">
                                    <v-text-field
                                        v-model="imgLinkData.imgLink"
                                        label="Գրել նկարի հղումը"
                                        solo
                                        color="#253266"
                                        class="rounded-lg"
                                        :error-messages="errors.imgLinkData.imgLink"
                                        @input="checkErrors('imgLinkData', 'imgLink')"
                                    >
                                    </v-text-field>
                                </div>
                                <div class="bottom-field">
                                   <div>
                                       <div>
                                           <label class="checkbox-content">
                                               <input
                                                   v-model="mailingImg"
                                                   type="checkbox">
                                               <span class="checkmark"></span>
                                           </label>
                                       </div>
                                       <div>
                                           <img src="/images/mailing.png" alt="">
                                       </div>
                                   </div>
                                    <div>
                                        <div class="attach-file-text">{{ buttonText }}</div>
                                        <div class="add-photo">
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
                                                        <div class="attach-file-content">
                                                            <v-icon>mdi-attachment</v-icon>
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
                                        <button @click="sendEmailImgLink">Ուղարկել</button>
                                    </div>
                                </div>
                            </div>
                            <div v-if="imgButton">
                                <div class="field select-field">
                                    <select v-model="imgBtnData.from" @input="checkErrors('imgBtnData', 'from')">
                                        <option v-for="item in emailSettings" :value="item.email">{{item.email}}</option>
                                    </select>
                                    <p class="error-message setting">{{errors.imgBtnData.from[0]}}</p>
                                    <img src="/images/icon.png" alt="">
                                </div>
                                <div class="field">
                                    <v-text-field
                                        v-model="imgBtnData.to"
                                        label="Ում"
                                        solo
                                        color="#253266"
                                        class="rounded-lg"
                                        :error-messages="errors.imgBtnData.to"
                                        @input="checkErrors('imgBtnData', 'to')"
                                    >
                                    </v-text-field>
                                </div>
                                <div class="field">
                                    <v-select
                                        v-model="imgBtnData.categoryIds"
                                        label="Ընտրել կատեգորիա"
                                        solo
                                        :items="categories"
                                        color="#253266"
                                        item-value="id"
                                        item-text="name"
                                        class="rounded-lg"
                                        multiple
                                        :error-messages="errors.imgBtnData.categoryIds"
                                        @input="checkErrors('imgBtnData', 'categoryIds')"
                                    >
                                    </v-select>
                                </div>
                                <div class="field">
                                    <v-text-field
                                        v-model="imgBtnData.subject"
                                        label="Թեմա"
                                        solo
                                        color="#253266"
                                        class="rounded-lg"
                                        :error-messages="errors.imgBtnData.subject"
                                        @input="checkErrors('imgBtnData', 'subject')"
                                    >
                                    </v-text-field>
                                </div>
                                <div class="field">
                                    <v-file-input
                                        v-model="imgBtnData.image"
                                        label="Ընտրել նկար"
                                        outlined
                                        dense
                                        :error-messages="errors.imgBtnData.image"
                                        @change="checkErrors('imgBtnData', 'image')"
                                    ></v-file-input>
                                </div>
                                <div class="fields">
                                   <div>
                                       <v-text-field
                                           v-model="imgBtnData.btnName"
                                           label="Գրել կոճակի անունը"
                                           solo
                                           color="#253266"
                                           class="rounded-lg"
                                           :error-messages="errors.imgBtnData.btnName"
                                           @input="checkErrors('imgBtnData', 'btnName')"
                                       >
                                       </v-text-field>
                                   </div>
                                   <div>
                                       <v-text-field
                                           v-model="imgBtnData.btnLink"
                                           label="Գրել կոճակի հղումը"
                                           solo
                                           color="#253266"
                                           class="rounded-lg"
                                           :error-messages="errors.imgBtnData.btnLink"
                                           @input="checkErrors('imgBtnData', 'btnLink')"
                                       >
                                       </v-text-field>
                                   </div>
                                </div>
                                <div class="bottom-field">
                                    <div>
                                        <div>
                                            <label class="checkbox-content">
                                                <input
                                                    v-model="mailingImg"
                                                    type="checkbox">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                        <div>
                                            <img src="/images/mailing.png" alt="">
                                        </div>
                                    </div>
                                    <div>
                                        <div class="attach-file-text">{{ buttonText }}</div>
                                        <div class="add-photo">
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
                                                        <div class="attach-file-content">
                                                            <v-icon>mdi-attachment</v-icon>
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
                                        <button @click="sendEmailImgBtn">Ուղարկել</button>
                                    </div>
                                </div>
                            </div>
                            <div v-if="imgText">
                                <div class="field select-field">
                                    <select v-model="imgTextData.from" @input="checkErrors('imgTextData', 'from')">
                                        <option v-for="item in emailSettings" :value="item.email">{{item.email}}</option>
                                    </select>
                                    <p class="error-message setting">{{errors.imgTextData.from[0]}}</p>
                                    <img src="/images/icon.png" alt="">
                                </div>
                                <div class="field">
                                    <v-text-field
                                        v-model="imgTextData.to"
                                        label="Ում"
                                        solo
                                        color="#253266"
                                        class="rounded-lg"
                                    >
                                    </v-text-field>
                                </div>
                                <div class="field">
                                    <v-select
                                        v-model="imgTextData.categoryIds"
                                        label="Ընտրել կատեգորիա"
                                        solo
                                        :items="categories"
                                        color="#253266"
                                        item-value="id"
                                        item-text="name"
                                        class="rounded-lg"
                                        multiple
                                        :error-messages="errors.imgTextData.categoryIds"
                                        @input="checkErrors('imgTextData', 'categoryIds')"
                                    />
                                </div>
                                <div class="field">
                                    <v-text-field
                                        v-model="imgTextData.subject"
                                        label="Թեմա"
                                        solo
                                        color="#253266"
                                        class="rounded-lg"
                                        :error-messages="errors.imgTextData.categoryIds"
                                        @input="checkErrors('imgTextData', 'categoryIds')"
                                    >
                                    </v-text-field>
                                </div>
                                <div class="field">
                                    <v-file-input
                                        v-model="imgTextData.image"
                                        label="Ընտրել նկար"
                                        outlined
                                        dense
                                        :error-messages="errors.imgTextData.image"
                                        @change="checkErrors('imgTextData', 'image')"
                                    ></v-file-input>
                                </div>
                                <div class="field">
                                    <vue-editor
                                        v-model="imgTextData.text"
                                        @input="checkErrors('imgTextData', 'text')"
                                    ></vue-editor>
                                    <p class="error-message">{{errors.imgTextData.text[0]}}</p>
                                </div>
                                <div class="bottom-field">
                                    <div>
                                        <div>
                                            <label class="checkbox-content">
                                                <input
                                                    v-model="mailingImg"
                                                    type="checkbox">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                        <div>
                                            <img src="/images/mailing.png" alt="">
                                        </div>
                                    </div>
                                    <div>
                                        <div class="attach-file-text">{{ buttonText }}</div>
                                        <div class="add-photo">
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
                                                        <div class="attach-file-content">
                                                            <v-icon>mdi-attachment</v-icon>
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
                                        <button @click="sendEmailImgTextData">Ուղարկել</button>
                                    </div>
                                </div>
                            </div>
                            <div v-if="imgTextButton">
                                <div class="field select-field">
                                    <select v-model="imgTextButtonData.from" @input="checkErrors('imgTextButtonData', 'from')">
                                        <option v-for="item in emailSettings" :value="item.email">{{item.email}}</option>
                                    </select>
                                    <p class="error-message setting">{{errors.imgTextButtonData.from[0]}}</p>
                                    <img src="/images/icon.png" alt="">
                                </div>
                                <div class="field">
                                    <v-text-field
                                        v-model="imgTextButtonData.to"
                                        label="Ում"
                                        solo
                                        color="#253266"
                                        class="rounded-lg"
                                    >
                                    </v-text-field>
                                </div>
                                <div class="field">
                                    <v-select
                                        v-model="imgTextButtonData.categoryIds"
                                        label="Ընտրել կատեգորիա"
                                        solo
                                        :items="categories"
                                        color="#253266"
                                        item-value="id"
                                        item-text="name"
                                        class="rounded-lg"
                                        multiple
                                        :error-messages="errors.imgTextButtonData.categoryIds"
                                        @input="checkErrors('imgTextButtonData', 'categoryIds')"
                                    />
                                </div>
                                <div class="field">
                                    <v-text-field
                                        v-model="imgTextButtonData.subject"
                                        label="Թեմա"
                                        solo
                                        color="#253266"
                                        class="rounded-lg"
                                        :error-messages="errors.imgTextButtonData.subject"
                                        @input="checkErrors('imgTextButtonData', 'subject')"
                                    >
                                    </v-text-field>
                                </div>
                                <div class="field">
                                    <v-file-input
                                        v-model="imgTextButtonData.image"
                                        label="Ընտրել նկար"
                                        outlined
                                        dense
                                        :error-messages="errors.imgTextButtonData.image"
                                        @change="checkErrors('imgTextButtonData', 'image')"
                                    ></v-file-input>
                                </div>
                                <div class="fields">
                                    <div>
                                        <v-text-field
                                            v-model="imgTextButtonData.btnName"
                                            label="Գրել կոճակի անունը"
                                            solo
                                            color="#253266"
                                            class="rounded-lg"
                                            :error-messages="errors.imgTextButtonData.btnName"
                                            @input="checkErrors('imgTextButtonData', 'btnName')"
                                        >
                                        </v-text-field>
                                    </div>
                                    <div>
                                        <v-text-field
                                            v-model="imgTextButtonData.btnLink"
                                            label="Գրել կոճակի հղումը"
                                            solo
                                            color="#253266"
                                            class="rounded-lg"
                                            :error-messages="errors.imgTextButtonData.btnLink"
                                            @input="checkErrors('imgTextButtonData', 'btnLink')"
                                        >
                                        </v-text-field>
                                    </div>
                                </div>
                                <div class="field">
                                    <vue-editor
                                        v-model="imgTextButtonData.text"
                                        @input="checkErrors('imgTextButtonData', 'text')"
                                    ></vue-editor>
                                    <p class="error-message">{{errors.imgTextButtonData.text[0]}}</p>
                                </div>
                                <div class="bottom-field">
                                    <div>
                                        <div>
                                            <label class="checkbox-content">
                                                <input
                                                    v-model="mailingImg"
                                                    type="checkbox">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                        <div>
                                            <img src="/images/mailing.png" alt="">
                                        </div>
                                    </div>
                                    <div>
                                        <div class="attach-file-text">{{ buttonText }}</div>
                                        <div class="add-photo">
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
                                                        <div class="attach-file-content">
                                                            <v-icon>mdi-attachment</v-icon>
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
                                        <button @click="sendEmailImgTextButton">Ուղարկել</button>
                                    </div>
                                </div>
                            </div>
                            <div v-if="textButton">
                                <div class="field select-field">
                                    <select v-model="textButtonData.from" @input="checkErrors('textButtonData', 'from')">
                                        <option v-for="item in emailSettings" :value="item.email">{{item.email}}</option>
                                    </select>
                                    <p class="error-message setting">{{errors.textButtonData.from[0]}}</p>
                                    <img src="/images/icon.png" alt="">
                                </div>
                                <div class="field">
                                    <v-text-field
                                        v-model="textButtonData.to"
                                        label="Ում"
                                        solo
                                        color="#253266"
                                        class="rounded-lg"
                                    >
                                    </v-text-field>
                                </div>
                                <div class="field">
                                    <v-select
                                        v-model="textButtonData.categoryIds"
                                        label="Ընտրել կատեգորիա"
                                        solo
                                        :items="categories"
                                        color="#253266"
                                        item-value="id"
                                        item-text="name"
                                        class="rounded-lg"
                                        multiple
                                        :error-messages="errors.textButtonData.categoryIds"
                                        @input="checkErrors('textButtonData', 'categoryIds')"
                                    />
                                </div>
                                <div class="field">
                                    <v-text-field
                                        v-model="textButtonData.subject"
                                        label="Թեմա"
                                        solo
                                        color="#253266"
                                        class="rounded-lg"
                                        :error-messages="errors.textButtonData.subject"
                                        @input="checkErrors('textButtonData', 'subject')"
                                    >
                                    </v-text-field>
                                </div>
                                <div class="fields">
                                    <div>
                                        <v-text-field
                                            v-model="textButtonData.btnName"
                                            label="Գրել կոճակի անունը"
                                            solo
                                            color="#253266"
                                            class="rounded-lg"
                                            :error-messages="errors.textButtonData.btnName"
                                            @input="checkErrors('textButtonData', 'btnName')"
                                        >
                                        </v-text-field>
                                    </div>
                                    <div>
                                        <v-text-field
                                            v-model="textButtonData.btnLink"
                                            label="Գրել կոճակի հղումը"
                                            solo
                                            color="#253266"
                                            class="rounded-lg"
                                            :error-messages="errors.textButtonData.btnLink"
                                            @input="checkErrors('textButtonData', 'btnLink')"
                                        >
                                        </v-text-field>
                                    </div>
                                </div>
                                <div class="field">
                                    <vue-editor
                                        v-model="textButtonData.text"
                                        @input="checkErrors('textButtonData', 'text')"
                                    ></vue-editor>
                                    <p class="error-message">{{errors.textButtonData.text[0]}}</p>
                                </div>
                                <div class="bottom-field">
                                    <div>
                                        <div>
                                            <label class="checkbox-content">
                                                <input
                                                    v-model="mailingImg"
                                                    type="checkbox">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                        <div>
                                            <img src="/images/mailing.png" alt="">
                                        </div>
                                    </div>
                                    <div>
                                        <div class="attach-file-text">{{ buttonText }}</div>
                                        <div class="add-photo">
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
                                                        <div class="attach-file-content">
                                                            <v-icon>mdi-attachment</v-icon>
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
                                        <button @click="sendEmailTextButton">Ուղարկել</button>
                                    </div>
                                </div>
                            </div>
                            <div v-if="imgTextButtonImg">
                                <div class="field select-field">
                                    <select v-model="imgTextButtonImgData.from" @input="checkErrors('imgTextButtonImgData', 'from')">
                                        <option v-for="item in emailSettings" :value="item.email">{{item.email}}</option>
                                    </select>
                                    <p class="error-message setting">{{errors.imgTextButtonImgData.from[0]}}</p>
                                    <img src="/images/icon.png" alt="">
                                </div>
                                <div class="field">
                                    <v-text-field
                                        v-model="imgTextButtonImgData.to"
                                        label="Ում"
                                        solo
                                        color="#253266"
                                        class="rounded-lg"
                                    >
                                    </v-text-field>
                                </div>
                                <div class="field">
                                    <v-select
                                        v-model="imgTextButtonImgData.categoryIds"
                                        label="Ընտրել կատեգորիա"
                                        solo
                                        :items="categories"
                                        color="#253266"
                                        item-value="id"
                                        item-text="name"
                                        class="rounded-lg"
                                        multiple
                                        :error-messages="errors.imgTextButtonImgData.categoryIds"
                                        @input="checkErrors('imgTextButtonImgData', 'categoryIds')"
                                    />
                                </div>
                                <div class="field">
                                    <v-text-field
                                        v-model="imgTextButtonImgData.subject"
                                        label="Թեմա"
                                        solo
                                        color="#253266"
                                        class="rounded-lg"
                                        :error-messages="errors.imgTextButtonImgData.subject"
                                        @input="checkErrors('imgTextButtonImgData', 'subject')"
                                    >
                                    </v-text-field>
                                </div>
                                <div class="fields">
                                    <v-file-input
                                        v-model="imgTextButtonImgData.image"
                                        label="Ընտրել առաջին նկարը"
                                        outlined
                                        dense
                                        :error-messages="errors.imgTextButtonImgData.image"
                                        @change="checkErrors('imgTextButtonImgData', 'image')"
                                    ></v-file-input>
                                    <v-file-input
                                        v-model="imgTextButtonImgData.image2"
                                        label="Ընտրել երկրորդ նկարը"
                                        outlined
                                        dense
                                        :error-messages="errors.imgTextButtonImgData.image2"
                                        @change="checkErrors('imgTextButtonImgData', 'image2')"
                                    ></v-file-input>
                                </div>
                                <div class="fields">
                                    <div>
                                        <v-text-field
                                            v-model="imgTextButtonImgData.btnName"
                                            label="Գրել կոճակի անունը"
                                            solo
                                            color="#253266"
                                            class="rounded-lg"
                                            :error-messages="errors.imgTextButtonImgData.btnName"
                                            @input="checkErrors('imgTextButtonImgData', 'btnName')"
                                        >
                                        </v-text-field>
                                    </div>
                                    <div>
                                        <v-text-field
                                            v-model="imgTextButtonImgData.btnLink"
                                            label="Գրել կոճակի հղումը"
                                            solo
                                            color="#253266"
                                            class="rounded-lg"
                                            :error-messages="errors.imgTextButtonImgData.btnLink"
                                            @input="checkErrors('imgTextButtonImgData', 'btnLink')"
                                        >
                                        </v-text-field>
                                    </div>
                                </div>
                                <div class="field">
                                    <vue-editor
                                        v-model="imgTextButtonImgData.text"
                                        @input="checkErrors('imgTextButtonImgData', 'text')"
                                    ></vue-editor>
                                    <p class="error-message">{{errors.imgTextButtonImgData.text[0]}}</p>
                                </div>
                                <div class="bottom-field">
                                    <div>
                                        <div>
                                            <label class="checkbox-content">
                                                <input
                                                    v-model="mailingImg"
                                                    type="checkbox">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                        <div>
                                            <img src="/images/mailing.png" alt="">
                                        </div>
                                    </div>
                                    <div>
                                        <div class="attach-file-text">{{ buttonText }}</div>
                                        <div class="add-photo">
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
                                                        <div class="attach-file-content">
                                                            <v-icon>mdi-attachment</v-icon>
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
                                        <button @click="sendEmailImgTextButtonImg">Ուղարկել</button>
                                    </div>
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
                                    @input="searchMessageData"
                                ></v-text-field>
                            </div>
                           <div>
                               <img v-if="selectedMessages.length" @click="deleteMessageDialog = true" src="/images/removeIcon.png" alt="">
                           </div>
                       </div>
                        <div class="message-list">
                            <div class="header">
                                <div>Ումից</div>
                                <div>Ում</div>
                                <div>Թեմա</div>
                                <div>Ամսաթիվ</div>
                            </div>
                            <div v-for="item in messages" class="list">
                                <div>
                                    <label class="checkbox-content">
                                        <input v-model="selectedMessages" :value="item.id" type="checkbox">
                                        <span class="checkmark"></span>
                                    </label>
                                    <div class="img"><img src="/images/Group.png" alt=""></div>
                                    <div style="cursor: pointer" @click="openModalDialog(item)" class="name">{{item.from}}</div>
                                </div>
                                <div>
                                    <img style="width: 25px; height: 25px; border-radius: 50%; object-fit: cover" :src="item.group ? item.group.image : '/images/Group.png'" alt="">
                                    <div>{{item.group ? item.group.name : item.email}}</div>
                                </div>
                                <div>
                                   <div style="
                                        width: 100px;
                                        overflow: hidden;
                                        display: -webkit-box;
                                        -webkit-line-clamp: 1;
                                        -webkit-box-orient: vertical;
                                        word-break: break-all;"
                                        v-html="item.subject"
                                   ></div>
                                </div>
                                <div>
                                    <div>{{item.created_at}}</div>
                                </div>
                            </div>
<!--                            <v-pagination-->
<!--                                class="users-pagination"-->
<!--                                v-model="pagination.current"-->
<!--                                :length="pagination.total"-->
<!--                                :total-visible="7"-->
<!--                                @input="onPageChange"-->
<!--                            ></v-pagination>-->
                        </div>
                    </div>
                </div>
           </div>
        </div>
        <loader v-if="loading" object="#03c200" color1="#ffffff" color2="#1fd13d" size="5" speed="2" bg="#343a40" objectbg="#999793" opacity="80" disableScrolling="false" name="dots"></loader>
        <!--NOTIFICATION-->
        <notifications group="auth"/>
        <v-dialog
            v-model="dialog"
            width="500"
        >
            <v-card>
                <v-card-title class="dialog-message-title">
                    <span class="text-h5">ՈՒՂԱՐԿՎԱԾ ՆԱՄԱԿ</span>
                </v-card-title>
                <v-card-text>
                    <div class="container message-modal-block">
                        <div v-for="item in messageData.file" class="image-content">
                            <a v-if="item.image_path" :href="item.image_link">
                                <img style="width: 100%" :src="item.image_path" alt="">
                            </a>
                        </div>
                        <div class="message-content" v-html="messageData.message"></div>
                        <div class="btn-content">
                            <a v-if="messageData.btn_link" :href="messageData.btn_link">
                               {{messageData.btn_name}}
                            </a>
                        </div>
                        <div v-for="item in messageData.file" class="image-content">
                            <a v-if="item.image_bottom_path" :href="item.image_link">
                                <img style="width: 100%" :src="item.image_bottom_path" alt="">
                            </a>
                        </div>
                        <div class="checked-img">
                            <img v-if="messageData.mailing_image == 1" src="/images/mailing.png" alt="">
                        </div>
                        <div v-for="item in messageData.file" class="image-content">
                           <a v-if="item.file_path" :href="item.file_path">Ֆայլ - {{item.file_name}}</a>
                        </div>
                    </div>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="#253266 dark"
                        variant="text"
                        @click="dialog = false"
                    >
                        Փակել
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!--Delete Message-->
        <v-dialog
            v-model="deleteMessageDialog"
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
                        @click="deleteMessageDialog = false"
                    >
                        Չեղարկել
                    </v-btn>

                    <v-btn
                        color="#253266"
                        text
                        @click="deleteSelectedMessages"
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
            pagination: {
                current: 1,
                total: 0
            },
            loading: false,
            imgLink: true,
            imgButton: false,
            isSelecting: false,
            imgText: false,
            imgTextButton: false,
            textButton: false,
            deleteMessageDialog: false,
            imgTextButtonImg: false,
            messageList: false,
            messageBox: true,
            dialog: false,
            search: '',
            isSelectingTopImg: false,
            isSelectingBottomImg: false,
            selectedFileTopImg: null,
            selectedFileBottomImg: null,
            selectedFile: null,
            selectedMessages: [],
            emailSettings: [],
            categories: [],
            messages: [],
            messageData: [],
            mailingImg: null,
            imgLinkData: {
                from: '',
                to: '',
                categoryIds: '',
                subject: '',
                image: null,
                imgLink: '',
            },

            imgBtnData: {
                from: '',
                to: '',
                categoryIds: '',
                subject: '',
                image: null,
                btnName: '',
                btnLink: '',
            },

            imgTextData: {
                from: '',
                to: '',
                categoryIds: '',
                subject: '',
                image: null,
                text: '',
            },

            imgTextButtonData: {
                from: '',
                to: '',
                categoryIds: '',
                subject: '',
                image: null,
                text: '',
                btnName: '',
                btnLink: '',
            },

            textButtonData: {
                from: '',
                to: '',
                categoryIds: '',
                subject: '',
                text: '',
                btnName: '',
                btnLink: '',
            },

            imgTextButtonImgData: {
                from: '',
                to: '',
                categoryIds: '',
                subject: '',
                image: null,
                image2: null,
                text: '',
                btnName: '',
                btnLink: '',
            },

            errors: {
                imgLinkData: {
                    from: '',
                    categoryIds: '',
                    subject: '',
                    image: '',
                    imgLink: '',
                },

                imgBtnData: {
                    from: '',
                    to: '',
                    categoryIds: '',
                    subject: '',
                    image: '',
                    btnName: '',
                    btnLink: '',
                },

                imgTextData: {
                    from: '',
                    to: '',
                    categoryIds: '',
                    subject: '',
                    image: '',
                    text: '',
                },

                imgTextButtonData: {
                    from: '',
                    to: '',
                    categoryIds: '',
                    subject: '',
                    image: '',
                    text: '',
                    btnName: '',
                    btnLink: '',
                },

                textButtonData: {
                    from: '',
                    to: '',
                    categoryIds: '',
                    subject: '',
                    text: '',
                    btnName: '',
                    btnLink: '',
                },

                imgTextButtonImgData: {
                    from: '',
                    to: '',
                    categoryIds: '',
                    subject: '',
                    image: '',
                    image2: '',
                    text: '',
                    btnName: '',
                    btnLink: '',
                },
            }
        }
    },

    async created() {
        await this.getEmailSetting()
        await this.getCategory()
        await this.getMessages()
        this.imgLinkData.from = this.emailSettings[0].email
        this.imgBtnData.from = this.emailSettings[0].email
        this.imgTextData.from = this.emailSettings[0].email
        this.imgTextButtonData.from = this.emailSettings[0].email
        this.textButtonData.from = this.emailSettings[0].email
        this.imgTextButtonImgData.from = this.emailSettings[0].email

        if (window.location.search === '?messages') {
            this.messageBox = false
            this.messageList = true
        }
    },

    computed: {
        buttonText() {
            return this.selectedFile ? this.selectedFile.name : this.defaultButtonText
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


        onPageChange() {
            this.getUsers();
        },

        async searchMessageData() {
            const formData =  new FormData()
            formData.append('query', this.search)

            await axios.post('/api/search-messages', formData).then(response => {
                this.messages = response.data.messages
            }).catch(error => {
                console.log(error)
            })
        },

        openModalDialog(data) {
            this.messageData = data
            this.dialog = true
        },

        async getMessages() {
            await axios.get('/api/get-messages?page=' + this.pagination.current).then(response => {
                this.messages = response.data.messages
                this.pagination.current = response.data.current_page;
                this.pagination.total = response.data.last_page;
            }).catch(error => {
                console.log(error)
            })
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

        openImgLink() {
            this.imgLink = true
            this.imgButton = false
            this.imgText = false
            this.imgTextButton = false
            this.textButton = false
            this.imgTextButtonImg = false
        },

        openImgButton() {
            this.imgLink = false
            this.imgButton = true
            this.imgText = false
            this.imgTextButton = false
            this.textButton = false
            this.imgTextButtonImg = false
        },

        openImgText() {
            this.imgLink = false
            this.imgButton = false
            this.imgText = true
            this.imgTextButton = false
            this.textButton = false
            this.imgTextButtonImg = false
        },

        openImgTextButton() {
            this.imgLink = false
            this.imgButton = false
            this.imgText = false
            this.imgTextButton = true
            this.textButton = false
            this.imgTextButtonImg = false
        },

        openTextButton() {
            this.imgLink = false
            this.imgButton = false
            this.imgText = false
            this.imgTextButton = false
            this.textButton = true
            this.imgTextButtonImg = false
        },

        openImgTextButtonImg() {
            this.imgLink = false
            this.imgButton = false
            this.imgText = false
            this.imgTextButton = false
            this.textButton = false
            this.imgTextButtonImg = true
        },

        async getEmailSetting() {
            await axios.post('/api/get-emails-setting').then(response => {
                this.emailSettings = response.data.emails
            }).catch(error => {
                console.log(error)
            })
        },

        async getCategory() {
            await axios.post('/api/get-category').then(response => {
                this.categories = response.data.categories
            }).catch(error => {
                console.log(error)
            })
        },

        closeMessageBox() {
            this.messageBox = false
            this.messageList = true
        },

        openMessageBox() {
            this.messageBox = true
            this.messageList = false
        },

        async sendEmailImgLink() {
            this.loading = true;
            const formData = new FormData()
            formData.append('from', this.imgLinkData.from)
            formData.append('to', this.imgLinkData.to)
            formData.append('categoryIds', this.imgLinkData.categoryIds)
            formData.append('subject', this.imgLinkData.subject)
            formData.append('image', this.imgLinkData.image ?? '')
            formData.append('imgLink', this.imgLinkData.imgLink)
            formData.append('file', this.selectedFile)
            formData.append('mailingImg', this.mailingImg ? '/images/mailing.png' : null)

            await axios.post('/api/send-email-img-link', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(response => {
                this.$notify({
                    group: 'auth',
                    type: 'success',
                    text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Նամակը հաջողությամբ ուղարկված է' ,
                    duration: 1000,
                    speed: 1000
                })
                this.imgLinkData.from = ''
                this.imgLinkData.to = ''
                this.imgLinkData.categoryIds = ''
                this.imgLinkData.subject = ''
                this.imgLinkData.image = ''
                this.imgLinkData.imgLink = ''
                this.selectedFile = ''
                this.mailingImg = ''
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
                this.errors.imgLinkData = Object.assign(this.errors.imgLinkData, error.response.data.errors)
            })

        },

        async sendEmailImgBtn() {
            this.loading = true;
            const formData = new FormData()
            formData.append('from', this.imgBtnData.from)
            formData.append('to', this.imgBtnData.to)
            formData.append('categoryIds', this.imgBtnData.categoryIds)
            formData.append('subject', this.imgBtnData.subject)
            formData.append('image', this.imgBtnData.image ?? '')
            formData.append('btnLink', this.imgBtnData.btnLink)
            formData.append('btnName', this.imgBtnData.btnName)
            formData.append('file', this.selectedFile)
            formData.append('mailingImg', this.mailingImg ? '/images/mailing.png' : null)

            await axios.post('/api/send-email-img-btn', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(response => {
                this.$notify({
                    group: 'auth',
                    type: 'success',
                    text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Նամակը հաջողությամբ ուղարկված է' ,
                    duration: 1000,
                    speed: 1000
                })
                this.imgBtnData.from = ''
                this.imgBtnData.to = ''
                this.imgBtnData.categoryIds = ''
                this.imgBtnData.subject = ''
                this.imgBtnData.image = ''
                this.imgBtnData.btnLink = ''
                this.imgBtnData.btnName = ''
                this.selectedFile = ''
                this.mailingImg = ''
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
                this.errors.imgBtnData = Object.assign(this.errors.imgBtnData, error.response.data.errors)
            })

        },

        async sendEmailImgTextData() {
            this.loading = true;
            const formData = new FormData()
            formData.append('from', this.imgTextData.from)
            formData.append('to', this.imgTextData.to)
            formData.append('categoryIds', this.imgTextData.categoryIds)
            formData.append('subject', this.imgTextData.subject)
            formData.append('image', this.imgTextData.image ?? '')
            formData.append('text', this.imgTextData.text)
            formData.append('file', this.selectedFile)
            formData.append('mailingImg', this.mailingImg ? '/images/mailing.png' : null)

            await axios.post('/api/send-email-img-text', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(response => {
                this.$notify({
                    group: 'auth',
                    type: 'success',
                    text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Նամակը հաջողությամբ ուղարկված է' ,
                    duration: 1000,
                    speed: 1000
                })
                this.imgTextData.from = ''
                this.imgTextData.to = ''
                this.imgTextData.categoryIds = ''
                this.imgTextData.subject = ''
                this.imgTextData.image = ''
                this.imgTextData.text = ''
                this.selectedFile = ''
                this.mailingImg = ''
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
                this.errors.imgTextData = Object.assign(this.errors.imgTextData, error.response.data.errors)
            })

        },

        async sendEmailImgTextButton() {
            this.loading = true;
            const formData = new FormData()
            formData.append('from', this.imgTextButtonData.from)
            formData.append('to', this.imgTextButtonData.to)
            formData.append('categoryIds', this.imgTextButtonData.categoryIds)
            formData.append('subject', this.imgTextButtonData.subject)
            formData.append('image', this.imgTextButtonData.image ?? '')
            formData.append('text', this.imgTextButtonData.text)
            formData.append('btnLink', this.imgTextButtonData.btnLink)
            formData.append('btnName', this.imgTextButtonData.btnName)
            formData.append('file', this.selectedFile)
            formData.append('mailingImg', this.mailingImg ? '/images/mailing.png' : null)

            await axios.post('/api/send-email-img-text-btn', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(response => {
                this.$notify({
                    group: 'auth',
                    type: 'success',
                    text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Նամակը հաջողությամբ ուղարկված է' ,
                    duration: 1000,
                    speed: 1000
                })
                this.imgTextButtonData.from = ''
                this.imgTextButtonData.to = ''
                this.imgTextButtonData.categoryIds = ''
                this.imgTextButtonData.subject = ''
                this.imgTextButtonData.image = ''
                this.imgTextButtonData.btnLink = ''
                this.imgTextButtonData.btnName = ''
                this.imgTextButtonData.text = ''
                this.selectedFile = ''
                this.mailingImg = ''
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
                this.errors.imgTextButtonData = Object.assign(this.errors.imgTextButtonData, error.response.data.errors)
            })

        },

        async sendEmailTextButton() {
            this.loading = true;
            const formData = new FormData()
            formData.append('from', this.textButtonData.from)
            formData.append('to', this.textButtonData.to)
            formData.append('categoryIds', this.textButtonData.categoryIds)
            formData.append('subject', this.textButtonData.subject)
            formData.append('text', this.textButtonData.text)
            formData.append('btnLink', this.textButtonData.btnLink)
            formData.append('btnName', this.textButtonData.btnName)
            formData.append('file', this.selectedFile)
            formData.append('mailingImg', this.mailingImg ? '/images/mailing.png' : null)

            await axios.post('/api/send-email-text-btn', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(response => {
                this.$notify({
                    group: 'auth',
                    type: 'success',
                    text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Նամակը հաջողությամբ ուղարկված է' ,
                    duration: 1000,
                    speed: 1000
                })
                this.textButtonData.from = ''
                this.textButtonData.to = ''
                this.textButtonData.categoryIds = ''
                this.textButtonData.subject = ''
                this.textButtonData.btnLink = ''
                this.textButtonData.btnName = ''
                this.textButtonData.text = ''
                this.selectedFile = ''
                this.mailingImg = ''
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
                this.errors.textButtonData = Object.assign(this.errors.textButtonData, error.response.data.errors)
            })

        },

        async sendEmailImgTextButtonImg() {
            this.loading = true;
            const formData = new FormData()
            formData.append('from', this.imgTextButtonImgData.from)
            formData.append('to', this.imgTextButtonImgData.to)
            formData.append('categoryIds', this.imgTextButtonImgData.categoryIds)
            formData.append('subject', this.imgTextButtonImgData.subject)
            formData.append('text', this.imgTextButtonImgData.text)
            formData.append('btnLink', this.imgTextButtonImgData.btnLink)
            formData.append('btnName', this.imgTextButtonImgData.btnName)
            formData.append('image', this.imgTextButtonImgData.image ?? '')
            formData.append('image2', this.imgTextButtonImgData.image2 ?? '')
            formData.append('file', this.selectedFile)
            formData.append('mailingImg', this.mailingImg ? '/images/mailing.png' : null)

            await axios.post('/api/send-email-img-text-btn-img', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(response => {
                this.$notify({
                    group: 'auth',
                    type: 'success',
                    text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Նամակը հաջողությամբ ուղարկված է' ,
                    duration: 1000,
                    speed: 1000
                })
                this.imgTextButtonImgData.from = ''
                this.imgTextButtonImgData.to = ''
                this.imgTextButtonImgData.categoryIds = ''
                this.imgTextButtonImgData.subject = ''
                this.imgTextButtonImgData.btnLink = ''
                this.imgTextButtonImgData.btnName = ''
                this.imgTextButtonImgData.text = ''
                this.imgTextButtonImgData.image = ''
                this.imgTextButtonImgData.image2 = ''
                this.selectedFile = ''
                this.mailingImg = ''
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
                this.errors.imgTextButtonImgData = Object.assign(this.errors.imgTextButtonImgData, error.response.data.errors)
            })

        },

        async deleteSelectedMessages() {
            this.loading = true
            await axios.post('/api/delete-selected-messages', {ids:this.selectedMessages}).then(response => {
                this.$notify({
                    group: 'auth',
                    type: 'success',
                    text: '<i class="fa fa-check-circle" aria-hidden="true"></i> Նամակները հաջողությամբ ջնջվել է' ,
                    duration: 1000,
                    speed: 1000
                })
                this.loading = false
                this.getEmailSetting()
                this.getCategory()
                this.getMessages()
                this.deleteMessageDialog  = false
                this.selectedMessages  = ''
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
.dialog-message-title {
    font-family: 'Arial AMU';
    font-style: normal;
    font-weight: 400;
    font-size: 30px;
    line-height: 34px;
    color: #253266;
    padding-bottom: 20px;
    border-bottom: 1px solid #253266;
    justify-content: center;
}
.field {
    select {
        width: 100%;
        height: 40px;
        box-shadow: unset !important;
        background: #EAEAEA !important;
        color: rgba(0,0,0,.87) !important;
        border-radius: 15px !important;
        padding: 0 12px;
        outline: 0;
    }
    .error-message {
        &.setting {
            margin-bottom: 25px;
        }
    }
}
.select-field {
    position: relative;
    > img {
        position: absolute;
        right: 15px;
        top: 15px;
    }
}
.message-modal-block {
    .image-content {
        margin-bottom: 30px;
    }
    .message-content {
        margin-bottom: 30px;
    }
    .btn-content {
        display: flex;
        justify-content: center;
        a {
            background: #253266;
            color: #ffffff;
            padding: 10px 30px;
            text-decoration: none;
            font-size: 18px;
            line-height: 22px;
            border-radius: 6px;
        }
    }
}
.error-message {
    color: red !important;
    font-size: 12px;
    line-height: 12px;
    margin: 5px 0;
}
.text-none {
    height: 45px !important;
    margin-right: 15px;
}
.attach-file-text {
    width: 90px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    word-break: break-all;
    margin-right: 10px;
}
    .fields {
        display: flex;
        justify-content: space-between;
        > div {
            width: calc(100% / 2 - 20px);
        }
    }
    .field-attachment {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        > .btn-content {
            margin-left: 10px;
            margin-top: -6px;
            button {
                &.active {
                    background: #253266;
                    color: #ffffff;
                }
            }
        }
        > div {
            width: 100%;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            button {
                width: 30%;
                background: #EAEAEA;
                height: 45px;
                border-radius: 5px;
                color: #253266;
                font-family: "Inter";
                font-style: normal;
                font-weight: 400;
                font-size: 18px;
                line-height: 18px;
                margin-right: 20px;
                margin-bottom: 20px;
            }
        }
    }
    .btn-text {
        width: 140px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        word-break: break-all;
        margin-top: 10px;
    }
    .bottom-field {
        display: flex;
        align-items: center;
        justify-content: space-between;
        > div {
            &:last-child {
                display: flex;
            }
        }
        button {
            height: 45px;
            padding: 0 50px;
            border: 0;
            background: #253266;
            color: #ffffff;
            font-size: 18px;
            border-radius: 5px;
        }
        img {
            margin-left: 20px;
        }
        .checkbox-content {
            margin: 0;
            .checkmark {
                top: 20px;
            }
        }
    }
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
