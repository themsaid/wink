<script type="text/ecmascript">
    export default {
        data() {
            return {}
        },

        created() {
            document.addEventListener('keydown', this.handleEscape);
            document.body.classList.add('overflow-hidden');
        },


        destroyed() {
            document.removeEventListener('keydown', this.handleEscape);
            document.body.classList.remove('overflow-hidden');
        },


        methods: {
            handleEscape(e) {
                e.stopPropagation();

                if (e.keyCode == 27) {
                    this.close();
                }
            },


            /**
             * Close the modal.
             */
            close() {
                this.$emit('close');
            },


            /**
             * Handle a click on the modal.
             */
            handleClicks(e) {
                if (e.target.classList.contains('modal-mask')) {
                    this.close();
                }
            }
        }
    }
</script>

<template>
    <transition name="modal">
        <div class="z-50 fixed pin overflow-y-scroll modal-mask" @click="handleClicks">
            <div class="bg-contrast relative rounded shadow-lg max-w-md mx-auto my-10 p-5 modal-container">
                <slot/>
            </div>
        </div>
    </transition>
</template>
