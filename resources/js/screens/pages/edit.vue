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

                settingsModalShown: false,

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
                this.settingsModalShown = true;

                $('#title').focus();
            },


            /**
             * Close the settings modal.
             */
            closeSettingsModal(){
                this.settingsModalShown = false;
            },


            /**
             * Delete the page.
             */
            deletePage(){
                this.alertConfirm("Are you sure you want to delete this page?", () => {
                    this.settingsModalShown = false;

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

                    this.settingsModalShown = true;

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
                <span class="font-semibold" v-if="!status && id == 'new'">New</span>
                <span class="font-semibold" v-if="!status && id != 'new'">Saved</span>
                <span>{{status}}</span>
            </div>


            <div class="flex items-center" v-if="ready && entry" slot="right-side">
                <button class="focus:outline-none text-light hover:text-primary mr-5" @click="settingsModal">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="w-4 h-4 fill-current">
                        <path d="M17 16v4h-2v-4h-2v-3h6v3h-2zM1 9h6v3H1V9zm6-4h6v3H7V5zM3 0h2v8H3V0zm12 0h2v12h-2V0zM9 0h2v4H9V0zM3 12h2v8H3v-8zm6-4h2v12H9V8z"/>
                    </svg>
                </button>

                <button class="py-1 px-2 btn-primary text-sm ml-6" @click="save">Save</button>
            </div>
        </page-header>

        <div class="container">
            <preloader v-if="!ready"></preloader>

            <h2 v-if="ready && !entry" class="text-center font-normal">
                404 — Page not found
            </h2>

            <div class="lg:w-3/4 mx-auto" v-if="ready && entry">
                <textarea-autosize
                        placeholder="Type something here..."
                        class="text-3xl font-semibold w-full focus:outline-none"
                        v-model="form.title"
                ></textarea-autosize>

                <editor :post-id="id" v-model="form.body"></editor>
            </div>
        </div>

        <!-- Post Settings Modal -->
        <modal v-if="settingsModalShown" @close="settingsModalShown = false">
            <div class="input-group pt-0">
                <label for="name" class="input-label">Slug</label>
                <input type="text" class="input"
                       v-model="form.slug"
                       placeholder="Give me a slug"
                       id="slug">

                <form-errors :errors="errors.slug"></form-errors>
            </div>

            <button class="text-red hover:underline focus:outline-none mt-10" @click="deletePage" v-if="id != 'new'">Delete this post</button>

            <div class="mt-10">
                <button class="btn-sm btn-light" @click="settingsModalShown = false">Cancel</button>
            </div>
        </modal>
    </div>
</template>

<style scoped>
</style>
