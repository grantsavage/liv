<template>
<ul class="nav navbar-nav navbar-right">
    <li> 
        <a :href="'/user/' + this.user.username">
        <img class="img-rounded" :src="this.user.avatar" alt="" width="25" height="25">
        <span id="nav-name">{{ this.user.name }}</span></a>
    </li>
    <li><a href="/home">Home</a></li>


    <li>
        <a href="#">
            <span class="glyphicon glyphicon-user"></span>
        </a>
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
                <a href=""><img :src="notification.data.user.avatar" alt="" width="35" height="35"> {{notification.data.user.name}} liked your post.</a>
            </li>

        </ul>
    </li>

    <li class="dropdown">
        <a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
        <span class="caret"></span>
        </a>

        <ul class="dropdown-menu" role="menu">
            <li><a :href="'/user/' + this.user.username">Profile</a></li>
            <li><a href="/profile/edit">Edit Profile</a></li>
            <li><a href="#">Settings</a></li>
            <li>
                <a href="/logout"
                    onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
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
                noti_count: 0
			}
		},
		props: ['user'],
        mounted() {
            this.$http.get('/notifications').then((response) => {
                this.notifications = response.body;
                this.noti_count = this.notifications.length;
            });
            eventHub.$on('post-liked',this.addNotification);
            eventHub.$on('clear',this.clear);
            $('#noti').on('show.bs.dropdown', function () {
                eventHub.$emit('clear');
            });
        },
		methods: {
            clear() {
                this.noti_count = 0;
                this.$http.get('/notifications/clear');
            },
            addNotification(postId, likedByCurrentUser, post) {
                this.$http.get('/notifications').then((response) => {
                    this.notifications = response.body;
                    this.noti_count = this.notifications.length;
                });
            }
		}
	}
    
</script>