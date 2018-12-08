<template>
  <div class="message">
    <Navigator :session="session"/>
    <div class="container" v-if="session.user_id != null">
      <div class="accordion"  id="MessageBox">
        <div class="card">
          <div class="card-header" id="sysnotice">
            <h5 class="mb-0">
              <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#notice" aria-expanded="true" aria-controls="notice">
                System Notice.
              </button>
            </h5>
          </div>

          <div id="notice" class="collapse show" aria-labelledby="sysnotice" data-parent="#MessageBox">
            <div class="card-body">
              <div class="list-group">
                <div v-for="message in messages" :key="message.message_id">
                  <div class="list-group-item list-group-item-action"  v-if="message.message_type === true">
                    <router-link :to="'/read/'+ message.message_from" v-if="message.message_is_read === false">
                      <b>{{message.message_content}}</b>
                    </router-link>
                    <router-link :to="'/read/'+ message.message_from" v-else-if=" message.message_is_read === true">
                      {{message.message_content}}
                    </router-link>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-header" id="message">
            <h5 class="mb-0">
              <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#messages" aria-expanded="false" aria-controls="messages">
                Messages
              </button>
            </h5>
          </div>
          <div id="messages" class="collapse" aria-labelledby="message" data-parent="#MessageBox">
            <div class="card-body">
              <div class="list-group">
                <div v-for="message in messages" :key="message.message_id">
                  <div class="list-group-item list-group-item-action"  v-if="message.message_type === false">
                    <router-link :to="'/send/'+ message.message_from" v-if="message.message_is_read === false">
                      <b>{{message.message_content}}</b>
                    </router-link>
                    <router-link :to="'/user/'+ message.message_from" v-else-if=" message.message_is_read === true">
                      {{message.message_content}}
                    </router-link>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-else class="container">
      <div class="alert alert-danger">Please login first.</div>
    </div>
    <Foot/>
  </div>
</template>

<script>
  import Navigator from '../components/Navigator'
  import Foot from '../components/Foot'

  export default {
    name: 'message',
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
        messages :{
          message: {
            message_id: null,
            message_from: null,
            message_to: null,
            message_content: null,
            message_time: null,
            message_type: null,
            message_is_read: null,
            message_from_name: null
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

<style scoped>

</style>
