<template>
	<form action="#" class="form-vertical" @submit.prevent="post">
		<div class="form-group">
			<textarea 
				class="form-control" 
				cols="30" 
				rows="1" 
				placeholder="What's going on?" 
				v-model="body"
				style="border: none; box-shadow: none;">
			</textarea>
		</div>
		<button 
			v-bind:class="{disabled: this.body == '' || this.body == null}" 
			id="submitButton" 
			type="submit" 
			class="btn btn-primary">{{this.button_text}}
			<div 
				class="button-loader hidden" 
				style="display: inline-block;">
			</div>
		</button>
		<label 
			for="pictureUpload" 
			class="btn btn-default">
			<span class="glyphicon glyphicon-picture"></span>
			Photo
		</label>
		<input 
			ref="image" 
			type="file" 
			class="hidden" 
			id="pictureUpload" 
			name="image">
		<img 
			:class="{hidden: !postHasImage}" 
			id="img" src="#" 
			class="img-thumbnail" 
			style="max-width: 200px;">
		<p 
			id="help" 
			class="text-danger hidden">
				Your post must have text in it to post
		</p>
		<div class="progress hidden">
	        <div 
	        	id="progressbar" 
	        	class="progress-bar" 
	        	role="progressbar" 
	        	aria-valuenow="0" 
	        	aria-valuemin="0" 
	        	aria-valuemax="100" 
	        	v-bind:style="{'width' : progress + '%'}">
	            	<span class="sr-only">
	            		0% Complete
	            	</span>
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
			/*
			 * Send new post to the server
			 */
			post() {
				$("#submitButton").blur();

				// Check if body is empty
				if (this.body == null || this.body == "") {

					// Show help text
					$("#help").removeClass("hidden").addClass("animated flash");

				// If body is not empty
				} else {

					$('.progress').removeClass('hidden');
					// Change UI to loading state
					$(".button-loader").removeClass("hidden");
					$("#help").addClass("hidden");
					$("#submitButton").addClass("disabled").blur();
					this.button_text = "Posting   ";
						
					// Get data
					var files = $("#pictureUpload")[0].files;
					var data = new FormData();

					// Set data to request
					data.append('body', this.body);
					data.append('image', files[0]);
					
					// Post the request
					this.$http.post('/posts', data, {

						// Progress event callback
						progress: (e) => {
							if (!e.lengthComputable) {
								return
							}
							// Update progress
							this.progress = Math.ceil((e.loaded / e.total) * 100);
						}

					// Handle Response
					}).then((response) => {
						
						if (response.status == 200) {
							// Emit post added event
							eventHub.$emit('post-added', response.body);

							// Reset form body to empty
							this.body = null;
							
							// Animate progress
							$('#progressbar').addClass('progress-bar-success');

							// Reset Button UI
							$("#submitButton").removeClass("disabled").blur();
							this.button_text = "Post it!";
							$(".button-loader").addClass("hidden");
							
							this.postHasImage = false;

							// Delay the hiding of the progress bar
							setTimeout(function() {
								$('#progressbar').removeClass('progress-bar-success');
								$('.progress').addClass('hidden');
								$("#pictureUpload")[0].files = null;
								$("#img").addClass("hidden");
								this.progress = 0
							},500);

						// Handle response error
						} else {
							// Alert user
							swal({type: 'error',title: 'Uh oh...',text: "A problem occured while posting. The image you submitted may be too large. Try compressing the image and re-upload."});

							// Reset Progress Bar
							$('#progressbar').removeClass('progress-bar-success');
							$('.progress').addClass('hidden');
							$("#pictureUpload")[0].files = null;
							$("#img").addClass("hidden");
						}

					// Handle response error
					}, (response) => {
						// Check for response
						if (response) {
							// Rest everything
							$("#pictureUpload")[0].files[0] = null;
							$("#submitButton").removeClass("disabled").blur();
							$(".button-loader").addClass("hidden");
							$("#img").addClass("hidden");
							this.body = null;
							this.button_text = "Post it!";
							this.postHasImage = false;

							// Alert user
							swal({type: 'error',title: 'Uh oh...',text: "A problem occured while posting. The image you submitted may be too large. Try compressing the image and re-upload."});
						}
					});
				}				
			}
		},

		/*
		 * Executed when view is mounted
		 */
		mounted(){
			// Function for image preview
			function readURL(input) {

			    if (input.files && input.files[0]) {
			        var reader = new FileReader();

			        reader.onload = function (e) {
			            $('#img').attr('src', e.target.result).removeClass("hidden");
			            this.postHasImage = true
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