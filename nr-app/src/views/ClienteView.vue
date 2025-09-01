<template>
  <div>
    <!-- Barra de navegación -->
    <div class="bg-white flex justify-between items-center h-[90px] shadow-md relative z-10">
      <!-- Logo -->
      <img src="@/assets/logo.png" alt="Logo" class="w-[140px] h-auto ml-3 md:ml-8" />
      <!-- Botón Ingresar -->
      <button @click="$router.push('/login')"
        class="bg-[#FFCF00] text-black text-lg h-[40px] px-6 rounded-xl mr-4 md:mr-10">Ingresar</button>
    </div>

    <BannerCliente />
    <!-- Titulo Productos -->
    <div class="py-10 text-center">
      <h2 class="text-3xl font-semibold mt-2">Productos</h2>
    </div>

    <div class="flex justify-center -mt-2">
      <!-- Buscador -->
      <div class="relative w-[325px] md:w-1/3 mb-2 md:mb-0">
        <span class="absolute inset-y-0 left-3 flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M11 4a7 7 0 1 0 0 14 7 7 0 0 0 0-14zM21 21l-4.35-4.35" />
          </svg>
        </span>
        <input v-model="searchTerm" type="text" placeholder="Buscar"
          class="w-full pl-10 pr-4 py-3 text-base md:text-lg rounded-xl shadow-lg border-none focus:border-none focus:ring-0 placeholder-gray-400 focus:outline-none" />
      </div>
    </div>

    <div class="flex items-center justify-center mt-12 mb-12">
      <!-- Barra de categorías -->
      <div ref="categoryContainer"
        class="flex justify-start gap-3 flex-nowrap w-[300px] md:w-[1160px] h-[60px] overflow-x-auto scroll-smooth scroll-container">
        <div v-for="category in categories" :key="category.name"
          class="flex items-center gap-2 px-4 py-3 bg-white border-transparent rounded-xl shadow-md cursor-pointer mb-3"
          @click="handleCategoryClick(category)" :data-category="category.name" :style="{
            'min-width':
              category.name === 'Artículos de temporada'
                ? '263px'
                : category.name === 'Artículos escolares y papelería'
                  ? '325px'
                  : category.name === 'Accesorios para celular'
                    ? '270px'
                    : 'auto',
          }">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" :class="{
            'text-[#129938]': selectedCategory === category.name,
            'text-black': selectedCategory !== category.name,
          }" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
            v-html="category.icon" />
          <!-- Texto -->
          <span class="text-black font-normal text-lg" :style="{
            color: selectedCategory === category.name ? '#129938' : 'black',
          }" :class="{
            'font-semibold': selectedCategory === category.name,
            'font-normal': selectedCategory !== category.name,
          }"> {{ category.name }}</span>
        </div>
      </div>
    </div>

    <!-- Mensaje si no hay productos o no se encuetran -->
    <div v-if="filteredProducts.length === 0 && !loading" class="text-center mt-12">
      <p v-if="searchTerm" class="text-xl font-base text-gray-500">
        Lo sentimos, no encontramos resultados para "{{ searchTerm }}".
      </p>
      <p v-else class="text-xl font-base text-gray-500">
        No hay productos disponibles en esta categoría.
      </p>
    </div>

    <div v-if="loading" class="flex justify-center items-center h-full">
      <SpinnerGreen :fullscreen="true" />
    </div>
    <div v-else>
      <div class="mb-[80px]">
        <CardProducto :products="filteredProducts" />
      </div>
    </div>
    <footer ref="footer" class="relative w-full">
      <!-- Fondo del footer -->
      <div class="absolute inset-0">
        <img src="@/assets/footer.svg" alt="Footer Background" class="w-full h-full object-cover" />
      </div>
      <div
        class="relative z-10 flex flex-col md:flex-row justify-between items-center py-6 px-8 w-full space-y-6 md:space-y-0">
        <div class="text-black text-center md:text-left w-full md:w-auto mt-0 md:mt-0">
          <p class="font-bold text-lg md:text-xl">¡Contáctanos!</p>
          <p class="text-sm md:text-lg">novedadesramirez@gmail.com</p>
        </div>
        <!-- Número de teléfono -->
        <div class="text-center md:text-left text-sm md:text-lg w-full md:w-auto mt-4 md:mt-0 md:mr-[125px]">
          <p>+52 481 152 1758</p>
        </div>
        <!-- Redes sociales -->
        <div class="flex justify-center md:justify-end gap-4 text-sm md:text-lg mt-4 md:mt-0">
          <svg class="w-6 h-6 md:w-7 md:h-7 text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
            height="24" fill="none" viewBox="0 0 24 24">
            <path fill="currentColor" fill-rule="evenodd"
              d="M12 4a8 8 0 0 0-6.895 12.06l.569.718-.697 2.359 2.32-.648.379.243A8 8 0 1 0 12 4ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10a9.96 9.96 0 0 1-5.016-1.347l-4.948 1.382 1.426-4.829-.006-.007-.033-.055A9.958 9.958 0 0 1 2 12Z"
              clip-rule="evenodd" />
            <path fill="currentColor"
              d="M16.735 13.492c-.038-.018-1.497-.736-1.756-.83a1.008 1.008 0 0 0-.34-.075c-.196 0-.362.098-.49.291-.146.217-.587.732-.723.886-.018.02-.042.045-.057.045-.013 0-.239-.093-.307-.123-1.564-.68-2.751-2.313-2.914-2.589-.023-.04-.024-.057-.024-.057.005-.021.058-.074.085-.101.08-.079.166-.182.249-.283l.117-.14c.121-.14.175-.25.237-.375l.033-.066a.68.68 0 0 0-.02-.64c-.034-.069-.65-1.555-.715-1.711-.158-.377-.366-.552-.655-.552-.027 0 0 0-.112.005-.137.005-.883.104-1.213.311-.35.22-.94.924-.94 2.16 0 1.112.705 2.162 1.008 2.561l.041.06c1.161 1.695 2.608 2.951 4.074 3.537 1.412.564 2.081.63 2.461.63.16 0 .288-.013.4-.024l.072-.007c.488-.043 1.56-.599 1.804-1.276.192-.534.243-1.117.115-1.329-.088-.144-.239-.216-.43-.308Z" />
          </svg>
          <svg class="w-6 h-6 md:w-7 md:h-7 text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
            height="24" fill="currentColor" viewBox="0 0 24 24">
            <path fill-rule="evenodd"
              d="M13.135 6H15V3h-1.865a4.147 4.147 0 0 0-4.142 4.142V9H7v3h2v9.938h3V12h2.021l.592-3H12V6.591A.6.6 0 0 1 12.592 6h.543Z"
              clip-rule="evenodd" />
          </svg>
          <svg class="w-6 h-6 md:w-7 md:h-7 text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
            height="24" fill="none" viewBox="0 0 24 24">
            <path fill="currentColor" fill-rule="evenodd"
              d="M3 8a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v8a5 5 0 0 1-5 5H8a5 5 0 0 1-5-5V8Zm5-3a3 3 0 0 0-3 3v8a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V8a3 3 0 0 0-3-3H8Zm7.597 2.214a1 1 0 0 1 1-1h.01a1 1 0 1 1 0 2h-.01a1 1 0 0 1-1-1ZM12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm-5 3a5 5 0 1 1 10 0 5 5 0 0 1-10 0Z"
              clip-rule="evenodd" />
          </svg>
        </div>
      </div>
    </footer>

    <!-- Botón de WhatsApp -->
    <div v-if="showWhatsApp">
      <a :href="whatsappUrl" target="_blank"
        class="fixed bottom-5 right-4 md:right-10 bg-[#4bec5f] text-white rounded-full shadow-lg p-3 flex items-center justify-center w-14 h-14 transition-all">
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-8 h-8">
          <path
            d="M20.52 3.478A11.914 11.914 0 0 0 12.09 0C5.455 0 0 5.454 0 12c0 2.12.561 4.22 1.622 6.06L.048 24l6.141-1.596a11.91 11.91 0 0 0 5.9 1.513h.002c6.635 0 12.09-5.455 12.09-12.001a11.897 11.897 0 0 0-3.661-8.438ZM12.09 21.807h-.001a9.766 9.766 0 0 1-4.987-1.38l-.357-.214-3.646.948.975-3.55-.232-.365a9.734 9.734 0 0 1-1.502-5.207c0-5.437 4.421-9.858 9.858-9.858a9.798 9.798 0 0 1 6.994 2.91 9.751 9.751 0 0 1 2.864 6.947c0 5.437-4.421 9.858-9.857 9.858Zm5.408-7.414c-.295-.147-1.744-.863-2.016-.96-.271-.099-.469-.147-.666.148-.197.295-.764.961-.937 1.157-.172.196-.344.221-.639.074-.295-.147-1.243-.457-2.366-1.458-.875-.78-1.465-1.743-1.636-2.038-.171-.295-.018-.455.13-.602.133-.132.295-.345.442-.518.148-.172.197-.295.296-.492.099-.196.049-.37-.025-.518-.074-.148-.666-1.615-.91-2.207-.24-.579-.487-.5-.666-.51h-.571c-.197 0-.518.074-.79.37-.27.296-1.04 1.017-1.04 2.479s1.064 2.875 1.213 3.074c.148.196 2.093 3.2 5.077 4.487.709.307 1.263.49 1.694.628.712.226 1.36.195 1.873.118.571-.085 1.744-.715 1.993-1.407.248-.69.248-1.282.173-1.406-.074-.123-.271-.196-.566-.344Z" />
        </svg>
      </a>
    </div>

  </div>

</template>

<script>
import BannerCliente from "@/components/BannerCliente.vue";
import CardProducto from "@/components/CardProducto.vue";
import SpinnerGreen from "@/components/SpinnerGreen.vue";

export default {
  name: "NavBar",
  components: {
    BannerCliente,
    CardProducto,
    SpinnerGreen
  },
  data() {
    return {
      loading: true,
      searchTerm: "",
      selectedCategory: "Todos",
      showWhatsApp: true, // Visibilidad del botón
      phoneNumber: "+5214811521758", // Número de teléfono
      defaultMessage: "¡Hola! Me gustaría obtener más información.", // Mensaje
      idCategory: 0,
      categories: [
        { id: 0, name: "Todos", icon: `<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.143 4H4.857A.857.857 0 0 0 4 4.857v4.286c0 .473.384.857.857.857h4.286A.857.857 0 0 0 10 9.143V4.857A.857.857 0 0 0 9.143 4Zm10 0h-4.286a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286A.857.857 0 0 0 20 9.143V4.857A.857.857 0 0 0 19.143 4Zm-10 10H4.857a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286a.857.857 0 0 0 .857-.857v-4.286A.857.857 0 0 0 9.143 14Zm10 0h-4.286a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286a.857.857 0 0 0 .857-.857v-4.286a.857.857 0 0 0-.857-.857Z"/>`, },
        { id: 1, name: "Básicos", icon: `<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10V6a3 3 0 0 1 3-3v0a3 3 0 0 1 3 3v4m3-2 .917 11.923A1 1 0 0 1 17.92 21H6.08a1 1 0 0 1-.997-1.077L6 8h12Z"/>`, },
        { id: 2, name: "Novedades", icon: `<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.122 17.645a7.185 7.185 0 0 1-2.656 2.495 7.06 7.06 0 0 1-3.52.853 6.617 6.617 0 0 1-3.306-.718 6.73 6.73 0 0 1-2.54-2.266c-2.672-4.57.287-8.846.887-9.668A4.448 4.448 0 0 0 8.07 6.31 4.49 4.49 0 0 0 7.997 4c1.284.965 6.43 3.258 5.525 10.631 1.496-1.136 2.7-3.046 2.846-6.216 1.43 1.061 3.985 5.462 1.754 9.23Z"/>`, },
        { id: 3, name: "Artículos de temporada", icon: `<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9 9 0 1 1 0-18c1.052 0 2.062.18 3 .512M7 9.577l3.923 3.923 8.5-8.5M17 14v6m-3-3h6"/>`, },
        { id: 4, name: "Artículos escolares y papelería", icon: `<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.779 17.779 4.36 19.918 6.5 13.5m4.279 4.279 8.364-8.643a3.027 3.027 0 0 0-2.14-5.165 3.03 3.03 0 0 0-2.14.886L6.5 13.5m4.279 4.279L6.499 13.5m2.14 2.14 6.213-6.504M12.75 7.04 17 11.28"/>`, },
        { id: 5, name: "Ferretería", icon: `<path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M7.58209 8.96025 9.8136 11.1917l-1.61782 1.6178c-1.08305-.1811-2.23623.1454-3.07364.9828-1.1208 1.1208-1.32697 2.8069-.62368 4.1363.14842.2806.42122.474.73509.5213.06726.0101.1347.0133.20136.0098-.00351.0666-.00036.1341.00977.2013.04724.3139.24069.5867.52125.7351 1.32944.7033 3.01552.4971 4.13627-.6237.8375-.8374 1.1639-1.9906.9829-3.0736l4.8107-4.8108c1.0831.1811 2.2363-.1454 3.0737-.9828 1.1208-1.1208 1.3269-2.80688.6237-4.13632-.1485-.28056-.4213-.474-.7351-.52125-.0673-.01012-.1347-.01327-.2014-.00977.0035-.06666.0004-.13409-.0098-.20136-.0472-.31386-.2406-.58666-.5212-.73508-1.3294-.70329-3.0155-.49713-4.1363.62367-.8374.83741-1.1639 1.9906-.9828 3.07365l-1.7788 1.77875-2.23152-2.23148-1.41419 1.41424Zm1.31056-3.1394c-.04235-.32684-.24303-.61183-.53647-.76186l-1.98183-1.0133c-.38619-.19746-.85564-.12345-1.16234.18326l-.86321.8632c-.3067.3067-.38072.77616-.18326 1.16235l1.0133 1.98182c.15004.29345.43503.49412.76187.53647l1.1127.14418c.3076.03985.61628-.06528.8356-.28461l.86321-.8632c.21932-.21932.32446-.52801.2846-.83561l-.14417-1.1127ZM19.4448 16.4052l-3.1186-3.1187c-.7811-.781-2.0474-.781-2.8285 0l-.1719.172c-.7811.781-.7811 2.0474 0 2.8284l3.1186 3.1187c.7811.781 2.0474.781 2.8285 0l.1719-.172c.7811-.781.7811-2.0474 0-2.8284Z"/>`, },
        { id: 6, name: "Jarciería", icon: `<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9a3 3 0 0 1 3-3m-2 15h4m0-3c0-4.1 4-4.9 4-9A6 6 0 1 0 6 9c0 4 4 5 4 9h4Z"/>`, },
        { id: 7, name: "Sandalias", icon: `<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.583 8.445h.01M10.86 19.71l-6.573-6.63a.993.993 0 0 1 0-1.4l7.329-7.394A.98.98 0 0 1 12.31 4l5.734.007A1.968 1.968 0 0 1 20 5.983v5.5a.992.992 0 0 1-.316.727l-7.44 7.5a.974.974 0 0 1-1.384.001Z"/>`, },
        { id: 8, name: "Accesorios para celular", icon: `<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.427 14.768 17.2 13.542a1.733 1.733 0 0 0-2.45 0l-.613.613a1.732 1.732 0 0 1-2.45 0l-1.838-1.84a1.735 1.735 0 0 1 0-2.452l.612-.613a1.735 1.735 0 0 0 0-2.452L9.237 5.572a1.6 1.6 0 0 0-2.45 0c-3.223 3.2-1.702 6.896 1.519 10.117 3.22 3.221 6.914 4.745 10.12 1.535a1.601 1.601 0 0 0 0-2.456Z"/>`, },
      ],
      products: [],
    };
  },
  computed: {
    whatsappUrl() {
      const baseUrl = "https://wa.me/";
      const formattedMessage = encodeURIComponent(this.defaultMessage);
      return `${baseUrl}${this.phoneNumber}?text=${formattedMessage}`;
    },
    filteredProducts() {
      if (!this.searchTerm) {
        return this.products;
      }
      return this.products.filter(product => {
        return Object.values(product).some(value =>
          String(value).toLowerCase().includes(this.searchTerm.toLowerCase())
        );
      });
    }
  },
  methods: {
    getProducts() {
      this.$axios.get(`/informacion-productos/${this.idCategory}`).then(response => {
        this.products = response.data.products;
        this.loading = false;
      });
    },
    selectCategory(category) {
      this.loading = true;
      this.selectedCategory = category.name;
      this.idCategory = category.id;
      this.getProducts();
    },

    handleCategoryClick(category) {
      this.selectCategory(category);
      this.scrollToCategory(category.name);
    },

    scrollToCategory(categoryName) {
      const container = this.$refs.categoryContainer;
      const categoryElement = container.querySelector(
        `[data-category="${categoryName}"]`
      );

      if (categoryElement) {
        const scrollOffset =
          categoryElement.offsetLeft -
          (container.offsetWidth / 2 - categoryElement.offsetWidth / 2);
        container.scrollTo({
          left: scrollOffset,
          behavior: "smooth",
        });
      }
    },
  },
  mounted() {
    this.getProducts();
    const footer = this.$refs.footer;
    const observer = new IntersectionObserver(
      (entries) => {
        const [entry] = entries;
        this.showWhatsApp = !entry.isIntersecting; // Oculta el botón si el footer es visible
      },
      { threshold: 0.1 }
    );
    if (footer) {
      observer.observe(footer);
    }
  },
};
</script>

<style scoped>
.scroll-container::-webkit-scrollbar {
  width: 0px;
  height: 0px;
}

.scroll-container {
  scrollbar-width: none;
  /* Para Firefox */
  -ms-overflow-style: none;
  /* Para Internet Explorer */
}
</style>