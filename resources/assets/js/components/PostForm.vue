<template>
	<form action="#" class="form-vertical" @submit.prevent="post">
		<div class="form-group">
			<textarea class="form-control" cols="30" rows="3" placeholder="What's going on?" v-model="body"></textarea>
		</div>
		<button v-bind:class="{disabled: this.body == '' || this.body == null}" id="submitButton" type="submit" class="btn btn-primary">{{this.button_text}}<div class="button-loader hidden" style="display: inline-block;"></div></button>
		<label for="pictureUpload" class="btn btn-default"><span class="glyphicon glyphicon-picture"></span></label>
		<input ref="image" type="file" class="hidden" id="pictureUpload" name="image">
		<img :class="{hidden: !postHasImage}" id="img" src="#" class="img-thumbnail" style="max-width: 200px;">
		<p id="help" class="text-danger hidden">Your post must have text in it to post</p>
		<div class="progress hidden">
	        <div id="progressbar" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" v-bind:style="{'width' : progress + '%'}">
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
				progress: 0
			}
		},
		methods: {
			post() {
				$("#submitButton").blur();
				if (this.body == null || this.body == "") {
					$("#help").removeClass("hidden").addClass("animated flash");
				} else {
					// Change UI to loading state
					$('.progress').removeClass('hidden');
					$(".button-loader").removeClass("hidden");
					$("#help").addClass("hidden");
					$("#submitButton").addClass("disabled").blur();
					this.button_text = "Posting   ";
					
					var files = $("#pictureUpload")[0].files;
					var data = new FormData();

					data.append('body', this.body);
					data.append('image', files[0]);
					
					// Post the request
					this.$http.post('/posts', data, {
						progress: (e) => {
							if (!e.lengthComputable) {
								return
							}
							this.progress = Math.ceil((e.loaded / e.total) * 100);
						}
					}).then((response) => {
						if (response.status == 200) {
							eventHub.$emit('post-added', response.body);

							this.body = null;
							
							// Animate progress
							$('#progressbar').addClass('progress-bar-success');
							$("#submitButton").removeClass("disabled").blur();
							this.button_text = "Post it!";
							$(".button-loader").addClass("hidden");
							
							this.postHasImage = false;

							setTimeout(function() {
								$('#progressbar').removeClass('progress-bar-success');
								$('.progress').addClass('hidden');
								$("#pictureUpload")[0].files = null;
								$("#img").addClass("hidden");
							},2000);

							
						} else {
							swal({type: 'error',title: 'Uh oh...',text: "A problem occured while posting. The image you submitted may be too large. Try compressing the image and re-upload."});
						}
					}, (response) => {
						if (response) {
							$("#pictureUpload")[0].files[0] = null;
							this.body = null;
							
							$("#submitButton").removeClass("disabled").blur();
							this.button_text = "Post it!";
							$(".button-loader").addClass("hidden");
							
							this.postHasImage = false;
							$("#img").addClass("hidden");
							swal({type: 'error',title: 'Uh oh...',text: "A problem occured while posting. The image you submitted may be too large. Try compressing the image and re-upload."});
						}
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

<!--</color> <navyblue> 

<georgina is cool>

<I love Grant> <3 -->