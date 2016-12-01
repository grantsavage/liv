<template>
	<div class="media">
		
		<div class="media-left">
			<a :href="'/user/' + post.user.username">
				<img class="media-object img-rounded" v-bind:src="post.user.avatar" v-bind:alt="post.user.name + ' avatar'" width="40" height="40">
			</a>
		</div>
		<div class="media-body">
			<a :href="'/user/' + post.user.username"><strong>{{ post.user.name }}</strong></a>
			<p>{{ post.body }}</p>
			<img :class="{hidden: !post.image_url}" :src="post.image_url" alt="" class="img-responsive" style="max-width: 350px;max-height: 250;margin-bottom: 10px;">
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
</style>