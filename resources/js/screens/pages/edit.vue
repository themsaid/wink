<script type="text/ecmascript-6">
    import $ from 'jquery';

    export default {
        data() {
            return {
                ready: false,
                entry: null,
                status: '',

                id: this.$route.params.id || 'new',

                errors: [],

                form: {
                    errors: [],
                    id: '',
                    title: 'Page Title',
                    slug: '',
                    body: '',
                }
            };
        },


        watch: {
            'form.slug'(val){
                this.debouncer(() => {
                    this.form.slug = this.slugify(val);
                });
            },

            'form.title'(val){
                this.debouncer(() => {
                    if (this.form.slug) return;

                    this.form.slug = this.slugify(val);
                });
            },

            '$route.params.id'(){
                this.id = this.$route.params.id;
            }
        },

        /**
         * Prepare the component.
         */
        mounted() {
            document.title = "Edit Page — Wink.";

            this.http().get('/api/pages/' + this.id).then(response => {
                this.entry = _.cloneDeep(response.data.entry);

                this.fillForm(response.data.entry);

                this.registerSaveKeyboardShortcut();

                this.ready = true;
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


            fillForm(data){
                this.form.id = data.id;

                if (this.id != 'new') {
                    this.form.title = data.title;
                    this.form.slug = data.slug;
                    this.form.body = data.body;
                }

                setTimeout(() => {
                    this.$watch('form', _.debounce(() => this.save(), 1000), {deep: true});
                }, 1000);
            },


            /**
             * Open the settings modal.
             */
            settingsModal(){
                $('#pageSettingsModal').modal('show');

                $('#title').focus();
            },


            /**
             * Close the settings modal.
             */
            closeSettingsModal(){
                $('#pageSettingsModal').modal('hide');
            },


            /**
             * Delete the page.
             */
            deletePage(){
                this.alertConfirm("Are you sure you want to delete this page?", () => {
                    $('#pageSettingsModal').modal('hide');

                    this.http().delete('/api/pages/' + this.id, this.form).then(response => {
                        this.$router.push({name: 'pages'})
                    })
                });
            },


            /**
             * Save the page.
             */
            save(){
                this.errors = [];
                this.status = 'Saving...';

                this.http().post('/api/pages/' + this.id, this.form).then(response => {
                    this.status = '';

                    if (this.id == 'new') {
                        this.$router.push({name: 'page-edit', params: {id: this.form.id}})
                    }
                }).catch(error => {
                    this.errors = error.response.data.errors;

                    $('#pageSettingsModal').modal('show');

                    this.form.working = false;
                });
            },
        }
    }
</script>

<template>
    <div>
        <page-header>
            <div slot="left-side">
                <strong v-if="!status && id == 'new'">New</strong>
                <strong v-if="!status && id != 'new'">Saved</strong>
                <span>{{status}}</span>
            </div>


            <div slot="right-side">
                <div v-if="ready && entry">
                    <button class="btn btn btn-link btn-sm btn-sm" @click="settingsModal">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="icon fill-secondary">
                            <path d="M17 16v4h-2v-4h-2v-3h6v3h-2zM1 9h6v3H1V9zm6-4h6v3H7V5zM3 0h2v8H3V0zm12 0h2v12h-2V0zM9 0h2v4H9V0zM3 12h2v8H3v-8zm6-4h2v12H9V8z"/>
                        </svg>
                    </button>
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
                            <h2 class="mb-5 text-center">404 — Page not found</h2>
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
                    <div class="modal" id="pageSettingsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <div class="form-group border-bottom pb-3">
                                        <label for="name" class="inline-form-control-label">Slug</label>
                                        <input type="text" class="inline-form-control text-body-color"
                                               v-model="form.slug"
                                               placeholder="Give me a slug"
                                               id="slug">

                                        <form-errors :errors="errors.slug"></form-errors>
                                    </div>

                                    <button class="btn btn-link text-danger p-0" @click="deletePage" v-if="id != 'new'">Delete this page</button>
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
</style>
