<template>
  <div
    class="flex justify-center items-center"
    :class="{ 'h-screen': fullscreen }"
  >
    <div
      class="animate-spin rounded-full h-16 w-16 border-t-4 border-green-500"
      :class="{ 'h-12 w-12': !fullscreen }"
    ></div>
  </div>
</template>

<script>
export default {
  name: "GreenSpinner",
  props: {
    fullscreen: {
      type: Boolean,
      default: false,
    },
  },
};
</script>