<script type="text/ecmascript-6">
    import $ from 'jquery';

    export default {
        props: ['postId'],

        data(){
            return {
                existingBlot: null,
                imageUrl: null,
                layout: 'default',
                caption: '',
                imagePickerKey: '',
                uploadProgress: 0,
                uploading: false,
            }
        },

        mounted() {
            this.layouts = [{id: 'test', name: 'Test'}];

            $('#editorImageUploadModal').on('hidden.bs.modal', e => {
                this.imagePickerKey = _.uniqueId();

                this.existingBlot = null;

                this.imageUrl = null;

                this.layout = 'default';

                this.caption = '';
            });

            this.$parent.$on('openingImageUploader', data => {
                if (data) {
                    this.caption = data.caption;
                    this.imageUrl = data.url;
                    this.layout = data.layout || 'default';
                    this.existingBlot = data.existingBlot;
                }

                $('#editorImageUploadModal').modal('show');
            });
        },


        methods: {
            /**
             * Close the modal.
             */
            close(){
                $('#editorImageUploadModal').modal('hide');
            },


            /**
             * Update the selected image.
             */
            updateImage({url, caption}){
                this.imageUrl = url;
                this.caption = caption;

                this.uploading = false;
            },


            /**
             * Add the image to the editor.
             */
            applyImage(){
                if (!this.imageUrl) {
                    return this.alertError('Please select an image.');
                }

                this.$emit('updated', {
                    url: this.imageUrl,
                    caption: this.caption,
                    existingBlot: this.existingBlot,
                    layout: this.layout,
                });

                this.close();
            },


            /**
             * Update the upload progress.
             */
            updateProgress({progress}){
                this.uploadProgress = progress;
            }
        }
    }
</script>

<template>
    <div class="modal" id="editorImageUploadModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h4 class="mb-4">Image</h4>

                    <div v-if="uploading" class="d-flex align-items-center justify-content-center p-5 bottom-radius">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="preloader spin fill-secondary">
                            <path d="M10 3v2a5 5 0 0 0-3.54 8.54l-1.41 1.41A7 7 0 0 1 10 3zm4.95 2.05A7 7 0 0 1 10 17v-2a5 5 0 0 0 3.54-8.54l1.41-1.41zM10 20l-4-4 4-4v8zm0-12V0l4 4-4 4z"/>
                        </svg>
                    </div>

                    <div v-if="imageUrl && !uploading">
                        <img :src="imageUrl" class="w-100 mb-4">

                        <div class="form-group border-bottom pb-3">
                            <label class="inline-form-control-label">Caption</label>
                            <input type="text" class="inline-form-control text-body-color"
                                   v-model="caption"
                                   ref="caption"
                                   placeholder="Add caption to the image">
                        </div>

                        <div class="form-group border-bottom pb-3">
                            <label class="inline-form-control-label">Layout</label>
                            <select class="inline-form-control text-body-color" v-model="layout">
                                <option value="default">Default</option>
                                <option value="wide">Wide Image</option>
                            </select>
                        </div>
                    </div>

                    <image-picker v-if="!imageUrl" :key="imagePickerKey"
                                  @changed="updateImage"
                                  @progressing="updateProgress"
                                  @uploading="uploading = true"></image-picker>

                    <button class="btn btn-outline-primary btn-sm mt-4" @click="applyImage">Apply</button>
                    <button class="btn btn-outline-secondary btn-sm mt-4" @click="close">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</template>

<style>

</style>
