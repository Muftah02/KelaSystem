@extends('layouts.app')
@section('title') Post Details @endsection
@section('content')
        <div class="card" style="width: 18rem;">
            
            <div class="card-body">
              <h5 class="card-title">Post info</h5>
              <h4>title: {{ $post->title }}</h4>
              <p class="card-text"> <h4>descreption :</h4>{{ $post->description }}</p>
             
            </div>
          </div>
       @endsection
     