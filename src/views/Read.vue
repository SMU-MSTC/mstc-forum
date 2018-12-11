<template>
  <div class="read">
    <Navigator :session="session" />
    <div class="read-page">
      <div class="thread">
        <div v-if="thread" class="container">
          <div class="row">
            <div class="col">
              <h2>{{thread.thread_title}}</h2>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <p class="lead">{{thread.thread_content}}</p>
              <p class="float-right">
                <router-link :to="'/user/' + thread.user_id">{{thread.user_name}}</router-link>
                {{thread.thread_time}}
              </p>
            </div>
          </div>
          <div class="row">
            <router-link :to="'/board/' + thread.board_id" class="btn btn-default float-left" role="button">&laquo; Back to {{thread.board_name}}</router-link>
            <div v-if="session.user_id" class="col">
              <p class="float-right">
                <button v-if="thread.favorite && session.user_id" v-on:click="favorite(thread.thread_id)" class="btn btn-success">Favorited</button>
                <button v-if="!thread.favorite && session.user_id" v-on:click="favorite(thread.thread_id)" class="btn btn-light">Favorite</button>
                <button v-on:click="threadOnClick()" class="btn btn-primary" type="button">Reply &raquo;</button>
              </p>
            </div>
          </div>
          <ReplyForm v-if="!thread.thread_visible" :thread_id="thread.thread_id" @reload="reload" />
        </div>
        <div v-else class="container">
          <div class="row">
            <div class="col">
              <h2>This thread has been deleted.</h2>
            </div>
          </div>
        </div>
        <hr>
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
          <div v-if="session.user_id">
            <div class="row">
              <div class="col">
                <button v-on:click="deleteReply(reply.reply_id)" v-if="session.user_is_admin" class="btn btn-danger float-left">&times; Delete</button>
                <button v-on:click="replyOnClick(reply.reply_id)" class="btn btn-secondary float-right" type="button">Reply &raquo;</button>
              </div>
            </div>
            <div v-if="tip.flag && tip.reply_id === reply.reply_id" class="tip row">
              <div v-if="tip.status === 'success'" class="alert alert-success">{{tip.message}}</div>
              <div v-if="tip.status === 'fail'" class="alert alert-danger">{{tip.message}}</div>
            </div>
          </div>
          <ReplyForm v-if="!reply.reply_visible && !tip.flag" :thread_id="thread.thread_id" :reply_id="reply.reply_id" @reload="reload" />
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
          <div v-if="session.user_id">
            <div class="row">
              <div class="col">
                <button v-on:click="deleteReply(reply.reply_id)" v-if="session.user_is_admin" class="btn btn-danger float-left">&times; Delete</button>
                <button v-on:click="replyOnClick(reply.reply_id)" class="btn btn-secondary float-right" type="button">Reply &raquo;</button>
              </div>
            </div>
            <div v-if="tip.flag && tip.reply_id === reply.reply_id" class="tip row">
              <div v-if="tip.status === 'success'" class="alert alert-success">{{tip.message}}</div>
              <div v-if="tip.status === 'fail'" class="alert alert-danger">{{tip.message}}</div>
            </div>
          </div>
          <ReplyForm v-if="!reply.reply_visible && !tip.flag" :thread_id="thread.thread_id" :reply_id="reply.reply_id" @reload="reload" />
          <hr>
        </div>
      </div>
    </div>
    <Foot />
  </div>
</template>

<script>
  import Navigator from '../components/Navigator'
  import ReplyForm from '../components/ReplyForm'
  import Foot from '../components/Foot'

  export default {
    name: 'read',
    components: {
      Navigator,
      ReplyForm,
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
        thread: {
          thread_id: null,
          user_id: null,
          user_name: null,
          thread_title: null,
          thread_content: null,
          thread_time: null,
          thread_visible: null,
          favorite: null
        },
        replies: {
          reply: {
            reply_id: null,
            user_id: null,
            user_name: null,
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
        }
      }
    },
    methods: {
      threadOnClick() {
        if (this.thread.thread_visible)
            this.thread.thread_visible = false
        else if (!this.thread.thread_visible)
            this.thread.thread_visible = true
      },
      replyOnClick(reply_id) {
        this.replies.forEach((item) => {
          if (item.reply_id === reply_id) {
            if (!item.reply_visible)
              item.reply_visible = true
            else if (item.reply_visible)
              item.reply_visible = false
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
              self.reload()
            }, 2000)
          } else if (data.toString() === '0') {
            self.tip.status = 'fail'
            self.tip.message = 'Delete failed!!'
            self.tip.reply_id = false
            setTimeout(() => {
              self.tip.status = null
              self.tip.message = null
              self.tip.flag = false
              self.$emit('reload')
            }, 2000)
          }
        })
      },
      favorite(thread_id) {
        const self = this
        $.post(api + '/favorite', { thread_id: thread_id }).done(() => {
          self.reload()
        })
      },
      reload() {
        const self = this
        const thread_id = this.$route.params.thread_id
        $.get(api + '/read?thread_id=' + thread_id, (data) => {
          self.thread = data.thread
          self.replies = data.replies
        })
      }
    },
    beforeMount() {
      const self = this
      const thread_id = this.$route.params.thread_id
      $.get(api + '/read?thread_id=' + thread_id, (data) => {
        self.thread = data.thread
        self.replies = data.replies
      })
    }
  }
</script>

<style scoped>
  .read-page {
    height: 100%;
    padding-bottom: 60px;
  }

  .read-page .thread {
    background-color: #e9ecef;
  }

  .read-page .btn {
    margin-left: 5px;
    margin-right: 5px;
  }

  .read-page .thread .row:first-child {
    padding-top: 60px;
  }

  .read-page .thread .col:last-child {
    padding-bottom: 30px;
  }

  .alert {
    width: 100%;
    padding: 15px;
    margin: auto;
  }

  .replies .tip {
    padding-top: 30px;
    padding-bottom: 30px;
  }
</style>
