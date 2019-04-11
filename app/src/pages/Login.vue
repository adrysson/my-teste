<template>
    <q-page class="grid">
        <div class="row justify-center">
            <h2>My Test</h2>
        </div>
        <div class="row justify-center">
          <form @submit.prevent="submit">
            <q-card>
                <q-card-title align="center">
                    Formulário de login
                </q-card-title>
                <q-card-separator />
                <q-card-main>
                    <div class="row justify-center">
                        <q-input v-model="form.username" class="q-mr-md q-ml-md" float-label="Nome de usuário"/>
                    </div>
                    <div class="row justify-center">
                        <q-input v-model="form.password" type="password" class="q-mr-md q-ml-md" float-label="Senha"/>
                    </div>
                </q-card-main>
                <q-card-separator />
                <q-card-actions>
                    <q-btn label="Entrar" :loading="loading" type="submit" color="green" />
                    <router-link to="/">
                        <q-btn label="Voltar" color="grey" />
                    </router-link>
                </q-card-actions>
            </q-card>
          </form>
        </div>
    </q-page>
</template>

<script>
export default {
  name: 'Login',
  data () {
    return {
      loading: false,
      form: {
        username: '',
        password: ''
      }
    }
  },
  methods: {
    submit () {
      this.loading = true
      this.$axios.post(`/v1/users/login`, this.form)
        .then(response => {
          localStorage.setItem('myteste@token', response.data.token)
          this.$store.commit('user/setUserId', response.data.user.id)
          this.$router.push('/perfil')
        })
        .catch((xhr) => {
          this.$q.notify({
            color: 'negative',
            position: 'top',
            message: xhr.response.data,
            icon: 'report_problem'
          })
        })
        .then(() => {
          this.loading = false
        })
    }
  }
}
</script>

<style>
</style>
