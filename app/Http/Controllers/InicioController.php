<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PlaceToPayPSE\PSE;
use App\Transaction;
use Validator;

class InicioController extends Controller
{
	
	protected $pse;
	
	public function __construct()
    {
		// validacion de usuario autenticado
        $this->middleware('auth');
		// clase 
		$this->pse=new PSE();
    }
    public function index()
    {
		// obtenemos las listas
		$banks=$this->pse->getBankList();
		$accounts=$this->pse->getAccountTypeList();
		// validacion
		$validator = Validator::make(
			// valores
			array(
				'banco' => $banks,
				'tipo' => $accounts
			),
			// validacion - tipos
			array(
				'banco' => 'required|Array',
				'tipo' => 'required|Array',
			),
			// mensajes de error
			array(
				'banco.required' => 'No se pudo obtener la lista de Entidades Financieras, por favor intente más tarde',
				'banco.array' => 'No se pudo obtener la lista de Entidades Financieras, por favor intente más tarde',
				'tipo.required' => 'No se pudo obtener la lista de Tipos de Cuenta, por favor intente más tarde',
				'tipo.array' => 'No se pudo obtener la lista de Tipos de Cuenta, por favor intente más tarde',
			)
		);
		// validacion de errores
		if($validator->fails()){
			// si hay errores se retorna a la vista con errores
            return view('inicio')->withErrors($validator);
        }else{
			// si no hay errores se retorna a la vista con las listas
			return view('inicio')->with('bancos',$banks)->with('tipos',$accounts);
		}
    }
	public function create(Request $request)
    {
		// validacion
		$validator = $request->validate([
			'type' => 'required|integer|min:0|max:1',
			'bank' => 'required|integer|min:1',
		],
		[
			'type.min' => 'Debes seleccionar un tipo de cuenta para continuar',
			'type.max' => 'Debes seleccionar un tipo de cuenta para continuar',
			'type.integer' => 'Debes seleccionar un tipo de cuenta para continuar',
			'type.required' => 'Debes seleccionar un tipo de cuenta para continuar',
			'bank.min' => 'Debes seleccionar un banco para continuar',
			'bank.integer' => 'Debes seleccionar un banco para continuar',
			'bank.required' => 'Debes seleccionar un banco para continuar',
		]);
		// inicio modelo Transactio
		$transaction=new Transaction();
		// agrego valores
		$transaction->bankCode=$request->bank;
		$transaction->bankInterface=$request->type;
		$transaction->totalAmount=100000; // un valor para la prueba
		$transaction->description="Pago de prueba para placetopay - Sergio Gallego"; // descripcion de prueba
		$transaction->user_id=\Auth::id(); // id del usuario autenticado
		// guardo el registro
		$transaction->save();
		// ------ preparo variables
		// pagador
		$this->pse->setPayer($transaction->user->document,$transaction->user->documentType,$transaction->user->name,$transaction->user->lastName,$transaction->user->company,$transaction->user->email,$transaction->user->address,$transaction->user->city,$transaction->user->province,$transaction->user->phone,$transaction->user->mobile,$transaction->user->country);
		// comprador
		$this->pse->setbuyer($transaction->user->document,$transaction->user->documentType,$transaction->user->firstName,$transaction->user->name,$transaction->user->company,$transaction->user->email,$transaction->user->address,$transaction->user->city,$transaction->user->province,$transaction->user->phone,$transaction->user->mobile,$transaction->user->country);
		// receptor -- Datos de prueba
		$this->pse->setShipping('123456789','CC','Uffox','SI','uffox','sergio@uffox.com','Cra 7','Medellin','Antioquia',null,null,null);
		// ruta para retornar desde el banco
		$returnURL=route('inicio.return',$transaction->id);
		// creacion de transaccion - webservice
		if($this->pse->createTransaction($transaction->bankCode,$transaction->bankInterface,$transaction->id,$returnURL,$transaction->description,$transaction->totalAmount)){
			// si la peticion es correcta
			$result=$this->pse->result;
			// agregando los datos que se retornan a la transaccion actual
			$transaction->bankURL=$result->bankURL;
			$transaction->transactionID=$result->transactionID;
			$transaction->returnCode=$result->returnCode;
			$transaction->responseReasonText=$result->responseReasonText;
			$transaction->transactionID=$result->transactionID;
			// actualizo el registro
			$transaction->save();
			// valido el estado de la transaccion
			if($transaction->returnCode=='SUCCESS'){
				// si la transaccion fue exitosa retorno a la url devuelta por el banco
				return redirect()->away($transaction->bankURL);
			}else{
				// si la transaccion no fue exitosa retorno al formulario con los errores
				return redirect()->route('inicio.index')->withErrors(array('message' => 'No se pudo crear la transaccion, por favor intente más tarde'))->withInput($request->all());
			}
		}else{
			// si la peticion no es correcta - elimino la transaccion creada y redirecciono al formulario con errror
			$transaction->delete();
			return redirect()->route('inicio.index')->withErrors(array('message' =>$this->pse->error))->withInput($request->all());
		}
    }
	public function returnview($id)
    {
		// busco la transaccion con el id recibido
		$transaction=Transaction::find($id);
		// valido que no sea nula
		if(!is_null($transaction)){
			// valido que el estao sea nulo o pendiente
			if($transaction->validateTransactionState()){
				// si la transaccion esta nula o pendiente - realizo la peticion - webservice
				if($this->pse->getTransactionInformation($transaction->transactionID)){
					// si la peticion es correcta
					$result=$this->pse->result;
					// agregando los datos que se retornan a la transaccion
					$transaction->transactionState=$result->transactionState;
					$transaction->responseReasonText=$result->responseReasonText;
					// actualizo el registro
					$transaction->save();
					// retorno a la vista que va mostrar mas informacion sobre la transaccion
					return view('return')->with('transaction',$transaction);
				}else{
					// si no es correcta redireccion a pagina de error
					return view('error');
				}
			}else{
				// como el estado ya esta consultado - retorno a la vista que va mostrar mas informacion sobre la transaccion
				return view('return')->with('transaction',$transaction);
			}
		}else{
			// como la transaccion no existe - retorno a vista de error
			return view('error');
		}
		
    }
	
	public function list()
    {
		// estado de las transacciones que llevan mas de 7 minutes
		$this->updateTransactionPending();
		// busco las transacciones
		$transactions=Transaction::UserId(\Auth::id())->orderBy('id','ASC')->get();
		// retorno a la vista con los transacciones
		return view('list')->with('transactions',$transactions);
    }
	
	private function updateTransactionPending(){
		$transactions=Transaction::TransactionStatePending()->get();
		foreach($transactions->all() as $row){
			if($row->created_at->diffInMinutes(now())>=8){
				// valido que el estao sea nulo o pendiente
				if($row->validateTransactionState()){
					// si la transaccion esta nula o pendiente inicio la transaccion a editar
					$transaction=Transaction::find($row->id);
					//y - realizo la peticion - webservice
					if($this->pse->getTransactionInformation($transaction->transactionID)){
						// si la peticion es correcta
						$result=$this->pse->result;
						// agregando los datos que se retornan a la transaccion
						$transaction->transactionState=$result->transactionState;
						$transaction->responseReasonText=$result->responseReasonText;
						// actualizo el registro
						$transaction->save();
					}
				}
			}
		}
	}
}
