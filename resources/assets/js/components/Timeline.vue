<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Timeline</div>
                        <div class="panel-body">
                            <post-form></post-form>
                            <hr>
                            <post v-for="post in posts" :post="post"></post>
                            <p v-if="posts.length <= 0">No posts to show!</p>
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
                posts: []
            }
        },
        methods: {
            addPost(post) {
                this.posts.unshift(post)
            },
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
            }
        },
        components: [
            Post
        ],
        mounted() {
            eventHub.$on('post-added', this.addPost)
            eventHub.$on('post-liked', this.likePost)

            this.$http.get('/posts').then((response) => {
                this.posts = response.body
            });
        }
    }
</script>