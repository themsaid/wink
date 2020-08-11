module.exports = {
    computed: {
        isFiltered() {
            return !!this.searchQuery.length ||
                (this.filters && this.filters.status) ||
                (this.filters && this.filters.author_id) ||
                (this.filters && this.filters.tag_id)
                ;
        }
    },


    watch: {
        searchQuery() {
            this.searchEntries();
        }
    },


    methods: {
        loadEntries() {
            this.http().get(this.baseURL + '?wink=wink' +
                (this.searchQuery ? '&search=' + this.searchQuery : '') +
                (this.filters && this.filters.status ? '&status=' + this.filters.status : '') +
                (this.filters && this.filters.author_id ? '&author_id=' + this.filters.author_id : '') +
                (this.filters && this.filters.tag_id ? '&tag_id=' + this.filters.tag_id : '')
            ).then(response => {
                this.entries = response.data.data;

                this.hasMoreEntries = !!response.data.links.next;

                this.nextPageUrl = response.data.links.next;

                this.ready = true;
            });
        },


        /**
         * Load the older entries.
         */
        loadOlderEntries() {
            this.loadingMoreEntries = true;

            this.http().get(this.nextPageUrl).then(response => {
                this.entries.push(...response.data.data);

                this.hasMoreEntries = !!response.data.links.next;

                this.nextPageUrl = response.data.links.next;

                this.loadingMoreEntries = false;
            });
        },


        /**
         * Filter the entries by the search query.
         */
        searchEntries() {
            if (!this.searchQuery) {
                this.ready = false;
            }

            this.debouncer(() => {
                this.ready = false;

                this.loadEntries();
            });
        },


        /**
         * Focus the search input when the filter dropdown opens.
         */
        focusSearchInput() {
            this.$nextTick(() => {
                this.$refs.searchInput.focus();
            });
        },


        /**
         * Watch filters changes and fetch the entries.
         */
        watchFiltersChanges() {
            this.$watch('filters', () => {
                this.ready = false;

                this.debouncer(() => {
                    this.ready = false;

                    this.loadEntries();
                });
            }, {deep: true});
        },
    }
};
