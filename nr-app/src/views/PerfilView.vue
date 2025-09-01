<template>
  <div class="flex h-screen">
    <!-- Barra lateral -->
    <aside id="logo-sidebar" class="hidden md:block w-64 text-white h-screen" aria-label="Sidebar"></aside>

    <!-- Contenedor Principal -->
    <div class="flex-1 p-6">
      <div v-if="loading" class="flex justify-center items-center h-full">
        <SpinnerGreen :fullscreen="true" />
      </div>
      <div v-else>
        <!-- Título -->
        <h1 class="text-3xl font-normal mt-4 md:mt-12 mb-8 ml-0 md:ml-5"> Perfil</h1>

        <!-- Contenedor del Perfil -->
        <div
          class="bg-white shadow-lg rounded-xl p-8 mb-10 w-[330px] md:w-[500px] mx-auto flex flex-col items-center text-center">
          <div class="space-y-2">
            <p class="text-xl md:text-2xl font-semibold text-black"> {{ usuario.name }}</p>
            <p class="text-lg md:text-xl text-black"> {{ usuario.role }}</p>
            <p class="text-lg md:text-xl text-black">{{ usuario.phone }}</p>
          </div>
          <button @click="$router.push('/cambiar-contrasena')"
            class="mt-4 bg-[#98D887] text-black py-2 text-lg px-10 rounded-xl mb-2">
            Cambiar contraseña
          </button>
        </div>

        <!-- Contenedor de Cerrar Sesión -->
        <div
          class="bg-white shadow-lg rounded-xl p-6 w-[330px] md:w-[500px] mx-auto flex flex-col items-center text-center">
          <h2 class="text-xl md:text-2xl font-semibold text-black mb-4"> Cerrar Sesión</h2>
          <p class="text-lg md:text-xl text-black mb-6"> ¿Estás seguro de que deseas cerrar sesión?</p>
          <div class="flex space-x-8">
            <button @click="$router.push('/catalogos')" class="bg-[#FF594D] text-black text-lg py-2 px-8 rounded-xl">
              Cancelar</button>
            <button @click="logout" class="bg-[#98D887] text-black text-lg py-2 px-8 rounded-xl">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SpinnerGreen from "@/components/SpinnerGreen.vue";

export default {
  name: "PerfilView",
  components: {
    SpinnerGreen,
  },
  data() {
    return {
      loading: true,
      usuario: {},
    };
  },
  mounted() {
    this.$axios.get('/user').then(response => {
      this.usuario = response.data;
      this.loading = false;
    }).catch(error => {
      console.error('Error al obtener la información:', error.response);
      alert(error.response.data.message);
    });
  },
  methods: {
    logout() {
      this.$axios.delete('/logout').then(
        response => {
          alert(response.data.message);
          localStorage.removeItem('token');
          this.$router.push('/login');
        }).catch(error => {
          console.error('Error al iniciar sesión:', error.response);
          if (error.response.request.status) {
            localStorage.removeItem('token');
            this.$router.push('/login');
          }
          alert(error.response.data.message);
        });
    }
  }
};
</script>