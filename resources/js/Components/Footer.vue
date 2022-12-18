<template>
    <v-footer
        dark
        padless
    >
        <v-card
            class="flex"
            flat
            tile
            color="green accent-4"
        >
            <v-card-text class="py-2 white--text text-center">
                {{ new Date().getFullYear() }} — <strong>Mailing</strong>
            </v-card-text>
        </v-card>
    </v-footer>
</template>
