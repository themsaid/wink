<script type="text/ecmascript-6">

    export default {
        /**
         * The component's data.
         */
        data() {
            return {
                entries: [],
                hasRecords: false,
                hasMoreEntries: false,
                nextPageUrl: null,
                loadingMoreEntries: false,
                ready: false,
                searchQuery: ''
            };
        },


        /**
         * Prepare the component.
         */
        mounted() {
            document.title = "Tags — Wink.";

            this.loadEntries();
        },


        methods: {
            loadEntries(){
                this.http().get('/api/tags?paginate=50').then(response => {
                    this.entries = response.data.entries.data;

                    this.hasRecords = !!this.entries.length;

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
            },

            /**
             * Filter the entries by the search query.
             */
            filterEntries: _.debounce(function () {
                this.ready = false;

                this.http().get('/api/tags?paginate=50&search=' + this.searchQuery).then(response => {
                    this.entries = response.data.entries.data;

                    this.hasMoreEntries = !!response.data.entries.next_page_url;

                    this.nextPageUrl = response.data.entries.next_page_url;

                    this.ready = true;
                });
            }, 500)
        }
    }
</script>

<template>
    <div>
        <page-header>
            <div slot="right-side">
                <router-link :to="{name:'tag-new'}" class="py-1 px-2 btn-primary text-sm">
                    New Tag
                </router-link>
            </div>
        </page-header>

        <div class="container">
            <div class="mb-10">
                <h1 class="inline font-semibold text-3xl">Tags</h1>
                <input v-if="hasRecords"
                       type="text" class="input mt-1 border-b border-very-light pb-2 w-1/4 float-right"
                       placeholder="Search"
                       v-model="searchQuery"
                       @input="filterEntries"
                       id="search">
            </div>

            <preloader v-if="!ready"></preloader>

            <div v-if="ready && entries.length == 0 && !hasRecords">
                <p>No tags were found, start by
                    <router-link :to="{name:'tag-new'}" class="no-underline text-primary hover:text-primary-dark">adding some tags</router-link>
                    .
                </p>
            </div>

            <div v-if="entries.length == 0 && searchQuery && ready">
                No tags matched the given search.
            </div>

            <div v-if="ready && entries.length > 0">
                <div v-for="entry in entries" :key="entry.id" class="border-t border-very-light flex items-center">
                    <div class="py-4" :title="entry.title">
                        <h2 class="text-xl font-semibold">
                            <router-link :to="{name:'tag-edit', params:{id: entry.id}}" class="no-underline text-black">
                                {{truncate(entry.name, 80)}}
                            </router-link>
                        </h2>
                    </div>

                    <div class="ml-auto text-light mr-8">
                        {{entry.posts_count}} Post(s)
                    </div>

                    <div>{{timeAgo(entry.created_at)}}</div>
                </div>


                <tr v-if="hasMoreEntries">
                    <td colspan="100" class="text-center py-3">
                        <small><a href="#" v-on:click.prevent="loadOlderEntries" v-if="!loadingMoreEntries">Load Older Tags</a></small>

                        <small v-if="loadingMoreEntries">Loading...</small>
                    </td>
                </tr>
            </div>
        </div>
    </div>
</template>
