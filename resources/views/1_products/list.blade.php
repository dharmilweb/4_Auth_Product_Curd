@extends('layouts.app')

@section('content')

<style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/css/bootstrap.css" rel="stylesheet" />
</style>

<head>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>

<div class="container">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-apps menu-icon"></i>
            </span> Products
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('products.create') }}" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">Create</a></li>
                </ol>
            </nav>
        </div>
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    
                    @include('errors.index')
                
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th> Id </th>
                                <th> Name </th>
                                <th> Details </th>
                                <th> Image </th>
                                <th> Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($Products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td> {!! wordwrap($product->name, 20, "<br>\n",TRUE) !!} </td>
                                    <td> {!! wordwrap($product->detail, 20, "<br>\n",TRUE) !!} </td>
                                    <td width="30%" hight="60%">
                                        <a href="{{ asset('image/'. $product->image .'') }}" target="_blank">
                                            <img id="ImageId" src="{{ asset('image/'. $product->image .'') }}" alt="" style="height:130px; width:130px">
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('products.edit',$product->id) }}" class="btn btn-block btn-warning auth-form-btn">
                                            edit
                                        </a> &nbsp&nbsp
                                        <a href="{{ route('products.show',$product->id) }}" class="btn btn-block btn-warning auth-form-btn">
                                            view
                                        </a> &nbsp&nbsp
                                        <meta name="csrf-token" content="{{ csrf_token() }}">
                                        <button value="{{ $product->id }}" class="btn btn-block btn-danger userButton">
                                                delete
                                        </button> &nbsp&nbsp
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                    <div class="d-flex justify-content-end mx-5">
                        {!! $Products->links() !!}
                    </div>
            </div>
        </div>
    </div>
</div>

@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.userButton').click(function(e) {
            e.preventDefault();
            var id = $(this).val();
            var token = $("meta[name='csrf-token']").attr("content");
            
            swal({
                title: "Are you sure, you want to delete this ?",
                text: "Once deleted, you will not be able to recover this file!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                closeOnConfirm: false
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "/products/"+id,
                        type: 'DELETE',
                        data: {
                            "id": id,
                            "_token": token,
                        },
                        success: function(data){
                            swal({
                                title: 'DELETED Successfully',
                                text: 'your App data has been deleted Successfully',
                                icon: "success",
                                buttons: false,
                                timer: 1500,
                            }).then((willDelete) => {
                                window.location.reload();
                            });
                        }
                    })

                } else {
                    swal("Your file is safe!", {
                        icon: "error",
                        buttons: false,
                        timer: 1500
                    });
                }
            });

        });
    });

</script>
