<template>
  <div id="app">
    <router-view :session="session" @update="update"/>
  </div>
</template>

<script>
  export default {
    name: 'app',
    data() {
      return {
        session: null
      }
    },
    beforeMount() {
      const self = this
      $.get(api + '/', (data) => {
        self.session = data.session
      })
    },
    methods: {
      update() {
        const self = this
        $.get(api + '/', (data) => {
          self.session = data.session
        })
      }
    }
  }
</script>

<style scoped>
  #app {
    padding-top: 3.5rem;
  }
</style>
