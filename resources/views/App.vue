<template>
    <v-app>
        <TopHeader></TopHeader>
        <v-main>
            <router-view />
        </v-main>
    </v-app>
</template>

<script>
import TopHeader from "./TopHeader.vue"

export default {
    components: {
        TopHeader
    }
}
</script>

