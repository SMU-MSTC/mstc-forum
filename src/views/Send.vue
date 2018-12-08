<template>
  <div class="send">
    <Navigator :session="session"/>
    <div v-if="session.user_id != null" class="send-page">
      <div class="container">
        <form @submit.prevent="submit" class="send-form">
          <label for="send_to" class="sr-only">Thread Title</label>
          <input v-model="send.to" type="text" id="send_to" class="form-control" placeholder="To" required autofocus>
          <label for="send_content" class="sr-only">Thread Content</label>
          <textarea v-model="send.content" type="text" id="send_content" class="form-control" rows="3" placeholder="Content"></textarea>
          <div v-if="tip.status === 'success'" class="alert alert-success">
            {{tip.message}}
          </div>
          <div v-if="tip.status === 'warn'" class="alert alert-warning">
            {{tip.message}}
          </div>
          <div v-if="tip.status === 'fail'" class="alert alert-danger">
            {{tip.message}}
          </div>
          <button class="btn btn-lg btn-primary btn-block" type="submit">Post</button>
        </form>
      </div>
    </div>
    <div v-else class="send-page">
      <div class="alert alert-danger">Please login first.</div>
    </div>
    <Foot/>
  </div>
</template>
<script>
  export default {
    name: 'send'
  }
</script>

<style scoped>

</style>
