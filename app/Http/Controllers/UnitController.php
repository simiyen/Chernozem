<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Unit;
use App\Models\Type;
use DB;

class UnitData{
    public $id;
    public $name;
    public $type;
}

class UnitController extends Controller
{
    private $route = 'unit';
    private $title = 'Birim';
    private $fillables = ['name','type'];
    private $fillables_titles = ['İsim','Tip'];
    private $fillables_types = ['text','one'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $units = Unit::all();
        
        $data =array();
        foreach($units as $item){
            $d = new UnitData();
            $d->id = $item->id;
            $d->name = $item->name;
            $d->type = $item->type->name;
            array_push($data,$d);
        }

        $my_data = array(
            'title' => $this->title,
            'route' => $this->route,
            'fillables' => $this->fillables,
            'fillables_titles' => $this->fillables_titles,
            'empty_space' => 1000,
            'data' => $data
        );
        return view($this->route.'.index')->with($my_data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //$types = Type::all();
        $types = Type::where('category_id','=','11')->get();

        //dd($actuators);
        if(count($types) == 0)
            return redirect()->route('type.create');
        $my_data = array(
            'title' => $this->title,
            'route' => $this->route,
            'fillables' => ['name', $types],
            'fillables_titles' => ['İsim','Tipler'],
            'fillables_types' => $this->fillables_types,
            'is_multiple' => false
        );        
        return view($this->route.'.create')->with($my_data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $unit = new Unit;
        $unit->name = $request->name;        
        $unit->type()->associate($request->types);     
        $unit->save();                         
        return redirect()->route($this->route.'.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Unit $unit)
    {
        //$types = Type::all();
        $types = Type::where('category_id','=','11')->get();
        
        $insertedTypesIds = array();                            
        array_push($insertedTypesIds, $unit->type->id);
        $my_data = array(
            'title' => $this->title,
            'route' => $this->route,
            'fillables' => ['name',[$types, $insertedTypesIds]],
            'fillables_titles' => ['İsim','Tipler'],  
            'fillables_types' => $this->fillables_types,          
            'data' => $unit
        );
        return view($this->route.'.edit')->with($my_data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Unit $unit)
    {
        $unit->name = $request->name;        
        $unit->type()->associate($request->types);          
        $unit->save();        
        return redirect()->route($this->route.'.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $isExistPlant = DB::table('plants')->where('unit_id', $id)->exists();
        $isExistArea = DB::table('areas')->where('unit_id', $id)->exists();
        if($isExistPlant | $isExistArea)
        {
            return redirect('/'.$this->route)
            ->with('warning', 'Bu '.$this->title.' türü diğer tablolarla ilişki olduğu için silemezsiniz.');
        }       


            Unit::find($id)->delete();
            return redirect('/'.$this->route)
                ->with('success', $this->title.' silme işlemi başarılı bir şekilde gerçekleştirildi');
        /*
        $unit->delete();
        return redirect()->route($this->route.'.index');*/
    }
}
