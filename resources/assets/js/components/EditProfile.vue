<template>
	<div class="container">
		<div :class="{hidden: searching}" class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-default">
					<div class="panel-heading">Update Profile</div>
					<div class="panel-body">
						<img class="img-rounded" :src="this.user.avatar" alt="" width="128" height="128">
						<label for="file" class="btn btn-primary">Upload Profile Picture</label>
						<input id="file" name="file" type="file" class="hidden">
						<hr>
						<form action="#" class="form-horizontal" @submit.prevent="update">
							<div class="form-group">	
								<div class="col-sm-8">
									<label for="name" class="control-label">Name</label>
									<input type="text" class="form-control" name="name" id="name" placeholder="John Doe"  v-model="name">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-8">
									<label for="location" class="control-label">Location</label>
									<input type="text" class="form-control" name="location" id="location" placeholder="Los Angeles, California" v-model="location">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-8">
									<label for="bio" class="control-label">Bio</label>
									<input type="text" class="form-control" name="bio" id="bio" placeholder="I like to..." v-model="bio">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-8">
									<button :class="{disabled: posting}" id="submitButton" type="submit" class="btn btn-primary">{{ this.buttonText }}<div :class="{hidden: !this.posting}" class="button-loader" style="display: inline-block;"></div> </button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import eventHub from "../event.js"
	export default {
		data(){
			return {
				name: this.user.name,
				location: this.user.location,
				bio: this.user.bio,
				buttonText: "Save",
				posting: false,
				searching: false
			}
		},
		props: [
			'user'
		],
		methods: {
			update(){
				this.posting = true;
				this.buttonText = "Saving...";
				this.$http.post('/profile/edit', {
					name: this.name,
					location: this.location,
					bio: this.bio
				},{timeout: 5000}).then((response) => {
					if (response.status == 200) {
						swal({title:"Profile Updated", text:"Your profile has been updated successfully.", type:"success",timer: 2000,showCloseButton: false,showConfirmButton: false});
						$("#nav-name").text(this.name);
					} else {
						swal({title: "Whoops...", text: "There was a problem updating your profile. Try again later...", type: "error"});
					}
					this.posting = false;
					this.buttonText = "Save";
				}, (response) => {
					swal({title: "Whoops...", text: "There was a problem updating your profile. Try again later...", type: "error"});
					this.posting = false;
					this.buttonText = "Save";
				});
				$("#submitButton").blur();
			},
			uploadProfilePhoto() {
				
			},
			hide() {
				this.searching = true;
			},
			show() {
				this.searching = false;
			}
		},
		mounted(){
			eventHub.$on("search",this.hide);
			eventHub.$on("not-searching",this.show);
		}
	}
</script>