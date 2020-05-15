<script type="text/ecmascript">
    import _ from 'lodash';

    export default {
        props: {
            value: {
                type: String,
                default: ''
            },
        },


        data() {
            return {
                content: ''
            }
        },

        watch: {
            content(val) {
                this.$emit('input', val);
            }
        },


        mounted() {
            this.content = this.value;

            this.$nextTick(() => {
                var textarea = document.getElementById("markdown-editor");
                var heightLimit = 2000;

                textarea.style.height = Math.min(textarea.scrollHeight, heightLimit) + "px";

                if (textarea) {
                    textarea.style.height = Math.min(textarea.scrollHeight, heightLimit) + "px";

                    textarea.oninput = function () {
                        textarea.style.height = "";
                        textarea.style.height = Math.min(textarea.scrollHeight, heightLimit) + "px";
                    };
                }
            });
        },


        methods: {}
    }
</script>

<template>
    <div>
       <textarea id="markdown-editor"
                 spellcheck="false"
                 class="w-full outline-none resize-none text-sm leading-loose font-mono"
                 cols="30" rows="10" v-model="content"
                 placeholder="Start writing *now*"></textarea>
    </div>
</template>
