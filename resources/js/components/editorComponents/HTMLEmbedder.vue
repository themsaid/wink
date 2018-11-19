<script type="text/ecmascript-6">
    export default {
        props: ['postId'],

        data(){
            return {
                content: '',

                modalShown: false,
            }
        },


        mounted() {
            this.$parent.$on('openingHTMLEmbedder', data => {
                this.modalShown = true;

                this.$nextTick(() => this.$refs.content.focus());
            });
        },


        methods: {
            /**
             * Close the modal.
             */
            close(){
                this.modalShown = false;
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
    <modal v-if="modalShown" @close="close">
        <h2 class="font-semibold mb-5">Embed HTML</h2>

        <textarea ref="content" cols="30" rows="10" class="input"
                  placeholder="Paste your HTML here"
                  v-model="content"></textarea>

        <button class="btn-sm btn-primary mt-10" @click="addHTML">Add HTML</button>
        <button class="btn-sm btn-light mt-10" @click="close">Cancel</button>
    </modal>
</template>

<style>

</style>
