<template>
	<form action="" class="navbar-form" @submit.prevent="">
	      <input 
	      	autocomplete="off" 
	      	type="text" 
	      	class="form-control" 
	      	id="search"
	      	placeholder="Search for people..." 
	      	style="width: 25em; border: none; box-shadow: none;" 
	      	name="query" >
	</form>
</template>

<script>
	//import eventHub from "../event.js";
	import autocomplete from "autocomplete.js"
	import algolia from "algoliasearch"
	export default {
		data() {
			return {
				query: ""
			}
		},

		methods: {
			/*
			 * Executed when input changes
			 */
			fire(){
				// Emit search event
				//eventHub.$emit("search",this.query);
			}
		},
		mounted() {
			const index = algolia('LW0X4WO13J','59760f43682cb7b271635abf93b3b600').initIndex('users');

			autocomplete('#search',{},{
				source: autocomplete.sources.hits(index, {hitsPerPage: 10}),
				displayKey: 'name',
				templates: {
					suggestion (suggestion) {
						return "<img src='" + suggestion.avatar + "' width='30' height='30'/> &nbsp; <span>"+ suggestion._highlightResult.name.value +"</span><span class='pull-right'>" + suggestion.location + "</span>"
					},
					footer: "<span class='pull-right'>powered by <img src='/images/algolia.jpg' width='63' height='20'/> &nbsp; </span>",
					empty: "<div>No Results</div>"
				}
			}).on('autocomplete:selected', (e, selection) => {
				window.location = '/user/' + selection.username
			});
		}
	}
</script>

<style scoped>
	input {
		border: none;
		box-shadow: none;
	}

	input:focus {
		box-shadow: none;
	}
</style>
