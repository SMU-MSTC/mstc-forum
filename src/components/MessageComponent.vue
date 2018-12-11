<template>
  <div class="jumbotron message-component">
    <div class="container" v-if="session.user_id">
      <div class="accordion" id="message-box">
        <div class="card">
          <div class="card-header" id="sys-notice">
            <button class="btn btn-link btn-lg" type="button" data-toggle="collapse" data-target="#notice" aria-expanded="true" aria-controls="notice">System Notice</button>
          </div>
          <div id="notice" class="collapse show" aria-labelledby="sys-notice" data-parent="#message-box">
            <div class="card-body">
              <div class="list-group">
                <div v-for="message in messages" :key="message.message_id">
                  <div v-if="message.message_type" class="list-group-item list-group-item-action">
                    <div v-if="!message.message_is_read">
                      <p><b>{{message.message_content}}</b></p>
                      <p><small>From
                        <router-link :to="'/user/'+ message.message_from">{{message.message_from_user_name}}</router-link>
                        In thread
                        <router-link :to="'/read/'+ message.message_from_thread_id">{{message.message_from_thread_title}}</router-link>
                      </small></p>
                    </div>
                    <div v-if="message.message_is_read">
                      <p>{{message.message_content}}</p>
                      <p><small>From
                        <router-link :to="'/user/'+ message.message_from">{{message.message_from_user_name}}</router-link>
                        In thread
                        <router-link :to="'/read/'+ message.message_from_thread_id">{{message.message_from_thread_title}}</router-link>
                      </small></p>
                    </div>
                  </div>
                  <div v-else-if="!message.message_type && message.message_from_user_is_admin" class="list-group-item list-group-item-action">
                    <div v-if="!message.message_is_read">
                      <p><b>{{message.message_content}}</b></p>
                      <p><small>From
                        <router-link :to="'/user/'+ message.message_from">{{message.message_from_user_name}}</router-link>
                        </small></p>
                    </div>
                    <div v-else-if="message.message_is_read">
                      <p>{{message.message_content}}</p>
                      <p><small>From
                        <router-link :to="'/user/'+ message.message_from">{{message.message_from_user_name}}</router-link>
                      </small></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-header" id="message">
            <button class="btn btn-link btn-lg" type="button" data-toggle="collapse" data-target="#messages" aria-expanded="false" aria-controls="messages">Messages</button>
          </div>
          <div id="messages" class="collapse" aria-labelledby="message" data-parent="#message-box">
            <div class="card-body">
              <div class="list-group">
                <div v-for="message in messages" :key="message.message_id">
                  <div v-if="!message.message_type && !message.message_from_user_is_admin" class="list-group-item list-group-item-action">
                    <div v-if="!message.message_is_read">
                      <p><b>{{message.message_content}}</b></p>
                      <p><small>From
                        <router-link :to="'/user/'+ message.message_from">{{message.message_from_user_name}}</router-link>
                      </small></p>
                    </div>
                    <div v-if="message.message_is_read">
                      <p>{{message.message_content}}</p>
                      <p><small>From
                        <router-link :to="'/user/'+ message.message_from">{{message.message_from_user_name}}</router-link>
                      </small></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-else class="container">
      <div class="alert alert-danger">Please log in first.</div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'message-component',
    props: {
      session: {
        user_id: null,
        user_is_admin: null,
        user_name: null
      },
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
          message_from_user_is_admin: null,
          message_from_thread_id: null,
          message_from_thread_title: null
        }
      }
    }
  }
</script>
