<template>
    <q-page class="grid">
        <div class="row justify-center">
            <h2>My Test</h2>
        </div>
        <div class="row justify-center">
            <q-card>
                <q-card-title align="center">
                    Escolha uma das opções
                </q-card-title>
                <q-card-separator />
                <q-card-main>
                  <div class="row justify-center">
                      <q-btn label="Entrar" color="primary" />
                  </div>
                  <br>
                  <div class="row justify-center">
                      <q-btn label="Realizar cadastro" color="secondary" />
                  </div>
                </q-card-main>
            </q-card>
        </div>
    </q-page>
</template>

<style>
</style>

<script>
export default {
  name: 'PageIndex'
}
</script>
