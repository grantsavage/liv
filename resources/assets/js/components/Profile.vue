<template>
	<div class="container">
		<div :class="{hidden: searching}" class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="panel panel-default">
					<div class="panel-heading">
						<img class="img-rounded" :src="this.user.avatar" alt="" width="128" height="128">
						<h2 style=" display: inline-block;">{{this.user.name}}</h2>
						<a v-if="this.user.id == $root.user.id" href="/profile/edit" style="margin: 20px; display: inline-block;" class="btn btn-primary pull-right">Edit Profile</a>
						<br>
						<br>
						<p class="lead" style="display: inline-block;">{{this.user.location}}</p>
						<div @click="addFriend" :class="{'hidden': isFriend || this.user.id == $root.user.id, 'btn-primary': !requestSent, 'btn-success': requestSent, 'disabled': requestSent}" class="btn btn-primary" style="display: inline-block;margin-left: 20px;"><span :class="{hidden: requestSent}" class="glyphicon glyphicon-plus"></span>  {{this.buttonText}}</div>
						<div @click="deleteFriend" class="btn btn-danger" style="display: inline-block;margin-left: 20px;" :class="{hidden: !isFriend}">Delete Friend</div>
						<h4>Bio</h4>
						<p class="">{{this.user.bio}}</p>
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
			hide(){
				this.searching = true;
			},
			show(){
				this.searching = false;
			},
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
			addFriend(){
				if (this.requestSent == false) {
					this.$http.get('/friends/add/'+this.user.username).then((response) => {
						if (response.body.error) {
							swal({title:"Uh oh...", text: response.body.error, type:"error",showConfirmButton: true});
						} else {
							swal({title:"Friend Added", text:"Friend request successfully sent to " + this.user.name, type:"success",timer: 2000,showCloseButton: false,showConfirmButton: false});
							this.requestSent = true;
							this.buttonText = "Request Sent";
						}
					}).then((response) => {
						if (response) {
							swal({title:"Whoops...", text:"There was a issue sending the friend request",showConfirmButton: true});
						}
					});
				}
			},
			deleteFriend(){
				this.$http.post("/friends/delete/"+this.user.username).then((response)=> {
					if (response.body.error) {
						swal({title:"Uh oh...", text: response.body.error, type:"error",showConfirmButton: true});
					} else {
						swal({title:"Friend Removed", text:"Successfully removed " + this.user.name + " from your friends.", type:"success",timer: 3000,showCloseButton: false,showConfirmButton: false});
						this.isFriend = false;
					}
				}).then((response) => {
					if (response) {
						swal({title:"Whoops...", text:"There was a issue deleting this friend,",showConfirmButton: true});
					}
				});
			}
		},
		mounted(){
			eventHub.$on('search',this.hide);
            eventHub.$on('not-searching', this.show);
            this.checkFriend();
		}
	}
</script>

<style scoped>
	
</style>