<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1">
                            <a href="#" @click="loadStockItem()">
                                <i class="fas fa-warehouse"></i>
                            </a>
                        </span>

                        <div class="info-box-content">
                            <span class="info-box-text">
                                Restock Barang Gudang
                            </span>
                            <span class="info-box-number">{{totalStock}}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-danger elevation-1">
                            <a href="#" @click="loadItemDisplay()">
                                <i class="fas fa-store"></i>
                            </a>
                        </span>

                        <div class="info-box-content">
                            <span class="info-box-text">Restock Barang Display</span>
                            <span class="info-box-number">{{totalDisplay}}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->

                <!-- fix for small devices only -->
                <div class="clearfix hidden-md-up" ></div>

                <div class="col-12 col-sm-6 col-md-3" v-if="!$gate.isAdmin()">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1">
                            <a href="#" @click="loadTransaction()">
                                <i class="fas fa-chart-line"></i>
                            </a>
                        </span>

                        <div class="info-box-content">
                            <span class="info-box-text">Pendapatan</span>
                            <span class="info-box-number">{{`${totalTransaction} - ${dateFormat(today)}`}}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3" v-if="!$gate.isAdmin()">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-warning elevation-1">
                            <a href="#" @click="loadOrder()">
                                <i class="fas fa-exclamation-circle"></i>
                            </a>
                        </span>

                        <div class="info-box-content">
                            <span class="info-box-text">Pengajuan Pengadaan</span>
                            <span class="info-box-number">{{totalOrder}}</span>
                        </div>
                    <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
            <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row">
                <!-- Left col -->
                <div class="col-md-8">
                    <!-- MAP & BOX PANE -->
                    <div class="card" v-if="shownTable==='Stok Gudang'">
                    <div class="card-header">
                        <h3 class="card-title">Restock Barang Gudang</h3>

                        <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <div class="table-responsive">
                        <table class="table m-0">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Sisa Barang</th>
                                    <th>Tanggal Expire</th>
                                    <th>Status Gudang</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="stock in filteredStockItems()" :key="stock.id">
                                    <td>{{stock.name}}</td>
                                    <td>{{stock.balance}}</td>
                                    <td>{{dateFormat(stock.expired_date)}}</td>
                                    <td>{{status(stock.status)}}</td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                    </div>
                <!-- /.card-body -->
                    <div class="card" v-if="shownTable==='Stok Display'">
                    <div class="card-header">
                        <h3 class="card-title">Restock Barang Display</h3>

                        <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <div class="table-responsive">
                        <table class="table m-0">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Jumlah Stok</th>
                                    <th>Tanggal Expire</th>
                                    <th>Status Display</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in filteredDisplayItems()" :key="item.id">
                                    <td>{{item.name}}</td>
                                    <td>{{item.amount}}</td>
                                    <td>{{dateFormat(item.expired_date)}}</td>
                                    <td>{{displayStatus(item.status)}}</td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    </div>

                    <div class="card" v-if="shownTable==='Transaksi'">
                    <div class="card-header">
                        <h3 class="card-title">Transaksi</h3>

                        <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <div class="table-responsive">
                        <table class="table m-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Waktu Transaksi </th>
                                    <th>Total Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="transaction in transactions" :key="transaction.id">
                                    <td>{{transaction.id}}</td>
                                    <td>{{timeFormat(transaction.created_at)}}</td>
                                    <td>{{transaction.total_price}}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Total transaksi</td>
                                    <td>{{totalTransaction}}</td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    </div>

                    <div class="card" v-if="shownTable==='Pengadaan'">
                    <div class="card-header">
                        <h3 class="card-title">Pengadaan Barang</h3>

                        <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <div class="table-responsive">
                        <table class="table m-0">
                            <thead>
                                <tr>
                                    <th>ID Pengadaan</th>
                                    <th>Tanggal Pengisian</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="order in filteredOrders()" :key="order.id">
                                    <td><a href="#" @click="openDetail(order.id)">{{order.id}}</a></td>
                                    <td>{{dateFormat(order.created_at)}}</td>
                                    <td>{{orderStatus(order.status)}}</td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    </div>
            </div>
            <!-- /.col -->
            </div>
            <!-- /.row -->
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

        </div><!--/. container-fluid -->
    </section>
</template>

<script>
    export default {
        data () {
            return {
                stockItems : {},
                displayItems: {},
                transactions: {},
                orders: {},
                totalTransaction: 0,
                totalDisplay: 0,
                totalStock: 0,
                totalOrder: 0,
                details:{},
                today: new Date(),
                shownTable: '',
            }
        },

        methods: {
            openDetail(id) {
                this.loadDetails(id);
                $('#showDetail').modal('show');
            },

            loadDetails(id) {
                axios.get(`api/orders/${id}/details`).then(({ data }) => (this.details = data.data));
            },

            loadStockItem() {
                this.shownTable = 'Stok Gudang'
            },

            loadItemDisplay() {
                this.shownTable = 'Stok Display'
            },

            loadTransaction() {
                this.shownTable = 'Transaksi'
            },

            loadOrder() {
                this.shownTable = 'Pengadaan'
            },

            loadOrderItems() {
                axios.get("/api/orders/items").then(({ data }) => {
                    this.orders.items = data.data
                });
            },

            dateFormat(dateInput) {
                const date = new Date(dateInput);
                let ye = new Intl.DateTimeFormat('id', { year: 'numeric' }).format(date);
                let mo = new Intl.DateTimeFormat('id', { month: 'long' }).format(date);
                let da = new Intl.DateTimeFormat('id', { day: '2-digit' }).format(date);
                return `${da} ${mo} ${ye}`;
            },

            dateHeader(dateInput) {
                const date = new Date(dateInput);
                let ye = new Intl.DateTimeFormat('id', { year: 'numeric' }).format(date);
                let mo = new Intl.DateTimeFormat('id', { month: '2-digit' }).format(date);
                let da = new Intl.DateTimeFormat('id', { day: '2-digit' }).format(date);
                return `${da}/${mo}/${ye}`;
            },

            timeFormat(dateInput) {
                const date = new Date(dateInput);
                let ho = new Intl.DateTimeFormat('id', { hour: 'numeric' }).format(date);
                let mi = new Intl.DateTimeFormat('id', { minute: 'numeric' }).format(date);
                let se = new Intl.DateTimeFormat('id', { second: 'numeric' }).format(date);
                return `${ho}:${mi} ${se}`;
            },

            status(statusId) {
                switch(statusId) {
                    case 1:
                        return 'Tersedia'
                    case 2:
                        return 'Segera Melakukan Pengadaan'
                    default:
                        return ''
                }
            },

            displayStatus(statusId) {
                switch(statusId) {
                    case 1:
                        return 'Barang Tersedia'
                    case 2:
                        return 'Stok Habis'
                    case 3:
                        return 'Kadaluarsa'
                    default:
                        return ''
                }
            },

            orderStatus(statusId) {
                switch (statusId) {
                    case 1:
                        return 'Diterima';
                    case 2:
                        return 'Sedang Diajukan'
                    default:
                        return ''
                }
            },

            filteredStockItems() {
                if (!this.stockItems.data) return [];
                return this.stockItems.data.filter(item => item.status !== 1);
            },

            filteredDisplayItems() {
                if (!this.displayItems.data) return [];
                return this.displayItems.data.filter(item => item.status !== 1);
            },

            filteredOrders() {
                if (!this.orders.data) return [];
                return this.orders.data.filter(order => order.status !== 1);
            },

            getTotalTransaction() {
                this.totalTransaction = 0;
                this.transactions.map(transaction => this.totalTransaction += transaction.total_price);
            }
        },

        mounted() {
            console.log('Component mounted.')
        },

        created() {
            this.$Progress.start();

            axios.get("/api/stockItems").then(({ data }) => {
                this.stockItems = data.data
                this.totalStock = this.filteredStockItems().length
            });
            axios.get("/api/displayItems").then(({ data }) => {
                this.displayItems = data.data
                this.totalDisplay = this.filteredDisplayItems().length
            });
            axios.get("/api/transactions/today").then(({ data }) => {
                this.transactions = data.data
                this.getTotalTransaction()
            });
            axios.get("/api/orders").then(({ data }) => {
                this.orders = data.data
                this.totalOrder = this.filteredOrders().length
            });

            this.$Progress.finish();
        }
    }
</script>
