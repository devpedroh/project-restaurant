<template>
  <v-app id="inspire">
    <v-app-bar
        color="#e0e0e0"
        elevation="8"
        app
    >

      <v-btn
          dense
          elevation="0"
          color="transparent"
          class="mr-4"
          @click="logout"
          v-if="this.$user"
      >
        <v-icon>mdi-logout-variant</v-icon>
      </v-btn>
      <v-btn
          dense
          elevation="0"
          color="transparent"
          to="/panel"
      >
        <v-icon>mdi-account-tie</v-icon>
      </v-btn>

      <v-spacer></v-spacer>

      <v-btn
          dense
          elevation="0"
          color="transparent"
      >
        <v-icon class="mr-4">mdi-shopping</v-icon> 0 (R$0,00)
      </v-btn>
    </v-app-bar>

    <v-main id="main-content" style="padding: 0 !important">
      <aside id="header">
        <section id="header-content">
          <v-container>
            <v-row align="center" align-content="center" style="height: 100%">
              <v-col lg="2" md="2" align-self="center">
                <v-icon color="#f8f8f8">mdi-account-tie</v-icon>
              </v-col>
              <v-col lg="10" md="10">
                <h2 class="text-h2 font-weight-bold">Controle Interno</h2>
              </v-col>
            </v-row>
          </v-container>
        </section>
      </aside>

      <slot />

      <aside id="footer">
        <section id="footer-content">
          <v-container>
            <div>
              Restaurente
              &copy;
              <span class="font-weight-medium">Todos os direitos reservados</span>
            </div>
          </v-container>
        </section>
      </aside>
    </v-main>
  </v-app>
</template>

<script>
export default {
  name: "panelTemplate",
  data: () => ({  }),
  methods: {
    activeNotification: function() {
      if (Notification.permission !== "granted") {
        Notification.requestPermission();
        let newwindow = window.open(
            this.$url + "notifications?token=" + this.$user.token,
            "Ativar notificações",
            "height=500,width=500"
        );
        if (window.focus) {
          newwindow.focus();
        }
        return false;
      }
    },
    logout: function () {

      if (this.$user) {
        this.$http.post(this.$url + 'logout', {

        }, { 'headers': { 'Authorization': 'Bearer ' + this.$user.token } }).then(response => {
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
                  localStorage.clear('userdata')
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
        })
      }
    }
  },
  created () {
    if (this.$user) { this.activeNotification() }
  }
}
</script>

<style scoped>

.v-main {
  background: #e0e0e0;
  color: #1e1e1e !important;
}

.theme--light.v-stepper .v-stepper__step .v-stepper__step__step{
  background: rgba(0, 0, 0, 0.38) !important
}

.v-application .white--text {
  color: #1e1e1e !important;
  caret-color: #1e1e1e !important;
}

aside#header section#header-content {
  background: #1e1e1e;
  color: #f8f8f8;
}
aside#header section#header-content > .container {
  height: 250px;
  position: relative;
}
aside#header section#header-content > .container .v-icon {
  font-size: 12em
}

aside#footer section#footer-content {
  padding-top: 15px;
  padding-bottom: 15px;
  font-weight: bold;
  text-align: center;
}

</style>