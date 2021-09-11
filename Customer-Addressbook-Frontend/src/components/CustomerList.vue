<template>
  <Toast position="top-left" />
  <div class="p-m-5">
    <div class="card">
      <DataTable
        ref="dt"
        :value="customers"
        v-model:selection="selectedCustomers"
        dataKey="id"
        :paginator="true"
        :rows="10"
        :filters="filters"
        paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
        :rowsPerPageOptions="[5, 10, 25]"
        currentPageReportTemplate="Showing {first} to {last} of {totalRecords} customers"
        responsiveLayout="scroll"
      >
        <template #header>
          <div
            class="
              table-header
              p-d-flex p-flex-column p-flex-md-row p-jc-md-between
            "
          >
            <h3 class="p-mb-2 p-m-md-0 p-as-md-center">Customer List</h3>
            <span class="p-input-icon-left">
              <i class="pi pi-search" />
              <InputText
                v-model="filters['global'].value"
                placeholder="Search..."
              />
            </span>
          </div>
        </template>

        <Column
          field="name"
          header="Name"
          :sortable="true"
          style="min-width: 12rem"
        ></Column>
        <Column
          field="nic"
          header="NIC"
          :sortable="true"
          style="min-width: 16rem"
        ></Column>
        <Column
          field="address"
          header="Address"
          filterField="address.address"
          style="min-width: 8rem"
        >
          <template #body="slotProps">
            <span>{{ slotProps.data.address.address }}</span>
          </template>
        </Column>
        <Column
          field="telephones"
          header="Telephones"
          filterField="telephone.telephone.telephone"
          style="min-width: 10rem"
        >
          <template #body="slotProps">
            <span
              v-for="telephone of slotProps.data.telephones"
              :key="telephone.id"
              >{{ telephone.telephone }}<br
            /></span>
          </template>
        </Column>
        <Column :exportable="false" header="Actions">
          <template #body="slotProps">
            <Button
              icon="pi pi-trash"
              class="p-button-rounded p-button-warning"
              @click="confirmDeleteCustomer(slotProps.data)"
            />
          </template>
        </Column>
      </DataTable>
    </div>

    <Dialog
      v-model:visible="deleteCustomerDialog"
      :style="{ width: '450px' }"
      header="Confirm"
      :modal="true"
    >
      <div class="confirmation-content">
        <i class="pi pi-exclamation-triangle p-mr-3" style="font-size: 2rem" />
        <span v-if="customer"
          >Are you sure you want to delete <b>{{ customer.name }}</b
          >?</span
        >
      </div>
      <template #footer>
        <Button
          label="No"
          icon="pi pi-times"
          class="p-button-text"
          @click="deleteCustomerDialog = false"
        />
        <Button
          label="Yes"
          icon="pi pi-check"
          class="p-button-text"
          @click="deleteCustomer"
        />
      </template>
    </Dialog>
  </div>
</template>

<script>
import { FilterMatchMode } from "primevue/api";
import Customer from "../../src/apis/Customers";

export default {
  data() {
    return {
      customers: [],
      customerDialog: false,
      deleteCustomerDialog: false,
      customer: {},
      selectedCustomers: null,
      filters: {},
      submitted: false,
    };
  },
  created() {
    this.initFilters();
  },
  mounted() {
    Customer.getCustomers().then((resp) => {
      console.log(resp.data.data);
      this.customers = resp.data.data;
    });
  },
  methods: {
    hideDialog() {
      this.customerDialog = false;
      this.submitted = false;
    },
    confirmDeleteCustomer(customer) {
      this.customer = customer;
      this.deleteCustomerDialog = true;
    },
    deleteCustomer() {
      this.customers = this.customers.filter(
        (val) => val.id !== this.customer.id
      );
      let customer_id = parseInt(this.customer.id);
      this.deleteCustomerDialog = false;
      this.customer = {};
      Customer.deleteCustomer(customer_id).then((resp) => {
        console.log(resp);
        if (resp.data == "Customer Deleted Successfully!") {
          this.$toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Customer Deleted",
            life: 3000,
          });
        } else {
          this.$toast.add({
            severity: "error",
            summary: "Error Message",
            detail: resp.data,
            life: 3000,
          });
        }
      });
    },
    confirmDeleteSelected() {
      this.deleteCustomersDialog = true;
    },
    initFilters() {
      this.filters = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
      };
    },
  },
};
</script>

<style lang="scss" scoped>
.table-header {
  display: flex;
  align-items: center;
  justify-content: space-between;

  @media screen and (max-width: 960px) {
    align-items: start;
  }
}
.confirmation-content {
  display: flex;
  align-items: center;
  justify-content: center;
}
@media screen and (max-width: 960px) {
  ::v-deep(.p-toolbar) {
    flex-wrap: wrap;

    .p-button {
      margin-bottom: 0.25rem;
    }
  }
}
</style>