<template>
  <section class="content">
    <div class="container-fluid">
        <div class="row">

          <div class="col-12">
        
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Stok Display</h3>

                <div class="card-tools" v-if="$gate.isAdmin()">
                  
                  <button type="button" class="btn btn-sm btn-primary" @click="newModal">
                      <i class="fa fa-plus-square"></i>
                      Tambah Stok Display
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>Nama Barang</th>
                      <th>Jumlah Stok Barang</th>
                      <th>Tanggal Expire</th>
                      <th>Status Display</th>
                      <th>Tanggal Update</th>
                      <th v-if="$gate.isAdmin()">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                     <tr v-for="item in displayItems.data" :key="item.id">

                      <td class="text-capitalize">{{item.name}}</td>
                      <td>{{item.amount}}</td>
                      <td>{{item.expired_date}}</td>
                      <td>{{item.status}}</td>
                      <td>{{dateFormat(item.updated_at)}}</td>
                      <td v-if="$gate.isAdmin()">

                        <a href="#" @click="editModal(item)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        <a href="#" @click="deleteDisplayItem(item.id)">
                            <i class="fa fa-trash red"></i>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="displayItems" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>


        <!-- <div v-if="!$gate.isAdmin()">
            <not-found></not-found>
        </div> -->

        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNew" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode">Tambah Stok Display</h5>
                    <h5 class="modal-title" v-show="editmode">Update Stok Display</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- <form @submit.prevent="createUser"> -->

                <form @submit.prevent="editmode ? updateCategory() : createCategory()">
                    <div class="modal-body">
                        <div class="form-group">

                            <label>Nama barang</label>
                            <select class="form-control" v-model="form.stockItemId">
                                <option v-for="item in stocks.data" :key="item.id" :value="item.id">
                                  {{`${item.name} - (expire: ${dateFormat(item.expired_date)})`}}
                                </option>
                            </select>
                            <has-error :form="form" field="stockItemId"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Jumlah stok barang</label>
                            <input v-model="form.amount" type="text" name="amount"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('amount') }">
                            <has-error :form="form" field="amount"></has-error>
                        </div>
                        <div class="form-group" v-show="editmode">
                            <label>Status</label>
                            <select class="form-control" v-model="form.status">
                              <option value="1">Tersedia</option>
                              <option value="2">Habis</option>
                            </select>
                            <has-error :form="form" field="status"></has-error>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                        <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </div>
  </section>
</template>

<script>
    export default {
        data () {
            return {
                editmode: false,
                displayItems : {},
                stocks: {},
                form: new Form({
                    id : '',
                    stockItemId: '',
                    amount: '',
                    status: 1,
                    updated_at: '',
                })
            }
        },
        methods: {

            getResults(page = 1) {

                  this.$Progress.start();
                  
                  axios.get('/api/displayItems?page=' + page).then(({ data }) => (this.displayItems = data.data));

                  this.$Progress.finish();
            },

            updateCategory(){
                this.$Progress.start()
                this.form.put('/api/displayItems/'+this.form.id)
                .then((response) => {
                    // success
                    $('#addNew').modal('hide');
                    Toast.fire({
                      icon: 'success',
                      title: response.data.message
                    });
                    this.$Progress.finish();
                        //  Fire.$emit('AfterCreate');

                    this.loadDisplayItems();
                })
                .catch(() => {
                    this.$Progress.fail();
                });

            },

            deleteDisplayItem(id){
                Swal.fire({
                    title: 'Are you sure?',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    }).then((result) => {

                        // Send request to the server
                         if (result.value) {
                                this.form.delete('api/displayItems/'+id).then(()=>{
                                        Swal.fire(
                                        'Deleted!',
                                        'Your file has been deleted.',
                                        'success'
                                        );
                                    this.loadDisplayItems();
                                }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                         }
                    })
            },
            editModal(displayItem){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(displayItem);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },

            loadDisplayItems(){
                axios.get("/api/displayItems").then(({ data }) => this.displayItems = data.data);
            },

            loadStockItems(){
                axios.get("/api/stockItems").then(({ data }) => {this.stocks = data.data});
            },
            
            createCategory(){
                this.form.post('/api/displayItems')
                .then((response)=>{
                    $('#addNew').modal('hide');

                    Toast.fire({
                            icon: 'success',
                            title: response.data.message
                    });

                    this.$Progress.finish();
                    this.loadDisplayItems();
                })
                .catch(()=>{
                    Toast.fire({
                        icon: 'error',
                        title: 'Some error occured! Please try again'
                    });
                })
            },
            dateFormat(dateInput) {
                const date = new Date(dateInput);
                let ye = new Intl.DateTimeFormat('id', { year: 'numeric' }).format(date);
                let mo = new Intl.DateTimeFormat('id', { month: 'long' }).format(date);
                let da = new Intl.DateTimeFormat('id', { day: '2-digit' }).format(date);
                return `${da} ${mo} ${ye}`;
            }

        },
        mounted() {
            console.log('Component mounted.')
        },
        created() {

            this.$Progress.start();
            this.loadDisplayItems();
            this.loadStockItems();
            this.$Progress.finish();
        }
    }
</script>
