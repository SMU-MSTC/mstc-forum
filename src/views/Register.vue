<template>
  <div class="register">
    <Navigator :session="session"/>
    <div class="register-page">
      <form @submit.prevent="submit" class="register-form">
        <img class="mb-4" src="../assets/logo.png" alt="" width="72" height="72">
        <h1 class="h3 mb-3 font-weight-normal">Please Input your information.</h1>
        <label for="user_name" class="sr-only">Username</label>
        <input v-model="register.user_name" type="text" id="user_name" class="form-control" placeholder="Username" required autofocus>
        <label for="user_password" class="sr-only">Password</label>
        <input v-model="register.user_password" type="password" id="user_password" class="form-control" placeholder="Password" required>
        <label for="confirm_password" class="sr-only">Confirm password</label>
        <input v-model="register.confirm_password" type="password" id="confirm_password" class="form-control" placeholder="Confirm Password" required>
        <label for="user_email" class="sr-only">Email</label>
        <input v-model="register.user_email" type="email" id="user_email" class="form-control" placeholder="Email" required autofocus>
        <label for="user_name" class="sr-only">Phone number</label>
        <input v-model="register.user_tel" type="tel" id="user_tel" class="form-control" placeholder="Phone number" required autofocus>
        <div v-if="tip.status === 'success'" class="alert alert-success">
          {{tip.message}}
        </div>
        <div v-if="tip.status === 'warn'" class="alert alert-warning">
          {{tip.message}}
        </div>
        <div v-if="tip.status === 'fail'" class="alert alert-danger">
          {{tip.message}}
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Register</button>
      </form>
    </div>
    <Foot/>
  </div>
</template>

<script>
  import Navigator from '../components/Navigator'
  import Foot from '../components/Foot'

  export default {
    name: 'register',
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
        register: {
          user_name: null,
          user_password: null,
          confirm_password: null,
          user_email: null,
          user_tel: null
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
        if (self.register.user_password !== self.register.confirm_password) {
          self.tip.status = 'warn'
          self.tip.message = 'Password does not match!'
          self.register.user_password = null
          self.register.confirm_password = null
          setTimeout(() => {
            self.tip.status = null
            self.tip.message = null
          }, 2000)
        } else {
          this.register.user_password = md5(this.register.user_password)
          $.post(api + '/register', this.register).done((data) => {
            if (data.toString() === '1') {
              self.tip.status = 'success'
              self.tip.message = 'Register successful!'
              self.$emit('update')
              setTimeout(() => {
                self.tip.message = 'Redirecting in 2 seconds.'
              }, 1000)
              setTimeout(() => {
                self.$router.push('/')
              }, 2000)
            } else if (data.toString() === '0') {
              self.tip.status = 'fail'
              self.tip.message = 'Register failed!'
              self.register.user_name = null
              self.register.user_password = null
              self.register.confirm_password = null
              setTimeout(() => {
                self.tip.status = null
                self.tip.message = null
              }, 2000)
            }
          })
        }
      }
    }
  }
</script>

<style scoped>
  .register-page {
    height: 100%;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-align: center;
    align-items: center;
    text-align: center;
    padding-top: 120px;
    padding-bottom: 120px;
  }

  .register-form {
    width: 100%;
    max-width: 480px;
    padding: 15px;
    margin: auto;
  }

  .register-form .form-control {
    position: relative;
    box-sizing: border-box;
    height: auto;
    padding: 16px;
    font-size: 16px;
  }

  .register-form .form-control:focus {
    z-index: 2;
  }

  .form-control input {
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
  }

  .register-form input[type="text"] {
    margin-top: 40px;
  }

  .register-form input[type="tel"] {
    margin-bottom: 40px;
  }

  .register-form .alert {
    margin-bottom: 40px;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
  }
</style>
