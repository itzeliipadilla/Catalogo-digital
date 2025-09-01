<template>
  <div class="flex h-screen">
    <!-- Spinner para generar PDF -->
    <div v-if="isGeneratingPDF"
      class="fixed inset-0 bg-gray-800 bg-opacity-50 z-50 flex flex-col justify-center items-center">
      <SpinnerGreen :fullscreen="false" />
      <div class="mt-4 w-64 bg-gray-300 rounded-full">
        <div class="bg-green-500 h-4 rounded-full transition-all" :style="{ width: `${pdfProgress}%` }"></div>
      </div>
      <p class="text-white mt-2">{{ pdfProgress }}%</p>
      <button @click="cancelPDFGeneration" class="mt-4 px-4 py-2 bg-[#FF594D] text-white rounded-xl transition">
        Cancelar
      </button>
    </div>

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
          <h1 class="text-3xl font-normal mt-4 md:mt-12 mb-8 ml-0 md:ml-5">
            Generar catálogo
          </h1>
          <!-- Contenedor -->
          <div class="bg-white p-6 rounded-xl shadow-md max-w-[1160px] mx-auto my-8 ml-0 md:ml-5">
            <h2 class="text-xl font-base mb-8 ml-2 md:ml-6 mt-3">
              Seleccione las familias de productos que desea incluir en su catálogo personalizado
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 ml-0 md:ml-3">
              <div v-for="(familia, index) in familias" :key="index" class="flex items-center gap-4 p-4">
                <input type="checkbox" :id="`familia-${index}`" :disabled="isGeneratingPDF"
                  class="custom-checkbox w-5 h-5 mt-[3px] rounded-lg" v-model="selectedFamilias" :value="familia" />
                <label :for="`familia-${index}`" class="text-black text-xl">{{ familia }}</label>
              </div>
            </div>
            <!-- Botón Generar Catálogo -->
            <div class="flex justify-end mt-6">
              <button @click="generatePDF" :disabled="isGeneratingPDF"
                class="bg-[#FFCF00] text-black px-6 py-2 rounded-xl mr-0 md:mr-4 font-base text-lg
                      hover:bg-[#e6b800] transition-colors duration-200
                      disabled:bg-gray-400 disabled:text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed">
                Generar catálogo
              </button>
            </div>
          </div>

          <h1 class="text-3xl font-normal mt-4 md:mt-12 mb-8 ml-0 md:ml-5">
            Previsualización
          </h1>
          <!-- Contenedor oculto para la generación del PDF -->
          <div
            class="overflow-x-auto px-4 py-2 w-[370px] md:w-[200mm] bg-white border-none shadow-md rounded-xl ml-0 md:ml-5">
            <div id="catalog"
              class="catalog-container flex flex-wrap justify-center bg-white p-[10mm] box-border w-[185.4mm] h-[261.8mm] overflow-y-auto pb-[20mm]">
              <ProductPrint v-for="(product, index) in products" :key="product.id" :product="product" :index="index"
                @image-loaded="onImageLoad" />
            </div>
          </div>
          <div style="height: 30px; width: 30px"></div>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import SpinnerGreen from "@/components/SpinnerGreen.vue";
import ProductPrint from "@/components/ProductPrint.vue";
import basicosBg from "@/assets/basicos-background.png";
import celularBg from "@/assets/celular-background.png";
import ferreteriaBg from "@/assets/ferreteria-background.png";
import jaiceriaBg from "@/assets/jarcieria-background.png";
import novedadesBg from "@/assets/novedades-background.png";
import papeleriaBg from "@/assets/papeleria-background.png";
import sandaliasBg from "@/assets/sandalias-background.png";
import temporadaBg from "@/assets/temporada-background.png";
import jsPDF from "jspdf";
import html2canvas from "html2canvas";

export default {
  name: "CatalogosView",
  components: {
    SpinnerGreen,
    ProductPrint,
  },
  data() {
    return {
      loading: false,
      isGeneratingPDF: false,
      familias: [
        "Básicos",
        "Novedades",
        "Artículos de temporada",
        "Artículos escolares y papelería",
        "Ferretería",
        "Jarciería",
        "Sandalias",
        "Accesorios para celular",
      ],
      selectedFamilias: [],
      products: [],
      loadedImagesCount: 0,
      allImagesLoaded: false,
      pdfProgress: 0,
      isCancelled: false,
      abortController: null,
    };
  },
  watch: {
    selectedFamilias: {
      handler: "loadProducts",
      immediate: true,
      deep: true,
    },
  },
  methods: {
    loadProducts() {
      if (this.selectedFamilias.length === 0) {
        this.products = [];
        return;
      }

      this.loading = true;

      this.$axios
        .post("/productos-catalogo", {
          familias: this.selectedFamilias,
        })
        .then((response) => {
          this.products = response.data.products;
          this.loading = false;
          this.loadedImagesCount = 0;
        })
        .catch((error) => {
          console.error("Error al obtener los productos:", error);
          this.loading = false;
        });
    },
    onImageLoad() {
      this.loadedImagesCount++;
      if (this.loadedImagesCount === this.products.length) {
        this.allImagesLoaded = true;
      }
    },
    async generatePDF() {
      if (!this.allImagesLoaded) {
        alert("Por favor espera a que todas las imágenes se carguen.");
        return;
      }

      this.isGeneratingPDF = true;
      this.isCancelled = false; // Reinicia el estado de cancelación
      this.pdfProgress = 0;

      // Crear un nuevo AbortController para esta operación
      this.abortController = new AbortController();
      const { signal } = this.abortController;

      const doc = new jsPDF("p", "mm", "a4");
      const pageWidth = 210;
      const pageHeight = 297;
      const marginTop = 65;
      const marginBottom = 40;
      const safeMarginX = 20;
      const usableWidth = pageWidth - 2 * safeMarginX;
      const usableHeight = pageHeight - marginTop - marginBottom;

      const backgroundImages = {
        "Básicos": basicosBg,
        "Novedades": novedadesBg,
        "Artículos de temporada": temporadaBg,
        "Artículos escolares y papelería": papeleriaBg,
        "Ferretería": ferreteriaBg,
        "Jarciería": jaiceriaBg,
        "Sandalias": sandaliasBg,
        "Accesorios para celular": celularBg,
      };

      const products = document.getElementsByClassName("product-card");
      const totalProducts = products.length;

      try {
        let currentPageProducts = [];
        let totalBlockHeight = 0;
        let currentFamily = this.products[0]?.family;

        for (let i = 0; i < products.length; i++) {
          if (this.isCancelled) {
            throw new Error("PDF generation cancelled");
          }

          const productCard = products[i];
          const productFamily = this.products[i].family;

          if (productFamily !== currentFamily || totalBlockHeight > usableHeight) {
            if (currentPageProducts.length > 0) {
              const verticalOffset = (usableHeight - totalBlockHeight) / 2;
              let yPosition = marginTop + verticalOffset;

              const backgroundImg = backgroundImages[currentFamily];
              doc.addImage(backgroundImg, "JPEG", 0, 0, pageWidth, pageHeight);

              currentPageProducts.forEach(({ imgData, imgHeight }) => {
                doc.addImage(imgData, "JPEG", safeMarginX, yPosition, usableWidth, imgHeight);
                yPosition += imgHeight + 10;
              });

              doc.addPage();
            }

            currentPageProducts = [];
            totalBlockHeight = 0;
            currentFamily = productFamily;
          }

          // Usa html2canvas con AbortController
          const canvas = await html2canvas(productCard, {
            scale: 2,
            useCORS: true,
            backgroundColor: null,
            signal, // Pasar el AbortSignal
          });

          const imgData = canvas.toDataURL("image/jpeg", 1);
          const imgHeight = (canvas.height * usableWidth) / canvas.width;

          currentPageProducts.push({ imgData, imgHeight });
          totalBlockHeight += imgHeight + (currentPageProducts.length > 1 ? 10 : 0);

          // Actualiza el progreso
          this.pdfProgress = Math.round(((i + 1) / totalProducts) * 100);
        }

        if (currentPageProducts.length > 0) {
          const verticalOffset = (usableHeight - totalBlockHeight) / 2;
          let yPosition = marginTop + verticalOffset;

          const lastBackgroundImg = backgroundImages[currentFamily];
          doc.addImage(lastBackgroundImg, "JPEG", 0, 0, pageWidth, pageHeight);

          currentPageProducts.forEach(({ imgData, imgHeight }) => {
            doc.addImage(imgData, "JPEG", safeMarginX, yPosition, usableWidth, imgHeight);
            yPosition += imgHeight + 10;
          });
        }

        doc.save("catalogo.pdf");
      } catch (error) {
        if (error.name === "AbortError") {
          alert("Generación del PDF cancelada.");
        } else {
          alert("Generación del PDF cancelada.");
        }
      } finally {
        this.isGeneratingPDF = false;
        this.pdfProgress = 0; // Reinicia el progreso
        this.abortController = null; // Limpia el AbortController
      }
    },
    cancelPDFGeneration() {
      if (this.abortController) {
        this.abortController.abort(); // Cancela tareas asíncronas activas
      }
      this.isCancelled = true; // Marca como cancelado
    },
  },
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

.transition-all {
  transition: width 0.3s ease;
}

.custom-checkbox:checked {
  background-color: #98d887;
  border-color: #98d887;
}

.custom-checkbox:checked::after {
  content: "✔";
  /* Palomita */
  color: white;
  font-size: 0.75rem;
  font-weight: bold;
}

.custom-checkbox:focus {
  outline: none;
  box-shadow: none;
}

@media print {
  .catalog-container {
    width: 210mm;
    /* Ancho A4 */
    height: 297mm;
    /* Altura A4 */
  }
}
</style>
