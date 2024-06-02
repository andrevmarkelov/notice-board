<template>
    <div class="card h-100">
        <img :src="adData.main_photo" class="card-img-top m-auto" loading="lazy" :alt="adData.title">
        <div class="card-body">
            <router-link :to="`/ad/${adData.id}`" class="card-title">{{ adData.title }}</router-link>
            <p class="card-text">{{ adData.price }}$</p>
        </div>
    </div>
</template>

<script setup lang="ts">
const { adData } = defineProps({ adData: Object });
</script>

<style scoped>
.card .card-img-top {
    width: 100px;
    height: 100px;
    object-fit: contain;
}

.card .card-title {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    height: 48px;
    font-size: 16px;
    text-decoration: none;
}
</style>
