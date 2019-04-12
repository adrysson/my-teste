<template>
    <q-page class="grid">
        <div class="row justify-center">
            <h2>My Test</h2>
        </div>
        <div class="row justify-center">
            <legend align="center">{{msg}}</legend>
        </div>
    </q-page>
</template>

<script>
export default {
  name: 'Ativacao',
  data () {
    return {
      msg: 'Ativando seu usuário...'
    }
  },
  mounted () {
    this.$q.loading.show()
    this.$axios.post(`/v1/users/activate`, { hash: this.$route.params.hash })
      .then(response => {
        this.$q.notify({
          color: 'positive',
          position: 'top',
          message: 'Seu usuário foi ativado!',
          icon: 'check'
        })
        this.msg = 'Você será redirecionado para o seu perfil em breve.'
        localStorage.removeItem('mytest@url')
        localStorage.setItem('myteste@token', response.data.token)
        this.$store.commit('user/setUserId', response.data.user.id)
        this.$router.push('/perfil')
      })
      .catch((xhr) => {
        if (xhr.response.status === 400) {
          this.msg = xhr.response.data
        }

        if (xhr.response.status === 422) {
          let errors = Object.keys(xhr.response.data).map(field => {
            return Object.keys(xhr.response.data[field]).map(error => {
              return `${field}: ${xhr.response.data[field][error]}`
            })
          }).join(', ')
          this.msg = errors
        }

        this.$q.notify({
          color: 'negative',
          position: 'top',
          message: 'Não foi possível ativar seu usuário',
          icon: 'report_problem'
        })
      })
      .then(() => {
        this.$q.loading.hide()
      })
  }
}
</script>

<style>
</style>
