<template>
  <div>
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2><i class="fas fa-tachometer-alt"></i> لوحة التحكم</h2>
      <div class="text-muted">
        {{ new Date().toLocaleDateString('ar-SA') }}
      </div>
    </div>

    <!-- إحصائيات سريعة -->
    <div class="row mb-4">
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                  إجمالي المنتجات
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                  {{ stats.total_products || 0 }}
                </div>
              </div>
              <div class="col-auto">
                <i class="fas fa-box fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                  مبيعات اليوم
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                  {{ formatCurrency(stats.today_sales) }}
                </div>
              </div>
              <div class="col-auto">
                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                  طلبات اليوم
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                  {{ stats.today_orders || 0 }}
                </div>
              </div>
              <div class="col-auto">
                <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                  منتجات قليلة المخزون
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                  {{ stats.low_stock_products || 0 }}
                </div>
              </div>
              <div class="col-auto">
                <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <!-- أحدث الطلبات -->
      <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">أحدث الطلبات</h6>
            <router-link to="/orders" class="btn btn-sm btn-primary">
              عرض الكل
            </router-link>
          </div>
          <div class="card-body">
            <div v-if="loading" class="text-center">
              <div class="spinner-border" role="status"></div>
            </div>
            
            <div v-else-if="stats.recent_orders && stats.recent_orders.length > 0">
              <div class="table-responsive">
                <table class="table table-sm">
                  <thead>
                    <tr>
                      <th>رقم الطلب</th>
                      <th>العميل</th>
                      <th>المبلغ</th>
                      <th>الحالة</th>
                      <th>التاريخ</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="order in stats.recent_orders" :key="order.id">
                      <td>{{ order.order_number }}</td>
                      <td>{{ order.customer?.first_name }} {{ order.customer?.last_name }}</td>
                      <td>{{ formatCurrency(order.total_amount) }}</td>
                      <td>
                        <span class="badge" :class="`bg-${getStatusColor(order.status)}`">
                          {{ getStatusText(order.status) }}
                        </span>
                      </td>
                      <td>{{ formatDate(order.created_at) }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            
            <div v-else class="text-center text-muted">
              لا توجد طلبات حديثة
            </div>
          </div>
        </div>
      </div>

      <!-- المنتجات الأكثر مبيعاً -->
      <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">المنتجات الأكثر مبيعاً</h6>
          </div>
          <div class="card-body">
            <div v-if="stats.top_selling_products && stats.top_selling_products.length > 0">
              <div v-for="product in stats.top_selling_products" :key="product.id" class="mb-3">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <div class="font-weight-bold">{{ product.name }}</div>
                    <small class="text-muted">{{ product.sku }}</small>
                  </div>
                  <span class="badge bg-success">{{ product.total_sold }}</span>
                </div>
                <hr class="my-2">
              </div>
            </div>
            
            <div v-else class="text-center text-muted">
              لا توجد بيانات مبيعات
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Dashboard',
  data() {
    return {
      stats: {},
      loading: true
    }
  },
  async mounted() {
    await this.loadStats();
  },
  methods: {
    async loadStats() {
      try {
        const response = await this.$http.get('/dashboard/stats');
        this.stats = response.data.data;
      } catch (error) {
        console.error('Error loading stats:', error);
      } finally {
        this.loading = false;
      }
    },
    formatCurrency(amount) {
      return new Intl.NumberFormat('ar-SA', {
        style: 'currency',
        currency: 'SAR'
      }).format(amount || 0);
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString('ar-SA');
    },
    getStatusColor(status) {
      const colors = {
        'pending': 'warning',
        'processing': 'info',
        'shipped': 'primary',
        'delivered': 'success',
        'cancelled': 'danger',
        'refunded': 'secondary'
      };
      return colors[status] || 'secondary';
    },
    getStatusText(status) {
      const texts = {
        'pending': 'في الانتظار',
        'processing': 'قيد المعالجة',
        'shipped': 'تم الشحن',
        'delivered': 'تم التسليم',
        'cancelled': 'ملغي',
        'refunded': 'مسترد'
      };
      return texts[status] || status;
    }
  }
}
</script>

<style scoped>
.border-left-primary {
  border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
  border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
  border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
  border-left: 0.25rem solid #f6c23e !important;
}

.text-xs {
  font-size: 0.75rem;
}

.text-gray-800 {
  color: #5a5c69;
}

.text-gray-300 {
  color: #dddfeb;
}
</style>