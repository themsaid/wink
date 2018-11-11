<script type="text/ecmascript-6">
    import moment from 'moment';

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
            document.title = "Posts — Wink.";

            this.loadEntries();
        },


        methods: {
            loadEntries(){
                this.http().get('/api/posts').then(response => {
                    this.entries = response.data.entries.data;

                    this.hasMoreEntries = !!response.data.entries.next_page_url;

                    this.nextPageUrl = response.data.entries.next_page_url;

                    this.ready = true;
                });
            },


            /**
             * Load the older entries.
             */
            loadOlderEntries(){
                this.loadingMoreEntries = true;

                this.http().get(this.nextPageUrl).then(response => {
                    this.entries.push(...response.data.entries.data);

                    this.hasMoreEntries = !!response.data.entries.next_page_url;

                    this.nextPageUrl = response.data.entries.next_page_url;

                    this.loadingMoreEntries = false;
                });
            },


            /**
             * Format the given tags for display.
             */
            formatTags(tags){
                return _.chain(tags).map('name').join(', ').value();
            },


            /**
             * Determine if the given date is in the future.
             */
            dateInTheFuture(date){
                return moment().diff(moment(date + ' Z'), 'minutes') < 0;
            },
        }
    }
</script>

<template>
    <div>
        <page-header>
            <template slot="right-side">
                <router-link :to="{name:'post-new'}" class="no-underline text-sm text-primary hover:text-white hover:bg-primary rounded border border-primary py-1 px-2">
                    New Post
                </router-link>
            </template>
        </page-header>

        <div class="container">
            <h1 class="font-semibold text-3xl mb-10">Posts</h1>

            <preloader v-if="!ready"></preloader>

            <div v-if="ready && entries.length == 0">
                No posts were found, start by
                <router-link :to="{name:'post-new'}" class="no-underline text-primary hover:text-primary-dark">writing your first post</router-link>
                .
            </div>

            <div v-if="ready && entries.length > 0">
                <div v-for="entry in entries" :key="entry.id" class="border-t border-very-light flex items-center">
                    <div class="py-4" :title="entry.title">
                        <h2 class="text-xl font-semibold mb-3">
                            <router-link :to="{name:'post-edit', params:{id: entry.id}}" class="no-underline text-black">
                                {{truncate(entry.title, 68)}}
                            </router-link>
                        </h2>

                        <p class="mb-3">{{truncate(entry.body.replace(/(<([^>]+)>)/ig,""), 100)}}</p>

                        <small class="text-light">
                            <span v-if="entry.published && !dateInTheFuture(entry.publish_date)">Published {{timeAgo(entry.publish_date)}}</span>
                            <span v-if="entry.published && dateInTheFuture(entry.publish_date)" class="text-green">Scheduled {{timeAgo(entry.publish_date)}}</span>
                            <span v-if="! entry.published" class="text-red">Draft</span>
                            — Updated {{timeAgo(entry.updated_at)}}
                            <span v-if="entry.tags.length">— Tags: {{formatTags(entry.tags)}}</span>
                        </small>
                    </div>

                    <div class="ml-auto hidden lg:block">
                        <div class="w-16 h-16 rounded-full bg-cover" v-if="entry.featured_image" :style="{ backgroundImage: 'url(' + entry.featured_image + ')' }"></div>
                        <div class="w-16 h-16 rounded-full bg-light flex items-center justify-center text-4xl text-white" v-else="entry.featured_image">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="fill-current w-8">
                                <path d="M0 6c0-1.1.9-2 2-2h3l2-2h6l2 2h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V6zm10 10a5 5 0 1 0 0-10 5 5 0 0 0 0 10zm0-2a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                            </svg>
                        </div>
                    </div>
                </div>


                <div v-if="hasMoreEntries">
                    <div colspan="100" class="py-8 uppercase">
                        <a href="#" v-on:click.prevent="loadOlderEntries" v-if="!loadingMoreEntries" class="no-underline text-primary">Load more posts</a>

                        <span v-if="loadingMoreEntries">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
