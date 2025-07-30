<template>
  <div class="login-container">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
          <div class="card shadow">
            <div class="card-body p-4">
              <div class="text-center mb-4">
                <i class="fas fa-store fa-3x text-primary mb-3"></i>
                <h3 class="card-title">نظام مبيعات الاكسسوارات</h3>
                <p class="text-muted">تسجيل الدخول للنظام</p>
              </div>

              <div v-if="error" class="alert alert-danger" role="alert">
                {{ error }}
              </div>

              <form @submit.prevent="login">
                <div class="mb-3">
                  <label for="email" class="form-label">البريد الإلكتروني</label>
                  <input 
                    type="email" 
                    class="form-control" 
                    id="email" 
                    v-model="form.email"
                    required
                    :disabled="loading"
                  >
                </div>

                <div class="mb-3">
                  <label for="password" class="form-label">كلمة المرور</label>
                  <input 
                    type="password" 
                    class="form-control" 
                    id="password" 
                    v-model="form.password"
                    required
                    :disabled="loading"
                  >
                </div>

                <div class="d-grid">
                  <button 
                    type="submit" 
                    class="btn btn-primary"
                    :disabled="loading"
                  >
                    <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
                    تسجيل الدخول
                  </button>
                </div>
              </form>

              <div class="text-center mt-3">
                <small class="text-muted">
                  للتجربة: admin@example.com / password
                </small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Login',
  data() {
    return {
      form: {
        email: 'admin@example.com',
        password: 'password'
      },
      loading: false,
      error: null
    }
  },
  methods: {
    async login() {
      this.loading = true;
      this.error = null;

      try {
        const response = await this.$http.post('/auth/login', this.form);
        
        if (response.data.success) {
          const token = response.data.data.access_token;
          localStorage.setItem('token', token);
          this.$http.defaults.headers.common['Authorization'] = `Bearer ${token}`;
          
          this.$router.push('/');
        }
      } catch (error) {
        console.error('Login error:', error);
        this.error = error.response?.data?.message || 'حدث خطأ في تسجيل الدخول';
      } finally {
        this.loading = false;
      }
    }
  },
  mounted() {
    // إذا كان المستخدم مسجل دخول بالفعل، انتقل للوحة التحكم
    if (localStorage.getItem('token')) {
      this.$router.push('/');
    }
  }
}
</script>

<style scoped>
.login-container {
  min-height: 100vh;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
}

.card {
  border: none;
  border-radius: 15px;
}

.form-control {
  border-radius: 10px;
  border: 1px solid #ddd;
  padding: 12px 15px;
}

.form-control:focus {
  border-color: #667eea;
  box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border: none;
  border-radius: 10px;
  padding: 12px;
  font-weight: 600;
}

.btn-primary:hover {
  background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
}
</style>