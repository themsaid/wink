<script type="text/ecmascript-6">
    import loadsEntries from '../loadsEntries';

    export default {
        mixins: [loadsEntries],


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

                <dropdown class="relative ml-4" @showing="focusSearchInput()">
                    <button slot="trigger" class="focus:outline-none text-light hover:text-primary h-8">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="w-4 h-4 fill-current">
                            <path d="M12.9 14.32a8 8 0 1 1 1.41-1.41l5.35 5.33-1.42 1.42-5.33-5.34zM8 14A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"/>
                        </svg>
                    </button>

                    <div slot="content" class="bg-white border border-lighter rounded absolute z-50 whitespace-no-wrap w-64 pin-r p-3">
                        <input type="text" class="input mt-0 w-full"
                               placeholder="Search..."
                               v-model="searchQuery"
                               ref="searchInput"
                               @input="searchEntries">
                    </div>
                </dropdown>
            </div>

            <preloader v-if="!ready"></preloader>

            <div v-if="ready && entries.length == 0 && !searchQuery">
                <p>No authors were found, start by
                    <router-link :to="{name:'team-new'}" class="no-underline text-primary hover:text-primary-dark">adding an author</router-link>
                    .
                </p>
            </div>

            <div v-if="ready && entries.length == 0 && searchQuery">
                No authors matched the given search.
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
