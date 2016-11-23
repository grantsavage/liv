<template>
	<div class="container">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="panel panel-default">
					<div class="panel-heading">
						<img class="img-rounded" :src="this.user.avatar" alt="" width="128" height="128">
						<h2 style="padding: 20px; display: inline;">{{this.user.name}}</h2>
						<a v-if="this.user.id == $root.user.id" href="/profile/edit" style="margin: 20px; display: inline;" class="btn btn-primary pull-right">Edit Profile</a>
						<p class="lead" style="display: inline;">{{this.user.location}}</p>
						<h4>Bio</h4>
						<p class="">{{this.user.bio}}</p>
					</div>
					<div class="panel-body">
						<post v-for="post in this.user.posts" :post="post"></post>
						<p v-if="this.user.posts <= 0">{{this.user.name}} hasn't posted anything yet!</p>
					</div>
					
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import Post from './Post.vue'
	export default {
		created() {
			// Get user and user posts
			this.$http.get('/user/'+this.username).then((response) => {
				this.user = response.body;
			});
		},
		data() {
			return {
				user: {},
			}
		},
		props: ['username'],
		components: [
			Post
		],
	}
</script>

<style scoped>
	
</style>