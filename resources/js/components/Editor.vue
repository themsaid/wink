<script type="text/ecmascript">
    import _ from 'lodash';
    import Quill from 'quill';
    import ImageUploader from './editorComponents/ImageUploader.vue';
    import HTMLEmbedder from './editorComponents/HTMLEmbedder.vue';
    import WinkImageBlot from './editorComponents/WinkImageBlot.js';
    import WinkDividerBlot from './editorComponents/WinkDividerBlot.js';
    import HTMLBlot from './editorComponents/HTMLBlot.js';
    import WinkClipboard from './editorComponents/WinkClipboard.js';
    import WinkLink from './editorComponents/WinkLink.js';
    import Parchment from 'parchment';

    export default {
        components: {
            'image-uploader': ImageUploader,
            'html-embedder': HTMLEmbedder
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

        data() {
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

            // This can be used to auto focus the editor and show the sidebar controls
            // we need to detect if the editor is empty and should be autofocused
            //     this.editor.focus();
            //
            //     let sidebarControls = document.getElementById('sidebar-controls');
            //
            //     sidebarControls.classList.remove('active');
            //
            //     sidebarControls.style.display = 'block';
            //
            //     sidebarControls.style.left = -50 + 'px';
            //     sidebarControls.style.top = -2 + 'px';
        },

        methods: {
            /**
             * Create an instance of the editor.
             */
            createEditor() {
                let icons;

                Quill.register(WinkImageBlot, true);
                Quill.register(WinkDividerBlot, true);
                Quill.register(HTMLBlot, true);
                Quill.register(WinkLink, true);
                Quill.register('modules/clipboard', WinkClipboard, true)

                icons = Quill.import('ui/icons');
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
            handleEditorValue() {
                this.editor.root.innerHTML = this.value;

                this.editor.on('text-change', () => {
                    this.$emit('input', this.editor.getText() ? this.editor.root.innerHTML : '');
                });
            },


            /**
             * Handle click events inside the editor.
             */
            handleClicksInsideEditor() {
                this.editor.root.addEventListener('click', (ev) => {
                    let blot = Parchment.find(ev.target, true);

                    if (blot instanceof WinkImageBlot) {
                        var values = blot.value(blot.domNode)['captioned-image'];

                        values.existingBlot = blot;

                        this.openImageUploader(values);
                    }

                    if (blot instanceof HTMLBlot) {
                        var values = blot.value(blot.domNode)['html'];

                        values.existingBlot = blot;

                        this.openingHTMLEmbedder(values);
                    }
                });
            },


            /**
             * Init the side controls.
             */
            initSideControls() {
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
            showSideControls() {
                document.getElementById('sidebar-controls').classList.toggle('active');

                this.editor.focus();
            },


            openingHTMLEmbedder(data = null) {
                this.$emit('openingHTMLEmbedder', data);
            },

            openImageUploader(data = null) {
                this.$emit('openingImageUploader', data);
            },


            /**
             * Add a new captioned image to the content.
             */
            applyImage({url, caption, existingBlot, layout}) {
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
            addDivider() {
                let range = this.editor.getSelection(true);

                this.editor.insertText(range.index, '\n', Quill.sources.USER);
                this.editor.insertEmbed(range.index + 1, 'divider', true, Quill.sources.USER);
                this.editor.setSelection(range.index + 2, Quill.sources.SILENT);
            },


            /**
             * Add a new HTML blot to the content.
             */
            applyHTML({content, existingBlot}) {
                let values = {
                    content: content,
                };

                let range = this.editor.getSelection(true);

                if (existingBlot) {
                    return existingBlot.replaceWith('html', values);
                }

                this.editor.insertEmbed(range.index, 'html', values, Quill.sources.USER);

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
                <button @click="openingHTMLEmbedder()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="fill-current w-3">
                        <path d="M.7 9.3l4.8-4.8 1.4 1.42L2.84 10l4.07 4.07-1.41 1.42L0 10l.7-.7zm18.6 1.4l.7-.7-5.49-5.49-1.4 1.42L17.16 10l-4.07 4.07 1.41 1.42 4.78-4.78z"/>
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
        <html-embedder post-id="postId" @adding="applyHTML"></html-embedder>
    </div>
</template>
