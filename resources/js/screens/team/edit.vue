<script type="text/ecmascript-6">

    export default {
        data() {
            return {
                entry: null,
                ready: false,

                id: this.$route.params.id || 'new',

                uploadProgress: 0,
                uploading: false,

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
            'form.slug'(val){
                this.debouncer(() => {
                    this.form.slug = this.slugify(val);
                });
            },

            'form.name'(val){
                this.debouncer(() => {
                    if (this.form.slug) return;

                    this.form.slug = this.slugify(val);
                });
            },

            '$route'(){
                this.id = this.$route.params.id;

                this.loadEntry();
            }
        },


        methods: {
            loadEntry(){
                this.ready = false;

                this.http().get('/wink/api/team/' + this.id).then(response => {
                    this.entry = response.data.entry;

                    this.form.id = response.data.entry.id;

                    if (this.id != 'new') {
                        this.form.name = response.data.entry.name;
                        this.form.slug = response.data.entry.slug;
                        this.form.email = response.data.entry.email;
                        this.form.bio = response.data.entry.bio;
                        this.form.avatar = response.data.entry.avatar;
                    }

                    this.ready = true;
                }).catch(error => {
                    this.ready = true;
                });
            },


            /**
             * Save the post.
             */
            save(){
                this.form.working = true;
                this.form.errors = [];

                this.http().post('/wink/api/team/' + this.id, this.form).then(response => {
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


            deleteAuthor(){
                this.alertConfirm("Are you sure you want to delete this author?", () => {
                    this.http().delete('/wink/api/team/' + this.id).then(response => {
                        this.$router.push({name: 'team'})
                    }).catch(error => {
                        this.alertError(error.response.data.message);
                    });
                });
            },


            /**
             * Upload the selected image.
             */
            uploadSelectedImage(){
                let file = event.target.files[0];
                let formData = new FormData();

                formData.append('image', file, file.name);

                this.uploading = true;

                this.http().post('/wink/api/uploads', formData, {
                    onUploadProgress: progressEvent => {
                        this.uploadProgress = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                    }
                }).then(response => {
                    this.form.avatar = response.data.url;

                    this.uploading = false;
                }).catch(error => {
                });
            },
        }
    }
</script>

<template>
    <div>
        <page-header>
            <div slot="right-side">
                <div v-if="ready && entry">
                    <button class="btn btn-link btn-sm" @click="deleteAuthor" v-if="id != 'new'">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="icon fill-secondary">
                            <path d="M6 2l2-2h4l2 2h4v2H2V2h4zM3 6h14l-1 14H4L3 6zm5 2v10h1V8H8zm3 0v10h1V8h-1z"/>
                        </svg>
                    </button>

                    <button class="btn btn-outline-primary btn-sm ml-2" @click="save" v-loading="form.working">Save</button>
                </div>
            </div>
        </page-header>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="card">
                        <div v-if="!ready" class="d-flex align-items-center justify-content-center p-5 bottom-radius">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="preloader spin fill-secondary">
                                <path d="M10 3v2a5 5 0 0 0-3.54 8.54l-1.41 1.41A7 7 0 0 1 10 3zm4.95 2.05A7 7 0 0 1 10 17v-2a5 5 0 0 0 3.54-8.54l1.41-1.41zM10 20l-4-4 4-4v8zm0-12V0l4 4-4 4z"/>
                            </svg>
                        </div>


                        <div v-if="ready && !entry" class="d-flex align-items-center justify-content-center p-5 bottom-radius">
                            <h2 class="mb-5 text-center">404 — Author not found</h2>
                        </div>

                        <div v-if="ready && entry">
                            <h2 class="mb-5" v-if="id != 'new' && Config.author.id != entry.id">Edit Author</h2>
                            <h2 class="mb-5" v-if="id == 'new' && Config.author.id != entry.id">New Author</h2>
                            <h2 class="mb-5" v-if="Config.author.id == entry.id">Your Profile</h2>

                            <div class="form-group border-bottom pb-3">
                                <label for="name" class="inline-form-control-label">Name</label>
                                <input type="text" class="inline-form-control text-body-color"
                                       v-model="form.name"
                                       placeholder="Give me a name"
                                       id="name">

                                <form-errors :errors="form.errors.name"></form-errors>
                            </div>

                            <div class="form-group border-bottom pb-3">
                                <label for="slug" class="inline-form-control-label">Slug</label>
                                <input type="text" class="inline-form-control text-body-color"
                                       v-model="form.slug"
                                       placeholder="and-a-slug-please"
                                       id="slug">

                                <form-errors :errors="form.errors.slug"></form-errors>
                            </div>

                            <div class="form-group border-bottom pb-3">
                                <label for="email" class="inline-form-control-label">Email</label>
                                <input type="email" class="inline-form-control text-body-color"
                                       v-model="form.email"
                                       placeholder="email@example.com"
                                       id="email">

                                <form-errors :errors="form.errors.email"></form-errors>
                            </div>

                            <div class="form-group border-bottom pb-3">
                                <label for="password" class="inline-form-control-label">Password</label>
                                <input type="password" class="inline-form-control text-body-color"
                                       v-model="form.password"
                                       placeholder="*****"
                                       id="password">

                                <form-errors :errors="form.errors.password"></form-errors>
                            </div>

                            <div class="form-group border-bottom pb-3">
                                <label for="slug" class="inline-form-control-label">Bio</label>
                                <mini-editor v-model="form.bio"></mini-editor>
                                <form-errors :errors="form.errors.bio"></form-errors>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div v-if="uploading" class="d-flex align-items-center justify-content-center p-5 bottom-radius">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="preloader spin fill-secondary">
                                            <path d="M10 3v2a5 5 0 0 0-3.54 8.54l-1.41 1.41A7 7 0 0 1 10 3zm4.95 2.05A7 7 0 0 1 10 17v-2a5 5 0 0 0 3.54-8.54l1.41-1.41zM10 20l-4-4 4-4v8zm0-12V0l4 4-4 4z"/>
                                        </svg>
                                    </div>

                                    <img v-if="!uploading" :src="form.avatar" class="w-100">
                                </div>
                                <div class="col">
                                    <input type="file" class="d-none" id="author_avatar" v-on:change="uploadSelectedImage">
                                    <label for="author_avatar" class="uploadLabel">Upload an avatar</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .uploadLabel {
        text-decoration: underline;
        cursor: pointer;
    }
</style>
