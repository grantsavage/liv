var eventHub = new Vue();
module.exports = eventHub;

import Push from "push.js";

Echo.private('posts').listen('PostWasCreated', (e) => {
        eventHub.$emit('post-added', e.post)
    });

Echo.private('likes').listen('PostWasLiked', (e) => {
    eventHub.$emit('post-liked', e.post.id, false, e.post)
});

if (window.Notification && Notification.permission !== 'denied') {
    Notification.requestPermission((status) => {
        Echo.private('App.User.'+ window.Laravel.user.id).listen('PostWasLiked', (e) => {
            /*new Notification('Liv', {
                body: e.user.name + ' liked your post "' + e.post.body + '"'
            });*/
            Push.create("Liv",{
                body: e.user.name + ' liked your post "' + e.post.body + '"',
                icon: e.user.avatar,
                timeout: 5000,
                onClick: function() {
                    window.focus();
                    this.close();
                }
            })
        });
    });
}