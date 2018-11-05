<script type="text/ecmascript-6">
    import $ from 'jquery';

    export default {
        props: ['postId'],

        data(){
            return {
                content: '',
            }
        },


        mounted() {
            $('#editorHTMLEmbedModal').on('hidden.bs.modal', e => {

            });

            this.$parent.$on('openingHTMLEmbedder', data => {
                $('#editorHTMLEmbedModal').modal({
                    backdrop: 'static',
                });

                this.$nextTick(() => this.$refs.content.focus());
            });
        },


        methods: {
            /**
             * Close the modal.
             */
            close(){
                $('#editorHTMLEmbedModal').modal('hide');
            },


            addHTML(){
                this.close();

                this.$emit('adding', {
                    content: this.content,
                });

                this.content = '';
            }
        }
    }
</script>

<template>
    <div class="modal" id="editorHTMLEmbedModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h4 class="mb-4">Embed HTML</h4>

                    <textarea ref="content" cols="30" rows="10" class="inline-form-control text-body-color"
                              placeholder="Paste your HTML here"
                              v-model="content"></textarea>

                    <button class="btn btn-outline-primary btn-sm mt-4" @click="addHTML">Add HTML</button>
                    <button class="btn btn-outline-secondary btn-sm mt-4" @click="close">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</template>

<style>

</style>
