<template>
  <div id="app">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary" v-if="$route.name !== 'login'">
      <div class="container-fluid">
        <router-link class="navbar-brand" to="/">
          <i class="fas fa-store"></i> نظام مبيعات الاكسسوارات
        </router-link>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav me-auto">
            <li class="nav-item">
              <router-link class="nav-link" to="/" active-class="active">
                <i class="fas fa-tachometer-alt"></i> لوحة التحكم
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/products" active-class="active">
                <i class="fas fa-box"></i> المنتجات
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/categories" active-class="active">
                <i class="fas fa-tags"></i> الفئات
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/orders" active-class="active">
                <i class="fas fa-shopping-cart"></i> الطلبات
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/customers" active-class="active">
                <i class="fas fa-users"></i> العملاء
              </router-link>
            </li>
          </ul>
          
          <ul class="navbar-nav">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                <i class="fas fa-user"></i> {{ user?.name || 'المستخدم' }}
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#" @click="logout">
                  <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
                </a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <main class="container-fluid mt-4" v-if="$route.name !== 'login'">
      <router-view></router-view>
    </main>
    
    <router-view v-else></router-view>
  </div>
</template>

<script>
export default {
  name: 'App',
  data() {
    return {
      user: null
    }
  },
  async mounted() {
    if (localStorage.getItem('token')) {
      try {
        const response = await this.$http.get('/auth/user');
        this.user = response.data.data;
      } catch (error) {
        console.error('Failed to fetch user:', error);
        this.logout();
      }
    }
  },
  methods: {
    async logout() {
      try {
        await this.$http.post('/auth/logout');
      } catch (error) {
        console.error('Logout error:', error);
      } finally {
        localStorage.removeItem('token');
        delete this.$http.defaults.headers.common['Authorization'];
        this.$router.push('/login');
      }
    }
  }
}
</script>

<style>
body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  direction: rtl;
  text-align: right;
}

.navbar-brand {
  font-weight: bold;
}

.nav-link.router-link-active {
  background-color: rgba(255,255,255,0.1);
  border-radius: 0.375rem;
}

.card {
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
  border: 1px solid rgba(0, 0, 0, 0.125);
}

.btn {
  border-radius: 0.375rem;
}

.table {
  border-radius: 0.375rem;
  overflow: hidden;
}

.alert {
  border-radius: 0.375rem;
}
</style>