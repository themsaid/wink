<script type="text/ecmascript-6">
    export default {
        data(){
            return {
                modalBg: null
            }
        },

        created() {
            document.addEventListener('keydown', this.handleEscape);
            document.body.classList.add('overflow-hidden');

            const modalBg = document.createElement('div');
            modalBg.classList = 'fixed pin modal-overlay-bg z-20 opacity-75';
            modalBg.addEventListener('click', this.close);

            this.modalBg = modalBg;

            document.body.appendChild(this.modalBg);
        },


        destroyed() {
            document.removeEventListener('keydown', this.handleEscape);
            document.body.classList.remove('overflow-hidden');
            document.body.removeChild(this.modalBg);
        },


        methods: {
            handleEscape(e) {
                e.stopPropagation();

                if (e.keyCode == 27) {
                    this.close();
                }
            },


            close() {
                this.$emit('close');
            },
        }
    }
</script>

<template>
    <div class="z-30 fixed pin overflow-y-scroll">
        <div class="bg-white rounded shadow-lg max-w-md mx-auto my-10 p-5">
            <slot/>
        </div>
    </div>
</template>