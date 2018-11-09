<script type="text/ecmascript-6">
    import $ from 'jquery';
    import FeaturedImageUploader from './FeaturedImageUploader';

    export default {
        components: {
            'featured-image-uploader': FeaturedImageUploader
        },


        data() {
            return {
                ready: false,
                entry: null,
                currentTab: 'post',
                tags: [],
                authors: [],
                status: '',

                id: this.$route.params.id || 'new',

                errors: [],

                formWatcher: null,

                form: {
                    id: '',
                    title: 'Draft',
                    slug: '',
                    excerpt: '',
                    tags: [],
                    author_id: '',
                    featured_image: '',
                    featured_image_caption: '',
                    body: '',
                    published: false,
                    publish_date: ''
                }
            };
        },


        watch: {
            'form.slug'(val){
                this.debouncer(() => {
                    this.form.slug = this.slugify(val);
                });
            },

            'form.published'(val){
                if (this.formWatcher) {
                    this.formWatcher();
                }

                if (!val) {
                    this.watchChangesAndSave();
                }
            },

            '$route.params.id'(){
                this.id = this.$route.params.id;
            }
        },

        /**
         * Prepare the component.
         */
        mounted() {
            document.title = "Edit Post — Wink.";

            this.loadResources();

            this.http().get('/api/posts/' + this.id).then(response => {
                this.entry = _.cloneDeep(response.data.entry);

                this.fillForm(response.data.entry);

                this.ready = true;

                this.registerSaveKeyboardShortcut();
            }).catch(error => {
                this.ready = true;
            });
        },


        /**
         * Clean after the component is destroyed.
         */
        destroyed() {
            $(document).off('keydown');
        },


        methods: {
            registerSaveKeyboardShortcut(){
                $(document).keydown(event => {
                            if ((event.ctrlKey || event.metaKey) && event.which == 83) {
                                event.preventDefault();

                                this.save();
                            }
                        }
                );
            },


            /**
             * Fill the form.
             */
            fillForm(data){
                this.form.id = data.id;
                this.form.publish_date = data.publish_date;

                if (this.id != 'new') {
                    this.form.title = data.title;
                    this.form.slug = data.slug;
                    this.form.excerpt = data.excerpt;
                    this.form.body = data.body;
                    this.form.published = data.published;
                    this.form.tags = data.tags || '';
                    this.form.author_id = data.author_id || '';
                    this.form.featured_image = data.featured_image;
                    this.form.featured_image_caption = data.featured_image_caption;
                }

                if (!this.form.published) {
                    this.watchChangesAndSave();
                }
            },


            /**
             * Watch changes and save the post.
             */
            watchChangesAndSave(){
                setTimeout(() => {
                    this.formWatcher = this.$watch('form', _.debounce(() => this.save(), 1000), {deep: true});
                }, 1000);
            },


            /**
             * Load the resources needed for the screen.
             */
            loadResources(){
                this.http().get('/api/tags').then(response => {
                    this.tags = response.data.entries;
                });

                this.http().get('/api/team').then(response => {
                    this.authors = response.data.entries;

                    if (!this.form.author_id && this.authors) {
                        this.form.author_id = _.first(this.authors).id;
                    }
                });
            },


            /**
             * Listen to changes in the post body.
             */
            updatePostBody(data){
                this.form.body = data.body;
            },


            /**
             * Update the post title.
             */
            updateTitle(val){
                this.form.title = val;
            },


            /**
             * Open the settings modal.
             */
            settingsModal(){
                $('#postSettingsModal').modal('show');

                $('#title').focus();
            },


            /**
             * Open the publishing modal.
             */
            publishingModal(){
                $('#publishingModal').modal('show');
            },


            /**
             * Open the featured image modal.
             */
            featuredImageModal(){
                this.$emit('openingFeaturedImageUploader');
            },


            /**
             * Handle the change event of featured images.
             */
            featuredImageChanged({url, caption}){
                this.form.featured_image = url;
                this.form.featured_image_caption = caption;
            },


            /**
             * Delete the post.
             */
            deletePost(){
                this.alertConfirm("Are you sure you want to delete this post?", () => {
                    $('#postSettingsModal').modal('hide');

                    this.http().delete('/api/posts/' + this.id, this.form).then(response => {
                        this.$router.push({name: 'posts'})
                    })
                });
            },


            /**
             * Publish the post.
             */
            publishPost(){
                this.form.published = true;

                this.save();

                $('#publishingModal').modal('hide');

                this.notifySuccess('Post Published!', 2000);
            },


            /**
             * Un-publish the post.
             */
            unpublishPost(){
                this.form.published = false;

                this.save();

                $('#publishingModal').modal('hide');

                this.notifySuccess('Post was converted to a draft!', 2000);
            },


            /**
             * Save the post.
             */
            save(){
                this.errors = [];
                this.status = 'Saving...';

                this.form.slug = this.form.slug || 'draft-' + this.form.id;
                this.form.title = this.form.title || 'Draft';

                this.http().post('/api/posts/' + this.id, this.form).then(response => {
                    this.status = '';

                    if (this.id == 'new') {
                        this.$router.push({name: 'post-edit', params: {id: this.form.id}})
                    }
                }).catch(error => {
                    this.errors = error.response.data.errors;

                    $('#postSettingsModal').modal('show');
                });
            },
        }
    }
</script>

<template>
    <div>
        <page-header>
            <div slot="left-side">
                <div v-if="ready && entry">
                    <strong v-if="!status && form.published">Published</strong>
                    <strong v-if="!status && !form.published">Draft</strong>
                    <span v-if="status">{{status}}</span>
                </div>
            </div>

            <div slot="right-side">
                <div v-if="ready && entry">
                    <button class="btn btn-link btn-sm" @click="featuredImageModal">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="icon fill-secondary">
                            <path d="M0 4c0-1.1.9-2 2-2h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm11 9l-3-3-6 6h16l-5-5-2 2zm4-4a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                        </svg>
                    </button>

                    <button class="btn btn-link btn-sm" @click="settingsModal">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="icon fill-secondary">
                            <path d="M17 16v4h-2v-4h-2v-3h6v3h-2zM1 9h6v3H1V9zm6-4h6v3H7V5zM3 0h2v8H3V0zm12 0h2v12h-2V0zM9 0h2v4H9V0zM3 12h2v8H3v-8zm6-4h2v12H9V8z"/>
                        </svg>
                    </button>

                    <button class="btn btn-outline-primary btn-sm ml-2" @click="publishingModal" v-if="!form.published">Publish</button>
                    <button class="btn btn-outline-primary btn-sm ml-2" @click="publishingModal" v-if="form.published">Update</button>
                </div>
            </div>
        </page-header>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <div v-if="!ready" class="d-flex align-items-center justify-content-center p-5 bottom-radius">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="preloader spin fill-secondary">
                                <path d="M10 3v2a5 5 0 0 0-3.54 8.54l-1.41 1.41A7 7 0 0 1 10 3zm4.95 2.05A7 7 0 0 1 10 17v-2a5 5 0 0 0 3.54-8.54l1.41-1.41zM10 20l-4-4 4-4v8zm0-12V0l4 4-4 4z"/>
                            </svg>
                        </div>


                        <div v-if="ready && !entry" class="d-flex align-items-center justify-content-center p-5 bottom-radius">
                            <h2 class="mb-5 text-center">404 — Post not found</h2>
                        </div>

                        <div v-if="ready && entry">


                            <div id="editorContainer">
                                <textarea-autosize
                                        placeholder="Type something here..."
                                        class="editor-title"
                                        v-model="form.title"
                                ></textarea-autosize>

                                <editor :post-id="id" v-model="form.body"></editor>
                            </div>
                        </div>
                    </div>

                    <!-- Post Settings Modal -->
                    <div class="modal" id="postSettingsModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <div class="form-group border-bottom pb-3">
                                        <label for="slug" class="inline-form-control-label">Slug</label>
                                        <input type="text" class="inline-form-control text-body-color"
                                               v-model="form.slug"
                                               placeholder="Give me a slug"
                                               id="slug">

                                        <form-errors :errors="errors.slug"></form-errors>
                                    </div>

                                    <div class="form-group border-bottom pb-3">
                                        <label for="author_id" class="inline-form-control-label">Author</label>
                                        <select name="author_id" class="inline-form-control text-body-color"
                                                v-model="form.author_id"
                                                id="author_id">
                                            <option v-for="author in authors" :value="author.id">{{author.name}}</option>
                                        </select>
                                        <form-errors :errors="errors.author_id"></form-errors>
                                    </div>

                                    <div class="form-group border-bottom pb-3">
                                        <label for="tag_ids" class="inline-form-control-label">Tags</label>
                                        <multiselect :options="tags"
                                                     option-id="id"
                                                     v-model="form.tags"
                                                     option-text="name"
                                        ></multiselect>
                                        <form-errors :errors="errors.tags"></form-errors>
                                    </div>

                                    <div class="form-group border-bottom pb-3">
                                        <label for="excerpt" class="inline-form-control-label">Excerpt</label>
                                        <textarea class="inline-form-control text-body-color"
                                                  v-model="form.excerpt"
                                                  placeholder="What's this post about?"
                                                  id="excerpt"></textarea>

                                        <form-errors :errors="errors.excerpt"></form-errors>
                                    </div>

                                    <button class="btn btn-link text-danger p-0" @click="deletePost" v-if="id != 'new'">Delete this post</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Publishing Modal -->
                    <div class="modal" id="publishingModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <div class="text-danger mb-4">
                                        <div class="mb-2" v-if="form.title == 'Draft'">
                                            Your post doesn't seem to have a friendly title.
                                        </div>

                                        <div class="mb-2" v-if="!form.slug || form.slug.startsWith('draft-')">
                                            Your post doesn't seem to have a friendly slug.
                                        </div>
                                    </div>

                                    <div class="form-group pb-3">
                                        <label class="inline-form-control-label">Publish Date (M/D/Y H:M)</label>
                                        <date-time-picker v-model="form.publish_date"></date-time-picker>
                                        <form-errors :errors="errors.publish_date"></form-errors>
                                    </div>

                                    <button class="btn btn-sm btn-outline-primary" @click="publishPost" v-if="!form.published" v-loading="status">Publish this post</button>
                                    <button class="btn btn-sm btn-outline-primary" @click="publishPost" v-if="form.published" v-loading="status">Update Post</button>
                                    <button class="btn btn-sm btn-outline-secondary" @click="unpublishPost" v-if="form.published" v-loading="status">Convert to draft</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <featured-image-uploader :post-id="this.form.id"
                                             @changed="featuredImageChanged"
                                             :current-image-url="form.featured_image"
                                             :current-caption="form.featured_image_caption"></featured-image-uploader>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
</style>
