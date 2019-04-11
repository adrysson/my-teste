<template>
    <q-page class="grid">
        <div class="row justify-center">
            <h2>My Test</h2>
        </div>
        <div class="row justify-center">
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
                    <q-btn label="Entrar" @click="submit" color="green" />
                    <router-link to="/">
                        <q-btn label="Voltar" color="grey" />
                    </router-link>
                </q-card-actions>
            </q-card>
        </div>
    </q-page>
</template>

<script>
export default {
  name: 'Login',
  data () {
    return {
      form: {
        username: '',
        password: ''
      }
    }
  },
  methods: {
    submit () {
      this.$q.loading.show()
      this.$axios.post(`/v1/users/login`, this.form)
        .then(response => {
          this.$q.notify({
            color: 'positive',
            position: 'top',
            message: response.data,
            icon: 'check'
          })
          this.$router.push('/perfil')
        })
        .catch((xhr) => {
          let errors = Object.keys(xhr.response.data).map(field => {
            return Object.keys(xhr.response.data[field]).map(error => {
              return `${field}: ${xhr.response.data[field][error]}`
            })
          }).join(', ')
          this.$q.notify({
            color: 'negative',
            position: 'top',
            message: errors,
            icon: 'report_problem'
          })
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
