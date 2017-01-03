<template>
    <div class="container">
        <div :class="{hidden: searching}" class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <!--<div class="panel-heading">Timeline</div>-->
                        <div class="panel-body">
                            <post-form></post-form>
                            <hr>
                            <post v-show="!loading" v-for="post in posts" :post="post"></post>
                            <div v-show="loading">
                                <div class="timeline-loader center-block"></div>
                            </div>
                            <p class="text-center" v-show="!loading" v-if="posts.length <= 0">No posts to show! Go make some friends!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Post from './Post.vue'
    import PostForm from './PostForm.vue'
    import eventHub from '../event.js'
    export default {
        data() {
            return {
                posts: [],
                searching: false,
                loading: true
            }
        },
        methods: {
            /*
             * Method for adding post to timeline
             */
            addPost(post) {
                this.posts.unshift(post)
            },

            /*
             * Update the likes of a post when the event occurs
             */
            likePost(postId, likedByCurrentUser) {
                for (var i = 0; i <= this.posts.length; i++) {
                    if (this.posts[i].id === postId) {
                        this.posts[i].likeCount++

                        if (likedByCurrentUser) {
                            this.posts[i].likedByCurrentUser = true
                        }

                        break;
                    }
                }
            },

            deletePost(postId) {
                for (var i = 0; i <= this.posts.length; i++) {
                    if (this.posts[i].id === postId) {
                        this.posts.splice(i,1);

                        break;
                    }
                }
            },

            /*
             * Hide and show timeline when searching
             */
            hideTimeline(){
                this.searching = true;
            },
            showTimeline(){
                this.searching = false;
            },

            /*
             * Reload the page when the event occurs
             */
            reload(){ 
                 this.$http.get('/posts').then((response) => {
                    this.posts = response.body
                    this.loading = false;
                });     
            }
        },

        components: [
            Post
        ],

        /*
         * Executed when view is mounted
         */
        mounted() {
            // Event listener setup
            eventHub.$on('post-added', this.addPost);
            eventHub.$on('post-liked', this.likePost);
            eventHub.$on('search',this.hideTimeline);
            eventHub.$on('not-searching', this.showTimeline);
            eventHub.$on('reload-timeline', this.reload);
            eventHub.$on('postDelete', this.deletePost);

            // Load the posts
            this.reload();
        }
    }
</script>