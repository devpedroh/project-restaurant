<template>
  <panelTemplate>
    <aside id="home">
      <section id="home-content">
        <v-container class="white--text pl-0 pr-0">
            <kitchen-area></kitchen-area>
        </v-container>
      </section>
    </aside>
  </panelTemplate>
</template>

<script>
import panelTemplate from '@/templates/panelTemplate'
import kitchenArea from '@/components/panel/kitchen'

export default {
  name: 'PanelHome',
  components: {
    panelTemplate,
    kitchenArea
  },
  data: () => ({

  }),
  methods: {

  },
  created () {
    if (!this.$user) { this.$router.push('/panel/login') }
  }
}
</script>

<style scoped>

aside#home section#home-content {
  background: #f8f8f8;
  padding-top: 3%;
  padding-bottom: 4%;
  min-height: 600px;
}

</style>