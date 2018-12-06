<template>
  <div class="logout"></div>
</template>

<script>
  export default {
    name: 'logout',
    beforeMount () {
      $.get(api + '/logout')
      this.$emit('update')
      this.$router.push('/')
    }
  }
</script>
