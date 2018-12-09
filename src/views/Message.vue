<template>
  <div class="message">
    <Navigator :session="session" />
    <MessageComponent :session="session" :messages="messages" />
    <Foot />
  </div>
</template>

<script>
  import Navigator from '../components/Navigator'
  import Foot from '../components/Foot'
  import MessageComponent from '../components/MessageComponent'

  export default {
    name: 'message',
    components: {
      MessageComponent,
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
        messages: {
          message: {
            message_id: null,
            message_from: null,
            message_to: null,
            message_content: null,
            message_time: null,
            message_type: null,
            message_is_read: null,
            message_from_user_name: null,
            message_from_thread_id: null,
            message_from_thread_title: null
          }
        }
      }
    },
    beforeMount () {
      const self = this
      $.get(api + '/message', (data) => {
        self.messages = data.message
      })
    }
  }
</script>
