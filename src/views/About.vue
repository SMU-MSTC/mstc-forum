<template>
  <div class="about">
    <Navigator :session="session" />
    <div class="jumbotron">
      <div class="container">
        <h1 class="display-3">Forum Demo</h1>
        <p>This is a simple demo of forum, written in PHP and Vue.js.</p>
        <ul class="list-group">
          <li class="list-group-item">Frontend: Vue.js (Vue CLI & Vue Router)</li>
          <li class="list-group-item">Backend: PHP</li>
          <li class="list-group-item">Database: PostgreSQL</li>
        </ul>
        <hr>
        <p>For more information, please view <a href="https://github.com/SMU-MSTC/mstc-forum">https://github.com/SMU-MSTC/mstc-forum</a> .</p>
      </div>
    </div>
    <Foot />
  </div>
</template>

<script>
  import Navigator from '../components/Navigator'
  import Foot from '../components/Foot'

  export default {
    name: 'about',
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
