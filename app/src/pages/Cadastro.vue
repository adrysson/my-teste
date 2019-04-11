<template>
    <q-page class="grid">
        <div class="row justify-center">
            <h2>My Test</h2>
        </div>
        <div class="row justify-center">
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
                    <q-btn label="Realizar cadastro" @click="submit" color="green" />
                </q-card-actions>
            </q-card>
        </div>
    </q-page>
</template>

<script>
export default {
  name: 'Cadastro',
  data () {
    return {
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
      this.$q.loading.show()
      this.$axios.post(`/v1/users`)
        .then(response => {
          alert('voltou')
        })
        .catch(() => {
          this.$q.notify({
            color: 'negative',
            position: 'top',
            message: 'Não foi possível carregar os dados',
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
