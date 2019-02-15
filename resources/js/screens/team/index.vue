<script type="text/ecmascript">
    import loadsEntries from '../loadsEntries';
    import FiltersDropdown from '../../partials/FilterDropdown.vue';

    export default {
        mixins: [loadsEntries],


        components: {
            'filters': FiltersDropdown
        },


        /**
         * The component's data.
         */
        data() {
            return {
                baseURL: '/api/team',
                entries: [],
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
            document.title = "Team — Wink.";

            this.loadEntries();
        },
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
            <div class="mb-10 flex items-center">
                <h1 class="inline font-semibold text-3xl mr-auto">Team</h1>

                <filters @showing="focusSearchInput" :is-filtered="isFiltered">
                    <input type="text" class="input mt-0 w-full"
                           placeholder="Search..."
                           v-model="searchQuery"
                           ref="searchInput">
                </filters>
            </div>

            <preloader v-if="!ready"></preloader>

            <div v-if="ready && entries.length == 0 && !isFiltered">
                <p>No authors were found, start by
                    <router-link :to="{name:'team-new'}" class="no-underline text-primary hover:text-primary-dark">adding an author</router-link>
                    .
                </p>
            </div>

            <div v-if="ready && entries.length == 0 && isFiltered">
                No authors matched the given search.
            </div>

            <div v-if="ready && entries.length > 0">
                <div v-for="entry in entries" :key="entry.id" class="border-t border-very-light flex items-center py-5">
                    <div :title="entry.name">
                        <h2 class="text-xl font-semibold mb-3">
                            <router-link :to="{name:'team-edit', params:{id: entry.id}}" class="no-underline text-text-color">
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

                    <router-link :to="{name:'team-edit', params:{id: entry.id}}" class="no-underline hidden lg:block">
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
