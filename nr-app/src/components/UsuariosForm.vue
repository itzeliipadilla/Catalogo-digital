<template>
  <!-- Contenedor -->
  <div class="bg-white shadow-lg rounded-xl p-8 relative w-full max-w-[1160px] mx-auto my-6 ml-0 md:ml-5">
    <!-- Botón para regresar -->
    <button @click="$router.push('/usuarios')" class="absolute top-4 left-4">
      <svg class="w-6 h-6 text-gray-800 dark:text-black sm:w-8 sm:h-8" aria-hidden="true"
        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M5 12h14M5 12l4-4m-4 4 4 4" />
      </svg>
    </button>

    <!-- Formulario -->
    <form class="space-y-6 mt-5 md:mt-10 ml-0 md:ml-[90px]">
      <!-- Nombre -->
      <div class="flex flex-col sm:flex-row items-start sm:items-center sm:w-[700px] py-0 md:py-4">
        <label for="nombre" class="w-full sm:w-32 text-black font-medium p-2 text-lg sm:text-xl -ml-2 md:ml-0">
          Nombre:</label>
        <input id="nombre" type="text" v-model="name"
          class="flex-1 border-2 border-black rounded-lg shadow-sm p-2 focus:outline-none focus:ring-0 focus:border-black text-base sm:text-lg w-full" maxlength="50"/>
      </div>

      <!-- Teléfono -->
      <div class="flex flex-col sm:flex-row items-start sm:items-center sm:w-[500px] py-0 md:py-4">
        <label for="telefono"
          class="w-full sm:w-[129px] text-black font-medium text-lg sm:text-xl p-2 -ml-2 md:ml-0">Teléfono:</label>
        <input id="telefono" type="tel" v-model="phone"
          class="flex-1 border-2 border-black rounded-lg shadow-sm p-2 focus:outline-none focus:ring-0 focus:border-black text-base sm:text-lg w-full" maxlength="10"/>
      </div>

      <!-- Usuario -->
      <div class="flex flex-col sm:flex-row items-start sm:items-center sm:w-[496px] py-0 md:py-4">
        <label for="usuario" class="w-full sm:w-[129px] text-black font-medium text-lg sm:text-xl p-2 -ml-2 md:ml-0">
          Usuario:</label>
        <select id="usuario" v-model="role"
          class="flex-1 border-2 border-black rounded-lg shadow-sm p-2 pr-8 focus:outline-none focus:ring-0 focus:border-black text-base sm:text-lg w-full appearance-none"
          style="
            background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 fill=%27none%27 viewBox=%270 0 24 24%27 stroke=%27currentColor%27%3e%3cpath stroke-linecap=%27round%27 stroke-linejoin=%27round%27 stroke-width=%272%27 d=%27M19 9l-7 7-7-7%27 /%3e%3c/svg%3e');
            background-repeat: no-repeat;
            background-position: right 0.5rem center;
            background-size: 1.5em;
          ">
          <option value="Administrador">Administrador</option>
          <option value="General">General</option>
        </select>
      </div>

      <!-- Usuario Activo (Checkbox) -->
      <div class="flex items-center mt-4 ml-0 md:ml-[130px]">
        <input id="usuario-activo" type="checkbox" v-model="active" class="custom-checkbox w-5 h-5 mt-[2px] rounded-lg" />
        <label for="usuario-activo" class="text-black font-base text-lg sm:text-xl ml-2">Usuario activo</label>
      </div>
    </form>

    <!-- Botones -->
    <div
      class="flex flex-col sm:flex-row justify-end mt-8 md:mb-4 space-y-4 sm:space-y-0 sm:space-x-10 mr-0 md:mr-[50px]">
      <!-- Condición para mostrar el botón adecuado -->
      <button v-if="$route.path === '/editar-usuario'" @click="eliminar"
        class="bg-[#FF594D] text-black py-2 px-8 sm:px-12 rounded-xl text-lg sm:text-lg">
        Eliminar
      </button>
      <button v-if="$route.path === '/editar-usuario'" @click="guardar"
        class="bg-[#98D887] text-black py-2 px-8 sm:px-12 rounded-xl text-lg sm:text-lg">
        Guardar
      </button>
      <button v-if="$route.path === '/registro-usuarios'" @click="registrar"
        class="bg-[#98D887] text-black py-2 px-8 sm:px-20 rounded-xl text-lg sm:text-lg">
        Registrar
      </button>
    </div>
  </div>
</template>

<script>
import methods from '@/methods';


export default {
  name: "UsuariosForm",
  data() {
    return {
      phone: '',
      role: '',
      active: false,
      name: '',
      id:'',
    }
  },
  mounted() {
    if (this.$route.path === '/editar-usuario') {
      if (this.$auxiliar.phone !== null) {
        this.phone = this.$auxiliar.phone;
        this.name = this.$auxiliar.name;
        this.active = this.$auxiliar.active;
        this.role = this.$auxiliar.role;
        this.id = this.$auxiliar.id;
        methods.deleteAuxProperties(this.$auxiliar);
      } else {
        this.$router.back(1);
      }
    }
  },
  methods: {
    guardar() {
      this.$axios.put(`/update-user/${this.id}`, {
        name: this.name,
        active: this.active,
        role: this.role,
        phone: this.phone
      }).then(response => {
        alert(response.data.message);
        this.$router.back();
      }).catch(error => {
        alert(error.response.data.message);
      });
    },
    eliminar() {
      this.$axios.delete(`/delete-user/${this.id}`).then(response => {
        alert(response.data.message);
        this.$router.back();
      }).catch(error => {
        alert(error.response.data.message);
      });
    },
    registrar() {
      this.$axios.post('/register-user', {
        name: this.name,
        phone: this.phone,
        active: this.active,
        role: this.role
      }).then(response => {
        alert(response.data.message);
        this.$router.back();
      }).catch(error => {
        alert(error.response.data.message);
      });
    }
  }
};
</script>

<style scoped>
.custom-checkbox {
  appearance: none;
  -webkit-appearance: none;
  border: 2px solid #222020; 
  border-radius: 0.25rem; 
  background-color: #ffffff;
  cursor: pointer;
  transition: background-color 0.2s, border-color 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
  outline: none; 
  box-shadow: none; 
}

.custom-checkbox:checked {
  background-color: #98d887;
  border-color: #98d887;
}

.custom-checkbox:checked::after {
  content: "✔"; /* Palomita */
  color: white;
  font-size: 0.75rem;
  font-weight: bold;
}

.custom-checkbox:focus {
  outline: none; 
  box-shadow: none; 
}
</style>