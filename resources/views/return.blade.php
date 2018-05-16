@extends('layouts.main')
@section('title','Ver')
@section('titulo','Informacion Transaccion # '.$transaction->id)

@section('content')
		<div class="row">
			<div class="col-sm">
				<h4>Transaccion</h4>
				<ul class="list-group mb-3">
					<li class="list-group-item d-flex justify-content-between lh-condensed">
						<div>
							<h6 class="my-0"><i class="fab fa-affiliatetheme"></i>Pago #</h6>
							<small class="text-muted">{{ $transaction->id }}</small>
						</div>
					</li>
					<li class="list-group-item d-flex justify-content-between lh-condensed">
						<div>
							<h6 class="my-0"><i class="fab fa-affiliatetheme"></i>Fecha</h6>
							<small class="text-muted">{{ $transaction->created_at }}</small>
						</div>
					</li>
					<li class="list-group-item d-flex justify-content-between lh-condensed">
						<div>
							<h6 class="my-0"><i class="fab fa-affiliatetheme"></i>Descripcion del pago</h6>
							<small class="text-muted">{{ $transaction->description }}</small>
						</div>
					</li>
					<li class="list-group-item d-flex justify-content-between lh-condensed">
						<div>
							<h6 class="my-0"><i class="fab fa-affiliatetheme"></i>Valor</h6>
							<small class="text-muted">$ {{ $transaction->totalAmount }}</small>
						</div>
					</li>
				</ul>
			</div>
			<div class="col-sm">
				<h4>Comprador</h4>
				<ul class="list-group mb-3">
					<li class="list-group-item d-flex justify-content-between lh-condensed">
						<div>
							<h6 class="my-0"><i class="fab fa-affiliatetheme"></i>Nombre</h6>
							<small class="text-muted">{{ $transaction->user->name }} {{ $transaction->user->lastName }}</small>
						</div>
					</li>
					<li class="list-group-item d-flex justify-content-between lh-condensed">
						<div>
							<h6 class="my-0"><i class="fab fa-affiliatetheme"></i>Documento</h6>
							<small class="text-muted">{{ $transaction->user->documentType }} {{ $transaction->user->document }}</small>
						</div>
					</li>
					<li class="list-group-item d-flex justify-content-between lh-condensed">
						<div>
							<h6 class="my-0"><i class="fab fa-affiliatetheme"></i>Correo</h6>
							<small class="text-muted">{{ $transaction->user->email }}</small>
						</div>
					</li>
					<li class="list-group-item d-flex justify-content-between lh-condensed">
						<div>
							<h6 class="my-0"><i class="fab fa-affiliatetheme"></i>Ubicacion</h6>
							<small class="text-muted">{{ $transaction->user->address }}, {{ $transaction->user->province }}-{{ $transaction->user->city }}</small>
						</div>
					</li>
					<li class="list-group-item d-flex justify-content-between lh-condensed">
						<div>
							<h6 class="my-0"><i class="fab fa-affiliatetheme"></i>Numeros</h6>
							<small class="text-muted">Cel: {{ $transaction->user->mobile }}, Tel: {{ $transaction->user->phone }}</small>
						</div>
					</li>

				</ul>
			</div>
			<div class="col-sm">
				<h4>Estado</h4>
				<ul class="list-group mb-3">
					<li class="list-group-item d-flex justify-content-between lh-condensed">
						<div>
							<h6 class="my-0"><i class="fab fa-affiliatetheme"></i>Transaccion enviada</h6>
							<small class="text-muted">{{ $transaction->returnCode }}</small>
						</div>
					</li>
					<li class="list-group-item d-flex justify-content-between lh-condensed">
						<div>
							<h6 class="my-0"><i class="fab fa-affiliatetheme"></i>Transaccion estado</h6>
							<small class="text-muted">{{ $transaction->transactionState }}</small>
						</div>
					</li>
					<li class="list-group-item d-flex justify-content-between lh-condensed">
						<div>
							<h6 class="my-0"><i class="fab fa-affiliatetheme"></i>Descripcion</h6>
							<small class="text-muted">{{ $transaction->responseReasonText }}</small>
						</div>
					</li>
					<li class="list-group-item d-flex justify-content-between lh-condensed">
						<div>
							<h6 class="my-0"><i class="fab fa-affiliatetheme"></i>Id de la transaccion</h6>
							<small class="text-muted">{{ $transaction->transactionID }}</small>
						</div>
					</li>
				</ul>
			</div>
		</div>
		
		@if ($errors->any())
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif
@endsection
