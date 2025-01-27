<template>
  <div>
    <h1 class="header">Viagens</h1>
    <div class="login-container">
      <el-form :model="loginForm" @submit.prevent="handleLogin">
        <el-form-item label="E-mail">
          <el-input v-model="loginForm.email" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="Senha">
          <el-input type="password" v-model="loginForm.password" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button :loading="loading" :disabled="loading" type="primary" native-type="submit">Entrar</el-button>
        </el-form-item>
        <el-form-item>
          <router-link to="/register">Criar uma nova conta</router-link>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
import api from '../services/api';
import { mapActions } from 'vuex';
import { ElNotification } from 'element-plus';
import { useRouter } from 'vue-router';

export default {
  data() {
    return {
      loginForm: {
        email: '',
        password: ''
      },
      loading: false
    };
  },
  methods: {
    ...mapActions(['login']),
    async handleLogin() {
      this.loading = true;
      try {
        const response = await api.post('/login', this.loginForm);
        const token = response.data.token;
        const user_id = response.data.user_id; // Get user_id from response
        const admin = response.data.admin; // Get admin from response
        const notifications = response.data.notifications; // Get notifications from response

        localStorage.setItem('token', token);
        localStorage.setItem('user_id', user_id); // Save user_id to localStorage
        localStorage.setItem('is_admin', admin); // Save admin to localStorage

        ElNotification({
          title: 'Sucesso',
          message: 'Login realizado com sucesso!',
          type: 'success',
        });

        // Show notifications if there are any
        if (notifications && notifications.length > 0) {
          notifications.forEach((notification, index) => {
            setTimeout(() => {
              ElNotification({
                title: notification.data.title,
                message: notification.data.message,
                type: 'info',
              });
            }, (index+1) * 500); // Add a delay of 500ms between notifications
          });
        }

        this.$router.push('/');
      } catch (error) {
        ElNotification({
          title: 'Erro',
          message: 'Falha no login. Por favor, tente novamente.',
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

.login-container {
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
