<template>
  <!-- Contenedor -->
  <div class="bg-white shadow-lg rounded-xl p-8 relative w-[340px] md:w-[1160px] mx-auto my-6 ml-0 md:ml-5">
    <!-- Botón para regresar -->
    <button @click="$router.push('/productos')" class="absolute top-4 left-4">
      <svg class="w-6 h-6 text-gray-800 sm:w-8 sm:h-8" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
        height="24" fill="none" viewBox="0 0 24 24">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M5 12h14M5 12l4-4m-4 4 4 4" />
      </svg>
    </button>

    <!-- Formulario -->
    <form class="space-y-6 mt-5 md:mt-10">
      <div class="flex flex-col md:flex-row">
        <!-- Contenedor para previsualizar la imagen -->
        <div class="flex flex-col items-center w-full md:w-[213px] ml-0 md:ml-[130px]">
          <div class="w-full h-48 rounded-lg flex items-center justify-center border">
            <img :src="previewImage" alt="Previsualización" class="object-contain h-full w-full" />
          </div>
          <label
            class="mt-4 bg-[#FFCF00] text-black py-2 w-full md:w-[211px] flex items-center justify-center h-[40px] sm:px-10 rounded-xl text-lg sm:text-lg">
            {{
              $route.path === "/editar-producto"
                ? "Cambiar imagen"
                : "Subir imagen"
            }}
            <input type="file" accept="image/*" class="hidden" @change="handleImageUpload" />
          </label>

          <!-- Checkbox Producto Activo -->
          <div class="flex items-center mt-[37px] -ml-[45px] mb-4">
          <input id="producto-activo" type="checkbox" v-model="active" class="custom-checkbox w-5 h-5 mt-[2px] border-black focus:outline-none focus:ring-0 focus:border-black" />
            <label for="producto-activo" class="text-black font-base text-lg sm:text-xl ml-2">
              Producto activo
            </label>
          </div>
        </div>

        <div class="flex-1 md:ml-[70px] space-y-4">
          <!-- Nombre -->
          <div class="flex flex-col">
            <label for="nombre" class="text-black font-medium text-xl mb-4">Nombre:</label>
            <input id="nombre" type="text" v-model="name"
              class="border-2 border-black rounded-lg shadow-sm p-2 focus:outline-none focus:ring-0 text-lg w-full md:w-[550px] mb-2" maxlength="40"/>
          </div>

          <!-- Descripción -->
          <div class="flex flex-col">
            <label for="descripcion" class="text-black font-medium text-xl mb-4">Descripción:</label>
            <textarea id="descripcion" v-model="descripcion"
              class="border-2 border-black rounded-lg shadow-sm p-2 focus:outline-none focus:ring-0 focus:border-black text-lg w-full md:w-[550px] h-20 mb-5"></textarea>
          </div>

          <!-- Medidas -->
          <div class="flex items-center mb-9">
            <label for="medidas" class="text-black font-medium text-xl w-[200px] md:w-[100px] -mt-4">Medidas:</label>
            <input id="medidas" type="text" v-model="medidas"
              class="border-2 border-black rounded-lg shadow-sm p-2 pr-8 focus:outline-none text-lg w-full md:w-[450px] mb-4" maxlength="30"/>
          </div>

          <div class="flex flex-col md:flex-row justify-between items-center mt-6 ml-0 md:-ml-[282px]">
            <div class="flex flex-col space-y-4">
              <!-- Unidad -->
              <div class="flex items-center mb-4">
                <label for="unidad" class="text-black font-medium text-xl  w-[105px] md:w-[90px]">Unidad:</label>
                <select id="unidad" v-model="unidad"
                  class="border-2 border-black rounded-lg shadow-sm p-2 focus:outline-none focus:ring-0 focus:border-black text-base md:text-lg appearance-none w-[170px] md:w-[200px]"
                  style="background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 fill=%27none%27 viewBox=%270 0 24 24%27 stroke=%27currentColor%27%3e%3cpath stroke-linecap=%27round%27 stroke-linejoin=%27round%27 stroke-width=%272%27 d=%27M19 9l-7 7-7-7%27 /%3e%3c/svg%3e'); background-repeat: no-repeat; background-position: right 0.5rem center; background-size: 1.5em;">
                  <option v-for="(unidadItem, index) in unidades" :key="index" :value="unidadItem">
                    {{ unidadItem }}
                  </option>
                </select>
              </div>

              <!-- Familia -->
              <div class="flex items-center">
                <label for="familia" class="text-black font-medium text-xl w-[105px] md:w-[90px] -mt-8">Familia:</label>
                <select id="familia" v-model="id_family"
                  class="border-2 border-black rounded-lg shadow-sm p-2 focus:outline-none focus:ring-0 focus:border-black text-base md:text-lg appearance-none w-[170px] md:w-[200px] mb-8"
                  style="background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 fill=%27none%27 viewBox=%270 0 24 24%27 stroke=%27currentColor%27%3e%3cpath stroke-linecap=%27round%27 stroke-linejoin=%27round%27 stroke-width=%272%27 d=%27M19 9l-7 7-7-7%27 /%3e%3c/svg%3e'); background-repeat: no-repeat; background-position: right 0.5rem center; background-size: 1.5em;">
                  <option v-for="(familiaItem, index) in familias" :key="index" :value="familiaItem.id">
                    {{ familiaItem.name }}
                  </option>
                </select>
              </div>
            </div>

            <!-- Botones -->
            <div
              class="flex flex-col sm:flex-row justify-end items-center space-y-4 sm:space-y-0 sm:space-x-10 w-full md:w-auto mr-0 md:-mr-[180px]">
              <button v-if="$route.path === '/editar-producto'" @click.prevent="eliminar"
                class="bg-[#FF594D] text-black w-full md:w-[200px] h-[45px] rounded-xl text-lg">
                Eliminar
              </button>
              <button v-if="$route.path === '/editar-producto'" @click.prevent="guardar"
                class="bg-[#98D887] text-black w-full md:w-[200px] h-[45px] rounded-xl text-lg">
                Guardar
              </button>
            </div>
            <div
              class="flex flex-col sm:flex-row justify-end items-center space-y-4 sm:space-y-0 sm:space-x-10 w-full md:w-auto md:mr-[220px]">
              <button v-if="$route.path === '/registro-productos'" @click.prevent="registrar"
                class="bg-[#98D887] text-black w-full md:w-[250px] h-[45px] rounded-xl text-lg">
                Registrar
              </button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import methods from '@/methods';
export default {
  name: "ProductosForm",
  data() {
    return {
      name: "",
      descripcion: "",
      medidas: "",
      unidad: "",
      familia: "",
      id_family: null,
      id: null,
      active: true,
      previewImage:
        this.$route.path === "/editar-producto"
          ? require("@/assets/productexample.svg")
          : require("@/assets/exampleimg.svg"), // Cambia la imagen según la ruta
      unidades: ["Pieza", "Docena", "Caja", "Litro", "Kilo", "Metros","Paquete"], // Arreglo de unidades
      familias: ["Papelería", "Limpieza", "Ferretería", "Juguetes", "Utensilios",], // Arreglo de familias
      image: null
    };
  },
  methods: {
    handleImageUpload(event) {
      const file = event.target.files[0];
      if (file) {
        this.image = file;
        const reader = new FileReader();
        reader.onload = (e) => {
          this.previewImage = e.target.result; // Actualiza la previsualización
        };
        reader.readAsDataURL(file);
      }
    },
    eliminar() {
      this.$axios.delete(`/eliminar-producto/${this.id}`).then(response => {
        alert(response.data.message);
        this.$router.back(1);
      }).catch(error => {
        alert(error.response.data.message);
      });
    },
    guardar() {
      const formData = new FormData();
      formData.append('name', this.name);
      formData.append('unit', this.unidad);
      formData.append('description', this.descripcion);
      formData.append('dimension', this.medidas);
      formData.append('active', this.active ? 1 : 0);
      formData.append('id_family', this.id_family);

      if (this.image) {
        formData.append('image', this.image);
      }
      this.$axios.post(`/actualizar-producto/${this.id}`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      }).then(response => {
        alert(response.data.message);
        this.$router.back(1);
      }).catch(error => {
        alert(error.response.data.message);
        console.error('Error al obtener la información:', error.response);
      });
    },
    registrar() {
      const formData = new FormData();
      formData.append('name', this.name);
      formData.append('unit', this.unidad);
      formData.append('description', this.descripcion);
      formData.append('dimension', this.medidas);
      formData.append('active', this.active ? 1 : 0);
      formData.append('id_family', this.id_family);

      if (this.image) {
        formData.append('image', this.image);
      }

      this.$axios.post('/registrar-producto', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      }).then(response => {
        alert(response.data.message);
        this.$router.back(1);
      }).catch(error => {
        alert(error.response.data.message);
      });
    },
    getFamilies() {
      this.$axios.get('/familias').then(response => {
        this.familias = response.data.familias;
      });
    },
    setInformation() {
      this.name = this.$productAux.name;
      this.previewImage = this.$productAux.image;
      this.medidas = this.$productAux.dimension;
      this.id = this.$productAux.id;
      this.unidad = this.$productAux.unit;
      this.descripcion = this.$productAux.description;
      this.active = this.$productAux.active;
      this.id_family = this.$productAux.id_family;
      methods.deleteAuxProduct(this.$productAux);
    }
  },
  mounted() {
    this.getFamilies();
    if (this.$route.path === '/editar-producto')
      this.setInformation();
  }
};
</script>

<style scoped>
input:focus {
  outline: none;
  box-shadow: none;
  border-color: black; 
}

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
