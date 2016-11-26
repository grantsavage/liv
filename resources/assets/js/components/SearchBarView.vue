<template>
	<form class="navbar-form">
	    <!--<div class="input-group">-->
	      <input type="text" class="form-control" placeholder="Search for people..." style="width: 20em" name="query" v-on:input="fire" v-model="query">
	      <!--<span class="input-group-btn">
	            <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
	      </span>
	    </div>
	    <input type="text" class="form-control hidden-xs hidden-sm" placeholder="Search for people..." style="width: 30em" name="query" v-on:input="fire" v-model="query">-->
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
			fire(){
				eventHub.$emit("search",this.query);
			}
		},
		mounted() {

		}
	}
</script>
