<script type="text/ecmascript-6">
    import axios from 'axios';
    import _ from 'lodash';

    export default {
        props: [],

        data() {
            return {
                imageUrl: '',
                uploadProgress: 100,

                selectedUnsplashImage: null,

                unsplashSearchTerm: '',
                unsplashPage: 1,
                searchingUnsplash: false,
                unsplashImages: [],
            }
        },


        mounted() {

        },


        watch: {
            unsplashSearchTerm() {
                this.debouncer(() => {
                    this.getImagesFromUnsplash();
                });
            }
        },


        computed: {
            unsplashModalShown() {
                return this.unsplashSearchTerm.length;
            }
        },


        methods: {
            getImagesFromUnsplash(page = 1) {
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
            uploadSelectedImage(event) {
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
             * Open unsplash modal.
             */
            openUnsplashModal() {
                this.unsplashSearchTerm = 'sunny';

                this.$nextTick(() => {
                    this.$refs.unsplashSearch.focus();
                })
            },


            /**
             * Select an unsplash Image.
             */
            closeUnplashModalAndInsertImage() {
                this.$emit('changed', {
                    url: this.selectedUnsplashImage.urls.regular,
                    caption: 'Photo by <a href="' + this.selectedUnsplashImage.user.links.html + '">' + this.selectedUnsplashImage.user.name + '</a> on <a href="https://unsplash.com">Unsplash</a>',
                });

                this.closeUnsplashModal();
            },


            /**
             * Close unsplash modal.
             */
            closeUnsplashModal() {
                this.unsplashSearchTerm = '';
                this.selectedUnsplashImage = null;
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
            <a v-if="Wink.unsplash_key" href="#" @click.prevent="openUnsplashModal" class="text-text-color">search Unsplash</a>
        </div>

        <fullscreen-modal v-if="unsplashModalShown">
            <div class="bg-contrast z-50 fixed pin overflow-y-scroll">
                <div class="container py-20">
                    <div class="flex items-center">
                        <h2 class="mr-auto">Search Unsplash</h2>

                        <button class="btn-primary mr-4" v-if="selectedUnsplashImage" @click="closeUnplashModalAndInsertImage">Choose Selected Image</button>
                        <button class="btn-light" @click="closeUnsplashModal">Cancel</button>
                    </div>

                    <input type="text" class="my-10 border-b border-very-light focus:outline-none w-full"
                           v-if="Wink.unsplash_key"
                           v-model="unsplashSearchTerm"
                           ref="unsplashSearch"
                           placeholder="search Unsplash">

                    <preloader v-if="searchingUnsplash" class="mt-10"></preloader>

                    <div v-if="!searchingUnsplash && unsplashImages.length" class="flex flex-wrap mt-5">
                        <div class="w-1/4 p-1 cursor-pointer" v-for="image in unsplashImages" @click="selectedUnsplashImage = image">
                            <div class="h-48 w-full bg-cover border-primary"
                                 :class="{'border-4': selectedUnsplashImage && selectedUnsplashImage.id == image.id}" :style="{ backgroundImage: 'url(' + image.urls.thumb + ')' }"></div>
                        </div>

                        <div class="w-1/4 p-1 flex items-center" v-if="unsplashImages.length == 19">
                            <button class="text-primary hover:underline" @click="getImagesFromUnsplash(unsplashPage + 1)">More...</button>
                        </div>
                    </div>
                </div>
            </div>
        </fullscreen-modal>
    </div>
</template>
