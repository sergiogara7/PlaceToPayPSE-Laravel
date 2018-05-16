@extends('layouts.main')
@section('title','Crear')

@section('content')
		@if ($errors->any())
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif
		@if(isset($tipos) and isset($bancos))
			{!! Form::open(['route'=>'inicio.create']) !!}
			<div class="form-group">
				{!! Form::label('type','indique el tipo de cuenta con la cual realizara el pago') !!}
				<br>
				{!! Form::select('type',$tipos,null,['class'=>'form-control','required']) !!}
			</div>
			<div class="form-group">
				{!! Form::label('bank','Selecciono la entidad financiera con la cual desea realizar el pago') !!}
				<br>
				{!! Form::select('bank',$bancos,null,['class'=>'form-control','required']) !!}
			</div>
			<div class="text-center form-group">
				{!! Form::submit('Clic para continuar con el pago',['class'=>'btn btn-info']) !!}
			</div>
			{!! Form::close() !!}
		@endif	
@endsection
