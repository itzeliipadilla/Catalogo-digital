<template>
  <!-- Fondo -->
  <div :style="{
    backgroundImage: 'url(' + require('@/assets/fondo.svg') + ')',
    backgroundSize: 'cover',
    backgroundPosition: 'center',
    height: '100vh',
  }" class="overflow-hidden">

    <div v-if="loading" class="flex justify-center items-center h-full">
      <SpinnerGreen :fullscreen="true" />
    </div>
    
    <!-- Contenedor -->
    <div class="flex justify-center items-center h-full flex-col text-center">

      <!-- Título -->
      <h1 class="text-2xl md:text-4xl font-semibold text-black mb-4 -mt-1">Inicio De Sesión</h1>

      <!-- Imagen del logo -->
      <img src="@/assets/logo.png" alt="Logo" class="w-[180px] md:w-[220px] h-auto mb-4 -mt-8" />

      <!-- Formulario -->
      <form @submit.prevent="login" class="w-[400px] space-y-4 -mt-10">
        <!-- Input telefono -->
        <div class="mb-8">
          <label for="telefono" class="block text-black text-xl md:text-2xl font-semibold mb-4 md:mb-6">Ingrese su
            número telefónico:</label>
          <input type="tel" id="telefono" name="telefono" v-model="telefono"
            class="flex-1 border-2 border-black rounded-lg shadow-sm p-2 focus:outline-none focus:ring-0 focus:border-black text-base sm:text-lg w-[280px] md:w-[330px] bg-[#B8E9A8] h-[40px]"
            required />
        </div>

        <!-- Input de contraseña -->
        <div class="relative">
          <label for="contrasena" class="block text-black text-xl md:text-2xl font-semibold mb-4 md:mb-6">Ingrese su
            contraseña:</label>
          <input :type="showPassword ? 'text' : 'password'" id="contrasena" name="contrasena" v-model="contrasena"
            class="flex-1 border-2 border-black rounded-lg shadow-sm p-2 focus:outline-none focus:ring-0 focus:border-black text-base sm:text-lg w-[280px] md:w-[330px] bg-[#B8E9A8] h-[40px]"
            required />
        </div>

        <!-- Botón de ingresar -->
        <div>
          <button type="submit"
            class="w-[280px] md:w-[330px] h-[45px] bg-[#FFCF00] text-black shadow-md rounded-xl text-lg mt-4">
            Ingresar
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import SpinnerGreen from "@/components/SpinnerGreen.vue";
export default {
  name: "LoginView",
  components: {
    SpinnerGreen
  },
  data() {
    return {
      telefono: '',
      contrasena: '',
      showPassword: false,
      loading: true
    };
  },
  mounted() {
    setTimeout(() => {
      this.loading = false; // Ocultar spinner 
    }, 200);
    if (localStorage.getItem('token')) {
      this.tryAuth();
    } else {
      this.loading = false;
    }
  },
  methods: {
    login() {
      this.$axios.post('/login', {
        phone: this.telefono,
        password: this.contrasena
      }).then(response => {
        const token = response.data.access_token;
        alert(response.data.message);
        localStorage.setItem('token', token);
        this.$axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        this.$user.role = response.data.user.role;
        this.$router.push("/catalogos");
      }).catch(error => {
        alert(error.response.data.message);
      });
    },
    tryAuth() {
      this.$axios.get('/user').then(response => {
        this.$user.role = response.data.role;
        this.$router.push('/catalogos');
      }).catch(() => {
        localStorage.removeItem('token');
      }).finally(() => {
        this.loading = false;
      });
    }
  }
};
</script>
