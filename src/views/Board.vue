<template>
  <div class="board">
    <Navigator :session="session" />
    <BoardJumbotron :info="board.info" :loaded="loaded" />
    <BoardComponent :session="session" :info="board.info" :threads="board.threads" @reload="reload" />
    <Foot />
  </div>
</template>

<script>
  import Navigator from '../components/Navigator'
  import BoardJumbotron from '../components/BoardJumbotron'
  import BoardComponent from '../components/BoardComponent'
  import Foot from '../components/Foot'

  export default {
    name: 'board',
    components: {
      Navigator,
      BoardJumbotron,
      BoardComponent,
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
        board: {
          info: null,
          threads: null,
        },
        loaded: false
      }
    },
    methods: {
      reload() {
        const self = this
        const board_id = this.$route.params.board_id
        $.get(api + '/board?board_id=' + board_id, (data) => {
          self.board = data.board
          self.loaded = true
        })
      }
    },
    beforeMount() {
      this.reload()
    }
  }
</script>
