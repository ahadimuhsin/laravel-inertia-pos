<template>
<Head>
    <title>Edit Role - Aplikasi Kasir</title>
</Head>
<main class="c-main">
    <div class="container-fluid">
        <div class="fade-in">
            <div class="row">
                <div class="col-md-12">
                    <div class="card border-0 rounded-3 shadow border-top-purple">
                        <div class="card-header">
                            <span class="font-weight-bold">
                                <i class="fa fa-shield-alt"></i>
                                Edit Role
                            </span>
                        </div>
                        <div class="card-body">
                            <form @submit.prevent="submit">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="mb-3">
                        <label class="fw-bold">Full Name</label>
                        <input type="text" class="form-control" v-model="form.name" :class="{'is-invalid' : errors.name}"
                        placeholder="Full Name">
                      </div>
                      <div v-if="errors.name" class="alert alert-danger">
                        {{ errors.name }}
                      </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                        <label class="fw-bold">Email</label>
                        <input type="email" class="form-control" v-model="form.email" :class="{'is-invalid' : errors.email}"
                        placeholder="Email Address">
                      </div>
                      <div v-if="errors.email" class="alert alert-danger">
                        {{ errors.email }}
                      </div>
                    </div>
                  </div>
                  <div class="row">
                      <div class="col-md-6">
                      <div class="mb-3">
                        <label class="fw-bold">Password</label>
                        <input type="password" class="form-control" v-model="form.password" :class="{'is-invalid' : errors.password}"
                        placeholder="Password">
                      </div>
                      <div v-if="errors.password" class="alert alert-danger">
                        {{ errors.password }}
                      </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                        <label class="fw-bold">Password Confirmation</label>
                        <input type="password" class="form-control" v-model="form.password_confirmation"
                        placeholder="Password Confirmation">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                      <div class="col-md-12">
                          <div class="mb-3">
                              <label for="roles" class="fw-bold">Roles</label>
                              <br>
                              <div class="form-check form-check-inline" v-for="(role, index) in roles" :key="index">
                                  <input type="checkbox" class="form-check-input"
                                  v-model="form.roles" :value="role.name"
                                  :id="`check-${role.id}`" :class="{'is-invalid' : errors.roles}">
                                  <label :for="`check-${role.id}`" class="form-check-label">
                                      {{ role.name }}
                                  </label>
                              </div>
                          </div>
                          <div v-if="errors.roles" class="alert alert-danger">
                              {{ errors.roles }}
                          </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-md-12">
                          <button class="btn btn-primary shadow-sm rounded-sm" type="submit">
                              Update
                          </button>
                          <button class="btn btn-warning rounded-sm shadow-sm" type="reset">Reset</button>
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
import LayoutApp from '../../../Layouts/App.vue';
import {Head, Link} from '@inertiajs/inertia-vue3';
import {reactive} from 'vue';
import {Inertia} from '@inertiajs/inertia';
import Swal from 'sweetalert2'
export default {
    layout: LayoutApp,
    //components
    components: {
        Head, Link
    },
    //props
    props: {
        errors: Object,
        roles: Array,
        user: Object,
    },
    setup(props) {
        const form = reactive({
            name: props.user.name,
            email: props.user.email,
            password: "",
            password_confirmation: "",
            roles: props.user.roles.map(obj => obj.name),
        });

        //method submit
        const submit = () => {
            Inertia.put(`/apps/users/${props.user.id}`, {
                //data
                name: form.name,
                email: form.email,
                password: form.password,
                password_confirmation: form.password_confirmation,
                roles: form.roles,
            },
            {
                onSuccess: () => {
                    Swal.fire({
                        title: 'Success!',
                        text: 'User updated successfully',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            })
        }

        return {
            form, submit
        }
    },
}
</script>
