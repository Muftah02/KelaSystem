<template>
  <div>
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2><i class="fas fa-users"></i> إدارة العملاء</h2>
      <button class="btn btn-primary">
        <i class="fas fa-plus"></i> إضافة عميل جديد
      </button>
    </div>

    <div class="card">
      <div class="card-body">
        <div v-if="loading" class="text-center py-4">
          <div class="spinner-border" role="status"></div>
        </div>

        <div v-else-if="customers.data && customers.data.length > 0">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>الاسم</th>
                  <th>البريد الإلكتروني</th>
                  <th>رقم الهاتف</th>
                  <th>إجمالي المشتريات</th>
                  <th>عدد الطلبات</th>
                  <th>مجموعة العميل</th>
                  <th>الإجراءات</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="customer in customers.data" :key="customer.id">
                  <td>
                    <strong>{{ customer.first_name }} {{ customer.last_name }}</strong>
                  </td>
                  <td>{{ customer.email }}</td>
                  <td>{{ customer.phone || '-' }}</td>
                  <td>{{ formatCurrency(customer.total_spent) }}</td>
                  <td>
                    <span class="badge bg-info">{{ customer.orders_count }}</span>
                  </td>
                  <td>
                    <span 
                      class="badge"
                      :class="getGroupColor(customer.customer_group)"
                    >
                      {{ getGroupText(customer.customer_group) }}
                    </span>
                  </td>
                  <td>
                    <div class="btn-group" role="group">
                      <button class="btn btn-sm btn-outline-info">
                        <i class="fas fa-eye"></i>
                      </button>
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
          <i class="fas fa-users fa-3x text-muted mb-3"></i>
          <p class="text-muted">لا يوجد عملاء</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Customers',
  data() {
    return {
      customers: { data: [] },
      loading: true
    }
  },
  async mounted() {
    await this.loadCustomers();
  },
  methods: {
    async loadCustomers() {
      try {
        const response = await this.$http.get('/customers');
        this.customers = response.data.data;
      } catch (error) {
        console.error('Error loading customers:', error);
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
    getGroupColor(group) {
      const colors = {
        'regular': 'bg-secondary',
        'vip': 'bg-warning',
        'wholesale': 'bg-success'
      };
      return colors[group] || 'bg-secondary';
    },
    getGroupText(group) {
      const texts = {
        'regular': 'عادي',
        'vip': 'مميز',
        'wholesale': 'جملة'
      };
      return texts[group] || group;
    }
  }
}
</script>