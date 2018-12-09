<template>
  <div class="create">
    <Navigator :session="session" />
    <BoardJumbotron :info="board" />
    <div v-if="session.user_name === 'admin'" class="create-page">
      <div class="container">
        <form @submit.prevent="submit" class="create-form">
          <label for="board_name" class="sr-only">Board Name</label>
          <input v-model="create.board_name" type="text" id="board_name" class="form-control" placeholder="Board Name" required autofocus>
          <label for="board_intro" class="sr-only">Board Intro</label>
          <textarea v-model="create.board_intro" type="text" id="board_intro" class="form-control" rows="3" placeholder="Board Intro"></textarea>
          <div v-if="tip.status === 'success'" class="alert alert-success">{{tip.message}}</div>
          <div v-if="tip.status === 'warn'" class="alert alert-warning">{{tip.message}}</div>
          <div v-if="tip.status === 'fail'" class="alert alert-danger">{{tip.message}}</div>
          <button class="btn btn-lg btn-primary btn-block" type="submit">Create</button>
        </form>
      </div>
    </div>
    <div v-else class="create-page">
      <div class="alert alert-danger">Only admin can create new board.</div>
    </div>
    <Foot />
  </div>
</template>

<script>
  import Navigator from '../components/Navigator'
  import BoardJumbotron from '../components/BoardJumbotron'
  import Foot from '../components/Foot'

  export default {
    name: 'create',
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
        create: {
          board_name: null,
          board_intro: null
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
        $.post(api + '/board', this.create).done((data) => {
          if (data.toString() === '1') {
            self.tip.status = 'success'
            self.tip.message = 'Create successfully!'
            self.$emit('update')
            setTimeout(() => {
              self.tip.message = 'Redirecting in 2 seconds.'
            }, 1000)
            setTimeout(() => {
              self.$router.push('/')
            }, 2000)
          } else if (data.toString() === '0') {
            self.tip.status = 'fail'
            self.tip.message = 'Create failed!!'
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
  .create-page {
    height: 100%;
    padding-bottom: 120px;
  }

  .create-form {
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

  .create-form .form-control {
    position: relative;
    box-sizing: border-box;
    height: auto;
    padding: 16px;
    font-size: 16px;
  }

  .create-form .form-control:focus {
    z-index: 2;
  }

  .form-control input {
    margin-top: 40px;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
  }

  .create-form textarea {
    margin-bottom: 40px;
  }

  .create-form .alert {
    margin-bottom: 40px;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
  }
</style>
