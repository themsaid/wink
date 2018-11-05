<script type="text/ecmascript-6">

    export default {
        /**
         * The component's data.
         */
        data() {
            return {};
        },


        /**
         * Prepare the component.
         */
        mounted() {
            document.title = "404 — Wink.";
        },


        methods: {}
    }
</script>

<template>
    <div>
        <page-header></page-header>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card">
                        <h2 class="mb-5 text-center">404 — Not found</h2>

                        <p class="text-center">The page you're looking for couldn't be found!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
