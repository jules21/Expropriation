@extends("layouts.master")
@section("title","Property types")

@section('page-header')
    <div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex align-items-center flex-wrap mr-1">
                <!--begin::Page Heading-->
                <div class="d-flex align-items-baseline mr-5">
                    <!--begin::Page Title-->
                    <h5 class="text-dark font-weight-bold my-2 mr-5">Property types</h5>
                    <!--end::Page Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                        <li class="breadcrumb-item">
                            <a href="/" class="text-muted">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a class="text-muted">Property types</a>
                        </li>
                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!--end::Page Heading-->
            </div>
            <!--end::Info-->
            <!--end::Toolbar-->
        </div>
    </div>
@stop
@section('content')
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container">
            <!--begin::Card-->
            @include('partial._alerts')
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap">
                    <div class="card-title">
                        <h3 class="kt-portlet__head-title">
                            List of Property types
                        </h3>
                    </div>
                    @can('Manage Property types')
                        <div class="card-toolbar">
                            <a href="javascript:void(0)" class="btn btn-primary"
                               data-toggle="modal"
                               data-target="#addModal" >
                                <i class="la la-plus"></i>
                                New Property type
                            </a>
                        </div>
                    @endcan
                    <!--end::Dropdown-->


                </div>
                <div class="card-body">
                    <!--begin: Datatable -->
                    <table class="table table-striped- table-hover table-checkable" id="kt_datatable1">
                        <thead>
                        <tr>
                            <td>#</td>
                            <td>Name</td>
                            <td>Name En</td>
                            <td>Action</td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($propertyTypes ?? []  as $key=>$propertyType)
                            <tr>
                                <td>{{ ++$key }}</td>

                                <td>{{ $propertyType->name }}</td>
                                <td>{{ $propertyType->name_en }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary  dropdown-toggle btn-sm"
                                                    data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">Actions</button>
                                            <div class="dropdown-menu" style="">
                                                <a class="dropdown-item dropdown-item-color btn-edit" href="#"
                                                   data-name="{{$propertyType->name}}"
                                                   data-name-en="{{$propertyType->name_en}}"
                                                   data-url="{{ route('admin.property-types.update', encryptId($propertyType->id)) }}"
                                                   data-toggle="modal"
                                                   data-target="#editModal"> <i class="la la-pencil"></i> Edit</a>
                                                <a class="dropdown-item dropdown-item-color btn-delete" href="#"
                                                   data-url="{{ route('admin.property-types.destroy', encryptId($propertyType->id))}}"><i class="la la-trash"></i> Delete</a>
                                            </div>
                                        </div>
                                    </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>


                <div class="modal fade " id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Property types</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                </button>
                            </div>
                            <form action="{{route("admin.property-types.store")}}" method="POST" id="add-property-types-form">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group" >
                                        <label for="name">Name</label>
                                        <input name="name" type="text" id="name" class="form-control">
                                    </div>
                                    <div class="form-group" >
                                        <label for="name">Name En</label>
                                        <input name="name_en" type="text" id="name_en" class="form-control">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle-o"></span> Confirm</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade " id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Edit Property type</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                </button>
                            </div>
                            <form action="" method="POST" id="edit-property-types-form">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="form-group" >
                                        <label for="name">Name</label>
                                        <input name="name" type="text" id="_name" class="form-control">
                                    </div>
                                    <div class="form-group" >
                                        <label for="name">Name En</label>
                                        <input name="name_en" type="text" id="_name_en" class="form-control">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle-o"></span> Confirm</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <form method="post" id="delete-property-types-form">
                    @csrf
                    @method('DELETE')
                </form>

            </div>
        </div>
    </div>


@stop

@section('scripts')
    <!-- Laravel Javascript Validation -->
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\ValidatePropertyType', '#add-property-types-form'); !!}
    {!! JsValidator::formRequest('App\Http\Requests\ValidatePropertyType', '#edit-property-types-form'); !!}
    <script>
        $('.nav-marketplace').addClass('menu-item-active  menu-item-open');
        $('.nav-all-zones').addClass('menu-item-active');

        $('#kt_datatable1').DataTable({
            responsive: true
        });

        $(document).on('click','.btn-edit',function(e) {
            e.preventDefault();
            $('#_name').val($(this).data('name'));
            $('#_name_en').val($(this).data('nameEn'));

            $('#edit-property-types-form').attr("action", $(this).data('url'));
        });

        $(document).on('click', '.btn-delete', function (e) {
            e.preventDefault();
            var url = $(this).data('url');
            swal.fire({
                title: 'Are you sure?',
                text: "This Property type Will be deleted.",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#06c4ff',
                confirmButtonText: 'Yes, Continue',
                cancelButtonColor: '#ff1533',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then(function (result) {
                if (result.value) {
                    $('#delete-property-types-form').attr("action", url);
                    $('#delete-property-types-form').submit();
                }
            });
        })
    </script>
@endsection
