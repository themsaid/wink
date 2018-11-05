<script type="text/ecmascript-6">
    export default {
        props: ['type', 'message', 'autoClose', 'confirmationProceed', 'confirmationCancel'],

        data(){
            return {
                timeout: null,
            }
        },


        mounted() {
            if (this.autoClose) {
                this.timeout = setTimeout(() => {
                    this.close();
                }, this.autoClose);
            }
        },


        methods: {
            /**
             * Close the alert.
             */
            close(){
                clearTimeout(this.timeout);

                this.$root.alert.type = null;
                this.$root.alert.autoClose = false;
                this.$root.alert.message = '';
                this.$root.alert.confirmationProceed = null;
                this.$root.alert.confirmationCancel = null;
            },


            /**
             * Confirm and close the alert.
             */
            confirm(){
                this.confirmationProceed();

                this.close();
            },


            /**
             * Cancel and close the alert.
             */
            cancel(){
                if (this.confirmationCancel) {
                    this.confirmationCancel();
                }

                this.close();
            }
        }
    }
</script>

<template>
    <div id="alert" v-show="$root.alert.type">
        <div class="dialog">
            <div class="text-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" v-if="type == 'confirmation'" class="fill-danger">
                    <path d="M10 20a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm2-13c0 .28-.21.8-.42 1L10 9.58c-.57.58-1 1.6-1 2.42v1h2v-1c0-.29.21-.8.42-1L13 9.42c.57-.58 1-1.6 1-2.42a4 4 0 1 0-8 0h2a2 2 0 1 1 4 0zm-3 8v2h2v-2H9z"/>
                </svg>

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" v-if="type == 'success'" class="fill-success">
                    <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM6.7 9.29L9 11.6l4.3-4.3 1.4 1.42L9 14.4l-3.7-3.7 1.4-1.42z"/>
                </svg>

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" v-if="type == 'error'" class="fill-danger">
                    <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm1.41-1.41A8 8 0 1 0 15.66 4.34 8 8 0 0 0 4.34 15.66zm9.9-8.49L11.41 10l2.83 2.83-1.41 1.41L10 11.41l-2.83 2.83-1.41-1.41L8.59 10 5.76 7.17l1.41-1.41L10 8.59l2.83-2.83 1.41 1.41z"/>
                </svg>

                <p class="mt-3 mb-0">{{message}}</p>
            </div>


            <div class="d-flex justify-content-center">
                <button v-if="type == 'error'" class="btn btn-outline-primary btn-sm" @click="close">
                    Ok
                </button>

                <button v-if="type == 'success' && !$root.alert.autoClose" class="btn btn-outline-primary btn-sm" @click="close">
                    Ok
                </button>


                <button v-if="type == 'confirmation'" class="btn btn-outline-secondary btn-sm" @click="confirm">
                    Yes
                </button>
                <button v-if="type == 'confirmation'" class="btn btn-outline-success btn-sm ml-1" @click="cancel">
                    No, Abort
                </button>
            </div>
        </div>
    </div>
</template>

<style>
    #alert {
        position: absolute;
        z-index: 99999;
        width: 100%;
        height: 100%;
        background: #000000ba;
    }

    #alert svg {
        display: block;
        margin: 0 auto;
        width: 4rem;
        height: 4rem;
    }

    #alert .dialog{
        background: #fff;
        width: 400px;
        margin: 40px auto;
        padding: 20px;
    }
</style>
