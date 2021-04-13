@extends('layouts.app')
@section('content')
<div class="containermt-5">
<div class="row justify-content-center align-items-center">
<div class="card"style="width:24rem;">
<div class="card-header">Edit article</div>
<div class="card-body">
@if($errors->any())
<div class="alert alert-danger">
<strong>Whoops!
</strong>Thereweresomeproblemswithyourinput.<br><br>
<ul>@foreach($errors->all() as $error)
	<li>{{$error}}</li>
@endforeach
</ul></div>
@endif
<form action="{{url('articles')}}/{{$articles->id}}" method="post" enctype="multipart/form-data">
@csrf
@method('PUT')
<div class="form-group">
	<label for="title">Judul</label>
	<input type="text" class="form-control" required="required" name="title" value="{{$articles->title}}">
</br>
</div>
<div class="form-group">
	<label for="content">Content</label>
	<!-- <input type="text" class="form-control" required="required" name="content" value=""> -->
	<textarea class="form-control" name="content" required>{{$articles->content}}</textarea>
</br>
</div>
<div class="form-group">
	<label for="image">Feature Image</label>
	<input type="file" class="form-control" required="required" name="image" value="{{$articles->featured_image}}"
	></br>
	<img width="150px" src="{{asset('storage/'.$articles->featured_image)}}">
</div>
<button type="submit" class="btn btn-primary float-right">Ubah Data</button>
</form>
</div>
</div>
</div>
</div>
@endsection