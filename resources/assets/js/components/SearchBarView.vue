<template>
	<form class="navbar-form">
	      <input 
	      	autocomplete="off" 
	      	type="text" 
	      	class="form-control" 
	      	placeholder="Search for people..." 
	      	style="width: 20em" 
	      	name="query" 
	      	v-on:input="fire" 
	      	v-model="query">
	</form>
</template>

<script>
	import eventHub from "../event.js";
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
				eventHub.$emit("search",this.query);
			}
		},
		mounted() {

		}
	}
</script>
