<style scoped>
    .action-link {
        cursor: pointer;
    }

    .m-b-none {
        margin-bottom: 0;
    }
</style>

<template>
    <div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span>
                        Personal Access Clients
                    </span>

                    <a class="action-link" @click="showCreateClientForm">
                        Create New Client
                    </a>
                </div>
            </div>

            <div class="panel-body">
                <!-- Current Clients -->
                <p class="m-b-none" v-if="clients.length === 0">
                    You have not created any Personal Access clients.
                </p>

                <table class="table table-borderless m-b-none" v-if="clients.length > 0">
                    <thead>
                    <tr>
                        <th>Client ID</th>
                        <th>Name</th>
                        <th>Secret</th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="client in clients">
                        <!-- ID -->
                        <td style="vertical-align: middle;">
                            {{ client.id }}
                        </td>

                        <!-- Name -->
                        <td style="vertical-align: middle;">
                            {{ client.name }}
                        </td>

                        <!-- Secret -->
                        <td style="vertical-align: middle;">
                            <code>{{ client.secret }}</code>
                        </td>

                        <!-- Edit Button -->
                        <td style="vertical-align: middle;">
                            <a class="action-link" @click="edit(client)">
                                Edit
                            </a>
                        </td>

                        <!-- Delete Button -->
                        <td style="vertical-align: middle;">
                            <a class="action-link text-danger" @click="destroy(client)">
                                Delete
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Create Client Modal -->
        <div class="modal fade" id="modal-create-personal-access-client" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button " class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                        <h4 class="modal-title">
                            Create Client
                        </h4>
                    </div>

                    <div class="modal-body">
                        <!-- Form Errors -->
                        <div class="alert alert-danger" v-if="createForm.errors.length > 0">
                            <p><strong>Whoops!</strong> Something went wrong!</p>
                            <br>
                            <ul>
                                <li v-for="error in createForm.errors">
                                    {{ error }}
                                </li>
                            </ul>
                        </div>

                        <!-- Create Client Form -->
                        <form class="form-horizontal" role="form">
                            <!-- User-ID -->
                            <div class="form-group">
                                <label class="col-md-3 control-label">User-ID</label>

                                <div class="col-md-7">
                                    <input id="create-personal-access-client-userid" type="text" class="form-control"
                                           @keyup.enter="store" v-model="createForm.user_id" disabled>

                                    <span class="help-block">
                                        User-ID
                                    </span>
                                </div>
                            </div>

                            <!-- Name -->
                            <div class="form-group">
                                <label class="col-md-3 control-label">Name</label>

                                <div class="col-md-7">
                                    <input id="create-personal-access-client-name" type="text" class="form-control"
                                           @keyup.enter="store" v-model="createForm.name">

                                    <span class="help-block">
                                        Something your users will recognize and trust.
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                        <button type="button" class="btn btn-primary" @click="store">
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Client Modal -->
        <div class="modal fade" id="modal-edit-personal-access-client" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button " class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                        <h4 class="modal-title">
                            Edit Client
                        </h4>
                    </div>

                    <div class="modal-body">
                        <!-- Form Errors -->
                        <div class="alert alert-danger" v-if="editForm.errors.length > 0">
                            <p><strong>Whoops!</strong> Something went wrong!</p>
                            <br>
                            <ul>
                                <li v-for="error in editForm.errors">
                                    {{ error }}
                                </li>
                            </ul>
                        </div>

                        <!-- Edit Client Form -->
                        <form class="form-horizontal" role="form">
                            <!-- User-ID -->
                            <div class="form-group">
                                <label class="col-md-3 control-label">User-ID</label>

                                <div class="col-md-7">
                                    <input id="edit-personal-access-client-userid" type="text" class="form-control"
                                           @keyup.enter="store" v-model="createForm.user_id" disabled>

                                    <span class="help-block">
                                        User-ID
                                    </span>
                                </div>
                            </div>

                            <!-- Name -->
                            <div class="form-group">
                                <label class="col-md-3 control-label">Name</label>

                                <div class="col-md-7">
                                    <input id="edit-personal-access-client-name" type="text" class="form-control"
                                           @keyup.enter="update" v-model="editForm.name">

                                    <span class="help-block">
                                        Something your users will recognize and trust.
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                        <button type="button" class="btn btn-primary" @click="update">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        /*
         * The component's data.
         */
        data() {
            return {
                clients: [],

                createForm: {
                    errors: [],
                    name: '',
                    user_id: ''
                },

                editForm: {
                    errors: [],
                    name: '',
                    user_id: ''
                }
            };
        },

        /**
         * Prepare the component (Vue 1.x).
         */
        ready() {
            this.prepareComponent();
        },

        /**
         * Prepare the component (Vue 2.x).
         */
        mounted() {
            this.prepareComponent();
        },

        methods: {
            /**
             * Prepare the component.
             */
            prepareComponent() {
                this.getClients();

                $('#modal-create-personal-access-client').on('shown.bs.modal', () => {
                    $('#create-personal-access-client-name').focus();
                });

                $('#modal-edit-personal-access-client').on('shown.bs.modal', () => {
                    $('#edit-personal-access-client-name').focus();
                });
            },

            /**
             * Get all of the OAuth clients for the user.
             */
            getClients() {
                axios.get('/oauth/extended/personal-access-clients')
                    .then(response => {
                        this.clients = response.data['clients'];
                        this.createForm.user_id = response.data['userId'];
                        this.editForm.user_id = response.data['userId'];
                    });
            },

            /**
             * Show the form for creating new clients.
             */
            showCreateClientForm() {
                $('#modal-create-personal-access-client').modal('show');
            },

            /**
             * Create a new OAuth client for the user.
             */
            store() {
                this.persistClient(
                    'post', '/oauth/extended/personal-access-clients',
                    this.createForm, '#modal-create-personal-access-client'
                );
            },

            /**
             * Edit the given client.
             */
            edit(client) {
                this.editForm.id = client.id;
                this.editForm.name = client.name;

                $('#modal-edit-personal-access-client').modal('show');
            },

            /**
             * Update the client being edited.
             */
            update() {
                this.persistClient(
                    'put', '/oauth/extended/personal-access-clients/' + this.editForm.id,
                    this.editForm, '#modal-edit-personal-access-client'
                );
            },

            /**
             * Persist the client to storage using the given form.
             */
            persistClient(method, uri, form, modal) {
                form.errors = [];

                axios[method](uri, form)
                    .then(response => {
                        this.getClients();

                        form.user_id = '';
                        form.name = '';
                        form.errors = [];

                        $(modal).modal('hide');
                    })
                    .catch(error => {
                        if (typeof error.response.data === 'object') {
                            form.errors = _.flatten(_.toArray(error.response.data));
                        } else {
                            form.errors = ['Something went wrong. Please try again.'];
                        }
                    });
            },

            /**
             * Destroy the given client.
             */
            destroy(client) {
                axios.delete('/oauth/extended/personal-access-clients/' + client.id)
                    .then(response => {
                        this.getClients();
                    });
            }
        }
    }
</script>
