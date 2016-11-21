<template>
	<form action="#" class="form-vertical" @submit.prevent="post">
		<div class="form-group">
			<textarea class="form-control" cols="30" rows="3" placeholder="What's going on?" v-model="body"></textarea>
		</div>
		<button v-bind:class="{disabled: this.body == '' || this.body == null}" id="submitButton" type="submit" class="btn btn-primary">Post it!</button>
		<p id="help" class="text-danger hidden">Your post must have text in it to post</p>
		<div class="progress hidden">
	        <div id="progressbar" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
	            <span class="sr-only">0% Complete</span>
	        </div>  
	    </div>
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
				$("#submitButton").blur();
				if (this.body == null || this.body == "") {
					$("#help").removeClass("hidden");
				} else {
					// Change UI to loading state
					$("#help").addClass("hidden");
					$("#submitButton").text("Posting...").addClass("disabled").blur();
					$(".progress").removeClass("hidden");
					$("#progressbar").animate({
					    width: "50%"
					}, 100);
					// Post the request
					this.$http.post('/posts', {
						body: this.body
					}).then((response) => {
						// emit event
						eventHub.$emit('post-added', response.body);

						this.body = null;
						// Animate progress
						$("#progressbar").animate({width: "100%"}, 100).addClass("progress-bar-success");
						$("#submitButton").removeClass("disabled").text("Post").blur();

						setTimeout(function(){
							// Fade out bar
							$(".progress").addClass("animated fadeOut");
							// Reset Progress
							setTimeout(function() {
								$(".progress").addClass("hidden");
								$("#progressbar").css("width","0%").removeClass("progress-bar-success");
								$(".progress").removeClass("animated fadeOut");
							},1000);
						},300);
					});
				}				
			}
		}
	}
</script>

<style scoped>
	textarea {
		resize: none;
	}

	.progress.active .progress-bar {
	    -webkit-transition: none !important;
	    transition: none !important;
	}
</style>