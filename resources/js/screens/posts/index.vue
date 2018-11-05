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
                this.http().get('/wink/api/posts').then(response => {
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
            <div slot="right-side">
                <router-link :to="{name:'post-new'}" class="btn btn-outline-primary btn-sm">
                    New Post
                </router-link>
            </div>
        </page-header>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h2>Posts</h2>
                        </div>

                        <div v-if="!ready" class="d-flex align-items-center justify-content-center p-5">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="preloader spin fill-secondary">
                                <path d="M10 3v2a5 5 0 0 0-3.54 8.54l-1.41 1.41A7 7 0 0 1 10 3zm4.95 2.05A7 7 0 0 1 10 17v-2a5 5 0 0 0 3.54-8.54l1.41-1.41zM10 20l-4-4 4-4v8zm0-12V0l4 4-4 4z"/>
                            </svg>
                        </div>


                        <div v-if="ready && entries.length == 0">
                            <p>No posts were found, start by
                                <router-link :to="{name:'post-new'}" class="">writing your first post</router-link>
                                .
                            </p>
                        </div>

                        <table v-if="ready && entries.length > 0" id="indexScreen" class="table mb-0 penultimate-column-right">
                            <tbody>
                            <tr v-for="entry in entries" :key="entry.id">
                                <td class="pl-0" :title="entry.title">
                                    <h5 class="mb-1 font-weight-bold">
                                        <router-link :to="{name:'post-edit', params:{id: entry.id}}" class="regular-link">
                                            {{truncate(entry.title, 68)}}
                                        </router-link>
                                    </h5>

                                    <p class="mb-1">{{truncate(entry.body.replace(/(<([^>]+)>)/ig,""), 100)}}</p>

                                    <small class="text-muted">
                                        Updated {{timeAgo(entry.updated_at)}}
                                        <span v-if="entry.tags.length">— Tags: {{formatTags(entry.tags)}}</span>
                                    </small>
                                </td>

                                <td class="table-fit">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="icon fill-success" v-if="entry.published">
                                        <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM6.7 9.29L9 11.6l4.3-4.3 1.4 1.42L9 14.4l-3.7-3.7 1.4-1.42z"/>
                                    </svg>

                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="icon fill-danger" v-else>
                                        <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm1.41-1.41A8 8 0 1 0 15.66 4.34 8 8 0 0 0 4.34 15.66zm9.9-8.49L11.41 10l2.83 2.83-1.41 1.41L10 11.41l-2.83 2.83-1.41-1.41L8.59 10 5.76 7.17l1.41-1.41L10 8.59l2.83-2.83 1.41 1.41z"/>
                                    </svg>
                                </td>

                                <td class="table-fit pr-0 text-right" :class="{'text-warning': timeAgo(entry.publish_date).startsWith('in')}">{{timeAgo(entry.publish_date)}}</td>
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
            </div>
        </div>
    </div>
</template>
