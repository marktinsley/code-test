<template>
  <div class="q-pa-md" style="max-width: 400px">

    <q-form
      @submit="onSubmit"
      @reset="onReset"
      class="q-gutter-md"
    >
      <q-input
        filled
        v-model="username"
        label="Email"
        lazy-rules
        :rules="[ val => val && val.length > 0 || 'Please type something']"
      />

      <q-input
        filled
        type="password"
        v-model="password"
        label="Password"
        lazy-rules
        :rules="[ val => val && val.length > 0 || 'Please type something']"
      />

      <q-banner v-if="loginMessage" v-text="loginMessage" rounded class="bg-red text-white"/>

      <div>
        <q-btn label="Submit" type="submit" color="primary"/>
        <q-btn label="Reset" type="reset" color="primary" flat class="q-ml-sm"/>
      </div>
    </q-form>
  </div>
</template>

<script>
import { mapActions, mapState } from 'vuex'

export default {

  data () {
    return {
      username: null,
      password: null
    }
  },

  computed: {
    ...mapState('auth', ['loginMessage'])
  },

  methods: {
    ...mapActions('auth', ['login']),

    async onSubmit () {
      await this.login({ username: this.username, password: this.password })
      await this.$router.push('/')
    },

    onReset () {
      this.username = null
      this.password = null
    }
  }
}
</script>
