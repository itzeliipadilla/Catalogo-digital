<template>
  <div :class="['product-card', positionClass, categoryClass]">
    <img :src="imagen" alt="Imagen del producto" class="product-image" @load="onImageLoad" />
    <div class="product-info">
      <div class="product-title">
        <h2><strong>{{ product.name }}</strong></h2>
      </div>
      <div class="product-description">
        <p>{{ product.description }}</p>
      </div>
      <div class="product-dimension">
        <p><strong>Medidas:</strong> {{ product.dimension }}</p>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  props: {
    product: {
      type: Object,
      required: true
    },
    index: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      imagen: ''
    }
  },
  computed: {
    positionClass() {
      return this.index % 2 === 0 ? 'left' : 'right';
    },
    categoryClass() {
      switch (this.product.family) {
        case 'Básicos':
          return 'category-basicos';
        case 'Novedades':
          return 'category-novedades';
        case 'Artículos de temporada':
          return 'category-temporada';
        case 'Artículos escolares y papelería':
          return 'category-escolar';
        case 'Ferretería':
          return 'category-ferreteria';
        case 'Jarciería':
          return 'category-jarciería';
        case 'Sandalias':
          return 'category-sandalias';
        case 'Accesorios para celular':
          return 'category-celular';
        default:
          return '';
      }
    }
  },
  methods: {
    onImageLoad() {
      this.$emit('image-loaded');
    }
  },
  mounted() {
    this.$axios({
      url: `/getImage/${this.product.image}`, // Usa la URL relativa
      method: 'GET',
      responseType: 'blob',
    })
      .then(response => {
        const reader = new FileReader();
        reader.readAsDataURL(response.data);
        reader.onloadend = () => {
          this.imagen = reader.result;
        };
      })
      .catch(error => {
        console.error('Error al descargar el archivo:', error);
      });
  }
};
</script>

<style scoped>
.product-card {
  display: flex;
  align-items: center;
  justify-content: space-between;
  border: 1px solid black;
  padding: 16px;
  margin: 10px;
  width: 180mm;
  /* Ajuste de ancho para impresión A4 */
  box-sizing: border-box;
  /* Incluir padding en las dimensiones */
  background-color: #ffffff;
}

.product-image {
  width: 30%;
  height: auto;
}

.product-info {
  width: 65%;
}

.product-card.left .product-image {
  order: 1;
}

.product-card.right .product-image {
  order: -1;
}

.product-info {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  height: 100%;
}

.product-title {
  margin-bottom: auto;
  /* Asegura que el título esté en la parte superior */
}

.product-description {
  margin: auto 0;
  /* Centra la descripción verticalmente */
}

.product-dimension {
  margin-top: auto;
  /* Asegura que la medida esté en la parte inferior */
}
</style>
