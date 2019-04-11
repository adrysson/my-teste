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
        password: ''
      }
    }
  }
}
</script>

<style>
</style>
