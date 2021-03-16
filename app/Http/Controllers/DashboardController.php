<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class DashboardController extends Controller
{
    public function index()
    {
        $currentUser = Auth::user();
        return view("dashboard.dashboard",['name'=>$currentUser]);
    }
    public function menu()
    {
        $currentUser = Auth::user();
        $items = DB::table('comidas')->get();
        $bebidas = DB::table('bebidas')->get();
        $postres = DB::table('postres')->get();

        return view('dashboard.menu')->with(['items'=>$items,'bebidas'=>$bebidas,'postres'=>$postres,'name'=>$currentUser->name]);
    }
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
    public function showProfile()
    {
        $_user = Auth::user();
        return view("Perfil",['name'=>$_user]);
    }
    public function edit()
    {
        $_user = Auth::user();
        return view("EditarPerfil",['name'=>$_user]);
    }
    public function editPerfil(Request $request)
    {   
        $_user = Auth::user();
        $UpUser = User::find($_user->id);
        $UpUser->name = $request->nombre;
        #$UpUser->email = $request->email;
        $UpUser->save();
       
        return view("Perfil",['name'=>$UpUser]);
    }
    public function nuevoPedido(Request $request)
    {
        $data = $request->input();
        $currentUser = Auth::user();
        //dd($currentUser->id);
        //dd($data);
        $mytime = Carbon::now();
        $mytimein7days = $mytime->addDays(7);
        //dd($mytime->toDateTimeString());
        //dd($mytimein7days->toDateTimeString());
        try{
            //creando los combos
            /*
            DB::insert('insert into carta (comidas_id,bebida_id,postre_id) values(?,?,?)',[$data["lunesc"],$data["lunesb"],$data["lunesp"]]);
            DB::insert('insert into carta (comidas_id,bebida_id,postre_id) values(?,?,?)',[$data["martesc"],$data["martesb"],$data["martesp"]]);
            DB::insert('insert into carta (comidas_id,bebida_id,postre_id) values(?,?,?)',[$data["miercolesc"],$data["miercolesb"],$data["miercolesp"]]);
            DB::insert('insert into carta (comidas_id,bebida_id,postre_id) values(?,?,?)',[$data["juevesc"],$data["juevesb"],$data["juevesp"]]);
            DB::insert('insert into carta (comidas_id,bebida_id,postre_id) values(?,?,?)',[$data["viernesc"],$data["viernesb"],$data["viernesp"]]);
            DB::insert('insert into carta (comidas_id,bebida_id,postre_id) values(?,?,?)',[$data["sabadoc"],$data["sabadob"],$data["sabadop"]]);
            DB::insert('insert into carta (comidas_id,bebida_id,postre_id) values(?,?,?)',[$data["domingoc"],$data["domingob"],$data["domingop"]]);
            */
            $lunes = DB::table('carta')->insertGetId(
                array('comidas_id' => $data["lunesc"], 'bebida_id' => $data["lunesb"], 'postre_id' => $data["lunesp"])
            );
            $martes = DB::table('carta')->insertGetId(
                array('comidas_id' => $data["martesc"], 'bebida_id' => $data["martesb"], 'postre_id' => $data["martesp"])
            );
            $miercoles = DB::table('carta')->insertGetId(
                array('comidas_id' => $data["miercolesc"], 'bebida_id' => $data["miercolesb"], 'postre_id' => $data["miercolesp"])
            );
            $jueves = DB::table('carta')->insertGetId(
                array('comidas_id' => $data["juevesc"], 'bebida_id' => $data["juevesb"], 'postre_id' => $data["juevesp"])
            );
            $viernes = DB::table('carta')->insertGetId(
                array('comidas_id' => $data["viernesc"], 'bebida_id' => $data["viernesb"], 'postre_id' => $data["viernesp"])
            );
            $sabado = DB::table('carta')->insertGetId(
                array('comidas_id' => $data["sabadoc"], 'bebida_id' => $data["sabadob"], 'postre_id' => $data["sabadop"])
            );
            $domingo = DB::table('carta')->insertGetId(
                array('comidas_id' => $data["domingoc"], 'bebida_id' => $data["domingob"], 'postre_id' => $data["domingop"])
            );
            //creando elemento pedido-comida
            $pedido_comida = DB::table('pedidos_comida')->insertGetId(
                array('Lunes' => $lunes,'Martes' => $martes,'Miercoles' => $miercoles,'Jueves' => $jueves,'Viernes' => $viernes,'Sabado' => $sabado,'Domingo' => $domingo)
            );
            //creando el pedido
            $pedido = DB::table('pedidos')->insertGetId(
                array('Fecha_Inicio' => $mytime->format('d-m-Y'), 'Fecha_Final' => $mytimein7days->format('d-m-Y'), 'user_id' => $currentUser->id, 'id_pedidos_comida' => $pedido_comida)
            );
            //DB::insert('insert into pedidos_comida (Lunes, Martes, Miercoles, Jueves, Viernes, Sabado, Domingo) values (?, ?, ?, ?, ?, ?, ?)',[$data["lunes"],$data["martes"],$data["miercoles"],$data["jueves"],$data["viernes"],$data["sabado"],$data["domingo"]]);

            return redirect('/dashboard')->with('status',"Insert successfully");
        }
        catch(Exception $e){
            return redirect('/menu')->with('failed',"operation failed");
        }
    }
}
