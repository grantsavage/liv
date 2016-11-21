<template>
		<a class="like-2" href="#" @click.prevent="like">Like&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-up"></span></a>
</template>

<script>
	import eventHub from '../event'
	export default {
		props: [
			'post'
		],
		methods: {
			like() {
				//$(this).addClass('hidden');
				this.$http.post('/posts/'+ this.post.id +'/likes').then((response) => {
					eventHub.$emit('post-liked', this.post.id, true)
				});
			}
		}
	}
</script>

<style scoped>
	.like {
		display: none;
		position: absolute;
		width: 100%;
		height: 100%;
		top: 0; left: 0;
		background: rgba(0,0,0, .05);
		border-radius: 3px;
		box-sizing: border-box;
	}

	.like__button {
		position: absolute;
		top: 50%;
		right: 20px;
		transform: translate(0, -50%);
	}

	.like-2 {
		margin-right: 10px;
	}
</style>