<template>
  <div>
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2><i class="fas fa-shopping-cart"></i> إدارة الطلبات</h2>
      <button class="btn btn-primary">
        <i class="fas fa-plus"></i> طلب جديد
      </button>
    </div>

    <div class="card">
      <div class="card-body">
        <div v-if="loading" class="text-center py-4">
          <div class="spinner-border" role="status"></div>
        </div>

        <div v-else-if="orders.data && orders.data.length > 0">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>رقم الطلب</th>
                  <th>العميل</th>
                  <th>المبلغ الإجمالي</th>
                  <th>حالة الطلب</th>
                  <th>حالة الدفع</th>
                  <th>التاريخ</th>
                  <th>الإجراءات</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="order in orders.data" :key="order.id">
                  <td>
                    <strong>{{ order.order_number }}</strong>
                  </td>
                  <td>
                    {{ order.customer?.first_name }} {{ order.customer?.last_name }}
                    <br>
                    <small class="text-muted">{{ order.customer?.email }}</small>
                  </td>
                  <td>{{ formatCurrency(order.total_amount) }}</td>
                  <td>
                    <span 
                      class="badge"
                      :class="`bg-${getStatusColor(order.status)}`"
                    >
                      {{ getStatusText(order.status) }}
                    </span>
                  </td>
                  <td>
                    <span 
                      class="badge"
                      :class="`bg-${getPaymentStatusColor(order.payment_status)}`"
                    >
                      {{ getPaymentStatusText(order.payment_status) }}
                    </span>
                  </td>
                  <td>{{ formatDate(order.created_at) }}</td>
                  <td>
                    <div class="btn-group" role="group">
                      <button class="btn btn-sm btn-outline-info">
                        <i class="fas fa-eye"></i>
                      </button>
                      <button class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-edit"></i>
                      </button>
                      <button class="btn btn-sm btn-outline-success">
                        <i class="fas fa-print"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div v-else class="text-center py-4">
          <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
          <p class="text-muted">لا توجد طلبات</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Orders',
  data() {
    return {
      orders: { data: [] },
      loading: true
    }
  },
  async mounted() {
    await this.loadOrders();
  },
  methods: {
    async loadOrders() {
      try {
        const response = await this.$http.get('/orders');
        this.orders = response.data.data;
      } catch (error) {
        console.error('Error loading orders:', error);
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
    },
    getPaymentStatusColor(status) {
      const colors = {
        'pending': 'warning',
        'paid': 'success',
        'partial': 'info',
        'refunded': 'danger'
      };
      return colors[status] || 'secondary';
    },
    getPaymentStatusText(status) {
      const texts = {
        'pending': 'في الانتظار',
        'paid': 'مدفوع',
        'partial': 'مدفوع جزئياً',
        'refunded': 'مسترد'
      };
      return texts[status] || status;
    }
  }
}
</script>