@extends('website.layouts.main')
@section('title','Dashboard')

@section('content')
<div class="pagetitle">
	<h1>Dashboard</h1>
	<nav>
	  <ol class="breadcrumb">
		<li class="breadcrumb-item active"><a href="#">Dashboard</a></li>
	  </ol>
	</nav>
  </div><!-- End Page Title -->
  <section class="section">
	<div class="row">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-body">
				  <h5 class="card-title">Keset</h5>
				</div>
			  </div>
		</div>
	</div>
  </section>

@endsection

@push('styles')
@endpush

@push('scripts')

@endpush