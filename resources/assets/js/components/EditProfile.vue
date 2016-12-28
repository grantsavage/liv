<template>
	<div class="container">
		<div :class="{hidden: searching}" class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-default">
					<div class="panel-heading">Update Profile</div>
					<div class="panel-body">
						<img class="img-rounded" :src="avatar" alt="" width="128" height="128">
						<label for="image" class="btn btn-primary" style="display: inline-block;">Upload Profile Picture</label>
						<input id="image" name="image" type="file" class="hidden">
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
									<button :class="{disabled: posting}" id="submitButton" type="submit" class="btn btn-primary">{{ this.buttonText }}<div :class="{hidden: !this.posting}" class="button-loader" style="display: inline-block;"></div></button>
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
				avatar: this.user.avatar,
				buttonText: "Save",
				posting: false,
				searching: false,
				_user: {}
			}
		},

		props: [
			'user'
		],

		methods: {
			/*
			 * Make request to server to update settings
			 */
			update(){
				// Set up UI
				this.posting = true;
				this.buttonText = "Saving...";

				// Get data
				var files = $("#image")[0].files;
				var data = new FormData();

				// Add the data to the request
				data.append('name',this.name);
				data.append('location',this.location);
				data.append('bio',this.bio);
				data.append('image',files[0]);

				// Make the request
				this.$http.post('/profile/edit',data).then((response) => {

					if (response.status == 200) {
						// Alert the user
						swal({title:"Profile Updated", text:"Your profile has been updated successfully.", type:"success",timer: 2000,showCloseButton: false,showConfirmButton: false});

						$("#nav-name").text(this.name);

					// Handle error
					} else {
						swal({title: "Whoops...", text: "There was a problem updating your profile. Try again later...", type: "error"});
					}

					// Reset UI
					this.posting = false;
					this.buttonText = "Save";

					// Get and set new user info
					this._user = response.body;
					this.name = this._user.name;
					this.location = this._user.location;
					this.bio = this._user.bio;
					this.avatar = this._user.avatar;

				// Handle response error
				}, (response) => {
					// Alert user
					swal({title: "Whoops...", text: "There was a problem updating your profile. Try again later...", type: "error"});

					// Reset UI
					this.posting = false;
					this.buttonText = "Save";
				});

				$("#submitButton").blur();
			},

			/*
			 * Hide and show view when performing search
			 */
			hide() {
				this.searching = true;
			},
			show() {
				this.searching = false;
			}

		},

		/*
		 * Executed when view is mounted
		 */
		mounted(){
			// Set user data
			this._user = this.user;

			// Set up event listeners
			eventHub.$on("search",this.hide);
			eventHub.$on("not-searching",this.show);
		}
	}
</script>