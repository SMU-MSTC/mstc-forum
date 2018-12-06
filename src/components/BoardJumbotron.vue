<template>
  <div class="board-jumbotron">
    <div class="jumbotron">
      <div class="container">
        <h1 class="display-3">{{info.board_name}}</h1>
        <p>{{info.board_intro}}</p>
        <div v-if="match()">
          <p>
            <router-link :to="'/post/' + info.board_id" class="btn btn-primary btn-lg" role="button">Post new thread &raquo;</router-link>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'BoardJumbotron',
    props: {
      info: {
        board_id: null,
        board_name: null,
        board_intro: null
      }
    },
    methods: {
      match () {
        return this.$route.path.indexOf('/post') === -1
      }
    }
  }
</script>
