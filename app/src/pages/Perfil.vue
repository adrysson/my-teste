<template>
    <q-page class="grid">
        <div class="row justify-center">
            <h2>My Test</h2>
        </div>
        <div class="row justify-center">
            <q-card>
                <q-card-title align="center">
                    Perfil do usuário
                </q-card-title>
                <q-card-separator />
                <q-card-main>
                </q-card-main>
            </q-card>
        </div>
    </q-page>
</template>

<script>
export default {
  name: 'Perfil',
  data () {
    return {
      user: {
        name: '',
        email: '',
        username: '',
        password: '',
        active: false
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
          console.log(response)
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
          this.$q.loading.hide()
        })
    }
  }
}
</script>

<style>
</style>
