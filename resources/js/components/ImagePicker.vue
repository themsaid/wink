<script type="text/ecmascript-6">
    import $ from 'jquery';
    import _ from 'lodash';

    export default {
        props: [],

        data(){
            return {
                imageUrl: '',
                uploadProgress: 100,

                unsplashSearchTerm: '',
                unsplashPage: 1,
                searchingUnsplash: false,
                unsplashImages: [],
            }
        },


        mounted() {

        },


        watch: {
            unsplashSearchTerm(){
                this.debouncer(() => {
                    this.getImagesFromUnsplash();
                });
            }
        },


        computed: {},


        methods: {
            getImagesFromUnsplash(page = 1){
                if (!this.Config.unsplash_key) {
                    return this.alertError('Please configure your Unsplash API Key.');
                }

                this.unsplashPage = page;

                this.searchingUnsplash = true;

                this.http().get('https://api.unsplash.com/search/photos?client_id=' + Wink.unsplash_key +
                    '&orientation=landscape&per_page=19' +
                    '&query=' + this.unsplashSearchTerm +
                    '&page=' + page
                ).then(response => {
                    this.unsplashImages = response.data.results;

                    this.searchingUnsplash = false;
                }).catch(error => {
                    let errors = error.response.data.errors;

                    this.searchingUnsplash = false;
                });
            },


            /**
             * Upload the selected image.
             */
            uploadSelectedImage(){
                let file = event.target.files[0];
                let formData = new FormData();

                formData.append('image', file, file.name);

                this.$emit('uploading');

                this.http().post('/wink/api/uploads', formData, {
                    onUploadProgress: progressEvent => {
                        this.$emit('progressing', {progress: Math.round((progressEvent.loaded * 100) / progressEvent.total)});
                    }
                }).then(response => {
                    this.$emit('changed', {url: response.data.url});
                }).catch(error => {
                    console.log(error);
                });
            },


            /**
             * Select an unsplash Image.
             */
            selectUnsplashImage(image){
                this.$emit('changed', {
                    url: image.urls.regular,
                    caption: 'Photo by <a href="' + image.user.links.html + '">' + image.user.name + '</a> on <a href="https://unsplash.com">Unsplash</a>',
                });
            }
        }
    }
</script>

<template>
    <div class="imagePicker">
        <input type="file" class="d-none" :id="'imageUpload'+_uid" v-on:change="uploadSelectedImage">

        <p class="mb-0">
            Please <label :for="'imageUpload'+_uid" class="uploadLabel">upload</label> an image
            <span v-if="Config.unsplash_key">or</span>
            <input type="text" class="searchInput p-0"
                   v-if="Config.unsplash_key"
                   v-model="unsplashSearchTerm"
                   placeholder="search Unsplash">
        </p>

        <div v-if="searchingUnsplash" class="d-flex align-items-center justify-content-center card-bg-secondary p-5 bottom-radius">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="icon spin mr-2 fill-text-color">
                <path d="M12 10a2 2 0 0 1-3.41 1.41A2 2 0 0 1 10 8V0a9.97 9.97 0 0 1 10 10h-8zm7.9 1.41A10 10 0 1 1 8.59.1v2.03a8 8 0 1 0 9.29 9.29h2.02zm-4.07 0a6 6 0 1 1-7.25-7.25v2.1a3.99 3.99 0 0 0-1.4 6.57 4 4 0 0 0 6.56-1.42h2.1z"/>
            </svg>

            <span>Fetching Images...</span>
        </div>

        <div class="row" v-if="!searchingUnsplash && unsplashImages.length">
            <div class="col-lg-3 col-md-4 mb-3" v-for="image in unsplashImages">
                <img :src="image.urls.thumb" @click="selectUnsplashImage(image)"
                     class="w-100" style="cursor: pointer">
            </div>

            <div class="col-lg-3 col-md-4 mb-3 d-flex align-items-center" v-if="unsplashImages.length == 19">
                <button class="btn btn-link" @click="getImagesFromUnsplash(unsplashPage + 1)">More...</button>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .searchInput {
        border-width: 0 0 1px 0;
    }

    .searchInput:focus {
        outline: none;
    }

    .uploadLabel {
        text-decoration: underline;
        cursor: pointer;
    }
</style>
