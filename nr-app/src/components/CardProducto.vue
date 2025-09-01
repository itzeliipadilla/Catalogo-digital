<template>
  <div>
    <div class="bg-[#DFFFD7]">
      <div class="grid grid-cols-2 sm:grid-cols-2 md:flex md:flex-wrap md:justify-center md:gap-6 items-center gap-4 px-2 md:px-8">
        <div v-for="(product, index) in products" :key="index" class="w-[190px] md:w-[250px] h-[280px] md:h-[340px] bg-white rounded-xl shadow-md overflow-hidden relative mb-4 p-4">
          <!-- Contenedor de la imagen -->
          <div class="flex justify-center items-center p-4">
            <img :src="product.image" :alt="product.nombre" class="w-[100px] md:w-[190px] h-[100px] md:h-[160px] object-contain"/>
          </div>
          <!-- Contenido -->
          <div class="text-left -mt-[10px]">
            <!-- Familia -->
            <p class="text-gray-500 text-xs md:text-sm">{{ product.familia }}</p>
            <!-- Nombre del producto -->
            <h3 class="text-gray-800 font-bold text-xs md:text-2xl mt-1 h-[65px]" :style="getTitleStyle(product.nombre)">
              {{ product.nombre }}
            </h3>
            <!-- Botón para abrir el modal -->
            <div class="mt-1">
              <button class="bg-[#FFCF00] text-black text-xs md:text-sm font-normal px-6 py-1 rounded-xl transition mt-1" @click="openModal(product)">
                Ver más
              </button>
            </div>
          </div>
          <!-- Logo (esquina superior derecha de la card) -->
          <div class="absolute top-3 right-3">
            <img  src="@/assets/novedades.png" alt="Logo" class="w-[35px] md:w-[45px] h-[35px] md:h-[45px]"/>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div v-if="isModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
      <div class="bg-white rounded-lg p-8 w-11/12 sm:w-3/4 md:w-1/2 flex md:flex-row flex-col relative">
        <button @click="closeModal" class="absolute top-3 right-3 text-black font-bold text-xl">
          <svg class="w-6 h-6 text-back" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
          </svg>
        </button>
        <!-- Imagen del producto dentro del modal -->
        <div class="flex justify-center items-center flex-shrink-0 w-full h-[200px] mb-4 md:mb-0 md:w-[200px] md:h-[200px] md:mr-8">
          <img :src="selectedProduct.image" :alt="selectedProduct.nombre" class="w-full h-full object-contain"/>
        </div>

        <!-- Contenido del producto -->
        <div class="flex-1 text-center md:text-left">
          <h3 class="text-2xl font-bold">{{ selectedProduct.nombre }}</h3>
          <p class="mt-4">{{ selectedProduct.descripcion }}</p>
          <p class="mt-2"><strong>Unidad:</strong> {{ selectedProduct.unidad }}</p>
          <p class="mt-2"><strong>Medidas:</strong> {{ selectedProduct.medida }}</p>
          <p class="mt-2"><strong>Familia:</strong> {{ selectedProduct.familia }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    products: Array, // Recibir los productos como prop
  },
  data() {
    return {
      isModalOpen: false,
      selectedProduct: null,
    };
  },
  methods: {
    openModal(product) {
      this.selectedProduct = product;
      this.isModalOpen = true;
    },
    closeModal() {
      this.isModalOpen = false;
      this.selectedProduct = null;
    },
    getTitleStyle(title) {
      let fontSize = "1.25rem";
      if (title.length > 20) {
        fontSize = "1.125rem";
      }
      return { fontSize };
    },
  },
};
</script>

<style scoped>
.fixed {
  z-index: 9999;
}
</style>
