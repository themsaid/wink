<script type="text/ecmascript-6">

    export default {
        /**
         * The component's data.
         */
        data() {
            return {
                entries: [],
                hasMoreEntries: false,
                nextPageUrl: null,
                loadingMoreEntries: false,
                ready: false
            };
        },


        /**
         * Prepare the component.
         */
        mounted() {
            document.title = "Pages — Wink.";

            this.loadEntries();
        },


        methods: {
            loadEntries(){
                this.http().get('/api/pages').then(response => {
                    this.entries = response.data.entries.data;

                    this.hasMoreEntries = !!response.data.entries.next_page_url;

                    this.nextPageUrl = response.data.entries.next_page_url;

                    this.ready = true;
                });
            },

            loadOlderEntries(){
                this.loadingMoreEntries = true;

                this.http().get(this.nextPageUrl).then(response => {
                    this.entries.push(...response.data.entries.data);

                    this.hasMoreEntries = !!response.data.entries.next_page_url;

                    this.nextPageUrl = response.data.entries.next_page_url;

                    this.loadingMoreEntries = false;
                });
            }
        }
    }
</script>

<template>
    <div>
        <page-header>
            <template slot="right-side">
                <router-link :to="{name:'page-new'}" class="no-underline text-sm text-primary hover:text-white hover:bg-primary rounded border border-primary py-1 px-2">
                    New Page
                </router-link>
            </template>
        </page-header>

        <div class="container">
            <h1 class="font-semibold text-3xl mb-10">Pages</h1>

            <preloader v-if="!ready"></preloader>

            <div v-if="ready && entries.length == 0">
                No pages were found, start by
                <router-link :to="{name:'page-new'}" class="no-underline text-primary hover:text-primary-dark">writing your first page</router-link>
                .
            </div>

            <div v-if="ready && entries.length > 0">
                <div v-for="entry in entries" :key="entry.id" class="border-t border-very-light flex items-center">
                    <div class="py-4" :title="entry.title">
                        <h2 class="text-xl font-semibold mb-3">
                            <router-link :to="{name:'page-edit', params:{id: entry.id}}" class="no-underline text-black">
                                {{truncate(entry.title, 68)}}
                            </router-link>
                        </h2>

                        <p class="mb-3">{{truncate(entry.body.replace(/(<([^>]+)>)/ig,""), 100)}}</p>

                        <small class="text-light">
                            Updated {{timeAgo(entry.updated_at)}}
                            — Created {{timeAgo(entry.created_at)}}
                        </small>
                    </div>
                </div>


                <div v-if="hasMoreEntries">
                    <div colspan="100" class="py-8 uppercase">
                        <a href="#" v-on:click.prevent="loadOlderEntries" v-if="!loadingMoreEntries" class="no-underline text-primary">Load more pages</a>

                        <span v-if="loadingMoreEntries">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
