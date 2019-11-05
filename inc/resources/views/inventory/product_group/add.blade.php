@extends('layout.master')
@section('inventory_class','menu-open')
@section('product_class','menu-open')
@section('inv_pro_grp','menu-open')
@section('add_pro_grp','active')
@section('content')
<section class="content">
        <section class="content-header">
            @if(session()->has('type'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('type') }}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            @endif
              <h1>
                Product group
              </h1>       
            </section>
                   <div class="box">
                    <div class="box-header with-border">
                      <h3 class="box-title">Add Product group</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    {{Form::open(['action' => 'Inventory\InvProductController@product_group_submit', 'method' => 'post' , 'class' => 'form-horizontal', 'id' => 'customer_create_form'])}}
                      <div class="box-body">
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">
                            Product Group Name<span class="text-danger">*</span>
                          </label>
                          <div class="col-sm-6">
                            <input type="text" name="type" autocomplete="off" value="{{ old('type') }}" class="form-control" id="inputEmail3" placeholder="Enter Product Group Name" >
                          </div>
                          @if($errors->has('type'))
                          <p class="text-danger">{{ $errors->first('type') }}</p>
                      @endif
                        </div>
                      </div>
                      <!-- /.box-body -->
                      <div class="box-footer">
                        <div class="col-sm-3">
                            <button type="submit" id="" class="btn btn-info pull-right">Create</button>
                         </div>
                      </div>
                      <!-- /.box-footer -->
                    {{ Form::close() }}
                  </div>
                 </section>
@endsection