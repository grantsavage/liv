<template>
	<div class="media">	
		<div class="media-left">
			<a :href="'/user/' + post.user.username">
				<img class="media-object img-rounded" v-bind:src="post.user.avatar" v-bind:alt="post.user.name + ' avatar'" width="40" height="40">
			</a>
		</div>
		<div class="media-body">
			
			<a :href="'/user/' + post.user.username"><strong>{{ post.user.name }}</strong></a>

			<div v-if="post.user.id == $root.user.id" class="dropdown pull-right post-dropdown" style="z-index: 1000;">
			    <button 
			    	class="btn btn-default dropdown-toggle" 
			    	:id="'post-menu-'+post.id" 
			    	data-toggle="dropdown" 
			    	aria-haspopup="true" 
			    	aria-expanded="true">
			    		<span class="caret"></span>
			    </button>

			  	<ul class="dropdown-menu" :aria-labelledby="'post-menu-'+post.id">
			    	<li @click.prevent="postDelete">
			    		<a style="color: #ED4F32" class="text-center post-delete" href="#">
			    			Delete
			    		</a>
			    	</li>
			  	</ul>

			</div>

			<p>{{ post.body }}</p>

			<img 
				:class="{hidden: !post.image_url || post.is_video}" 
				:src="post.image_url" 
				alt="" 
				class="img-responsive time-image" 
				style="max-width: 75%;max-height: 100%;margin-bottom: 10px;" 
				data-lity 
				:data-lity-target="post.image_url"
				@click="fixDropdown"
				v-if="!post.is_video">
			
			<div 
				class="wrapper" 
				v-if="post.is_video && post.image_url"
				style="max-width: 75%;">
				<div class="videocontent">
					<video
						:src="post.image_url"
						class="video-js vjs-default-skin vjs vjs-big-play-centered"
						:id="'vjs' + post.id"
						data-setup='{"fluid": true}'>
					</video>
				</div>
			</div>

			<p>
				<like-button 
				:post="post" 
				>
				</like-button>
				&nbsp;&nbsp;{{ post.likeCount }} {{ pluralize('like', post.likeCount) }}&nbsp;&nbsp;&nbsp;&nbsp;
				<span class="comments" v-if="post.commentCount > 0" @click="showComments" style="cursor: pointer;">
					{{ post.commentCount }} {{ pluralize('comment', post.commentCount) }}
				</span>
			</p>
			
			<div style="background-color: #f5f8fa;">
				<reply v-if="post.replies && shouldShowComments" v-for="reply in post.replies" :reply="reply"></reply>
			</div>
			
			<div :class="{'hidden' : !shouldShowCommentForm}" style="margin-top: 10px;">
				<textarea class="form-control" rows="1" placeholder="Comment..." style="width: 15em; resize: none; margin-bottom: 10px;" v-model="comment"></textarea>
				<button 
					@click="postComment" 
					class="btn btn-primary btn-sm" 
					v-bind:class="{'disabled': comment == '' || comment == null || commenting}">
						Post 
						<div 
							:class="{'hidden': !commenting}" 
							class="button-loader" 
							style="display: inline-block;">
						</div>
				</button>
			</div>
		</div>
	</div>
</template>

<script>
	import pluralize from 'pluralize'
	import LikeButton from './LikeButton.vue' 
	import eventHub from '../event.js'
	import autosize from 'autosize'
	export default {

		data() {
			return {
				comment: null,
				commenting: false,
				shouldShowComments: false,
				shouldShowCommentForm: false,
				//videoInitalized: false
			}
		},

		props: [
			'post'
		],

		components: [
			LikeButton
		],
		
		methods: {
			pluralize,

			/*
			 * Method for deleting the post
			 */
			postDelete() {

				this.$http.delete('/post/'+this.post.id).then((response) => {
					if (response) {
						swal({title:"Successfully Deleted Post", type:"success",timer: 2000,showCloseButton: false,showConfirmButton: false});
						eventHub.$emit('postDelete', this.post.id);
					}
					swal({title:"Successfully Deleted Post", type:"success",timer: 2000,showCloseButton: false,showConfirmButton: false});
				}, (response) => {
					swal({title:"Whoops...", text: 'There was a problem deleting the post.', type:"error", showCloseButton: false});
				});
			},

			/*
			 * Method to post a comment to the post
			 */
			postComment() {

				if (this.comment == null || this.comment == "") {

				} else {
					this.commenting = true;
					var data = new FormData();

					data.append('body', this.comment);

					this.$http.post("/posts/"+this.post.id+"/reply", data).then(response => {
						this.commenting = false;
						this.post.replies.push(response.body);
						var replyId = response.body.id;
						this.comment = null;
						this.showComments();

						Vue.nextTick(function() {
							$('html, body').animate({
			                    scrollTop: ($('#reply'+replyId).offset().top - 300) + 'px'
			                }, 2000);
						});

					}, (response) => {
						this.commenting = false;
						swal({type: 'error', title: 'Whoops...', text: response.body.error, showCloseButton: true, showConfirmButton: false});
					});

				}
			},

			/*
			 * Method for showing the post comments
			 */
			showComments() {
				this.shouldShowComments = true;
				this.shouldShowCommentForm = true;
				autosize(document.querySelectorAll('textarea'));
			},

			/*
			 * Method for showing the comment form 
			 */
			showCommentForm() {
				this.shouldShowCommentForm = true;
				autosize(document.querySelectorAll('textarea'));
			},

			/*
			 * This sets the z-index of the post-dropdown so it doesnt show on lightbox
			 */
			fixDropdown() {
				$(".post-dropdown").css("z-index","-2000");
			}
		},

		created() {
			eventHub.$on("showCommentForm"+this.post.id, this.showCommentForm);
		},

		mounted() {
			/*var id = this.post.id
			videojs("vjs"+id, 
                { 
                autoplay: false, 
                preload: "auto", 
                controls: true}, 
                function() {
                //
            });

            this.videoInitalized = true;*/
		},

		/*updated() {
			videojs("vjs"+this.post.id, 
                        {width: 450, 
                        autoplay: false, 
                        preload: "auto", 
                        controls: true}, 
                        function() {
                        //
                });
		}*/
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

	.comments:hover {
		text-decoration: underline;
	}
</style>