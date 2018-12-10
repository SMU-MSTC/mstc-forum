<template>
  <div class="favorites">
    <Navigator :session="session" />
    <div class="favorites-page">
      <div class="container">
        <div class="row">
          <div class="col">
            <h1 v-if="favorites">Your favorites</h1>
            <div v-else-if="!session.user_id" class="alert alert-danger">Please login first.</div>
            <h1 v-if="!favorites">You have no favorites.</h1>
            <p v-if="!favorites">Maybe your favorites have been deleted.</p>
          </div>
        </div>
      </div>
    </div>
    <div class="favorite" v-for="favorite in favorites" :key="favorite.thread_id">
      <div class="container">
        <div class="row">
          <div class="col">
            <h2>{{favorite.thread_title}}</h2>
            <p class="float-right">
              <router-link :to="'/user/' + favorite.user_id">{{favorite.user_name}}</router-link>
              {{favorite.thread_time}}
            </p>
          </div>
        </div>
        <div class="row">
          <div class="col">
            <router-link :to="'/board/' + favorite.board_id" class="btn btn-default" role="button">&raquo; Go to {{favorite.board_name}}</router-link>
            <p class="float-right">
              <button v-if="favorite.favorite && session.user_id" v-on:click="favor(favorite.thread_id)" class="btn btn-warning">Unfavorite</button>
              <router-link :to="'/read/' + favorite.thread_id" class="btn btn-secondary" role="button">View &raquo;</router-link>
            </p>
          </div>
        </div>
        <hr>
      </div>
    </div>
    <Foot />
  </div>
</template>

<script>
  import Navigator from '../components/Navigator'
  import Foot from '../components/Foot'

  export default {
    name: 'favorites',
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
        favorites: {
          favorite: {
            thread_id: null,
            board_id: null,
            board_name: null,
            user_id: null,
            user_name: null,
            thread_title: null,
            thread_content: null,
            thread_time: null,
            favorite: null
          }
        }
      }
    },
    methods: {
      favor(thread_id) {
        const self = this
        $.post(api + '/favorite', { thread_id: thread_id }).done(() => {
          self.reload()
        })
      },
      reload() {
        const self = this
        $.get(api + '/favorite', (data) => {
          self.favorites = data.favorites
        })
      }
    },
    beforeMount() {
      const self = this
      $.get(api + '/favorite', (data) => {
        self.favorites = data.favorites
      })
    }
  }
</script>

<style scoped>
  .favorites-page {
    padding-top: 120px;
    padding-bottom: 120px;
    background-color: #e9ecef;
  }

  .favorite {
    padding-top: 30px;
    padding-bottom: 30px;
  }

  .favorite .btn {
    margin-left: 5px;
    margin-right: 5px;
  }
</style>
