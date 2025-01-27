<template>
  <div>
    <el-container>
      <el-header>
        <el-menu :default-active="activeIndex" class="el-menu-demo" mode="horizontal" @select="handleSelect">
          <el-menu-item index="1">Início</el-menu-item>
          <el-menu-item index="2">Painel</el-menu-item>
          <el-menu-item index="3">Entrar</el-menu-item>
        </el-menu>
      </el-header>
      <el-main>
        <h1>Bem-vindo à Página Inicial</h1>
        <el-button type="primary" @click="navigateTo('dashboard')">Ir para o Painel</el-button>
      </el-main>
    </el-container>
  </div>
</template>

<script>
export default {
  data() {
    return {
      activeIndex: '1',
    };
  },
  methods: {
    handleSelect(key, keyPath) {
      console.log(key, keyPath);
    },
    navigateTo(route) {
      this.$router.push({ name: route });
    },
  },
};
</script>

<style scoped>
.el-menu-demo {
  background-color: #545c64;
  color: #fff;
}
</style>
