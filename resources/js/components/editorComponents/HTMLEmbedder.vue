<script type="text/ecmascript">
    export default {
        props: ['postId'],

        data() {
            return {
                existingBlot: null,
                content: '',

                modalShown: false,
            }
        },


        mounted() {
            this.$parent.$on('openingHTMLEmbedder', data => {
                if (data) {
                    this.content = data.content;
                    this.existingBlot = data.existingBlot;
                }

                this.modalShown = true;

                this.$nextTick(() => this.$refs.content.focus());
            });
        },


        methods: {
            close() {
                this.modalShown = false;

                this.content = '';

                this.existingBlot = null;
            },


            applyHTML() {
                this.$emit('adding', {
                    content: this.content,
                    existingBlot: this.existingBlot,
                });

                this.close();
            }
        }
    }
</script>

<template>
    <modal v-if="modalShown" @close="close">
        <h2 class="font-semibold mb-5">Embed HTML</h2>

        <textarea ref="content" cols="30" rows="10" class="input"
                  placeholder="Paste your HTML here"
                  v-model="content"></textarea>

        <button class="btn-sm btn-primary mt-10" @click="applyHTML">Apply</button>
        <button class="btn-sm btn-light mt-10" @click="close">Cancel</button>
    </modal>
</template>

<style>

</style>
