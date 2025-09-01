<template>
  <div class="p-6 -ml-[20px] md:-ml-[2px]">
    <!-- Tabla -->
    <table class="w-[324px] md:min-w-full border-collapse shadow-lg rounded-xl overflow-hidden text-base md:text-lg">
      <thead class="bg-[#ABE29B]">
        <tr>
          <th v-for="(header, index) in filteredHeaders" :key="index" class="py-2 px-4 text-center text-black">
            {{ header }}
          </th>
        </tr>
      </thead>
      <tbody class="bg-white">
        <tr v-for="(row, index) in paginatedRows" :key="index" @click="handleRowClick(row)"
          class="cursor-pointer hover:bg-yellow-100">
          <td v-for="(value, key) in row" :key="key" class="py-2 px-4 text-center align-middle">
            {{ value }}
          </td>
        </tr>
      </tbody>
    </table>
    <!-- Controles de paginación -->
    <div class="mt-8 flex justify-center">
      <fwb-pagination v-model="currentPage" :total-pages="totalPages" previous-label="<" next-label=">" class="pagination"></fwb-pagination>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue';
import { FwbPagination } from 'flowbite-vue';
import methods from '@/methods';

export default {
  name: "DataTable",
  components: {
    FwbPagination, // Registramos el componente aquí
  },
  props: {
    headers: {
      type: Array,
      required: true,
    },
    rows: {
      type: Array,
      required: true,
    },
    visibleColumnsMobile: {
      type: Array,
      required: true,
    },
    baseRoute: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      visibleColumns: [],
      pageSize: 5, // Tamaño de página por defecto
    };
  },
  computed: {
    filteredHeaders() {
      return this.headers.filter((_, index) =>
        this.visibleColumns.includes(index)
      );
    },
    paginatedRows() {
      // Filtrar y luego aplicar la paginación
      const filtered = this.rows.map((row) => {
        const filteredRow = {};
        Object.keys(row).forEach((key, index) => {
          if (this.visibleColumns.includes(index)) {
            filteredRow[key] = row[key];
          }
        });
        return filteredRow;
      });

      const start = (this.currentPage - 1) * this.pageSize;
      const end = this.currentPage * this.pageSize;
      return filtered.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.rows.length / this.pageSize);
    },
  },
  mounted() {
    // Columnas visibles
    this.setVisibleColumns();
    // Actualización de columnas
    window.addEventListener("resize", this.setVisibleColumns);
  },
  beforeUnmount() {
    window.removeEventListener("resize", this.setVisibleColumns);
  },
  methods: {
    setVisibleColumns() {
      if (window.innerWidth < 768) {
        this.visibleColumns = this.visibleColumnsMobile;
      } else {
        this.visibleColumns = this.headers.map((_, index) => index);
      }
    },
    handleRowClick(row) {
      if (row.role) {
        this.$axios.get(`/get-user/${row.id}`).then(response => {
          methods.updateAuxProperties(this.$auxiliar, response.data.user);
          this.$router.push(`${this.baseRoute}`);
        }).catch(error => {
          console.error('Error al obtener la información:', error.response);
          alert(error.response.data.message);
        });
      } else {
        this.$axios.get(`/producto/${row.id}`).then(response => {
          methods.updateAuxProduct(this.$productAux, response.data.product);
          this.$router.push('/editar-producto');
        }).catch(error => {
          console.error('Error al obtener la información:', error.response);
          alert(error.response.data.message);
        });
      }
    },
  },
  setup() {
    const currentPage = ref(1);
    return { currentPage };
  },
}
</script>

<style scoped>
::v-deep .pagination button {
  background-color: white !important;
  color: #000 !important;
  border: 1px solid #ffffff !important;
   box-shadow: 0 5px 5px rgba(0, 0, 0, 0.1) !important; 
  padding: 8px;
}

::v-deep .pagination button:hover {
  background-color: #DCFFD0 !important;
  color: #000000 !important;
}

::v-deep .pagination button[disabled] {
  background-color: #ffffff !important;
  color: #36c110 !important;
  cursor: not-allowed !important;
}
</style>