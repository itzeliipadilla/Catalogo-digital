<template>
  <div>
    <!-- Para pantallas grandes -->
    <div class="hidden sm:block bg-cover bg-center h-[400px] relative"
      :style="{
        backgroundImage: 'url(' + require('@/assets/fondocliente.svg') + ')',
      }"
    >
      <img src="@/assets/iconocliente.svg" alt="Icono Cliente" class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[400px]"/>
      <img src="@/assets/lema.png" alt="Lema" class="absolute bottom-[110px] w-[800px] -ml-[90px]"/>
      <div class="absolute top-[50%] right-8 transform -translate-y-1/2 text-black text-2xl mr-[60px] font-semibold w-[400px] text-left tracking-wider">Variedad, calidad y estilo en un solo lugar</div>
    </div>

    <!-- Para pantallas pequeñas -->
    <div
      class="block sm:hidden bg-cover bg-center h-[400px] relative flex items-center justify-between px-8 overflow-hidden"
      :style="{
        backgroundImage: 'url(' + require('@/assets/bannercliente.svg') + ')',
      }"
    >
      <img src="@/assets/lemaclientes.png" alt="Lema Cliente" class="w-auto h-auto max-w-[80%] max-h-[80%] object-contain -ml-[70px]"/>
      <img src="@/assets/iconocliente.png" alt="Icono Cliente" class="w-auto h-auto max-w-[98%] max-h-[100%] object-contain -ml-[76px]"/>
    </div>
  </div>
</template>
