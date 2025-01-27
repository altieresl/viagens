<template>
  <div id="app">
    <el-header v-if="!isAuthRoute">
      <div class="container">
        <el-row>
          <el-col :span="4">
            <h1 class="header-title">Viagens</h1>
          </el-col>
          <el-col :span="16">
            <el-menu :default-active="activeIndex" class="el-menu-demo" mode="horizontal" @select="handleSelect">
              <router-link to="/">
                <el-menu-item index="1">Dashboard
                </el-menu-item>
              </router-link>
              <router-link to="/new">
                <el-menu-item index="2">
                  Novo
                </el-menu-item>
              </router-link>
            </el-menu>
          </el-col>
          <el-col :span="4" class="align-right content-center">
            <el-button type="primary" @click="logout">Sair</el-button>
          </el-col>
        </el-row>
      </div>
    </el-header>
    <router-view></router-view>
    <footer>
      <p>&copy; 2025 Viagens</p>
    </footer>
  </div>
</template>

<script>
import api from '../services/api';
import router from '../router';

export default {
  name: 'App',
  data() {
    return {
      activeIndex: this.getActiveIndex()
    };
  },
  computed: {
    isAuthRoute() {
      return ['/login', '/register'].includes(this.$route.path);
    }
  },
  methods: {
    handleSelect(key, keyPath) {
      console.log(key, keyPath);
    },
    navigateTo(path) {
      router.push(path);
    },
    logout() {
      localStorage.removeItem('token');
      this.$router.push('/login');
    },
    getActiveIndex() {
      const routes = {
        '/': '1',
        '/new': '2'
      };
      return routes[this.$route.path] || '1';
    }
  },
  watch: {
    $route(to, from) {
      this.activeIndex = this.getActiveIndex();
    }
  },
  created() {
    const token = localStorage.getItem('token');
    if (!token) {
      router.push('/login');
    } else {
      api.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    }
  },
  mounted() {
    window.addEventListener('storage', this.handleStorageChange);
  },
  beforeDestroy() {
    window.removeEventListener('storage', this.handleStorageChange);
  }
};
</script>

<style scoped>
.el-header {
  background-color: white;
  padding: 0 50px;
  border-bottom: 1px solid var(--el-menu-border-color);
}

.container {
  max-width: 100%;
  margin: 0 auto;
  padding: 0 15px;
}

@media (min-width: 576px) {
  .container {
    max-width: 540px;
  }
}

@media (min-width: 768px) {
  .container {
    max-width: 720px;
  }
}

@media (min-width: 992px) {
  .container {
    max-width: 960px;
  }
}

@media (min-width: 1200px) {
  .container {
    max-width: 1140px;
  }
}

.header-title {
  margin: 0;
  line-height: 60px;
  font-size: 24px;
  color: #333;
}

.el-menu-demo {
  line-height: 60px;
}

.el-menu--horizontal {
  border-bottom: 0;
}

.align-right {
  text-align: right;
}

footer {
  background-color: #f8f9fa;
  padding: 1rem;
  text-align: center;
  position: fixed;
  width: 100%;
  bottom: 0;
}
</style>
