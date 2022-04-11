<template>
  <panelTemplate>
    <aside id="login">
      <section id="login-content">
        <v-container>
          <v-row justify="center" align="center">
            <v-col lg="4" md="4">
              <v-card max-width="600">
                <v-card-title>Entrar</v-card-title>
                <v-card-text>
                  <v-form
                      ref="form"
                      v-model="valid"
                      lazy-validation
                  >
                    <v-text-field
                        label="Email"
                        v-model="data.user.text"
                        :rules="data.user.validation"
                        type="email"
                        color="none"
                        required
                    ></v-text-field>

                    <v-text-field
                        label="Senha"
                        v-model="data.password.text"
                        :rules="data.password.validation"
                        color="none"
                        type="password"
                        required
                    ></v-text-field>

                    <v-spacer></v-spacer>

                    <v-btn
                        color="success"
                        outlined
                        :disabled="!valid"
                        @click="validate"
                    >{{ (this.valid ? 'Autenticar' : 'Verificando...') }}</v-btn>
                  </v-form>
                </v-card-text>
              </v-card>
            </v-col>
          </v-row>
        </v-container>
      </section>
    </aside>
  </panelTemplate>
</template>

<script>
import panelTemplate from '@/templates/panelTemplate'

export default {
  name: "Login",
  components: {
    panelTemplate
  },
  data: () => ({
    valid: true,
    data: {
      user: {
        text: '',
        validation: [
          v => !!v || 'Este campo deve ser preenchido',
          v => /.+@.+\..+/.test(v) || 'E-mail invalido',
        ],
      },
      password: {
        text: '',
        validation: [
          v => !!v || 'Este campo deve ser preenchido',
        ],
      }
    }
  }),
  methods: {
    validate: function () {
      this.valid = false
      if (this.$refs.form.validate()) {
        this.$http.post(this.$url + 'login', {
          email: this.data.user.text,
          password: this.data.password.text
        }, {}).then(response => {
          if (("type" in response.data)) {
            this.$swal.fire({
              title: 'Opa!',
              html: response.data.message,
              icon: 'error'
            })
          } else {
            if (parseInt(response.data.status) === 200) {
              let timerInterval
              this.$swal.fire({
                title: response.data.message,
                html: 'Você será redirecinado em <b></b> milesegundos.',
                timer: 2000,
                timerProgressBar: true,
                didOpen: () => {
                  this.$swal.showLoading()
                  const b = this.$swal.getHtmlContainer().querySelector('b')
                  timerInterval = setInterval(() => {
                    b.textContent = this.$swal.getTimerLeft()
                  }, 100)
                },
                willClose: () => {
                  clearInterval(timerInterval)
                }
              }).then((result) => {
                if (result.dismiss === this.$swal.DismissReason.timer) {
                  localStorage.setItem('userdata', JSON.stringify(response.data.user))
                  this.$router.go()
                }
              })
            } else {
              this.$swal.fire({
                title: 'Opa!',
                html: response.data.message,
                icon: 'error'
              })
            }
          }
          this.valid = true
        })
      } else {
        this.valid = true
      }
    }
  },
  created () {
    if (this.$user) { this.$router.push('/panel') }
  }
}
</script>

<style scoped>

section#login-content {
  background: #f8f8f8;
  padding-top: 3%;
  padding-bottom: 4%;
}
section#login-content .row {
  min-height: 600px;
}

</style>