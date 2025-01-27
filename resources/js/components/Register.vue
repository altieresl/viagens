<template>
  <div>
    <h1 class="header">Registrar</h1>
    <div class="register-container">
      <el-form :model="registerForm" @submit.prevent="handleRegister">
        <el-form-item label="Nome">
          <el-input v-model="registerForm.name" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="E-mail">
          <el-input v-model="registerForm.email" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="Senha">
          <el-input type="password" v-model="registerForm.password" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="Confirmar Senha">
          <el-input type="password" v-model="registerForm.password_confirmation" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button :loading="loading" :disabled="loading" type="primary" native-type="submit">Registrar</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
import api from '../services/api';
import { ElNotification } from 'element-plus';

export default {
  data() {
    return {
      registerForm: {
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
      },
      loading: false
    };
  },
  methods: {
    async handleRegister() {
      this.loading = true;
      try {
        await api.post('/register', this.registerForm);
        ElNotification({
          title: 'Sucesso',
          message: 'Conta criada com sucesso!',
          type: 'success',
        });
        this.$router.push('/login');
      } catch (error) {
        let errorMessage = 'Falha ao criar conta. Por favor, tente novamente.';
        if (error.response && error.response.data && error.response.data.error) {
          errorMessage += ` ${error.response.data.error}`;
        }
        ElNotification({
          title: 'Erro',
          message: errorMessage,
          type: 'error',
        });
      } finally {
        this.loading = false;
      }
    }
  }
};
</script>

<style scoped>
.header {
  text-align: center;
  padding: 20px 0;
  position: fixed;
  width: 100%;
  font-size: 2em;
  color: #409EFF
}

.register-container {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  height: 100vh;
  padding: 20px;
  border: 1px solid #dcdfe6;
  border-radius: 4px;
  box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
  background-color: #ffffff;
}
</style>
