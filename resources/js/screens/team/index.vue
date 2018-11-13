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
            document.title = "Team — Wink.";

            this.loadEntries();
        },


        methods: {
            loadEntries(){
                this.http().get('/api/team?paginate=50').then(response => {
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
                <router-link :to="{name:'team-new'}" class="py-1 px-2 btn-primary text-sm">
                    New Author
                </router-link>
            </div>
        </page-header>

        <div class="container">
            <h1 class="font-semibold text-3xl mb-10">Team</h1>

            <preloader v-if="!ready"></preloader>

            <div v-if="ready && entries.length == 0">
                <p>No authors were found, start by
                    <router-link :to="{name:'team-new'}" class="no-underline text-primary hover:text-primary-dark">adding an author</router-link>
                    .
                </p>
            </div>

            <div v-if="ready && entries.length > 0">
                <div v-for="entry in entries" :key="entry.id" class="border-t border-very-light flex items-center py-5">
                    <div :title="entry.name">
                        <h2 class="text-xl font-semibold mb-3">
                            <router-link :to="{name:'team-edit', params:{id: entry.id}}" class="no-underline text-black">
                                {{truncate(entry.name, 68)}}
                            </router-link>
                        </h2>

                        <small class="text-light">
                            <span>{{entry.email}}</span>
                            — Created {{timeAgo(entry.created_at)}}
                        </small>
                    </div>

                    <div class="ml-auto text-light mr-8">
                        {{entry.posts_count}} Post(s)
                    </div>

                    <router-link :to="{name:'team-edit', params:{id: entry.id}}" class="no-underline ml-auto hidden lg:block">
                        <div class="w-16 h-16 rounded-full bg-cover" :style="{ backgroundImage: 'url(' + entry.avatar + ')' }"></div>
                    </router-link>
                </div>

                <div v-if="hasMoreEntries">
                    <div colspan="100" class="py-8 uppercase">
                        <a href="#" v-on:click.prevent="loadOlderEntries" v-if="!loadingMoreEntries" class="no-underline text-primary">Load more authors</a>

                        <span v-if="loadingMoreEntries">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
