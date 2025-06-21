@extends('layouts.app')
@section('title') Create Post @endsection
@section('content')
  <div class="container">
    <h2 class="mb-4">Create Post</h2>
    <form action="{{ route('posts.store') }}" method="POST">
        @csrf
      <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control" id="title" name="title" >
      </div>

      <div class="mb-3">
        <label for="description" class="form-label">Descreption</label>
        <textarea class="form-control" id="description" name="description" rows="4" ></textarea>
      </div>
      <div class="mb-3">
        
        <select name="post_creator" id="" class="form-control" required>
          @foreach ($users as $user )
          <option value="{{ $user->id }}" name="post_creator">{{ $user->name}}</option>
          @endforeach
        </select>
      </div>
      
      <button type="submit" class="btn btn-primary">Post</button>
      
    </form>
  </div>
@endsection
