<template>
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="panel panel-default">
					<div class="panel-heading">
						Upload
					</div>
					<div class="panel-body">
						<div class="form-group">
							<input type="file" id="video" name="video" @change="upload">
						</div>
						<div class="progress">
							<div class="progress-bar" v-bind:style="{width: progress + '%'}"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				progress: 0
			}
		},

		methods: {
			upload() {
				var form = new FormData();
				this.file = document.getElementById('video').files[0];

				form.append('video', this.file, 'video');
				//console.log(form.getAll());
				this.$http.post('/upload', form, {

					progress: (e) => {
						this.progress = (e.loaded / e.total) * 100
					}

				}).then((response) => {
					console.log("done")
				}, (response) => {

				});
			}
		},

		mounted() {
	
		}
	}
	
</script>