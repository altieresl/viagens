<template>
  <div class="form-container">
    <h1>{{ isEdit ? 'Editar Pedido de Viagem' : 'Cadastrar Novo Pedido de Viagem' }}</h1>
    <el-form @submit.prevent="submitForm" :model="form" label-position="top">
      <el-form-item label="Nome do Solicitante" :required="true">
        <el-input v-model="form.requester_name" />
      </el-form-item>
      <el-form-item label="Destino" :required="true">
        <el-input v-model="form.destination" />
      </el-form-item>
      <el-form-item label="Data de Ida" :required="true">
        <el-date-picker v-model="form.departure_date" type="date" />
      </el-form-item>
      <el-form-item label="Data de Volta" :required="true">
        <el-date-picker v-model="form.return_date" type="date" />
      </el-form-item>
      <!-- Hide status option if not admin -->
      <el-form-item v-if="isAdmin" label="Status" :required="true" class="status-select" width="100px">
        <el-select v-model="form.status" placeholder="Selecione o status" width="100px">
          <el-option label="Solicitado" value="solicitado"></el-option>
          <el-option label="Aprovado" value="aprovado"></el-option>
          <el-option label="Cancelado" value="cancelado"></el-option>
        </el-select>
      </el-form-item>
      <el-button type="primary" :loading="loading" native-type="submit">
        {{ isEdit ? 'Atualizar' : 'Salvar' }}
      </el-button>
    </el-form>
  </div>
</template>

<script>
import api from '../services/api';
import { ElNotification } from 'element-plus';

export default {
  name: 'New',
  data() {
    return {
      form: {
        requester_name: '',
        destination: '',
        departure_date: '',
        return_date: '',
        status: '',
        user_id: localStorage.getItem('user_id') || ''
      },
      loading: false,
      isEdit: false,
      travelRequestId: null,
      isAdmin: localStorage.getItem('is_admin') === 'true' // Check if user is admin
    };
  },
  async created() {
    if (this.$route.params.id) {
      this.isEdit = true;
      this.travelRequestId = this.$route.params.id;
      await this.fetchTravelRequest();
    }
  },
  methods: {
    async fetchTravelRequest() {
      try {
        const response = await api.get(`/travel-requests/${this.travelRequestId}`);
        this.form = response.data;
      } catch (error) {
        ElNotification({
          title: 'Erro',
          message: 'Falha ao carregar pedido de viagem.',
          type: 'error'
        });
      }
    },
    async submitForm() {
      this.loading = true;
      try {
        if (this.isEdit) {
          await api.put(`/travel-requests/${this.travelRequestId}`, this.form);
          ElNotification({
            title: 'Sucesso',
            message: 'Pedido de viagem atualizado com sucesso!',
            type: 'success'
          });
        } else {
          await api.post('/travel-requests', this.form);
          ElNotification({
            title: 'Sucesso',
            message: 'Pedido de viagem criado com sucesso!',
            type: 'success'
          });
        }
      } catch (error) {
        ElNotification({
          title: 'Erro',
          message: 'Falha ao salvar pedido de viagem.',
          type: 'error'
        });
      } finally {
        this.loading = false;
      }
    }
  }
};
</script>

<style scoped>
.form-container {
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  min-height: 100vh;
}

.el-form {
  width: 100%;
  max-width: 600px;
}

.el-form-item {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  width: 100%;
}

.el-form-item label {
  width: 100%;
}

.el-form-item .el-input,
.el-form-item .el-date-picker,
.el-form-item .el-select {
  width: 100%;
}

.status-select .el-select {
  width: 100%;
}

.success {
  color: green;
}
.error {
  color: red;
}
</style>
