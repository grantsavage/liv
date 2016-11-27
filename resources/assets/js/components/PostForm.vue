<template>
	<form action="#" class="form-vertical" @submit.prevent="post">
		<div class="form-group">
			<textarea class="form-control" cols="30" rows="3" placeholder="What's going on?" v-model="body"></textarea>
		</div>
		<button v-bind:class="{disabled: this.body == '' || this.body == null}" id="submitButton" type="submit" class="btn btn-primary">{{this.button_text}}<div class="button-loader hidden" style="display: inline-block;"></div></button>
		<label for="pictureUpload" class="btn btn-default"><span class="glyphicon glyphicon-picture"></span></label>
		<input ref="image" type="file" class="hidden" id="pictureUpload">
		<img :class="{hidden: !postHasImage}" id="img" src="#" class="img-thumbnail" style="max-width: 200px;">
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
				body: null,
				button_text: "Post it!",
				postHasImage: false,
			}
		},
		methods: {
			post() {
				$("#submitButton").blur();
				if (this.body == null || this.body == "") {
					$("#help").removeClass("hidden").addClass("animated flash");
				} else {
					// Change UI to loading state
					$(".button-loader").removeClass("hidden");
					$("#help").addClass("hidden");
					$("#submitButton").addClass("disabled").blur();
					this.button_text = "Posting   ";
					$(".progress").removeClass("hidden");
					$("#progressbar").animate({
					    width: "50%"
					}, 100);
					var files = $("#pictureUpload")[0].files;
					var data = new FormData();

					data.append('body', this.body);
					data.append('image', files[0]);

					// Post the request
					this.$http.post('/posts', data).then((response) => {
						// emit event
						eventHub.$emit('post-added', response.body);

						this.body = null;
						
						// Animate progress
						$("#progressbar").animate({width: "100%"}, 100).addClass("progress-bar-success");
						$("#submitButton").removeClass("disabled").blur();
						this.button_text = "Post it!";
						$(".button-loader").addClass("hidden");
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
		},
		mounted(){
			function readURL(input) {

			    if (input.files && input.files[0]) {
			        var reader = new FileReader();

			        reader.onload = function (e) {
			            $('#img').attr('src', e.target.result).removeClass("hidden");
			        }

			        reader.readAsDataURL(input.files[0]);
			    } else {
			    	$("#img").addClass("hidden");
			    }
			}
			$("#pictureUpload").change(function(){
			    readURL(this);
			});
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