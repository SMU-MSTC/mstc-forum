<template>
  <div class="read">
    <Navigator :session="session" />
    <div class="read-page">
      <div class="thread">
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
            <div class="col">
              <router-link :to="'/board/' + thread.board_id" class="btn btn-default float-left" role="button">&laquo; Back to {{thread.board_name}}</router-link>
            </div>
            <div v-if="session.user_id !== null" class="col">
              <button v-on:click="threadOnClick()" class="btn btn-primary float-right" type="button">Reply &raquo;</button>
            </div>
          </div>
          <ReplyForm v-if="thread.thread_visible === false" :thread_id="thread.thread_id" @reload="reload" />
        </div>
        <hr>
      </div>
      <div class="replies" v-for="reply in replies" :key="reply.reply_id">
        <div v-if="reply.reply_is_reply === false" class="container">
          <div class="row">
            <div class="col">
              <p class="lead">{{reply.reply_content}}</p>
              <p class="float-right">
                <router-link :to="'/user/' + reply.user_id">{{reply.user_name}}</router-link>
                {{reply.reply_time}}
              </p>
            </div>
          </div>
          <div v-if="session.user_id !== null">
            <div class="row">
              <div class="col">
                <button v-on:click="deleteReply(reply.reply_id)" v-if="session.user_is_admin === true" class="btn btn-danger float-left">&times; Delete</button>
                <button v-on:click="replyOnClick(reply.reply_id)" class="btn btn-secondary float-right" type="button">Reply &raquo;</button>
              </div>
            </div>
            <div v-if="tip.flag === true && tip.reply_id === reply.reply_id" class="tip row">
              <div v-if="tip.status === 'success'" class="alert alert-success">{{tip.message}}</div>
              <div v-if="tip.status === 'fail'" class="alert alert-danger">{{tip.message}}</div>
            </div>
          </div>
          <ReplyForm v-if="reply.reply_visible === false && tip.flag ===false" :thread_id="thread.thread_id" :reply_id="reply.reply_id" @reload="reload" />
          <hr>
        </div>
        <div v-else class="container">
          <div class="row">
            <div class="col">
              <p>&raquo; <small><em>{{reply.reply_reply_content}}
                <router-link :to="'/user/' + reply.reply_reply_user_id">{{reply.reply_reply_user_name}} </router-link>
                {{reply.reply_reply_time}}
              </em></small></p>
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
          <div v-if="session.user_id !== null">
            <div class="row">
              <div class="col">
                <button v-on:click="deleteReply(reply.reply_id)" v-if="session.user_is_admin === true" class="btn btn-danger float-left">&times; Delete</button>
                <button v-on:click="replyOnClick(reply.reply_id)" class="btn btn-secondary float-right" type="button">Reply &raquo;</button>
              </div>
            </div>
            <div v-if="tip.flag === true && tip.reply_id === reply.reply_id" class="tip row">
              <div v-if="tip.status === 'success'" class="alert alert-success">{{tip.message}}</div>
              <div v-if="tip.status === 'fail'" class="alert alert-danger">{{tip.message}}</div>
            </div>
          </div>
          <ReplyForm v-if="reply.reply_visible === false && tip.flag ===false" :thread_id="thread.thread_id" :reply_id="reply.reply_id" @reload="reload" />
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
          thread_visible: null
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
        if (this.thread.thread_visible === true)
            this.thread.thread_visible = false
        else if (this.thread.thread_visible === false)
            this.thread.thread_visible = true
      },
      replyOnClick(reply_id) {
        this.replies.forEach((item) => {
          if (item.reply_id === reply_id) {
            if (item.reply_visible === false)
              item.reply_visible = true
            else if (item.reply_visible === true)
              item.reply_visible = false
          }
        })
      },
      deleteReply(reply_id) {
        const self = this
        self.replies.forEach((item) => {
          if (item.reply_id === reply_id) {
            if (item.reply_visible === false)
              item.reply_visible = true
            else if (item.reply_visible === true)
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
