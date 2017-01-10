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
			v-bind:class="{disabled: this.body == '' || this.body == null || this.posting}" 
			id="submitButton" 
			type="submit" 
			class="btn btn-primary">{{this.button_text}}
			<div 
				class="button-loader"
				:class="{hidden: !this.posting}" 
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
		<label 
			for="videoUpload" 
			class="btn btn-default">
			<span class="glyphicon glyphicon-facetime-video"></span>
			Video
		</label>
		<input 
			ref="video" 
			type="file" 
			class="hidden" 
			id="videoUpload" 
			name="video"
			accept="video/*">
		<img 
			v-show="postHasImage"
			id="img" src="#" 
			class="img-thumbnail" 
			style="max-width: 200px;">

		<video 
			id="videoPreview" 
			autoplay 
			loop 
			width="550" 
			muted
			style="margin-top: 20px; margin-bottom: 10px;"
			:class="{hidden : !showVideoPreview || this.posting}"></video>

		<p 
			id="help" 
			class="text-danger hidden">
				Your post must have text in it to post
		</p>
		<div 
			class="progress"
			:class="{'hidden' : !posting}"
			style="margin-top: 20px;">
	        <div 
	        	id="progressbar" 
	        	class="progress-bar"
	        	:class="{'progress-bar-success' : success, 'progress-bar-danger' : failed}"
	        	role="progressbar" 
	        	:aria-valuenow="progress" 
	        	aria-valuemin="0" 
	        	aria-valuemax="100" 
	        	v-bind:style="{'width' : progress + '%'}">
	            	<span class="sr-only">
	            		{{progress}}% Complete
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
				progress: 0,
				showVideoPreview: false,
				showImagePreview: false,
				posting: false,
				failed: false,
				success: false
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
					
					this.posting = true;	
					this.showVideoPreview = false;

					// Get data
					var picture = $("#pictureUpload")[0].files;
					var video = $("#videoUpload")[0].files;
					var data = new FormData();

					// Set data to request
					data.append('body', this.body);

					if (picture[0]) {
						data.append('media', picture[0]);
					} else if (video[0]) {
						data.append('media', video[0]);
					}

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
							// Reset form body to empty
							this.body = null;
							
							this.button_text = "Post it!";
							
							this.postHasImage = false;

							this.success = true

							this.posting = false
							/*setTimeout(function() {
								v.posting = false;
								eventHub.$emit('post-added', response.body);
							}, 700);*/

							$("#videoUpload")[0].files = null;
							$("#pictureUpload")[0].files = null;
							document.getElementById("img").src = "";
							eventHub.$emit('post-added', response.body);
						// Handle response error
						} else {
							// Alert user
							swal({type: 'error',title: 'Uh oh...',text: "A problem occured while posting. The image you submitted may be too large. Try compressing the image and re-upload."});
							this.failed = true
							$("#videoUpload")[0].files = null;
						}

					// Handle response error
					}, (response) => {
						$("#videoUpload")[0].files = null;
						this.posting = false
						// Check for response
						if (response) {
							// Rest everything
							this.body = null;
							this.button_text = "Post it!";
							this.postHasImage = false;
							this.failed = true

							// Alert user
							swal({type: 'error',title: 'Uh oh...',text: "A problem occured while posting. The media you submitted may be too large. Try compressing the media and re-upload."});
						}
					});
				}				
			}
		},

		/*
		 * Executed when view is mounted
		 */
		mounted() {
			  var URL = window.URL || window.webkitURL
			  var vm = this
			  var playSelectedFile = function (event) {
			  	if (this.files[0]) {
			  		this.files[0] = null
			  	}
			    var file = this.files[0]
			    var type = file.type
			    var videoNode = document.getElementById('videoPreview')
			    var canPlay = videoNode.canPlayType(type)
			    if (canPlay === '') canPlay = 'no'
			    var message = 'Can play type "' + type + '": ' + canPlay
			    var isError = canPlay === 'no'

			    if (isError) {
			      return
			    }

			    var fileURL = URL.createObjectURL(file)
			    videoNode.src = fileURL

			    vm.showVideoPreview = true
			  }
			  var inputNode = document.getElementById('videoUpload')
			  inputNode.addEventListener('change', playSelectedFile, false)

			  function readURL(input) {

			    if (input.files && input.files[0]) {
			        var reader = new FileReader();

			        reader.onload = function (e) {
			            $('#img').attr('src', e.target.result);
			            vm.postHasImage = true
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
</style>