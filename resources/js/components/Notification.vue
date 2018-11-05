<script type="text/ecmascript-6">
    export default {
        props: ['type', 'message', 'autoClose'],

        data(){
            return {
                timeout: null
            }
        },


        mounted() {
            this.timeout = setTimeout(() => {
                this.close();
            }, this.autoClose);
        },


        methods: {
            /**
             * Close the notification.
             */
            close(){
                clearTimeout(this.timeout);

                this.$root.notification.type = null;
                this.$root.notification.autoClose = false;
                this.$root.notification.message = '';
            }
        }
    }
</script>

<template>
    <div id="notificationBody" class="text-white bg-success border px-5 py-2">
        {{message}}
    </div>
</template>

<style>
    #notificationBody {
        z-index: 99999;
        position: fixed;
        bottom: 20px;
        right: 10px;
    }
</style>
