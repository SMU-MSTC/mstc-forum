<template>
  <div class="post">
    <Navigator :session="session" />
    <BoardJumbotron :info="board" />
    <div v-if="session.user_name === 'admin'" class="update-page">
      <div class="container">
        <form @submit.prevent="submit" class="update-form">
          <label for="board_name" class="sr-only">Board Name</label>
          <input v-model="update.board_name" type="text" id="board_name" class="form-control" placeholder="Board Name" required autofocus>
          <label for="board_intro" class="sr-only">Board Intro</label>
          <textarea v-model="update.board_intro" type="text" id="board_intro" class="form-control" rows="3" placeholder="Board Intro"></textarea>
          <div v-if="tip.status === 'success'" class="alert alert-success">{{tip.message}}</div>
          <div v-if="tip.status === 'warn'" class="alert alert-warning">{{tip.message}}</div>
          <div v-if="tip.status === 'fail'" class="alert alert-danger">{{tip.message}}</div>
          <button class="btn btn-lg btn-primary btn-block" type="submit">Update</button>
        </form>
      </div>
    </div>
    <div v-else class="update-page">
      <div class="alert alert-danger">Only admin can update board information.</div>
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
    data () {
      return {
        board: {
          board_id: null,
          board_name: null,
          board_intro: null
        },
        update: {
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
      submit () {
        const self = this
        const board_id = this.$route.params.board_id
        $.post(api + '/board?board_id=' + board_id, this.update).done((data) => {
          if (data.toString() === '1') {
            self.tip.status = 'success'
            self.tip.message = 'Update successfully!'
            self.$emit('update')
            setTimeout(() => {
              self.tip.message = 'Redirecting in 2 seconds.'
            }, 1000)
            setTimeout(() => {
              self.$router.push('/')
            }, 2000)
          } else if (data.toString() === '0') {
            self.tip.status = 'fail'
            self.tip.message = 'Update failed!!'
            setTimeout(() => {
              self.tip.status = null
              self.tip.message = null
            }, 2000)
          }
        })
      }
    },
    beforeMount () {
      const self = this
      const board_id = this.$route.params.board_id
      $.get(api + '/board?board_id=' + board_id, (data) => {
        self.board = data.board.info
      })
    }
  }
</script>

<style scoped>
  .update-page {
    height: 100%;
    padding-bottom: 120px;
  }

  .update-form {
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
    margin-top: 40px;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
  }

  .update-form textarea {
    margin-bottom: 40px;
  }

  .update-form .alert {
    margin-bottom: 40px;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
  }
</style>
