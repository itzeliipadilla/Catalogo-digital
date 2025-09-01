<template>
  <div class="flex h-screen">
    <!-- Barra lateral-->
    <aside id="logo-sidebar" class="hidden md:block w-64 text-white h-screen" aria-label="Sidebar"></aside>

    <!-- Contenedor Principal -->
    <div class="flex-1 p-6">
      <div v-if="loading" class="flex justify-center items-center h-full">
        <SpinnerGreen :fullscreen="true" />
      </div>
      <div v-else>
        <!-- Título -->
        <h1 class="text-3xl font-normal mt-4 md:mt-12 mb-8 ml-0 md:ml-5">Editar Producto</h1>
      </div>
      <div>
        <Productos />
      </div>
      <div class="h-[40px]"></div>
    </div>
  </div>
</template>

<script>
import Productos from "@/components/ProductosForm.vue";
import SpinnerGreen from "@/components/SpinnerGreen.vue";

export default {
  name: "RegistrarProductosView",
  components: {
    Productos,
    SpinnerGreen
  },
  data() {
    return {
      loading: true,
    };
  },
  mounted() {
    if (this.$user.role !== 'Administrador')
      this.$router.back(1);
    setTimeout(() => {
      this.loading = false;
    }, 500);
  },
  beforeMount() {
    if (this.$productAux.id_family === null)
      this.$router.back(1);
  }
};
</script>
