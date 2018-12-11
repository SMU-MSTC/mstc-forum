<template>
  <div class="search">
    <Navigator :session="session" />
    <div v-if="threads" class="container threads-page">
      <hr><h1>Threads</h1><hr><hr>
    </div>
    <div v-else class="container threads-page">
      <hr><h1>No matched thread</h1><hr>
    </div>
    <div class="threads" v-for="thread in threads" :key="thread.thread_id">
      <div class="container">
        <div class="row">
          <div class="col">
            <h2>{{thread.thread_title}}</h2>
            <p class="float-right">
              <router-link :to="'/user/' + thread.user_id">{{thread.user_name}}</router-link>
              {{thread.thread_time}}
            </p>
          </div>
        </div>
        <div class="row">
          <router-link :to="'/board/' + thread.board_id" class="btn btn-default float-left" role="button">&laquo; Go to {{thread.board_name}}</router-link>
        </div>
        <div class="row">
          <div class="col">
            <button v-on:click="deleteThread(thread.thread_id)" v-if="session.user_is_admin" class="btn btn-danger ">&times; Delete</button>
            <p class="float-right">
              <button v-if="thread.favorite && session.user_id" v-on:click="favorite(thread.thread_id)" class="btn btn-success">Favorited</button>
              <button v-if="!thread.favorite && session.user_id" v-on:click="favorite(thread.thread_id)" class="btn btn-light">Favorite</button>
              <router-link :to="'/read/' + thread.thread_id" class="btn btn-secondary" role="button">View &raquo;</router-link>
            </p>
          </div>
        </div>
        <div v-if="!thread.thread_visible" class="tip row">
          <div v-if="tip.status === 'success'" class="alert alert-success">{{tip.message}}</div>
          <div v-if="tip.status === 'fail'" class="alert alert-danger">{{tip.message}}</div>
        </div>
        <hr>
      </div>
    </div>
    <div v-if="replies" class="container replies-page">
      <hr><h1>Replies</h1><hr><hr>
    </div>
    <div v-else class="container replies-page">
      <hr><h1>No matched reply</h1><hr>
    </div>
    <div class="replies" v-for="reply in replies" :key="reply.reply_id">
      <div v-if="reply && !reply.reply_is_reply" class="container">
        <div class="row">
          <div class="col">
            <p class="lead">{{reply.reply_content}}</p>
            <p class="float-right">
              <router-link :to="'/user/' + reply.user_id">{{reply.user_name}}</router-link>
              {{reply.reply_time}}
            </p>
          </div>
        </div>
        <div class="row">
          <router-link :to="'/board/' + reply.board_id" class="btn btn-default float-left" role="button">&laquo; Go to {{reply.board_name}}</router-link>
        </div>
        <div v-if="session.user_id">
          <div class="row">
            <div class="col">
              <button v-on:click="deleteReply(reply.reply_id)" v-if="session.user_is_admin" class="btn btn-danger float-left">&times; Delete</button>
              <router-link :to="'/read/' + reply.thread_id" class="btn btn-secondary float-right" role="button">View &raquo;</router-link>
            </div>
          </div>
        </div>
        <div v-if="tip.flag && tip.reply_id === reply.reply_id" class="tip row">
          <div v-if="tip.status === 'success'" class="alert alert-success">{{tip.message}}</div>
          <div v-if="tip.status === 'fail'" class="alert alert-danger">{{tip.message}}</div>
        </div>
        <hr>
      </div>
      <div v-else-if="reply && reply.reply_is_reply" class="container">
        <div class="row">
          <div v-if="reply.reply_reply_content" class="col">
            <p>&raquo; <small><em>{{reply.reply_reply_content}}
              <router-link :to="'/user/' + reply.reply_reply_user_id">{{reply.reply_reply_user_name}} </router-link>
              {{reply.reply_reply_time}}
            </em></small></p>
          </div>
          <div v-else>
            <p>&raquo; <small><em>[Reply has been deleted!]</em></small></p>
          </div>
        </div>
        <div class="row">
          <div class="col">
            <p class="lead">{{reply.reply_content}}</p>
            <p class="float-right">
              <router-link :to="'/user/' + reply.user_id">{{reply.user_name}}</router-link>
              {{reply.reply_time}}
            </p>
          </div>
        </div>
        <div class="row">
          <router-link :to="'/board/' + reply.board_id" class="btn btn-default float-left" role="button">&laquo; Go to {{reply.board_name}}</router-link>
        </div>
        <div v-if="session.user_id">
          <div class="row">
            <div class="col">
              <button v-on:click="deleteReply(reply.reply_id)" v-if="session.user_is_admin" class="btn btn-danger float-left">&times; Delete</button>
              <router-link :to="'/read/' + reply.thread_id" class="btn btn-secondary float-right" role="button">View &raquo;</router-link>
            </div>
          </div>
        </div>
        <div v-if="tip.flag && tip.reply_id === reply.reply_id" class="tip row">
          <div v-if="tip.status === 'success'" class="alert alert-success">{{tip.message}}</div>
          <div v-if="tip.status === 'fail'" class="alert alert-danger">{{tip.message}}</div>
        </div>
        <hr>
      </div>
    </div>
    <Foot />
  </div>
</template>

<script>
  import Navigator from '../components/Navigator'
  import Foot from '../components/Foot'

  export default {
    name: 'search',
    components: {
      Navigator,
      Foot
    },
    props: {
      session: {
        user_id: null,
        user_is_admin: null,
        user_name: null
      }
    },
    data() {
      return {
        threads: {
          thread: {
            thread_id: null,
            user_id: null,
            user_name: null,
            board_id: null,
            board_name: null,
            thread_title: null,
            thread_content: null,
            thread_time: null,
            thread_visible: null,
            favorite: null
          },
        },
        replies: {
          reply: {
            reply_id: null,
            user_id: null,
            user_name: null,
            thread_id: null,
            thread_title: null,
            reply_content: null,
            reply_time: null,
            reply_is_reply: null,
            reply_reply_id: null,
            reply_reply_user_id: null,
            reply_reply_user_name: null,
            reply_reply_content: null,
            reply_reply_time: null,
            reply_visible: null
          }
        },
        tip: {
          flag: false,
          reply_id: null,
          status: null,
          message: null,
        },
        change: false,
      }
    },
    watch: {
      '$route.params'() {
        const self = this
        const search = this.$route.params.search
        $.post(api + "/search", { search: search }).done((data) => {
          self.threads = data.threads
          self.replies = data.replies
        })
      },
      'change'() {
        const self = this
        const search = this.$route.params.search
        $.post(api + "/search", { search: search }).done((data) => {
          self.threads = data.threads
          self.replies = data.replies
        })
      }
    },
    methods: {
      deleteThread(thread_id) {
        const self = this
        this.threads.forEach((item) => {
          if (item.thread_id === thread_id) {
            if (!item.thread_visible)
              item.thread_visible = true
            else if (item.thread_visible)
              item.thread_visible = false
          }
        })
        $.post(api + '/delete', { thread_id: thread_id }).done((data) => {
          if (data.toString() === '1') {
            self.tip.status = 'success'
            self.tip.message = 'Delete successfully!'
            setTimeout(() => {
              self.tip.message = 'Reloading in 2 seconds.'
            }, 1000)
            setTimeout(() => {
              self.tip.status = null
              self.tip.message = null
              self.$emit('update')
              this.change = (this.change === false)
            }, 2000)
          } else if (data.toString() === '0') {
            self.tip.status = 'fail'
            self.tip.message = 'Delete failed!!'
            setTimeout(() => {
              self.tip.status = null
              self.tip.message = null
            }, 2000)
          }
        })
      },
      deleteReply(reply_id) {
        const self = this
        self.replies.forEach((item) => {
          if (item.reply_id === reply_id) {
            if (!item.reply_visible)
              item.reply_visible = true
            else if (item.reply_visible)
              item.reply_visible = false
          }
        })
        self.tip.flag = (self.tip.flag !== true)
        self.tip.reply_id = reply_id
        $.post(api + '/delete', { reply_id: reply_id }).done((data) => {
          if (data.toString() === '1') {
            self.tip.status = 'success'
            self.tip.message = 'Delete successfully!'
            setTimeout(() => {
              self.tip.message = 'Reloading in 2 seconds.'
            }, 1000)
            setTimeout(() => {
              self.tip.status = null
              self.tip.message = null
              self.tip.flag = false
              self.tip.reply_id = null
              this.change = (this.change === false)
            }, 2000)
          } else if (data.toString() === '0') {
            self.tip.status = 'fail'
            self.tip.message = 'Delete failed!!'
            self.tip.reply_id = false
            setTimeout(() => {
              self.tip.status = null
              self.tip.message = null
              self.tip.flag = false
            }, 2000)
          }
        })
      },
      favorite(thread_id) {
        const self = this
        this.change = (this.change === false)
        $.post(api + '/favorite', { thread_id: thread_id }).done(() => {
          self.$emit('reload')
        })
      }
    },
    beforeMount() {
      const self = this
      const search = this.$route.params.search
      $.post(api + "/search", { search: search }).done((data) => {
        self.threads = data.threads
        self.replies = data.replies
      })
    }
  }
</script>

<style scoped>
  .threads-page {
    padding-top: 30px;
    padding-bottom: 30px;
  }

  .replies-page {
    padding-top: 30px;
    padding-bottom: 30px;
  }

  .alert {
    width: 100%;
    padding: 15px;
    margin: auto;
  }

  .threads:first-child {
    padding-top: 30px;
  }

  .threads:last-child {
    padding-bottom: 30px;
  }

  .threads .tip {
    padding-top: 30px;
    padding-bottom: 30px;
  }

  .threads .btn {
    margin-left: 5px;
    margin-right: 5px;
  }

  .replies:first-child {
    padding-top: 30px;
  }

  .replies:last-child {
    padding-bottom: 30px;
  }

  .replies .tip {
    padding-top: 30px;
    padding-bottom: 30px;
  }
</style>
