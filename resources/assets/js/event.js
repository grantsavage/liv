var eventHub = new Vue();
module.exports = eventHub;

Echo.private('posts').listen('PostWasCreated', (e) => {
        eventHub.$emit('post-added', e.post)
    });

Echo.private('likes').listen('PostWasLiked', (e) => {
    eventHub.$emit('post-liked', e.post.id, false)
});

if (window.Notification && Notification.permission !== 'denied') {
    Notification.requestPermission((status) => {
        Echo.private('App.User.'+ window.Laravel.user.id).listen('PostWasLiked', (e) => {
            new Notification('Liv', {
                body: e.user.name + ' liked your post "' + e.post.body + '"'
            });
        });
    });
}