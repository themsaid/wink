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
            }
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

            <div v-if="!ready" class="p-10 flex items-center justify-center text-light">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="spin fill-current w-10">
                    <path d="M10 3v2a5 5 0 0 0-3.54 8.54l-1.41 1.41A7 7 0 0 1 10 3zm4.95 2.05A7 7 0 0 1 10 17v-2a5 5 0 0 0 3.54-8.54l1.41-1.41zM10 20l-4-4 4-4v8zm0-12V0l4 4-4 4z"/>
                </svg>
            </div>


            <div v-if="ready && entries.length == 0">
                No posts were found, start by
                <router-link :to="{name:'post-new'}" class="no-underline text-primary hover:text-primary-dark">writing your first post</router-link>
                .
            </div>

            <table v-if="ready && entries.length > 0" id="indexScreen">
                <tbody>
                <tr v-for="entry in entries" :key="entry.id" class="border-t border-very-light">
                    <td class="pl-0 p-4 w-full" :title="entry.title">
                        <h2 class="text-xl font-semibold mb-2">
                            <router-link :to="{name:'post-edit', params:{id: entry.id}}" class="no-underline text-black">
                                {{truncate(entry.title, 68)}}
                            </router-link>
                        </h2>

                        <p class="mb-2">{{truncate(entry.body.replace(/(<([^>]+)>)/ig,""), 100)}}</p>

                        <small class="text-light">
                            Updated {{timeAgo(entry.updated_at)}}
                            <span v-if="entry.tags.length">— Tags: {{formatTags(entry.tags)}}</span>
                        </small>
                    </td>

                    <td class="w-1 p-4">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="w-4 fill-green" v-if="entry.published">
                            <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM6.7 9.29L9 11.6l4.3-4.3 1.4 1.42L9 14.4l-3.7-3.7 1.4-1.42z"/>
                        </svg>

                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="w-4 fill-red" v-else>
                            <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm1.41-1.41A8 8 0 1 0 15.66 4.34 8 8 0 0 0 4.34 15.66zm9.9-8.49L11.41 10l2.83 2.83-1.41 1.41L10 11.41l-2.83 2.83-1.41-1.41L8.59 10 5.76 7.17l1.41-1.41L10 8.59l2.83-2.83 1.41 1.41z"/>
                        </svg>
                    </td>

                    <td class="w-1 whitespace-no-wrap text-right p-4 pr-0" :class="{'text-warning': timeAgo(entry.publish_date).startsWith('in')}">{{timeAgo(entry.publish_date)}}</td>
                </tr>


                <tr v-if="hasMoreEntries">
                    <td colspan="100" class="text-center py-3">
                        <small><a href="#" v-on:click.prevent="loadOlderEntries" v-if="!loadingMoreEntries">Load Older Posts</a></small>

                        <small v-if="loadingMoreEntries">Loading...</small>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
