<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar>
        <q-toolbar-title class="q-py-md">
          <q-btn to="/" flat label="Our Products"/>
        </q-toolbar-title>

        <q-btn v-if="isSignedIn" flat label="Logout" @click="logUserOut"/>
        <q-btn v-else to="/login" flat label="Login"/>
      </q-toolbar>
    </q-header>

    <q-page-container>
      <router-view/>
    </q-page-container>
  </q-layout>
</template>

<script>
import { defineComponent } from 'vue'
import { mapActions, mapGetters } from 'vuex'

export default defineComponent({
  name: 'MainLayout',

  computed: {
    ...mapGetters('auth', ['isSignedIn'])
  },

  methods: {
    ...mapActions('auth', ['logout']),

    logUserOut () {
      this.logout()
      this.$router.push('login')
    }
  }
})
</script>
