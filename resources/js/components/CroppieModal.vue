<script type="text/ecmascript-6">
    import _ from 'lodash';

    export default {
        props: ['file','viewport', 'boundary'],


        data() {
            return {
                image: null,
                cropped: null,
                uploadProgress: 0,
                uploading: false
            }
        },


        mounted() {

            this.image = this.file;
            this.readFile(this.file);

        },


        methods: {
            /**
             * This method will close() the modal.
             */
            close() {
                this.$emit('cancelCroppie');
            },
            /**
             * This method closes croppie and sends the
             * cropped image back in the event
             */
            closeCroppie() {
                this.$emit('closeCroppie', {
                    avatar: this.avatar
                });
            },

            /**
             * This method emits a cancel event.
             */
            cancelCroppie() {
                this.$emit('cancelCroppie');
            },

            /**
             * This method is used to crop the image
             */
            crop() {
                // Here we are getting the result via callback function
                // and set the result to this.cropped which is being
                // used to display the result above.
                let options = {
                    format: 'png',
                    quality: 1
                }
                this.$refs.croppieRef.result(options, (output) => {
                    this.cropped = output;
                    this.uploadSelectedImage(output);
                });
            },


            /**
             * Read File with FileReader Class. So that croppie
             * can be binded to the file.
             */

            readFile(file) {

                let reader = new FileReader();

                reader.onload = (e) => {
                    this.$refs.croppieRef.bind({
                        url: e.target.result,
                        zoom: 0
                    });
                }
                reader.readAsDataURL(file);

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
                            this.avatar = response.data.url;
                            this.uploading = false;
                            this.closeCroppie();
                        }).catch(error => {
                        });
                    });
            }
        }
    }
</script>

<template>
    <modal @close="close">
        <div :style="{'height':viewport}">
            <vue-croppie
                    ref="croppieRef"
                    :enableOrientation="true"
                    :viewport ="viewport"
                    :boundary="boundary"
                    :enableResize="true">
            </vue-croppie>
            </div>

        <div class="mt-10">
            <button class="btn-sm ml-1 btn-light" @click="cancelCroppie()">Cancel</button>
            <button class="btn-sm btn-primary float-right" @click="crop()">Crop Image</button>
        </div>
    </modal>
</template>
