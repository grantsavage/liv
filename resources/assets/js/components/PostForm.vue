<template>
	<form action="#" class="form-vertical" @submit.prevent="post">
		<div class="form-group">
			<textarea class="form-control" cols="30" rows="3" placeholder="What's going on?" v-model="body"></textarea>

		</div>
		<button id="submitButton" type="submit" class="btn btn-primary">Post it!</button>
	</form>
</template>

<script>
	import eventHub from '../event'
	export default {
		data () {
			return {
				body: null
			}
		},

		methods: {
			post() {
				this.$http.post('/posts', {
					body: this.body
				}).then((response) => {
					// emit event
					eventHub.$emit('post-added', response.body)
					this.body = null
					$("#submitButton").blur();
				})
			}
		}
	}
</script>

<style scoped>
	textarea {
		resize: none;
	}
</style>