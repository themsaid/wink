<script type="text/ecmascript-6">
    export default {
        props: {
            network: {
                type: String,
                required: true
            },
            name: {
                type: String,
                required: true
            },
            form: {
                type: Object,
                required: true
            },
            expanded: {
                type: Boolean,
                required: true
            }
        },

        data(){
            return {
                is_uploading: false,

                errors: [],

                formWatcher: null,

                readableName: this.name.charAt(0).toUpperCase()+this.name.slice(1),
            }
        },


        mounted() {
        },


        watch: {
        },


        computed: {},


        methods: {
            /**
             * Update the network-specific social image.
             */
            updateSocialImage({url}){
                this.form.meta[this.network+'_image'] = url;
                this.is_uploading = false;
            },
        }
    }
</script>

<template>
    <div class="card input-group">
        <div :class="'card-header input-label '+(!this.expanded ? 'collapsed': '')" :id="name+'-heading'" data-toggle="collapse" :data-target="'#'+name+'-collapse'" aria-expanded="false" :aria-controls="name+'-collapse'">
            <img :src="'/vendor/wink/icons/'+name+'.svg'" :alt="readableName" class="network-icon" /> {{ readableName }} Sharing Settings <i></i>
        </div>
        <div :id="name+'-collapse'" :class="'accordion-collapse '+(this.expanded ? 'show': '')" :aria-labelledby="name+'-collapse'">
            <div class="card-body">
                <div class="input-group">
                    <label :for="network+'_title'" class="input-label">
                        {{ readableName }} Title
                    </label>
                    <div class="explainer-subtext">
                        If you don't want to use the current post title when sharing on {{ readableName }}, enter an alternate title here.
                    </div>
                    <input type="text" class="input"
                           v-model="form.meta[network+'_title']"
                           :placeholder="readableName+' Title'"
                           :id="network+'_title'">

                    <form-errors :errors="errors[network+'_title']"></form-errors>
                </div>

                <div class="input-group">
                    <label :for="network+'_description'" class="input-label">
                        {{ readableName }} Description
                    </label>
                    <div class="explainer-subtext">
                        If you don't want to use the meta description when sharing on {{ readableName }}, enter another description here.
                    </div>
                    <textarea class="input"
                        v-model="form.meta[network+'_description']"
                        :placeholder="readableName+' Description'"
                        :id="network+'_description'"></textarea>

                    <form-errors :errors="errors[network+'_description']"></form-errors>
                </div>

                <div class="input-group">
                    <label :for="network+'_image'" class="input-label">
                        {{ readableName }} Image
                    </label>

                    <div>
                        <div v-if="form.meta[network+'_image'] && !is_uploading" class="social-image-holder text-center">
                            <a :href="form.meta[network+'_image']" target="_new"><img :src="form.meta[network+'_image']"></a>
                        </div>

                        <div class="explainer-subtext">
                            If you don't want to use the featured image when sharing on {{ readableName }}, upload an alternate image here.
                        </div>

                        <image-picker :key="form.meta[network+'_image']"
                            @changed="updateSocialImage"
                            @uploading="is_uploading = true"></image-picker>

                        <form-errors :errors="errors[network+'_image']"></form-errors>
                    </div>

                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</template>
