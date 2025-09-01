<template>
  <div class="flex h-screen relative">
    <!-- Barra lateral -->
    <aside id="logo-sidebar" class="hidden md:block w-64 text-white h-screen" aria-label="Sidebar"></aside>

    <!-- Contenedor Principal -->
    <div class="flex-1 p-6">
      <div v-if="loading" class="flex justify-center items-center h-full">
        <SpinnerGreen :fullscreen="true" />
      </div>
      <div v-else>
        <!-- Título -->
        <h1 class="text-3xl font-normal mt-4 md:mt-12 mb-8 ml-0 md:ml-5">Cambiar Contraseña</h1>

        <div
          class="bg-white shadow-lg rounded-xl p-8 mb-10 w-[330px] md:w-[500px] mx-auto flex flex-col items-center text-center relative">
          <!-- Botón para regresar -->
          <button @click="$router.back();" class="absolute top-4 left-4 bg-white p-2 z-10">
            <svg class="w-6 h-6 text-gray-800 dark:text-black sm:w-8 sm:h-8" aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M5 12h14M5 12l4-4m-4 4 4 4" />
            </svg>
          </button>

          <!-- Formulario -->
          <form @submit.prevent="change_password" class="w-full space-y-6 mt-10">
            <!-- Contraseña Actual -->
            <div class="flex flex-col">
              <label for="current-password" class="text-black font-medium text-lg md:text-xl mb-4 text-left"> Contraseña
                actual:</label>
              <input id="current-password" type="password" v-model="currentPassword"
                class="flex-1 border-2 border-black rounded-lg shadow-sm p-2 focus:outline-none focus:ring-0 focus:border-black text-base sm:text-lg w-full" maxlength="25"/>
            </div>

            <!-- Nueva Contraseña -->
            <div class="flex flex-col">
              <label for="new-password" class="text-black font-medium text-lg md:text-xl mb-4 text-left"> Nueva
                contraseña:</label>
              <input id="new-password" type="password" v-model="newPassword"
                class="flex-1 border-2 border-black rounded-lg shadow-sm p-2 focus:outline-none focus:ring-0 focus:border-black text-base sm:text-lg w-full" maxlength="25"/>
            </div>

            <!-- Confirmar Contraseña -->
            <div class="flex flex-col">
              <label for="confirm-password" class="text-black font-medium text-lg md:text-xl mb-4 text-left">Confirmar
                contraseña:</label>
              <input id="confirm-password" type="password" v-model="confirmPassword"
                class="flex-1 border-2 border-black rounded-lg shadow-sm p-2 focus:outline-none focus:ring-0 focus:border-black text-base sm:text-lg w-full" maxlength="25"/>
            </div>

            <!-- Botón Guardar Contraseña -->
            <button type="submit" class="mt-4 bg-[#98D887] text-black py-2 text-lg px-10 rounded-xl mb-2 w-full">
              Guardar contraseña
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SpinnerGreen from "@/components/SpinnerGreen.vue";

export default {
  name: "ContrasenaView",
  components: {
    SpinnerGreen
  },
  data() {
    return {
      loading: true,
      confirmPassword: "",
      currentPassword: "",
      newPassword: ""
    };
  },
  mounted() {
    setTimeout(() => {
      this.loading = false; // Ocultar spinner 
    }, 500);
  },
  methods: {
    change_password() {
      this.$axios.patch('/update-password', {
        oldPassword: this.currentPassword,
        newPassword: this.newPassword,
        newPassword_confirmation: this.confirmPassword
      }).then(response => {
        console.log(response.data.message);
        alert(response.data.message);
        this.$router.back();
      }).catch(error => {
        console.error('Error al cambiar la contraseña:', error.response);
        if (error.response.request.status) {
          localStorage.removeItem('token');
          this.$router.push('/');
        }
        alert(error.response.data.message);
      });
    }
  }
};
</script>
