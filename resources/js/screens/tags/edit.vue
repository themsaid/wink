<script type="text/ecmascript-6">

    export default {
        data() {
            return {
                entry: null,
                ready: false,

                id: this.$route.params.id || 'new',

                form: {
                    errors: [],
                    working: false,
                    id: '',
                    name: '',
                    slug: '',
                }
            };
        },

        /**
         * Prepare the component.
         */
        mounted() {
            document.title = "Tag — Wink.";

            this.http().get('/api/tags/' + this.id).then(response => {
                this.entry = response.data.entry;

                this.form.id = response.data.entry.id;

                if (this.id != 'new') {
                    this.form.name = response.data.entry.name;
                    this.form.slug = response.data.entry.slug;
                }

                this.ready = true;
            }).catch(error => {
                this.ready = true;
            });
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
        },


        methods: {
            /**
             * Delete the tag.
             */
            deleteTag(){
                this.alertConfirm("Are you sure you want to delete this tag?", () => {
                    this.http().delete('/api/tags/' + this.id, this.form).then(response => {
                        this.$router.push({name: 'tags'})
                    })
                });
            },


            /**
             * Save the tag.
             */
            save(){
                this.form.working = true;
                this.form.errors = [];

                this.http().post('/api/tags/' + this.id, this.form).then(response => {
                    this.form.working = false;

                    this.notifySuccess('Saved!', 2000);
                }).catch(error => {
                    this.form.errors = error.response.data.errors;

                    this.form.working = false;
                });
            }
        }
    }
</script>

<template>
    <div>
        <page-header>
            <div class="flex items-center" v-if="ready && entry" slot="right-side">
                <button class="focus:outline-none text-light hover:text-red" @click="deleteTag" v-if="id != 'new'">
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
                404 — Tag not found
            </h2>

            <div class="lg:w-2/3 mx-auto" v-if="ready && entry">
                <h1 class="font-semibold text-3xl mb-10" v-if="id != 'new'">Edit Tag</h1>
                <h1 class="font-semibold text-3xl mb-10" v-else>New Tag</h1>

                <div class="input-group">
                    <label for="name" class="input-label">Tag Name</label>
                    <input type="text" class="input"
                           v-model="form.name"
                           placeholder="Give me a name"
                           id="name">

                    <form-errors :errors="form.errors.name"></form-errors>
                </div>

                <div class="input-group">
                    <label for="name" class="input-label">Tag Slug</label>
                    <input type="text" class="input"
                           v-model="form.slug"
                           placeholder="and-a-slug-please"
                           id="slug">

                    <form-errors :errors="form.errors.slug"></form-errors>
                </div>
            </div>
        </div>
    </div>
</template>