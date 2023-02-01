<template>
    <v-card>
        <v-navigation-drawer
        >
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
