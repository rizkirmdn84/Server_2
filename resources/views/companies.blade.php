<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
        <title>Server 2</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Axios --}}
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

        {{-- JQuery --}}
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

        {{-- Bootsrap 4 --}}
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        {{-- Datatables --}}
        <link  href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
        <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

        {{-- Dropzone --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.js"></script>

        {{-- Socket.IO client --}}
        <script src="https://cdn.socket.io/4.4.1/socket.io.min.js" integrity="sha384-fKnu0iswBIqkjxrhQCTZ7qlLHOFEgNkRmK2vaO/LbTZSXdJfAu6ewRBdwHPhBo/H" crossorigin="anonymous"></script>

    </head>
<body>
    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">Rizki Company Server 2</a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      </header>
      <div class="container-fluid">
        <div class="row">
          <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="position-sticky pt-3">
              <ul class="nav flex-column">
                <li class="nav-item">
                  <a class="nav-link active btn btn-secondary" aria-current="page" href="/">
                    <span data-feather="home"></span>
                    Dashboard
                  </a>
                </li>
              </ul>
            </div>
          </nav>

          <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
              <h1 class="h2">Dashboard</h1>
            </div>
            <div class="pull-right mb-2">
                <a class="btn btn-warning" onClick="importcompanies()" href="javascript:void(0)" >Import</a>
                <a class="btn btn-primary" href="{{ url('companies/export/') }}">Export</a>
                <a class="btn btn-success" onClick="add()" href="javascript:void(0)" style="float: right;">Create Company</a>
            </div>
            <br>
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
            @endif
            <div class="table-responsive">
              <table class="table table-bordered" id="crud">
                  <thead>
                      <tr>
                        <th scope="col">No</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Address</th>
                        <th scope="col">Created at</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
              </table>
            </div>
          </main>
        </div>
      </div>

    <!-- boostrap company model -->
    <div class="modal fade" id="company-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="CompanyModal"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" id="CompanyForm" name="CompanyForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Nama</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan nama anda.." maxlength="50" >
                            </div>
                        </div>  
                        <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-12">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email anda.." maxlength="50" >
                            </div>
                        </div>
                        <div class="form-group">
                        <label for="phone" class="col-sm-2 control-label">Phone</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="phone" name="phone" placeholder="Masukkan HP anda.." maxlength="50" >
                            </div>
                        </div>
                        <div class="form-group">
                        <label for="address" class="col-sm-2 control-label">Alamat</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="address" name="address" placeholder="Masukkan alamat anda.." >
                            </div>
                        </div>
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" id="btn-save">Save changes</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
    <!-- end bootstrap model -->

    <!-- import model -->
    <div class="modal fade" id="import-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Import</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="dropzoneForm" class="dropzone" action="{{ route('dropzone.upload') }}">
                        @csrf
                    </form>
                    <br>
                    <div align="center">
                        <button type="button" class="btn btn-info" id="submit-all">Upload</button>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <!-- import model -->
    </body>
    <script type="text/javascript">
        const hostName = 'http://127.0.0.1:8000/api';
        const endpoint = {
            post: `${hostName}/store-company`,
            get: `${hostName}/edit-company`,
        };

        //websocket server
        const socketIpAddress = '127.0.0.1';
        const socketPort = '3000';
        window.socket = io(`${socketIpAddress}:${socketPort}`);

        window.reDrawDataTable = function () {
            var oTable = $('#crud').dataTable();
            oTable.fnDraw(false);
        }

        socket.on('refreshData', () => {
            reDrawDataTable();
        });

        $(document).ready( function () {
            $.ajaxSetup({
            headers: {
                'Access-Control-Allow-Origin': '*',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#crud').DataTable({
            processing: false,
            serverSide: true,
            ajax: "{{ url('list') }}",
            columns: [
                { data: 'no', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'phone', name: 'phone' },
                { data: 'address', name: 'address' },
                { data: 'created_at', name: 'created_at' },
                {data: 'action', name: 'action', orderable: false},
                ],
                order: [[0, 'desc']]
            });
        });

        function add() {
            $('#CompanyForm').trigger("reset");
            $('#CompanyModal').html("Add Company");
            $('#company-modal').modal('show');
            $('#id').val('');
        }

        function importcompanies() {
            $('#import-modal').modal('show');
        }

        function editFunc(id){
            $.ajax({
                type:"POST",
                url: "{{ url('edit-company') }}",
                data: { id: id },
                dataType: 'json',
                success: function(res) {
                    $('#CompanyModal').html("Edit Company");
                    $('#company-modal').modal('show');
                    $('#id').val(res.id);
                    $('#name').val(res.name);
                    $('#email').val(res.email);
                    $('#phone').val(res.phone);
                    $('#address').val(res.address);
                    console.log(res);
                },
                error: function(data){
                    console.log(data);
                }
            });
        }

        function deleteFunc(id){
            if (confirm(`Delete Record with id ${id}?`) == true) {
                // ajax
                $.ajax({
                    type:"POST",
                    url: "{{ url('delete-company') }}",
                    data: { id: id },
                    cache:false,
                    dataType: 'json',
                    success: function(res){
                        reDrawDataTable();

                        socket.emit('refreshData');
                    },
                    error: function(data){
                        console.log(data);
                    }
                });
            }
        }

        $('#CompanyForm').submit(function(e) {
            e.preventDefault()

            var dataCompany = {
                id: $('#id').val(),
                name: $('#name').val(),
                email: $('#email').val(),
                phone: $('#phone').val(),
                address: $('#address').val()
            };

            //contoh isi variable dataCompany

            axios.post(endpoint.post, dataCompany)
                .then(function (response) {
                    let data = response.data;

                    $("#company-modal").modal('hide');

                    reDrawDataTable();

                    socket.emit('refreshData');

                    $("#btn-save").html('Submit');
                    $("#btn-save").attr("disabled", false);

                    let errorMessages = '';
                    let newLine = '\n';

                    if (data.errors) {
                        for (const property in response.data.errors) {
                            var errors = response.data.errors[property];

                            errorMessages += `${property} : ${errors}${newLine}`;
                        }

                        alert(errorMessages);
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        });

        Dropzone.options.dropzoneForm = {
            maxFiles : 1,
            parallelUploads: 1,
            autoProcessQueue : false,
            acceptedFiles : ".xlsx,.xls",
            accept: function(file, done) {
                console.log("uploaded");
                done();
            },

            success: function(file, response){
                let errorMessages = '';
                let newLine = '\n';

                if (response) {
                    var resp = JSON.parse(response);

                    if (resp.errors) {
                        for (const property in resp.errors) {
                            var errors = resp.errors[property];

                            errorMessages += `${errors}${newLine}`;
                        }

                        alert(errorMessages);
                    }
                }
            },

            init:function(){
                var submitButton = document.querySelector("#submit-all");
                myDropzone = this;

                submitButton.addEventListener('click', function(){
                    myDropzone.processQueue();
                });

                this.on("addedfile", function() {
                    if (this.files[1]!=null){
                        this.removeFile(this.files[0]);
                    }
                });

                this.on("complete", function() {
                    if(this.getQueuedFiles().length == 0 && this.getUploadingFiles().length == 0) {
                        var _this = this;
                        _this.removeAllFiles();

                        $('#import-modal').modal('hide');

                        reDrawDataTable();

                        socket.emit('refreshData');
                    }
                });
            }
        };
    </script>
</html>