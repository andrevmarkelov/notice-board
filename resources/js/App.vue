<template>
    <div>
        <TheHeader />

        <router-view v-slot="{ Component, route }">
            <div :key="route.name">
                <Component :is="Component" />
            </div>
        </router-view>
    </div>
</template>

<script setup lang="ts">
import TheHeader from "./Components/TheHeader.vue";
</script>
