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

                settingsModalShown: false,
                publishingModalShown: false,

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
                this.settingsModalShown = true;

                $('#title').focus();
            },


            /**
             * Open the publishing modal.
             */
            publishingModal(){
                this.publishingModalShown = true;
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
                    this.settingsModalShown = false;

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

                this.publishingModalShown = false;

                this.notifySuccess('Post Published!', 2000);
            },


            /**
             * Un-publish the post.
             */
            unpublishPost(){
                this.form.published = false;

                this.save();

                this.publishingModalShown = false;

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

                    this.settingsModalShown = true;
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
                    <span class="font-semibold" v-if="!status && form.published">Published</span>
                    <span class="font-semibold" v-if="!status && !form.published">Draft</span>
                    <span v-if="status">{{status}}</span>
                </div>
            </div>

            <div class="flex items-center" v-if="ready && entry" slot="right-side">
                <button class="focus:outline-none text-light hover:text-primary mr-5" @click="featuredImageModal">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="w-4 h-4 fill-current">
                        <path d="M0 4c0-1.1.9-2 2-2h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm11 9l-3-3-6 6h16l-5-5-2 2zm4-4a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                    </svg>
                </button>

                <button class="focus:outline-none text-light hover:text-primary" @click="settingsModal">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="w-4 h-4 fill-current">
                        <path d="M17 16v4h-2v-4h-2v-3h6v3h-2zM1 9h6v3H1V9zm6-4h6v3H7V5zM3 0h2v8H3V0zm12 0h2v12h-2V0zM9 0h2v4H9V0zM3 12h2v8H3v-8zm6-4h2v12H9V8z"/>
                    </svg>
                </button>

                <button class="py-1 px-2 btn-primary text-sm ml-6" @click="publishingModal" v-if="!form.published">Publish</button>
                <button class="py-1 px-2 btn-primary text-sm ml-6" @click="publishingModal" v-if="form.published">Update</button>
            </div>
        </page-header>

        <div class="container">
            <preloader v-if="!ready"></preloader>

            <h2 v-if="ready && !entry" class="text-center font-normal">
                404 — Post not found
            </h2>

            <div class="lg:w-3/4 mx-auto" v-if="ready && entry">
                <textarea-autosize
                        placeholder="Type something here..."
                        class="text-3xl font-semibold w-full"
                        v-model="form.title"
                ></textarea-autosize>

                <editor :post-id="id" v-model="form.body"></editor>
            </div>
        </div>

        <!-- Post Settings Modal -->
        <modal v-if="settingsModalShown" @close="settingsModalShown = false">
            <div class="input-group pt-0">
                <label for="slug" class="input-label">Slug</label>
                <input type="text" class="input"
                       v-model="form.slug"
                       placeholder="Give me a slug"
                       id="slug">

                <form-errors :errors="errors.slug"></form-errors>
            </div>

            <div class="input-group">
                <label for="author_id" class="input-label">Author</label>
                <select name="author_id" class="input"
                        v-model="form.author_id"
                        id="author_id">
                    <option v-for="author in authors" :value="author.id">{{author.name}}</option>
                </select>
                <form-errors :errors="errors.author_id"></form-errors>
            </div>

            <div class="input-group">
                <label for="tag_ids" class="input-label mb-4">Tags</label>
                <multiselect :options="tags"
                             option-id="id"
                             v-model="form.tags"
                             option-text="name"
                ></multiselect>
                <form-errors :errors="errors.tags"></form-errors>
            </div>

            <div class="input-group">
                <label for="excerpt" class="input-label">Excerpt</label>
                                        <textarea class="input"
                                                  v-model="form.excerpt"
                                                  placeholder="What's this post about?"
                                                  id="excerpt"></textarea>

                <form-errors :errors="errors.excerpt"></form-errors>
            </div>

            <button class="text-red hover:underline focus:outline-none mt-10" @click="deletePost" v-if="id != 'new'">Delete this post</button>

            <div class="mt-10">
                <button class="btn-sm btn-light" @click="settingsModalShown = false">Cancel</button>
            </div>
        </modal>

        <!-- Publishing Modal -->
        <modal v-if="publishingModalShown" @close="publishingModalShown = false">
            <div class="mb-10 text-red" v-if="form.title == 'Draft'">
                Your post doesn't seem to have a friendly title.
            </div>

            <div class="mb-10 text-red" v-if="!form.slug || form.slug.startsWith('draft-')">
                Your post doesn't seem to have a friendly slug.
            </div>

            <div class="input-group pt-0 mb-10">
                <label class="input-label">Publish Date (M/D/Y H:M)</label>
                <date-time-picker v-model="form.publish_date"></date-time-picker>
                <form-errors :errors="errors.publish_date"></form-errors>
            </div>

            <button class="btn-sm btn-primary" @click="publishPost" v-if="!form.published" v-loading="status">Publish this post</button>
            <button class="btn-sm btn-primary" @click="publishPost" v-if="form.published" v-loading="status">Update Post</button>
            <button class="btn-sm btn-light" @click="unpublishPost" v-if="form.published" v-loading="status">Convert to draft</button>
            <button class="btn-sm btn-light" @click="publishingModalShown = false">Cancel</button>
        </modal>

        <featured-image-uploader :post-id="this.form.id"
                                 @changed="featuredImageChanged"
                                 :current-image-url="form.featured_image"
                                 :current-caption="form.featured_image_caption"></featured-image-uploader>
    </div>
</template>

<style scoped>
</style>
