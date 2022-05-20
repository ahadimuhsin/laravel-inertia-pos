<template>
  <Head>
    <title>Add New Customer - Aplikasi Kasir</title>
  </Head>
  <main class="c-main">
    <div class="container-fluid">
      <div class="fade-in">
        <div class="row">
          <div class="col-md-12">
            <div class="card border-0 rounded-3 shadow border-top-purple">
              <div class="card header">
                <span class="font-weight-bold">
                  <i class="fa fa-user-circle"></i>
                  Tambah Customer
                </span>
              </div>
              <div class="card-body">
                <form @submit.prevent="submit">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="" class="fw-bold">Full Name</label>
                                <input
                                type="text"
                                class="form-control"
                                v-model="form.name"
                                :class="{ 'is-invalid': errors.name }"
                                placeholder="Customer's Full Name"
                                />
                            </div>
                            <div v-if="errors.name" class="alert alert-danger">
                                {{ errors.name }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="" class="fw-bold">No. Telp</label>
                                <input
                                type="text"
                                class="form-control"
                                v-model="form.no_telp"
                                :class="{ 'is-invalid': errors.no_telp }"
                                placeholder="Customer's Phone Number"
                                v-maska="{ mask: '#*', tokens: { '#': { pattern: /[0-9]/ }}}"
                                />
                            </div>
                            <div v-if="errors.no_telp" class="alert alert-danger">
                                {{ errors.no_telp }}
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="" class="fw-bold">Address</label>
                        <textarea v-model="form.address" rows="5" class="form-control"
                        placeholder="Customer's Address" :class="{'is-invalid': errors.address}"></textarea>
                    </div>
                    <div v-if="errors.address" class="alert alert-danger">
                        {{ errors.address }}
                    </div>
                  <div class="row">
                    <div class="col-md-12">
                      <button
                        class="btn btn-primary shadow-sm rounded-sm mr-2"
                        type="submit"
                      >
                        Save
                      </button>
                      <button
                        class="btn btn-warning rounded-sm shadow-sm"
                        type="reset"
                      >
                        Reset
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script>
import LayoutApp from "../../../Layouts/App";
//import componen pagination
//import header dan Link
import { Head, Link } from "@inertiajs/inertia-vue3";

import { reactive } from "vue";
import { Inertia } from "@inertiajs/inertia";
import Swal from "sweetalert2";
import {maska} from 'maska';
export default {
  layout: LayoutApp,
  components: {
    Head,
    Link,
  },
  props: {
    errors: Object,
  },

  directives: { maska },

  setup() {
    const form = reactive({
      name: "",
      address: "",
      no_telp: ""
    });

    //method submit
    const submit = () => {
      //send data to server
      Inertia.post(
        "/apps/customers",
        {
          name: form.name,
          address: form.address,
          no_telp: form.no_telp,
        },
        {
          onSuccess: () => {
            //show sweet alert
            // console.log("1");
            Swal.fire({
              title: "Success!",
              text: "customer saved successfully.",
              icon: "success",
              showConfirmButton: false,
              timer: 2000,
            });
            // console.log("2");
          },
        }
      );
    };
    return {
      form,
      submit,
    };
  },
};
</script>
