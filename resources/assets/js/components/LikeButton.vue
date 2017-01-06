<template>
	<p>
		<a 
		class="like-2" 
		href="#" 
		v-if="post.user.id != $root.user.id"
		@click.prevent="like">
			<span 
				:class="{hidden: this.liking}" 
				class="glyphicon glyphicon-thumbs-up" style="margin-right: 5px;">
			</span>
			{{this.text}}&nbsp;&nbsp;
			<div 
				:class="{hidden: !this.liking}" 
				class="loader" 
				style="display: inline-block;">
			</div>
		</a>
		<a 
		v-if="post.parent_id == null"
		class="like-2" 
		href="#" 
		@click.prevent="showCommentForm">
			<span 
				:class="" 
				class="glyphicon glyphicon-comment" style="margin-right: 5px;">
			</span>
			Comment&nbsp;&nbsp;
		</a>
	</p>
</template>

<script>
	import eventHub from '../event'
	export default {
		data() {
			return {
				liking: false,
				text: "Like",
			}
		},

		props: [
			'post'
		],

		methods: {
			/*
			 * Post like to server and update timeline
			 */
			like() {
				// Set up UI
				this.liking = true;
				this.text = "";

				// Make request
				this.$http.post('/posts/'+ this.post.id +'/likes').then((response) => {
					// Emit event
					eventHub.$emit('post-liked', this.post.id, true);

					// Reset UI
					this.liking = false;
					this.text = "Like";

				// Handle response error
				}, (response)=> {
					// Reset UI
					this.liking = false;
					this.text = "Like";
				});
			},

			showCommentForm() {
				eventHub.$emit("showCommentForm" + this.post.id);
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