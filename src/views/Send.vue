<template>
  <div class="send">
    <Navigator :session="session" />
    <div class="jumbotron">
      <div class="container">
        <h1>Sending message to <router-link :to="'/user/' + send.to">{{send.user_name}}</router-link></h1>
      </div>
    </div>
    <div v-if="session.user_id" class="send-page">
      <div class="container">
        <form @submit.prevent="submit" class="send-form">
          <label for="content" class="sr-only">Thread Content</label>
          <textarea v-model="send.content" type="text" id="content" class="form-control" rows="3" placeholder="Content" required autofocus></textarea>
          <div v-if="tip.status === 'success'" class="alert alert-success">{{tip.message}}</div>
          <div v-if="tip.status === 'warn'" class="alert alert-warning">{{tip.message}}</div>
          <div v-if="tip.status === 'fail'" class="alert alert-danger">{{tip.message}}</div>
          <button class="btn btn-lg btn-primary btn-block" type="submit">Send</button>
        </form>
      </div>
    </div>
    <div v-else class="send-page">
      <div class="alert alert-danger">Please login first.</div>
    </div>
    <Foot />
  </div>
</template>

<script>
  import Navigator from '../components/Navigator'
  import Foot from '../components/Foot'
  export default {
    name: 'send',
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
        send: {
          to: null,
          user_name: null,
          content: null
        },
        tip: {
          status: null,
          message: null,
        }
      }
    },
    methods: {
      submit() {
        const self = this
        const user_id = this.$route.params.user_id
        $.post(api + '/send?user_id=' + user_id, this.send).done((data) => {
          if (data.toString() === '1') {
            self.tip.status = 'success'
            self.tip.message = 'Send successfully!'
            self.$emit('update')
            setTimeout(() => {
              self.tip.message = 'Redirecting in 2 seconds.'
            }, 1000)
            setTimeout(() => {
              self.$router.push('/user/' + user_id)
            }, 2000)
          } else if (data.toString() === '0') {
            self.tip.status = 'fail'
            self.tip.message = 'Send failed!!'
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
      this.send.to = this.$route.params.user_id
      $.get(api + '/user?user_id=' + this.send.to, (data) => {
        self.send.user_name = data.user.user_name
      })
    }
  }
</script>

<style scoped>
  .send-page {
    height: 100%;
    padding-bottom: 120px;
  }

  .send-form {
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

  .send-form .form-control {
    position: relative;
    box-sizing: border-box;
    height: auto;
    padding: 16px;
    font-size: 16px;
  }

  .send-form .form-control:focus {
    z-index: 2;
  }

  .form-control input {
    margin-top: 40px;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
  }

  .send-form textarea {
    margin-bottom: 40px;
  }

  .send-form .alert {
    margin-bottom: 40px;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
  }
</style>
