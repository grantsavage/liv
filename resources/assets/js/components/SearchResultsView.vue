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
										<img class="media-object img-rounded" v-bind:src="result.avatar" v-bind:alt="result.name + ' avatar'" width="40" height="40">
									</a>
								</div>
								<div class="media-body">
									<a :href="'/user/' + result.username"><strong>{{ result.name }}</strong></a>
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
			getQuery(query) {
				if (query.length !== 0) {
					this.searching = true;
					this.$http.get('/search',{params:  {query: query}}).then((response) => {
						this.results = response.body;
					}).then((response) => {

					});
				} else {
					this.searching = false;
					eventHub.$emit("not-searching");
				}	
			}
		},
		mounted() {
			eventHub.$on("search",this.getQuery);
		}
	}
</script>