<script type="text/ecmascript-6">

    export default {
        components: {},

        props: ['errors'],

        data(){
            return {}
        },


        mounted() {

        },


        methods: {}
    }
</script>

<template>
    <ul class="formErrorsList mt-1" v-if="errors">
        <li v-for="error in errors">
            <small>{{error}}</small>
        </li>
    </ul>
</template>
