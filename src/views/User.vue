<template>
  <div class="user">
    <Navigator :session="session" />
    <UserComponent :session="session" :user="user" :loaded="loaded" @reload="reload" />
    <Foot />
  </div>
</template>

<script>
  import Navigator from '../components/Navigator'
  import UserComponent from '../components/UserComponent'
  import Foot from '../components/Foot'

  export default {
    name: 'user',
    components: {
      Navigator,
      UserComponent,
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
        user: {
          user_id: null,
          user_name: null,
          user_is_admin: null,
          user_gender: null,
          user_birth: null,
          user_email: null,
          user_tel: null,
          user_intro: null
        },
        loaded: false
      }
    },
    watch: {
      '$route.params'() {
        this.reload()
      }
    },
    methods: {
      reload() {
        const self = this
        const user_id = this.$route.params.user_id
        fetch(api + '/user?user_id=' + user_id)
          .then((response) => {
            return response.json()
          })
          .then((data) => {
            self.user = data.user
            self.loaded = true
          })
      }
    },
    beforeMount() {
      this.reload()
    }
  }
</script>

<style scoped>

</style>
