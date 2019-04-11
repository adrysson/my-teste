<template>
    <q-page class="grid">
        <div class="row justify-center">
            <h2>My Test</h2>
        </div>
        <div class="row justify-center">
          <form @submit.prevent="submit">
            <q-card>
                <q-card-title align="center">
                    Formulário de cadastro
                </q-card-title>
                <q-card-separator />
                <q-card-main>
                    <div class="row justify-center">
                        <q-input v-model="form.name" class="q-mr-md q-ml-md" float-label="Nome completo"/>
                    </div>
                    <div class="row justify-center">
                        <q-input v-model="form.email" class="q-mr-md q-ml-md" float-label="E-mail"/>
                    </div>
                    <div class="row justify-center">
                        <q-input v-model="form.username" class="q-mr-md q-ml-md" float-label="Nome de usuário"/>
                    </div>
                    <div class="row justify-center">
                        <q-input v-model="form.password" type="password" class="q-mr-md q-ml-md" float-label="Senha"/>
                    </div>
                </q-card-main>
                <q-card-separator />
                <q-card-actions>
                    <q-btn label="Realizar cadastro" :loading="loading" type="submit" color="green" />
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
  name: 'Cadastro',
  data () {
    return {
      loading: false,
      form: {
        name: '',
        email: '',
        username: '',
        password: ''
      }
    }
  },
  methods: {
    submit () {
      this.loading = true
      this.$axios.post(`/v1/users`, this.form)
        .then(response => {
          this.$q.notify({
            color: 'positive',
            position: 'top',
            message: response.data,
            icon: 'check'
          })
          this.$router.push('/entrar')
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
          this.loading = false
        })
    }
  }
}
</script>

<style>
</style>
