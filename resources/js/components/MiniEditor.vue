<script type="text/ecmascript-6">
    import $ from 'jquery';
    import _ from 'lodash';
    import Quill from 'quill';

    export default {
        components: {},

        props: {
            value: {
                type: String,
                default: ''
            }
        },

        data(){
            return {
                editor: null,
                editorBody: this.body
            }
        },


        mounted() {
            this.editor = this.createEditor();

            this.handleEditorValue();
        },


        /**
         * Clean after the component is destroyed.
         */
        destroyed() {
        },


        methods: {
            /**
             * Create an instance of the editor.
             */
            createEditor(){
                return new Quill(this.$refs.editor, {
                    modules: {
                        syntax: true,
                        toolbar: [
                            ['bold', 'italic', 'underline', 'strike', 'link'],
//                        [{'direction': 'rtl'}],
                        ]
                    },
                    theme: 'bubble',
                    scrollingContainer: 'html, body'
                });
            },


            /**
             * Handle the editor value.
             */
            handleEditorValue(){
                this.editor.root.innerHTML = this.value || 'Write something...';

                this.editor.on('text-change', () => {
                    this.$emit('input', this.editor.getText() ? this.editor.root.innerHTML : '');
                });
            }
        }
    }
</script>

<template>
    <div style="position: relative">
        <div ref="editor"></div>
    </div>
</template>

<style scoped>
    .ql-container {
        font-size: inherit;
    }
</style>
