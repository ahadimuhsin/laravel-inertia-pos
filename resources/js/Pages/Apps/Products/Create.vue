<template>
  <Head>
    <title>Add New Product - Aplikasi Kasir</title>
  </Head>
  <main class="c-main">
    <div class="container-fluid">
      <div class="fade-in">
        <div class="row">
          <div class="col-md-12">
            <div class="card border-0 rounded-3 shadow border-top-purple">
              <div class="card header">
                <span class="font-weight-bold">
                  <i class="fa fa-shield-alt"></i>
                  Tambah Product
                </span>
              </div>
              <div class="card-body">
                <form @submit.prevent="submit">
                  <div class="mb-3">
                    <input
                      type="file"
                      class="form-control"
                      @input="form.image = $event.target.files[0]"
                      :class="{ 'is-invalid': errors.image }"
                    />
                    <progress
                      v-if="form.progress"
                      :value="form.progress.percentage"
                      max="100"
                    >
                      {{ form.progress.percentage }}%
                    </progress>
                  </div>
                  <div v-if="errors.image" class="alert alert-danger">
                    {{ errors.image }}
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="mb-3">
                        <label for="barcode" class="fw-bold">Barcode</label>
                        <input
                          type="text"
                          v-model="form.barcode"
                          :class="{ 'is-invalid': errors.barcode }"
                          class="form-control"
                          placeholder="Barcode / Code Product"
                        />
                      </div>
                      <div v-if="errors.barcode" class="alert alert-danger">
                        {{ errors.barcode }}
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="mb-3">
                        <label for="category_id" class="fw-bold"
                          >Category</label
                        >
                        <select
                          v-model="form.category_id"
                          class="form-select"
                          :class="{ 'is-invalid': errors.category_id }"
                        >
                          <option
                            v-for="(category, index) in categories"
                            :key="index"
                            :value="category.id"
                          >
                            {{ category.name }}
                          </option>
                        </select>
                      </div>
                      <div v-if="errors.category_id" class="alert alert-danger">
                        {{ errors.category_id }}
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="mb-3">
                        <label for="title" class="fw-bold">Title</label>
                        <input
                          type="text"
                          v-model="form.title"
                          :class="{ 'is-invalid': errors.title }"
                          class="form-control"
                          placeholder="Product Title"
                        />
                      </div>
                      <div v-if="errors.title" class="alert alert-danger">
                        {{ errors.title }}
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="mb-3">
                        <label for="stock" class="fw-bold">Stock</label>
                        <input
                          type="number"
                          v-model="form.stock"
                          :class="{ 'is-invalid': errors.stock }"
                          class="form-control"
                          placeholder="Product Stock"
                        />
                      </div>
                      <div v-if="errors.stock" class="alert alert-danger">
                        {{ errors.stock }}
                      </div>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label for="description" class="fw-bold">Description</label>
                    <textarea
                      type="text"
                      v-model="form.description"
                      :class="{ 'is-invalid': errors.description }"
                      class="form-control"
                      placeholder="Description Product"
                      rows="4"
                    ></textarea>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="mb-3">
                        <label for="buy_price" class="fw-bold">Buy Price</label>
                        <input
                          type="number"
                          v-model="form.buy_price"
                          :class="{ 'is-invalid': errors.buy_price }"
                          class="form-control"
                          placeholder="Product's Buy Price"
                        />
                      </div>
                      <div v-if="errors.buy_price" class="alert alert-danger">
                        {{ errors.buy_price }}
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="mb-3">
                        <label for="sell_price" class="fw-bold">Sell Price</label>
                        <input
                          type="number"
                          v-model="form.sell_price"
                          :class="{ 'is-invalid': errors.sell_price }"
                          class="form-control"
                          placeholder="Product's Sell Price"
                        />
                      </div>
                      <div v-if="errors.sell_price" class="alert alert-danger">
                        {{ errors.sell_price }}
                      </div>
                    </div>
                  </div>
                  <div v-if="errors.description" class="alert alert-danger">
                    {{ errors.description }}
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <button
                        class="btn btn-primary shadow-sm rounded-sm"
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
export default {
  layout: LayoutApp,
  components: {
    Head,
    Link,
  },
  props: {
    errors: Object,
    categories: Array,
  },

  setup() {
    const form = reactive({
      image: "",
      title: "",
      description: "",
      category_id: '',
      buy_price: '',
      sell_price: '',
      stock: '',
      barcode: ''
    });

    //method submit
    const submit = () => {
      //send data to server
      Inertia.post(
        "/apps/products",
        {
          title: form.title,
          image: form.image,
          description: form.description,
          barcode: form.barcode,
          buy_price: form.buy_price,
          sell_price: form.sell_price,
          stock: form.stock,
          category_id: form.category_id,
        },
        {
          onSuccess: () => {
            //show sweet alert
            // console.log("1");
            Swal.fire({
              title: "Success!",
              text: "Product saved successfully.",
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
