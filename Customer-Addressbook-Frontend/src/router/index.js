import { createRouter, createWebHistory } from "vue-router";
import Home from "../components/Home.vue";
import CustomerForm from "../components/CustomerForm.vue";
import CustomerList from "../components/CustomerList.vue";

const routes = [
  {
    path: "/",
    name: "Home",
    component: Home,
  },
  {
    path: "/customerForm",
    name: "customerForm",
    component: CustomerForm,
  },
  {
    path: "/customerList",
    name: "customerList",
    component: CustomerList,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
