<template>
<ul class="nav navbar-nav navbar-right">
    <li> 
        <a :href="'/user/' + this.user.username">
        <img class="img-rounded" :src="this.user.avatar" alt="" width="25" height="25">
        {{ this.user.name }}</a>
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

    <li>
        <a href="#">
            <span class="glyphicon glyphicon-globe"></span>
            <span class="badge" style="margin-bottom: 4px; background-color: #d9534f; padding-top: 1px;">4</span>
        </a>

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
	export default {
		data() {
			return {

			}
		},
		props: ['user'],
		methods() {

		},
		mounted() {
			
		}
	}
</script>