<template>
  <div id="app">
    <CalculatorComponent />
  </div>
</template>

<script>
import CalculatorComponent from './components/CalculatorComponent.vue';

export default {
  name: 'App',
  components: {
    CalculatorComponent
  }
};
</script>

<style>
#app {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background-color: #ffff;
}
</style>
