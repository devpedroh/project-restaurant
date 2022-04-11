<template>
  <primaryTemplate ref="primaryTemplate">
    <aside id="home">
      <section id="header">
        <v-container class="d-flex justify-center align-center flex-column text-center">
          <h3 class="white--text text-h3 font-weight-bold">
            Restaurente
            <br>
            do Zé
          </h3>
        </v-container>
      </section>

      <section id="most-wanted">
        <v-container>
          <div id="mw-title" class="text-center">
            <h4 class="text-h4 font-weight-bold white--text">
              <v-icon class="d-block w-100 mb-3" color="#f8f8f8" size="48">mdi-star-circle-outline</v-icon>
              Os mais procurados
            </h4>
          </div>

          <div id="mw-content">
            <v-row v-if="topItems !== null">
              <v-col lg="3" md="3" v-for="(titem, index) in topItems" :key="index">
                <v-card color="transparent" class="mx-auto white--text">
                  <v-img
                      height="125"
                      :src="titem.file"
                  ></v-img>

                  <v-card-title><span>{{ titem.name }}</span></v-card-title>
                  <v-card-text class="plate-description white--text">{{ titem.description }}</v-card-text>
                  <v-card-actions class="red--text font-weight-bold">
                    <span>R${{ parseFloat(titem.vaun).toFixed(2).replace(',', '').replace('.', ',') }}</span>

                    <v-spacer></v-spacer>

                    <v-dialog
                        transition="dialog-top-transition"
                        max-width="500"
                    >
                      <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            color="warning"
                            outlined
                            v-bind="attrs"
                            v-on="on"
                        >Detalhes</v-btn>
                      </template>
                      <template v-slot:default="dialog">
                        <v-card>
                          <v-card-text class="pl-0 pr-0">
                            <v-img
                                height="200"
                                :src="titem.file"
                            ></v-img>
                          </v-card-text>
                          <v-card-text class="mb-10">
                            <div class="text-h5 pb-2 font-weight-bold">{{ titem.name }}</div>
                            <div class="font-weight-bold">{{ titem.description }}</div>
                          </v-card-text>
                          <v-card-actions>
                            <div class="red--text font-weight-bold">R${{ parseFloat(titem.vaun).toFixed(2).replace(',', '').replace('.', ',') }}</div>
                            <v-spacer></v-spacer>
                            <v-btn
                                outlined
                                color="success"
                                @click="addItem(titem)"
                            >Adicionar</v-btn>
                          </v-card-actions>
                        </v-card>
                      </template>
                    </v-dialog>
                  </v-card-actions>
                </v-card>
              </v-col>
            </v-row>

            <v-row justify="center" align="center" v-else>
              <v-col lg="3" md="3" class="text-center">
                <v-progress-circular
                    :size="60"
                    :width="7"
                    color="white"
                    indeterminate
                ></v-progress-circular>
                <h5 class="white--text text-h5 font-weight-bold pt-8">Carregando...</h5>
              </v-col>
            </v-row>
          </div>
        </v-container>
      </section>

      <section id="plates">
        <v-container class="white--text pl-0 pr-0">
          <div id="plates-content">
            <v-stepper
                v-model="step"
                elevation="0"
            >
              <v-stepper-header class="elevation-0">

                <v-stepper-step
                    :complete="step > 1"
                    step="1"
                    color="transparent"
                >
                  <span class="text-h6 white--text font-weight-bold">Porções</span>
                  <small class="white--text">Faça seu pedido</small>
                </v-stepper-step>

                <v-divider></v-divider>

                <v-stepper-step
                    :complete="step > 2"
                    step="2"
                    color="transparent"
                >
                  <span class="text-h6 white--text font-weight-bold">Resumo</span>
                  <small class="white--text">Revise seu pedido</small>
                </v-stepper-step>

                <v-divider></v-divider>

                <v-stepper-step
                    :complete="step > 3"
                    step="3"
                    color="transparent"
                >
                  <span class="text-h6 white--text font-weight-bold">Finalizar</span>
                  <small class="white--text">Finalize seu pedido</small>
                </v-stepper-step>
              </v-stepper-header>

              <v-stepper-items>

                <!-- Etapa 1-->
                <v-stepper-content step="1">
                  <v-text-field
                      label="Pesquise nome ou código do produto"
                      color="none"
                      class="5"
                      v-model="search"
                      @change="getProducts"
                  ></v-text-field>

                  <div id="plates-row" class="mt-5" v-if="items.length > 0">
                    <v-row>
                      <v-col lg="3" md="3" v-for="(item, index) in items" :key="index">
                        <v-card color="transparent" class="mx-auto white--text">
                          <v-img
                              height="125"
                              :src="item.file"
                          ></v-img>

                          <v-card-title><span>{{ item.name }}</span></v-card-title>
                          <v-card-text class="plate-description white--text">{{ item.description }}</v-card-text>
                          <v-card-actions class="red--text font-weight-bold">
                            <span>R${{ parseFloat(item.vaun).toFixed(2).replace(',', '').replace('.', ',') }}</span>

                            <v-spacer></v-spacer>

                            <v-dialog
                                transition="dialog-top-transition"
                                max-width="500"
                            >
                              <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    color="warning"
                                    outlined
                                    v-bind="attrs"
                                    v-on="on"
                                >Detalhes</v-btn>
                              </template>
                              <template v-slot:default="dialog">
                                <v-card>
                                  <v-card-text class="pl-0 pr-0">
                                    <v-img
                                        height="200"
                                        :src="item.file"
                                    ></v-img>
                                  </v-card-text>
                                  <v-card-text class="mb-10">
                                    <div class="text-h5 pb-2 font-weight-bold">{{ item.name }}</div>
                                    <div class="font-weight-bold">{{ item.description }}</div>
                                  </v-card-text>
                                  <v-card-actions>
                                    <div class="red--text font-weight-bold">R${{ parseFloat(item.vaun).toFixed(2).replace(',', '').replace('.', ',') }}</div>
                                    <v-spacer></v-spacer>
                                    <v-btn
                                        outlined
                                        color="success"
                                        @click="addItem(item)"
                                    >Adicionar</v-btn>
                                  </v-card-actions>
                                </v-card>
                              </template>
                            </v-dialog>
                          </v-card-actions>
                        </v-card>
                      </v-col>
                    </v-row>

                    <v-row class="plates-footer mt-8">
                      <v-col lg="9" md="9">
                        <div id="plates-pagination" class="text-center">
                          <v-pagination
                              v-model="page"
                              :length="parseInt((total / pageSize) + 0.4)"
                              color="#C62828"
                              circle
                          ></v-pagination>
                        </div>
                      </v-col>
                      <v-col lg="3" md="3">
                        <v-btn
                            color="none"
                            outlined
                            dense
                            block
                            :disabled="!cart.length > 0"
                            @click="step = 2"
                        >
                          Finalizar pedido
                        </v-btn>
                      </v-col>
                    </v-row>
                  </div>
                  <div id="plates-loading" class="mt-5" v-else>
                    <v-row justify="center" align="center">
                      <v-col lg="3" md="3" class="text-center">
                        <v-progress-circular
                            :size="60"
                            :width="7"
                            color="none"
                            indeterminate
                        ></v-progress-circular>
                        <h5 class="text-h5 font-weight-bold pt-8">Carregando...</h5>
                      </v-col>
                    </v-row>
                  </div>
                </v-stepper-content>

                <!-- Etapa 2-->
                <v-stepper-content step="2">
                  <div class="table-responsive basket mt-5">
                    <table class="table table-borderless basket-items">
                      <thead class="font-weight-bold">
                      <tr>
                        <td style="width: 5%"></td>
                        <td style="width: 30%"></td>
                        <td style="width: 25%"></td>
                        <td style="width: 25%"></td>
                        <td style="width: 25%"></td>
                        <td style="width: 5%"></td>
                      </tr>
                      </thead>
                      <tbody>
                      <tr style="align-items: center;" v-for="(item, index) in cart" :key="index">
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
                        <td class="text-center">
                          <span data-toggle="tooltip" data-placement="right" title="Deletar produto da cesta">
                            <v-btn icon color="red" @click="delItem(item.id)">
                              <v-icon>mdi-delete-empty</v-icon>
                            </v-btn>
                          </span>
                        </td>
                      </tr>
                      </tbody>
                      <tfoot>
                      <tr>
                        <td colspan="4"></td>
                        <td  style="border-top: 1px solid; border-color: #1e1e1e !important">
                          <h5 class="font-weight-bold text-center pt-4">
                            R${{ parseFloat(cartTotal.total).toFixed(2).replace(',', '').replace('.', ',') }}
                            <small class="d-block">Valor total</small>
                          </h5>
                        </td>
                      </tr>
                      </tfoot>
                    </table>
                  </div>

                  <div class="plates-footer mt-10 text-right">

                    <v-btn
                        color="warning"
                        outlined
                        dense
                        @click="step = 1"
                        class="mr-3"
                    >
                      Adicionar mais produtos
                    </v-btn>

                    <v-btn
                        color="success"
                        outlined
                        dense
                        @click="step = 3"
                    >
                      Realizar pagamento
                    </v-btn>

                  </div>
                </v-stepper-content>

                <!-- Etapa 3-->
                <v-stepper-content step="3">
                  <h5 class="text-h5 font-weight-bold mb-8 mt-10">Antes precisamos de alguns dados</h5>

                  <v-row>
                    <v-col lg="9" md="9">
                      <v-form
                        ref="form"
                        v-model="valid"
                        lazy-validation
                      >
                        <v-text-field
                            label="Nome"
                            color="none"
                            v-model="data.name.text"
                            :rules="data.name.validation"
                        ></v-text-field>

                        <v-select
                            label="Forma de pagamento"
                            v-model="data.payment_method.text"
                            color="none"
                            :items="['Dinheiro', 'Cartão']"
                            :rules="data.payment_method.validation"
                            @change="(data.payment_method.text === 'Cartão' ? data.payment_value.text = 49.90 : data.payment_value.text = 0)"
                        ></v-select>

                        <div id="paymentMethod" v-if="data.payment_method.text !== '' && data.payment_method.text !== null">
                          <div class="payment_cash" v-if="data.payment_method.text === 'Dinheiro'">
                            <v-text-field
                                label="Valor"
                                v-model="data.payment_value.text"
                                :rules="data.payment_value.validation"
                                color="none"
                                type="number"
                            >
                              <span slot="prepend">R$</span>
                            </v-text-field>
                          </div>
                        </div>

                        <v-textarea
                            label="Observações"
                            v-model="data.obs.text"
                            color="none"
                        ></v-textarea>
                      </v-form>
                    </v-col>
                    <v-col lg="3" md="3" class="text-right">
                      <v-text-field
                        label="Total"
                        :disabled="true"
                        :value="cartTotal.total.toFixed(2).replace(',', '').replace('.', ',')"
                      >
                        <span slot="prepend">R$</span>
                      </v-text-field>

                      <v-text-field
                          label="Pagamento"
                          :disabled="true"
                          :value="parseFloat(data.payment_value.text).toFixed(2).replace(',', '').replace('.', ',')"
                      >
                        <span slot="prepend">R$</span>
                      </v-text-field>

                      <v-text-field
                          v-if="data.payment_method.text === 'Dinheiro'"
                          label="Troco"
                          :disabled="true"
                          :value="returnValue().toFixed(2).replace(',', '').replace('.', ',')"
                      >
                        <span slot="prepend">R$</span>
                      </v-text-field>
                    </v-col>
                  </v-row>
                  <div class="plates-footer mt-10 text-right">

                    <v-btn
                        color="warning"
                        outlined
                        dense
                        @click="step = 2"
                        class="mr-3"
                    >
                      Rever pedido
                    </v-btn>

                    <v-btn
                        color="success"
                        outlined
                        dense
                        :disabled="!valid"
                        @click="endOrder"
                    >
                      Finalizar
                    </v-btn>

                  </div>
                </v-stepper-content>
              </v-stepper-items>
            </v-stepper>
          </div>
        </v-container>
      </section>
    </aside>
  </primaryTemplate>
</template>

<script>
import primaryTemplate from '@/templates/primary'
import axios from "axios";

export default {
  name: 'Home',
  components: {
    primaryTemplate
  },
  data: () => ({
    page: 1,
    step: 2,
    valid: true,
    cart: [],
    cartTotal: {
      qtd: 0,
      total: 0.00
    },
    items: [],
    topItems: null,
    total: 0,
    pageSize: 12,
    currentPage: 1,
    lastPage: 0,
    search: '',
    data: {
      name: {
        text: '',
        validation: [
          v => !!v || 'Este campo deve ser preenchido',
        ],
      },
      obs: {
        text: '',
      },
      payment_method: {
        text: '',
        validation: [
          v => !!v || 'Este campo deve ser preenchido',
          v => (!(v !== 'Dinheiro' && v !== 'Cartão')) || 'Forma de pagamento inválida'
        ],
      },
      payment_value: {
        text: 0.00,
        validation: [
          v => !!v || 'Este campo deve ser preenchido',
        ],
      },
    },
  }),
  methods: {
    getProducts: function () {
      this.items = []
      this.loading = true
      this.$http.post(this.$url + 'products/get/', {
        currentPage: this.currentPage,
        pageSize: this.pageSize,
        search: this.search,
      }, {})
          .then(response => {
            if (!("type" in response.data)) {
              this.items = (response.data.data ? response.data.data : [])
              this.total = response.data.total
            } else {
              this.$swal.fire({
                title: 'Erro',
                html: response.data.message,
                icon: 'error'
              })
            }
          })
    },
    getTopProducts: function () {
      this.topItems = null
      this.$http.post(this.$url + 'products/getTop', {}).then(response => {
        if (!("message" in response.data)) {
          this.topItems = (response.data.data ? response.data.data : [])
        } else {
          console.log(response.data)
        }
      })
    },
    addItem: function (product) {
      let products = JSON.parse(localStorage.getItem('cart'))
      if (!products) { products = [] }
      let findProduct = products.map(function (element) { return element.id; }).indexOf(product.id)
      if (findProduct >= 0) {
        products[findProduct].qtd += 1
      } else {
        products.push({
          id: product.id,
          file: product.file,
          name: product.name,
          vaun: product.vaun,
          qtd: 1
        })
      }
      localStorage.setItem('cartTotal', JSON.stringify(this.$refs.primaryTemplate.cart))
      localStorage.setItem('cart', JSON.stringify(products))
      this.attCart()
      this.$swal.fire({
        position: 'top-end',
        icon: 'success',
        title: 'Produto adicionado com sucesso',
        showConfirmButton: false,
        timer: 1500
      })
    },
    changeQtd: function (pId, pQtd) {
      let products = JSON.parse(localStorage.getItem('cart'))
      if (!products) { products = [] }
      let findProduct = products.map(function (element) { return element.id; }).indexOf(pId)
      if (findProduct >= 0) {
        if (parseInt(pQtd) === 0) {
          products.splice(findProduct)
        } else {
          products[findProduct].qtd = parseInt(pQtd)
        }
      }
      localStorage.setItem('cartTotal', JSON.stringify(this.$refs.primaryTemplate.cart))
      localStorage.setItem('cart', JSON.stringify(products))
      this.attCart()
    },
    delItem: function (pId) {
      let products = JSON.parse(localStorage.getItem('cart'))
      if (!products) { products = [] }
      let findProduct = products.map(function (element) { return element.id; }).indexOf(pId)
      if (findProduct >= 0) {
        products.splice(findProduct)
      }
      localStorage.setItem('cartTotal', JSON.stringify(this.$refs.primaryTemplate.cart))
      localStorage.setItem('cart', JSON.stringify(products))
      this.attCart()
    },
    attCart: function () {
      this.cart = JSON.parse(localStorage.getItem('cart'))
      if (!this.cart) { this.cart = [] }
      this.cartTotal = JSON.parse(localStorage.getItem('cartTotal'))
      if (!this.cartTotal) {
        this.cartTotal = {
          qtd: 0,
          total: 0.00
        }
      }
      this.cartTotal.qtd = 0
      this.cartTotal.total = 0
      let i = 0
      for (i; i < this.cart.length; i++) {
        this.cartTotal.qtd += parseInt(this.cart[i].qtd)
        this.cartTotal.total += parseFloat(this.cart[i].vaun) * parseInt(this.cart[i].qtd)
      }
      localStorage.setItem('cart', JSON.stringify(this.cart))
      localStorage.setItem('cartTotal', JSON.stringify(this.cartTotal))
      this.$refs.primaryTemplate.attCart()
    },
    returnValue: function () {
      return (parseFloat(this.data.payment_value.text) <= this.cartTotal.total ? 0.00 : parseFloat(this.data.payment_value.text) - this.cartTotal.total);
    },
    endOrder: function () {
      this.valid = true
      if (this.$refs.form.validate()) {
        this.attCart()
        if (parseFloat(this.data.payment_value.text) < parseFloat(this.cartTotal.total)) {
          this.$swal.fire({
            title: 'Opa!',
            html: 'O valor pago não pode ser menor que o total do pedido',
            icon: 'error'
          })
          this.valid = false
        } else {
          this.$http.post(this.$url + 'orders/create', {
            name: this.data.name.text,
            obs: this.data.obs.text,
            payment_method: this.data.payment_method.text,
            payment_value: this.data.payment_value.text,
            products: this.cart
          }).then(response => {
            if (("type" in response.data)) {
              this.$swal.fire({
                title: 'Opa!',
                html: response.data.message,
                icon: 'error'
              })
              this.valid = false
            } else {
              if (parseInt(response.data.status) === 200) {
                this.$http.post(this.$urlNotify, {
                  allNotifications: true,
                }, {}).then(response => {
                  this.$swal.fire({
                    title: 'Pedido finalizado!',
                    text: 'E já foi enviado a cozinha para a preparação! Em alguns minutos ele já estará sendo entregue a você.',
                    icon: 'success',
                    confirmButtonText: 'Legal!'
                  }).then((result) => {
                    if (result.isConfirmed) {
                      this.valid = false
                      this.step = 1
                      this.data.name.text = ''
                      this.data.payment_method.text = ''
                      this.data.payment_value.text = 0
                      localStorage.clear('cart')
                      localStorage.clear('cartTotal')
                      this.attCart()
                    }
                  })
                })
                .catch(error => {
                  if(error.response){
                    this.errors(error.response.message);
                  }else if (error.request) {
                    console.log('error.request');
                  } else {
                    console.log('Error', error);
                  }
                  console.log("rejected");
                });
              } else {
                this.$swal.fire({
                  title: 'Opa!',
                  html: response.data.message,
                  icon: 'error'
                })
                this.valid = false
              }
            }
          })
        }
      } else { this.valid = false }
    },
  },
  created () {
    this.getProducts()
    this.getTopProducts()
    this.cart = JSON.parse(localStorage.getItem('cart'))
    this.cartTotal = JSON.parse(localStorage.getItem('cartTotal'))
    if (!this.cart) { this.cart = [] }
    if (!this.cartTotal) {
      this.cartTotal = {
        qtd: 0,
        total: 0.00
      }
    }
  }
}
</script>

<style scoped>

aside#home section#header {
  background-image: url('../assets/background_3.jpg');
  background-repeat: no-repeat;
  background-size: cover;
  background-position: center center;
}
aside#home section#header > .container {
  height: 350px;
  position: relative;
}
aside#home section#header > .container > .v-card {
  position: absolute;
  bottom: -200px;
  max-width: inherit;
  z-index: 1;
}

aside#home section#most-wanted {
  background: #1E1E1E;
  padding-top: 1%;
  padding-bottom: 4%;
}
aside#home section#most-wanted #mw-title {
  margin-bottom: 50px;
}

aside#home section#plates {
  background: #f8f8f8;
  /*margin-top: 160px;*/
  padding-top: 3%;
  padding-bottom: 4%;
}

aside#home section#most-wanted #mw-content .v-card__title span,
aside#home section#most-wanted #mw-content .plate-description,
aside#home section#plates #plates-content .v-card__title span,
aside#home section#plates #plates-content .plate-description {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
aside#home section#plates #plates-content .theme--light.v-stepper {
  background: transparent !important
}
aside#home section#plates #plates-content .white--text,
aside#home section#plates #plates-content .basket-items td {
  color: #1e1e1e !important;
  caret-color: #1e1e1e !important;
}

</style>
