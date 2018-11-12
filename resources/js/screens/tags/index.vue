<script type="text/ecmascript-6">

    export default {
        /**
         * The component's data.
         */
        data() {
            return {
                entries: [],
                ready: false,
                nextPageUrl: null,
                hasMoreEntries: false,
                loadingMoreEntries: false,
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
            <div slot="right-side">
                <router-link :to="{name:'tag-new'}" class="py-1 px-2 btn-primary text-sm">
                    New Tag
                </router-link>
            </div>
        </page-header>

        <div class="container">
            <h1 class="font-semibold text-3xl mb-10">Tags</h1>

            <preloader v-if="!ready"></preloader>

            <div v-if="ready && entries.length == 0">
                <p>No tags were found, start by
                    <router-link :to="{name:'tag-new'}" class="no-underline text-primary hover:text-primary-dark">adding some tags</router-link>
                    .
                </p>
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
