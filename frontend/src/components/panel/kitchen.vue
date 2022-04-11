<template>
  <div id="kitchen-data">
    <v-text-field
        v-model="search"
        label="Pesquisar"
        placeholder="ID ou nome do cliente"
        color="none"
        hint="Pressione Enter para pesquisar"
        id="search"
    ></v-text-field>
    <v-data-table
        background-color="transparent"
        :headers="headers"
        :items="items"
        :page.sync="currentPage"
        :server-items-length="total"
        :items-per-page="pageSize"
        :footer-props="{
          itemsPerPageText: 'Registros por página',
          itemsPerPageOptions: [
            5, 10, 15, 20, 30, 50
          ],
          pageText: 'Página ' + this.currentPage + ' de ' + ((this.total / this.pageSize) + 0.4).toFixed()
        }"
        @update:items-per-page="getItemPerPage"
        :loading="loading"
        loading-text="Buscando dados... Por favor, aguarde!"
    >

      <template v-slot:top>
        <v-dialog
            v-model="dialog"
            max-width="800px"
            style="z-index: 999"
        >
          <v-card>
            <v-card-text class="pt-6 pb-6">
              <v-container>
                <v-row v-if="order">
                  <v-col lg="12" md="12">
                    <h4 class="text-h4">Pedido #{{ order.id }}</h4>
                    <v-divider class="mt-5"></v-divider>
                    <v-list>
                      <v-list-item v-for="(p, index) in order.products" :key="index">
                        <v-list-item-content>
                          <v-list-item-title>{{ p.name }}</v-list-item-title>
                          <v-list-item-subtitle>{{ 'Quantidade: ' + p.qtd }}</v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                    </v-list>
                    <v-divider class="mt-2 mb-5"></v-divider>
                    <h6 class="text-h6">Observação do cliente</h6>
                    <p>{{order.obs}}</p>
                  </v-col>
                </v-row>
                <v-row v-else justify="center" align="center">
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
              </v-container>
            </v-card-text>

            <v-card-actions v-if="order">
              <v-btn
                  color="success"
                  outlined
                  v-if="parseInt(order.status) === 0"
                  :disabled="!end"
                  @click="endOrder(order.id)"
              >
                Finalizar pedido
              </v-btn>
              <v-spacer></v-spacer>
              <v-btn
                  color="error"
                  outlined
                  @click="close"
              >
                Fechar
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </template>

      <template v-slot:item.created_at="{ item }">
        {{(item.created_at ? moment(item.created_at).format('DD/MM/YYYY') + ' às ' + moment(item.created_at).format('HH:mm') : '---')}}
      </template>
      <template v-slot:item.updated_at="{ item }">
        {{(item.created_at ? moment(item.updated_at).format('DD/MM/YYYY') + ' às ' + moment(item.updated_at).format('HH:mm') : '---')}}
      </template>
      <template v-slot:item.status="{ item }">
        <v-chip color="error" v-if="parseInt(item.status) === 0">Aguardando</v-chip>
        <v-chip color="success" v-else-if="parseInt(item.status) === 1">Finalizado</v-chip>
      </template>

      <template v-slot:item.actions="{ item }">
        <v-tooltip bottom>
          <template v-slot:activator="{ on }">
          <span v-on="on">
            <v-icon small class="mr-2" color="success" @click="startAct(item.id)">
              mdi-eye
            </v-icon>
          </span>
          </template>
          <span>Visualizar pedido</span>
        </v-tooltip>
      </template>

    </v-data-table>
  </div>
</template>

<script>
export default {
  name: "kitchen",
  data: () => ({
    items: [],
    loading: true,
    total: 0,
    pageSize: 5,
    currentPage: 1,
    lastPage: 0,
    dialog: false,
    headers: [
      {
        text: 'ID',
        align: 'start',
        sortable: false,
        value: 'id',
      },
      { text: 'Nome', value: 'name', sortable: false },
      { text: 'Status', value: 'status', filterable: false, sortable: false },
      { text: 'Feito em', value: 'created_at', filterable: false, sortable: false },
      { text: 'Ultima atualização', value: 'updated_at', filterable: false, sortable: false },
      { text: 'Ações', value: 'actions', sortable: false },
    ],
    search: '',
    order: '',
    end: true
  }),
  methods: {
    getItemPerPage(val) {
      this.pageSize = val
      this.getData()
    },
    getData: function () {
      this.items = []
      this.loading = true
      this.$http.post(this.$url + 'orders/get', {
        currentPage: this.currentPage,
        pageSize: this.pageSize,
        search: this.search,
      }, {headers: {Authorization: 'Bearer ' + this.$user.token}}).then(response => {
        if (!("message" in response.data)) {
          this.items = (response.data.data ? response.data.data : [])
          this.total = response.data.total
          this.loading = false
        } else {
          console.log(response.data)
        }
      })
    },
    startAct: function (id) {
        this.dialog = true
        this.viewOrder(id)
    },
    close () {
      this.dialog = false
      this.order = ''
    },
    viewOrder: function (value) {
      if (value) {
        this.$http.post(this.$url + 'orders/view', {
          id: value
        }, {headers: { Authorization: 'Bearer ' + this.$user.token }}).then(response => {
          if (!("type" in response.data)) {
            this.order = response.data.order
            this.order.products = response.data.order_items
          } else {
            console.log(response.data)
          }
        }).catch(err => {
          console.log(err)
        })
      }
    },
    endOrder: function (value) {
      if (value) {
        this.end = false
        this.$http.post(this.$url + 'orders/end', {
          id: value
        }, {headers: { Authorization: 'Bearer ' + this.$user.token }}).then(response => {
          if (!("type" in response.data)) {
            if (parseInt(response.data.status) === 200) {
              this.$swal.fire({
                position: 'top-end',
                icon: 'success',
                title: response.data.message,
                showConfirmButton: false,
                timer: 1500
              })
              this.getData()
            } else {
              this.$swal.fire({
                position: 'top-end',
                icon: 'error',
                title: response.data.message,
                showConfirmButton: false,
                timer: 1500
              })
            }
            this.end = true
            this.close()
          } else {
            console.log(response.data)
            this.end = true
            this.close()
          }
        }).catch(err => {
          console.log(err)
          this.end = true
          this.close()
        })
      }
    }
  },
  mounted () {
    var search = document.querySelector('input#search');
    var vm = this;
    search.addEventListener('keypress', logKey);
    function logKey(e) {
      if (e.code == 'NumpadEnter' || e.code == 'Enter') {
        vm.getData()
      }
    }
  },
  created () {
    if (("token") in this.$user) {
      this.getData()
    } else {
      this.$router.push('/panel/login')
    }
  },
  watch: {
    dialog (val) {
      val || this.close()
    },
    currentPage (val) {
      this.getData()
    },
    pageSize (val) {
      this.getData()
    },
  },
}
</script>

<style scoped>

.theme--light.v-data-table {
  background: transparent !important;
}

</style>