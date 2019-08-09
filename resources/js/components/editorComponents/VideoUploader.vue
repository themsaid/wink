<script type="text/ecmascript">
    export default {
        props: ['postId'],

        data() {
            return {
                existingBlot: null,
                videoUrl: null,
                videoPickerKey: '',
                uploadProgress: 0,
                uploading: false,

                modalShown: false,
            }
        },

        mounted() {
            this.$parent.$on('openingVideoUploader', data => {
                if (data) {
                    this.videoUrl = data.url;
                    this.existingBlot = data.existingBlot;
                }

                this.modalShown = true;
            });
        },


        methods: {
            /**
             * Close the modal.
             */
            close() {
                this.modalShown = false;

                this.imagePickerKey = _.uniqueId();

                this.existingBlot = null;

                this.videoUrl = null;
            },


            /**
             * Update the selected video.
             */
            updateVideo({url}) {
                this.videoUrl = url;

                this.uploading = false;
            },


            /**
             * Add the video to the editor.
             */
            applyVideo() {
                if (!this.videoUrl) {
                    return this.alertError('Please select a video.');
                }

                this.$emit('updated', {
                    url: this.videoUrl,
                    existingBlot: this.existingBlot,
                });

                this.close();
            },


            /**
             * Update the upload progress.
             */
            updateProgress({progress}) {
                this.uploadProgress = progress;
            }
        }
    }
</script>

<template>
    <modal v-if="modalShown" @close="close">
        <h2 class="font-semibold mb-5">Add Video</h2>

        <preloader v-if="uploading"></preloader>

        <div v-if="videoUrl && !uploading">
            <video class="max-w-full" controls>
                <source :src="videoUrl"/>
            </video>
        </div>

        <video-picker v-if="!videoUrl" :key="videoPickerKey"
                      class="mt-5"
                      @changed="updateVideo"
                      @progressing="updateProgress"
                      @uploading="uploading = true"></video-picker>

        <button class="btn-sm btn-primary mt-10" @click="applyVideo">Apply</button>
        <button class="btn-sm btn-light mt-10" @click="close">Cancel</button>
    </modal>
</template>

<style>

</style>
