<script type="text/ecmascript-6">
    import _ from 'lodash';

    export default {
        props: ['input'],


        data(){
            return {
                form: {
                    meta_description: '',
                    opengraph_title: '',
                    opengraph_description: '',
                    twitter_title: '',
                    twitter_description: '',
                }
            }
        },


        mounted(){
            this.form = this.input;
        },


        methods: {
            close(){
                this.$emit('close', {
                    content: this.form
                });
            }
        }
    }
</script>

<template>
    <modal @close="close">
        <div class="input-group">
            <label for="meta_description" class="input-label">
                Meta description
            </label>
                <textarea class="input"
                          v-model="form.meta_description"
                          placeholder="Meta description"
                          id="meta_description"></textarea>
        </div>

        <div class="input-group">
            <label for="opengraph_title" class="input-label">
                Facebook Card Title
            </label>
            <input type="text" class="input"
                   v-model="form.opengraph_title"
                   placeholder="Title in Facebook Card"
                   id="opengraph_title">
        </div>

        <div class="input-group">
            <label for="opengraph_description" class="input-label">
                Facebook Card Description
            </label>
                <textarea class="input"
                          v-model="form.opengraph_description"
                          placeholder="Description in Facebook Card"
                          id="opengraph_description"></textarea>
        </div>

        <div class="input-group">
            <label for="twitter_title" class="input-label">
                Twitter Card Title
            </label>
            <input type="text" class="input"
                   v-model="form.twitter_title"
                   placeholder="Title in Twitter Card"
                   id="twitter_title">
        </div>

        <div class="input-group">
            <label for="twitter_description" class="input-label">
                Twitter Card Description
            </label>
                <textarea class="input"
                          v-model="form.twitter_description"
                          placeholder="Description in Twitter Card"
                          id="twitter_description"></textarea>
        </div>

        <div class="mt-10">
            <button class="btn-sm btn-primary" @click="close">Done</button>
        </div>
    </modal>
</template>