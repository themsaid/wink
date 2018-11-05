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

            this.http().get('/wink/api/tags/' + this.id).then(response => {
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
                    this.http().delete('/wink/api/tags/' + this.id, this.form).then(response => {
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

                this.http().post('/wink/api/tags/' + this.id, this.form).then(response => {
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
            <div slot="right-side">
                <div v-if="ready && entry">
                    <button class="btn btn-link btn-sm" @click="deleteTag" v-if="id != 'new'">
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
                            <h2 class="mb-5 text-center">404 — Tag not found</h2>
                        </div>

                        <div v-if="ready && entry">
                            <h2 class="mb-5" v-if="id != 'new'">Edit Tag</h2>
                            <h2 class="mb-5" v-else>New Tag</h2>

                            <div class="form-group border-bottom pb-3">
                                <label for="name" class="inline-form-control-label">Tag Name</label>
                                <input type="text" class="inline-form-control text-body-color"
                                       v-model="form.name"
                                       placeholder="Give me a name"
                                       id="name">

                                <form-errors :errors="form.errors.name"></form-errors>
                            </div>

                            <div class="form-group border-bottom pb-3">
                                <label for="name" class="inline-form-control-label">Tag Slug</label>
                                <input type="text" class="inline-form-control text-body-color"
                                       v-model="form.slug"
                                       placeholder="and-a-slug-please"
                                       id="slug">

                                <form-errors :errors="form.errors.slug"></form-errors>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
</style>
