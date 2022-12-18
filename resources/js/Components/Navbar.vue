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

                <v-list-item-title>John Leider</v-list-item-title>

                <v-btn
                    icon
                    @click.stop="mini = !mini"
                >
                    <v-icon>mdi-chevron-left</v-icon>
                </v-btn>
            </v-list-item>

            <v-divider></v-divider>

            <v-list dense>
                <v-list-item
                    v-for="item in items"
                    :key="item.title"
                    link
                >
                    <v-list-item-icon>
                        <v-icon>{{ item.icon }}</v-icon>
                    </v-list-item-icon>

                    <v-list-item-content>
                        <a class="v-list-item__title-a" :href="item.href">
                            <v-list-item-title>{{ item.title }}</v-list-item-title>
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
            items: [
                { title: 'Գլխավոր', icon: 'mdi-home-city', href: '/'},
                { title: 'Խմբեր', icon: 'mdi-account-group-outline', href: '/groups'},
                { title: 'Օգտատերեր', icon: 'mdi-account-group-outline', href: '/contacts'},
                { title: 'Ավելացնել օգտատեր', icon: 'mdi-account-plus-outline', href: '/register'},
                { title: 'Ավելացնել Կոնտակտ', icon: 'mdi-account-plus-outline', href: '/add-contact'},
            ],
            mini: true,
        }
    },
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
