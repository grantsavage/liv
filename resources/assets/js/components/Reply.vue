<template>
	<div class="media" :id="'reply'+reply.id">
		<div class="media-left">
			<a :href="'/user/' + reply.user.username">
				<img class="media-object img-rounded" v-bind:src="reply.user.avatar" v-bind:alt="reply.user.name + ' avatar'" width="25" height="25">
			</a>
		</div>
		<div class="media-body">
			<a :href="'/user/' + reply.user.username"><strong>{{ reply.user.name }}</strong></a>
			<p>{{ reply.body }}</p>
			<like-button :post="reply"></like-button>
		</div>
	</div>
</template>

<script>
	export default {
		props: [
			'reply'
		],

		methods: {

		}
	}
</script>