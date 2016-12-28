<template>
	<div class="media">	
		<div class="media-left">
			<a :href="'/user/' + post.user.username">
				<img class="media-object img-rounded" v-bind:src="post.user.avatar" v-bind:alt="post.user.name + ' avatar'" width="40" height="40">
			</a>
		</div>
		<div class="media-body">
			<a :href="'/user/' + post.user.username"><strong>{{ post.user.name }}</strong></a>
			<div class="dropdown pull-right">
			    <button class="btn btn-default dropdown-toggle" :id="'post-menu-'+post.id" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span class="caret"></span></button>
			  <ul class="dropdown-menu" :aria-labelledby="'post-menu-'+post.id">
			    <li><a href="#">Edit</a></li>
			    <li><a href="#">Another action</a></li>
			    <li><a href="#">Something else here</a></li>
			    <li role="separator" class="divider"></li>
			    <li><a href="#">Separated link</a></li>
			  </ul>
			</div>
			<p>{{ post.body }}</p>
			<img :class="{hidden: !post.image_url}" :src="post.image_url" alt="" class="img-responsive time-image" style="max-width: 350px;max-height: 250;margin-bottom: 10px;" data-lity :data-lity-target="post.image_url">
			<p><like-button :post="post" v-if="post.likedByCurrentUser === false && post.user.id !== $root.user.id"></like-button>&nbsp;&nbsp;{{ post.likeCount }} {{ pluralize('like', post.likeCount) }}</p>
		</div>
	</div>
</template>

<script>
	import pluralize from 'pluralize'
	import LikeButton from './LikeButton.vue' 
	export default {
		props: [
			'post'
		],

		components: [
			LikeButton
		],
		
		methods: {
			pluralize
		}
	}
</script>

<style scoped>
	.media {
		position: relative;
		margin: 0;
		padding: 10px;
	}

	.media:hover .like {
		display: block;
	}

	.time-image:hover {
		cursor: pointer;
	}

	.btn-default {
		border: none;
	}

	.dropdown {
		z-index: 9999;
		position: relative;
	}
</style>