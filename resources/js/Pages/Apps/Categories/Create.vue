<template>
  <Head>
    <title>Add New Category - Aplikasi Kasir</title>
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
                  Tambah Category
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

                  <div class="mb-3">
                    <label for="" class="fw-bold">Category Name</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="form.name"
                      :class="{ 'is-invalid': errors.name }"
                      placeholder="Category Name"
                    />
                  </div>
                  <div v-if="errors.name" class="alert alert-danger">
                    {{ errors.name }}
                  </div>

                  <div class="mb-3">
                    <label for="" class="fw-bold">Description</label>
                    <textarea
                      class="form-control"
                      v-model="form.description"
                      :class="{ 'is-invalid': errors.name }"
                      placeholder="Category Description"
                      type="text"
                      rows="4"
                    ></textarea>
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
  },

  setup() {
    const form = reactive({
      image: "",
      name: "",
      description: "",
    });

    //method submit
    const submit = () => {
      //send data to server
      Inertia.post(
        "/apps/categories",
        {
          name: form.name,
          image: form.image,
          description: form.description,
        },
        {
          onSuccess: () => {
            //show sweet alert
            // console.log("1");
            Swal.fire({
              title: "Success!",
              text: "Category saved successfully.",
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
