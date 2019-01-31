<script type="text/ecmascript-6">
    export default {
        props: ['postId'],

        data() {
            return {
                content: '',

                modalShown: false,
            }
        },


        mounted() {
            this.$parent.$on('openingGistEmbedder', data => {
                this.modalShown = true;

                this.$nextTick(() => this.$refs.content.focus());
            });
        },


        methods: {
            /**
             * Close the modal.
             */
            close() {
                this.modalShown = false;
            },


            addGist() {
                this.close();

                this.$emit('adding', this.content);

                this.content = '';
            }
        }
    }
</script>

<template>
    <modal v-if="modalShown" @close="close">
        <h2 class="font-semibold mb-5">Embed Gist</h2>

        <input type="text" ref="content" class="input"
                  placeholder="Gist code, e.g. username/031031230031"
                  v-model="content"/>

        <button class="btn-sm btn-primary mt-10" @click="addGist">Add Gist</button>
        <button class="btn-sm btn-light mt-10" @click="close">Cancel</button>
    </modal>
</template>

<style>

</style>
