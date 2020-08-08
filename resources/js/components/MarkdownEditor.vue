<script type="text/ecmascript">
import _ from "lodash";

export default {
    props: {
        value: {
            type: String,
            default: ""
        }
    },

    data() {
        return {
            textarea: null,
            content: "",
            uploadProgress: 0,
        };
    },

    watch: {
        content(val) {
            this.$emit("input", val);
        }
    },

    mounted() {
        this.content = this.value;

        this.$nextTick(() => {
            this.textarea = document.getElementById("markdown-editor");
            var heightLimit = 2000;

            this.textarea.style.height =
                Math.min(this.textarea.scrollHeight, heightLimit) + "px";

            if (this.textarea) {
                this.textarea.style.height =
                    Math.min(this.textarea.scrollHeight, heightLimit) + "px";

                this.textarea.oninput = function() {
                    this.textarea.style.height = "";
                    this.textarea.style.height =
                        Math.min(this.textarea.scrollHeight, heightLimit) +
                        "px";
                };

                this.textarea.addEventListener("paste", this.onPaste);
            }
        });
    },

    destroyed() {
        if (this.textarea) {
            this.textarea.removeEventListener("paste", this.onPaste);
        }
    },

    methods: {
        onPaste(e) {
            if (e.clipboardData && e.clipboardData.items) {
                var items = e.clipboardData.items;
                for (var i = 0; i < items.length; i++) {
                    if (items[i].type.indexOf("image") !== -1) {
                        const image = items[i].getAsFile();
                        this.uploadPastedImage(image);
                        e.preventDefault();
                    }
                }
            }
        },

        uploadPastedImage(image) {
            let formData = new FormData();

            formData.append("image", image, image.name);

            const placeholder = `![Uploading ${image.name}…]()`;

            this.content = this.content.concat(placeholder);

            this.http()
                .post("/api/uploads", formData, {
                    onUploadProgress: progressEvent => {
                        this.uploadProgress = Math.round(
                            (progressEvent.loaded * 100) / progressEvent.total
                        );
                    }
                })
                .then(({ data }) => {
                    this.insertImage(placeholder, data.url);
                })
                .catch(error => {
                    console.log(error);
                });
        },
        insertImage(placeholder, url) {
            this.content = this.content.replace(
                placeholder,
                `![alt text](${url} "alt text)`
            );
        }
    }
};
</script>

<template>
    <div>
       <textarea id="markdown-editor"
                 spellcheck="false"
                 class="w-full font-mono text-sm leading-loose outline-none resize-none"
                 cols="30" rows="10" v-model="content"
                 placeholder="Start writing *now*"></textarea>
    </div>
</template>
