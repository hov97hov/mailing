<template>
    <v-card>
        <v-navigation-drawer
            v-model="drawer"
            :mini-variant.sync="mini"
            permanent
        >
            <v-list-item class="px-2">
                <v-list-item-avatar>
                    <v-img src="/images/user/login-user-photo.png"></v-img>
                </v-list-item-avatar>

                <v-list-item-title>{{authUser.name}}</v-list-item-title>

                <v-btn
                    icon
                    @click.stop="mini = !mini"
                >
                    <v-icon>mdi-chevron-left</v-icon>
                </v-btn>
            </v-list-item>

            <v-divider></v-divider>

            <v-list dense>
                <v-list-item>
                    <v-list-item-icon>
                        <v-icon>mdi-home-city</v-icon>
                    </v-list-item-icon>

                    <v-list-item-content>
                        <a class="v-list-item__title-a" href="/">
                            <v-list-item-title>Գլխավոր</v-list-item-title>
                        </a>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item>
                    <v-list-item-icon>
                        <v-icon>mdi-account-group-outline</v-icon>
                    </v-list-item-icon>

                    <v-list-item-content>
                        <a class="v-list-item__title-a" href="/groups">
                            <v-list-item-title>Խմբեր</v-list-item-title>
                        </a>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item>
                    <v-list-item-icon>
                        <v-icon>mdi-account-group-outline</v-icon>
                    </v-list-item-icon>

                    <v-list-item-content>
                        <a class="v-list-item__title-a" href="/contacts">
                            <v-list-item-title>Կոնտակտներ</v-list-item-title>
                        </a>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item>
                    <v-list-item-icon>
                        <v-icon>mdi-account-group-outline</v-icon>
                    </v-list-item-icon>

                    <v-list-item-content>
                        <a class="v-list-item__title-a" href="/users">
                            <v-list-item-title>Օգտատերեր</v-list-item-title>
                        </a>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item v-if="authUser.role === 1">
                    <v-list-item-icon>
                        <v-icon>mdi-account-plus-outline</v-icon>
                    </v-list-item-icon>

                    <v-list-item-content>
                        <a class="v-list-item__title-a" href="/register">
                            <v-list-item-title>Ավելացնել օգտատեր</v-list-item-title>
                        </a>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item v-if="authUser.role === 1">
                    <v-list-item-icon>
                        <v-icon>mdi-email-plus-outline</v-icon>
                    </v-list-item-icon>

                    <v-list-item-content>
                        <a class="v-list-item__title-a" href="/emails">
                            <v-list-item-title>Էլ․ փոստերի ցուցակ</v-list-item-title>
                        </a>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>
    </v-card>
</template>
<script>
export default {
    data () {
        return {
            drawer: true,
            mini: true,
            authUser: []
        }
    },
    async created() {
        await this.getAuthUser()
    },
    methods: {
        async getAuthUser() {
            await axios.post(`/api/get-auth-user`)
                .then(response => {
                    this.authUser = response.data.authUser
                })
                .catch(e => {
                    console.log(e)
                })
        }
    }
}
</script>
<style>
    .v-navigation-drawer__content {
        height: 100vh;
    }
    .v-sheet.v-card {
        border-radius: 0;
    }
    .v-list-item:hover {
        background: #00C853 !important;
        color: #ffffff !important
    }
    .v-list-item__title-a {
        color: #000000 !important;
    }
    .v-list-item:hover  .v-list-item__title-a {
        color: #ffffff !important;
    }
    .v-list-item:hover  .theme--light.v-icon {
        color: #ffffff !important;
    }
    .v-list-item__title-a:hover {
        color: #ffffff !important;
    }
    .v-list-item__title {
        margin-left: 10px;
    }
</style>
