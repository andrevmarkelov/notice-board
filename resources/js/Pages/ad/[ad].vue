<template>
    <main class="container py-5">
        <div v-if="error" class="alert alert-danger" role="alert">{{ error }}</div>

        <content-loader
            v-if="isLoading"
            viewBox="0 0 1296 398"
            :speed="2"
            primaryColor="#f3f3f3"
            secondaryColor="#ecebeb"
        >
            <rect x="0" y="0" rx="0" ry="0" width="1296" height="398" />
        </content-loader>

        <div v-else-if="!error" class="card">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-4 mb-4">
                        <img :src="ad.main_photo" :alt="ad.title" class="card-image mb-4">
                        <div v-if="ad?.photos" class="card-thumbnail d-flex gap-2 flex-wrap">
                            <div v-for="(image, index) in ad.photos" class="border">
                                <img :src="image" :alt="`${ad.title} - ${index}`">
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <h5 class="card-title">{{ ad.title }}</h5>
                        <span class="card-price">Price: {{ ad.price }}$</span>
                        <p v-if="ad?.description" class="card-description">Description: {{ ad?.description }}</p>
                    </div>
                </div>

                <form @change="fetchAds">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" v-model="fields.description" id="description">
                        <label class="form-check-label" for="description">Show description</label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" v-model="fields.photos" id="photos">
                        <label class="form-check-label" for="photos">Show photos</label>
                    </div>
                </form>
            </div>
        </div>
    </main>
</template>

<script setup>
import {ContentLoader} from "vue-content-loader";
import {useRoute} from "vue-router";
import {onMounted, reactive, ref} from "vue";

import { getAdById } from '../../Composables/api_get_methods.js';

const route = useRoute();

const ad = ref([]);
const error = ref(null);
const isLoading = ref(true);

const fields = reactive({
    description: false,
    photos: false,
});

onMounted(async() => {
    const response = await getAdById(route.params.id);

    if (response.ad) {
        ad.value = response.ad;
        return isLoading.value = false;
    }

    if (response.error) {
        isLoading.value = false;
        return error.value = response.error
    }
});

const fetchAds = async () => {
    let selectedFields = [];
    for (let key in fields) {
        if (fields[key]) selectedFields.push(key);
    }

    const query = selectedFields.length ? selectedFields.join(',') : '';
    const response = await getAdById(route.params.id, query);
    ad.value = response.ad;
}
</script>

<style scoped>
.card-image {
    width: 250px;
    height: 250px;
    object-fit: cover;
}

.card-thumbnail img {
    width: 70px;
    height: 70px;
    object-fit: cover;
}
</style>
