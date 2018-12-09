<template>
  <div class="board-jumbotron">
    <div class="jumbotron">
      <div class="container">
        <h1 v-if="exist()" class="display-3">{{info.board_name}}</h1>
        <h1 v-else class="display-3">Create new board</h1>
        <p v-if="exist()">{{info.board_intro}}</p>
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
      },
      action: null
    },
    methods: {
      match() {
        return (this.$route.path.indexOf('/post') === -1 && this.$route.path.indexOf('/update') === -1 && this.exist())
      },
      exist() {
        return (this.$route.path.indexOf('/create') === -1)
      }
    }
  }
</script>
