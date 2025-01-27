<template>
    <div class="dashboard-container">
        <div class="filters">
            <h1 style="font-weight: bold; color: #2c3e50;">Filtros</h1>
            <el-row :gutter="20" style="margin-bottom: 20px;">
                <el-col :span="24">
                    <el-select v-model="filterStatus" placeholder="Filtrar por status" clearable class="filter-input">
                        <el-option label="Solicitado" value="solicitado"></el-option>
                        <el-option label="Aprovado" value="aprovado"></el-option>
                        <el-option label="Cancelado" value="cancelado"></el-option>
                    </el-select>
                </el-col>
                <el-col :span="24">
                    <el-input v-model="filterId" placeholder="Filtrar por ID" clearable class="filter-input"></el-input>
                </el-col>
                <el-col :span="24" style="margin-bottom: 5px;">
                    <el-date-picker v-model="filterStartDate" type="date" placeholder="Data de Início" clearable class="filter-input" style="width: 100%"></el-date-picker>
                </el-col>
                <el-col :span="24" style="margin-bottom: 5px;">
                    <el-date-picker v-model="filterEndDate" type="date" placeholder="Data de Fim" clearable class="filter-input" style="width: 100%"></el-date-picker>
                </el-col>
                <el-col :span="24" style="margin-bottom: 5px;">
                    <el-input v-model="filterDestination" placeholder="Filtrar por destino" clearable class="filter-input"></el-input>
                </el-col>
                <el-col :span="24">
                    <el-button type="primary" @click="fetchTravelRequests">Pesquisar</el-button>
                </el-col>
            </el-row>
        </div>
        <div class="requests">
            <h1 style="font-weight: bold; color: #2c3e50; margin: 10px 0">Pedidos de Viagem</h1>
            <el-skeleton :loading="loading" animated>
                <template #template>
                    <el-skeleton-item variant="text"></el-skeleton-item>
                    <el-skeleton-item variant="text"></el-skeleton-item>
                    <el-skeleton-item variant="text"></el-skeleton-item>
                    <el-skeleton-item variant="text"></el-skeleton-item>
                    <el-skeleton-item variant="text"></el-skeleton-item>
                </template>
                <el-table v-if="!loading" :data="travelRequests" style="width: 100%">
                    <el-table-column prop="id" label="ID"></el-table-column>
                    <el-table-column prop="requester_name" label="Solicitante"></el-table-column>
                    <el-table-column prop="destination" label="Destino"></el-table-column>
                    <el-table-column prop="departure_date" label="Data de Partida"></el-table-column>
                    <el-table-column prop="return_date" label="Data de Retorno"></el-table-column>
                    <el-table-column prop="status" label="Status">
                        <template v-slot="scope">
                            <el-select v-if="isAdmin && scope.row.user_id != userId" v-model="scope.row.status" @change="updateStatus(scope.row.id, scope.row.status)" placeholder="Alterar Status" size="small" :disabled="loading">
                                <el-option label="Solicitado" value="solicitado"></el-option>
                                <el-option label="Aprovado" value="aprovado"></el-option>
                                <el-option label="Cancelado" value="cancelado"></el-option>
                            </el-select>
                            <span v-else>{{ scope.row.status }} </span>
                        </template>
                    </el-table-column>
                </el-table>
            </el-skeleton>
        </div>
    </div>
</template>

<script>
import api from '../services/api';
import { ElNotification } from 'element-plus';

export default {
    data() {
        return {
            filterStatus: '',
            filterStartDate: '',
            filterEndDate: '',
            filterDestination: '',
            filterId: '',
            travelRequests: [],
            isAdmin: localStorage.getItem('is_admin') != 0,
            userId: localStorage.getItem('user_id'),
            loading: false
        };
    },
    methods: {
        async fetchTravelRequests() {
            this.loading = true;
            try {
                const response = await api.get('/travel-requests', {
                    params: {
                        status: this.filterStatus,
                        start_date: this.filterStartDate,
                        end_date: this.filterEndDate,
                        destination: this.filterDestination,
                        id: this.filterId
                    }
                });
                this.travelRequests = response.data;
            } catch (error) {
                ElNotification({
                    title: 'Erro',
                    message: 'Falha ao buscar pedidos de viagens.',
                    type: 'error',
                });
            } finally {
                this.loading = false;
            }
        },
        async updateStatus(id, status) {
            this.loading = true;
            try {
                await api.put(`/travel-requests/${id}`, { status });
                ElNotification({
                    title: 'Sucesso',
                    message: 'Status atualizado com sucesso.',
                    type: 'success',
                });
                this.fetchTravelRequests();
            } catch (error) {
                ElNotification({
                    title: 'Erro',
                    message: 'Falha ao atualizar status.',
                    type: 'error',
                });
            } finally {
                this.loading = false;
            }
        }
    },
    created() {
        this.fetchTravelRequests();
    }
};
</script>

<style scoped>
.dashboard-container {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.filters {
    width: 100%;
    margin-bottom: 20px;
}

.filter-input {
    width: 100%;
    margin-bottom: 5px;
    font-size: 14px;
}

.requests {
    width: 100%;
}

@media (min-width: 768px) {
    .dashboard-container {
        flex-direction: row;
        justify-content: center;
    }

    .filters {
        width: 10%;
        margin-right: 20px;
        margin-bottom: 0;
    }

    .requests {
        width: 60%;
    }
}
</style>
