<template>
  <div class="not-found">
    <Navigator :session="session" />
    <div class="text-center">
      <h1>404</h1>
      <h2>Page Not Found!</h2>
    </div>
    <Foot />
  </div>
</template>

<script>
  import Navigator from '../components/Navigator'
  import Foot from '../components/Foot'

  export default {
    name: 'not-found',
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
    }
  }
</script>

<style scoped>
  .text-center {
    height: 100%;
    padding-top: 240px;
    padding-bottom: 240px;
  }
</style>
