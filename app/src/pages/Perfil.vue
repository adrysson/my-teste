<template>
    <q-page class="grid">
        <div class="row justify-center">
            <h2>My Test</h2>
        </div>
        <div class="row justify-center" v-if="!loading">
            <q-card>
                <q-card-title align="center">
                    Perfil do usuário
                </q-card-title>
                <q-card-separator />
                <q-card-main align="center">
                  <p>
                    <v-gravatar :email="user.email" />
                  </p>
                  <p>Nome completo: {{user.name}}</p>
                  <p>E-mail: {{user.email}}</p>
                  <p>Nome de usuário: {{user.username}}</p>
                </q-card-main>
            </q-card>
        </div>
    </q-page>
</template>

<script>
import Gravatar from 'vue-gravatar'
export default {
  name: 'Perfil',
  components: {
    'v-gravatar': Gravatar
  },
  data () {
    return {
      loading: true,
      user: {
        name: '',
        email: '',
        username: ''
      }
    }
  },
  mounted () {
    const userId = this.$store.getters['user/getUserId']
    this.getUser(userId)
  },
  methods: {
    getUser (id) {
      this.$q.loading.show()
      this.$axios.get(`/v1/users/${id}`)
        .then(response => {
          this.user = response.data.user
        })
        .catch((xhr) => {
          this.$q.notify({
            color: 'negative',
            position: 'top',
            message: xhr.response.data.message,
            icon: 'report_problem'
          })
          this.$router.push('/entrar')
        })
        .then(() => {
          this.loading = false
          this.$q.loading.hide()
        })
    }
  }
}
</script>

<style>
</style>
