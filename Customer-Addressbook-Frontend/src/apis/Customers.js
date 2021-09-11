/* eslint-disable prettier/prettier */
import Api from "./Api";

export default {
  getCustomers() {
    return Api().get("/customers");
  },

  addCustomer(form) {
    return Api().post("/customers", form);
  },

  deleteCustomer(id) {
      return Api().delete("/customers/"+id);
  },

  updateCustomer(form,id) {
    return Api().delete("/customers/"+id,form);
  }
};
