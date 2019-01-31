<script type="text/ecmascript-6">
    import _ from 'lodash';
    import Quill from 'quill';
    import ImageUploader from './editorComponents/ImageUploader.vue';
    import HTMLEmbedder from './editorComponents/HTMLEmbedder.vue';
    import GistEmbedder from './editorComponents/GistEmbedder.vue';
    import ImageBlot from './editorComponents/ImageBlot.js';
    import DividerBlot from './editorComponents/DividerBlot.js';
    import HTMLBlot from './editorComponents/HTMLBlot.js';
    import GistBlot from './editorComponents/GistBlot.js';
    import Parchment from 'parchment';

    export default {
        components: {
            'image-uploader': ImageUploader,
            'html-embedder': HTMLEmbedder,
            'gist-embedder': GistEmbedder
        },

        props: {
            value: {
                type: String,
                default: ''
            },

            postId: {
                type: String
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

            this.handleClicksInsideEditor();

            this.initSideControls();
        },

        methods: {
            /**
             * Create an instance of the editor.
             */
            createEditor(){
                Quill.register(ImageBlot, true);
                Quill.register(DividerBlot, true);
                Quill.register(HTMLBlot, true);
                Quill.register(GistBlot, true);

                const icons = Quill.import('ui/icons');
                icons.header[3] = require('!html-loader!quill/assets/icons/header-3.svg');

                return new Quill(this.$refs.editor, {
                    modules: {
                        syntax: true,
                        toolbar: [
                            ['bold', 'italic', 'underline', 'strike', 'code'],
                            [{'header': '2'}, {'header': '3'}],
                            [{'list': 'ordered'}, {'list': 'bullet'}, 'link'],
                            ['blockquote', 'code-block'],
//                        [{'direction': 'rtl'}],
                        ]
                    },
                    theme: 'bubble',
                    scrollingContainer: 'html, body',
                    placeholder: "Starting writing now..."
                });
            },


            /**
             * Handle the editor value.
             */
            handleEditorValue(){
                this.editor.root.innerHTML = this.value;

                this.editor.on('text-change', () => {
                    this.$emit('input', this.editor.getText() ? this.editor.root.innerHTML : '');
                });
            },


            /**
             * Handle click events inside the editor.
             */
            handleClicksInsideEditor(){
                this.editor.root.addEventListener('click', (ev) => {
                    let blot = Parchment.find(ev.target, true);

                    if (blot instanceof ImageBlot) {
                        var values = blot.value(blot.domNode)['captioned-image'];

                        values.existingBlot = blot;

                        this.openImageUploader(values);
                    }
                });
            },


            /**
             * Init the side controls.
             */
            initSideControls(){
                let Block = Quill.import('blots/block');

                this.editor.on(Quill.events.EDITOR_CHANGE, (eventType, range) => {
                    let sidebarControls = document.getElementById('sidebar-controls');

                    if (eventType !== Quill.events.SELECTION_CHANGE) return;

                    if (range == null) return;

                    if (range.length === 0) {
                        let [block, offset] = this.editor.scroll.descendant(Block, range.index);

                        if (block != null && block.domNode.firstChild instanceof HTMLBRElement) {
                            let lineBounds = this.editor.getBounds(range);

                            sidebarControls.classList.remove('active');

                            sidebarControls.style.display = 'block';

                            sidebarControls.style.left = (lineBounds.left - 50) + 'px';
                            sidebarControls.style.top = (lineBounds.top - 2) + 'px';
                        } else {
                            sidebarControls.style.display = 'none';

                            sidebarControls.classList.remove('active');
                        }
                    } else {
                        sidebarControls.style.display = 'none';

                        sidebarControls.classList.remove('active');
                    }
                });
            },


            /**
             * Show the side controls.
             */
            showSideControls(){
                document.getElementById('sidebar-controls').classList.toggle('active');

                this.editor.focus();
            },


            openImageUploader(data = null){
                this.$emit('openingImageUploader', data);
            },


            /**
             * Add a new captioned image to the content.
             */
            applyImage({url, caption, existingBlot, layout}){
                let values = {
                    url: url,
                    caption: caption,
                    layout: layout,
                };

                if (existingBlot) {
                    return existingBlot.replaceWith('captioned-image', values);
                }

                let range = this.editor.getSelection(true);

                this.editor.insertEmbed(range.index, 'captioned-image', values, Quill.sources.USER);

                this.editor.setSelection(range.index + 1, Quill.sources.SILENT);
            },


            /**
             * Add a divider to the content.
             */
            addDivider(){
                let range = this.editor.getSelection(true);

                this.editor.insertText(range.index, '\n', Quill.sources.USER);
                this.editor.insertEmbed(range.index + 1, 'divider', true, Quill.sources.USER);
                this.editor.setSelection(range.index + 2, Quill.sources.SILENT);
            },


            /**
             * Add a new HTML blot to the content.
             */
            addHTML({content}){
                let range = this.editor.getSelection(true);

                this.editor.insertEmbed(range.index, 'html', {
                    content: content,
                }, Quill.sources.USER);

                this.editor.setSelection(range.index + 1, Quill.sources.SILENT);
            },


            /**
             * Add a new Gist to the content.
             */
            addGist(code){
                let range = this.editor.getSelection(true);

                this.editor.insertEmbed(range.index, 'gist', code, Quill.sources.USER);

                this.editor.setSelection(range.index + 1, Quill.sources.SILENT);
            },
        }
    }
</script>

<template>
    <div style="position: relative">
        <div id="sidebar-controls">
            <button id="show-controls" class="rounded-full w-8 h-8 border border-light text-light hover:bg-light hover:text-contrast text-center" @click="showSideControls">+</button>

            <div class="controls hidden pl-4 bg-contrast">
                <button @click="openImageUploader()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="fill-current w-3">
                        <path d="M0 4c0-1.1.9-2 2-2h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm11 9l-3-3-6 6h16l-5-5-2 2zm4-4a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                    </svg>
                </button>
                <button @click="$emit('openingHTMLEmbedder')">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="fill-current w-3">
                        <path d="M.7 9.3l4.8-4.8 1.4 1.42L2.84 10l4.07 4.07-1.41 1.42L0 10l.7-.7zm18.6 1.4l.7-.7-5.49-5.49-1.4 1.42L17.16 10l-4.07 4.07 1.41 1.42 4.78-4.78z"/>
                    </svg>
                </button>
                <button @click="$emit('openingGistEmbedder')">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="fill-current w-3">
                       <path style=" stroke:none;fill-rule:nonzero;fill:rgb(0%,0%,0%);fill-opacity:1;" d="M 18.640625 5.21875 C 17.746094 3.6875 16.535156 2.476562 15.003906 1.585938 C 13.472656 0.691406 11.800781 0.246094 9.988281 0.246094 C 8.175781 0.246094 6.503906 0.691406 4.976562 1.585938 C 3.445312 2.476562 2.234375 3.6875 1.339844 5.21875 C 0.445312 6.75 0 8.421875 0 10.234375 C 0 12.410156 0.636719 14.367188 1.90625 16.105469 C 3.175781 17.84375 4.816406 19.046875 6.828125 19.714844 C 7.0625 19.757812 7.234375 19.730469 7.347656 19.625 C 7.460938 19.519531 7.519531 19.390625 7.519531 19.234375 C 7.519531 19.207031 7.515625 18.976562 7.511719 18.53125 C 7.507812 18.089844 7.503906 17.703125 7.503906 17.375 L 7.207031 17.425781 C 7.015625 17.460938 6.773438 17.476562 6.484375 17.472656 C 6.195312 17.46875 5.890625 17.4375 5.578125 17.382812 C 5.265625 17.324219 4.976562 17.195312 4.707031 16.992188 C 4.441406 16.789062 4.25 16.519531 4.136719 16.191406 L 4.007812 15.890625 C 3.917969 15.691406 3.78125 15.472656 3.597656 15.230469 C 3.410156 14.984375 3.222656 14.820312 3.03125 14.734375 L 2.941406 14.667969 C 2.878906 14.625 2.824219 14.574219 2.769531 14.511719 C 2.71875 14.453125 2.679688 14.390625 2.652344 14.332031 C 2.628906 14.269531 2.648438 14.21875 2.71875 14.179688 C 2.789062 14.140625 2.914062 14.125 3.09375 14.125 L 3.355469 14.160156 C 3.527344 14.195312 3.742188 14.300781 4 14.472656 C 4.253906 14.648438 4.464844 14.871094 4.628906 15.148438 C 4.828125 15.507812 5.070312 15.777344 5.351562 15.964844 C 5.632812 16.148438 5.917969 16.242188 6.203125 16.242188 C 6.492188 16.242188 6.738281 16.222656 6.945312 16.179688 C 7.152344 16.136719 7.347656 16.070312 7.53125 15.984375 C 7.609375 15.402344 7.820312 14.957031 8.167969 14.644531 C 7.675781 14.589844 7.230469 14.511719 6.835938 14.410156 C 6.441406 14.304688 6.03125 14.136719 5.613281 13.902344 C 5.191406 13.667969 4.84375 13.375 4.566406 13.03125 C 4.289062 12.683594 4.058594 12.226562 3.882812 11.664062 C 3.703125 11.101562 3.617188 10.449219 3.617188 9.714844 C 3.617188 8.664062 3.957031 7.769531 4.644531 7.035156 C 4.324219 6.246094 4.351562 5.359375 4.734375 4.378906 C 4.984375 4.300781 5.359375 4.359375 5.851562 4.554688 C 6.347656 4.75 6.710938 4.917969 6.9375 5.058594 C 7.167969 5.195312 7.351562 5.3125 7.492188 5.40625 C 8.296875 5.183594 9.132812 5.070312 9.988281 5.070312 C 10.847656 5.070312 11.679688 5.183594 12.488281 5.40625 L 12.980469 5.097656 C 13.320312 4.886719 13.71875 4.695312 14.179688 4.523438 C 14.636719 4.351562 14.988281 4.300781 15.230469 4.378906 C 15.621094 5.359375 15.65625 6.246094 15.335938 7.035156 C 16.019531 7.769531 16.363281 8.664062 16.363281 9.714844 C 16.363281 10.449219 16.273438 11.101562 16.097656 11.671875 C 15.917969 12.238281 15.6875 12.695312 15.40625 13.035156 C 15.125 13.378906 14.773438 13.667969 14.355469 13.902344 C 13.933594 14.136719 13.527344 14.304688 13.132812 14.410156 C 12.738281 14.511719 12.292969 14.589844 11.796875 14.644531 C 12.25 15.035156 12.472656 15.648438 12.472656 16.488281 L 12.472656 19.234375 C 12.472656 19.390625 12.527344 19.519531 12.636719 19.625 C 12.746094 19.726562 12.917969 19.757812 13.152344 19.714844 C 15.164062 19.046875 16.804688 17.84375 18.074219 16.105469 C 19.34375 14.367188 19.980469 12.410156 19.980469 10.234375 C 19.980469 8.421875 19.53125 6.75 18.640625 5.21875 Z M 18.640625 5.21875 "/>
                    </svg>
                </button>
                <button @click="addDivider">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="fill-current w-3">
                        <path d="M4 12a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm6 0a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm6 0a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/>
                    </svg>
                </button>
            </div>
        </div>

        <div ref="editor"></div>

        <image-uploader post-id="postId" @updated="applyImage"></image-uploader>
        <html-embedder post-id="postId" @adding="addHTML"></html-embedder>
        <gist-embedder post-id="postId" @adding="addGist"></gist-embedder>
    </div>
</template>
