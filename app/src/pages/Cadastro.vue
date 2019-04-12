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
                    <q-field :error-label="error.name" class="row justify-center">
                        <q-input v-model="form.name" class="q-mr-md q-ml-md" @blur="$v.form.name.$touch" :error="errors.form.name.error || $v.form.name.$error" float-label="Nome completo"/>
                    </q-field>
                    <q-field :error-label="error.email" class="row justify-center">
                        <q-input v-model="form.email" class="q-mr-md q-ml-md" type="email" @blur="$v.form.email.$touch" :error="errors.form.email.error || $v.form.email.$error" float-label="E-mail"/>
                    </q-field>
                    <q-field :error-label="error.username" class="row justify-center">
                        <q-input v-model="form.username" class="q-mr-md q-ml-md" @blur="$v.form.username.$touch" :error="errors.form.username.error || $v.form.username.$error" float-label="Nome de usuário"/>
                    </q-field>
                    <q-field :error-label="error.password" class="row justify-center">
                        <q-input v-model="form.password" type="password" class="q-mr-md q-ml-md" @blur="$v.form.password.$touch" :error="errors.form.password.error || $v.form.password.$error" float-label="Senha"/>
                    </q-field>
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
import { required, email } from 'vuelidate/lib/validators'
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
      },
      error: {
        name: '',
        email: '',
        username: '',
        password: ''
      },
      errors: {
        form: {
          name: {
            error: false
          },
          email: {
            error: false
          },
          username: {
            error: false
          },
          password: {
            error: false
          }
        }
      },
      errorMessages: {
        required: 'Este campo é obrigatório',
        email: 'Informe um e-mail válido'
      }
    }
  },
  validations: {
    form: {
      name: { required },
      email: { required, email },
      username: { required },
      password: { required }
    }
  },
  methods: {
    submit () {
      this.removeErrors()
      this.$v.form.$touch()
      if (this.$v.form.$error) {
        this.checkErrors(this.$v.form)
        this.$q.notify({
          color: 'negative',
          position: 'top',
          message: 'Preencha os campos corretamente',
          icon: 'report_problem'
        })
      } else {
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
            Object.keys(xhr.response.data).forEach(field => {
              Object.keys(xhr.response.data[field]).forEach(error => {
                this.errorMessages[error] = xhr.response.data[field][error]
                this.setError(field, error)
              })
            })
          })
          .then(() => {
            this.loading = false
          })
      }
    },
    checkErrors (fields) {
      Object.keys(this.form).forEach(field => {
        if (fields[field].$error) {
          Object.keys(fields[field].$params).forEach(param => [
            this.setError(field, param)
          ])
        }
      })
    },
    setError (field, param) {
      this.error[field] = this.getErrorMessage(param)
      console.log(this.errors.form)
      this.errors.form[field].error = true
    },
    getErrorMessage (param) {
      return this.errorMessages[param]
    },
    removeErrors () {
      Object.keys(this.form).forEach(field => {
        this.$v.form[field].error = this.errors.form[field].error = false
        this.error[field] = ''
      })
    }
  }
}
</script>

<style>
</style>
