@extends('layouts.admin')

@section('title', 'Edit Category')

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categories</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
@endsection

@section('content')
    <form action="{{ route('categories.update',$category->id) }}" method="post">
        @method('put')
        @csrf
    @include('admin.categories._form',[
            'button'=>'Update',
])
    </form>
@endsection
