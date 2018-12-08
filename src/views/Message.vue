<template>
  <div class="message">
    <Navigator :session="session"/>
    <div class="accordion" id="MessageBox">
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
            Anim pariatur cliche reprehenderit, enim eiusmod high life accus1
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
            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
          </div>
        </div>
      </div>
    </div>
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
    beforeMount () {
      const self = this
    }
  }
</script>

<style scoped>

</style>
