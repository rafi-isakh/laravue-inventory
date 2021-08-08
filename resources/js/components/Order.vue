<template>
  <section class="content">
    <div class="container-fluid">
        <div class="row">

          <div class="col-12">
        
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Pengadaan</h3>

                <div class="card-tools">
                  
                  <button type="button" class="btn btn-sm btn-primary" @click="newModal">
                      <i class="fa fa-plus-square"></i>
                      Tambah Pengadaan
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Tanggal Pengisian</th>
                      <th>Status Pengadaan</th>
                      <th v-if="$gate.isAdmin()">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                     <tr v-for="order in orders.data" :key="order.id">

                      <td><a href="#" @click="openDetail(order.id)">{{order.id}}</a></td>
                      <td>{{dateFormat(order.created_at)}}</td>
                      <td>{{status(order.status)}}</td>
                      <td v-if="$gate.isAdmin()">

                        <a href="#" @click="editModal(order)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        <a href="#" @click="deleteOrder(order.id)">
                            <i class="fa fa-trash red"></i>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="showDetail" tabindex="-1" role="dialog" aria-labelledby="showDetail" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLongTitle">Daftar Barang</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">
                    <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="item in details" :key="item.id">
                        <td>{{item.name}}</td>
                        <td>{{item.amount}}</td>
                      </tr>
                    </tbody>
                  </table>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNew" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Tambah Pengadaan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form @submit.prevent="editmode? updateOrder() : createOrder()">
                    <div class="modal-body">
                        <div class="form-group" v-show="!editmode">
                            <label>Items
                              <a href="#" @click="addItem">
                                  <i class="fas fa-plus-circle blue"></i>
                              </a>
                            </label>
                            <div class="itemInput" v-for="(input, index) in form.orderItems" v-bind:key="index">
                              <a href="#" @click="deleteItem(index)">
                                  <i class="fas fa-minus-circle red"></i>
                              </a>
                              <select class="form-control" v-model="input.item">
                                <option value="" disabled selected hidden>Pilih Item</option>
                                <option v-for="item in items.data" :key="item.id" :value="item.id">
                                  {{item.name}}
                                </option>
                              </select>
                              <input type="text" class="form-control" name="amount" placeholder="Jumlah" v-model="input.amount">
                            </div>
                        </div>
                        <div class="form-group" v-show="editmode">
                            <label>Status</label>
                            <select class="form-control" v-model="form.status">
                              <option value="1">Diterima</option>
                              <option value="2">Sedang Diajukan</option>
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

<style scoped>
.itemInput {
  margin: 20px auto;
}
</style>

<script>
    export default {
        data () {
            return {
              editmode: false,
              orders : {},
              items: {},
              details: {},
              form: new Form({
                  id : '',
                  orderItems: [
                    {
                      item: "", 
                      amount:""
                    }
                  ],
                  status: '',
              })
            }
        },
        methods: {

          addItem() {
            this.form.orderItems.push({
              item: "",
              amount: ""
            })
          },

          deleteItem(counter) {
            this.form.orderItems.splice(counter, 1);
          },

          editModal(product){
              this.editmode = true;
              this.form.reset();
              $('#addNew').modal('show');
              this.form.fill(product);
          },

          newModal(){
              this.editmode = false;
              this.form.reset();
              $('#addNew').modal('show');
          },

          openDetail(id) {
            this.loadDetails(id);
            $('#showDetail').modal('show');
          },

          loadOrders(){
            axios.get("api/orders").then(({ data }) => (this.orders = data.data));
          },

          loadItems() {
            axios.get("api/items").then(({ data }) => (this.items = data.data));
          },

          loadDetails(id) {
            axios.get(`api/orders/${id}/details`).then(({ data }) => (this.details = data.data));
          }, 
          
          createOrder(){
              this.$Progress.start();
              this.form.post('api/orders')
              .then((data)=>{
                  $('#addNew').modal('hide');

                  Toast.fire({
                        icon: 'success',
                        title: data.data.message
                    });
                  this.$Progress.finish();
                  this.loadOrders();

              })
              .catch(()=>{

                  Toast.fire({
                      icon: 'error',
                      title: 'Some error occured! Please try again'
                  });
              })
          },

          updateOrder(){
                this.$Progress.start()
                this.form.put('/api/orders/'+this.form.id)
                .then((response) => {
                    // success
                    $('#addNew').modal('hide');
                    Toast.fire({
                      icon: 'success',
                      title: response.data.message
                    });
                    this.$Progress.finish();
                        //  Fire.$emit('AfterCreate');

                    this.loadOrders();
                })
                .catch(() => {
                    this.$Progress.fail();
                });

            },

          dateFormat(dateInput) {
                const date = new Date(dateInput);
                let ye = new Intl.DateTimeFormat('id', { year: 'numeric' }).format(date);
                let mo = new Intl.DateTimeFormat('id', { month: 'long' }).format(date);
                let da = new Intl.DateTimeFormat('id', { day: '2-digit' }).format(date);
                let ho = new Intl.DateTimeFormat('id', { hour: 'numeric'}).format(date);
                let min = new Intl.DateTimeFormat('id', { minute: 'numeric'}).format(date); 
                return `${da} ${mo} ${ye} ${ho}:${min}`;
          },

          status(number) {
            switch (number) {
              case 1:
                return 'Diterima';
              case 2:
                return 'Sedang Diajukan'
              default:
                return ''
            }
          }

        },
        mounted() {
            
        },
        created() {
            this.$Progress.start();

            this.loadItems();  
            this.loadOrders();

            this.$Progress.finish();
        }
    }
</script>
