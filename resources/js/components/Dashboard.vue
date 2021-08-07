<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-warehouse"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Restock Barang Gudang</span>
                            <span class="info-box-number">10</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-store"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Restock Barang Display</span>
                            <span class="info-box-number">5</span>
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
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-chart-line"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Pendapatan</span>
                            <span class="info-box-number">2.500.000 - 12/12/2021</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3" v-if="!$gate.isAdmin()">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-exclamation-circle"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Pengajuan Pengadaan</span>
                            <span class="info-box-number">3</span>
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
                    <div class="card" v-if="$gate.isAdmin()">
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
                                <tr>
                                    <td>Barang A</td>
                                    <td>5</td>
                                    <td>12/12/2021</td>
                                    <td>Segera Melakukan Pengadaan</td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                <!-- /.card-body -->
                </div>
                <!-- /.card -->
                
                <div class="card" v-if="!$gate.isAdmin()">
                    <div class="card-header">
                        <h3 class="card-title">Pengajuan Pengadaan</h3>

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
                                    <th>Jumlah Barang Diajukan</th>
                                    <th>Status Pengadaan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Barang C</td>
                                    <td>10</td>
                                    <td>Sedang Diajukan</td>
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
        </div><!--/. container-fluid -->
    </section>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
