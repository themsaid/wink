<script type="text/ecmascript">
    import axios from 'axios';

    export default {
        props: [],

        data() {
            return {
                file: null,
                imageUrl: '',
                uploadProgress: 100,

                selectedUnsplashImage: null,
                unsplashModalShown: false,
                unsplashSearchTerm: '',
                unsplashPage: 1,
                searchingUnsplash: true,
                unsplashImages: [],

                cropperModalShown: false,
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
             * Load the selected image into the Cropper.
             */
            loadSelectedImage(event){
                this.file = event.target.files[0];

                this.showCropperModal();
            },

            /**
             * Open unsplash modal.
             */
            openUnsplashModal() {
                this.unsplashSearchTerm = 'sunny';
                this.unsplashModalShown = true;

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
                this.unsplashModalShown = false;
                this.selectedUnsplashImage = null;
            },


            /**
             * Open the cropper modal.
             */
            showCropperModal() {
                this.cropperModalShown = true;
            },


            /**
             * Close the cropper modal.
             */
            closeCropperModal({image}) {
                this.cropperModalShown = false;
                this.imageUrl = image;
                this.$emit('changed', {url: image, caption: ''});
            },


            /**
             * Close and Cancel the cropper modal.
             */
            cancelCropperModal() {
                this.cropperModalShown = false;
            }
        }
    }
</script>

<template>
    <div>
        <input type="file" class="hidden" :id="'imageUpload'+_uid" accept="image/*" v-on:change="loadSelectedImage">

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

                        <div class="w-1/4 p-1" v-if="unsplashImages.length == 19">
                            <div class="bg-primary text-center flex items-center justify-center h-full">
                                <button class="text-contrast hover:underline" @click="getImagesFromUnsplash(unsplashPage + 1)">More >></button>
                            </div>
                        </div>
                    </div>

                    <div v-if="!searchingUnsplash && !unsplashImages.length">
                        <h4 class="text-center">We couldn't find any matches.</h4>
                    </div>
                </div>
            </div>
        </fullscreen-modal>

        <cropper-modal v-if="cropperModalShown"
                       :image="file"
                       :viewport ="{ width: 600, height: 400 }"
                       :boundary="{ width: 600, height: 400 }"
                       @close="closeCropperModal"
                       @cancel="cancelCropperModal"></cropper-modal>
    </div>
</template>
