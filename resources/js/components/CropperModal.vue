<script type="text/ecmascript">

    export default {
        props: ['image', 'viewport', 'boundary'],


        data() {
            return {
                finalImage: null,
                uploadProgress: 0,
                uploading: false,
                size: 'original'
            }
        },


        mounted() {
            this.readImage(this.image);
        },


        methods: {
            /**
             * Close after cropping.
             */
            saveCroppedImageAndClose() {
                this.$emit('close', {
                    image: this.finalImage
                });
            },


            /**
             * Cancel cropping.
             */
            cancel() {
                this.$emit('cancel');
            },


            /**
             * Crop the image.
             */
            crop() {
                this.$refs.croppieRef.result({
                    type: 'canvas',
                    format: 'png',
                    quality: 1,
                    size: this.size
                }, (output) => {
                    this.uploadSelectedImage(output);
                });
            },


            /**
             * Read the given image file.
             */
            readImage(image) {
                let reader = new FileReader();

                reader.onload = (e) => {
                    this.$refs.croppieRef.bind({
                        url: e.target.result,
                        zoom: 0
                    });
                }
                reader.readAsDataURL(image);
            },

            /**
             * Upload the orginal image.
             */
            uploadOriginalImage() {
                let file = this.image;
                let formData = new FormData();

                formData.append('image', file, file.name);

                this.$emit('uploading');

                this.uploading = true;

                this.http().post('/api/uploads', formData, {
                    onUploadProgress: progressEvent => {
                        this.uploadProgress = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                    }
                }).then(response => {
                    this.finalImage = response.data.url;

                    this.uploading = false;

                    this.saveCroppedImageAndClose();
                }).catch(error => {
                    console.log(error);
                });
            },


            /**
             * Upload the selected image.
             */
            uploadSelectedImage(base64) {
                let formData = new FormData();

                fetch(base64)
                    .then(res => res.blob())
                    .then(blob => {
                        let file = new File([blob], "filename.jpeg");

                        formData.append('image', file, file.name);

                        this.uploading = true;

                        this.http().post('/api/uploads', formData, {
                            onUploadProgress: progressEvent => {
                                this.uploadProgress = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                            }
                        }).then(response => {
                            this.finalImage = response.data.url;

                            this.uploading = false;

                            this.saveCroppedImageAndClose();
                        }).catch(error => {
                            //
                        });
                    });
            }
        }
    }
</script>

<template>
    <modal @close="cancel">
        <div v-if="uploading" class="absolute pin bg-black-shade z-50 flex items-center justify-center">
            <preloader class="text-green"></preloader>
        </div>
        <div :style="{'height':viewport}">
            <vue-croppie
                    ref="croppieRef"
                    :enableOrientation="true"
                    :viewport="viewport"
                    :boundary="boundary"
                    :enableResize="true">
            </vue-croppie>
        </div>

        <div class="mt-10 flex align-center">
            <button class="btn-sm ml-1 btn-light mr-auto" @click="cancel()">Cancel</button>
            <button class="btn-sm btn-primary mr-2" @click="crop()">Crop Image</button>
            <button class="btn-sm btn-primary" @click="uploadOriginalImage()">Use Original</button>
        </div>
    </modal>
</template>
