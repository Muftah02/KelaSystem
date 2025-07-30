<template>
  <div>
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2><i class="fas fa-box"></i> إدارة المنتجات</h2>
      <button class="btn btn-primary" @click="showAddModal = true">
        <i class="fas fa-plus"></i> إضافة منتج جديد
      </button>
    </div>

    <!-- فلاتر البحث -->
    <div class="card mb-4">
      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <input 
              type="text" 
              class="form-control" 
              placeholder="البحث في المنتجات..."
              v-model="filters.search"
              @input="loadProducts"
            >
          </div>
          <div class="col-md-3">
            <select class="form-select" v-model="filters.category_id" @change="loadProducts">
              <option value="">جميع الفئات</option>
              <option v-for="category in categories" :key="category.id" :value="category.id">
                {{ category.name }}
              </option>
            </select>
          </div>
          <div class="col-md-3">
            <select class="form-select" v-model="filters.status" @change="loadProducts">
              <option value="">جميع الحالات</option>
              <option value="active">نشط</option>
              <option value="inactive">غير نشط</option>
              <option value="discontinued">متوقف</option>
            </select>
          </div>
          <div class="col-md-2">
            <button class="btn btn-outline-secondary w-100" @click="resetFilters">
              إعادة تعيين
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- جدول المنتجات -->
    <div class="card">
      <div class="card-body">
        <div v-if="loading" class="text-center py-4">
          <div class="spinner-border" role="status"></div>
        </div>

        <div v-else-if="products.data && products.data.length > 0">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>الصورة</th>
                  <th>اسم المنتج</th>
                  <th>الفئة</th>
                  <th>السعر</th>
                  <th>المخزون</th>
                  <th>الحالة</th>
                  <th>الإجراءات</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="product in products.data" :key="product.id">
                  <td>
                    <img 
                      :src="getProductImage(product)" 
                      :alt="product.name"
                      style="width: 50px; height: 50px; object-fit: cover;"
                      class="rounded"
                    >
                  </td>
                  <td>
                    <div>
                      <strong>{{ product.name }}</strong>
                      <br>
                      <small class="text-muted">{{ product.sku }}</small>
                    </div>
                  </td>
                  <td>{{ product.category?.name }}</td>
                  <td>{{ formatCurrency(product.current_price) }}</td>
                  <td>
                    <span 
                      class="badge"
                      :class="product.is_low_stock ? 'bg-warning' : 'bg-success'"
                    >
                      {{ product.stock_quantity }}
                    </span>
                  </td>
                  <td>
                    <span 
                      class="badge"
                      :class="getStatusColor(product.status)"
                    >
                      {{ getStatusText(product.status) }}
                    </span>
                  </td>
                  <td>
                    <div class="btn-group" role="group">
                      <button 
                        class="btn btn-sm btn-outline-primary"
                        @click="editProduct(product)"
                      >
                        <i class="fas fa-edit"></i>
                      </button>
                      <button 
                        class="btn btn-sm btn-outline-danger"
                        @click="deleteProduct(product)"
                      >
                        <i class="fas fa-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <nav v-if="products.last_page > 1" class="mt-3">
            <ul class="pagination justify-content-center">
              <li class="page-item" :class="{ disabled: products.current_page === 1 }">
                <button class="page-link" @click="changePage(products.current_page - 1)">
                  السابق
                </button>
              </li>
              <li 
                v-for="page in paginationPages" 
                :key="page"
                class="page-item"
                :class="{ active: page === products.current_page }"
              >
                <button class="page-link" @click="changePage(page)">
                  {{ page }}
                </button>
              </li>
              <li class="page-item" :class="{ disabled: products.current_page === products.last_page }">
                <button class="page-link" @click="changePage(products.current_page + 1)">
                  التالي
                </button>
              </li>
            </ul>
          </nav>
        </div>

        <div v-else class="text-center py-4">
          <i class="fas fa-box fa-3x text-muted mb-3"></i>
          <p class="text-muted">لا توجد منتجات</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Products',
  data() {
    return {
      products: { data: [] },
      categories: [],
      loading: true,
      showAddModal: false,
      filters: {
        search: '',
        category_id: '',
        status: '',
        page: 1
      }
    }
  },
  computed: {
    paginationPages() {
      const pages = [];
      const current = this.products.current_page;
      const last = this.products.last_page;
      
      const start = Math.max(1, current - 2);
      const end = Math.min(last, current + 2);
      
      for (let i = start; i <= end; i++) {
        pages.push(i);
      }
      
      return pages;
    }
  },
  async mounted() {
    await Promise.all([
      this.loadProducts(),
      this.loadCategories()
    ]);
  },
  methods: {
    async loadProducts() {
      this.loading = true;
      try {
        const params = new URLSearchParams();
        Object.keys(this.filters).forEach(key => {
          if (this.filters[key]) {
            params.append(key, this.filters[key]);
          }
        });
        
        const response = await this.$http.get(`/products?${params}`);
        this.products = response.data.data;
      } catch (error) {
        console.error('Error loading products:', error);
      } finally {
        this.loading = false;
      }
    },
    
    async loadCategories() {
      try {
        const response = await this.$http.get('/categories');
        this.categories = response.data.data;
      } catch (error) {
        console.error('Error loading categories:', error);
      }
    },
    
    changePage(page) {
      if (page >= 1 && page <= this.products.last_page) {
        this.filters.page = page;
        this.loadProducts();
      }
    },
    
    resetFilters() {
      this.filters = {
        search: '',
        category_id: '',
        status: '',
        page: 1
      };
      this.loadProducts();
    },
    
    editProduct(product) {
      // TODO: Implement edit modal
      console.log('Edit product:', product);
    },
    
    async deleteProduct(product) {
      if (confirm(`هل أنت متأكد من حذف المنتج: ${product.name}؟`)) {
        try {
          await this.$http.delete(`/products/${product.id}`);
          await this.loadProducts();
        } catch (error) {
          console.error('Error deleting product:', error);
          alert('حدث خطأ في حذف المنتج');
        }
      }
    },
    
    getProductImage(product) {
      if (product.images && product.images.length > 0) {
        return `/storage/${product.images[0]}`;
      }
      return '/images/no-image.png';
    },
    
    formatCurrency(amount) {
      return new Intl.NumberFormat('ar-SA', {
        style: 'currency',
        currency: 'SAR'
      }).format(amount || 0);
    },
    
    getStatusColor(status) {
      const colors = {
        'active': 'bg-success',
        'inactive': 'bg-secondary',
        'discontinued': 'bg-danger'
      };
      return colors[status] || 'bg-secondary';
    },
    
    getStatusText(status) {
      const texts = {
        'active': 'نشط',
        'inactive': 'غير نشط',
        'discontinued': 'متوقف'
      };
      return texts[status] || status;
    }
  }
}
</script>