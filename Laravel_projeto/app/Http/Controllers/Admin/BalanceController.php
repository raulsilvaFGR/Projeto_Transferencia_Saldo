<?php

namespace App\Http\Controllers\Admin;
use App\User;     
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\Historic;
use App\Http\Requests\ValidacaoDinheiroFormRequest;


class BalanceController extends Controller
{   

     private $totalPage = 2;
    public function index()
    {
    	//dd(auth()->user()->balance()->get());

    	$balance = auth()->user()->balance;
    	$amount = $balance ? $balance->amount: 0;

    	return view('admin.balance.index', compact('amount'));
    }

    public function desposit()
    {
    	return view('admin.balance.deposit');
    }

    public function  depositStore(ValidacaoDinheiroFormRequest $request)
    {
    	
    	$balance = auth()->user()->balance()->firstOrCreate([]);
        $response = $balance->deposit($request->value);   

            if ($response['success'])
                 return redirect()
                                ->route('admin.balance')
                                ->with ('success',$response['message']);
            return redirect()
                        ->back()
                        ->with('error',$response['message']);                                
    } 

        public function withdraw()
        {
            return view('admin.balance.withdraw');

        }

    public function  withdrawStore(ValidacaoDinheiroFormRequest $request)
    {
        $balance = auth()->user()->balance()->firstOrCreate([]);
        $response = $balance->withdraw($request->value);   

            if ($response['success'])
                 return redirect()
                                ->route('admin.balance')
                                ->with('success', $response['message']);
            return redirect()
                        ->back()
                        ->with('error', $response['message']);   

    }    

    public function transfer()
    {
        return view('admin.balance.transfer');
    }     

    public function confirmar(Request $request, User $user)
    {
        
        if (!$sender = $user->getSender($request->sender)) 
        return redirect()
               ->back()
               ->with('error', 'ussuario nao foi encontrado');

               if ($sender->id  === auth()->user()->id)
                         return redirect()
                                ->back()
                                ->with('error', 'Escolha outro ID!');

                 $balance = auth()->user()->balance;               

               return view('admin.balance.transfer-conf' , compact('sender', 'balance'));       
       
             
       
    }


    public function transferStore(ValidacaoDinheiroFormRequest $request, User $user)
    {
          if(!$sender = $user->find($request->sender_id))
            return redirect()
                    -> route('balance.transfer')
                    ->  with('warning', 'N encontrado!');

         $balance = auth()->user()->balance()->firstOrCreate([]);
         $response = $balance->transfer($request->value, $sender);   

            if ($response['success'])
                 return redirect()
                                ->route('admin.balance')
                                ->with('success', $response['message']);
            return redirect()
                        ->route('balance.transfer')
                        ->with('error', $response['message']); 
    }


     public function historic(Historic $historic)
    {
        $historics  =  auth()->user() 
                               ->historics()
                               ->with(['userSender'])
                               ->paginate($this->totalPage);
          $types = $historic->type();                      

        return view('admin.balance.historic', compact('historics', 'types'));
}
 
    public function searchHistoric(Request $request, Historic $historic)
    {
        $dataForm = $request->except('_token');

        $historics = $historic->search($dataForm, $this->totalPage);

         $types = $historic->type(); 
         return view('admin.balance.historic', compact('historics', 'types', 'dataForm'));

    }

}
