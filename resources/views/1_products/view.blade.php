@extends('layouts.app')
@section('content')

<div class="container">    
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> @if(!isset($Products)) Create Product @else Product Deatils @endif </h3>
            <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Form</a></li>
                <li class="breadcrumb-item active" aria-current="page">App elements</li>
            </ol>
            </nav>
        </div>
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    
                    @include('errors.index')
                    
                    <form class="forms-sample" role="form" method="post" action="{{route('products.store')}}"  enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="exampleInputName1">Name</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Name" name="name" value="@if(isset($Products) && $Products->name){{$Products->name}}@endif" @if(isset($Products) && $Products->name) readonly @endif>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputName1">Details</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Detail" name="detail" value="@if(isset($Products) && $Products->detail){{$Products->detail}}@endif" @if(isset($Products) && $Products->detail) readonly @endif>
                        </div>
                        
                        @if(!isset($Products))
                            <div>
                                <div class="mb-3">
                                <label for="formFileMultiple" class="form-label">File upload</label>
                                <input class="form-control file-upload-info data" type="file" id="formFileMultiple" name="image">
                                </div>
                            </div><br>
                        @endif

                        @if(isset($Products) && $Products->image)
                            <br>
                            <div>
                                <a href="@if(isset($Products) && $Products->image){{ asset('image/'. $Products->image .'')}}@endif" target="_blank">
                                    <img id="ImageId" src="@if(isset($Products) && $Products->image){{ asset('image/'. $Products->image .'')}}@endif" alt="wrong image path" style="hight:130px; width:130px">
                                </a>
                            </div><br>
                        @endif

                        @if(!isset($Products))
                            <button type="submit" class="btn btn-primary me-2">Submit</button>
                        @endif
                        <a href="{{ url()->previous() }}" class="btn btn-light">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
