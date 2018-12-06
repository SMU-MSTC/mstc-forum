<template>
  <div class="login">
    <Navigator :session="session"/>
    <div class="login-page">
      <form @submit.prevent="submit" class="loign-form">
        <img class="mb-4" src="../assets/logo.png" alt="" width="72" height="72">
        <h1 class="h3 mb-3 font-weight-normal">Please log in</h1>
        <label for="user_name" class="sr-only">Username</label>
        <input v-model="login.user_name" type="text" id="user_name" class="form-control" placeholder="Username" required autofocus>
        <label for="user_password" class="sr-only">Password</label>
        <input v-model="login.user_password" type="password" id="user_password" class="form-control" placeholder="Password" required>
        <div v-if="tip.status === 'success'" class="alert alert-success">
          {{tip.message}}
        </div>
        <div v-if="tip.status === 'fail'" class="alert alert-danger">
          {{tip.message}}
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Log in</button>
      </form>
    </div>
    <Foot/>
  </div>
</template>

<script>
  import Navigator from '../components/Navigator'
  import Foot from '../components/Foot'

  export default {
    name: 'login',
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
    data () {
      return {
        login: {
          user_name: null,
          user_password: null
        },
        tip: {
          status: null,
          message: null
        }
      }
    },
    methods: {
      submit () {
        const self = this
        this.login.user_password = md5(this.login.user_password)
        $.post(api + '/login', this.login).done((data) => {
          if (data.toString() === '1') {
            self.tip.status = 'success'
            self.tip.message = 'Login successful!'
            self.$emit('update')
            setTimeout(() => {
              self.tip.message = 'Redirecting in 2 seconds.'
            }, 1000)
            setTimeout(() => {
              self.$router.push('/')
            }, 2000)
          } else if (data.toString() === '0') {
            self.tip.status = 'fail'
            self.tip.message = 'Login failed!'
            self.login.user_name = null
            self.login.user_password = null
            setTimeout(() => {
              self.tip.status = null
              self.tip.message = null
            }, 2000)
          }
        })
      }
    }
  }
</script>

<style scoped>
  .login-page {
    height: 100%;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-align: center;
    align-items: center;
    text-align: center;
    padding-top: 120px;
    padding-bottom: 120px;
  }

  .loign-form {
    width: 100%;
    max-width: 480px;
    padding: 15px;
    margin: auto;
  }

  .loign-form .form-control {
    position: relative;
    box-sizing: border-box;
    height: auto;
    padding: 16px;
    font-size: 16px;
  }

  .loign-form .form-control:focus {
    z-index: 2;
  }

  .loign-form input[type="text"] {
    margin-top: 40px;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
  }

  .loign-form input[type="password"] {
    margin-bottom: 40px;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
  }

  .loign-form .alert {
    margin-bottom: 40px;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
  }
</style>
