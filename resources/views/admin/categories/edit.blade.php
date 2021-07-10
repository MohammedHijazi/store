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

        <div class="form-group">
            <label for="">Category Name</label>
            <input type="text" class="form-control" name="name" value="{{$category->name}}">
        </div>
        <div class="form-group">
            <label for="">Parent</label>
            <select name="parent_id" id="parent_id" class="form-control">
                <option value="">No Parent</option>
                @foreach ($parents as $parent)
                    <option value="{{ $parent->id }}"@if($parent->id==$category->parent_id) selected @endif>{{ $parent->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="">Description</label>
            <textarea class="form-control" name="description">{{$category->description}}</textarea>
        </div>
        <div class="form-group">
            <label for="">Image</label>
            <input type="file" class="form-control" name="image">
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" id="status-active" value="active" @if(old('status', $category->status) == 'active') checked @endif>
                    <label class="form-check-label" for="status-active">
                        Active
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" id="status-draft" value="draft" @if(old('status', $category->status) == 'draft') checked @endif>
                    <label class="form-check-label" for="status-draft">
                        Draft
                    </label>
                </div>
            </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>

@endsection
