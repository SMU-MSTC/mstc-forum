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
      this.update()
    },
    methods: {
      update() {
        const self = this
        fetch(api + '/')
          .then((response) => {
            return response.json()
          })
          .then((data) => {
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
