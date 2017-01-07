<template>
<ul class="nav navbar-nav navbar-right">
    <li> 
        <a :href="'/user/' + this.user.username">
        <img class="img-rounded" :src="this.user.avatar" alt="" width="25" height="25">
        <span id="nav-name">{{ this.user.name }}</span></a>
    </li>
    <li><a href="/home">Home</a></li>
    
    <li class="dropdown" id="req">
        <a id="requests" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
            <span class="glyphicon glyphicon-user"></span>
            <span v-if="req_count > 0" id="req-badge" class="badge" style="margin-bottom: 4px; background-color: #d9534f; padding-top: 1px;">{{this.req_count}}</span>
        </a>
        
         <ul class="dropdown-menu requests" role="menu" aria-labelledby="requests">
            <li v-if="this.reqs.length <= 0" class="dropdown-header text-center">No Friend Requests!</li>
            <li v-else class="dropdown-header text-center">Friend Requests</li>
            <li v-for="request in reqs">
                <a>
                    <img :src="request.avatar" alt="" width="35" height="35"> 
                    {{request.name}}&nbsp;&nbsp;
                    <span :key="request.id">
                    <div @click="acceptRequest(request)" class="btn btn-primary" style="display: inline;">Accept</div>
                    <div @click="deleteRequest(request)" class="btn btn-default" style="display: inline;">Delete</div>
                    </span>
                </a>
            </li>

        </ul>
    </li>

    <li>
        <a href="#">
            <span class="glyphicon glyphicon-comment"></span>
        </a>
    </li>

    <li class="dropdown" id="noti">
        <a id="notifications" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
            <span class="glyphicon glyphicon-globe"></span>
            <span v-if="noti_count > 0" id="noti-badge" class="badge" style="margin-bottom: 4px; background-color: #d9534f; padding-top: 1px;">{{this.noti_count}}</span>
        </a>
        
         <ul class="dropdown-menu notifications" role="menu" aria-labelledby="notifications">
            <li v-if="notifications.length <= 0" class="dropdown-header text-center">No Notifications!</li>
            <li v-else class="dropdown-header text-center">Notifications</li>
            <li v-for="notification in notifications">
                <a :href="'/post/' + notification.data.post.id"><img :src="notification.data.user.avatar" alt="" width="35" height="35"> <a :href="'/user/' + notification.data.user.username"><strong>{{notification.data.user.name}}</strong></a> liked your post.</a>
            </li>

        </ul>
    </li>

    <li class="dropdown">
        <a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
        <span class="caret"></span>
        </a>

        <ul class="dropdown-menu" role="menu">
            <li><a :href="'/user/' + this.user.username"><span class="glyphicon glyphicon-user"></span>&nbsp;Profile</a></li>
            <li><a href="/profile/edit"><span class="glyphicon glyphicon-wrench"></span>&nbsp;Edit Profile</a></li>
            <li><a href="/settings"><span class="glyphicon glyphicon-cog"></span>&nbsp;Settings</a></li>
            <li>
                <a href="/logout"
                    onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                             <span class="glyphicon glyphicon-log-out"></span>&nbsp;
                    Logout
                </a>
                <form id="logout-form" action="/logout" method="POST" style="display: none;">
                    <input type="hidden" name="_token" :value="$root.csrfToken">
                </form>
            </li>
        </ul>
    </li>
</ul>
</template>

<script>
    import eventHub from '../event.js';
	export default {
		data() {
			return {
                notifications: [],
                noti_count: 0,
                req_count: 0,
                reqs: []
			}
		},

		props: ['user','requests'],

        /*
         * When view is mounted
         */
        mounted() {
            // Set data to property
            this.reqs = this.requests;

            // Get notifications and add them to the list
            this.$http.get('/notifications').then((response) => {
                this.notifications = response.body;
                this.noti_count = this.notifications.length;
            });

            // Request count logic
            for (var i = 0; i < this.reqs.length; i++) {
                if(this.reqs[i].pivot.user_id == this.user.id) {
                    this.req_count++;
                }
            }

            //eventHub.$on('post-liked',this.addNotification);
            
            // Set up event listeners
            eventHub.$on('user-post-liked',this.addNotification);
            eventHub.$on('clear',this.clear);
            eventHub.$on('request-received',this.addRequest);

            // Emit clear event when notifications dropdown is clicked
            $('#noti').on('show.bs.dropdown', function () {
                eventHub.$emit('clear');
            });
        },

		methods: {
            /*
             * Send request to server to clear notifications
             */
            clear() {
                this.noti_count = 0;
                this.$http.get('/notifications/clear');
            },

            /*
             * Get unread notifications and add them to the list
             */
            addNotification(postId, likedByCurrentUser, post) {
                this.$http.get('/notifications').then((response) => {
                    this.notifications = response.body;
                    this.noti_count = this.notifications.length;
                });
            },

            /*
             * Add request to list
             */
            addRequest(user) {
                this.reqs.unshift(user);
                this.req_count = this.requests.length;
            },

            /*
             * Send to request to server to accept a request and remove it from the list
             */
            acceptRequest(request) {
                // Send request to server
                this.$http.get("/friends/accept/"+request.username).then((response) => {

                    // Check for errors
                    if (response.body.error) {

                        swal({title:"Uh oh...", text: response.body.error, type:"error",showConfirmButton: true});

                    // If there is no error, alert the user of the successful add
                    } else {
                         swal({title:"Friend Added", text:"You and " + request.name + " are now friends!", type:"success",timer: 3000,showCloseButton: false,showConfirmButton: false});

                        // Reload the timeline to show new friends posts
                        eventHub.$emit("reload-timeline");

                        // Get index of request
                        var index = this.reqs.indexOf(request);

                        // Remove request and reset request length
                        this.reqs.splice(index, 1);
                        this.req_count = this.reqs.length;
                    }

                // Handle Response Error
                }).then((response) => {
                    // Check for resposne
                    if (response) {
                        // Alert user
                        swal({title:"Whoops...", text:"There was a issue accepting the friend request",showConfirmButton: true});
                    }
                });
            },

            /*
             * Remove request from list and send XHR request to remove from backend
             */
            deleteRequest(request) {
                // Send request to server
                this.$http.get("/friends/decline/"+request.username).then((response) => {

                    // Check if there is an error
                    if (response.body.error) {

                        swal({title:"Uh oh...", text: response.body.error, type:"error",showConfirmButton: true});

                    // If there is no error, alert user of success
                    } else {
                        swal({title:"Request Declined", text:request.name + "'s friend request has been deleted.", type:"success",timer: 3000,showCloseButton: false,showConfirmButton: false});

                        // Get the index of the request
                        var index = this.reqs.indexOf(request);

                        //  Remove request and reset request count
                        this.reqs.splice(index, 1);
                        this.req_count = this.reqs.length;
                    }

                // Handle response error
                }).then((response) => {
                    // Check if there is a response
                    if (response) {
                        // Alert user
                        swal({title:"Whoops...", text:"There was a issue deleting the friend request",showConfirmButton: true});
                    }
                });
            }
		}
	}
    
</script>