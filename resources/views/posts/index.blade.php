@extends('layouts.app')
@section('title') Posts @endsection
@section('content')
        <div class="text-center">
            <a href="{{ route('posts.create') }}" type="button" class="btn btn-success">create post</a>
        </div>
       
        <table class="table mt-4">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Title</th>
                <th scope="col">Posted By</th>
                <th scope="col">Created At</th>
                <th scope="col">Action</th>
              </tr>
            </thead>

            <tbody>
                @foreach ($allPosts as $post)
                
              <tr>
                <th scope="row">{{ $post->id }}</th>
                <td>{{ $post['title'] }}</td>
                <td>{{ $post->posted_by }}</td>
                
                {{-- <td>{{ $post->created_at->addDays(30)->format('y-m-d') }}</td> --}}
                <td>{{ $post->created_at->format('y-m-d') }}</td>

                <td>
                  <a href="{{ route('posts.show',$post['id']) }}" class="btn btn-primary">view</a>
                  <a href="{{ route('posts.edit',$post['id']) }}" class="btn btn-danger">edit</a>
                  <form action="{{ route('posts.destroy',$post['id']) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                     
                    <button type="submit" class="btn btn-info" onclick="return confirm('Are you sure you want to delete this post?')" >delete</button>
                    
                  </form>
               
                </td>
              </tr>
            
                @endforeach
            </tbody>
          </table>
          @endsection
          
    