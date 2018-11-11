<script type="text/ecmascript-6">
    import axios from 'axios';
    import _ from 'lodash';

    export default {
        props: [],

        data(){
            return {
                imageUrl: '',
                uploadProgress: 100,

                selectedUnsplashImageId: null,

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
                if (!Wink.unsplash_key) {
                    return this.alertError('Please configure your Unsplash API Key.');
                }

                this.unsplashPage = page;

                this.searchingUnsplash = true;

                axios.get('https://api.unsplash.com/search/photos?client_id=' + Wink.unsplash_key +
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

                this.http().post('/api/uploads', formData, {
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
                this.selectedUnsplashImageId = image.id;

                this.$emit('changed', {
                    url: image.urls.regular,
                    caption: 'Photo by <a href="' + image.user.links.html + '">' + image.user.name + '</a> on <a href="https://unsplash.com">Unsplash</a>',
                });
            }
        }
    }
</script>

<template>
    <div>
        <input type="file" class="hidden" :id="'imageUpload'+_uid" accept="image/*" v-on:change="uploadSelectedImage">

        <div class="mb-0">
            Please <label :for="'imageUpload'+_uid" class="cursor-pointer underline">upload</label> an image
            <span v-if="Wink.unsplash_key">or</span>
            <input type="text" class="border-b border-very-light focus:outline-none"
                   v-if="Wink.unsplash_key"
                   v-model="unsplashSearchTerm"
                   placeholder="search Unsplash">
        </div>

        <preloader v-if="searchingUnsplash" class="mt-10"></preloader>

        <div v-if="!searchingUnsplash && unsplashImages.length" class="flex flex-wrap mt-5">
            <div class="w-1/2 p-1 cursor-pointer" v-for="image in unsplashImages" @click="selectUnsplashImage(image)">
                <div class="h-48 w-full bg-cover border-primary" :class="{'border-4': selectedUnsplashImageId == image.id}" :style="{ backgroundImage: 'url(' + image.urls.thumb + ')' }"></div>
            </div>

            <div class="w-1/2 p-1 flex items-center" v-if="unsplashImages.length == 19">
                <button class="text-primary hover:underline" @click="getImagesFromUnsplash(unsplashPage + 1)">More...</button>
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
