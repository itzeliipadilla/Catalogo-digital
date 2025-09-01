<template>
  <div class="flex h-screen">
    <!-- Barra lateral-->
    <aside id="logo-sidebar" class="hidden md:block w-64 text-white h-screen" aria-label="Sidebar"></aside>

    <!-- Contenedor principal -->
    <div class="flex-1 p-6">
      <div v-if="loading" class="flex justify-center items-center h-full">
        <SpinnerGreen :fullscreen="true" />
      </div>
      <div v-else>
        <div class="usuarios-container">
          <!-- Título -->
          <h1 class="text-3xl font-normal mt-4 md:mt-12 mb-8 ml-0 md:ml-5">Usuarios</h1>

          <!-- Buscador -->
          <div
            class="flex flex-col md:flex-row md:items-start justify-start md:justify-between mb-0 md:mb-4 ml-[2px] md:ml-5 mr-6 space-y-4 md:space-y-0">
            <div class="relative w-[325px] md:w-1/3 mb-2 md:mb-0">
              <span class="absolute inset-y-0 left-3 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M11 4a7 7 0 1 0 0 14 7 7 0 0 0 0-14zM21 21l-4.35-4.35" />
                </svg>
              </span>
              <input v-model="searchTerm" type="text" placeholder="Buscar" class="w-full pl-10 pr-4 py-3 text-base md:text-lg rounded-xl shadow-lg border-none focus:border-none focus:ring-0 placeholder-gray-400 focus:outline-none" />
            </div>

            <!-- Botón Registrar Usuario -->
            <button
              class="bg-[#FFCF00] text-black py-2 px-6 rounded-xl shadow-lg focus:outline-none w-[180px] md:w-auto text-base md:text-lg"
              @click="$router.push('/registro-usuarios')">
              Registrar usuario
            </button>
          </div>

          <!-- Componente DataTable -->
          <DataTable :headers="headers" :rows="filteredRows" :visibleColumnsMobile="visibleColumnsMobile"
            baseRoute="/editar-usuario" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import DataTable from "@/components/DataTable.vue";
import SpinnerGreen from "@/components/SpinnerGreen.vue";
import methods from "@/methods";
export default {
  name: "UsuariosView",
  components: {
    DataTable,
    SpinnerGreen,
  },
  data() {
    return {
      loading: true,
      headers: ["ID", "Nombre", "Teléfono", "Tipo"],
      rows: [],
      visibleColumnsMobile: [0, 1, 3],
      searchTerm: '',
    };
  },
  mounted() {
    this.$axios('/get-users').then(response => {
      this.rows = response.data.users;
      this.loading = false;
      methods.deleteAuxProperties(this.$auxiliar);
    }).catch(error => {
      console.error('Error al obtener la información:', error.response);
      alert(error.response.data.message);
      if (error.response.request.status === 403) {
        this.$router.back(1);
      }
    });
  },
  computed: {
    filteredRows() {
      if (!this.searchTerm) {
        return this.rows;
      }
      return this.rows.filter(row => {
        return Object.values(row).some(value =>
          String(value).toLowerCase().includes(this.searchTerm.toLowerCase())
        );
      });
    },
  },
};
</script>
