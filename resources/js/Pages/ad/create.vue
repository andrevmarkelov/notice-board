<template>
    <main class="container py-5">
        <form @submit.prevent="sendForm">

            <div v-if="error" class="alert alert-danger" role="alert">{{ error }}</div>

            <div class="mb-3">
                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" v-model="form.title" maxlength="200" aria-describedby="titleHelp" required>
                <div id="titleHelp" class="form-text">{{ form.title.length }}/200</div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                <textarea class="form-control" id="description" v-model="form.description" maxlength="1000" rows="3" required></textarea>
                <div id="titleHelp" class="form-text">{{ form.description.length }}/1000</div>
            </div>

            <div class="mb-3">
                <label for="photo0" class="d-block">Photos <span class="text-danger">*</span></label>
                <div v-for="(item, index) in form.photos" :key="index" class="d-flex align-content-center gap-3 mb-4">
                    <input type="url" class="form-control" :id="'photo' + index" v-model="form.photos[index]" required>
                    <button type="button" class="btn btn-close" @click="removePhoto(index)"></button>
                </div>

                <button type="button" class="d-block m-auto btn btn-success" @click="addPhoto" v-if="form.photos.length < 3">Add photo</button>

                <div id="titleHelp" class="form-text">{{ form.photos.length }}/3</div>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="price" v-model="form.price" step="0.01" min="0" required>
            </div>

            <button type="submit" class="btn btn-primary">Create</button>
        </form>

        <div v-if="isShowModal" class="d-block modal" @click="isShowModal = !isShowModal">
            <div class="modal-dialog">
                <div class="modal-content" @click.stop>
                    <div class="modal-header">
                        <h5 class="modal-title">Success!</h5>
                        <button type="button" class="btn-close" @click="isShowModal = !isShowModal" aria-label="Close"></button>
                    </div>
                    <div class="d-flex gap-1 modal-body">
                        <p>Announcement created and available at</p>
                        <router-link :to="`/ad/${responseData.ads}`">link</router-link>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" @click="isShowModal = !isShowModal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script setup>
import {reactive, ref} from "vue";
import {createAd} from '../../Composables/api_post_methods.js';

const isShowModal = ref(false);
const responseData = ref(null);
const error = ref(null);

const form = reactive({
    title: '',
    description: '',
    photos: [],
    price: 0
});

const addPhoto = () => {
    if (form.photos.length < 3) {
        form.photos.push('');
    }
};

const removePhoto = (index) => form.photos.splice(index, 1);

const sendForm = async () => {
    error.value = null;

    const response = await createAd(form);

    if (response.ads) {
        responseData.value = response;
        isShowModal.value = true;
        return resetForm();
    }

    if (response.error) {
        return error.value = response.error;
    }
}

const resetForm = () => {
    form.title = '';
    form.description = '';
    form.photos = [];
    form.price = 0;
}
</script>

<style scoped>
.modal {
    background-color: rgba(0, 0, 0, 0.80);
}
</style>
