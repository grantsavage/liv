@extends('layouts.app');

@section('content')
<div class="container">
	<div class="col-md-8 col-md-offset-2">
		<div class="panel panel-default">
			<div class="panel-body">
				<post :post="{{$post}}"></post>
			</div>
		</div>
	</div>
</div>
@endsection