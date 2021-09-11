<template>
  <div class="form-demo">
    <Toast position="top-left" />
    <div class="p-d-flex p-jc-center">
      <div class="card">
        <h3 class="p-text-center">Customer Creation Form</h3>
        <form @submit.prevent="handleSubmit(!v$.$invalid)" class="p-fluid">
          <div class="p-field p-grid">
            <label for="name" class="p-col-fixed" style="width: 100px"
              >Name</label
            >
            <div class="p-col">
              <InputText
                id="name"
                v-model="v$.name.$model"
                :class="{ 'p-invalid': v$.name.$invalid && submitted }"
              />
              <small
                v-if="
                  (v$.name.$invalid && submitted) || v$.name.$pending.$response
                "
                class="p-error"
                >{{ v$.name.required.$message.replace("Value", "Name") }}</small
              >
            </div>
          </div>
          <div class="p-field p-grid">
            <label for="nic" class="p-col-fixed" style="width: 100px"
              >NIC</label
            >
            <div class="p-col">
              <InputText
                id="nic"
                v-model="v$.nic.$model"
                :class="{ 'p-invalid': v$.nic.$invalid && submitted }"
              />
              <small
                v-if="
                  (v$.nic.$invalid && submitted) || v$.nic.$pending.$response
                "
                class="p-error"
                >{{ v$.nic.required.$message.replace("Value", "nic") }}</small
              >
            </div>
          </div>
          <div class="p-field p-grid">
            <label for="address" class="p-col-fixed" style="width: 100px"
              >Address</label
            >
            <div class="p-col">
              <Textarea
                id="address"
                v-model="v$.address.$model"
                :class="{ 'p-invalid': v$.address.$invalid && submitted }"
              />
              <small
                v-if="
                  (v$.address.$invalid && submitted) ||
                  v$.address.$pending.$response
                "
                class="p-error"
                >{{
                  v$.address.required.$message.replace("Value", "address")
                }}</small
              >
            </div>
          </div>
          <div class="p-grid">
            <div class="p-col-8" v-for="(telephone, i) in telephones" :key="i">
              <div class="p-grid p-pr-0 p-pl-0">
                <div
                  class="p-col-4 p-text-left p-pr-0 p-pl-0"
                  style="width: 100px"
                >
                  <label for="telephone" class="p-col-fixed" style="width: 75px"
                    >Phone {{ i + 1 }}</label
                  >
                </div>
                <div class="p-col-8">
                  <div class="p-field">
                    <InputText
                      id="telephone"
                      v-model="telephone.telephoneNumber"
                      required
                      placeholder="0xxxxxxxxx"
                      type="tel"
                      pattern="[0-9]{10}"
                    />
                  </div>
                </div>
              </div>
            </div>
            <div class="p-col-4 p-text-left">
              <Button
                @click="increment"
                icon="pi pi-plus"
                class="p-button-rounded p-button-outlined p-mr-2"
              />
              <Button
                @click="decrement()"
                icon="pi pi-minus"
                class="p-button-rounded p-button-outlined"
              />
            </div>
          </div>
          <div class="p-grid">
            <div class="p-col-3">
              <Button
                label="Cancel"
                class="p-mt-2 p-button-danger"
                @click="reset"
              />
            </div>
            <div class="p-col-3"></div>
            <div class="p-col-3">
              <Button type="submit" label="Submit" class="p-mt-2" />
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import {
  required,
  minLength,
  maxLength,
  numeric,
  helpers,
} from "@vuelidate/validators";
import { useVuelidate } from "@vuelidate/core";
import Customer from "../../src/apis/Customers";

export default {
  setup: () => ({ v$: useVuelidate() }),
  data() {
    return {
      name: "",
      nic: "",
      address: "",
      telephones: [{ telephoneNumber: "" }],
      submitted: false,
      telephoneCount: 1,
    };
  },
  validations() {
    return {
      name: {
        required,
      },
      nic: {
        required,
      },
      address: {
        required,
      },
      telephones: {
        $each: {
          telephoneNumber: {
            required,
            numeric: helpers.withMessage(
              "Contact number should have only numbers",
              numeric
            ),
            minLength: minLength(10),
            maxLength: maxLength(10),
          },
        },
      },
    };
  },
  created() {},
  mounted() {},
  methods: {
    handleSubmit(isFormValid) {
      this.submitted = true;

      if (!isFormValid) {
        return;
      } else {
        let NewCustomer = {
          name: this.name,
          nic: this.nic,
          address: this.address,
          telephones: this.telephones,
        };

        Customer.addCustomer(NewCustomer).then((resp) => {
          if (resp.data.data) {
            console.log(resp.data.data);
            this.$toast.add({
              severity: "success",
              summary: "Successful",
              detail: resp.data.message,
              life: 3000,
            });

            this.$router.push("/customerList");
          } else {
            this.$toast.add({
              severity: "error",
              summary: "Error Message",
              detail: resp.data.message,
              life: 3000,
            });
          }
        });
      }
    },
    resetForm() {
      this.name = "";
      this.nic = "";
      this.address = "";
      this.telephone = [""];
      this.telephone.telephoneNumber = "";
      this.submitted = false;
    },
    increment() {
      this.telephoneCount++;

      this.telephones.push({ telephoneNumber: "" });
      console.log(this.telephoneCount);
    },
    decrement() {
      if (this.telephoneCount > 1) {
        this.telephoneCount--;
        this.telephones.pop();
        console.log(this.telephoneCount);
      }
    },
    reset() {
      this.resetForm();
    },
  },
};
</script>

<style lang="scss" scoped>
.form-demo {
  .card {
    min-width: 800px;

    form {
      margin-top: 2rem;
    }

    .p-field {
      margin-bottom: 1.5rem;
    }
  }

  @media screen and (max-width: 960px) {
    .card {
      width: 80%;
    }
  }
}
</style>
