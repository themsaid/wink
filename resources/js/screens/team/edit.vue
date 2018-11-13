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

                this.http().get('/api/team/' + this.id).then(response => {
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


            deleteAuthor(){
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
            uploadSelectedImage(){
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
        }
    }
</script>

<template>
    <div>
        <page-header>
            <div class="flex items-center" v-if="ready && entry" slot="right-side">
                <button class="focus:outline-none text-light hover:text-red" @click="deleteAuthor" v-if="id != 'new'">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="w-4 h-4 fill-current">
                        <path d="M6 2l2-2h4l2 2h4v2H2V2h4zM3 6h14l-1 14H4L3 6zm5 2v10h1V8H8zm3 0v10h1V8h-1z"/>
                    </svg>
                </button>

                <button class="py-1 px-2 btn-primary text-sm ml-6" @click="save" v-loading="form.working">Save</button>
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
    </div>
</template>
