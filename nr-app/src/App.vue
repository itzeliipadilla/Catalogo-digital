<template>
  <div id="app"> 
    <NavBar />
    <router-view />
  </div>
</template>

<script>
import NavBar from './components/NavBar.vue'; 

export default {
  name: 'App',
  components: {
    NavBar,  
  },
};
</script>

<style>
body {
  background-color: #DCFFD0;
  font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto,
    Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif;
}
</style>
