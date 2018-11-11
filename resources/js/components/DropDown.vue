<script type="text/ecmascript-6">
    export default {
        data(){
            return {
                shouldShowContent: false
            }
        },

        methods: {
            toggle(){
                this.shouldShowContent = !this.shouldShowContent;
            },

            hide(){
                this.shouldShowContent = false;
            }
        }
    }
</script>

<template>
    <div v-click-outside="hide">
        <div v-on:click.prevent="toggle">
            <slot name="trigger"></slot>
        </div>
        <slot name="content" v-if="shouldShowContent"></slot>
    </div>
</template>