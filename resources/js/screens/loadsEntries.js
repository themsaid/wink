module.exports = {
    computed: {
        isFiltered(){
            return !!this.searchQuery.length || this.filters.status;
        }
    },


    methods: {
        loadEntries(){
            this.http().get(this.baseURL + '?wink=wink' +
                (this.searchQuery ? '&search=' + this.searchQuery : '') +
                (this.filters.status ? '&status=' + this.filters.status : '')
            ).then(response => {
                this.entries = response.data.data;

                this.hasMoreEntries = !!response.data.next_page_url;

                this.nextPageUrl = response.data.next_page_url;

                this.ready = true;
            });
        },


        /**
         * Load the older entries.
         */
        loadOlderEntries(){
            this.loadingMoreEntries = true;

            this.http().get(this.nextPageUrl).then(response => {
                this.entries.push(...response.data.data);

                this.hasMoreEntries = !!response.data.next_page_url;

                this.nextPageUrl = response.data.next_page_url;

                this.loadingMoreEntries = false;
            });
        },


        /**
         * Filter the entries by the search query.
         */
        searchEntries(){
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
        focusSearchInput(){
            this.$nextTick(() => {
                this.$refs.searchInput.focus();
            });
        },


        /**
         * Watch changes and save the post.
         */
        watchFiltersChanges(){
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
