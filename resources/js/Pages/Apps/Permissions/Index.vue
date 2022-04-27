<template>
  <Head>
    <title>Permissions - Aplikasi Kasir</title>
  </Head>

  <main class="c-main">
      <div class="container-fluid">
          <div class="fade-in">
              <div class="row">
                  <div class="col-md-12">
                      <div class="card border-0 rounded-3 shadow border-top-purple">
                          <div class="card-header">
                              <span class="font-weight-bold">
                                  <i class="fa fa-key"></i> Permissions
                              </span>
                          </div>
                          <div class="card-body">
                              <form @submit.prevent="handleSearch">
                                  <div class="input-group mb-3">
                                      <input type="text" placeholder="Search By Permission Name" class="form-control"
                                      v-model="search">
                                      <button type="submit" class="btn btn-primary input-group-text">
                                          <i class="fa fa-search me-2"></i> Search
                                      </button>
                                  </div>
                              </form>
                              <table class="table table-striped table-bordered table-hover">
                                  <thead>
                                      <tr>
                                          <th scope="col">Permission Name</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      <tr v-for="(permission, index) in permissions.data" :key="index">
                                          <td>{{permission.name}}</td>
                                      </tr>
                                  </tbody>
                              </table>
                              <Pagination :links="permissions.links" align="end"></Pagination>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </main>
</template>

<script>
import LayoutApp from '../../../Layouts/App';
//import componen pagination
import Pagination from '../../../Components/Pagination.vue';
//import header dan Link
import {Head, Link} from '@inertiajs/inertia-vue3'

import {ref} from 'vue';
import {Inertia} from '@inertiajs/inertia'
export default {
    layout: LayoutApp,

    components: {
        Head, Link, Pagination
    },
    //props
    props: {
        permissions: Object
    },

    setup()
    {
        //define state search
        const search = ref('' ||
        (new URL(document.location)).searchParams.get('q'));

        //define method search
        const handleSearch = () => {
            Inertia.get('/apps/permissions', {
                q: search.value
            })
        }

        return {
            search,
            handleSearch
        }
    }
};
</script>
