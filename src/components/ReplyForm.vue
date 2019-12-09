<template>
  <div class="reply">
    <form @submit.prevent="submit" class="reply-form">
      <label for="reply_content" class="sr-only">Reply</label>
      <textarea v-model="reply_content" type="text" id="reply_content"
                class="form-control" rows="3" placeholder="Reply"/>
      <div v-if="tip.status === 'success'" class="alert alert-success">
        {{tip.message}}
      </div>
      <div v-if="tip.status === 'warn'" class="alert alert-warning">
        {{tip.message}}
      </div>
      <div v-if="tip.status === 'fail'" class="alert alert-danger">
        {{tip.message}}
      </div>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Reply
      </button>
    </form>
  </div>
</template>

<script>
  export default {
    name: 'reply-form',
    props: {
      thread_id: null,
      reply_id: null
    },
    data() {
      return {
        reply_content: null,
        tip: {
          status: null,
          message: null,
        }
      }
    },
    methods: {
      submit() {
        const self = this
        const reply = {
          thread_id: self.thread_id,
          reply_id: self.reply_id,
          reply_content: self.reply_content
        }
        fetch(api + '/reply', this.post(reply))
          .then((response) => {
            return response.json()
          })
          .then((data) => {
            if (data.toString() === '1') {
              self.tip.status = 'success'
              self.tip.message = 'Reply successfully!'
              setTimeout(() => {
                self.tip.message = 'Reloading in 2 seconds.'
              }, 1000)
              setTimeout(() => {
                self.tip.status = null
                self.tip.message = null
                self.$emit('update')
                self.$emit('reload')
              }, 2000)
            } else if (data.toString() === '0') {
              self.tip.status = 'fail'
              self.tip.message = 'Reply failed!!'
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
  .reply-form {
    width: 100%;
    max-width: 960px;
    padding: 15px;
    margin: auto;
  }
</style>
