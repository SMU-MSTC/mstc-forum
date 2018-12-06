<template>
  <div class="navigator">
    <div class="nav navbar navbar-expand-md navbar-dark fixed-top bg-dark">
      <router-link to="/" class="navbar-brand">Forum Demo</router-link>
      <ul v-if="session.user_id === null" class="navbar-nav ml-auto">
        <li class="nav-item">
          <router-link to="/register" class="nav-link">Register</router-link>
        </li>
        <li class="nav-item">
          <router-link to="/login" class="nav-link">Login</router-link>
        </li>
      </ul>
      <ul v-else class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Welcome, {{session.user_name}}! </a>
          <div class="dropdown-menu" aria-labelledby="dropdown">
            <router-link :to="'/user/' + session.user_id" class="dropdown-item">Update information</router-link>
            <router-link :to="'/favorites/' + session.user_id" class="dropdown-item">View favorites </router-link>
            <router-link :to="'/message/' + session.user_id" class="dropdown-item">View messages</router-link>
          </div>
        </li>
        <li class="nav-item">
          <router-link to="/logout" class="nav-link">Logout</router-link>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'navigator',
    props: {
      session: {
        user_id: null,
        user_is_admin: null,
        user_name: null
      }
    }
  }
</script>
