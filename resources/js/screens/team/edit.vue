<script type="text/ecmascript-6">

    import SEOModal from './../../components/SEOModal';

    export default {
        components: {
            'seo-modal': SEOModal,
        },

        data() {
            return {
                entry: null,
                ready: false,

                id: this.$route.params.id || 'new',

                uploadProgress: 0,
                uploading: false,

                seoModalShown: false,

                form: {
                    errors: [],
                    working: false,
                    id: '',
                    name: '',
                    slug: '',
                    email: '',
                    bio: 'I am who I\'m meant to be, this is me.',
                    avatar: '',
                    password: '',
                    meta: {
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
            };
        },

        /**
         * Prepare the component.
         */
        mounted() {
            document.title = "Author — Wink.";

            this.loadEntry();
        },


        watch: {
            'form.slug'(val) {
                this.debouncer(() => {
                    this.form.slug = this.slugify(val);
                });
            },

            'form.name'(val) {
                this.debouncer(() => {
                    if (this.form.slug) return;

                    this.form.slug = this.slugify(val);
                });
            },

            '$route'() {
                this.id = this.$route.params.id;

                this.loadEntry();
            }
        },


        methods: {
            loadEntry() {
                this.ready = false;

                this.http().get('/api/team/' + this.id).then(response => {
                    this.entry = response.data.entry;

                    this.form.id = response.data.entry.id;

                    if (this.id != 'new') {
                        this.form.name = response.data.entry.name;
                        this.form.slug = response.data.entry.slug;
                        this.form.email = response.data.entry.email;
                        this.form.bio = response.data.entry.bio;
                        this.form.avatar = response.data.entry.avatar;
                        this.form.meta = {
                            meta_description: response.data.entry.meta.meta_description || '',
                            opengraph_title: response.data.entry.meta.opengraph_title || '',
                            opengraph_description: response.data.entry.meta.opengraph_description || '',
                            opengraph_image: response.data.entry.meta.opengraph_image || '',
                            opengraph_image_width: response.data.entry.meta.opengraph_image_width || '',
                            opengraph_image_height: response.data.entry.meta.opengraph_image_height || '',
                            twitter_title: response.data.entry.meta.twitter_title || '',
                            twitter_description: response.data.entry.meta.twitter_description || '',
                            twitter_image: response.data.entry.meta.twitter_image || '',
                        };
                    }

                    this.ready = true;
                }).catch(error => {
                    this.ready = true;
                });
            },


            /**
             * Save the post.
             */
            save() {
                this.form.working = true;
                this.form.errors = [];

                this.http().post('/api/team/' + this.id, this.form).then(response => {
                    this.form.working = false;

                    this.notifySuccess('Saved!', 2000);

                    if (this.id == 'new') {
                        this.id = this.form.id;

                        this.$router.push({name: 'team-edit', params: {id: this.form.id}})
                    }
                }).catch(error => {
                    this.form.errors = error.response.data.errors;

                    this.form.working = false;
                });
            },


            deleteAuthor() {
                this.alertConfirm("Are you sure you want to delete this author?", () => {
                    this.http().delete('/api/team/' + this.id).then(response => {
                        this.$router.push({name: 'team'})
                    }).catch(error => {
                        this.alertError(error.response.data.message);
                    });
                });
            },


            /**
             * Upload the selected image.
             */
            uploadSelectedImage(event) {
                let file = event.target.files[0];
                let formData = new FormData();

                formData.append('image', file, file.name);

                this.uploading = true;

                this.http().post('/api/uploads', formData, {
                    onUploadProgress: progressEvent => {
                        this.uploadProgress = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                    }
                }).then(response => {
                    this.form.avatar = response.data.url;

                    this.uploading = false;
                }).catch(error => {
                });
            },

            /**
             * Open the SEO & Social modal.
             */
            seoModal() {
                this.seoModalShown = true;
            },

            /**
             * Close the SEO modal.
             */
            closeSeoModal({content}) {
                this.seoModalShown = false;
                this.form.meta = content;
            },

        }
    }
</script>

<template>
    <div>
        <page-header>
            <div class="flex items-center" v-if="ready && entry" slot="right-side">

                <button class="py-1 px-2 btn-primary text-sm mr-6" @click="save" v-loading="form.working">Save</button>

                <dropdown class="relative">
                    <button slot="trigger" class="focus:outline-none text-light hover:text-primary h-8">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="w-4 h-4 fill-current mt-1">
                            <path d="M17 16v4h-2v-4h-2v-3h6v3h-2zM1 9h6v3H1V9zm6-4h6v3H7V5zM3 0h2v8H3V0zm12 0h2v12h-2V0zM9 0h2v4H9V0zM3 12h2v8H3v-8zm6-4h2v12H9V8z"/>
                        </svg>
                    </button>

                    <div slot="content" class="dropdown-content pin-r min-w-dropdown mt-1 text-sm py-2">
                        <a href="#" @click.prevent="seoModal" class="no-underline text-black hover:text-primary w-full block py-2 px-4">
                            SEO & Social
                        </a>
                        <a href="#" @click.prevent="deleteAuthor" class="no-underline text-red w-full block py-2 px-4" v-if="id != 'new'">Delete</a>
                    </div>
                </dropdown>
            </div>
        </page-header>

        <div class="container">
            <preloader v-if="!ready"></preloader>

            <h2 v-if="ready && !entry" class="text-center font-normal">
                404 — Author not found
            </h2>

            <div class="lg:w-2/3 mx-auto" v-if="ready && entry">
                <h1 class="font-semibold text-3xl mb-10" v-if="id != 'new' && Wink.author.id != entry.id">Edit Author</h1>
                <h1 class="font-semibold text-3xl mb-10" v-if="id == 'new' && Wink.author.id != entry.id">New Author</h1>
                <h1 class="font-semibold text-3xl mb-10" v-if="Wink.author.id == entry.id">Your Profile</h1>

                <div class="input-group">
                    <label for="name" class="input-label">Name</label>
                    <input type="text" class="input"
                           v-model="form.name"
                           placeholder="Give me a name"
                           id="name">

                    <form-errors :errors="form.errors.name"></form-errors>
                </div>

                <div class="input-group">
                    <label for="slug" class="input-label">Slug</label>
                    <input type="text" class="input"
                           v-model="form.slug"
                           placeholder="and-a-slug-please"
                           id="slug">

                    <form-errors :errors="form.errors.slug"></form-errors>
                </div>

                <div class="input-group">
                    <label for="email" class="input-label">Email</label>
                    <input type="email" class="input"
                           v-model="form.email"
                           placeholder="email@example.com"
                           id="email">

                    <form-errors :errors="form.errors.email"></form-errors>
                </div>

                <div class="input-group">
                    <label for="password" class="input-label">Password</label>
                    <input type="password" class="input"
                           v-model="form.password"
                           placeholder="*****"
                           id="password">

                    <form-errors :errors="form.errors.password"></form-errors>
                </div>

                <div class="input-group mb-5">
                    <label for="slug" class="input-label mb-4">Bio</label>
                    <mini-editor v-model="form.bio"></mini-editor>
                    <form-errors :errors="form.errors.bio"></form-errors>
                </div>

                <div v-if="uploading">
                    <preloader></preloader>
                </div>

                <div class="flex items-center" v-if="!uploading">
                    <div class="w-16 h-16 rounded-full bg-cover" :style="{ backgroundImage: 'url(' + form.avatar + ')' }"></div>

                    <input type="file" class="hidden" id="author_avatar" accept="image/*" v-on:change="uploadSelectedImage">

                    <label for="author_avatar" class="ml-5 cursor-pointer underline">Upload an avatar</label>
                </div>
            </div>
        </div>

        <!-- SEO & Social Modal -->
        <seo-modal v-if="seoModalShown"
                   :input="form.meta"
                   @close="closeSeoModal"></seo-modal>
    </div>
</template>
