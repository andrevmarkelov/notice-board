<template>
    <main class="container py-5">
        <form @change="fetchAdsBySort" class="d-flex align-content-center justify-content-end flex-wrap gap-3">
            <div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="radio" name="priceSort" id="priceAsc" v-model="sortFields.price" value="asc">
                    <label class="form-check-label" for="priceAsc">Price: Low to high</label>
                </div>

                <div class="form-check form-switch">
                    <input class="form-check-input" type="radio" name="priceSort" id="priceDesc" v-model="sortFields.price" value="desc">
                    <label class="form-check-label" for="priceDesc">Price: High to low</label>
                </div>
            </div>

            <div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="radio" name="dateSort" id="dateAsc" v-model="sortFields.created_at" value="asc">
                    <label class="form-check-label" for="dateAsc">Date: Oldest to newest</label>
                </div>

                <div class="form-check form-switch">
                    <input class="form-check-input" type="radio" name="dateSort" id="dateDesc" v-model="sortFields.created_at" value="desc">
                    <label class="form-check-label" for="dateDesc">Date: Newest arrivals</label>
                </div>
            </div>
        </form>

        <div class="row row-cols-2 row-cols-md-4 g-4">
            <div v-if="isLoading" v-for="index in 12" :key="index" class="col">
                <content-loader
                    viewBox="0 0 306 214"
                    :speed="2"
                    primaryColor="#f3f3f3"
                    secondaryColor="#ecebeb"
                >
                    <rect x="0" y="0" rx="0" ry="0" width="306" height="214" />
                </content-loader>
            </div>

            <div v-else v-for="item in ads.data" :key="item.id" class="col">
                <AdCard :ad-data="item" />
            </div>
        </div>

        <div v-if="ads.data?.length" class="mt-5">
            <ul class="pagination flex-wrap justify-content-center">
                <li class="page-item" v-for="link in ads.links" :key="link.label" :class="{ active: link.active }">
                    <a class="page-link" href="#" @click.prevent="fetchAds(link.url)" v-html="link.label"></a>
                </li>
            </ul>
        </div>
    </main>
</template>

<script setup>
import {onMounted, reactive, ref} from "vue";
import { ContentLoader } from "vue-content-loader"

import { getAds } from '../Composables/api_get_methods.js';
import AdCard from "../Components/AdCard.vue";

const ads = ref([]);
const isLoading = ref(true);

const sortFields = reactive({
    price: '',
    created_at: ''
});

const fetchAds = async (url) => {
    if (!url) return;

    ads.value = [];
    isLoading.value = true;

    const response = await fetch(url);
    const result = await response.json();

    ads.value = result.response.ads;
    isLoading.value = false;
};

onMounted(async() => {
    ads.value = await getAds(10);
    isLoading.value = false;
});

const fetchAdsBySort = async () => {
    const sortQuery = Object.keys(sortFields)
        .filter(field => sortFields[field])
        .map(field => `sort_fields[${field}]=${sortFields[field]}`)
        .join('&');

    const queryString = sortQuery ? `?${sortQuery}` : '';

    ads.value = [];
    isLoading.value = true;

    ads.value = await getAds(10, queryString);
    isLoading.value = false;
}
</script>
