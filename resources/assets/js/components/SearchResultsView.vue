<template>
	<div class="container">
        <div :class="{hidden: !searching}" class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Search results</div>
                        <div class="panel-body">
                            <div class="media" v-for="result in results">
								<div class="media-left">
									<a :href="'/user/' + result.username">
										<img 
											class="media-object img-rounded" 
											v-bind:src="result.avatar" 
											v-bind:alt="result.name + ' avatar'" 
											width="40" 
											height="40">
									</a>
								</div>
								<div class="media-body">
									<a :href="'/user/' + result.username">
										<strong>{{ result.name }}</strong>
									</a>
								</div>
							</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
	import eventHub from '../event.js';
	export default {
		data() {
			return {
				results: [],
				searching: false
			}
		},
		methods: {
			/*
			 * Get the search query from the event
			 */
			getQuery(query) {
				// Check if query is not empty
				if (query.length !== 0) {

					// Setup UI
					this.searching = true;

					// Create request
					this.$http.get('/search',{params:  {query: query}}).then((response) => {

						// Get the results
						this.results = response.body;

					// Handle response error
					}).then((response) => {

					});

				// Reset UI
				} else {
					this.searching = false;
					eventHub.$emit("not-searching");
				}	
			}
		},

		/*
		 * Executed when view is mounted
		 */
		mounted() {
			// Set up event listener
			eventHub.$on("search",this.getQuery);
		}
	}
</script>