<template>
  <div class="home-component">
    <div class="boards" v-for="board in boards" :key="board.board_id">
      <div class="container">
        <div class="row">
          <div class="col">
            <h2>{{board.board_name}}</h2>
            <p>{{board.board_intro}}</p>
            <router-link :to="'/board/' + board.board_id" class="btn btn-secondary float-right" role="button">View {{board.board_name}} &raquo;</router-link>
          </div>
        </div>
        <hr>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'home-component',
    props: {
      boards: {
        board: {
          board_id: null,
          board_name: null,
          board_intro: null
        }
      }
    }
  }
</script>
