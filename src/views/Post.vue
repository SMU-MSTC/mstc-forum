<template>
  <div class="post">
    <Navigator :session="session" />
    <BoardJumbotron :info="board" :loaded="loaded" />
    <div v-if="session.user_id" class="post-page">
      <div class="container">
        <form @submit.prevent="submit" class="post-form">
          <label for="thread_title" class="sr-only">Thread Title</label>
          <input v-model="threadPost.thread_title" type="text" id="thread_title" class="form-control" placeholder="Thread Title" required autofocus>
          <label for="thread_content" class="sr-only">Thread Content</label>
          <textarea v-model="threadPost.thread_content" type="text"
                    id="thread_content" class="form-control" rows="3"
                    placeholder="Thread Content"/>
          <div v-if="tip.status === 'success'" class="alert alert-success">{{tip.message}}</div>
          <div v-if="tip.status === 'warn'" class="alert alert-warning">{{tip.message}}</div>
          <div v-if="tip.status === 'fail'" class="alert alert-danger">{{tip.message}}</div>
          <button class="btn btn-lg btn-primary btn-block" type="submit">Post</button>
        </form>
      </div>
    </div>
    <div v-else class="post-page">
      <div class="alert alert-danger">Please login first.</div>
    </div>
    <Foot />
  </div>
</template>

<script>
  import Navigator from '../components/Navigator'
  import BoardJumbotron from '../components/BoardJumbotron'
  import Foot from '../components/Foot'

  export default {
    name: 'post',
    components: {
      Navigator,
      BoardJumbotron,
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
        board: {
          board_id: null,
          board_name: null,
          board_intro: null
        },
        threadPost: {
          thread_title: null,
          thread_content: null
        },
        tip: {
          status: null,
          message: null,
        },
        loaded: false
      }
    },
    methods: {
      submit() {
        const self = this
        const board_id = this.$route.params.board_id
        fetch(api + '/post?board_id=' + board_id, this.post(this.threadPost))
          .then((response) => {
            return response.json()
          })
          .then((data) => {
            if (data.toString() === '1') {
              self.tip.status = 'success'
              self.tip.message = 'Post successfully!'
              self.$emit('update')
              setTimeout(() => {
                self.tip.message = 'Redirecting in 2 seconds.'
              }, 1000)
              setTimeout(() => {
                self.$router.push('/board/' + board_id)
              }, 2000)
            } else if (data.toString() === '0') {
              self.tip.status = 'fail'
              self.tip.message = 'Post failed!!'
              setTimeout(() => {
                self.tip.status = null
                self.tip.message = null
              }, 2000)
            }
          })
      }
    },
    beforeMount() {
      const self = this
      const board_id = this.$route.params.board_id
      fetch(api + '/post?board_id=' + board_id)
        .then((response) => {
          return response.json()
        })
        .then((data) => {
          self.board = data.board
          self.loaded = true
        })
    }
  }
</script>

<style scoped>
  .post-page {
    height: 100%;
    padding-bottom: 120px;
  }

  .post-form {
    width: 100%;
    max-width: 960px;
    padding: 15px;
    margin: auto;
  }

  .alert {
    width: 100%;
    max-width: 960px;
    padding: 15px;
    margin: auto;
  }

  .post-form .form-control {
    position: relative;
    box-sizing: border-box;
    height: auto;
    padding: 16px;
    font-size: 16px;
  }

  .post-form .form-control:focus {
    z-index: 2;
  }

  .form-control input {
    margin-top: 40px;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
  }

  .post-form textarea {
    margin-bottom: 40px;
  }

  .post-form .alert {
    margin-bottom: 40px;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
  }
</style>
