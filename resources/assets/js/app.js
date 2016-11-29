
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('./event');


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('timeline', require('./components/Timeline.vue'));
Vue.component('post', require('./components/Post.vue'));
Vue.component('post-form', require('./components/PostForm.vue'));
Vue.component('like-button', require('./components/LikeButton.vue'));
Vue.component('edit-profile', require('./components/EditProfile.vue'));
Vue.component('profile', require('./components/Profile.vue'));
Vue.component('nav-bar-notifications', require('./components/NavBarNotifications.vue'));
Vue.component('search-bar-view', require('./components/SearchBarView.vue'));
Vue.component('search-results-view', require('./components/SearchResultsView.vue'));

const app = new Vue({
    el: '#app',
    data: window.Laravel
});

/*$(function () {
    $('#welcome').removeClass('hidden');
    $('#welcome').addClass('animated fadeIn');
});*/
