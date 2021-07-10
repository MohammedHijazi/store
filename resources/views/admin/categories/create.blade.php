@extends('layouts.admin')

@section('title', 'Create New Category')

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categories</a></li>
        <li class="breadcrumb-item active">Create</li>
    </ol>
@endsection

@section('content')
    <form action="{{ route('categories.store') }}" method="post">
        @csrf
        @include('admin.categories._form',[
            'button'=>'Add',
])
    </form>
@endsection
