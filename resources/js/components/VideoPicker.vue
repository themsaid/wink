<script type="text/ecmascript">
    export default {
        props: [],

        data() {
            return {
                file: null,
                videoUrl: '',
                uploadProgress: 0,
                uploading: false,

                video: null,
            }
        },

        methods: {

            /**
             * Load the selected video.
             */
            loadSelectedVideo(event){
                this.file = event.target.files[0];
                this.uploadVideoFile();
            },


            /**
             * Finish the upload process.
             */
            uploadFinished({video}) {
                this.videoUrl = video;
                this.$emit('changed', { url: video });
            },

            uploadVideoFile() {
                let file = this.file;
                let formData = new FormData();

                formData.append('video', file, file.name);

                this.$emit('uploading');

                this.uploading = true;

                this.http().post('/api/uploads/video', formData, {
                    onUploadProgress: progressEvent => {
                        this.uploadProgress = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                        this.$emit('progressing', { progress: this.uploadProgress })
                    }
                }).then(response => {
                    this.video = response.data.url;

                    this.uploading = false;

                    this.uploadFinished({ video: this.video });
                }).catch(error => {
                    console.log(error);
                });
            }
        }
    }
</script>

<template>
    <div>
        <input type="file" class="hidden" :id="'videoUpload'+_uid" accept="video/*" v-on:change="loadSelectedVideo">
        Please <label :for="'videoUpload'+_uid" class="cursor-pointer underline">upload</label> a video.
    </div>
</template>
