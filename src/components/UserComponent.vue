<template>
  <div class="user-component">
    <div v-if="validate()" class="user-page" role="form">
      <form @submit.prevent="submit" class="update-form">
        <img class="mb-4" src="../assets/logo.png" alt="" width="72" height="72">
        <h1 class="h3 mb-3 font-weight-normal">Update your information.</h1>
        <label for="user_name" class="sr-only">Username</label>
        <input v-model="update.user_name" type="text" id="user_name" class="form-control" placeholder="Username" required autofocus>
        <label for="user_password" class="sr-only">Password</label>
        <input v-model="update.user_password" type="password" id="user_password" class="form-control" placeholder="Password" required>
        <label for="new_password" class="sr-only">New password</label>
        <input v-model="update.new_password" type="password" id="new_password" class="form-control" placeholder="New Password (Keep blank if you don't want to change)">
        <label for="user_gender" class="sr-only">Gender</label>
        <select v-model="update.user_gender" id="user_gender" class="form-control">
          <option value=null selected hidden>Gender</option>
          <option value="male">Male</option>
          <option value="female">Female</option>
          <option value="others">Others</option>
        </select>
        <label for="user_birth" class="sr-only">Birthday</label>
        <input v-model="update.user_birth" type="date" id="user_birth" class="form-control">
        <label for="user_email" class="sr-only">Email</label>
        <input v-model="update.user_email" type="email" id="user_email" class="form-control" placeholder="Email" required>
        <label for="user_name" class="sr-only">Phone number</label>
        <input v-model="update.user_tel" type="tel" id="user_tel" class="form-control" placeholder="Phone number" required>
        <label for="user_intro" class="sr-only">Intro</label>
        <textarea v-model="update.user_intro" type="text" id="user_intro" class="form-control" rows="3" placeholder="Intro"></textarea>
        <div v-if="tip.status === 'success'" class="alert alert-success">
          {{tip.message}}
        </div>
        <div v-if="tip.status === 'warn'" class="alert alert-warning">
          {{tip.message}}
        </div>
        <div v-if="tip.status === 'fail'" class="alert alert-danger">
          {{tip.message}}
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Update</button>
      </form>
    </div>
    <div v-else class="user-page">
      <div class="user-info">
        <div class="container">
          <img class="mb-4" src="../assets/logo.png" alt="" width="72"
               height="72">
          <table class="table table-bordered table-hover">
            <thead class="thead-light">
            <tr>
              <th colspan="2">User Information</th>
            </tr>
            </thead>
            <tbody>
            <tr>
              <th>Username</th>
              <td>{{user.user_name}}</td>
            </tr>
            <tr>
              <th>Gender</th>
              <td>{{user.user_gender}}</td>
            </tr>
            <tr>
              <th>Birthday</th>
              <td>{{user.user_birth}}</td>
            </tr>
            <tr>
              <th>Email</th>
              <td>{{user.user_email}}</td>
            </tr>
            <tr>
              <th>Phone number</th>
              <td>{{user.user_tel}}</td>
            </tr>
            <tr>
              <th>Intro</th>
              <td>{{user.user_intro}}</td>
            </tr>
            </tbody>
          </table>
          <router-link :to="'/send/' + user.user_id" v-if="session.user_id !== null" class="btn btn-primary" role="button">Send message to {{user.user_name}} &raquo;</router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'user-component',
    props: {
      session: {
        user_id: null,
        user_is_admin: null,
        user_name: null
      },
      user: {
        user_id: null,
        user_name: null,
        user_is_admin: null,
        user_gender: null,
        user_birth: null,
        user_email: null,
        user_tel: null,
        user_intro: null
      }
    },
    data() {
      return {
        update: {
          user_name: null,
          user_password: null,
          new_password: null,
          user_gender: null,
          user_birth: null,
          user_email: null,
          user_tel: null,
          user_intro: null
        },
        tip: {
          status: null,
          message: null,
        },
        flag: false
      }
    },
    methods: {
      validate() {
        if (this.session.user_id === this.user.user_id) {
          if (this.flag === false) {
            this.update.user_name = this.user.user_name
            this.update.user_gender = this.user.user_gender
            this.update.user_birth = this.user.user_birth
            this.update.user_email = this.user.user_email
            this.update.user_tel = this.user.user_tel
            this.update.user_intro = this.user.user_intro
            this.flag = true
          }
          return true
        } else
          return false
      },
      submit() {
        const self = this
        const user_id = this.session.user_id
        if (self.update.new_password === self.update.user_password) {
          self.tip.status = 'warn'
          self.tip.message = 'Please use another password.'
          self.update.user_password = null
          self.update.new_password = null
          setTimeout(() => {
            self.tip.status = null
            self.tip.message = null
          }, 2000)
        } else {
          this.update.user_password = md5(this.update.user_password)
          this.update.new_password = md5(this.update.new_password)
          $.post(api + '/user?user_id=' + user_id, this.update).done((data) => {
            if (data.toString() === '1') {
              self.tip.status = 'success'
              self.tip.message = 'Update successful!'
              self.$emit('update')
              setTimeout(() => {
                self.tip.message = 'Redirecting in 3 seconds.'
              }, 1000)
              setTimeout(() => {
                self.$router.push('/')
              }, 3000)
            } else if (data.toString() === '0') {
              self.tip.status = 'fail'
              self.tip.message = 'Update failed!'
              self.update.user_password = null
              self.update.new_password = null
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
  .user-page {
    height: 100%;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-align: center;
    align-items: center;
    text-align: center;
    padding-top: 40px;
    padding-bottom: 40px;
  }

  .update-form {
    width: 100%;
    max-width: 480px;
    padding: 15px;
    margin: auto;
  }

  .update-form .form-control {
    position: relative;
    box-sizing: border-box;
    height: auto;
    padding: 16px;
    font-size: 16px;
  }

  .update-form .form-control:focus {
    z-index: 2;
  }

  .form-control input {
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
  }

  .update-form #user_name {
    margin-top: 40px;
  }

  .update-form #user_intro {
    margin-bottom: 40px;
  }

  .update-form .alert {
    margin-bottom: 40px;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
  }

  .user-info {
    width: 100%;
    max-width: 480px;
    padding: 15px;
    margin: auto;
  }

  .table {
    margin-bottom: 40px;
  }
</style>
