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
                this.http().get('/wink/api/pages').then(response => {
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
                <router-link :to="{name:'page-new'}" class="btn btn-outline-primary btn-sm">
                    New page
                </router-link>
            </div>
        </page-header>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h2>Pages</h2>
                        </div>

                        <div v-if="!ready" class="d-flex align-items-center justify-content-center p-5">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="preloader spin fill-secondary">
                                <path d="M10 3v2a5 5 0 0 0-3.54 8.54l-1.41 1.41A7 7 0 0 1 10 3zm4.95 2.05A7 7 0 0 1 10 17v-2a5 5 0 0 0 3.54-8.54l1.41-1.41zM10 20l-4-4 4-4v8zm0-12V0l4 4-4 4z"/>
                            </svg>
                        </div>


                        <div v-if="ready && entries.length == 0">
                            <p>No pages were found, start by
                                <router-link :to="{name:'page-new'}" class="">building your first page</router-link>
                                .
                            </p>
                        </div>

                        <table v-if="ready && entries.length > 0" id="indexScreen" class="table table-sm mb-0">
                            <tbody>
                            <tr v-for="entry in entries" :key="entry.id">
                                <td class="pl-0" :title="entry.title">
                                    <h5 class="mb-1 font-weight-bold">
                                        <router-link :to="{name:'page-edit', params:{id: entry.id}}" class="regular-link">
                                            {{truncate(entry.title, 80)}}
                                        </router-link>
                                    </h5>

                                    <p class="mb-1">{{truncate(entry.body.replace(/(<([^>]+)>)/ig,""), 100)}}</p>

                                    <small class="text-muted">
                                        Updated {{timeAgo(entry.updated_at)}}
                                    </small>
                                </td>

                                <td class="table-fit pr-0 text-right">{{timeAgo(entry.created_at)}}</td>
                            </tr>


                            <tr v-if="hasMoreEntries">
                                <td colspan="100" class="text-center py-3">
                                    <small><a href="#" v-on:click.prevent="loadOlderEntries" v-if="!loadingMoreEntries">Load Older Pages</a></small>

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
