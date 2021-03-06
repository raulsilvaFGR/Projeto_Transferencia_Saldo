@extends('adminlte::page')

@section('title', 'Tranferir Saldo')

@section('content_header')
    <h1>Fazer Transferência</h1>
    <ol class="breadcrumb">
    	<li><a href="">Dashboard</a></li>
    	<li><a href="">Despositar</a></li>
    </ol>
@stop

@section('content')
     <div class="box">
     <div class="box-header">
         	<H3>Fazer Transferência(Informe o Recebedor)</H3>
         </div>
         <div class="box-body">

            @include('admin.includes.alerts')

         	<form method="POST" action="{{ route('confirm.transfer') }}"> 
         		{!!csrf_field() !!}

         		<div class="form-group">
         		 <input type="text"  name= "sender" placeholder="Informações Recebedor" class="from-control">
         		</div>
         		<div class="form-group">
         			<button type="submit" class="btn btn-success">Next</button >
         			
         		</div>
         	</form>
          </div>
        </div>

@stop