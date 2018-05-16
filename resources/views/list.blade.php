@extends('layouts.main')
@section('title','Transacciones')

@section('content')
	<div class="card mb-3">
		<div class="card-header"><i class="fa fa-table"></i> Tabla Transacciones <a href="{{ route('inicio.index') }}" class="btn btn-success btn-sm">Crear nueva transaccion</a></div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th>Id</th>
							<th>Fecha</th>
							<th>TransaccionId</th>
							<th>Estado</th>
							<th>Acciones</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th>Id</th>
							<th>Fecha</th>
							<th>TransaccionId</th>
							<th>Estado</th>
							<th>Acciones</th>
						</tr>
					</tfoot>
					<tbody>
					@foreach($transactions as $row)
						<tr>
							<td>{{ $row->id }}</td>
							<td>{{ $row->created_at }}</td>
							<td>{{ $row->transactionID }}</td>
							<td>{{ $row->transactionState }}</td>
							<td>
								<div class="btn-group btn-group-sm" role="group">
									<a href="{{ route('inicio.return',$row->id) }}" class="btn btn-primary btn-sm">Ver mas</a>
								</div>
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
		</div>
        <div class="card-footer small text-muted">
		</div>
	</div>
@endsection
