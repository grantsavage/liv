<template>
	<div class="container">
		<div :class="{hidden: searching}" class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="panel panel-default">
					<div class="panel-heading">
						<a 
						:href="this.user.avatar" 
						data-lity>
							<img 
							class="img-rounded" 
							:src="this.user.avatar" 
							alt="" 
							width="128" 
							height="128">
						</a>
						<h2 style=" display: inline-block;">
							{{this.user.name}}
						</h2>
						<div 
						@click="addFriend" 
						:class="{'hidden': isFriend || this.user.id == $root.user.id, 'btn-primary': !requestSent, 'btn-success': requestSent , 'disabled': requestSent}" 
						class="btn btn-primary" 
						id="requestButton" 
						style="display: inline-block;margin-left: 20px;">
						<span 
						:class="{hidden: requestSent}" 
						class="glyphicon glyphicon-plus">		
						</span>
						<span 
						:class="{hidden: !requestSent}" 
						class="glyphicon glyphicon-ok">
						</span>
							&nbsp;{{this.buttonText}}&nbsp;
						<div 
						class="button-loader hidden" 
						style="display: inline-block;"></div>
						</div>
						<div 
						@click="deleteFriend" 
						class="btn btn-danger" 
						style="display: inline-block;margin-left: 20px;" 
						:class="{hidden: !isFriend}">
						<span 
						class="glyphicon glyphicon-remove"></span>
							&nbsp;Delete Friend&nbsp;
						<div 
						id="rl" 
						class="button-loader hidden" 
						style="display: inline-block;"></div>
						</div>
						<a 
						v-if="this.user.id == $root.user.id" 
						href="/profile/edit" 
						style="margin: 20px; display: inline-block;" 
						class="btn btn-primary pull-right">
							Edit Profile
						</a>
						<br>
						<br>
						<p 
						class="lead" 
						style="display: inline-block;">
							{{this.user.location}}
						</p>
						<p>
							{{this.user.bio}}
						</p>
					</div>
					<div class="panel-body">
						<post v-for="post in this.user.posts" :post="post"></post>
						<p v-if="this.user.posts <= 0">{{this.user.name}} hasn't posted anything yet!</p>
					</div>	
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import Post from './Post.vue';
	import eventHub from '../event.js';
	export default {
		data() {
			return {
				searching: false,
				isFriend: false,
				requestSent: false,
				buttonText: "Add Friend"
			}
		},

		props: ['user','friends','requests'],

		components: [
			Post
		],

		methods: {
			/*
			 * Hide and show the view when searching
			 */
			hide(){
				this.searching = true;
			},

			show(){
				this.searching = false;
			},

			/*
			 * Check to see if the current user is friends with the profile user
			 */
			checkFriend(){
				// Check if friend
				for (var i = 0; i < this.friends.length; i++) {
					if(this.friends[i].id == this.user.id) {
						this.isFriend = true;
					}
				}

				// Check if request is pending
				for (var i = 0; i < this.requests.length; i++) {
					if(this.requests[i].id == this.user.id) {
						this.requestSent = true;
						this.buttonText = "Request Pending";
					}
				}
			},

			/*
			 * Request to add profile user as friend
			 */
			addFriend(){

				// Check if request has not been sent
				if (this.requestSent == false) {

					// Set up UI
					$(".button-loader").removeClass("hidden");
					$("#requestButton").addClass("disabled");

					// Create request
					this.$http.get('/friends/add/'+this.user.username).then((response) => {

						// Check for errors
						if (response.body.error) {

							// Alert user
							swal({title:"Uh oh...", text: response.body.error, type:"error",showConfirmButton: true});

							// Reset UI
							$(".button-loader").addClass("hidden");
							$("#requestButton").removeClass("disabled");

						// If there are no error
						} else {

							// Alert user
							swal({title:"Friend Added", text:"Friend request successfully sent to " + this.user.name, type:"success",timer: 2000,showCloseButton: false,showConfirmButton: false});

							this.requestSent = true;

							// Reset UI
							this.buttonText = "Request Sent";
							$(".button-loader").addClass("hidden");
							$("#requestButton").removeClass("disabled");
						}

					// Handle response error
					}).then((response) => {

						// Check for response
						if (response) {
							// Alert user
							swal({title:"Whoops...", text:"There was a issue sending the friend request",showConfirmButton: true});
						}
					});
				}
			},

			/*
			 * Request to delete profile user from friends list
			 */
			deleteFriend(){
				// UI
				$("#rl").removeClass("hidden");

				// Make request
				this.$http.post("/friends/delete/"+this.user.username).then((response)=> {

					// Check for errors
					if (response.body.error) {

						// Alert user
						swal({title:"Uh oh...", text: response.body.error, type:"error",showConfirmButton: true});
						$("#rl").addClass("hidden");

					// If there are no errors
					} else {

						// Alert user
						swal({title:"Friend Removed", text:"Successfully removed " + this.user.name + " from your friends.", type:"success",timer: 3000,showCloseButton: false,showConfirmButton: false});

						this.isFriend = false;

						// Reset UI
						$("#rl").addClass("hidden");
					}

				// Handle response error
				}).then((response) => {

					// Check for response
					if (response) {
						// ALert user
						swal({title:"Whoops...", text:"There was a issue deleting this friend,",showConfirmButton: true});
					}
				});
			}
		},
		
		/*
		 * Executed when view is mounted
		 */
		mounted(){
			// Set up event listeners
			eventHub.$on('search',this.hide);
            eventHub.$on('not-searching', this.show);

            // Check if friend
            this.checkFriend();
		}
	}
</script>