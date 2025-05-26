@extends('layouts.app')
@section('title') edit Post @endsection
@section('content')
  <div class="container">
    <h2 class="mb-4">Edit Post</h2>
    <form  action="{{ route('posts.update', $post->id) }}" method="POST">
        @csrf
        @method('PUT')
      <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control" id="title" name="title" value="{{ $post->title }}" required>
      </div>

      <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="4" required>{{$post->description}}</textarea>
      </div>

      <button type="submit" class="btn btn-primary">edit</button>
    </form>
  </div>
@endsection
