<template>
  <section class="content">
    <div class="container-fluid">
        <div class="row">

          <div class="col-12">
        
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Stok Gudang</h3>

                <div class="card-tools" v-if="$gate.isAdmin()">
                  
                  <button type="button" class="btn btn-sm btn-primary" @click="newModal">
                      <i class="fa fa-plus-square"></i>
                      Tambah Stok Gudang
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>Nama Barang</th>
                      <th>Jumlah Barang Masuk</th>
                      <th>Jumlah Barang Keluar</th>
                      <th>Sisa Barang</th>
                      <th>Tanggal Expire</th>
                      <th>Status Gudang</th>
                      <th>Tanggal Update</th>
                      <th v-if="$gate.isAdmin()">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                     <tr v-for="stock in stockItems.data" :key="stock.id">

                      <td class="text-capitalize">{{stock.name}}</td>
                      <td>{{stock.item_in}}</td>
                      <td>{{stock.item_out}}</td>
                      <td>{{stock.balance}}</td>
                      <td>{{dateFormat(stock.expired_date)}}</td>
                      <td>{{stock.status}}</td>
                      <td>{{dateFormat(stock.updated_at)}}</td>
                      <td v-if="$gate.isAdmin()">

                        <a href="#" @click="editModal(stock)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        <a href="#" @click="deleteStockItem(stock.id)">
                            <i class="fa fa-trash red"></i>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="stockItems" @pagination-change-page="getResults"></pagination>
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
                    <h5 class="modal-title" v-show="!editmode">Tambah Stok Gudang</h5>
                    <h5 class="modal-title" v-show="editmode">Update Stok Gudang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- <form @submit.prevent="createUser"> -->

                <form @submit.prevent="editmode ? updateCategory() : createCategory()">
                    <div class="modal-body">
                        <div class="form-group">

                            <label>Nama barang</label>
                            <select class="form-control" v-model="form.itemId">
                              <option v-for="item in items.data" :key="item.id" :value="item.id">{{item.name}}</option>
                            </select>
                            <has-error :form="form" field="name"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Jumlah barang masuk</label>
                            <input v-model="form.itemIn" type="text" name="itemIn"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('itemIn') }">
                            <has-error :form="form" field="itemIn"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Jumlah barang keluar</label>
                            <input v-model="form.itemOut" type="text" name="itemOut"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('itemOut') }">
                            <has-error :form="form" field="itemOut"></has-error>
                        </div>
                        <div class="form-group" v-show="editmode">
                            <label>Status</label>
                            <select class="form-control" v-model="form.status">
                              <option value="1">Status 1</option>
                              <option value="2">Status 2</option>
                            </select>
                            <has-error :form="form" field="type"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Expire</label>
                            <VueDatePicker v-model="form.expiredDate" name="expiredDate" placeholder="Pilih tanggal"></VueDatePicker>
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
                stockItems : {},
                items: {},
                form: new Form({
                    id : '',
                    itemId: '',
                    itemIn: '',
                    itemOut: '',
                    expiredDate: null,
                    status: 1,
                    updated_at: '',
                })
            }
        },
        methods: {

            getResults(page = 1) {

                  this.$Progress.start();
                  
                  axios.get('/api/stockItems?page=' + page).then(({ data }) => (this.stockItems = data.data));

                  this.$Progress.finish();
            },
            updateCategory(){
                this.$Progress.start();
                this.form.put('/api/stockItems/'+this.form.id)
                .then((response) => {
                    // success
                    $('#addNew').modal('hide');
                    Toast.fire({
                      icon: 'success',
                      title: response.data.message
                    });
                    this.$Progress.finish();
                        //  Fire.$emit('AfterCreate');

                    this.loadStockItems();
                })
                .catch(() => {
                    this.$Progress.fail();
                });

            },
            deleteStockItem(id){
                Swal.fire({
                    title: 'Are you sure?',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    }).then((result) => {

                        // Send request to the server
                         if (result.value) {
                                this.form.delete('api/stockItems/'+id).then(()=>{
                                        Swal.fire(
                                        'Deleted!',
                                        'Your file has been deleted.',
                                        'success'
                                        );
                                    // Fire.$emit('AfterCreate');
                                    this.loadStockItems();
                                }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                         }
                    })
            },
            editModal(stockItem){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(stockItem);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },

            loadItems(){
                axios.get("/api/items").then(({ data }) => this.items = data.data);
            },

            loadStockItems(){
                axios.get("/api/stockItems").then(({ data }) => {this.stockItems = data.data});
            },
            
            createCategory(){

                this.form.post('/api/stockItems')
                .then((response)=>{
                    $('#addNew').modal('hide');

                    Toast.fire({
                            icon: 'success',
                            title: response.data.message
                    });

                    this.$Progress.finish();
                    this.loadStockItems();
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
            this.loadItems();
            this.loadStockItems();
            this.$Progress.finish();
        }
    }
</script>
