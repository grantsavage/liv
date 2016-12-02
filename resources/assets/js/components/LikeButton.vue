<template>
		<a class="like-2" href="#" @click.prevent="like">{{this.text}}&nbsp;&nbsp;<span :class="{hidden: this.liking}" class="glyphicon glyphicon-thumbs-up"></span><div :class="{hidden: !this.liking}" class="loader" style="display: inline-block;"></div></a>
</template>

<script>
	import eventHub from '../event'
	export default {
		data() {
			return {
				liking: false,
				text: "Like"
			}
		},
		props: [
			'post'
		],
		methods: {
			like() {
				this.liking = true;
				this.text = "";
				this.$http.post('/posts/'+ this.post.id +'/likes').then((response) => {
					eventHub.$emit('post-liked', this.post.id, true);
					this.liking = false;
					this.text = "Like";
				}).then((response)=> {
					this.liking = false;
					this.text = "Like";
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

	.like-2:hover, .like-2:focus {
		text-decoration: none !important;
	}
</style>