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
        to="/panel"
      >
        <v-icon>mdi-account-tie</v-icon>
      </v-btn>

      <v-spacer></v-spacer>

      <v-dialog
          v-model="dialog"
          fullscreen
          hide-overlay
          transition="dialog-top-transition"
      >
        <template v-slot:activator="{ on, attrs }">
          <v-btn
              dense
              elevation="0"
              color="transparent"
              v-bind="attrs"
              v-on="on"
          >
            <v-icon class="mr-4">mdi-shopping</v-icon>
            {{ cart.qtd }} (R${{ parseFloat(cart.total).toFixed(2).replace(',', '').replace('.', ',') }})
          </v-btn>
        </template>
        <v-card>
          <v-toolbar
              dark
              color="#1e1e1e"
          >
            <v-btn
                icon
                dark
                @click="attCart()"
            >
              <v-icon>mdi-close</v-icon>
            </v-btn>
            <v-toolbar-title>Pedido</v-toolbar-title>
          </v-toolbar>

          <v-container>
            <div class="table-responsive basket mt-5" v-if="items.length > 0">
              <table class="table table-borderless basket-items">
                <thead class="font-weight-bold">
                <tr>
                  <td style="width: 10%"></td>
                  <td style="width: 30%"></td>
                  <td style="width: 30%"></td>
                  <td style="width: 30%"></td>
                  <td style="width: 30%"></td>
                </tr>
                </thead>
                <tbody>
                <tr style="align-items: center;" v-for="(item, index) in items" :key="index">
                  <td>
                    <v-img width="65" :src="item.file"></v-img>
                  </td>
                  <td class="pl-3">
                    <h6 class="text-h6 font-weight-bold">
                      {{ item.name }}
                      <small class="d-block font-weight-regular" style="line-height: 21px">Porções</small>
                    </h6>
                  </td>
                  <td style="padding-top: 10px">
                    <v-text-field
                        label="Quantidade"
                        color="none"
                        v-model="item.qtd"
                        :disabled="true"
                        @change="changeQtd(item.id, item.qtd)"
                        type="number"
                    ></v-text-field>
                  </td>
                  <td>
                    <h5 class="font-weight-bold text-center">
                      R${{ parseFloat(item.vaun).toFixed(2).replace(',', '').replace('.', ',') }}
                      <small class="d-block">Valor unitário</small>
                    </h5>
                  </td>
                  <td>
                    <h5 class="font-weight-bold text-center">
                      R${{ (parseFloat(item.vaun) * item.qtd).toFixed(2).replace(',', '').replace('.', ',') }}
                      <small class="d-block">Valor total</small>
                    </h5>
                  </td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                  <td colspan="4"></td>
                  <td  style="border-top: 1px solid; border-color: #1e1e1e !important">
                    <h5 class="font-weight-bold text-center pt-4">
                      R${{ parseFloat(cart.total).toFixed(2).replace(',', '').replace('.', ',') }}
                      <small class="d-block">Valor total</small>
                    </h5>
                  </td>
                </tr>
                </tfoot>
              </table>
            </div>

            <div class="text-center mt-10" v-else>
              <h4 class="text-h4">
                <v-icon color="#1e1e1e" class="d-block w-100 mb-4" size="108">mdi-emoticon-frown-outline</v-icon>
                Você ainda não adicionou nada ao seu pedido!
              </h4>
            </div>
          </v-container>
        </v-card>
      </v-dialog>
    </v-app-bar>

    <v-main id="main-content" style="padding: 0 !important">
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
  name: "primary",
  data: () => ({
    dialog: false,
    cart: {
      qtd: 0,
      total: 0.00
    },
    items: [],
  }),
  methods: {
    attCart: function () {
      this.items = JSON.parse(localStorage.getItem('cart'))
      if (!this.items) { this.items = [] }
      this.cart = JSON.parse(localStorage.getItem('cartTotal'))
      if (!this.cart) {
        this.cart = {
          qtd: 0,
          total: 0.00
        }
      }
      this.cart.qtd = 0
      this.cart.total = 0
      let i = 0
      for (i; i < this.items.length; i++) {
        this.cart.qtd += parseInt(this.items[i].qtd)
        this.cart.total += parseFloat(this.items[i].vaun) * parseInt(this.items[i].qtd)
      }
      localStorage.setItem('cartTotal', JSON.stringify(this.cart))
      this.dialog = false
    }
  },
  created () {
    this.attCart()
  }
}
</script>

<style>

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

aside#footer section#footer-content {
  padding-top: 15px;
  padding-bottom: 15px;
  font-weight: bold;
  text-align: center;
}

</style>