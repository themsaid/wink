<script type="text/ecmascript">
    import _ from 'lodash';

    export default {
        props: ['input'],


        data() {
            return {
                facebookImageUploading: false,
                twitterImageUploading: false,

                form: {
                    meta_description: '',
                    opengraph_title: '',
                    opengraph_description: '',
                    opengraph_image: '',
                    opengraph_image_width: '',
                    opengraph_image_height: '',
                    twitter_title: '',
                    twitter_description: '',
                    twitter_image: '',
                }
            }
        },


        mounted() {
            this.form = this.input;
        },


        methods: {
            close() {
                this.$emit('close', {
                    content: this.form
                });
            },


            /**
             * Update the selected opengraph image.
             */
            updateFacebookImage({url}) {
                var img = new Image();

                this.form.opengraph_image = url;

                img.src = url;

                img.onload = e => {
                    this.form.opengraph_image_height = e.target.height;
                    this.form.opengraph_image_width = e.target.width;
                };

                this.facebookImageUploading = false;
            },

            /**
             * Update the selected twitter image.
             */
            updateTwitterImage({url}) {
                this.form.twitter_image = url;

                this.twitterImageUploading = false;
            },
        }
    }
</script>

<template>
    <modal @close="close">
        <div class="input-group">
            <label for="meta_description" class="input-label">
                Meta description
            </label>
            <textarea class="input"
                      v-model="form.meta_description"
                      placeholder="Meta description"
                      id="meta_description"></textarea>
        </div>

        <div class="input-group">
            <label for="opengraph_title" class="input-label">
                Facebook Card Title
            </label>
            <input type="text" class="input"
                   v-model="form.opengraph_title"
                   placeholder="Title in Facebook Card"
                   id="opengraph_title">
        </div>

        <div class="input-group">
            <label for="opengraph_description" class="input-label">
                Facebook Card Description
            </label>
            <textarea class="input"
                      v-model="form.opengraph_description"
                      placeholder="Description in Facebook Card"
                      id="opengraph_description"></textarea>
        </div>

        <div class="input-group py-4">
            <div class="flex items-center justify-between">
                <div>
                    <label class="input-label">
                        Facebook Card Image
                    </label>

                    <image-picker class="mt-4 mb-1"
                                  @changed="updateFacebookImage"
                                  @uploading="facebookImageUploading = true"></image-picker>
                </div>

                <preloader v-if="facebookImageUploading"></preloader>

                <div v-if="!facebookImageUploading">
                    <div class="w-16 h-16 rounded-full bg-light flex items-center justify-center text-4xl text-contrast" v-if="!form.opengraph_image">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="fill-current w-8">
                            <path d="M0 6c0-1.1.9-2 2-2h3l2-2h6l2 2h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V6zm10 10a5 5 0 1 0 0-10 5 5 0 0 0 0 10zm0-2a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                        </svg>
                    </div>
                    <div class="w-16 h-16 rounded-full bg-cover"
                         v-if="form.opengraph_image"
                         :style="{ backgroundImage: 'url(' + form.opengraph_image + ')' }"></div>
                </div>
                <input type="hidden" v-model="form.opengraph_image_width"/>
                <input type="hidden" v-model="form.opengraph_image_height"/>
            </div>
        </div>

        <div class="input-group">
            <label for="twitter_title" class="input-label">
                Twitter Card Title
            </label>
            <input type="text" class="input"
                   v-model="form.twitter_title"
                   placeholder="Title in Twitter Card"
                   id="twitter_title">
        </div>

        <div class="input-group">
            <label for="twitter_description" class="input-label">
                Twitter Card Description
            </label>
            <textarea class="input"
                      v-model="form.twitter_description"
                      placeholder="Description in Twitter Card"
                      id="twitter_description"></textarea>
        </div>

        <div class="input-group py-4">
            <div class="flex items-center justify-between">
                <div>
                    <label class="input-label">
                        Twitter Card Image
                    </label>

                    <image-picker class="mt-4 mb-1"
                                  @changed="updateTwitterImage"
                                  @uploading="twitterImageUploading = true"></image-picker>
                </div>

                <preloader v-if="twitterImageUploading"></preloader>

                <div v-if="!twitterImageUploading">
                    <div class="w-16 h-16 rounded-full bg-light flex items-center justify-center text-4xl text-contrast" v-if="!form.twitter_image">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="fill-current w-8">
                            <path d="M0 6c0-1.1.9-2 2-2h3l2-2h6l2 2h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V6zm10 10a5 5 0 1 0 0-10 5 5 0 0 0 0 10zm0-2a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                        </svg>
                    </div>
                    <div class="w-16 h-16 rounded-full bg-cover"
                         v-if="form.twitter_image"
                         :style="{ backgroundImage: 'url(' + form.twitter_image + ')' }"></div>
                </div>
            </div>
        </div>

        <div class="mt-10">
            <button class="btn-sm btn-primary" @click="close">Done</button>
        </div>
    </modal>
</template>
