<script type="text/ecmascript">

    export default {
        components: {},

        props: ['errors'],

        data() {
            return {}
        },


        mounted() {

        },


        methods: {}
    }
</script>

<template>
    <div class="mt-2 text-sm">
        <span class="text-red block" v-for="error in errors">{{error}}</span>
    </div>
</template>
