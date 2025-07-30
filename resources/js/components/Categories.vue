<template>
  <div>
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2><i class="fas fa-tags"></i> إدارة الفئات</h2>
      <button class="btn btn-primary">
        <i class="fas fa-plus"></i> إضافة فئة جديدة
      </button>
    </div>

    <div class="card">
      <div class="card-body">
        <div v-if="loading" class="text-center py-4">
          <div class="spinner-border" role="status"></div>
        </div>

        <div v-else-if="categories.length > 0">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>اسم الفئة</th>
                  <th>عدد المنتجات</th>
                  <th>الحالة</th>
                  <th>الإجراءات</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="category in categories" :key="category.id">
                  <td>
                    <strong>{{ category.name }}</strong>
                    <br>
                    <small class="text-muted">{{ category.description }}</small>
                  </td>
                  <td>
                    <span class="badge bg-info">{{ category.products_count }}</span>
                  </td>
                  <td>
                    <span 
                      class="badge"
                      :class="category.is_active ? 'bg-success' : 'bg-secondary'"
                    >
                      {{ category.is_active ? 'نشط' : 'غير نشط' }}
                    </span>
                  </td>
                  <td>
                    <div class="btn-group" role="group">
                      <button class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-edit"></i>
                      </button>
                      <button class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div v-else class="text-center py-4">
          <i class="fas fa-tags fa-3x text-muted mb-3"></i>
          <p class="text-muted">لا توجد فئات</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Categories',
  data() {
    return {
      categories: [],
      loading: true
    }
  },
  async mounted() {
    await this.loadCategories();
  },
  methods: {
    async loadCategories() {
      try {
        const response = await this.$http.get('/categories');
        this.categories = response.data.data;
      } catch (error) {
        console.error('Error loading categories:', error);
      } finally {
        this.loading = false;
      }
    }
  }
}
</script>