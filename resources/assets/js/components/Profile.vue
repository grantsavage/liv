<template>
	<div class="container">
		<div :class="{hidden: searching}" class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="panel panel-default">
					<div class="panel-heading">
						<a 
						:href="'/user/'+this.user.username" 
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
						:class="{'hidden' : awaitingAccept || isFriend || this.user.id == $root.user.id, 'btn-primary' : !isFriend, 'btn-success' : friendRequestSent, 'disabled' : friendRequestSent || sendingFriendRequest}" 
						class="btn" 
						id="requestButton" 
						style="display: inline-block;margin-left: 20px;">
						<span 
						:class="{'hidden' : isFriend || friendRequestSent || sendingFriendRequest}" 
						class="glyphicon glyphicon-plus">		
						</span>
						<span 
						:class="{'hidden' : !friendRequestSent || sendingFriendRequest}" 
						class="glyphicon glyphicon-ok">
						</span>
							&nbsp;{{this.buttonText}}&nbsp; 
						<div 
						class="button-loader" 
						:class="{'hidden' : !sendingFriendRequest}"
						style="display: inline-block;"></div>
						</div>


						<div 
						@click="deleteFriend" 
						class="btn btn-danger" 
						style="display: inline-block;margin-left: 20px;" 
						:class="{'hidden' : awaitingAccept || friendRequestSent || !isFriend || this.user.id == $root.user.id, 'disabled' : deletingFriend}">
						<span 
						class="glyphicon glyphicon-remove"></span>
							&nbsp;{{this.buttonText}}&nbsp; 
						<div 
						id="rl" 
						class="button-loader" 
						:class="{'hidden' : !deletingFriend}"
						style="display: inline-block;"></div>
						</div>


						<div 
						@click="acceptRequest" 
						:class="{'hidden' : !awaitingAccept || this.user.id == $root.user.id, 'disabled' : accepting || friendAdded}" 
						class="btn btn-success" 
						id="acceptButton" 
						style="display: inline-block;margin-left: 20px;">
						<span 
						:class="" 
						class="glyphicon glyphicon-ok">		
						</span>
							&nbsp;{{this.buttonText}}&nbsp; 
						<div 
						class="button-loader" 
						:class="{'hidden' : !accepting}"
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
				sendingFriendRequest: false,
				friendRequestSent: false,

				awaitingAccept: false,
				accepting: false,
				friendAdded: false,

				deletingFriend: false,
				friendDeleted: false,

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
						this.buttonText = "Delete Friend"
					}
				}

				// Check if request is pending
				for (var i = 0; i < this.requests.length; i++) {

					// If the user is a request
					if(this.requests[i].id == this.user.id) {
						this.friendRequestSent = true;
						this.buttonText = "Request Pending"
					}

					if (this.requests[i].pivot.user_id != this.user.id) {
						this.awaitingAccept = true;
						this.buttonText = "Accept Request";
					}
				}
			},

			/*
			 * Request to add profile user as friend
			 */
			addFriend(){

				// Check if request has not been sent
				if (this.isFriend == false) {

					// Set up UI
					this.sendingFriendRequest = true;
					this.buttonText = "Sending Request..."
					// Create request
					this.$http.get('/friends/add/'+this.user.username).then((response) => {

						// Check for errors
						if (response.body.error) {

							// Alert user
							swal({title:"Uh oh...", text: response.body.error, type:"error",showConfirmButton: true});

							this.sendingFriendRequest = false;

							// Reset UI
							$(".button-loader").addClass("hidden");
							$("#requestButton").removeClass("disabled");

						// If there are no error
						} else {

							// Alert user
							swal({title:"Friend Added", text:"Friend request successfully sent to " + this.user.name, type:"success",timer: 2000,showCloseButton: false,showConfirmButton: false});

							this.sendingFriendRequest = false;
							this.friendRequestSent = true;
							// Reset UI
							this.buttonText = "Request Sent";
							$(".button-loader").addClass("hidden");
							$("#requestButton").removeClass("disabled");
						}

					// Handle response error
					},(response) => {
						this.sendingFriendRequest = false;
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
				this.deletingFriend = true
				this.buttonText = "Removing Friend..."

				// Make request
				this.$http.post("/friends/delete/"+this.user.username).then((response)=> {

					// Check for errors
					if (response.body.error) {

						// Alert user
						swal({title:"Uh oh...", text: response.body.error, type:"error",showConfirmButton: true});
						$("#rl").addClass("hidden");
						this.deletingFriend = false
					// If there are no errors
					} else {

						// Alert user
						swal({title:"Friend Removed", text:"Successfully removed " + this.user.name + " from your friends.", type:"success",timer: 3000,showCloseButton: false,showConfirmButton: false});

						this.isFriend = false;
						this.deletingFriend = false
						// Reset UI
						$("#rl").addClass("hidden");
						this.buttonText = "Add Friend"
					}

				// Handle response error
				},(response) => {

					// Check for response
					if (response) {
						// ALert user
						swal({title:"Whoops...", text:"There was a issue deleting this friend,",showConfirmButton: true});
					}
				});
			},

			acceptRequest() {
				if (this.awaitingAccept == true) {

					this.accepting = true;

					this.buttonText = "Accepting..."

					this.$http.get('/friends/accept/'+this.user.username).then(response => {
						this.accepting = false;
						this.buttonText = "Request Accepted";
						this.awaitingResponse = false
						this.friendAdded = true
						swal({type: 'success', title: 'Success!', text: 'Successfully accepted ' + this.user.name + '\'s request.' , timer: 3000, showCloseButton: false, showConfirmButton: false});
					}, (response) => {
						this.accepting = false
						if (response.error) {
							swal({type: 'error', title: 'Whoops...', text: response.error, showCloseButton: true, showConfirmButton: false});
						}
						swal({type: 'error', title: 'Whoops...', text: 'There was a problem accepting the friend request.', showCloseButton: true, showConfirmButton: false});
					});
				}
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