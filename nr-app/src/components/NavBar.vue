<template>
  <div>
    <!-- Barra en pantallas pequeñas -->
    <div v-if="!$route.meta.hideNav" class="bg-[#98D887] p-4 flex justify-between items-center md:hidden h-[85px] z-40">
      <!-- Logo -->
      <img src="@/assets/logo.png" alt="Logo" class="w-[120px] h-auto" />

      <!-- Ícono de menú -->
      <button @click="toggleSidebar" class="text-black mr-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
      </button>
    </div>

    <!-- Barra lateral -->
    <nav v-if="!$route.meta.hideNav" 
     :class="{'sidebar-open': isSidebarOpen, 'sidebar-closed': !isSidebarOpen}" 
     class="bg-[#98D887] p-4 flex flex-col items-center justify-start shadow-md fixed left-0 top-0 h-full w-60 md:block z-50">

      <!-- Logo -->
      <div class="mb-4 flex justify-center hidden md:block">
        <img src="@/assets/logo.png" alt="Logo" class="w-[200px] h-auto -mt-5" />
      </div>

      <!-- Catálogos -->
      <router-link to="/catalogos" class="w-full" @click="closeSidebar">
        <div
          class="flex items-center text-black mb-8 group w-[175px] ml-[17px] px-4 py-2 rounded-lg hover:bg-[#DCFFD0] transition-all duration-200 mt-8 md:-mt-5"
          :class="{ 'bg-[#DCFFD0]': $route.path === '/catalogos' }">
          <svg class="w-6 h-6 text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
            fill="currentColor" viewBox="0 0 24 24">
            <path fill-rule="evenodd"
              d="M9 2.221V7H4.221a2 2 0 0 1 .365-.5L8.5 2.586A2 2 0 0 1 9 2.22ZM11 2v5a2 2 0 0 1-2 2H4v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2h-7ZM8 16a1 1 0 0 1 1-1h6a1 1 0 1 1 0 2H9a1 1 0 0 1-1-1Zm1-5a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2H9Z"
              clip-rule="evenodd" />
          </svg>
          <span class="ml-2 text-xl opacity-100 group-hover:opacity-100 transition-opacity">Catálogos</span>
        </div>
      </router-link>

      <!-- Productos -->
      <router-link v-if="this.$user.role === 'Administrador'" to="/productos" class="w-full" @click="closeSidebar">
        <div
          class="flex items-center text-black mb-8 group w-[175px] ml-[17px] px-4 py-2 rounded-lg hover:bg-[#DCFFD0] transition-all duration-200"
          :class="{ 'bg-[#DCFFD0]': $route.path === '/productos' }">
          <svg class="w-6 h-6 text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
            fill="currentColor" viewBox="0 0 24 24">
            <path fill-rule="evenodd"
              d="M4 4a1 1 0 0 1 1-1h1.5a1 1 0 0 1 .979.796L7.939 6H19a1 1 0 0 1 .979 1.204l-1.25 6a1 1 0 0 1-.979.796H9.605l.208 1H17a3 3 0 1 1-2.83 2h-2.34a3 3 0 1 1-4.009-1.76L5.686 5H5a1 1 0 0 1-1-1Z"
              clip-rule="evenodd" />
          </svg>
          <span class="ml-2 text-xl opacity-100 group-hover:opacity-100 transition-opacity">Productos</span>
        </div>
      </router-link>

      <!-- Usuarios -->
      <router-link v-if="this.$user.role === 'Administrador'" to="/usuarios" class="w-full" @click="closeSidebar">
        <div
          class="flex items-center text-black mb-8 group w-[175px] ml-[17px] px-4 py-2 rounded-lg hover:bg-[#DCFFD0] transition-all duration-200"
          :class="{ 'bg-[#DCFFD0]': $route.path === '/usuarios' }">
          <svg class="w-6 h-6 text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
            fill="currentColor" viewBox="0 0 24 24">
            <path fill-rule="evenodd"
              d="M8 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4H6Zm7.25-2.095c.478-.86.75-1.85.75-2.905a5.973 5.973 0 0 0-.75-2.906 4 4 0 1 1 0 5.811ZM15.466 20c.34-.588.535-1.271.535-2v-1a5.978 5.978 0 0 0-1.528-4H18a4 4 0 0 1 4 4v1a2 2 0 0 1-2 2h-4.535Z"
              clip-rule="evenodd" />
          </svg>
          <span class="ml-2 text-xl opacity-100 group-hover:opacity-100 transition-opacity">Usuarios</span>
        </div>
      </router-link>

      <!-- Perfil -->
      <router-link to="/perfil" class="w-full" @click="closeSidebar">
        <div
          class="flex items-center text-black mb-8 group w-[175px] ml-[17px] px-4 py-2 rounded-lg hover:bg-[#DCFFD0] transition-all duration-200"
          :class="{ 'bg-[#DCFFD0]': $route.path === '/perfil' }">
          <svg class="w-6 h-6 text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
            fill="currentColor" viewBox="0 0 24 24">
            <path fill-rule="evenodd"
              d="M12 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4h-4Z"
              clip-rule="evenodd" />
          </svg>
          <span class="ml-2 text-xl opacity-100 group-hover:opacity-100 transition-opacity">Perfil</span>
        </div>
      </router-link>

      <!-- Inicio -->
      <div class="absolute bottom-0 w-full ml-[30px] md:ml-0">
        <router-link to="/" class="w-full" @click="closeSidebar">
          <div
            class="flex items-center text-black mb-8 group w-[175px] ml-[17px] px-4 py-2 rounded-lg hover:bg-[#DCFFD0] transition-all duration-200"
            :class="{ 'bg-[#DCFFD0]': $route.path === '/' }">
            <svg class="w-6 h-6 text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" 
              fill="currentColor" viewBox="0 0 24 24">
              <path fill-rule="evenodd" d="M11.293 3.293a1 1 0 0 1 1.414 0l6 6 2 2a1 1 0 0 1-1.414 1.414L19 12.414V19a2 2 0 0 1-2 2h-3a1 1 0 0 1-1-1v-3h-2v3a1 1 0 0 1-1 1H7a2 2 0 0 1-2-2v-6.586l-.293.293a1 1 0 0 1-1.414-1.414l2-2 6-6Z" clip-rule="evenodd"/>
            </svg>
            <span class="ml-2 text-xl opacity-100 group-hover:opacity-100 transition-opacity">Inicio</span>
          </div>
        </router-link>
      </div>
    </nav>
  </div>
</template>

<script>
export default {
  name: "NavBar",
  data() {
    return {
      isSidebarOpen: false,
    };
  },
  methods: {
    toggleSidebar() {
      this.isSidebarOpen = !this.isSidebarOpen;
    },
    closeSidebar() {
      this.isSidebarOpen = false;
    },
    getRole() {
      this.$axios.get('/user').then(response => {
        this.$user.role = response.data.role;
      }).catch(error => {
        console.error('Error al iniciar sesión:', error.response);
      });
    }
  },
  mounted() {
    if(localStorage.getItem('token')){
      this.getRole();
    }
    
  }
};
</script>

<style scoped>
/* Estilo base de la barra lateral */
nav {
  transition: transform 0.3s ease-in-out;
}

/* Barra lateral cuando está abierta en pantallas pequeñas */
nav.sidebar-open {
  transform: translateX(0); /* Muestra la barra lateral */
}

/* Barra lateral cuando está cerrada en pantallas pequeñas */
nav.sidebar-closed {
  transform: translateX(-100%); /* Mueve la barra lateral fuera de la vista */
}

/* Barra lateral visible en pantallas grandes */
@media (min-width: 768px) {
  nav {
    transform: translateX(0); /* No se mueve, siempre visible */
    transition: none; /* Desactiva la transición en pantallas grandes */
  }

  nav.sidebar-open,
  nav.sidebar-closed {
    transform: translateX(0); /* Siempre visible */
  }
}

/* Deshabilitar pointer-events solo en pantallas pequeñas cuando está cerrada */
@media (max-width: 767px) {
  nav.sidebar-closed {
    pointer-events: none; /* Desactiva interacciones cuando está cerrada */
  }
}
</style>
